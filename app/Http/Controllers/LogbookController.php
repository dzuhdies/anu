<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Logbook;
use App\Models\StudentProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LogbookController extends Controller
{
    /**
     * Logbook sekarang menjadi salah satu tab di halaman presensi siswa.
     * Route lama tetap diarahkan ke tab tersebut agar tautan lama tidak rusak.
     */
    public function index(Request $request)
    {
        $date = $request->query('date');

        return redirect()->route('siswa.absen.form', array_filter([
            'tab' => 'logbook',
            'date' => is_string($date) ? $date : null,
        ]));
    }

    /**
     * Menyiapkan seluruh data yang dibutuhkan tab logbook.
     *
     * Aturan pengisian: logbook tanggal H dapat diisi pada H (hari ini)
     * dan H+1 (besok). Mulai H+2 logbook terkunci permanen.
     */
    public function buildPageData(StudentProfile $student, $requestedDate = null): array
    {
        $today = Carbon::today();
        $startDate = $student->start_date ? Carbon::parse($student->start_date)->startOfDay() : null;
        $endDate = $student->end_date ? Carbon::parse($student->end_date)->endOfDay() : null;

        // Tanggal yang masih boleh diisi: kemarin (H+1) dan hari ini (H).
        [$yesterday, $todayDate] = Logbook::editableRange($today);

        $availableDates = collect([$yesterday, $todayDate])
            ->filter(function (Carbon $date) use ($startDate, $endDate) {
                return (!$startDate || $date->gte($startDate))
                    && (!$endDate || $date->lte($endDate));
            })
            ->values();

        if ($availableDates->isEmpty()) {
            $availableDates = collect([$todayDate]);
        }

        $requested = null;
        if (is_string($requestedDate) && $requestedDate !== '') {
            try {
                $requested = Carbon::parse($requestedDate)->startOfDay();
            } catch (\Throwable $e) {
                $requested = null;
            }
        }

        $selectedDate = ($requested && $availableDates->contains(fn (Carbon $date) => $date->isSameDay($requested)))
            ? $requested
            : $availableDates->last();

        $entries = $student->logbooks()
            ->whereIn('date', $availableDates->map(fn (Carbon $date) => $date->toDateString())->all())
            ->get()
            ->keyBy(fn (Logbook $logbook) => $logbook->date->toDateString());

        $panels = $availableDates->mapWithKeys(function (Carbon $date) use ($entries, $today) {
            $key = $date->toDateString();
            $logbook = $entries->get($key);

            return [$key => [
                'date' => $date,
                'logbook' => $logbook,
                'canEdit' => Logbook::isDateEditable($date, $today)
                    && (!$logbook || $logbook->isEditableByStudent($today)),
            ]];
        });

        $selectedPanel = $panels->get($selectedDate->toDateString());

        $logbooks = $student->logbooks()
            ->orderByDesc('date')
            ->limit(60)
            ->get();

        $stats = [
            'total' => $student->logbooks()->count(),
            'pending' => $student->logbooks()->where('status', Logbook::STATUS_PENDING)->count(),
            'approved' => $student->logbooks()->where('status', Logbook::STATUS_APPROVED)->count(),
            'rejected' => $student->logbooks()->where('status', Logbook::STATUS_REJECTED)->count(),
        ];

        return [
            'today' => $today,
            'availableDates' => $availableDates,
            'selectedDate' => $selectedDate,
            'selectedLogbook' => $selectedPanel['logbook'] ?? null,
            'canEdit' => $selectedPanel['canEdit'] ?? false,
            'logbookPanels' => $panels,
            'logbooks' => $logbooks,
            'stats' => $stats,
        ];
    }

    /**
     * Mengambil daftar status logbook bulanan untuk ditampilkan pada kalender logbook siswa.
     */
    public function getMonthlyData(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer',
        ]);

        $user = $request->user() ?? Auth::user();
        if (!$user || !$user->studentProfile) {
            return response()->json([]);
        }
        $student = $user->studentProfile;

        $startDate = Carbon::createFromDate($request->tahun, $request->bulan, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromDate($request->tahun, $request->bulan, 1)->endOfMonth()->toDateString();

        $logbooks = Logbook::where('student_id', $student->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get(['id', 'date', 'status']);

        $data = $logbooks->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => Carbon::parse($item->date)->toDateString(),
                'status' => $item->status,
                'status_label' => $item->status_label,
            ];
        });

        return response()->json($data);
    }

    /**
     * Mengambil rincian isi logbook untuk tanggal tertentu saat siswa mengklik tanggal.
     * Backend hanya dieksekusi untuk mengambil isi detail ketika tanggal diklik.
     */
    public function getDetail(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $user = $request->user() ?? Auth::user();
        if (!$user || !$user->studentProfile) {
            return response()->json(['error' => 'Profil peserta magang tidak ditemukan.'], 404);
        }
        $student = $user->studentProfile;

        $targetDate = Carbon::createFromFormat('Y-m-d', $request->date)->startOfDay();
        $today = Carbon::today()->startOfDay();
        $targetDateStr = $targetDate->toDateString();

        $startDate = $student->start_date ? Carbon::parse($student->start_date)->startOfDay() : null;
        $endDate = $student->end_date ? Carbon::parse($student->end_date)->endOfDay() : null;

        $isWithinPeriod = (!$startDate || $targetDate->gte($startDate)) && (!$endDate || $targetDate->lte($endDate));
        $isFuture = $targetDate->gt($today);
        $isDateEditable = Logbook::isDateEditable($targetDate, $today);

        $logbook = Logbook::with('reviewer')
            ->where('student_id', $student->id)
            ->whereDate('date', $targetDateStr)
            ->first();

        $exists = (bool) $logbook;
        $isApproved = $logbook ? $logbook->isApproved() : false;
        $canEdit = $isWithinPeriod && !$isFuture && $isDateEditable && !$isApproved;

        $lockReason = null;
        if (!$isWithinPeriod) {
            $lockReason = 'Tanggal ini berada di luar periode masa magang Anda.';
        } elseif ($isFuture) {
            $lockReason = 'Logbook untuk tanggal mendatang belum dapat diisi.';
        } elseif ($isApproved) {
            $lockReason = 'Logbook sudah disetujui dan tidak dapat diubah lagi.';
        } elseif (!$isDateEditable) {
            $lockReason = 'Batas pengisian logbook (H+1) telah terlewat. Logbook tanggal ini sudah terkunci permanen.';
        }

        $dateFormatted = $targetDate->locale('id')->translatedFormat('l, d F Y');

        return response()->json([
            'date' => $targetDateStr,
            'date_formatted' => $dateFormatted,
            'exists' => $exists,
            'can_edit' => $canEdit,
            'is_today' => $targetDate->isToday(),
            'is_future' => $isFuture,
            'is_within_period' => $isWithinPeriod,
            'lock_reason' => $lockReason,
            'logbook' => $logbook ? [
                'id' => $logbook->id,
                'activity' => $logbook->activity,
                'result' => $logbook->result,
                'status' => $logbook->status,
                'status_label' => $logbook->status_label,
                'status_badge' => $logbook->status_badge,
                'review_note' => $logbook->review_note,
                'reviewed_by' => $logbook->reviewer?->username ?? '',
                'reviewed_at' => $logbook->reviewed_at ? $logbook->reviewed_at->locale('id')->translatedFormat('d M Y, H:i') . ' WIB' : null,
            ] : null,
        ]);
    }

    /**
     * Simpan / perbarui logbook siswa.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $student = $user->studentProfile;

        $logbookReviewEnabled = \App\Models\Setting::where('key', 'logbook_review_enabled')->value('value');
        $logbookReviewEnabled = $logbookReviewEnabled === null || $logbookReviewEnabled === '1' || $logbookReviewEnabled === 'true';

        if (!$student) {
            LogHelper::fail($request, 'Simpan Logbook Gagal', 'Profil siswa tidak ditemukan untuk akun ' . $user->username);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Profil peserta magang tidak ditemukan.'], 404);
            }
            return back()->with('error', 'Profil peserta magang tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'activity' => 'required|string|min:3|max:2000',
            'result' => 'nullable|string|max:2000',
        ], [
            'date.required' => 'Tanggal logbook wajib dipilih.',
            'date.date_format' => 'Format tanggal logbook tidak valid.',
            'activity.required' => 'Uraian kegiatan wajib diisi.',
            'activity.min' => 'Uraian kegiatan terlalu pendek, minimal 3 karakter.',
            'activity.max' => 'Uraian kegiatan maksimal 2000 karakter.',
            'result.max' => 'Hasil / output maksimal 2000 karakter.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Simpan Logbook (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $date = Carbon::createFromFormat('Y-m-d', $request->date)->startOfDay();
        $dateLabel = $date->locale('id')->translatedFormat('d F Y');

        if ($date->gt(Carbon::today())) {
            $msg = "Logbook tanggal {$dateLabel} belum dapat diisi. Logbook hanya dapat diisi untuk hari ini atau kemarin (masa tenggang H+1).";
            LogHelper::fail($request, 'Simpan Logbook Gagal (Tanggal Belum Tersedia)', $msg);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        if (!Logbook::isDateEditable($date)) {
            $batas = $date->copy()->addDay()->locale('id')->translatedFormat('d F Y');
            $msg = "Logbook tanggal {$dateLabel} sudah terkunci. Batas pengisian adalah H+1 (paling lambat {$batas}).";
            LogHelper::fail($request, 'Simpan Logbook Gagal (Terkunci)', $msg);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $startDate = $student->start_date ? Carbon::parse($student->start_date)->startOfDay() : null;
        $endDate = $student->end_date ? Carbon::parse($student->end_date)->endOfDay() : null;

        if (($startDate && $date->lt($startDate)) || ($endDate && $date->gt($endDate))) {
            $msg = "Tanggal {$dateLabel} berada di luar periode magang Anda.";
            LogHelper::fail($request, 'Simpan Logbook Gagal (Di Luar Periode)', $msg);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $existing = $student->logbooks()
            ->whereDate('date', $date->toDateString())
            ->first();

        if ($existing && $existing->isApproved()) {
            $msg = "Logbook tanggal {$dateLabel} sudah disetujui dan tidak dapat diubah lagi.";
            LogHelper::fail($request, 'Simpan Logbook Gagal (Sudah Disetujui)', $msg);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $logbook = $student->logbooks()->updateOrCreate(
            ['date' => $date->toDateString()],
            [
                'activity' => trim($request->activity),
                'result' => $request->filled('result') ? trim($request->result) : null,
                'status' => $logbookReviewEnabled ? Logbook::STATUS_PENDING : Logbook::STATUS_APPROVED,
                'review_note' => null,
                'reviewed_by' => null,
                'reviewed_at' => $logbookReviewEnabled ? null : now(),
            ]
        );

        LogHelper::success(
            $request,
            $existing ? 'Perbarui Logbook Berhasil' : 'Simpan Logbook Berhasil',
            "Siswa {$student->full_name} " . ($existing ? 'memperbarui' : 'mengisi') . " logbook tanggal {$dateLabel}."
        );

        $successMsg = $existing
            ? "Logbook tanggal {$dateLabel} berhasil diperbarui dan menunggu review."
            : "Logbook tanggal {$dateLabel} berhasil disimpan dan menunggu review.";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'date' => $logbook->date->toDateString(),
                'logbook' => [
                    'id' => $logbook->id,
                    'activity' => $logbook->activity,
                    'result' => $logbook->result,
                    'status' => $logbook->status,
                    'status_label' => $logbook->status_label,
                    'status_badge' => $logbook->status_badge,
                ],
            ]);
        }

        return redirect()
            ->route('siswa.absen.form', [
                'tab' => 'logbook',
                'date' => $logbook->date->toDateString(),
            ])
            ->with('success', $successMsg);
    }

    /**
     * Ekspor seluruh logbook bulan terpilih ke dalam format cetak/PDF
     * menggunakan template_logbook.html
     */
    public function exportMonthly(Request $request)
    {
        $user = $request->user() ?? Auth::user();
        if (!$user || !$user->studentProfile) {
            abort(404, 'Profil peserta magang tidak ditemukan.');
        }

        return $this->renderMonthlyExportView($user->studentProfile, $request);
    }

    /**
     * Ekspor logbook bulanan siswa tertentu (untuk pegawai/admin)
     */
    public function exportMonthlyForStudent(Request $request, $id)
    {
        $student = StudentProfile::findOrFail($id);
        return $this->renderMonthlyExportView($student, $request);
    }

    /**
     * Render view ekspor bulanan dengan template_logbook
     */
    protected function renderMonthlyExportView(StudentProfile $student, Request $request)
    {
        $bulan = (int) ($request->query('bulan') ?? now()->month);
        $tahun = (int) ($request->query('tahun') ?? now()->year);

        if ($bulan < 1 || $bulan > 12) {
            $bulan = now()->month;
        }

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $logbooks = Logbook::where('student_id', $student->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        $division = $student->division;
        $divisionName = $division ? $division->name : ($student->institution ?: 'BPS Kota Malang');

        $leader = null;
        if ($division && $division->employeeProfiles()->exists()) {
            $leader = $division->employeeProfiles()->first();
        }

        $leaderName = $leader ? $leader->full_name : null;
        $leaderNip = null;

        $bulanNama = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM');
        $periodeMagang = '-';
        if ($student->start_date && $student->end_date) {
            $periodeMagang = Carbon::parse($student->start_date)->locale('id')->isoFormat('D MMM Y') . ' s.d. ' . Carbon::parse($student->end_date)->locale('id')->isoFormat('D MMM Y');
        }

        return view('siswa.logbook-export', [
            'student' => $student,
            'studentName' => $student->full_name ?? ($student->user->name ?? 'Peserta Magang'),
            'institution' => $student->institution,
            'divisionName' => $divisionName,
            'leaderName' => $leaderName,
            'leaderNip' => $leaderNip,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanNama' => $bulanNama,
            'periodeMagang' => $periodeMagang,
            'logbooks' => $logbooks,
        ]);
    }
}
