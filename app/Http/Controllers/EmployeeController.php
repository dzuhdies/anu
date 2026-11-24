<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogHelper;
use App\Models\StudentProfile;
use App\Models\Building;
use App\Models\EmployeeProfile;
use Carbon\Carbon;
use App\Exports\RekapAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Setting;
use App\Models\InternshipApplication;
use App\Models\Division;
use App\Models\Attendance;
use App\Models\StudentMonthlyProgress;

class EmployeeController extends Controller
{
    protected function parseFlexibleDate(?string $val): ?string
    {
        if (!$val) return null;
        $val = trim($val);
        try {
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $val, $m)) {
                return Carbon::createFromDate((int)$m[3], (int)$m[2], (int)$m[1])->toDateString();
            }
            return Carbon::parse($val)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rekapAbsensi(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employeeProfile;
        $building = $employee?->building ?? Building::first();
        $employeeDivision = $employee?->division;
        $jamTelat = Setting::where('key', 'jam_telat')->value('value') ?? '08:16:00';

        $dariInput = $request->input('dari');
        $keInput = $request->input('ke');
        $bulan = $request->input('bulan');
        $tanggal = $request->input('tanggal');

        $dari = $this->parseFlexibleDate($dariInput);
        $ke = $this->parseFlexibleDate($keInput);

        if ($dari && !$ke) {
            $ke = $dari;
        } elseif (!$dari && $ke) {
            $dari = $ke;
        } elseif ($dari && $ke && $dari > $ke) {
            $temp = $dari;
            $dari = $ke;
            $ke = $temp;
        }

        if ($dari && $ke) {
            if ($dari === $ke) {
                $tanggal = $dari;
                $bulan = null;
                $displayMode = 'daily';
            } else {
                $tanggal = null;
                $bulan = null;
                $displayMode = 'range';
            }
        } elseif ($bulan) {
            $targetMonth = Carbon::parse($bulan);
            $dari = $targetMonth->copy()->startOfMonth()->toDateString();
            $ke = $targetMonth->copy()->endOfMonth()->toDateString();
            $tanggal = null;
            $displayMode = 'monthly';
        } else {
            $tanggal = $tanggal ? ($this->parseFlexibleDate($tanggal) ?: now()->toDateString()) : now()->toDateString();
            $dari = $tanggal;
            $ke = $tanggal;
            $bulan = null;
            $displayMode = 'daily';
        }

        $todayDate = Carbon::today()->toDateString();

        // Base student query: pastikan TIDAK PERNAH memuat user dengan role 'admin'
        // dan jika pegawai memegang "Seluruh Divisi" (division_id null), jangan batasi building_id
        $baseStudentQuery = StudentProfile::query()
            ->whereHas('user', fn ($u) => $u->where('role', '!=', 'admin'));

        if ($employee && $employee->division_id) {
            $baseStudentQuery->where('division_id', $employee->division_id);
        }

        // Daftar Siswa Magang Aktif: hanya siswa yang belum selesai (end_date belum terlewat)
        $all_siswas = (clone $baseStudentQuery)
            ->where(function ($q) use ($todayDate) {
                $q->whereNull('end_date')
                  ->orWhereDate('end_date', '>=', $todayDate);
            })
            ->with(['division', 'building', 'user.participantProfile'])
            ->orderBy('full_name', 'asc')
            ->get();

        $queryStudent = clone $baseStudentQuery;

        if ($displayMode === 'range') {
            $queryStudent->where(function ($q) use ($dari, $ke) {
                $q->where(function ($periodQ) use ($dari, $ke) {
                    $periodQ->where('start_date', '<=', $ke)
                            ->where('end_date', '>=', $dari);
                })->orWhereHas('attendances', function ($absQ) use ($dari, $ke) {
                    $absQ->whereBetween('date', [$dari, $ke]);
                });
            });
        } elseif ($displayMode === 'monthly') {
            $targetMonth = Carbon::parse($bulan);
            $startOfMonth = $targetMonth->copy()->startOfMonth()->toDateString();
            $endOfMonth = $targetMonth->copy()->endOfMonth()->toDateString();

            $queryStudent->where(function ($q) use ($startOfMonth, $endOfMonth, $targetMonth) {
                $q->where(function ($periodQ) use ($startOfMonth, $endOfMonth) {
                    $periodQ->where('start_date', '<=', $endOfMonth)
                            ->where('end_date', '>=', $startOfMonth);
                })->orWhereHas('attendances', function ($absQ) use ($targetMonth) {
                    $absQ->whereYear('date', $targetMonth->year)
                         ->whereMonth('date', $targetMonth->month);
                });
            });
        } else {
            $targetDate = $dari ?: $tanggal ?: $todayDate;
            $queryStudent->where(function ($q) use ($targetDate) {
                $q->where(function ($periodQ) use ($targetDate) {
                    $periodQ->where('start_date', '<=', $targetDate)
                            ->where('end_date', '>=', $targetDate);
                })->orWhereHas('attendances', function ($absQ) use ($targetDate) {
                    $absQ->whereDate('date', $targetDate);
                });
            });
        }

        $attendanceQuery = function ($q) use ($displayMode, $dari, $ke, $bulan, $tanggal) {
            $q->with('leaveType');
            if ($displayMode === 'range') {
                $q->whereBetween('date', [$dari, $ke]);
            } elseif ($displayMode === 'monthly') {
                $date = Carbon::parse($bulan);
                $q->whereYear('date', $date->year)->whereMonth('date', $date->month);
            } else {
                $q->whereDate('date', $dari ?: $tanggal);
            }
            $q->orderBy('date', 'desc');
        };

        $siswas = $queryStudent->with([
            'attendances' => $attendanceQuery,
            'absensis' => $attendanceQuery,
            'division',
            'building',
            'user.participantProfile'
        ])
            ->orderBy('full_name', 'asc')
            ->get();
        $this->setStudentAbsensiPhotoUrls($siswas);

        $applicationQuery = InternshipApplication::with(['division', 'divisi', 'members.user', 'anggotas.user', 'leader', 'ketua'])
            ->whereHas('leader', fn ($u) => $u->where('role', '!=', 'admin'));
        if ($employee && $employee->division_id) {
            $applicationQuery->where('division_id', $employee->division_id);
        }
        $pendaftarans = $applicationQuery->latest()->get();
        
        // Attach min_remaining_quota to each application for the UI
        foreach ($pendaftarans as $p) {
            if (($p->status === 'pending' || $p->status === 'rescheduled') && $p->division) {
                $check = $p->division->checkAvailability(
                    $p->start_date->toDateString(), 
                    $p->end_date->toDateString(), 
                    $p->members->count()
                );
                $p->min_remaining_quota = $check['min_remaining_quota'];
            }
        }

        $pendingCount = $pendaftarans->where('status', 'pending')->count();
        $divisis = Division::where('is_active', true)->orderBy('name')->get();

        // Logbook: jumlah yang menunggu review (dibatasi divisi pegawai bila ada, tanpa role admin)
        $logbookQuery = \App\Models\Logbook::query()
            ->whereHas('student.user', fn ($u) => $u->where('role', '!=', 'admin'));
        if ($employee && $employee->division_id) {
            $logbookQuery->whereHas('student', fn ($q) => $q->where('division_id', $employee->division_id));
        }
        $logbookPendingCount = (clone $logbookQuery)->where('status', \App\Models\Logbook::STATUS_PENDING)->count();
        $logbookReviewEnabled = \App\Models\Setting::where('key', 'logbook_review_enabled')->value('value');
        $logbookReviewEnabled = $logbookReviewEnabled === null || $logbookReviewEnabled === '1' || $logbookReviewEnabled === 'true';

        // Data review logbook (untuk tab Logbook Magang)
        $logbookBase = (clone $logbookQuery);
        if ($request->filled('status')) {
            $logbookBase->where('status', $request->status);
        }
        // Filter tanggal: dukung rentang (dari/ke) seperti Rekap Presensi, fallback tanggal tunggal
        $logDari = $request->filled('dari') ? $request->dari : ($request->filled('tanggal') ? $request->tanggal : null);
        $logKe = $request->filled('ke') ? $request->ke : ($request->filled('tanggal') ? $request->tanggal : null);
        if ($logDari && $logKe) {
            if ($logDari > $logKe) { $tmp = $logDari; $logDari = $logKe; $logKe = $tmp; }
            $logbookBase->whereBetween('date', [$logDari, $logKe]);
        } elseif ($logDari) {
            $logbookBase->whereDate('date', $logDari);
        } elseif ($logKe) {
            $logbookBase->whereDate('date', $logKe);
        }
        if ($request->filled('q')) {
            $term = '%' . trim($request->q) . '%';
            $logbookBase->whereHas('student', fn ($q) => $q
                ->where('full_name', 'like', $term)
                ->orWhere('institution', 'like', $term));
        }

        $logbooks = (clone $logbookBase)
            ->with(['student.division', 'student.building', 'reviewer'])
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $logbookStats = [
            'total' => (clone $logbookQuery)->count(),
            'pending' => (clone $logbookQuery)->where('status', \App\Models\Logbook::STATUS_PENDING)->count(),
            'approved' => (clone $logbookQuery)->where('status', \App\Models\Logbook::STATUS_APPROVED)->count(),
            'rejected' => (clone $logbookQuery)->where('status', \App\Models\Logbook::STATUS_REJECTED)->count(),
            'today' => (clone $logbookQuery)->whereDate('date', \Carbon\Carbon::today())->count(),
        ];

        $logbookPayload = $logbooks->getCollection()->map(function (\App\Models\Logbook $logbook) {
            return [
                'id' => $logbook->id,
                'student' => $logbook->student->full_name ?? 'Peserta Magang',
                'institution' => $logbook->student->institution ?: '-',
                'division' => $logbook->student->division->nama_divisi ?? '-',
                'date' => $logbook->date->locale('id')->translatedFormat('l, d F Y'),
                'activity' => $logbook->activity,
                'result' => $logbook->result,
                'status' => $logbook->status,
                'status_label' => $logbook->status_label,
                'status_badge' => $logbook->status_badge,
                'review_note' => $logbook->review_note,
                'reviewer' => $logbook->reviewer->username ?? null,
                'reviewed_at' => $logbook->reviewed_at?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB',
                'approve_url' => route('pegawai.logbook.approve', $logbook),
                'reject_url' => route('pegawai.logbook.reject', $logbook),
            ];
        })->values();

        // Query berkas progres bulanan untuk review pegawai
        $progresQuery = StudentMonthlyProgress::with(['student.division', 'student.building', 'reviewer'])
            ->whereHas('student.user', fn ($u) => $u->where('role', '!=', 'admin'))
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('updated_at');

        if ($employee && $employee->division_id) {
            $progresQuery->whereHas('student', fn ($q) => $q->where('division_id', $employee->division_id));
        }

        if ($request->filled('q')) {
            $term = '%' . trim($request->q) . '%';
            $progresQuery->whereHas('student', fn ($q) => $q
                ->where('full_name', 'like', $term)
                ->orWhere('institution', 'like', $term));
        }

        if ($request->filled('progres_status')) {
            $progresQuery->where('status', $request->progres_status);
        }

        $monthlyProgresses = $progresQuery->paginate(15, ['*'], 'progres_page')->withQueryString();
        $monthlyProgressPendingCount = StudentMonthlyProgress::where('status', StudentMonthlyProgress::STATUS_PENDING)->count();

        // Data Peserta Magang Aktif & Progres Bulanan Terstruktur (Layout Sidebar + Detail)
        // Bebas dari filter tanggal rekap absensi & tidak dibatasi building jika "Seluruh Divisi"
        $activeStudentsForProgress = (clone $baseStudentQuery)
            ->where(function ($q) use ($todayDate) {
                $q->whereNull('end_date')
                  ->orWhereDate('end_date', '>=', $todayDate);
            })
            ->with(['division', 'building', 'user.participantProfile', 'monthlyProgresses.reviewer'])
            ->orderBy('full_name', 'asc')
            ->get();

        $progressStudentsPayload = $activeStudentsForProgress->map(function ($st) {
            $start = $st->start_date ? Carbon::parse($st->start_date)->startOfMonth() : null;
            $end = $st->end_date ? Carbon::parse($st->end_date)->startOfMonth() : null;

            $months = [];
            $uploadedCount = 0;
            $approvedCount = 0;

            if ($start && $end && $start <= $end) {
                $cur = $start->copy();
                while ($cur <= $end) {
                    $m = $cur->month;
                    $y = $cur->year;
                    $prog = $st->monthlyProgresses->firstWhere(fn ($p) => $p->month == $m && $p->year == $y);

                    if ($prog) {
                        $uploadedCount++;
                        if ($prog->status === 'approved') {
                            $approvedCount++;
                        }
                    }

                    $months[] = [
                        'month' => $m,
                        'year' => $y,
                        'month_name' => $cur->locale('id')->isoFormat('MMMM Y'),
                        'progress_id' => $prog?->id,
                        'file_name' => $prog?->file_name,
                        'file_size_formatted' => $prog?->file_size_formatted,
                        'download_url' => $prog ? route('siswa.progres.download', $prog->id) : null,
                        'status' => $prog ? $prog->status : 'unsubmitted',
                        'status_badge' => $prog ? $prog->status_badge : 'bg-gray-100 text-gray-500 border-gray-200',
                        'status_label' => $prog ? ($prog->status === 'approved' ? 'Disetujui (ACC)' : ($prog->status === 'rejected' ? 'Perlu Perbaikan' : 'Menunggu Review')) : 'Belum Dikumpulkan',
                        'notes' => $prog?->notes,
                        'feedback' => $prog?->feedback,
                        'uploaded_at' => $prog?->updated_at?->locale('id')->isoFormat('D MMM Y, HH:mm'),
                        'approve_url' => $prog ? route('pegawai.progres.approve', $prog->id) : null,
                    ];
                    $cur->addMonth();
                }
            }

            $totalMonths = count($months);
            $isComplete = ($totalMonths > 0 && $approvedCount === $totalMonths);

            $cert = $st->certificate ?? $st->user?->certificate;

            return [
                'id' => $st->id,
                'name' => $st->full_name,
                'institution' => $st->institution ?: ($st->user?->participantProfile?->institution ?: '-'),
                'major' => $st->user?->participantProfile?->major ?? '-',
                'phone' => $st->user?->participantProfile?->phone ?? '-',
                'email' => $st->user?->email ?? '-',
                'division' => $st->division?->nama_divisi ?? '-',
                'start_date' => $st->start_date ? Carbon::parse($st->start_date)->format('d/m/Y') : '-',
                'end_date' => $st->end_date ? Carbon::parse($st->end_date)->format('d/m/Y') : '-',
                'total_months' => $totalMonths,
                'uploaded_count' => $uploadedCount,
                'approved_count' => $approvedCount,
                'is_complete' => $isComplete,
                'final_message' => $st->final_message,
                'save_message_url' => route('pegawai.siswa.final_message', $st->id),
                'has_certificate' => (bool)$cert,
                'certificate_id' => $cert?->id,
                'certificate_number' => $cert?->certificate_number,
                'certificate_url' => $cert ? route('sertifikat.show', $cert->id) : null,
                'default_cert_number' => 'B-' . str_pad($st->id, 3, '0', STR_PAD_LEFT) . '/35730/KP.010/' . now()->format('m/Y'),
                'months' => $months,
            ];
        });

        // Data Riwayat Alumni Magang (History) - HANYA yang sudah selesai magang
        $historyQuery = (clone $baseStudentQuery)
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', $todayDate);

        $historyStudentsRaw = $historyQuery
            ->with([
                'division',
                'building',
                'user.participantProfile',
                'user.ledApplications',
                'user.memberApplications',
                'attendances.leaveType',
                'logbooks',
                'monthlyProgresses',
                'certificate',
            ])
            ->orderBy('end_date', 'desc')
            ->get();

        $historyStudents = $historyStudentsRaw->map(function ($st) use ($todayDate) {
            $user = $st->user;
            $app = $user ? ($user->activeApplication() ?: $user->ledApplications->first() ?: $user->memberApplications->first()) : null;
            $cert = $st->certificate ?? $user?->certificate;

            $rHistory = $app?->resignation_history ?? [];
            if (empty($rHistory) && $app?->alasan_undur_diri) {
                $rHistory[] = [
                    'action' => $app->status === 'resigned' ? 'approved' : ($app->status === 'approved' ? 'rejected' : 'requested'),
                    'status_label' => $app->status === 'resigned' ? 'Pengunduran Diri Disetujui' : ($app->status === 'approved' ? 'Pengunduran Diri Ditolak Pegawai' : 'Mengajukan Pengunduran Diri'),
                    'reason' => $app->alasan_undur_diri,
                    'proof' => $app->bukti_undur_diri ? (str_starts_with($app->bukti_undur_diri, 'http') ? $app->bukti_undur_diri : asset('storage/' . $app->bukti_undur_diri)) : null,
                    'note' => $app->catatan_undur_diri_admin,
                    'created_at' => $app->tgl_undur_diri ? $app->tgl_undur_diri->translatedFormat('d F Y, H:i') : '-',
                ];
            }

            $attendances = $st->attendances;
            $totalHari = $attendances->count();
            $totalHadir = $attendances->where('attendance_status', true)->count();
            $totalTelat = $attendances->where('punctuality_status', 'Terlambat')->count();
            $totalIzin = $attendances->where('is_permission', true)->count();
            $persenHadir = $totalHari > 0 ? round(($totalHadir / $totalHari) * 100) : 100;

            $pProfile = $st->user?->participantProfile;
            $cvUrl = $pProfile?->cv_path ? (str_starts_with($pProfile->cv_path, 'http') ? $pProfile->cv_path : asset('storage/' . $pProfile->cv_path)) : null;
            $portoUrl = $pProfile?->portofolio_path ? (str_starts_with($pProfile->portofolio_path, 'http') ? $pProfile->portofolio_path : asset('storage/' . $pProfile->portofolio_path)) : ($pProfile?->portofolio_url ?? null);

            $isEnded = $st->end_date && Carbon::parse($st->end_date)->endOfDay()->lt(now());

            return [
                'id' => $st->id,
                'name' => $st->full_name,
                'username' => $st->user?->username ?? '-',
                'email' => $st->user?->email ?? '-',
                'phone' => $pProfile?->phone ?? '-',
                'institution' => $st->institution ?: ($pProfile?->institution ?: '-'),
                'major' => $pProfile?->major ?? '-',
                'division' => $st->division?->nama_divisi ?? '-',
                'building' => $st->building?->name ?? 'Kantor BPS',
                'start_date' => $st->start_date ? Carbon::parse($st->start_date)->format('d/m/Y') : '-',
                'end_date' => $st->end_date ? Carbon::parse($st->end_date)->format('d/m/Y') : '-',
                'status' => $app?->status ?? ($isEnded ? 'completed' : 'active'),
                'is_ended' => $isEnded,
                'cv_url' => $cvUrl,
                'porto_url' => $portoUrl,
                'final_message' => $st->final_message,
                'has_certificate' => (bool)$cert && !in_array($app?->status, ['resigned', 'terminated']),
                'certificate_id' => $cert?->id,
                'certificate_number' => $cert?->certificate_number,
                'certificate_predicate' => $cert?->predicate,
                'certificate_url' => $cert ? route('sertifikat.show', $cert->id) : null,
                'resignation_history' => $rHistory,
                'stats' => [
                    'total_hari' => $totalHari,
                    'total_hadir' => $totalHadir,
                    'total_telat' => $totalTelat,
                    'total_izin' => $totalIzin,
                    'persen_hadir' => $persenHadir,
                    'total_logbook' => $st->logbooks->count(),
                    'approved_logbook' => $st->logbooks->where('status', 'approved')->count(),
                    'total_progres' => $st->monthlyProgresses->count(),
                    'approved_progres' => $st->monthlyProgresses->where('status', 'approved')->count(),
                ],
                'attendances' => $attendances->sortByDesc('date')->values()->map(fn ($att) => [
                    'tanggal' => Carbon::parse($att->date)->translatedFormat('l, d F Y'),
                    'jam_masuk' => $att->check_in_time ? Carbon::parse($att->check_in_time)->format('H:i') : null,
                    'jam_pulang' => $att->check_out_time ? Carbon::parse($att->check_out_time)->format('H:i') : null,
                    'status_kehadiran' => $att->punctuality_status ?: ($att->check_in_time ? 'Hadir' : ($att->is_permission ? 'Izin' : 'Tidak Hadir')),
                    'keterangan' => $att->leaveType?->name ?? ($att->is_wfh ? 'WFH' : 'WFO'),
                ]),
                'logbooks' => $st->logbooks->sortByDesc('date')->values()->map(fn ($lb) => [
                    'tanggal' => Carbon::parse($lb->date)->translatedFormat('l, d F Y'),
                    'activity' => $lb->activity,
                    'obstacle' => $lb->obstacle,
                    'status' => $lb->status,
                    'status_badge' => $lb->status_badge,
                ]),
                'monthly_progresses' => $st->monthlyProgresses->sortByDesc('month')->values()->map(fn ($mp) => [
                    'month_name' => Carbon::createFromDate($mp->year, $mp->month, 1)->locale('id')->isoFormat('MMMM Y'),
                    'file_name' => $mp->file_name,
                    'download_url' => route('siswa.progres.download', $mp->id),
                    'status' => $mp->status,
                    'status_badge' => $mp->status_badge,
                ]),
            ];
        });

        $activeTab = $request->input('tab', ($dariInput || $keInput || $tanggal || $bulan) ? 'absensi' : 'pendaftaran');

        $logMessage = 'Pegawai melihat dashboard presensi & seleksi';

        if ($displayMode === 'range') {
            $logMessage .= " periode $dari s/d $ke";
        } elseif ($tanggal) {
            $logMessage .= " pada tanggal: " . Carbon::parse($tanggal)->format('d M Y');
        } elseif ($bulan) {
            $logMessage .= " pada bulan: " . Carbon::parse($bulan)->format('F Y');
        } else {
            $logMessage .= " (semua data)";
        }

        if ($building) {
            $logMessage .= ' di gedung: ' . $building->name;
        }

        LogHelper::log($request, 'Lihat Rekap Absensi & Seleksi', $logMessage);

        // Keep view variables identical so Blade doesn't break
        $pegawai = $employee;
        $gedung = $building;
        $divisiPegawai = $employeeDivision;

        // Hitung sisa kuota hari ini untuk divisi pegawai (agar mudah dibaca di sidebar)
        $sisaKuotaHariIni = 0;
        if ($divisiPegawai) {
            $sisaKuotaHariIni = $divisiPegawai->getRemainingQuotaAttribute();
        }

        return view('pegawai.absensi', compact(
            'sisaKuotaHariIni',
            'siswas',
            'all_siswas',
            'tanggal',
            'gedung',
            'bulan',
            'dari',
            'ke',
            'displayMode',
            'jamTelat',
            'pegawai',
            'divisiPegawai',
            'pendaftarans',
            'pendingCount',
            'divisis',
            'activeTab',
            'logbookPendingCount',
            'logbookReviewEnabled',
            'logbooks',
            'logbookStats',
            'logbookPayload',
            'monthlyProgresses',
            'monthlyProgressPendingCount',
            'progressStudentsPayload',
            'historyStudents'
        ));
    }

    public function riwayatSiswa(Request $request, $id)
    {
        $user = Auth::user();
        $employee = $user->employeeProfile;
        $building = $employee?->building ?? Building::first();
        $jamTelat = Setting::where('key', 'jam_telat')->value('value') ?? '08:16:00';

        $siswa = StudentProfile::with(['division', 'building', 'user.participantProfile'])->findOrFail($id);

        if ($employee && $employee->division_id && $siswa->division_id != $employee->division_id) {
            abort(403, 'Anda tidak memiliki wewenang untuk melihat riwayat siswa di luar divisi penugasan.');
        }

        $tglMulai = $siswa->start_date;
        $tglSelesai = $siswa->end_date;
        $defaultDari = $tglMulai ? Carbon::parse($tglMulai)->toDateString() : now()->startOfMonth()->toDateString();
        $defaultKe = $tglSelesai ? Carbon::parse($tglSelesai)->toDateString() : now()->toDateString();

        $dariInput = $request->input('dari');
        $keInput = $request->input('ke');

        $dari = $dariInput ? ($this->parseFlexibleDate($dariInput) ?: $defaultDari) : $defaultDari;
        $ke = $keInput ? ($this->parseFlexibleDate($keInput) ?: $defaultKe) : $defaultKe;

        if ($dari > $ke) {
            $temp = $dari;
            $dari = $ke;
            $ke = $temp;
        }

        $absensis = Attendance::with('leaveType')
            ->where('student_id', $siswa->id)
            ->whereBetween('date', [$dari, $ke])
            ->orderBy('date', 'desc')
            ->get();

        foreach ($absensis as $ab) {
            $this->setAbsensiPhotoUrls($ab);
        }

        $totalHadir = 0;
        $totalTepatWaktu = 0;
        $totalTerlambat = 0;
        $totalIzin = 0;

        foreach ($absensis as $ab) {
            if ($ab->is_permission) {
                $totalIzin++;
            } else {
                $totalHadir++;
                if ($ab->punctuality_status === 'Terlambat') {
                    $totalTerlambat++;
                } else {
                    $totalTepatWaktu++;
                }
            }
        }

        LogHelper::log($request, 'Lihat Riwayat Siswa', "Pegawai melihat riwayat presensi siswa {$siswa->full_name} (Periode: $dari s/d $ke)");

        $pegawai = $employee;
        $gedung = $building;

        return view('pegawai.riwayat_siswa', compact(
            'siswa',
            'absensis',
            'dari',
            'ke',
            'defaultDari',
            'defaultKe',
            'totalHadir',
            'totalTepatWaktu',
            'totalTerlambat',
            'totalIzin',
            'jamTelat',
            'pegawai',
            'gedung'
        ));
    }

    public function kalenderKuota(Request $request)
    {
        $request->merge(['tab' => 'kalender']);
        return $this->rekapAbsensi($request);
    }

    public function exportRekap(Request $request)
    {
        $employee = Auth::user()->employeeProfile;
        $building = $employee?->building ?? Building::first();

        $query = StudentProfile::query();
        if ($employee && $employee->division_id) {
            $query->where('division_id', $employee->division_id);
        } elseif ($employee && $employee->building_id) {
            $query->where('building_id', $employee->building_id);
        }

        $displayMode = 'daily';
        $tanggal = $request->input('tanggal', now()->toDateString());
        if ($request->filled('bulan')) {
            $displayMode = 'monthly';
            list($tahun, $bulan) = explode('-', $request->bulan);
            $query->with(['attendances' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('date', $bulan)
                    ->whereYear('date', $tahun)
                    ->orderBy('date', 'asc');
            }]);
        } else {
            $query->with(['attendances' => function ($q) use ($tanggal) {
                $q->whereDate('date', $tanggal);
            }]);
        }

        if ($request->filled('siswa_id')) {
            $query->where('id', $request->siswa_id);
            if ($request->filled('bulan')) {
                $displayMode = 'user_history';
            }
        } else {
            if ($request->filled('bulan')) {
                $targetMonth = Carbon::parse($request->bulan);
                $startOfMonth = $targetMonth->copy()->startOfMonth()->toDateString();
                $endOfMonth = $targetMonth->copy()->endOfMonth()->toDateString();

                $query->where(function ($q) use ($startOfMonth, $endOfMonth, $targetMonth) {
                    $queryPeriod = function ($periodQ) use ($startOfMonth, $endOfMonth) {
                        $periodQ->where('start_date', '<=', $endOfMonth)
                                ->where('end_date', '>=', $startOfMonth);
                    };
                    $q->where($queryPeriod)->orWhereHas('attendances', function ($absQ) use ($targetMonth) {
                        $absQ->whereYear('date', $targetMonth->year)
                             ->whereMonth('date', $targetMonth->month);
                    });
                });
            } elseif ($tanggal) {
                $targetDate = Carbon::parse($tanggal)->toDateString();
                $query->where(function ($q) use ($targetDate) {
                    $queryPeriod = function ($periodQ) use ($targetDate) {
                        $periodQ->where('start_date', '<=', $targetDate)
                                ->where('end_date', '>=', $targetDate);
                    };
                    $q->where($queryPeriod)->orWhereHas('attendances', function ($absQ) use ($targetDate) {
                        $absQ->whereDate('date', $targetDate);
                    });
                });
            }
        }
        $siswas = $query->orderBy('full_name', 'asc')->get();
        $this->setStudentAbsensiPhotoUrls($siswas);
        $jamTelat = Setting::where('key', 'jam_telat')->value('value') ?? '08:16:00';

        $periode = $request->filled('bulan') ? $request->bulan : $tanggal;
        $filename = 'rekap-absensi-pegawai-' . $periode . '.xlsx';

        return Excel::download(new RekapAbsensiExport($siswas, $tanggal), $filename);
    }

    public function setWfh(Request $request, $id)
    {
        $studentProfile = StudentProfile::findOrFail($id);
        $mode = $request->input('mode', 'today'); // 'today', 'range', 'cancel'

        if ($mode === 'cancel') {
            $studentProfile->update([
                'wfh_start_date' => null,
                'wfh_end_date' => null,
                'wfh_notes' => null,
            ]);

            LogHelper::success(
                $request,
                'Cabut Izin WFH',
                "Pegawai mencabut izin WFH untuk siswa {$studentProfile->full_name}."
            );

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Izin WFH untuk {$studentProfile->full_name} berhasil dicabut.",
                    'data' => [
                        'wfh_start_date' => null,
                        'wfh_end_date' => null,
                        'is_wfh_today' => false,
                        'wfh_status_label' => 'WFO (Normal)',
                    ]
                ]);
            }

            return back()->with('success', "Izin WFH untuk {$studentProfile->full_name} berhasil dicabut.");
        }

        if ($mode === 'today') {
            $today = Carbon::today()->toDateString();
            $studentProfile->update([
                'wfh_start_date' => $today,
                'wfh_end_date' => $today,
                'wfh_notes' => $request->input('wfh_notes', 'WFH Hari Ini (' . Carbon::today()->translatedFormat('d F Y') . ')'),
            ]);

            LogHelper::success(
                $request,
                'Set WFH Hari Ini',
                "Pegawai menetapkan izin WFH hari ini ({$today}) untuk siswa {$studentProfile->full_name}."
            );

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Izin WFH hari ini untuk {$studentProfile->full_name} berhasil diaktifkan.",
                    'data' => [
                        'wfh_start_date' => $today,
                        'wfh_end_date' => $today,
                        'is_wfh_today' => true,
                        'wfh_status_label' => 'WFH Aktif (Hari Ini)',
                    ]
                ]);
            }

            return back()->with('success', "Izin WFH hari ini untuk {$studentProfile->full_name} berhasil diaktifkan.");
        }

        if ($mode === 'range') {
            $request->validate([
                'wfh_start_date' => 'required|date',
                'wfh_end_date' => 'required|date|after_or_equal:wfh_start_date',
            ], [
                'wfh_start_date.required' => 'Tanggal mulai WFH wajib diisi.',
                'wfh_end_date.required' => 'Tanggal selesai WFH wajib diisi.',
                'wfh_end_date.after_or_equal' => 'Tanggal selesai WFH tidak boleh lebih awal dari tanggal mulai.',
            ]);

            $startDate = Carbon::parse($request->wfh_start_date)->toDateString();
            $endDate = Carbon::parse($request->wfh_end_date)->toDateString();

            $studentProfile->update([
                'wfh_start_date' => $startDate,
                'wfh_end_date' => $endDate,
                'wfh_notes' => $request->input('wfh_notes', 'WFH ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y')),
            ]);

            $tglMulaiIndo = Carbon::parse($startDate)->translatedFormat('d M Y');
            $tglSelesaiIndo = Carbon::parse($endDate)->translatedFormat('d M Y');

            LogHelper::success(
                $request,
                'Set WFH Rentang Tanggal',
                "Pegawai menetapkan izin WFH untuk siswa {$studentProfile->full_name} periode {$startDate} s.d. {$endDate}."
            );

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Izin WFH untuk {$studentProfile->full_name} ({$tglMulaiIndo} s.d. {$tglSelesaiIndo}) berhasil diaktifkan.",
                    'data' => [
                        'wfh_start_date' => $startDate,
                        'wfh_end_date' => $endDate,
                        'is_wfh_today' => $studentProfile->isWfhOn(),
                        'wfh_status_label' => "WFH Aktif ({$tglMulaiIndo} - {$tglSelesaiIndo})",
                    ]
                ]);
            }

            return back()->with('success', "Izin WFH untuk {$studentProfile->full_name} ({$tglMulaiIndo} s.d. {$tglSelesaiIndo}) berhasil diaktifkan.");
        }

        return back()->with('error', 'Mode pengaturan WFH tidak valid.');
    }

    public function saveFinalMessage(Request $request, $id)
    {
        $request->validate([
            'final_message' => 'required|string|max:5000',
        ], [
            'final_message.required' => 'Pesan apresiasi / catatan akhir magang wajib diisi.',
        ]);

        $student = StudentProfile::findOrFail($id);
        $student->update([
            'final_message' => $request->final_message,
        ]);

        LogHelper::success(
            $request,
            'Simpan Pesan Akhir Magang',
            "Pegawai mengisi pesan apresiasi akhir magang untuk siswa {$student->full_name}."
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan apresiasi berhasil disimpan dan akan tampil di akun anak magang.',
                'final_message' => $student->final_message,
            ]);
        }

        return back()->with('success', 'Pesan apresiasi berhasil disimpan dan akan tampil di halaman riwayat akun siswa.');
    }
}
