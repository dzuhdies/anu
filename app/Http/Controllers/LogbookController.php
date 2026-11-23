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
            return back()->withErrors($validator)->withInput();
        }

        $date = Carbon::createFromFormat('Y-m-d', $request->date)->startOfDay();
        $dateLabel = $date->locale('id')->translatedFormat('d F Y');

        if ($date->gt(Carbon::today())) {
            $msg = "Logbook tanggal {$dateLabel} belum dapat diisi. Logbook hanya dapat diisi untuk hari ini atau kemarin (masa tenggang H+1).";
            LogHelper::fail($request, 'Simpan Logbook Gagal (Tanggal Belum Tersedia)', $msg);
            return back()->with('error', $msg);
        }

        if (!Logbook::isDateEditable($date)) {
            $batas = $date->copy()->addDay()->locale('id')->translatedFormat('d F Y');
            $msg = "Logbook tanggal {$dateLabel} sudah terkunci. Batas pengisian adalah H+1 (paling lambat {$batas}).";
            LogHelper::fail($request, 'Simpan Logbook Gagal (Terkunci)', $msg);
            return back()->with('error', $msg);
        }

        $startDate = $student->start_date ? Carbon::parse($student->start_date)->startOfDay() : null;
        $endDate = $student->end_date ? Carbon::parse($student->end_date)->endOfDay() : null;

        if (($startDate && $date->lt($startDate)) || ($endDate && $date->gt($endDate))) {
            $msg = "Tanggal {$dateLabel} berada di luar periode magang Anda.";
            LogHelper::fail($request, 'Simpan Logbook Gagal (Di Luar Periode)', $msg);
            return back()->with('error', $msg);
        }

        $existing = $student->logbooks()
            ->whereDate('date', $date->toDateString())
            ->first();

        if ($existing && $existing->isApproved()) {
            $msg = "Logbook tanggal {$dateLabel} sudah disetujui dan tidak dapat diubah lagi.";
            LogHelper::fail($request, 'Simpan Logbook Gagal (Sudah Disetujui)', $msg);
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

        return redirect()
            ->route('siswa.absen.form', [
                'tab' => 'logbook',
                'date' => $logbook->date->toDateString(),
            ])
            ->with('success', $existing
                ? "Logbook tanggal {$dateLabel} berhasil diperbarui dan menunggu review admin."
                : "Logbook tanggal {$dateLabel} berhasil disimpan dan menunggu review admin.");
    }
}
