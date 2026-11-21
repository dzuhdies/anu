<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    protected $table = 'log_activities';

    protected $fillable = [
        'user',
        'role',
        'status',
        'action',
        'aksi',
        'description',
        'keterangan',
        'ip_address',
        'user_agent',
    ];

    public function getAksiAttribute()
    {
        return $this->attributes['action'] ?? null;
    }

    public function setAksiAttribute($value)
    {
        $this->attributes['action'] = $value;
    }

    public function getKeteranganAttribute()
    {
        return $this->attributes['description'] ?? null;
    }

    public function setKeteranganAttribute($value)
    {
        $this->attributes['description'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user', 'username');
    }
}
