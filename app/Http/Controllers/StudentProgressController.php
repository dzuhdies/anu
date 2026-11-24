<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\StudentMonthlyProgress;
use App\Models\StudentProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentProgressController extends Controller
{
    /**
     * Mengambil daftar progres bulanan untuk siswa yang sedang login.
     */
    public function getProgressData(Request $request)
    {
        $user = $request->user() ?? Auth::user();
        if (!$user || !$user->studentProfile) {
            return response()->json(['error' => 'Profil siswa tidak ditemukan.'], 404);
        }

        $student = $user->studentProfile;
        $data = $this->buildProgressListForStudent($student);

        return response()->json($data);
    }

    /**
     * Mengambil daftar progres bulanan siswa tertentu (untuk pegawai/admin).
     */
    public function getProgressForStudent($id)
    {
        $student = StudentProfile::with('monthlyProgresses')->findOrFail($id);
        $data = $this->buildProgressListForStudent($student);
        $data['student_name'] = $student->full_name;
        return response()->json($data);
    }

    /**
     * Upload atau upload ulang berkas progres logbook bulanan oleh siswa.
     */
    public function upload(Request $request)
    {
        $user = $request->user() ?? Auth::user();
        if (!$user || !$user->studentProfile) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Profil siswa tidak ditemukan.'], 404);
            }
            return back()->with('error', 'Profil siswa tidak ditemukan.');
        }

        $student = $user->studentProfile;

        $validator = Validator::make($request->all(), [
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string|max:1000',
        ], [
            'file.required' => 'File berkas logbook wajib diunggah.',
            'file.mimes' => 'Format file harus berupa PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
            'file.max' => 'Ukuran file maksimal adalah 10 MB.',
            'year.required' => 'Tahun periode tidak valid.',
            'month.required' => 'Bulan periode tidak valid.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Upload Berkas Progres Gagal', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $year = (int) $request->year;
        $month = (int) $request->month;
        $uploadedFile = $request->file('file');
        $fileName = $uploadedFile->getClientOriginalName();
        $fileSize = $uploadedFile->getSize();

        // Cari progres yang sudah ada jika upload ulang
        $progress = StudentMonthlyProgress::where('student_id', $student->id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        // Jika upload ulang dan ada file lama, hapus dari storage
        if ($progress && $progress->file_path && Storage::disk('public')->exists($progress->file_path)) {
            Storage::disk('public')->delete($progress->file_path);
        }

        // Simpan file baru di storage public/progress/{student_id}
        $filePath = $uploadedFile->store("progress/{$student->id}", 'public');

        $isReupload = (bool) $progress;

        // Saat upload ulang atau baru, status otomatis pending (jika sebelumnya ACC, status ACC hilang)
        $progress = StudentMonthlyProgress::updateOrCreate(
            [
                'student_id' => $student->id,
                'year' => $year,
                'month' => $month,
            ],
            [
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'notes' => $request->filled('notes') ? trim($request->notes) : null,
                'status' => StudentMonthlyProgress::STATUS_PENDING,
                'feedback' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]
        );

        $monthName = Carbon::createFromDate($year, $month, 1)->locale('id')->isoFormat('MMMM Y');
        $msg = $isReupload
            ? "Berkas logbook bulan {$monthName} berhasil diperbarui. Status persetujuan telah direset dan menunggu verifikasi pegawai."
            : "Berkas logbook bulan {$monthName} berhasil diunggah dan menunggu verifikasi pegawai.";

        LogHelper::success($request, 'Upload Berkas Progres Berhasil', "Siswa {$student->full_name} mengunggah berkas progres bulan {$monthName}.");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'progress' => $progress,
            ]);
        }

        return redirect()->route('siswa.absen.form', ['tab' => 'progres'])->with('success', $msg);
    }

    /**
     * Download atau lihat file berkas progres.
     */
    public function download(StudentMonthlyProgress $progress)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        // Cek hak akses: hanya siswa pemilik berkas atau pegawai/admin yang dapat mendownload
        if ($user->role === 'siswa') {
            if (!$user->studentProfile || $user->studentProfile->id !== $progress->student_id) {
                abort(403, 'Anda tidak memiliki hak akses ke berkas ini.');
            }
        }

        if (!Storage::disk('public')->exists($progress->file_path)) {
            abort(404, 'File berkas tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($progress->file_path, $progress->file_name);
    }

    /**
     * ACC / Setujui berkas progres bulanan oleh pegawai/admin.
     */
    public function approve(Request $request, StudentMonthlyProgress $progress)
    {
        $reviewer = $request->user() ?? Auth::user();

        $progress->update([
            'status' => StudentMonthlyProgress::STATUS_APPROVED,
            'feedback' => null,
            'reviewed_by' => $reviewer?->id,
            'reviewed_at' => now(),
        ]);

        $monthName = Carbon::createFromDate($progress->year, $progress->month, 1)->locale('id')->isoFormat('MMMM Y');
        $studentName = $progress->student->full_name ?? 'Siswa';

        LogHelper::success($request, 'Setujui Berkas Progres', "Pegawai menyetujui berkas progres bulan {$monthName} milik {$studentName}.");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Berkas bulan {$monthName} berhasil disetujui (ACC)."]);
        }

        return back()->with('success', "Berkas logbook bulan {$monthName} berhasil disetujui (ACC).");
    }

    /**
     * Minta perbaikan (revisi) berkas progres bulanan oleh pegawai/admin.
     */
    public function reject(Request $request, StudentMonthlyProgress $progress)
    {
        $validator = Validator::make($request->all(), [
            'feedback' => 'required|string|min:3|max:1000',
        ], [
            'feedback.required' => 'Catatan perbaikan wajib diisi.',
            'feedback.min' => 'Catatan perbaikan minimal 3 karakter.',
            'feedback.max' => 'Catatan perbaikan maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Tolak Berkas Progres (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $reviewer = $request->user() ?? Auth::user();

        $progress->update([
            'status' => StudentMonthlyProgress::STATUS_REJECTED,
            'feedback' => trim($request->feedback),
            'reviewed_by' => $reviewer?->id,
            'reviewed_at' => now(),
        ]);

        $monthName = Carbon::createFromDate($progress->year, $progress->month, 1)->locale('id')->isoFormat('MMMM Y');
        $studentName = $progress->student->full_name ?? 'Siswa';

        LogHelper::success($request, 'Minta Perbaikan Berkas Progres', "Pegawai meminta perbaikan berkas progres bulan {$monthName} milik {$studentName}.");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Permintaan perbaikan berkas bulan {$monthName} berhasil dikirim ke siswa."]);
        }

        return back()->with('success', "Permintaan perbaikan berkas bulan {$monthName} berhasil dikirim ke siswa.");
    }

    /**
     * Helper untuk menyusun daftar bulan dan status berkas progres seorang siswa.
     */
    public function buildProgressListForStudent(StudentProfile $student): array
    {
        $months = $student->getInternshipMonths();
        $progresses = $student->monthlyProgresses->keyBy(function ($item) {
            return sprintf('%d-%02d', $item->year, $item->month);
        });

        $list = [];
        $totalUploaded = 0;
        $totalApproved = 0;

        foreach ($months as $m) {
            $key = sprintf('%d-%02d', $m['year'], $m['month']);
            $item = $progresses->get($key);

            $status = $item ? $item->status : 'not_uploaded';
            $statusLabel = match ($status) {
                'approved' => 'Disetujui (ACC)',
                'rejected' => 'Perlu Perbaikan',
                'pending' => 'Menunggu Verifikasi',
                default => 'Belum Diunggah',
            };

            $statusBadge = match ($status) {
                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                default => 'bg-gray-100 text-gray-500 border-gray-200',
            };

            if ($item) {
                $totalUploaded++;
                if ($item->status === StudentMonthlyProgress::STATUS_APPROVED) {
                    $totalApproved++;
                }
            }

            $list[] = [
                'index' => $m['index'],
                'year' => $m['year'],
                'month' => $m['month'],
                'month_name' => $m['name'],
                'has_uploaded' => (bool) $item,
                'progress_id' => $item ? $item->id : null,
                'status' => $status,
                'status_label' => $statusLabel,
                'status_badge' => $statusBadge,
                'file_name' => $item ? $item->file_name : null,
                'file_size_formatted' => $item ? $item->file_size_formatted : null,
                'file_url' => $item ? route('siswa.progres.download', $item->id) : null,
                'notes' => $item ? $item->notes : null,
                'feedback' => $item ? $item->feedback : null,
                'uploaded_at' => $item ? $item->updated_at->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB' : null,
                'reviewed_at' => ($item && $item->reviewed_at) ? $item->reviewed_at->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB' : null,
            ];
        }

        return [
            'total_months' => count($months),
            'total_uploaded' => $totalUploaded,
            'total_approved' => $totalApproved,
            'months' => $list,
        ];
    }
}
