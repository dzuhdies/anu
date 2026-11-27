<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_permission' => 'boolean',
        'attendance_status' => 'boolean',
        'work_type' => 'boolean',
    ];

    protected $appends = [
        'date_formatted',
        'check_in_time_formatted',
        'check_out_time_formatted',
        'check_in_photo_url',
        'check_out_photo_url',
        'work_duration',
        // Backward-compatible appends
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status_ketepatan',
        'status_kerja',
        'status_kehadiran',
        'izin',
        'keterangan_izin',
        'file_izin',
        'lokasi_lat',
        'lokasi_long',
        'lokasi_masuk_lat',
        'lokasi_masuk_long',
        'foto_masuk_url',
        'foto_keluar_url',
        'durasi_kerja',
    ];

    protected $fillable = [
        'student_id',
        'date',
        'check_in_time',
        'punctuality_status',
        'check_out_time',
        'work_type',
        'attendance_status',
        'check_in_photo',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_photo',
        'is_permission',
        'leave_type_id',
        'leave_description',
        'leave_file',
        // Backward-compatible fillable
        'siswa_id',
        'tanggal',
        'jam_masuk',
        'status_ketepatan',
        'jam_pulang',
        'status_kerja',
        'status_kehadiran',
        'foto_masuk',
        'lokasi_masuk_lat',
        'lokasi_masuk_long',
        'foto_keluar',
        'izin',
        'jenis_izin_id',
        'keterangan_izin',
        'file_izin',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    // Backward-compatible relations
    public function siswa(): BelongsTo
    {
        return $this->student();
    }

    public function jenisIzin(): BelongsTo
    {
        return $this->leaveType();
    }

    // Accessors & Mutators
    protected function resolvePhotoUrl(?string $photo): ?string
    {
        if (!$photo) return null;
        $clean = ltrim($photo, '/');
        if (str_starts_with($clean, 'http://') || str_starts_with($clean, 'https://')) {
            return $photo;
        }
        if (str_starts_with($clean, 'storage/')) {
            return asset($clean);
        }
        if (!str_starts_with($clean, 'absen/')) {
            $clean = 'absen/' . $clean;
        }
        return asset('storage/' . $clean);
    }

    public function getCheckInPhotoUrlAttribute(): ?string
    {
        return $this->resolvePhotoUrl($this->attributes['check_in_photo'] ?? null);
    }

    public function getCheckOutPhotoUrlAttribute(): ?string
    {
        return $this->resolvePhotoUrl($this->attributes['check_out_photo'] ?? null);
    }

    public function getWorkDurationAttribute(): ?string
    {
        $in = $this->attributes['check_in_time'] ?? null;
        $out = $this->attributes['check_out_time'] ?? null;
        if (!$in || !$out) return null;

        try {
            $inTime = Carbon::parse($in);
            $outTime = Carbon::parse($out);
            $diffMinutes = $inTime->diffInMinutes($outTime);
            $hours = floor($diffMinutes / 60);
            $mins = $diffMinutes % 60;
            return "{$hours} jam {$mins} menit";
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getDateFormattedAttribute(): ?string
    {
        $date = $this->attributes['date'] ?? null;
        if (!$date) return null;
        return Carbon::parse($date)->translatedFormat('d F Y');
    }

    public function getCheckInTimeFormattedAttribute(): ?string
    {
        $val = $this->attributes['check_in_time'] ?? null;
        return $val ? Carbon::parse($val)->format('H:i') : null;
    }

    public function getCheckOutTimeFormattedAttribute(): ?string
    {
        $val = $this->attributes['check_out_time'] ?? null;
        return $val ? Carbon::parse($val)->format('H:i') : null;
    }

    // Backward compatibility accessors
    public function getSiswaIdAttribute() { return $this->attributes['student_id'] ?? null; }
    public function setSiswaIdAttribute($val) { $this->attributes['student_id'] = $val; }

    public function getTanggalAttribute() {
        $raw = $this->attributes['date'] ?? null;
        return $raw ? Carbon::parse($raw)->format('Y-m-d') : null;
    }
    public function setTanggalAttribute($val) { $this->attributes['date'] = $val; }

    public function getJamMasukAttribute() { return $this->attributes['check_in_time'] ?? null; }
    public function setJamMasukAttribute($val) { $this->attributes['check_in_time'] = $val; }

    public function getJamPulangAttribute() { return $this->attributes['check_out_time'] ?? null; }
    public function setJamPulangAttribute($val) { $this->attributes['check_out_time'] = $val; }

    public function getStatusKetepatanAttribute() { return $this->attributes['punctuality_status'] ?? null; }
    public function setStatusKetepatanAttribute($val) { $this->attributes['punctuality_status'] = $val; }

    public function getStatusKerjaAttribute() { return $this->attributes['work_type'] ?? null; }
    public function setStatusKerjaAttribute($val) { $this->attributes['work_type'] = $val; }

    public function getStatusKehadiranAttribute() { return $this->attributes['attendance_status'] ?? null; }
    public function setStatusKehadiranAttribute($val) { $this->attributes['attendance_status'] = $val; }

    public function getFotoMasukAttribute() { return $this->attributes['check_in_photo'] ?? null; }
    public function setFotoMasukAttribute($val) { $this->attributes['check_in_photo'] = $val; }

    public function getFotoKeluarAttribute() { return $this->attributes['check_out_photo'] ?? null; }
    public function setFotoKeluarAttribute($val) { $this->attributes['check_out_photo'] = $val; }

    public function getFotoMasukUrlAttribute() { return $this->getCheckInPhotoUrlAttribute(); }
    public function getFotoKeluarUrlAttribute() { return $this->getCheckOutPhotoUrlAttribute(); }

    public function getLokasiLatAttribute() { return $this->attributes['check_in_latitude'] ?? null; }
    public function getLokasiLongAttribute() { return $this->attributes['check_in_longitude'] ?? null; }
    public function getLokasiMasukLatAttribute() { return $this->attributes['check_in_latitude'] ?? null; }
    public function setLokasiMasukLatAttribute($val) { $this->attributes['check_in_latitude'] = $val; }
    public function getLokasiMasukLongAttribute() { return $this->attributes['check_in_longitude'] ?? null; }
    public function setLokasiMasukLongAttribute($val) { $this->attributes['check_in_longitude'] = $val; }

    public function getIzinAttribute() { return (bool) ($this->attributes['is_permission'] ?? false); }
    public function setIzinAttribute($val) { $this->attributes['is_permission'] = (bool) $val; }

    public function getJenisIzinIdAttribute() { return $this->attributes['leave_type_id'] ?? null; }
    public function setJenisIzinIdAttribute($val) { $this->attributes['leave_type_id'] = $val; }

    public function getKeteranganIzinAttribute() { return $this->attributes['leave_description'] ?? null; }
    public function setKeteranganIzinAttribute($val) { $this->attributes['leave_description'] = $val; }

    public function getFileIzinAttribute() { return $this->attributes['leave_file'] ?? null; }
    public function setFileIzinAttribute($val) { $this->attributes['leave_file'] = $val; }

    public function getDurasiKerjaAttribute() { return $this->getWorkDurationAttribute(); }
}
