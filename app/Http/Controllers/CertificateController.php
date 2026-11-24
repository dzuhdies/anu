<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\StudentProfile;
use App\Models\InternshipApplication;
use App\Models\User;
use App\Http\Controllers\StudentProgressController;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CertificateController extends Controller
{
    /**
     * Terbitkan sertifikat magang untuk peserta dan selesaikan magang.
     */
    public function issue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:student_profiles,id',
            'certificate_number' => 'required|string|max:100',
            'predicate' => 'required|string|max:50',
            'issue_date' => 'required|date',
            'signer_name' => 'required|string|max:150',
            'signer_position' => 'required|string|max:150',
            'notes' => 'nullable|string|max:1000',
        ], [
            'student_id.required' => 'Data peserta magang tidak valid.',
            'certificate_number.required' => 'Nomor sertifikat wajib diisi.',
            'predicate.required' => 'Predikat kelulusan wajib dipilih.',
            'issue_date.required' => 'Tanggal terbit sertifikat wajib diisi.',
            'signer_name.required' => 'Nama penandatangan sertifikat wajib diisi.',
            'signer_position.required' => 'Jabatan penandatangan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $student = StudentProfile::with(['user', 'division', 'building'])->findOrFail($request->student_id);
        $user = $student->user;

        // Validasi: Berkas progres bulanan wajib lengkap dan disetujui seluruhnya sebelum menerbitkan sertifikat
        $progressData = app(StudentProgressController::class)->buildProgressListForStudent($student);
        $totalMonths = $progressData['total_months'] ?? 0;
        $totalApproved = $progressData['total_approved'] ?? 0;
        $isComplete = ($totalMonths > 0 && $totalApproved >= $totalMonths);

        if (!$isComplete) {
            return back()->with('error', "Sertifikat tidak dapat diterbitkan karena berkas logbook bulanan peserta belum lengkap ({$totalApproved} dari {$totalMonths} bulan disetujui). Seluruh berkas harus disetujui terlebih dahulu.");
        }

        // Cari permohonan magang terkait
        $application = null;
        if ($user) {
            $application = $user->activeApplication()
                ?: $user->ledApplications()->latest()->first()
                ?: $user->memberApplications()->latest()->first();
        }

        if ($application && in_array($application->status, ['resigned', 'terminated'])) {
            return back()->with('error', 'Peserta yang mengundurkan diri atau diberhentikan tidak dapat diberikan sertifikat magang.');
        }

        $studentName = $request->input('student_name', $student->full_name);
        $institution = $request->input('institution', $student->institution ?: ($user?->participantProfile?->institution ?: '-'));
        $divisionName = $student->division?->nama_divisi ?? ($application?->division?->nama_divisi ?? 'BPS Kota Malang');

        $certificate = Certificate::updateOrCreate(
            [
                'user_id' => $user?->id ?? $student->user_id,
                'student_id' => $student->id,
            ],
            [
                'application_id' => $application?->id,
                'division_id' => $student->division_id,
                'certificate_number' => trim($request->certificate_number),
                'student_name' => $studentName,
                'institution' => $institution,
                'division_name' => $divisionName,
                'start_date' => $student->start_date ?: $application?->start_date,
                'end_date' => $student->end_date ?: $application?->end_date,
                'issue_date' => $request->issue_date,
                'predicate' => $request->predicate,
                'issued_by' => Auth::user()?->id,
                'signer_name' => trim($request->signer_name),
                'signer_position' => trim($request->signer_position),
                'status' => 'issued',
                'notes' => $request->notes,
            ]
        );

        // Tandai permohonan magang sebagai completed jika ada
        if ($application) {
            $application->update([
                'status' => 'completed',
            ]);
        }

        // Kembalikan role user ke calon_peserta (alumni) agar diarahkan kembali ke akun pendaftaran / history
        if ($user && $user->role === 'siswa') {
            $user->update(['role' => 'calon_peserta']);
        }

        LogHelper::success(
            $request,
            'Terbitkan Sertifikat Magang',
            "Pengguna " . (Auth::user()->username ?? 'Pegawai') . " menerbitkan sertifikat No. {$certificate->certificate_number} untuk {$studentName}."
        );

        return back()->with('success', "Penyelesaian magang berhasil disetujui! Sertifikat No. {$certificate->certificate_number} telah resmi diterbitkan.");
    }

    /**
     * Tampilkan template sertifikat untuk dicetak / diunduh.
     */
    public function show($id)
    {
        $certificate = Certificate::with(['user', 'student.division', 'application.division', 'issuer'])->findOrFail($id);

        // Otorisasi: pemilik sertifikat, admin, atau pegawai
        $user = Auth::user();
        if ($user && $user->role !== 'admin' && $user->role !== 'pegawai' && $user->id !== $certificate->user_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat sertifikat ini.');
        }

        return view('sertifikat.template', compact('certificate'));
    }
}
