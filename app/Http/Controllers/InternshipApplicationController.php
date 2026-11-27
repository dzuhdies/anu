<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Division;
use App\Models\InternshipApplication;
use App\Models\ApplicationMember;
use App\Models\ParticipantProfile;
use App\Models\Building;
use App\Models\StudentProfile;
use App\Helpers\LogHelper;
use Carbon\Carbon;

class InternshipApplicationController extends Controller
{
    /**
     * Halaman Dashboard Calon Peserta
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $participantProfile = $user->participantProfile;
        $pesertaProfile = $participantProfile;

        // 1. Deteksi & Perbarui Status Magang yang sudah selesai masa berlakunya (end_date < today)
        $pastApprovedApplications = InternshipApplication::where('status', 'approved')
            ->whereDate('end_date', '<', $today)
            ->where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', fn($sub) => $sub->where('user_id', $user->id));
            })
            ->get();

        foreach ($pastApprovedApplications as $pap) {
            $pap->update(['status' => 'completed']);
        }

        // Cek apakah user masih memiliki magang approved yang SEDANG AKTIF (start_date <= today <= end_date)
        $hasActiveOngoingMagang = InternshipApplication::where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', fn($sub) => $sub->where('user_id', $user->id));
            })
            ->exists();

        if (!$hasActiveOngoingMagang && $user->role === 'siswa') {
            $user->update(['role' => 'calon_peserta']);
        }

        // 2. Cari pendaftaran aktif di mana user terdaftar
        $memberRecord = ApplicationMember::where('user_id', $user->id)
            ->whereHas('application', function ($q) use ($today) {
                $q->whereIn('status', ['pending', 'rescheduled', 'resignation_requested'])
                  ->orWhere(function ($sub) use ($today) {
                      $sub->where('status', 'approved')
                          ->whereDate('end_date', '>=', $today);
                  });
            })
            ->latest('id')
            ->first();

        $application = null;
        if ($memberRecord) {
            $application = InternshipApplication::with(['division', 'members.user.participantProfile', 'leader'])
                ->find($memberRecord->application_id);
        }

        $pendaftaran = $application;
        $anggotaRecord = $memberRecord;

        // 3. Ambil Seluruh Riwayat Magang (History)
        $historyMagang = InternshipApplication::where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', fn($sub) => $sub->where('user_id', $user->id));
            })
            ->where(function ($q) use ($today) {
                $q->whereIn('status', ['completed', 'resigned', 'terminated'])
                  ->orWhere(function ($sub) use ($today) {
                      $sub->where('status', 'approved')
                          ->whereDate('end_date', '<', $today);
                  });
            })
            ->with(['division', 'members.user.participantProfile', 'leader', 'certificate'])
            ->latest('end_date')
            ->get();

        $studentProfile = $user->studentProfile;
        $historyAbsensis = collect();
        $historyAbsensiStats = null;
        if ($studentProfile) {
            $historyAbsensis = $studentProfile->attendances()
                ->with('leaveType')
                ->orderBy('date', 'desc')
                ->get();

            $totalHadir = $historyAbsensis->where('attendance_status', true)->count();
            $totalTelat = $historyAbsensis->where('punctuality_status', 'Terlambat')->count();
            $totalIzin = $historyAbsensis->where('is_permission', true)->count();
            $totalHari = $historyAbsensis->count();

            $historyAbsensiStats = [
                'total_hari' => $totalHari,
                'total_hadir' => $totalHadir,
                'total_telat' => $totalTelat,
                'total_izin' => $totalIzin,
                'persentase_kehadiran' => $totalHari > 0 ? round(($totalHadir / $totalHari) * 100) : 100,
            ];
        }

        $magangBelumMulai = false;
        $magangSudahMulai = false;
        $hariMenujuMulai = 0;
        $tglMulaiCarbon = null;

        if ($application && $application->status === 'approved') {
            $tglMulaiCarbon = Carbon::parse($application->start_date)->startOfDay();
            $tglSelesaiCarbon = Carbon::parse($application->end_date)->endOfDay();

            if ($today->lt($tglMulaiCarbon)) {
                $magangBelumMulai = true;
                $hariMenujuMulai = $today->diffInDays($tglMulaiCarbon);
            } elseif ($today->lte($tglSelesaiCarbon)) {
                $magangSudahMulai = true;
                if ($user->role !== 'siswa') {
                    $user->update(['role' => 'siswa']);
                }
            }
        }

        if ($magangSudahMulai && $user->role === 'siswa' && !$request->has('tab') && (!$application || $application->status !== 'resignation_requested')) {
            return redirect()->route('siswa.absen.form');
        }

        $divisis = Division::where('is_active', true)->orderBy('name')->get();

        $activeTab = $request->query('tab', 'pendaftaran');
        if (!$participantProfile && !$application && $historyMagang->isEmpty()) {
            $activeTab = 'profile';
        } elseif ($request->has('tab')) {
            $activeTab = $request->query('tab');
        } elseif ($application && in_array($application->status, ['pending', 'approved', 'rescheduled', 'resignation_requested'])) {
            $activeTab = 'status';
        } elseif ($historyMagang->isNotEmpty() && !$application) {
            $activeTab = 'history';
        }

        return view('pendaftaran.index', compact(
            'user', 'pesertaProfile', 'pendaftaran', 'anggotaRecord', 
            'divisis', 'activeTab', 'magangBelumMulai', 'magangSudahMulai', 'hariMenujuMulai', 'tglMulaiCarbon',
            'historyMagang', 'historyAbsensiStats', 'historyAbsensis', 'studentProfile'
        ));
    }

    public function profile()
    {
        return redirect()->route('pendaftaran.index', ['tab' => 'profile']);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $profile = $user->participantProfile;

        $fullNameKey = $request->filled('full_name') ? 'full_name' : 'nama_lengkap';
        $institutionKey = $request->filled('institution') ? 'institution' : 'asal_univ';
        $majorKey = $request->filled('major') ? 'major' : 'jurusan';
        $phoneKey = $request->filled('phone') ? 'phone' : 'no_hp';

        $rules = [
            $fullNameKey => 'required|string|max:150',
            $institutionKey => 'required|string|max:150',
            $majorKey => 'required|string|max:100',
            $phoneKey => 'required|string|max:25',
            'portofolio_file' => 'nullable|file|mimes:pdf|max:10240',
            'portofolio_url' => 'nullable|url|max:255',
        ];

        if (!$profile || empty($profile->cv_path)) {
            $rules['cv'] = 'required|file|mimes:pdf|max:3072';
        } else {
            $rules['cv'] = 'nullable|file|mimes:pdf|max:3072';
        }

        $messages = [
            $fullNameKey . '.required' => 'Nama lengkap wajib diisi.',
            $institutionKey . '.required' => 'Asal universitas/sekolah/instansi wajib diisi.',
            $majorKey . '.required' => 'Program studi / jurusan wajib diisi.',
            $phoneKey . '.required' => 'Nomor WhatsApp / HP aktif wajib diisi.',
            'cv.required' => 'Curriculum Vitae (CV) wajib diunggah dalam format PDF.',
            'cv.mimes' => 'Berkas CV harus berformat PDF.',
            'cv.max' => 'Ukuran berkas CV maksimal 3 MB.',
            'portofolio_file.mimes' => 'Berkas portofolio harus berformat PDF.',
            'portofolio_file.max' => 'Ukuran berkas portofolio maksimal 10 MB.',
            'portofolio_url.url' => 'Format URL portofolio tidak valid (gunakan awalan http:// atau https://).',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Update Biodata (Salah Isi)', $validator->errors());
            return back()->withErrors($validator)->withInput();
        }

        $folderCv = 'pendaftaran/cv/' . date('Y-m');
        $folderPorto = 'pendaftaran/portofolio/' . date('Y-m');

        $cvPath = $profile ? $profile->cv_path : null;
        if ($request->hasFile('cv')) {
            if ($cvPath && Storage::disk('public')->exists($cvPath)) {
                Storage::disk('public')->delete($cvPath);
            }
            $cvName = 'cv_' . $user->id . '_' . time() . '.pdf';
            $cvPath = $request->file('cv')->storeAs($folderCv, $cvName, 'public');
        }

        $portoPath = $profile ? $profile->portofolio_path : null;
        if ($request->hasFile('portofolio_file')) {
            if ($portoPath && Storage::disk('public')->exists($portoPath)) {
                Storage::disk('public')->delete($portoPath);
            }
            $portoName = 'porto_' . $user->id . '_' . time() . '.pdf';
            $portoPath = $request->file('portofolio_file')->storeAs($folderPorto, $portoName, 'public');
        }

        $portoUrl = $request->filled('portofolio_url') ? $request->portofolio_url : ($profile ? $profile->portofolio_url : null);

        ParticipantProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $request->input($fullNameKey),
                'institution' => $request->input($institutionKey),
                'major' => $request->input($majorKey),
                'phone' => $request->input($phoneKey),
                'cv_path' => $cvPath,
                'portofolio_path' => $portoPath,
                'portofolio_url' => $portoUrl,
            ]
        );

        LogHelper::success($request, 'Update Biodata Peserta Berhasil', "User {$user->username} memperbarui biodata profil calon peserta.");

        return redirect()->route('pendaftaran.index', ['tab' => 'pendaftaran'])
            ->with('success', 'Biodata profil dan berkas Anda berhasil disimpan! Silakan lanjutkan pemilihan divisi dan jadwal magang.');
    }

    public function checkQuota(Request $request)
    {
        $divisionId = $request->division_id ?? $request->divisi_id;
        $startDate = $request->start_date ?? $request->tanggal_mulai;
        $endDate = $request->end_date ?? $request->tanggal_selesai;
        $headCount = (int) ($request->head_count ?? ($request->jumlah_orang ?: 1));

        $validator = Validator::make([
            'division_id' => $divisionId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'head_count' => $headCount,
        ], [
            'division_id' => 'required|exists:divisions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'head_count' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $division = Division::findOrFail($divisionId);
        $ketersediaan = $division->checkAvailability($startDate, $endDate, $headCount);

        if (!$ketersediaan['available']) {
            LogHelper::fail(
                $request,
                'Cek Kuota Divisi (Penuh)',
                "Pengecekan kuota divisi {$division->name} periode {$startDate} s.d. {$endDate} ({$headCount} orang): Kuota Penuh / Tidak Mencukupi (Max terisi: {$ketersediaan['max_occupied']}, Kuota: {$division->quota})."
            );
        }

        return response()->json([
            'success' => true,
            'tersedia' => $ketersediaan['available'],
            'available' => $ketersediaan['available'],
            'kuota_divisi' => $ketersediaan['division_quota'],
            'division_quota' => $ketersediaan['division_quota'],
            'max_terisi' => $ketersediaan['max_occupied'],
            'max_occupied' => $ketersediaan['max_occupied'],
            'sisa_kuota_minimal' => $ketersediaan['min_remaining_quota'],
            'min_remaining_quota' => $ketersediaan['min_remaining_quota'],
            'tanggal_penuh' => $ketersediaan['full_dates'],
            'full_dates' => $ketersediaan['full_dates'],
            'breakdown_bulanan' => $ketersediaan['monthly_breakdown'],
            'monthly_breakdown' => $ketersediaan['monthly_breakdown'],
            'data' => $ketersediaan,
        ]);
    }

    public function checkUser(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $raw = trim($request->identifier);
        $identifier = str_starts_with($raw, '@') ? substr($raw, 1) : $raw;
        $currentUser = Auth::user();

        $user = User::with(['participantProfile', 'studentProfile'])->where(function ($q) use ($identifier) {
            $q->where('username', $identifier)
              ->orWhere('email', $identifier);
        })->first();

        if (!$user) {
            LogHelper::fail($request, 'Cek Anggota Gagal', "Pencarian anggota kelompok gagal: identifier '{$request->identifier}' tidak ditemukan.");
            return response()->json([
                'success' => false,
                'message' => "Pengguna dengan username/email '{$request->identifier}' tidak ditemukan. Pastikan rekan Anda sudah membuat akun terlebih dahulu.",
            ], 404);
        }

        if ($user->id === $currentUser->id) {
            LogHelper::fail($request, 'Cek Anggota Ditolak', "Pendaftar mencoba menambahkan akun sendiri sebagai rekan anggota tambahan.");
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak perlu menambahkan akun Anda sendiri sebagai anggota tambahan.',
            ], 422);
        }

        if (in_array($user->role, ['admin', 'pegawai', 'guru'])) {
            LogHelper::fail($request, 'Cek Anggota Ditolak (Bukan Peserta)', "Pengguna '@{$user->username}' berstatus {$user->role} dan tidak dapat didaftarkan sebagai peserta magang.");
            return response()->json([
                'success' => false,
                'message' => "Pengguna '@{$user->username}' berstatus pegawai/staf/admin dan tidak dapat didaftarkan sebagai peserta magang.",
            ], 422);
        }

        $isSiswa = ($user->role === 'siswa');
        $hasActiveApprovedMagang = InternshipApplication::where('status', 'approved')
            ->whereDate('end_date', '>=', now()->toDateString())
            ->where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', fn($sub) => $sub->where('user_id', $user->id));
            })->exists();

        $hasActiveStudentProfile = $user->studentProfile()
            ->whereDate('end_date', '>=', now()->toDateString())
            ->exists();

        if (($isSiswa && $hasActiveStudentProfile) || $hasActiveApprovedMagang) {
            LogHelper::fail($request, 'Cek Anggota Ditolak (Sedang Aktif Magang)', "Pengguna '@{$user->username}' tidak dapat ditambahkan karena saat ini sedang aktif sebagai peserta magang.");
            return response()->json([
                'success' => false,
                'message' => "Pengguna '@{$user->username}' tidak dapat ditambahkan karena saat ini sedang aktif sebagai peserta magang.",
            ], 422);
        }

        $activeApplication = InternshipApplication::whereIn('status', ['pending', 'rescheduled', 'resignation_requested'])
            ->where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', fn($sub) => $sub->where('user_id', $user->id));
            })
            ->with(['leader'])
            ->first();

        if ($activeApplication) {
            if ($activeApplication->type === 'kelompok') {
                $groupName = $activeApplication->group_name ?: 'Kelompok Magang';
                $leaderId = $activeApplication->leader_user_id;
                if ($leaderId === $user->id) {
                    $msg = "Pengguna '@{$user->username}' tidak dapat ditambahkan karena sudah membentuk kelompok sendiri ('{$groupName}') pada pengajuan lain.";
                } else {
                    $leaderUsername = $activeApplication->leader ? $activeApplication->leader->username : 'rekan lain';
                    $msg = "Pengguna '@{$user->username}' tidak dapat ditambahkan karena sudah tergabung dalam kelompok '{$groupName}' (Ketua: @{$leaderUsername}).";
                }
            } else {
                $msg = "Pengguna '@{$user->username}' tidak dapat ditambahkan karena sudah memiliki permohonan pendaftaran magang mandiri yang sedang diproses.";
            }

            LogHelper::fail($request, 'Cek Anggota Ditolak (Ada Pengajuan Lain)', $msg);
            return response()->json([
                'success' => false,
                'message' => $msg,
            ], 422);
        }

        $profile = $user->participantProfile;
        if (!$profile || empty($profile->full_name) || empty($profile->cv_path)) {
            return response()->json([
                'success' => false,
                'message' => "Pengguna '@{$user->username}' belum melengkapi formulir biodata dan berkas CV di profilnya. Minta rekan Anda untuk login dan melengkapi profil terlebih dahulu.",
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'nama_lengkap' => $profile->full_name,
                'asal_univ' => $profile->institution,
                'jurusan' => $profile->major,
                'no_hp' => $profile->phone,
                'cv_ready' => !empty($profile->cv_path),
                'porto_ready' => !empty($profile->portofolio_path) || !empty($profile->portofolio_url),
            ],
            'message' => "Rekan ditemukan: {$profile->full_name} (@{$user->username})",
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $userProfile = $user->participantProfile;
        if (!$userProfile || empty($userProfile->full_name) || empty($userProfile->cv_path)) {
            LogHelper::fail($request, 'Pengajuan Pendaftaran Ditolak', 'Pendaftar belum melengkapi formulir biodata dan berkas CV.');
            return redirect()->route('pendaftaran.index', ['tab' => 'profile'])
                ->with('error', 'Anda wajib melengkapi formulir biodata dan mengunggah berkas CV terlebih dahulu.');
        }

        $isSiswa = ($user->role === 'siswa');
        $hasActiveApprovedMagang = InternshipApplication::where('status', 'approved')
            ->whereDate('end_date', '>=', now()->toDateString())
            ->where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', fn($sub) => $sub->where('user_id', $user->id));
            })->exists();

        $hasActiveStudentProfile = $user->studentProfile()
            ->whereDate('end_date', '>=', now()->toDateString())
            ->exists();

        if (($isSiswa && $hasActiveStudentProfile) || $hasActiveApprovedMagang) {
            LogHelper::fail($request, 'Pengajuan Pendaftaran Ditolak', 'Pendaftar saat ini masih berstatus aktif sebagai peserta magang di BPS.');
            return back()->with('error', 'Anda saat ini sedang aktif menjalani program magang di BPS.');
        }

        $activeSub = InternshipApplication::whereIn('status', ['pending', 'rescheduled', 'resignation_requested'])
            ->where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', fn($sub) => $sub->where('user_id', $user->id));
            })
            ->first();

        if ($activeSub) {
            $kelMsg = $activeSub->type === 'kelompok' 
                ? "kelompok '{$activeSub->group_name}'"
                : "permohonan magang";
            LogHelper::fail($request, 'Pengajuan Pendaftaran Ditolak', "Pendaftar sudah terdaftar dalam {$kelMsg} yang sedang aktif diproses.");
            return back()->with('error', "Anda sudah terdaftar dalam {$kelMsg} yang sedang aktif diproses.");
        }

        $divisionId = $request->division_id ?? $request->divisi_id;
        $startDate = $request->start_date ?? $request->tanggal_mulai;
        $endDate = $request->end_date ?? $request->tanggal_selesai;
        $groupName = $request->group_name ?? $request->nama_kelompok;
        $memberUserIdsInput = $request->member_user_ids ?? $request->anggota_user_id;

        $validator = Validator::make([
            'division_id' => $divisionId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'group_name' => $groupName,
            'member_user_ids' => $memberUserIdsInput,
        ], [
            'division_id' => 'required|exists:divisions,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'group_name' => 'nullable|string|max:100',
            'member_user_ids' => 'nullable|array',
            'member_user_ids.*' => 'distinct|exists:users,id',
        ], [
            'division_id.required' => 'Pilih divisi magang tujuan.',
            'division_id.exists' => 'Divisi magang yang dipilih tidak valid.',
            'start_date.required' => 'Tanggal mulai magang wajib diisi.',
            'start_date.after_or_equal' => 'Tanggal mulai magang tidak boleh di masa lalu.',
            'end_date.required' => 'Tanggal selesai magang wajib diisi.',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Pengajuan Pendaftaran (Salah Isi)', $validator->errors());
            return back()->withErrors($validator)->withInput();
        }

        $division = Division::findOrFail($divisionId);

        $anggotaUserIds = [];
        if (is_array($memberUserIdsInput)) {
            $anggotaUserIds = array_values(array_unique(array_filter($memberUserIdsInput, fn($id) => (int)$id !== (int)$user->id)));
        }

        $isKelompok = count($anggotaUserIds) > 0;
        $totalAnggota = 1 + count($anggotaUserIds);

        DB::beginTransaction();
        try {
            $lockedDivision = Division::where('id', $division->id)->lockForUpdate()->firstOrFail();

            foreach ($anggotaUserIds as $anggotaId) {
                $memberUser = User::with(['participantProfile', 'studentProfile'])->find($anggotaId);
                if (!$memberUser) {
                    DB::rollBack();
                    return back()->withInput()->with('error', "Data rekan anggota tidak ditemukan.");
                }

                $memberIsSiswa = ($memberUser->role === 'siswa');
                $memberHasActiveApproved = InternshipApplication::where('status', 'approved')
                    ->whereDate('end_date', '>=', now()->toDateString())
                    ->where(function ($q) use ($memberUser) {
                        $q->where('leader_user_id', $memberUser->id)
                          ->orWhereHas('members', fn($sub) => $sub->where('user_id', $memberUser->id));
                    })->exists();

                $memberHasActiveStudent = $memberUser->studentProfile()
                    ->whereDate('end_date', '>=', now()->toDateString())
                    ->exists();

                if (($memberIsSiswa && $memberHasActiveStudent) || $memberHasActiveApproved) {
                    DB::rollBack();
                    LogHelper::fail($request, 'Pengajuan Pendaftaran Ditolak (Rekan Sedang Magang)', "Rekan @{$memberUser->username} sedang aktif sebagai peserta magang di BPS.");
                    return back()->withInput()->with('error', "Rekan @{$memberUser->username} tidak dapat ditambahkan karena saat ini sedang aktif sebagai peserta magang di BPS.");
                }

                $memberActiveApp = InternshipApplication::whereIn('status', ['pending', 'rescheduled', 'resignation_requested'])
                    ->where(function ($q) use ($memberUser) {
                        $q->where('leader_user_id', $memberUser->id)
                          ->orWhereHas('members', fn($sub) => $sub->where('user_id', $memberUser->id));
                    })
                    ->first();

                if ($memberActiveApp) {
                    $conflictKel = $memberActiveApp->type === 'kelompok'
                        ? "kelompok '{$memberActiveApp->group_name}'"
                        : "pengajuan mandiri";
                    DB::rollBack();
                    LogHelper::fail($request, 'Pengajuan Pendaftaran Ditolak (Rekan Sudah Terdaftar)', "Rekan @{$memberUser->username} sudah tergabung dalam {$conflictKel}.");
                    return back()->withInput()->with('error', "Rekan @{$memberUser->username} sudah tergabung dalam {$conflictKel} yang sedang diproses.");
                }

                $memberProfile = $memberUser->participantProfile;
                if (!$memberProfile || empty($memberProfile->full_name) || empty($memberProfile->cv_path)) {
                    DB::rollBack();
                    LogHelper::fail($request, 'Pengajuan Pendaftaran Ditolak (Rekan Belum Lengkap Biodata)', "Rekan @{$memberUser->username} belum melengkapi biodata dan berkas CV.");
                    return back()->withInput()->with('error', "Rekan @{$memberUser->username} belum melengkapi biodata dan berkas CV.");
                }
            }

            $finalGroupName = null;
            if ($isKelompok) {
                $finalGroupName = !empty($groupName)
                    ? $groupName 
                    : 'Kelompok ' . ($userProfile->full_name ?: $user->username);
            }

            $application = InternshipApplication::create([
                'leader_user_id' => $user->id,
                'division_id' => $lockedDivision->id,
                'type' => $isKelompok ? 'kelompok' : 'individu',
                'group_name' => $finalGroupName,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'pending',
            ]);

            ApplicationMember::create([
                'application_id' => $application->id,
                'user_id' => $user->id,
                'full_name' => $userProfile->full_name,
                'institution' => $userProfile->institution,
                'major' => $userProfile->major,
                'phone' => $userProfile->phone,
                'cv_path' => $userProfile->cv_path,
                'portofolio_path' => $userProfile->portofolio_path ?: $userProfile->portofolio_url,
                'is_leader' => true,
            ]);

            foreach ($anggotaUserIds as $anggotaId) {
                $memberUser = User::with('participantProfile')->findOrFail($anggotaId);
                $memberProfile = $memberUser->participantProfile;

                ApplicationMember::create([
                    'application_id' => $application->id,
                    'user_id' => $memberUser->id,
                    'full_name' => $memberProfile->full_name,
                    'institution' => $memberProfile->institution,
                    'major' => $memberProfile->major,
                    'phone' => $memberProfile->phone,
                    'cv_path' => $memberProfile->cv_path,
                    'portofolio_path' => $memberProfile->portofolio_path ?: $memberProfile->portofolio_url,
                    'is_leader' => false,
                ]);
            }

            DB::commit();

            LogHelper::success(
                $request,
                'Pengajuan Pendaftaran Magang Berhasil',
                "User {$user->username} berhasil mengajukan pendaftaran magang ID #{$application->id} pada divisi {$lockedDivision->name} untuk {$totalAnggota} orang ({$startDate} s.d. {$endDate})."
            );

            return redirect()->route('pendaftaran.index', ['tab' => 'status'])
                ->with('success', 'Pendaftaran magang berhasil diajukan! Seluruh biodata dan berkas telah tersimpan untuk diverifikasi oleh pihak BPS.');
        } catch (\Exception $e) {
            DB::rollBack();
            LogHelper::fail($request, 'Pengajuan Pendaftaran Gagal (Error)', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memproses pendaftaran: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, $id)
    {
        $user = Auth::user();
        $application = InternshipApplication::where('id', $id)
            ->where('leader_user_id', $user->id)
            ->firstOrFail();

        if ($application->status === 'approved') {
            LogHelper::fail($request, 'Pembatalan Pendaftaran Ditolak', "User {$user->username} mencoba membatalkan pendaftaran ID #{$id} yang sudah disetujui.");
            return back()->with('error', 'Permohonan pendaftaran yang telah disetujui tidak dapat dibatalkan.');
        }

        $applicantName = $user->participantProfile->full_name ?? $user->username;
        $application->update([
            'status' => 'cancelled',
            'admin_note' => 'Permohonan pendaftaran dibatalkan oleh pemohon (' . $applicantName . ').',
        ]);

        LogHelper::success($request, 'Batalkan Pendaftaran Berhasil', "User {$user->username} membatalkan pendaftaran magang ID #{$id}. Status dicatat sebagai dibatalkan.");

        return redirect()->route('pendaftaran.index', ['tab' => 'pendaftaran'])
            ->with('success', 'Permohonan pendaftaran magang Anda berhasil dibatalkan. Riwayat pembatalan tercatat dan Anda dapat memilih divisi atau jadwal magang baru.');
    }

    public function ajukanUndurDiri(Request $request, $id)
    {
        $user = Auth::user();
        $application = InternshipApplication::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', fn ($m) => $m->where('user_id', $user->id));
            })
            ->firstOrFail();

        if ($application->status !== 'approved') {
            LogHelper::fail($request, 'Pengajuan Pengunduran Diri Ditolak', "User {$user->username} mencoba mengajukan pengunduran diri pada ID #{$id} yang statusnya {$application->status} (bukan approved).");
            return back()->with('error', 'Pengunduran diri hanya dapat diajukan untuk permohonan magang yang telah disetujui.');
        }

        $reason = $request->resignation_reason ?? $request->alasan_undur_diri;
        $proofFileKey = $request->hasFile('resignation_proof') ? 'resignation_proof' : 'bukti_undur_diri';

        $validator = Validator::make([
            'reason' => $reason,
            'proof' => $request->file($proofFileKey),
        ], [
            'reason' => 'required|string|min:10|max:1000',
            'proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'reason.required' => 'Alasan pengunduran diri wajib diisi.',
            'reason.min' => 'Alasan pengunduran diri minimal 10 karakter.',
            'proof.required' => 'Berkas bukti pengunduran diri wajib diunggah.',
            'proof.mimes' => 'Format berkas bukti harus berupa PDF, JPG, JPEG, atau PNG.',
            'proof.max' => 'Ukuran berkas bukti maksimal 5MB.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Pengajuan Pengunduran Diri (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput()->with('modal_error_target', 'undurDiriModal');
        }

        $proofPath = $request->file($proofFileKey)->store('undur_diri', 'public');

        $history = $application->resignation_history ?? [];
        $history[] = [
            'action' => 'requested',
            'status_label' => 'Mengajukan Pengunduran Diri',
            'actor' => $user->username,
            'reason' => $reason,
            'proof' => $proofPath,
            'created_at' => now()->translatedFormat('d F Y, H:i'),
        ];

        $application->update([
            'status' => 'resignation_requested',
            'resignation_reason' => $reason,
            'resignation_proof' => $proofPath,
            'resignation_requested_at' => now(),
            'resignation_history' => $history,
        ]);

        LogHelper::success($request, 'Pengajuan Pengunduran Diri Berhasil', "User {$user->username} mengajukan pengunduran diri magang pada ID #{$id}. Menunggu persetujuan Admin/Pegawai.");

        $successMsg = 'Permohonan pengunduran diri dan dokumen bukti Anda berhasil dikirimkan! Saat ini pengajuan sedang menunggu verifikasi & persetujuan dari pihak BPS.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'redirect' => route('pendaftaran.index', ['tab' => 'status'])
            ]);
        }

        return redirect()->route('pendaftaran.index', ['tab' => 'status'])
            ->with('success', $successMsg);
    }

    public function batalUndurDiri(Request $request, $id)
    {
        $user = Auth::user();
        $application = InternshipApplication::with(['division', 'members.user'])
            ->where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', function ($aq) use ($user) {
                      $aq->where('user_id', $user->id);
                  });
            })
            ->firstOrFail();

        if ($application->status !== 'resignation_requested') {
            $msg = 'Pengajuan pengunduran diri tidak dapat dibatalkan karena status permohonan saat ini bukan dalam proses pengunduran diri.';
            LogHelper::fail($request, 'Batal Pengunduran Diri Gagal', "Pendaftaran #{$id} status bukan resignation_requested.");
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $proofFile = $application->resignation_proof;
        if ($proofFile && Storage::disk('public')->exists($proofFile)) {
            Storage::disk('public')->delete($proofFile);
        }

        $history = $application->resignation_history ?? [];
        $history[] = [
            'action' => 'cancelled',
            'status_label' => 'Pengunduran Diri Dibatalkan Peserta',
            'actor' => $user->username,
            'reason' => $application->resignation_reason,
            'created_at' => now()->translatedFormat('d F Y, H:i'),
        ];

        $application->update([
            'status' => 'approved',
            'resignation_reason' => null,
            'resignation_proof' => null,
            'resignation_requested_at' => null,
            'resignation_admin_note' => null,
            'resignation_approved_by' => null,
            'resignation_approved_at' => null,
            'resignation_history' => $history,
        ]);

        LogHelper::success($request, 'Batalkan Pengunduran Diri Berhasil', "User {$user->username} membatalkan pengajuan pengunduran diri pada pendaftaran ID #{$id}. Status dikembalikan menjadi disetujui (approved).");

        $successMsg = 'Pengajuan pengunduran diri Anda berhasil dibatalkan. Status magang Anda tetap aktif sebagai Disetujui (Approved).';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'redirect' => route('pendaftaran.index', ['tab' => 'status'])
            ]);
        }

        return redirect()->route('pendaftaran.index', ['tab' => 'status'])
            ->with('success', $successMsg);
    }

    public function reapply(Request $request, $id)
    {
        return redirect()->route('pendaftaran.index', ['tab' => 'pendaftaran'])
            ->with('success', 'Silakan pilih divisi dan tentukan tanggal magang baru pada formulir pendaftaran.');
    }

    public function acceptReschedule(Request $request, $id)
    {
        $user = Auth::user();
        $application = InternshipApplication::with(['division', 'members.user'])
            ->where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', function ($aq) use ($user) {
                      $aq->where('user_id', $user->id);
                  });
            })
            ->firstOrFail();

        if ($application->status !== 'rescheduled') {
            LogHelper::fail($request, 'Terima Revisi Jadwal Gagal', "Pendaftaran #{$id} status bukan rescheduled.");
            return back()->with('error', 'Pengajuan ini tidak memiliki usulan revisi jadwal yang aktif.');
        }

        $saranMulai = $application->suggested_start_date;
        $saranSelesai = $application->suggested_end_date;

        if (!$saranMulai || !$saranSelesai) {
            LogHelper::fail($request, 'Terima Revisi Jadwal Gagal', "Data tanggal usulan revisi pendaftaran #{$id} tidak valid.");
            return back()->with('error', 'Data tanggal usulan revisi tidak valid.');
        }

        $startDateStr = Carbon::parse($saranMulai)->format('Y-m-d');
        $endDateStr = Carbon::parse($saranSelesai)->format('Y-m-d');

        $totalMembers = $application->members->count() ?: 1;
        $ketersediaan = $application->division->checkAvailability(
            $startDateStr,
            $endDateStr,
            $totalMembers
        );

        if (!$ketersediaan['available']) {
            LogHelper::fail($request, 'Terima Revisi Jadwal Gagal (Kuota Penuh)', "Kuota Divisi {$application->division->name} untuk jadwal saran penuh.");
            return back()->with('error', "Mohon maaf, kuota Divisi {$application->division->name} untuk jadwal saran tersebut telah penuh. Silakan hubungi narahubung BPS.");
        }

        $defaultBuilding = Building::first();
        if (!$defaultBuilding) {
            $defaultBuilding = Building::create([
                'name' => 'Kantor BPS Kota Malang',
                'latitude' => -7.983908,
                'longitude' => 112.621391,
                'radius_meter' => 100,
            ]);
        }

        DB::beginTransaction();
        try {
            $application->update([
                'start_date' => $startDateStr,
                'end_date' => $endDateStr,
                'status' => 'approved',
                'approved_by' => $application->rescheduled_by ?: $user->id,
                'approved_at' => now(),
            ]);

            foreach ($application->members as $member) {
                $memberUser = $member->user;
                if ($memberUser) {
                    $memberUser->update(['role' => 'siswa']);

                    StudentProfile::updateOrCreate(
                        ['user_id' => $memberUser->id],
                        [
                            'full_name' => $member->full_name,
                            'institution' => $member->institution,
                            'major' => $member->major,
                            'building_id' => $defaultBuilding ? $defaultBuilding->id : 1,
                            'division_id' => $application->division_id,
                            'start_date' => $startDateStr,
                            'end_date' => $endDateStr,
                        ]
                    );
                }
            }

            DB::commit();

            LogHelper::success($request, 'Terima Revisi Jadwal Berhasil', "Peserta {$user->username} menyetujui revisi jadwal pendaftaran ID #{$application->id}. Status langsung disetujui (Approved).");

            return redirect()->route('pendaftaran.index', ['tab' => 'status'])
                ->with('success', 'Selamat! Anda telah menyetujui usulan jadwal baru. Pengajuan magang Anda RESMI DITERIMA untuk periode ' . Carbon::parse($startDateStr)->format('d/m/Y') . ' s.d. ' . Carbon::parse($endDateStr)->format('d/m/Y') . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            LogHelper::fail($request, 'Terima Revisi Jadwal Error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses persetujuan jadwal: ' . $e->getMessage());
        }
    }

    public function rejectReschedule(Request $request, $id)
    {
        $reason = $request->rejection_reason ?? $request->alasan_penolakan;

        $validator = Validator::make([
            'reason' => $reason,
        ], [
            'reason' => 'required|string|max:1000',
        ], [
            'reason.required' => 'Mohon sertakan alasan penolakan jadwal baru.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Tolak Revisi Jadwal (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput()->with('modal_error_target', 'rejectRescheduleModal');
        }

        $user = Auth::user();
        $application = InternshipApplication::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('leader_user_id', $user->id)
                  ->orWhereHas('members', function ($aq) use ($user) {
                      $aq->where('user_id', $user->id);
                  });
            })
            ->firstOrFail();

        if ($application->status !== 'rescheduled') {
            $errMsg = 'Pengajuan ini tidak memiliki usulan revisi jadwal yang aktif.';
            LogHelper::fail($request, 'Tolak Revisi Jadwal Gagal', "Pendaftaran #{$id} status bukan rescheduled.");
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errMsg], 422);
            }
            return back()->with('error', $errMsg)->with('modal_error_target', 'rejectRescheduleModal');
        }

        $application->update([
            'status' => 'rejected',
            'applicant_rejection_reason' => $reason,
            'admin_note' => 'Usulan jadwal baru tidak dapat disetujui oleh peserta. Alasan: ' . $reason,
        ]);

        LogHelper::success($request, 'Tolak Revisi Jadwal Berhasil', "Peserta {$user->username} menolak usulan jadwal pendaftaran ID #{$application->id}. Alasan: {$reason}");

        $infoMsg = 'Penolakan usulan jadwal telah tersimpan. Status pengajuan Anda telah ditutup. Anda dapat mengajukan pendaftaran baru dengan periode lain jika berkenan.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $infoMsg,
                'redirect' => route('pendaftaran.index', ['tab' => 'status'])
            ]);
        }

        return redirect()->route('pendaftaran.index', ['tab' => 'status'])
            ->with('info', $infoMsg);
    }
}
