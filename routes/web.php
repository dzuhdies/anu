<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\InternshipApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\PegawaiLogbookController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\JenisIzinController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\StudentProgressController;
use App\Http\Controllers\CertificateController;

// Halaman Awal / Landing Page Portal Magang BPS Kota Malang
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Fallback untuk shared hosting yang tidak dapat membaca symlink public/storage.
Route::get('/storage/absen/{path}', [StudentController::class, 'showFotoAbsensi'])
    ->where('path', '.*')
    ->name('absensi.photo');

// Kompatibilitas untuk URL lama ketika document root mengarah ke folder proyek.
Route::get('/public/storage/absen/{path}', [StudentController::class, 'showFotoAbsensi'])
    ->where('path', '.*')
    ->name('absensi.photo.legacy');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::get('/register/check-username', [AuthController::class, 'checkUsername'])->name('register.check_username');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::resource('gedungs', BuildingController::class)
        ->only(['store', 'update', 'destroy'])
        ->names('admin.gedungs');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::put('/admin/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/admin/gedungs', [AdminController::class, 'storeGedung'])->name('admin.gedungs.store');
    Route::put('/admin/gedungs/{id}', [AdminController::class, 'updateGedung'])->name('admin.gedungs.update');
    Route::delete('/admin/gedungs/{id}', [AdminController::class, 'destroyGedung'])->name('admin.gedungs.destroy');
    Route::get('/admin/log-aktivitas', [LogController::class, 'index'])->name('admin.log.index');
    Route::get('/logs', [LogController::class, 'index'])->name('admin.logs');
    Route::put('/admin/jam-telat', [AdminController::class, 'updateJamTelat'])->name('admin.jam_telat.update');
    Route::resource('jenis-izin', LeaveTypeController::class)->names('admin.jenis_izin');

    // Manajemen Divisi & Kuota
    Route::post('/divisi', [AdminController::class, 'storeDivisi'])->name('admin.divisi.store');
    Route::put('/divisi/{id}', [AdminController::class, 'updateDivisi'])->name('admin.divisi.update');
    Route::delete('/divisi/{id}', [AdminController::class, 'deleteDivisi'])->name('admin.divisi.delete');
});

// Seleksi & Persetujuan Pendaftaran Magang (Dapat dilakukan oleh Admin dan Pegawai Staff Divisi)
Route::middleware(['auth', 'role:admin,pegawai'])->group(function () {
    Route::get('/divisi/{id}/quota-calendar', [AdminController::class, 'getQuotaCalendar'])->name('admin.divisi.quota_calendar');
    Route::post('/pendaftaran/{id}/approve', [AdminController::class, 'approvePendaftaran'])->name('admin.pendaftaran.approve');
    Route::post('/pendaftaran/{id}/reject', [AdminController::class, 'rejectPendaftaran'])->name('admin.pendaftaran.reject');
    Route::post('/pendaftaran/{id}/reschedule', [AdminController::class, 'reschedulePendaftaran'])->name('admin.pendaftaran.reschedule');
    Route::post('/pendaftaran/{id}/approve-undur-diri', [AdminController::class, 'approveResignation'])->name('admin.pendaftaran.approve_undur_diri');
    Route::post('/pendaftaran/{id}/reject-undur-diri', [AdminController::class, 'rejectResignation'])->name('admin.pendaftaran.reject_undur_diri');
    Route::post('/pendaftaran/{id}/berhentikan', [AdminController::class, 'berhentikanPeserta'])->name('admin.pendaftaran.berhentikan');
    Route::post('/admin/pendaftaran/{id}/berhentikan', [AdminController::class, 'berhentikanPeserta']);
    Route::post('/pegawai/siswa/{id}/berhentikan', [AdminController::class, 'berhentikanSiswa'])->name('pegawai.siswa.berhentikan');
    Route::post('/admin/pendaftaran/{id}/approve', [AdminController::class, 'approvePendaftaran']);
    Route::post('/admin/pendaftaran/{id}/reject', [AdminController::class, 'rejectPendaftaran']);
    Route::post('/admin/pendaftaran/{id}/reschedule', [AdminController::class, 'reschedulePendaftaran']);

    // Ubah Tanggal Selesai / Periode Magang Siswa (Admin & Pegawai)
    Route::post('/siswa/{id}/update-periode', [AdminController::class, 'updatePeriodeSiswa'])->name('siswa.update_periode');
    Route::post('/admin/siswa/{id}/update-periode', [AdminController::class, 'updatePeriodeSiswa'])->name('admin.siswa.update_periode');
    Route::post('/pegawai/siswa/{id}/update-periode', [AdminController::class, 'updatePeriodeSiswa'])->name('pegawai.siswa.update_periode');

    // Pengaturan WFH Siswa (Admin & Pegawai)
    Route::post('/siswa/{id}/set-wfh', [EmployeeController::class, 'setWfh'])->name('siswa.set_wfh');
    Route::post('/pegawai/siswa/{id}/set-wfh', [EmployeeController::class, 'setWfh'])->name('pegawai.siswa.set_wfh');
    Route::post('/pegawai/siswa/{id}/final-message', [EmployeeController::class, 'saveFinalMessage'])->name('pegawai.siswa.final_message');
    Route::post('/logbook/toggle', [AdminController::class, 'toggleLogbook'])->name('logbook.toggle');
    Route::post('/logbook/toggle', [AdminController::class, 'toggleLogbook'])->name('logbook.toggle');
});

Route::middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/pegawai/rekap-absensi', [EmployeeController::class, 'rekapAbsensi'])->name('pegawai.absensi.rekap');
    Route::get('/pegawai/dashboard', [EmployeeController::class, 'rekapAbsensi'])->name('pegawai.absensi');
    Route::get('/pegawai/kalender-kuota', [EmployeeController::class, 'kalenderKuota'])->name('pegawai.kalender.kuota');
    Route::get('/pegawai/rekap/export', [EmployeeController::class, 'exportRekap'])->name('pegawai.absensi.export');
    Route::get('/pegawai/export-rekap', [EmployeeController::class, 'exportRekap'])->name('pegawai.export.rekap');
    Route::get('/pegawai/logbook', [PegawaiLogbookController::class, 'index'])->name('pegawai.logbook.index');
    Route::post('/pegawai/logbook/{logbook}/approve', [PegawaiLogbookController::class, 'approve'])->name('pegawai.logbook.approve');
    Route::post('/pegawai/logbook/{logbook}/reject', [PegawaiLogbookController::class, 'reject'])->name('pegawai.logbook.reject');
    Route::get('/pegawai/siswa/{id}/riwayat-absensi', [EmployeeController::class, 'riwayatSiswa'])->name('pegawai.siswa.riwayat');
    Route::get('/pegawai/siswa/{id}/logbook/export', [LogbookController::class, 'exportMonthlyForStudent'])->name('pegawai.siswa.logbook.export');
    Route::get('/pegawai/siswa/{id}/progres', [StudentProgressController::class, 'getProgressForStudent'])->name('pegawai.siswa.progres');
    Route::post('/pegawai/progres/{progress}/approve', [StudentProgressController::class, 'approve'])->name('pegawai.progres.approve');
    Route::post('/pegawai/progres/{progress}/reject', [StudentProgressController::class, 'reject'])->name('pegawai.progres.reject');
    Route::post('/pegawai/sertifikat/terbitkan', [CertificateController::class, 'issue'])->name('pegawai.sertifikat.terbitkan');
});

Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa/absen', [StudentController::class, 'absenForm'])->name('siswa.absen.form');
    Route::get('/siswa/penyelesaian', [StudentController::class, 'penyelesaian'])->name('siswa.penyelesaian');
    Route::post('/siswa/absen-masuk', [StudentController::class, 'absenMasuk'])->name('siswa.absen.masuk');
    Route::post('/siswa/absen-keluar', [StudentController::class, 'absenKeluar'])->name('siswa.absen.keluar');
    Route::get('/siswa/riwayat', [StudentController::class, 'getRiwayat'])->name('siswa.riwayat');
    Route::post('/siswa/absen-izin', [StudentController::class, 'absenIzin'])->name('siswa.absen.izin');
    Route::get('/siswa/logbook', [LogbookController::class, 'index'])->name('siswa.logbook.index');
    Route::get('/siswa/logbook/bulan', [LogbookController::class, 'getMonthlyData'])->name('siswa.logbook.bulan');
    Route::get('/siswa/logbook/detail', [LogbookController::class, 'getDetail'])->name('siswa.logbook.detail');
    Route::post('/siswa/logbook', [LogbookController::class, 'store'])->name('siswa.logbook.store');
    Route::get('/siswa/logbook/export', [LogbookController::class, 'exportMonthly'])->name('siswa.logbook.export');
    Route::get('/siswa/progres/data', [StudentProgressController::class, 'getProgressData'])->name('siswa.progres.data');
    Route::post('/siswa/progres/upload', [StudentProgressController::class, 'upload'])->name('siswa.progres.upload');
    Route::get('/siswa/progres/{progress}/download', [StudentProgressController::class, 'download'])->name('siswa.progres.download');
});

// Portal Pendaftaran Magang (Calon Peserta / Siswa)
Route::middleware(['auth'])->group(function () {
    Route::get('/pendaftaran', [InternshipApplicationController::class, 'index'])->name('pendaftaran.index');
    Route::get('/pendaftaran/profile', [InternshipApplicationController::class, 'profile'])->name('pendaftaran.profile');
    Route::post('/pendaftaran/profile', [InternshipApplicationController::class, 'updateProfile'])->name('pendaftaran.profile.update');
    Route::post('/pendaftaran', [InternshipApplicationController::class, 'store'])->name('pendaftaran.store');
    Route::post('/pendaftaran/check-quota', [InternshipApplicationController::class, 'checkQuota'])->name('pendaftaran.check_quota');
    Route::post('/pendaftaran/check-user', [InternshipApplicationController::class, 'checkUser'])->name('pendaftaran.check_user');
    Route::post('/pendaftaran/{id}/cancel', [InternshipApplicationController::class, 'cancel'])->name('pendaftaran.cancel');
    Route::post('/pendaftaran/{id}/undur-diri', [InternshipApplicationController::class, 'ajukanUndurDiri'])->name('pendaftaran.undur_diri');
    Route::post('/pendaftaran/{id}/batal-undur-diri', [InternshipApplicationController::class, 'batalUndurDiri'])->name('pendaftaran.batal_undur_diri');
    Route::post('/pendaftaran/{id}/reapply', [InternshipApplicationController::class, 'reapply'])->name('pendaftaran.reapply');
    Route::post('/pendaftaran/{id}/accept-reschedule', [InternshipApplicationController::class, 'acceptReschedule'])->name('pendaftaran.accept_reschedule');
    Route::post('/pendaftaran/{id}/reject-reschedule', [InternshipApplicationController::class, 'rejectReschedule'])->name('pendaftaran.reject_reschedule');
    Route::get('/sertifikat/{id}', [CertificateController::class, 'show'])->name('sertifikat.show');
});

// Fallback langsung untuk melihat / download berkas storage publik (CV, Portofolio, Berkas Izin, Foto Absen)
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404, 'Berkas atau dokumen yang dicari tidak ditemukan di server.');
    }

    $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
    ]);
})->where('path', '.*')->name('storage.direct');

