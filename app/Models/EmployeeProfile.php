<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeProfile extends Model
{
    protected $table = 'employee_profiles';

    protected $fillable = [
        'user_id',
        'full_name',
        'building_id',
        'division_id',
        // Backward-compatibility
        'nama_lengkap',
        'gedung_id',
        'divisi_id',
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

    // Backward-compatible relations
    public function gedung(): BelongsTo
    {
        return $this->building();
    }

    public function divisi(): BelongsTo
    {
        return $this->division();
    }

    // Backward-compatible accessors
    public function getNamaLengkapAttribute() { return $this->attributes['full_name'] ?? null; }
    public function setNamaLengkapAttribute($v) { $this->attributes['full_name'] = $v; }

    public function getGedungIdAttribute() { return $this->attributes['building_id'] ?? null; }
    public function setGedungIdAttribute($v) { $this->attributes['building_id'] = $v; }

    public function getDivisiIdAttribute() { return $this->attributes['division_id'] ?? null; }
    public function setDivisiIdAttribute($v) { $this->attributes['division_id'] = $v; }
}
