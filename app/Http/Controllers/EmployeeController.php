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

        $queryStudent = StudentProfile::query();
        if ($employee && $employee->division_id) {
            $queryStudent->where('division_id', $employee->division_id);
        } elseif ($employee && $employee->building_id) {
            $queryStudent->where('building_id', $employee->building_id);
        }

        $all_siswas = (clone $queryStudent)->with(['division', 'building', 'user.participantProfile'])->orderBy('full_name', 'asc')->get();

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
            $targetDate = $dari;
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

        $siswas = $queryStudent->with(['attendances' => $attendanceQuery, 'division', 'building', 'user.participantProfile'])
            ->orderBy('full_name', 'asc')
            ->get();
        $this->setStudentAbsensiPhotoUrls($siswas);

        $applicationQuery = InternshipApplication::with(['division', 'divisi', 'members.user', 'anggotas.user', 'leader', 'ketua']);
        if ($employee && $employee->division_id) {
            $applicationQuery->where('division_id', $employee->division_id);
        }
        $pendaftarans = $applicationQuery->latest()->get();
        $pendingCount = $pendaftarans->where('status', 'pending')->count();
        $divisis = Division::where('is_active', true)->orderBy('name')->get();

        // Logbook: jumlah yang menunggu review (dibatasi divisi pegawai bila ada)
        $logbookQuery = \App\Models\Logbook::query();
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
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('updated_at');

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

        return view('pegawai.absensi', compact(
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
            'monthlyProgressPendingCount'
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
}
