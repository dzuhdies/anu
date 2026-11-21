<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Division;
use App\Models\Building;
use App\Models\Divisi;
use App\Models\Gedung;
use App\Models\User;

class LandingController extends Controller
{
    /**
     * Tampilkan Halaman Awal / Landing Page Portal Magang BPS Kota Malang
     */
    public function index()
    {
        $divisis = Division::where('is_active', true)->orderBy('name')->get();
        $gedung = Building::first();
        $totalSiswaSelesai = User::where('role', 'calon_peserta')->whereHas('studentProfile')->count();
        $totalSiswaAktif = User::where('role', 'siswa')->count();

        // Tentukan URL dashboard untuk user yang sedang login
        $dashboardUrl = null;
        if (Auth::check()) {
            $user = Auth::user();
            $dashboardUrl = match ($user->role) {
                'admin' => route('admin.dashboard'),
                'pegawai' => route('pegawai.absensi.rekap'),
                'siswa' => route('siswa.absen.form'),
                'calon_peserta' => route('pendaftaran.index'),
                default => '/login',
            };
        }

        return view('landing', compact('divisis', 'gedung', 'totalSiswaSelesai', 'totalSiswaAktif', 'dashboardUrl'));
    }
}
