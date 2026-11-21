<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    protected $table = 'buildings';

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius_meter',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'radius_meter' => 'integer',
    ];

    public function studentProfiles(): HasMany
    {
        return $this->hasMany(StudentProfile::class, 'building_id');
    }

    public function employeeProfiles(): HasMany
    {
        return $this->hasMany(EmployeeProfile::class, 'building_id');
    }

    // Backward-compatibility aliases
    public function getNamaAttribute()
    {
        return $this->attributes['name'] ?? null;
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    public function siswaProfiles(): HasMany
    {
        return $this->studentProfiles();
    }

    public function pegawaiProfiles(): HasMany
    {
        return $this->employeeProfiles();
    }
}
