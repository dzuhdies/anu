<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\EmployeeProfile;
use App\Models\Building;
use App\Models\Division;
use App\Models\InternshipApplication;
use App\Models\ApplicationMember;
use App\Models\ParticipantProfile;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Helpers\LogHelper;
use App\Models\Setting;
use Carbon\Carbon;   

class AdminController extends Controller
{
    public function index()
    {
        $siswaUsers = User::with('siswaProfile.gedung')->where('role', 'siswa')->get();
        $pegawaiUsers = User::with(['pegawaiProfile.gedung', 'pegawaiProfile.divisi'])->where('role', 'pegawai')->get();
        $adminUsers = User::where('role', 'admin')->get();
        $gedungs = Building::all();
        $jamTelat = Setting::where('key', 'jam_telat')->value('value') ?? '08:16';
        $allSiswas = StudentProfile::orderBy('full_name')->get();
        $jenisIzin = LeaveType::all();

        // Data Divisi & Pendaftaran Magang
        $divisis = Division::withCount(['pendaftarans' => function ($q) {
            $q->where('status', 'approved');
        }])->orderBy('name')->get();

        $pendaftarans = InternshipApplication::with(['divisi', 'division', 'anggotas.user', 'members.user', 'ketua', 'leader'])
            ->latest()
            ->get();

        $pendingCount = InternshipApplication::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'siswaUsers', 'pegawaiUsers', 'allSiswas', 'adminUsers', 
            'gedungs', 'jamTelat', 'jenisIzin', 'divisis', 'pendaftarans', 'pendingCount'
        ));
    }

    public function users()
    {
        $users = User::with(['siswaProfile', 'pegawaiProfile'])->get();
        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        $gedungs = Building::all();
        return view('admin.create-user', compact('gedungs'));
    }

    public function storeUser(Request $request)
    {
        // Support either direct 'nama_lengkap' / 'gedung_id' OR role-suffixed keys
        if (!$request->filled('nama_lengkap')) {
            if ($request->role === 'siswa' && $request->filled('nama_lengkap_siswa')) {
                $request->merge(['nama_lengkap' => $request->nama_lengkap_siswa]);
            } elseif ($request->role === 'pegawai' && $request->filled('nama_lengkap_pegawai')) {
                $request->merge(['nama_lengkap' => $request->nama_lengkap_pegawai]);
            }
        }
        if (!$request->filled('gedung_id')) {
            if ($request->role === 'siswa' && $request->filled('gedung_id_siswa')) {
                $request->merge(['gedung_id' => $request->gedung_id_siswa]);
            } elseif ($request->role === 'pegawai' && $request->filled('gedung_id_pegawai')) {
                $request->merge(['gedung_id' => $request->gedung_id_pegawai]);
            }
        }
        if (!$request->filled('divisi_id') && $request->filled('divisi_id_pegawai')) {
            $request->merge(['divisi_id' => $request->divisi_id_pegawai]);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:siswa,pegawai,admin',
            'nama_lengkap' => 'required_if:role,siswa,pegawai|nullable|string|max:255',
            'asal_pkl' => 'required_if:role,siswa|nullable|string|max:255',
            'gedung_id' => 'required_if:role,siswa,pegawai|nullable|exists:buildings,id',
            'divisi_id' => 'nullable|exists:divisions,id',
        ], [
            'nama_lengkap.required_if' => 'Nama lengkap wajib diisi untuk pengguna ini.',
            'asal_pkl.required_if' => 'Asal sekolah / instansi wajib diisi untuk siswa PKL.',
            'gedung_id.required_if' => 'Gedung penempatan wajib dipilih.',
            'gedung_id.exists' => 'Gedung yang dipilih tidak valid.',
            'divisi_id.exists' => 'Divisi yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Tambah User (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $namaLengkap = $request->nama_lengkap;
        $gedungId = $request->gedung_id;

        if ($request->role === 'siswa') {
            StudentProfile::create([
                'user_id' => $user->id,
                'full_name' => $namaLengkap,
                'institution' => $request->asal_pkl,
                'building_id' => $gedungId,
            ]);
        } else if ($request->role === 'pegawai') {
            EmployeeProfile::create([
                'user_id' => $user->id,
                'full_name' => $namaLengkap,
                'building_id' => $gedungId,
                'division_id' => $request->filled('divisi_id') ? $request->divisi_id : null,
            ]);
        }

        LogHelper::success($request, 'Tambah User Berhasil', "Admin menambahkan user baru {$user->username} dengan role {$user->role}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil ditambahkan');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!$request->filled('divisi_id') && $request->filled('divisi_id_pegawai')) {
            $request->merge(['divisi_id' => $request->divisi_id_pegawai]);
        }

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'nama_lengkap' => ['required_if:role,siswa,pegawai', 'nullable', 'string', 'max:255'],
            'asal_pkl' => ['required_if:role,siswa', 'nullable', 'string', 'max:255'],
            'gedung_id' => ['required_if:role,siswa,pegawai', 'nullable', 'exists:buildings,id'],
            'divisi_id' => ['nullable', 'exists:divisions,id'],
        ], [
            'nama_lengkap.required_if' => 'Nama lengkap wajib diisi.',
            'asal_pkl.required_if' => 'Asal sekolah / kampus wajib diisi untuk siswa PKL.',
            'gedung_id.required_if' => 'Gedung penempatan wajib dipilih.',
            'gedung_id.exists' => 'Gedung yang dipilih tidak valid.',
            'divisi_id.exists' => 'Divisi yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Update User (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($user->role === 'siswa') {
            $siswaData = [
                'full_name' => $request->nama_lengkap,
                'institution' => $request->asal_pkl,
                'building_id' => $request->gedung_id,
            ];
            if ($request->filled('tanggal_mulai')) {
                $siswaData['start_date'] = Carbon::parse($request->tanggal_mulai)->format('Y-m-d');
            }
            if ($request->filled('tanggal_selesai')) {
                $siswaData['end_date'] = Carbon::parse($request->tanggal_selesai)->format('Y-m-d');
            }

            if ($user->siswaProfile) {
                $user->siswaProfile->update($siswaData);
            } else {
                $siswaData['user_id'] = $user->id;
                StudentProfile::create($siswaData);
            }

            // Sinkronkan ke pendaftaran approved jika ada
            if ($request->filled('tanggal_selesai') || $request->filled('tanggal_mulai')) {
                $pendaftaran = InternshipApplication::where(function ($q) use ($user) {
                    $q->where('leader_user_id', $user->id)
                      ->orWhereHas('anggotas', fn($sub) => $sub->where('user_id', $user->id));
                })->where('status', 'approved')->first();

                if ($pendaftaran) {
                    $appUpdate = [];
                    if ($request->filled('tanggal_mulai')) $appUpdate['start_date'] = Carbon::parse($request->tanggal_mulai)->format('Y-m-d');
                    if ($request->filled('tanggal_selesai')) $appUpdate['end_date'] = Carbon::parse($request->tanggal_selesai)->format('Y-m-d');
                    $pendaftaran->update($appUpdate);
                }
            }
        } else if ($user->role === 'pegawai') {
            $divisiVal = $request->filled('divisi_id') ? $request->divisi_id : null;
            if ($user->pegawaiProfile) {
                $user->pegawaiProfile->update([
                    'full_name' => $request->nama_lengkap,
                    'building_id' => $request->gedung_id,
                    'division_id' => $divisiVal,
                ]);
            } else {
                EmployeeProfile::create([
                    'user_id' => $user->id,
                    'full_name' => $request->nama_lengkap,
                    'building_id' => $request->gedung_id,
                    'division_id' => $divisiVal,
                ]);
            }
        }

        LogHelper::success($request, 'Update User Berhasil', 'Admin memperbarui user: ' . $user->username);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'User berhasil diperbarui.']);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        LogHelper::success(request(), 'Hapus User Berhasil', 'Admin menghapus user: ' . $user->username);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus.']);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil dihapus.');
    }

    public function storeGedung(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meter' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Tambah Gedung (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $gedung = Building::create([
            'name' => $request->nama,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_meter' => (int) $request->radius_meter,
        ]);

        LogHelper::success($request, 'Tambah Gedung Berhasil', 'Admin menambahkan gedung: ' . $request->nama);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Gedung berhasil ditambahkan']);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Gedung berhasil ditambahkan');
    }

    public function updateGedung(Request $request, $id)
    {
        $gedung = Building::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meter' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Update Gedung (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $gedung->update([
            'name' => $request->nama,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_meter' => (int) $request->radius_meter,
        ]);

        LogHelper::success($request, 'Update Gedung Berhasil', 'Admin memperbarui gedung: ' . $gedung->nama);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Gedung berhasil diperbarui.']);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroyGedung($id)
    {
        $gedung = Building::findOrFail($id);
        $gedung->delete();

        LogHelper::success(request(), 'Hapus Gedung Berhasil', 'Admin menghapus gedung: ' . $gedung->nama);

        return redirect()->route('admin.dashboard')->with('success', 'Gedung berhasil dihapus.');
    }

    public function updateJamTelat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jam_telat' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Update Jam Batas Masuk (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        Setting::updateOrCreate(
            ['key' => 'jam_telat'],
            ['value' => $request->jam_telat]
        );

        LogHelper::success($request, 'Update Jam Batas Masuk Berhasil', 'Admin memperbarui jam batas presensi menjadi: ' . $request->jam_telat);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Jam batas presensi berhasil diperbarui.']);
        }

        return back()->with('success', 'Jam telat berhasil diperbarui.');
    }

    // ================= DIVISI MANAGEMENT =================
    public function storeDivisi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_divisi' => 'required|string|max:150|unique:divisions,name',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|integer|min:1|max:100',
        ], [
            'nama_divisi.unique' => 'Nama divisi sudah ada.',
            'kuota.min' => 'Kuota minimal 1 orang.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Tambah Divisi (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $divisi = Division::create([
            'name' => $request->nama_divisi,
            'description' => $request->deskripsi,
            'quota' => (int) $request->kuota,
            'is_active' => true,
        ]);

        LogHelper::success($request, 'Tambah Divisi Berhasil', "Admin menambahkan divisi baru: {$divisi->nama_divisi} dengan kuota {$divisi->kuota}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Divisi '{$divisi->nama_divisi}' berhasil ditambahkan."]);
        }

        return back()->with('success', "Divisi '{$divisi->nama_divisi}' berhasil ditambahkan.");
    }

    public function updateDivisi(Request $request, $id)
    {
        $divisi = Division::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_divisi' => ['required', 'string', 'max:150', Rule::unique('divisions', 'name')->ignore($divisi->id)],
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Update Divisi (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $divisi->update([
            'name' => $request->nama_divisi,
            'description' => $request->deskripsi,
            'quota' => (int) $request->kuota,
            'is_active' => $request->boolean('is_active'),
        ]);

        LogHelper::success($request, 'Update Divisi Berhasil', "Admin memperbarui divisi: {$divisi->nama_divisi}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Divisi '{$divisi->nama_divisi}' berhasil diperbarui."]);
        }

        return back()->with('success', "Divisi '{$divisi->nama_divisi}' berhasil diperbarui.");
    }

    public function deleteDivisi($id)
    {
        $divisi = Division::withCount('pendaftarans')->findOrFail($id);

        if ($divisi->pendaftarans_count > 0) {
            LogHelper::fail(request(), 'Hapus Divisi Ditolak', "Divisi '{$divisi->nama_divisi}' tidak dapat dihapus karena sudah memiliki data riwayat pendaftaran.");
            return back()->with('error', "Divisi '{$divisi->nama_divisi}' tidak dapat dihapus karena sudah memiliki data riwayat pendaftaran. Anda dapat menonaktifkannya melalui Edit.");
        }

        $divisi->delete();

        LogHelper::success(request(), 'Hapus Divisi Berhasil', "Admin menghapus divisi: {$divisi->nama_divisi}");

        return back()->with('success', "Divisi berhasil dihapus.");
    }

    // ================= PENDAFTARAN MAGANG SELEKSI =================
    public function approvePendaftaran(Request $request, $id)
    {
        $pendaftaran = InternshipApplication::with(['divisi', 'anggotas.user'])->findOrFail($id);

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'pegawai') {
            $pegawaiProfile = $currentUser->pegawaiProfile;
            if ($pegawaiProfile && $pegawaiProfile->divisi_id && (int)$pendaftaran->divisi_id !== (int)$pegawaiProfile->divisi_id) {
                LogHelper::fail($request, 'Approve Pendaftaran Ditolak (Bukan Divisi Pegawai)', "Pegawai {$currentUser->username} mencoba menyetujui pendaftaran ID #{$id} di luar divisi tugasnya.");
                return back()->with('error', "Akses Ditolak: Anda bertugas pada Divisi " . ($pegawaiProfile->divisi->nama_divisi ?? 'Khusus') . " dan hanya berhak menyeleksi pendaftar untuk divisi tersebut.");
            }
        }

        if ($pendaftaran->status === 'approved') {
            LogHelper::fail($request, 'Approve Pendaftaran Gagal', "Pendaftaran ID #{$id} sudah disetujui sebelumnya.");
            return back()->with('info', 'Pendaftaran ini sudah disetujui sebelumnya.');
        }

        $defaultGedung = Building::first();
        if (!$defaultGedung) {
            $defaultGedung = Building::create([
                'name' => 'Kantor BPS',
                'latitude' => -6.1753924,
                'longitude' => 106.8271528,
                'radius_meter' => 100,
            ]);
        }

        $adminId = Auth::user() ? Auth::user()->id : null;
        $totalAnggota = $pendaftaran->anggotas->count() ?: 1;
        $forceOverQuota = $request->boolean('force_over_quota');
        $isOverQuota = false;

        DB::beginTransaction();
        try {
            // Lock divisi untuk mencegah over-capacity jika ada beberapa persetujuan bersamaan
            $lockedDivisi = Division::where('id', $pendaftaran->division_id ?? $pendaftaran->divisi_id)->lockForUpdate()->firstOrFail();
            $tglMulaiCarbon = Carbon::parse($pendaftaran->start_date ?? $pendaftaran->tanggal_mulai);
            $tglSelesaiCarbon = Carbon::parse($pendaftaran->end_date ?? $pendaftaran->tanggal_selesai);
            $ketersediaan = $lockedDivisi->cekKetersediaan(
                $tglMulaiCarbon->format('Y-m-d'),
                $tglSelesaiCarbon->format('Y-m-d'),
                $totalAnggota
            );

            if (!$ketersediaan['tersedia']) {
                if ($forceOverQuota) {
                    $isOverQuota = true;
                } else {
                    DB::rollBack();
                    LogHelper::fail($request, 'Approve Pendaftaran Gagal (Kuota Penuh)', "Kuota divisi {$lockedDivisi->nama_divisi} tidak mencukupi untuk pendaftaran ID #{$id} ({$totalAnggota} orang).");
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'needs_over_quota_confirmation' => true,
                            'message' => "Kuota Divisi {$lockedDivisi->nama_divisi} tidak mencukupi (Kapasitas: {$lockedDivisi->kuota}, Sisa Kursi: {$ketersediaan['sisa_kuota_minimal']}, Pendaftar: {$totalAnggota} orang).",
                            'ketersediaan' => $ketersediaan,
                        ], 422);
                    }
                    return back()->with('error', "Gagal menyetujui: Kuota Divisi {$lockedDivisi->nama_divisi} sudah penuh pada rentang tanggal tersebut. Anda dapat menggunakan opsi 'Terima Melebihi Kuota' jika tetap ingin menerima pemohon ini.");
                }
            }

            $catatan = $isOverQuota ? 'Disetujui Melebihi Kuota Resmi Divisi' : null;

            $pendaftaran->update([
                'status' => 'approved',
                'is_over_quota' => $isOverQuota,
                'admin_note' => $catatan,
                'approved_by' => $adminId,
                'approved_at' => now(),
            ]);

            // Ubah role seluruh anggota menjadi siswa dan buat profil siswa
            foreach ($pendaftaran->anggotas as $anggota) {
                $user = $anggota->user;
                if ($user) {
                    $user->update(['role' => 'siswa']);

                    StudentProfile::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'full_name' => $anggota->full_name ?? $anggota->nama_lengkap,
                            'institution' => ($anggota->institution ?? $anggota->asal_univ) . ($anggota->major ? ' - ' . $anggota->major : ''),
                            'building_id' => $defaultGedung ? $defaultGedung->id : 1,
                            'division_id' => $pendaftaran->division_id ?? $pendaftaran->divisi_id,
                            'start_date' => $pendaftaran->start_date ?? $pendaftaran->tanggal_mulai,
                            'end_date' => $pendaftaran->end_date ?? $pendaftaran->tanggal_selesai,
                        ]
                    );
                }
            }

            DB::commit();

            $logNote = $isOverQuota ? " DENGAN STATUS MELEBIHI KUOTA" : "";
            LogHelper::success($request, 'Approve Pendaftaran Magang Berhasil', "Pengguna " . Auth::user()->username . " menyetujui pendaftaran magang ID #{$pendaftaran->id}{$logNote} untuk divisi {$pendaftaran->divisi->nama_divisi} ({$totalAnggota} orang)");

            $msg = $isOverQuota
                ? "Pendaftaran magang berhasil disetujui dengan status MELEBIHI KUOTA! Kuota resmi divisi tetap tidak berubah ({$lockedDivisi->kuota} orang)."
                : "Pendaftaran magang berhasil disetujui! Akun peserta telah diaktifkan menjadi siswa dan kuota divisi periode {$tglMulaiCarbon->format('d/m/Y')} - {$tglSelesaiCarbon->format('d/m/Y')} resmi terkunci.";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $msg,
                    'is_over_quota' => $isOverQuota,
                ]);
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            LogHelper::fail($request, 'Approve Pendaftaran Error', 'Terjadi kesalahan saat memproses persetujuan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses persetujuan: ' . $e->getMessage());
        }
    }

    public function rejectPendaftaran(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Reject Pendaftaran (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $pendaftaran = InternshipApplication::with('divisi')->findOrFail($id);

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'pegawai') {
            $pegawaiProfile = $currentUser->pegawaiProfile;
            if ($pegawaiProfile && $pegawaiProfile->divisi_id && (int)$pendaftaran->divisi_id !== (int)$pegawaiProfile->divisi_id) {
                LogHelper::fail($request, 'Reject Pendaftaran Ditolak (Bukan Divisi Pegawai)', "Pegawai {$currentUser->username} mencoba menolak pendaftaran ID #{$id} di luar divisinya.");
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => "Akses Ditolak: Anda bertugas pada Divisi " . ($pegawaiProfile->divisi->nama_divisi ?? 'Khusus') . " dan hanya berhak menyeleksi pendaftar untuk divisi tersebut."], 422);
                }
                return back()->with('error', "Akses Ditolak: Anda bertugas pada Divisi " . ($pegawaiProfile->divisi->nama_divisi ?? 'Khusus') . " dan hanya berhak menyeleksi pendaftar untuk divisi tersebut.")
                             ->with('modal_error_target', 'pendaftaranRejectModal');
            }
        }

        $adminId = Auth::user() ? Auth::user()->id : null;

        $pendaftaran->update([
            'status' => 'rejected',
            'admin_note' => $request->catatan_admin ?: 'Mohon maaf, kuota penuh atau kualifikasi berkas belum memenuhi persyaratan.',
            'approved_by' => $adminId,
            'approved_at' => now(),
        ]);

        LogHelper::success($request, 'Reject Pendaftaran Magang Berhasil', "Pengguna " . Auth::user()->username . " menolak pendaftaran magang ID #{$pendaftaran->id}. Catatan: " . ($request->catatan_admin ?: 'Kualifikasi belum sesuai'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pendaftaran magang telah ditolak dan kuota tidak terpakai.']);
        }

        return back()->with('success', 'Pendaftaran magang telah ditolak dan kuota tidak terpakai.');
    }

    public function reschedulePendaftaran(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_mulai_saran' => 'required|date|after_or_equal:today',
            'tanggal_selesai_saran' => 'required|date|after:tanggal_mulai_saran',
            'catatan_revisi_jadwal' => 'required|string|max:1000',
        ], [
            'tanggal_mulai_saran.required' => 'Tanggal mulai yang disarankan wajib diisi.',
            'tanggal_mulai_saran.after_or_equal' => 'Tanggal mulai minimal hari ini.',
            'tanggal_selesai_saran.required' => 'Tanggal selesai yang disarankan wajib diisi.',
            'tanggal_selesai_saran.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'catatan_revisi_jadwal.required' => 'Alasan atau catatan penyesuaian jadwal wajib disertakan.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Revisi Jadwal (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput()->with('modal_error_target', 'pendaftaranRescheduleModal');
        }

        $pendaftaran = InternshipApplication::with(['divisi', 'anggotas'])->findOrFail($id);

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'pegawai') {
            $pegawaiProfile = $currentUser->pegawaiProfile;
            if ($pegawaiProfile && $pegawaiProfile->divisi_id && (int)$pendaftaran->divisi_id !== (int)$pegawaiProfile->divisi_id) {
                LogHelper::fail($request, 'Revisi Jadwal Ditolak (Bukan Divisi Pegawai)', "Pegawai {$currentUser->username} mencoba merevisi pendaftaran ID #{$id} di luar divisinya.");
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => "Akses Ditolak: Anda bertugas pada Divisi " . ($pegawaiProfile->divisi->nama_divisi ?? 'Khusus') . " dan hanya berhak menyeleksi pendaftar untuk divisi tersebut."], 422);
                }
                return back()->with('error', "Akses Ditolak: Anda bertugas pada Divisi " . ($pegawaiProfile->divisi->nama_divisi ?? 'Khusus') . " dan hanya berhak menyeleksi pendaftar untuk divisi tersebut.")
                             ->with('modal_error_target', 'pendaftaranRescheduleModal');
            }
        }

        if ($pendaftaran->status === 'approved') {
            LogHelper::fail($request, 'Revisi Jadwal Gagal', "Pendaftaran ID #{$id} sudah disetujui sebelumnya.");
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Pendaftaran ini sudah berstatus disetujui dan tidak dapat direvisi jadwalnya.'], 422);
            }
            return back()->with('error', 'Pendaftaran ini sudah berstatus disetujui dan tidak dapat direvisi jadwalnya.')
                         ->with('modal_error_target', 'pendaftaranRescheduleModal');
        }

        // Cek ketersediaan kuota pada rentang tanggal saran baru
        $totalAnggota = $pendaftaran->anggotas->count() ?: 1;
        $ketersediaan = $pendaftaran->divisi->cekKetersediaan(
            $request->tanggal_mulai_saran,
            $request->tanggal_selesai_saran,
            $totalAnggota
        );

        $isOverQuota = false;
        if (!$ketersediaan['tersedia']) {
            if ($request->boolean('force_over_quota')) {
                $isOverQuota = true;
            } else {
                LogHelper::fail($request, 'Revisi Jadwal Gagal (Kuota Penuh)', "Kuota divisi {$pendaftaran->divisi->nama_divisi} penuh pada tanggal saran pendaftaran ID #{$id}.");
                $errMsg = "Peringatan: Kuota Divisi {$pendaftaran->divisi->nama_divisi} pada tanggal saran tersebut tidak mencukupi (Sisa kursi: {$ketersediaan['sisa_kuota_minimal']}, Pengajuan: {$totalAnggota} orang). Aktifkan persetujuan melebihi kuota jika ingin tetap mengusulkan jadwal ini.";
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errMsg, 'needs_over_quota_confirmation' => true], 422);
                }
                return back()->with('error', $errMsg)->with('modal_error_target', 'pendaftaranRescheduleModal');
            }
        }

        $adminId = Auth::user() ? Auth::user()->id : null;

        $pendaftaran->update([
            'status' => 'rescheduled',
            'is_over_quota' => $isOverQuota,
            'suggested_start_date' => $request->tanggal_mulai_saran,
            'suggested_end_date' => $request->tanggal_selesai_saran,
            'reschedule_reason' => $request->catatan_revisi_jadwal,
            'rescheduled_by' => $adminId,
            'rescheduled_at' => now(),
            'applicant_rejection_reason' => null,
        ]);

        $logNote = $isOverQuota ? " (Status Melebihi Kuota)" : "";
        LogHelper::success($request, 'Revisi Jadwal Berhasil', "Pengguna " . Auth::user()->username . " merevisi jadwal pendaftaran ID #{$pendaftaran->id}{$logNote} menjadi {$request->tanggal_mulai_saran} s.d. {$request->tanggal_selesai_saran}");

        $msgSuccess = $isOverQuota
            ? "Usulan jadwal baru BERHASIL DIKIRIM dengan status MELEBIHI KUOTA! Kuota resmi divisi tetap tidak berubah. Peserta dapat meninjau tawaran ini di akun mereka."
            : "Usulan jadwal baru berhasil dikirim ke calon peserta! Peserta dapat menerima (langsung disetujui) atau menolak tawaran tersebut di portal pendaftaran mereka.";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msgSuccess]);
        }

        return back()->with('success', $msgSuccess);
    }

    /**
     * Endpoint API Kalender Kuota Divisi (AJAX)
     * Mengembalikan data ketersediaan kuota per hari dalam bulan yang dipilih
     */
    public function getQuotaCalendar(Request $request, $id)
    {
        $divisi = Division::findOrFail($id);
        $year = (int)$request->input('year', now()->year);
        $month = (int)$request->input('month', now()->month);
        $excludeId = $request->input('exclude_pendaftaran_id') ? (int)$request->input('exclude_pendaftaran_id') : null;

        $calendar = $divisi->getKalenderKuota($year, $month, $excludeId);

        return response()->json($calendar);
    }

    /**
     * Setujui Pengunduran Diri Peserta Magang (Admin / Pegawai)
     * Mengubah status menjadi 'resigned', menghapus SiswaProfile agar kuota divisi langsung bebas kembali
     */
    public function approveResignation(Request $request, $id)
    {
        $pendaftaran = InternshipApplication::with(['divisi', 'anggotas'])->findOrFail($id);

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'pegawai') {
            $pegawaiProfile = $currentUser->pegawaiProfile;
            if ($pegawaiProfile && $pegawaiProfile->divisi_id && (int)$pendaftaran->divisi_id !== (int)$pegawaiProfile->divisi_id) {
                LogHelper::fail($request, 'Setujui Pengunduran Diri Ditolak (Bukan Divisi Pegawai)', "Pegawai {$currentUser->username} mencoba menyetujui pengunduran diri ID #{$id} di luar divisinya.");
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => "Akses Ditolak: Anda tidak berwenang memproses pengunduran diri pada divisi ini."], 422);
                }
                return back()->with('error', "Akses Ditolak: Anda tidak berwenang memproses pengunduran diri pada divisi ini.");
            }
        }

        if ($pendaftaran->status !== 'resignation_requested') {
            LogHelper::fail($request, 'Setujui Pengunduran Diri Gagal', "Pendaftaran ID #{$id} tidak sedang mengajukan pengunduran diri ({$pendaftaran->status}).");
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Pendaftaran ini tidak sedang mengajukan pengunduran diri.'], 422);
            }
            return back()->with('error', 'Pendaftaran ini tidak sedang mengajukan pengunduran diri.');
        }

        DB::beginTransaction();
        try {
            $catatan = $request->input('catatan') ?: $request->input('catatan_admin', 'Permohonan pengunduran diri telah diverifikasi dan disetujui.');
            $adminUserId = $currentUser ? $currentUser->id : null;

            $pendaftaran->update([
                'status' => 'resigned',
                'resignation_approved_by' => $adminUserId,
                'resignation_approved_at' => now(),
                'resignation_admin_note' => $catatan,
            ]);

            // Bebaskan kuota dengan menghapus SiswaProfile siswa terkait
            $allUserIds = [];
            if ($pendaftaran->leader_user_id) $allUserIds[] = $pendaftaran->leader_user_id;
            elseif ($pendaftaran->ketua_user_id) $allUserIds[] = $pendaftaran->ketua_user_id;
            foreach ($pendaftaran->anggotas as $ang) {
                if ($ang->user_id) $allUserIds[] = $ang->user_id;
            }

            foreach ($allUserIds as $uId) {
                StudentProfile::where('user_id', $uId)->delete();
                User::where('id', $uId)->update(['role' => 'calon_peserta']);
            }

            DB::commit();

            LogHelper::success($request, 'Setujui Pengunduran Diri Berhasil', "Pengguna " . Auth::user()->username . " menyetujui pengunduran diri peserta magang ID #{$pendaftaran->id}. Kuota divisi telah dibebaskan kembali.");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Permohonan pengunduran diri telah DISETUJUI. Akun peserta telah dikembalikan ke status pemohon dan kuota divisi otomatis telah dibebaskan!']);
            }

            return back()->with('success', 'Permohonan pengunduran diri telah DISETUJUI. Akun peserta telah dikembalikan ke status pemohon dan kuota divisi otomatis telah dibebaskan!');
        } catch (\Exception $e) {
            DB::rollBack();
            LogHelper::fail($request, 'Setujui Pengunduran Diri Error', 'Gagal memproses pengunduran diri: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memproses pengunduran diri: ' . $e->getMessage()], 422);
            }
            return back()->with('error', 'Gagal memproses pengunduran diri: ' . $e->getMessage());
        }
    }

    /**
     * Tolak Permohonan Pengunduran Diri Peserta Magang
     */
    public function rejectResignation(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'catatan' => 'required_without:catatan_admin|nullable|string|max:500',
            'catatan_admin' => 'required_without:catatan|nullable|string|max:500',
        ], [
            'catatan.required_without' => 'Catatan alasan penolakan pengunduran diri wajib diisi.',
            'catatan_admin.required_without' => 'Catatan alasan penolakan pengunduran diri wajib diisi.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Tolak Pengunduran Diri (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput()->with('modal_error_target', 'rejectResignationModal');
        }

        $catatan = $request->input('catatan_admin') ?: $request->input('catatan');

        $pendaftaran = InternshipApplication::findOrFail($id);

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'pegawai') {
            $pegawaiProfile = $currentUser->pegawaiProfile;
            if ($pegawaiProfile && $pegawaiProfile->divisi_id && (int)$pendaftaran->divisi_id !== (int)$pegawaiProfile->divisi_id) {
                LogHelper::fail($request, 'Tolak Pengunduran Diri Ditolak (Bukan Divisi Pegawai)', "Pegawai {$currentUser->username} mencoba menolak pengunduran diri ID #{$id} di luar divisinya.");
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => "Akses Ditolak: Anda tidak berwenang memproses pengunduran diri pada divisi ini."], 422);
                }
                return back()->with('error', "Akses Ditolak: Anda tidak berwenang memproses pengunduran diri pada divisi ini.")
                             ->with('modal_error_target', 'rejectResignationModal');
            }
        }

        if ($pendaftaran->status !== 'resignation_requested') {
            LogHelper::fail($request, 'Tolak Pengunduran Diri Gagal', "Pendaftaran ID #{$id} tidak sedang mengajukan pengunduran diri ({$pendaftaran->status}).");
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Pendaftaran ini tidak sedang mengajukan pengunduran diri.'], 422);
            }
            return back()->with('error', 'Pendaftaran ini tidak sedang mengajukan pengunduran diri.')
                         ->with('modal_error_target', 'rejectResignationModal');
        }

        $adminUserId = $currentUser ? $currentUser->id : null;

        $pendaftaran->update([
            'status' => 'approved',
            'resignation_approved_by' => $adminUserId,
            'resignation_approved_at' => now(),
            'resignation_admin_note' => $catatan,
        ]);

        LogHelper::success($request, 'Tolak Pengunduran Diri Berhasil', "Pengguna " . Auth::user()->username . " menolak permohonan pengunduran diri ID #{$pendaftaran->id}. Status magang tetap disetujui.");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Permohonan pengunduran diri telah DITOLAK. Status magang peserta tetap disetujui.']);
        }

        return back()->with('success', 'Permohonan pengunduran diri telah DITOLAK. Status magang peserta tetap disetujui.');
    }

    /**
     * Ubah Tanggal Selesai (atau Mulai) Magang Peserta / Siswa (Dapat mempercepat atau memperpanjang masa magang)
     */
    public function updatePeriodeSiswa(Request $request, $id)
    {
        $siswa = StudentProfile::with(['user', 'divisi'])->find($id);
        if (!$siswa) {
            $user = User::find($id);
            if ($user && $user->siswaProfile) {
                $siswa = $user->siswaProfile;
            }
        }

        if (!$siswa) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Data profil siswa magang tidak ditemukan.'], 404);
            }
            return back()->with('error', 'Data profil siswa magang tidak ditemukan.');
        }

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'pegawai') {
            $pegawaiProfile = $currentUser->pegawaiProfile;
            if ($pegawaiProfile && $pegawaiProfile->division_id && (int)$siswa->division_id !== (int)$pegawaiProfile->division_id) {
                LogHelper::fail($request, 'Ubah Periode Siswa Ditolak (Bukan Divisi Pegawai)', "Pegawai {$currentUser->username} mencoba mengubah periode siswa ID #{$siswa->id} di luar divisi binaannya.");
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Akses Ditolak: Anda hanya berwenang mengubah periode siswa pada divisi binaan Anda.'], 403);
                }
                return back()->with('error', 'Akses Ditolak: Anda hanya berwenang mengubah periode siswa pada divisi binaan Anda.');
            }
        }

        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'required|date',
            'alasan' => 'nullable|string|max:500',
        ], [
            'tanggal_selesai.required' => 'Tanggal selesai magang wajib diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
        ]);

        $startVal = $request->filled('tanggal_mulai') 
            ? Carbon::parse($request->tanggal_mulai)->format('Y-m-d') 
            : ($siswa->start_date ? Carbon::parse($siswa->start_date)->format('Y-m-d') : null);
        $endVal = Carbon::parse($request->tanggal_selesai)->format('Y-m-d');

        if ($startVal && $endVal < $startVal) {
            $validator->after(function ($v) use ($startVal) {
                $v->errors()->add('tanggal_selesai', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai (' . Carbon::parse($startVal)->format('d/m/Y') . ').');
            });
        }

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Ubah Periode Siswa (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $oldEndRaw = $siswa->end_date ? Carbon::parse($siswa->end_date)->format('Y-m-d') : null;
            $oldEnd = $siswa->end_date ? Carbon::parse($siswa->end_date)->format('d/m/Y') : '-';
            $newEndFormatted = Carbon::parse($endVal)->format('d/m/Y');

            // 1. Update profil siswa
            $updateData = ['end_date' => $endVal];
            if ($request->filled('tanggal_mulai')) {
                $updateData['start_date'] = $startVal;
            }
            $siswa->update($updateData);

            // 2. Sinkronisasi pendaftaran approved terkait
            $pendaftaran = InternshipApplication::where(function ($q) use ($siswa) {
                $q->where('leader_user_id', $siswa->user_id)
                  ->orWhereHas('anggotas', fn($sub) => $sub->where('user_id', $siswa->user_id));
            })->where('status', 'approved')->first();

            if ($pendaftaran) {
                $appUpdate = ['end_date' => $endVal];
                if ($request->filled('tanggal_mulai')) {
                    $appUpdate['start_date'] = $startVal;
                }
                $pendaftaran->update($appUpdate);
            }

            // 3. Pastikan user role tetap siswa jika periode berakhir di masa depan/hari ini
            $user = $siswa->user;
            if ($user) {
                if (Carbon::parse($endVal)->endOfDay()->gte(today())) {
                    $user->update(['role' => 'siswa']);
                }
            }

            DB::commit();

            $perubahanType = ($oldEndRaw && $endVal > $oldEndRaw) ? 'diperpanjang' : 'dipercepat';
            $catatan = $request->input('alasan') ? " (Catatan: " . $request->input('alasan') . ")" : "";
            $msg = "Tanggal selesai magang untuk {$siswa->full_name} berhasil {$perubahanType} dari {$oldEnd} menjadi {$newEndFormatted}.{$catatan}";

            LogHelper::success($request, 'Ubah Periode Magang Berhasil', "{$currentUser->username} ({$currentUser->role}) mengubah tanggal selesai siswa {$siswa->full_name} dari {$oldEnd} menjadi {$newEndFormatted}.{$catatan}");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $msg,
                    'new_end_date' => $newEndFormatted,
                ]);
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            LogHelper::fail($request, 'Ubah Periode Siswa Error', $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
