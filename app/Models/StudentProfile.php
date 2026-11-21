<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentProfile extends Model
{
    protected $table = 'student_profiles';

    protected $fillable = [
        'user_id',
        'full_name',
        'institution',
        'building_id',
        'division_id',
        'start_date',
        'end_date',
        'can_shortcut',
        'shortcut_lat',
        'shortcut_long',
        'shortcut_jam',
        'shortcut_active',
        // Backward-compatibility
        'nama_lengkap',
        'asal_pkl',
        'gedung_id',
        'divisi_id',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'can_shortcut' => 'boolean',
        'shortcut_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    // Backward-compatible relations
    public function gedung(): BelongsTo
    {
        return $this->building();
    }

    public function divisi(): BelongsTo
    {
        return $this->division();
    }

    public function absensis(): HasMany
    {
        return $this->attendances();
    }

    // Backward-compatible accessors
    public function getNamaLengkapAttribute() { return $this->attributes['full_name'] ?? null; }
    public function setNamaLengkapAttribute($v) { $this->attributes['full_name'] = $v; }

    public function getAsalPklAttribute() { return $this->attributes['institution'] ?? null; }
    public function setAsalPklAttribute($v) { $this->attributes['institution'] = $v; }

    public function getGedungIdAttribute() { return $this->attributes['building_id'] ?? null; }
    public function setGedungIdAttribute($v) { $this->attributes['building_id'] = $v; }

    public function getDivisiIdAttribute() { return $this->attributes['division_id'] ?? null; }
    public function setDivisiIdAttribute($v) { $this->attributes['division_id'] = $v; }

    public function getTanggalMulaiAttribute() { return $this->start_date; }
    public function setTanggalMulaiAttribute($v) { $this->attributes['start_date'] = $v; }

    public function getTanggalSelesaiAttribute() { return $this->end_date; }
    public function setTanggalSelesaiAttribute($v) { $this->attributes['end_date'] = $v; }
}
