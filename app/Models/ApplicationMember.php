<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationMember extends Model
{
    protected $table = 'application_members';

    protected $fillable = [
        'application_id',
        'user_id',
        'full_name',
        'institution',
        'major',
        'phone',
        'cv_path',
        'portofolio_path',
        'is_leader',
        // Backward-compatibility
        'pendaftaran_id',
        'nama_lengkap',
        'asal_univ',
        'jurusan',
        'no_hp',
        'is_ketua',
    ];

    protected $casts = [
        'is_leader' => 'boolean',
    ];

    protected $appends = [
        'pendaftaran_id',
        'nama_lengkap',
        'asal_univ',
        'jurusan',
        'no_hp',
        'is_ketua',
        'username',
        'email',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(InternshipApplication::class, 'application_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Backward-compatible relation
    public function pendaftaran(): BelongsTo
    {
        return $this->application();
    }

    // Backward-compatible accessors
    public function getPendaftaranIdAttribute() { return $this->attributes['application_id'] ?? null; }
    public function setPendaftaranIdAttribute($v) { $this->attributes['application_id'] = $v; }

    public function getNamaLengkapAttribute() { return $this->attributes['full_name'] ?? null; }
    public function setNamaLengkapAttribute($v) { $this->attributes['full_name'] = $v; }

    public function getAsalUnivAttribute() { return $this->attributes['institution'] ?? null; }
    public function setAsalUnivAttribute($v) { $this->attributes['institution'] = $v; }

    public function getJurusanAttribute() { return $this->attributes['major'] ?? null; }
    public function setJurusanAttribute($v) { $this->attributes['major'] = $v; }

    public function getNoHpAttribute() { return $this->attributes['phone'] ?? null; }
    public function setNoHpAttribute($v) { $this->attributes['phone'] = $v; }

    public function getIsKetuaAttribute(): bool { return (bool) ($this->attributes['is_leader'] ?? false); }
    public function setIsKetuaAttribute($v) { $this->attributes['is_leader'] = (bool) $v; }

    public function getUsernameAttribute()
    {
        return $this->user?->username ?? $this->application?->leader?->username ?? null;
    }

    public function getEmailAttribute()
    {
        return $this->user?->email ?? $this->application?->leader?->email ?? null;
    }
}

