<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Helpers\LogHelper;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:4|max:50|alpha_dash|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh pengguna lain.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, tanda hubung, dan garis bawah.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid(
                $request,
                'Registrasi Gagal (Salah Isi)',
                $validator->errors(),
                $request->input('username') ?: 'calon_peserta',
                'calon_peserta'
            );
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $user = User::create([
            'username' => $validated['username'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 'calon_peserta',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        LogHelper::success($request, 'Registrasi Berhasil', "Calon peserta baru berhasil mendaftar: {$user->username} ({$user->email})");

        return redirect('/pendaftaran')->with('success', 'Akun berhasil dibuat! Silakan lengkapi berkas dan formulir pendaftaran magang Anda.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'remember' => 'nullable|boolean',
        ], [
            'username.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Login Gagal (Salah Isi)', $validator->errors(), $request->input('username'));
            return back()->withErrors($validator)->withInput();
        }

        $remember = $request->boolean('remember');

        // Dukung login dengan username ATAU email
        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $attemptData = [
            $loginField => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($attemptData, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            LogHelper::success($request, 'Login Berhasil', "User {$user->username} ({$user->role}) berhasil login ke sistem.");

            return $this->redirectBasedOnRole($user->role);
        }

        // Cari tahu apakah username terdaftar atau password yang keliru
        $userExists = User::where('username', $request->username)->orWhere('email', $request->username)->first();
        $reason = $userExists
            ? "Password salah untuk user '{$request->username}' (Role: {$userExists->role})."
            : "Akun dengan identifier '{$request->username}' tidak terdaftar.";

        LogHelper::fail(
            $request,
            'Login Gagal',
            "Percobaan login gagal: {$reason}",
            $request->username,
            $userExists ? $userExists->role : 'guest'
        );

        return back()->withInput($request->only('username'))->withErrors([
            'username' => 'Username/Email atau password salah.',
        ]);
    }

    protected function redirectBasedOnRole(string $role)
    {
        if ($role === 'siswa') {
            $profile = Auth::user()->siswaProfile;
            $start = $profile ? ($profile->start_date ?? $profile->tanggal_mulai) : null;
            $end = $profile ? ($profile->end_date ?? $profile->tanggal_selesai) : null;

            if ($profile && $start && \Carbon\Carbon::today()->lt(\Carbon\Carbon::parse($start)->startOfDay())) {
                return redirect()->route('pendaftaran.index', ['tab' => 'status']);
            }
            if ($profile && $end && \Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($end)->endOfDay())) {
                Auth::user()->update(['role' => 'calon_peserta']);
                return redirect()->route('pendaftaran.index', ['tab' => 'history'])
                    ->with('info', 'Masa kegiatan magang Anda di BPS Kota Malang telah selesai. Selamat dan terima kasih! Riwayat magang Anda tersimpan pada menu History.');
            }
            return redirect('/siswa/absen');
        }

        return match ($role) {
            'admin' => redirect('/admin/dashboard'),
            'pegawai' => redirect('/pegawai/rekap-absensi'),
            'calon_peserta' => redirect('/pendaftaran'),
            default => redirect('/'),
        };
    }

    public function logout(Request $request)
    {
        $username = Auth::check() ? Auth::user()->username : 'User';
        LogHelper::success($request, 'Logout', "User {$username} berhasil logout dari sistem.");

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
