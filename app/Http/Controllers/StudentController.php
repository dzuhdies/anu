<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Attendance;
use App\Helpers\LogHelper;
use App\Models\Setting;
use Carbon\Carbon;
use App\Models\LeaveType;
use App\Models\StudentProfile;
use App\Models\Building;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    private const MAX_FOTO_BYTES = 200 * 1024;
    private const MAX_FOTO_DIMENSION = 1280;

    /**
     * Menampilkan foto absensi dari storage untuk hosting yang tidak
     * mendukung symbolic link public/storage.
     */
    public function showFotoAbsensi(string $path)
    {
        if (!preg_match('#^\d{4}-\d{2}/[a-zA-Z0-9._-]+\.(?:jpe?g|png|webp)$#i', $path)) {
            abort(404);
        }

        $storagePath = $this->absensiPhotoPath($path);
        $disk = Storage::disk(self::ABSENSI_PHOTO_DISK);

        abort_unless($disk->exists($storagePath), 404);

        return $disk->response($storagePath, null, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    /**
     * Menampilkan halaman absensi siswa.
     */
    public function absenForm()
    {
        $user = Auth::user();
        $studentProfile = $user->studentProfile;

        // Auto-create student profile if it doesn't exist
        if (!$studentProfile) {
            $defaultBuilding = Building::first();
            $studentProfile = StudentProfile::create([
                'user_id' => $user->id,
                'full_name' => $user->username,
                'institution' => 'Belum Diatur',
                'building_id' => $defaultBuilding ? $defaultBuilding->id : 1,
            ]);
        }

        $startDate = $studentProfile->start_date;
        $endDate = $studentProfile->end_date;

        // Cek periode magang
        if ($startDate && Carbon::today()->lt(Carbon::parse($startDate)->startOfDay())) {
            $tglMulai = Carbon::parse($startDate)->translatedFormat('d F Y');
            $hariMenuju = Carbon::today()->diffInDays(Carbon::parse($startDate)->startOfDay());

            return redirect()->route('pendaftaran.index', ['tab' => 'status'])
                ->with('info', "Pengajuan magang Anda telah disetujui! Namun periode magang Anda baru akan dimulai pada {$tglMulai} ({$hariMenuju} hari lagi). Halaman absensi akan aktif otomatis saat periode magang Anda dimulai.");
        }

        // Cek apakah masa magang sudah selesai
        if ($endDate && Carbon::today()->gt(Carbon::parse($endDate)->endOfDay())) {
            $tglSelesai = Carbon::parse($endDate)->translatedFormat('d F Y');
            $user->update(['role' => 'calon_peserta']);

            return redirect()->route('pendaftaran.index', ['tab' => 'history'])
                ->with('info', "Masa kegiatan magang Anda telah resmi selesai pada tanggal {$tglSelesai}. Terima kasih atas dedikasi dan kontribusi Anda selama magang di BPS Kota Malang! Riwayat magang Anda tersimpan pada menu History.");
        }

        $today = now()->toDateString();
        $jamTelat = Setting::where('key', 'jam_telat')->value('value') ?? '08:16:00';
        $leaveTypes = LeaveType::all();
        $jenisIzin = $leaveTypes;

        $todayAttendance = $studentProfile->attendances()->where('date', '>=', $today)->where('date', '<', now()->addDay()->toDateString())->first();
        $this->setAbsensiPhotoUrls($todayAttendance);

        $siswaProfile = $studentProfile;
        $absensi = $todayAttendance;
        $gedung = $studentProfile->building;
        $isWfhToday = $studentProfile->isWfhOn();

        return view('siswa.absen', [
            'nama' => $studentProfile->full_name,
            'siswaProfile' => $siswaProfile,
            'absensi' => $absensi,
            'gedung' => $gedung,
            'serverTime' => now()->getTimestamp(),
            'jamTelat' => $jamTelat,
            'jenisIzin' => $jenisIzin,
            'isWfhToday' => $isWfhToday,
            'wfhStartDate' => $studentProfile->wfh_start_date ? $studentProfile->wfh_start_date->format('Y-m-d') : null,
            'wfhEndDate' => $studentProfile->wfh_end_date ? $studentProfile->wfh_end_date->format('Y-m-d') : null,
            'wfhNotes' => $studentProfile->wfh_notes,
        ]);
    }

    public function absenMasuk(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'required|string',
        ], [
            'latitude.required' => 'Koordinat GPS wajib aktif untuk melakukan presensi masuk.',
            'longitude.required' => 'Koordinat GPS wajib aktif untuk melakukan presensi masuk.',
            'foto.required' => 'Foto bukti presensi wajib diambil.',
        ]);

        $user = Auth::user();
        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            LogHelper::fail($request, 'Absen Masuk Gagal', 'Profil siswa tidak ditemukan untuk akun ' . $user->username);
            return back()->with('error', 'Profil siswa tidak ditemukan.');
        }

        $today = Carbon::today();
        $startDate = $studentProfile->start_date;
        $endDate = $studentProfile->end_date;

        if ($startDate && $today->lt(Carbon::parse($startDate)->startOfDay())) {
            $msg = 'Periode magang Anda belum dimulai (Mulai: ' . Carbon::parse($startDate)->format('d/m/Y') . ').';
            LogHelper::fail($request, 'Absen Masuk Gagal (Belum Mulai)', $msg);
            return back()->with('error', $msg);
        }
        if ($endDate && $today->gt(Carbon::parse($endDate)->endOfDay())) {
            $msg = 'Masa kegiatan magang Anda telah selesai pada ' . Carbon::parse($endDate)->format('d/m/Y') . '.';
            LogHelper::fail($request, 'Absen Masuk Gagal (Selesai)', $msg);
            return back()->with('error', $msg);
        }

        $building = $studentProfile->building;

        $lat = (float) $request->latitude;
        $long = (float) $request->longitude;

        if (!$lat || !$long) {
            LogHelper::invalid($request, 'Absen Masuk (Salah Isi / GPS Mati)', 'Titik koordinat GPS tidak valid (lat/long kosong). Pastikan izin lokasi aktif.');
            return back()->with('error', 'Titik koordinat GPS tidak valid. Pastikan GPS perangkat Anda aktif.');
        }

        if (!$request->filled('foto')) {
            LogHelper::invalid($request, 'Absen Masuk (Salah Isi)', 'Foto kamera tidak terdeteksi atau kosong.');
            return back()->with('error', 'Foto kamera wajib diambil untuk melakukan presensi.');
        }

        $imageData = $this->compressFoto($request->foto);
        $monthFolder = now()->format('Y-m');
        $filename = 'absen_masuk_' . $user->id . '_' . time() . '.jpg';

        Storage::disk(self::ABSENSI_PHOTO_DISK)->put(
            $this->absensiPhotoPath("{$monthFolder}/{$filename}"),
            $imageData
        );

        $distance = 0;
        $isWFO = true;
        if ($building && $building->latitude && $building->longitude) {
            $distance = $this->getDistance(
                $lat,
                $long,
                $building->latitude,
                $building->longitude
            );
            $maxRadius = $building->radius_meter ?? 50;
            $isWFO = $distance <= $maxRadius;
        }

        $isWfhAllowed = $studentProfile->isWfhOn();
        if (!$isWFO && !$isWfhAllowed) {
            $jarakBulat = round($distance, 1);
            $msg = "Presensi masuk ditolak: Anda berada di luar radius gedung kantor BPS ({$jarakBulat} meter) dan tidak memiliki izin WFH untuk hari ini. Silakan melakukan presensi di lokasi kantor.";
            LogHelper::fail($request, 'Absen Masuk Ditolak (Luar Radius & Tanpa Izin WFH)', $msg);
            return back()->with('error', $msg);
        }

        $jamTelat = Setting::where('key', 'jam_telat')->value('value') ?? '08:16:00';

        $now = Carbon::now();
        $jamMasuk = $now->format('H:i:s');
        $isTelat = $jamMasuk > $jamTelat;
        $statusKetepatan = $isTelat ? 'Terlambat' : 'Tepat Waktu';

        Attendance::updateOrCreate(
            ['student_id' => $studentProfile->id, 'date' => $now->toDateString()],
            [
                'check_in_time' => $jamMasuk,
                'check_in_photo' => $monthFolder . '/' . $filename,
                'check_in_latitude' => $lat,
                'check_in_longitude' => $long,
                'work_type' => $isWFO,
                'attendance_status' => true,
                'punctuality_status' => $statusKetepatan,
                'is_permission' => false,
                'leave_type_id' => null,
                'leave_description' => null,
                'leave_file' => null,
            ]
        );

        $wfoStatus = $isWFO ? 'Hadir (WFO Dalam Gedung)' : 'Hadir (WFH Izin Pegawai)';
        LogHelper::success(
            $request,
            'Absen Masuk Berhasil',
            "Siswa {$studentProfile->full_name} berhasil absen masuk pukul {$jamMasuk} ({$statusKetepatan}, {$wfoStatus}, jarak: " . round($distance, 1) . "m dari gedung)."
        );

        return redirect()->route('siswa.absen.form')->with('success', 'Absen masuk berhasil (' . ($isWFO ? 'WFO - Hadir' : 'WFH - Izin Pegawai') . ')!');
    }

    public function absenKeluar(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'required|string',
        ], [
            'latitude.required' => 'Koordinat GPS wajib aktif untuk melakukan presensi pulang.',
            'longitude.required' => 'Koordinat GPS wajib aktif untuk melakukan presensi pulang.',
            'foto.required' => 'Foto bukti presensi pulang wajib diambil.',
        ]);

        $user = Auth::user();
        $studentProfile = $user->studentProfile;
        if (!$studentProfile) {
            return back()->with('error', 'Profil siswa tidak ditemukan.');
        }

        $lat = (float) $request->latitude;
        $long = (float) $request->longitude;

        if (!$lat || !$long) {
            LogHelper::invalid($request, 'Absen Keluar (Salah Isi / GPS Mati)', 'Titik koordinat GPS tidak valid (lat/long kosong). Pastikan GPS aktif.');
            return back()->with('error', 'Titik koordinat GPS tidak valid. Pastikan GPS perangkat Anda aktif.');
        }

        $today = Carbon::today();
        $startDate = $studentProfile->start_date;
        $endDate = $studentProfile->end_date;

        if ($startDate && $today->lt(Carbon::parse($startDate)->startOfDay())) {
            $msg = 'Periode magang Anda belum dimulai.';
            LogHelper::fail($request, 'Absen Keluar Gagal (Periode Belum Mulai)', $msg);
            return back()->with('error', $msg);
        }
        if ($endDate && $today->gt(Carbon::parse($endDate)->endOfDay())) {
            $msg = 'Masa kegiatan magang Anda telah selesai.';
            LogHelper::fail($request, 'Absen Keluar Gagal (Masa Magang Selesai)', $msg);
            return back()->with('error', $msg);
        }

        $building = $studentProfile->building;
        $distance = 0;
        $isWFO = true;
        if ($building && $building->latitude && $building->longitude) {
            $distance = $this->getDistance(
                $lat,
                $long,
                $building->latitude,
                $building->longitude
            );
            $maxRadius = $building->radius_meter ?? 50;
            $isWFO = $distance <= $maxRadius;
        }

        $isWfhAllowed = $studentProfile->isWfhOn();
        if (!$isWFO && !$isWfhAllowed) {
            $jarakBulat = round($distance, 1);
            $msg = "Presensi pulang ditolak: Anda berada di luar radius gedung kantor BPS ({$jarakBulat} meter) dan tidak memiliki izin WFH untuk hari ini. Silakan melakukan presensi di lokasi kantor.";
            LogHelper::fail($request, 'Absen Keluar Ditolak (Luar Radius & Tanpa Izin WFH)', $msg);
            return back()->with('error', $msg);
        }

        $todayStr = now()->toDateString();

        $attendance = Attendance::where('student_id', $studentProfile->id)
            ->where('date', $todayStr)
            ->first();

        if ($attendance && $attendance->check_in_time && !$attendance->check_out_time) {
            $imageData = $this->compressFoto($request->foto);
            $monthFolder = now()->format('Y-m');
            $filename = 'absen_keluar_' . $user->id . '_' . time() . '.jpg';

            Storage::disk(self::ABSENSI_PHOTO_DISK)->put(
                $this->absensiPhotoPath("{$monthFolder}/{$filename}"),
                $imageData
            );

            $jamPulang = now()->toTimeString();
            $attendance->update([
                'check_out_time' => $jamPulang,
                'check_out_photo' => $monthFolder . '/' . $filename,
            ]);

            LogHelper::success($request, 'Absen Keluar Berhasil', "Siswa {$studentProfile->full_name} berhasil absen keluar pada pukul {$jamPulang}.");

            return redirect()->route('siswa.absen.form')->with('success', 'Absen keluar berhasil!');
        }

        $gagalReason = !$attendance 
            ? "Siswa belum melakukan absen masuk hari ini ({$todayStr})."
            : ($attendance->check_out_time ? "Siswa sudah pernah absen keluar hari ini pada pukul {$attendance->check_out_time}." : "Data presensi tidak memenuhi syarat absen keluar.");

        LogHelper::fail($request, 'Absen Keluar Gagal', "Percobaan absen keluar ditolak: {$gagalReason}");

        return redirect()->route('siswa.absen.form')->with('error', 'Anda belum bisa melakukan absen keluar saat ini.');
    }

    private function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    private function compressFoto(string $fotoBase64): string
    {
        if (!function_exists('imagecreatefromstring') || !function_exists('imagejpeg')) {
            throw ValidationException::withMessages([
                'foto' => 'Server belum mendukung kompresi foto (ekstensi PHP GD belum aktif).',
            ]);
        }

        $encodedImage = preg_replace('#^data:image/[a-zA-Z0-9.+-]+;base64,#i', '', $fotoBase64);
        $imageData = base64_decode($encodedImage, true);
        $sourceImage = $imageData !== false ? @imagecreatefromstring($imageData) : false;

        if ($sourceImage === false) {
            throw ValidationException::withMessages([
                'foto' => 'Data foto tidak valid. Silakan ambil foto kembali.',
            ]);
        }

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
        $scale = min(1, self::MAX_FOTO_DIMENSION / max($sourceWidth, $sourceHeight));
        $width = max(1, (int) round($sourceWidth * $scale));
        $height = max(1, (int) round($sourceHeight * $scale));

        $workingImage = $this->resizeFoto($sourceImage, $width, $height);
        imagedestroy($sourceImage);

        for ($resizeAttempt = 0; $resizeAttempt < 10; $resizeAttempt++) {
            for ($quality = 85; $quality >= 35; $quality -= 5) {
                ob_start();
                imagejpeg($workingImage, null, $quality);
                $compressed = ob_get_clean();

                if ($compressed !== false && strlen($compressed) <= self::MAX_FOTO_BYTES) {
                    imagedestroy($workingImage);
                    return $compressed;
                }
            }

            $newWidth = max(1, (int) round(imagesx($workingImage) * 0.85));
            $newHeight = max(1, (int) round(imagesy($workingImage) * 0.85));
            $smallerImage = $this->resizeFoto($workingImage, $newWidth, $newHeight);
            imagedestroy($workingImage);
            $workingImage = $smallerImage;
        }

        imagedestroy($workingImage);

        throw ValidationException::withMessages([
            'foto' => 'Foto tidak dapat dikompres hingga 200 KB. Silakan ambil foto kembali.',
        ]);
    }

    private function resizeFoto($sourceImage, int $width, int $height)
    {
        $resizedImage = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($resizedImage, 255, 255, 255);
        imagefill($resizedImage, 0, 0, $white);

        imagecopyresampled(
            $resizedImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $width,
            $height,
            imagesx($sourceImage),
            imagesy($sourceImage)
        );

        return $resizedImage;
    }

    public function getRiwayat(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer',
        ]);

        $user = Auth::user();
        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            return response()->json([]);
        }

        $startDate = Carbon::createFromDate($request->tahun, $request->bulan, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromDate($request->tahun, $request->bulan, 1)->endOfMonth()->toDateString();

        $riwayat = Attendance::with('leaveType')
            ->where('student_id', $studentProfile->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        foreach ($riwayat as $item) {
            $this->setAbsensiPhotoUrls($item);
        }

        return response()->json($riwayat);
    }

    public function absenIzin(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'jenis_izin_id' => 'required|exists:leave_types,id',
            'keperluan' => 'nullable|string|max:500',
            'file_izin' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ], [
            'jenis_izin_id.required' => 'Jenis izin wajib dipilih.',
            'jenis_izin_id.exists' => 'Jenis izin yang dipilih tidak valid.',
            'file_izin.mimes' => 'Format file surat izin harus berupa PDF, JPG, atau PNG.',
            'file_izin.max' => 'Ukuran file surat izin maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Pengajuan Izin (Salah Isi)', $validator->errors());
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $student = $user->studentProfile;

        if (!$student) {
            LogHelper::fail($request, 'Pengajuan Izin Gagal', 'Profil siswa tidak ditemukan untuk akun ' . $user->username);
            return back()->with('error', 'Profil siswa tidak ditemukan.');
        }

        $today = Carbon::today();
        $startDate = $student->start_date;
        $endDate = $student->end_date;

        if ($startDate && $today->lt(Carbon::parse($startDate)->startOfDay())) {
            $msg = 'Periode magang Anda belum dimulai.';
            LogHelper::fail($request, 'Pengajuan Izin Gagal (Periode Belum Mulai)', $msg);
            return back()->with('error', $msg);
        }
        if ($endDate && $today->gt(Carbon::parse($endDate)->endOfDay())) {
            $msg = 'Masa kegiatan magang Anda telah selesai.';
            LogHelper::fail($request, 'Pengajuan Izin Gagal (Masa Magang Selesai)', $msg);
            return back()->with('error', $msg);
        }

        $todayAttendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', now()->toDateString())
            ->first();

        if ($todayAttendance?->check_in_time) {
            $msg = 'Izin tidak dapat diajukan karena Anda sudah absen masuk hari ini.';
            LogHelper::fail($request, 'Pengajuan Izin Ditolak', "Siswa {$student->full_name} sudah absen masuk pada jam {$todayAttendance->check_in_time}.");
            return back()->with('error', $msg);
        }

        $path = null;
        if ($request->hasFile('file_izin')) {
            $path = $request->file('file_izin')->store('izin_berkas', 'public');
        }

        Attendance::updateOrCreate(
            ['student_id' => $student->id, 'date' => now()->toDateString()],
            [
                'attendance_status' => false,
                'is_permission' => true,
                'leave_type_id' => $request->jenis_izin_id,
                'leave_description' => $request->keperluan,
                'leave_file' => $path,
            ]
        );

        $leaveType = LeaveType::find($request->jenis_izin_id);
        $leaveName = $leaveType ? $leaveType->name : 'ID: ' . $request->jenis_izin_id;

        LogHelper::success(
            $request,
            'Pengajuan Izin Siswa Berhasil',
            'Siswa "' . $student->full_name . '" mengajukan izin (' . $leaveName . ') dengan keperluan: ' . ($request->keperluan ?: '-')
        );

        return back()->with('success', 'Izin berhasil dikirim');
    }
}
