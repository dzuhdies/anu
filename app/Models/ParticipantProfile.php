<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipantProfile extends Model
{
    use HasFactory;

    protected $table = 'participant_profiles';

    protected $fillable = [
        'user_id',
        'full_name',
        'institution',
        'major',
        'phone',
        'cv_path',
        'portofolio_path',
        'portofolio_url',
        // Backward-compatibility
        'nama_lengkap',
        'asal_univ',
        'jurusan',
        'no_hp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Backward-compatible accessors
    public function getNamaLengkapAttribute() { return $this->attributes['full_name'] ?? null; }
    public function setNamaLengkapAttribute($v) { $this->attributes['full_name'] = $v; }

    public function getAsalUnivAttribute() { return $this->attributes['institution'] ?? null; }
    public function setAsalUnivAttribute($v) { $this->attributes['institution'] = $v; }

    public function getJurusanAttribute() { return $this->attributes['major'] ?? null; }
    public function setJurusanAttribute($v) { $this->attributes['major'] = $v; }

    public function getNoHpAttribute() { return $this->attributes['phone'] ?? null; }
    public function setNoHpAttribute($v) { $this->attributes['phone'] = $v; }
}
