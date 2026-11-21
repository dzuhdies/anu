<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternshipApplication extends Model
{
    protected $table = 'internship_applications';

    protected $fillable = [
        'leader_user_id',
        'division_id',
        'type',
        'group_name',
        'start_date',
        'end_date',
        'suggested_start_date',
        'suggested_end_date',
        'reschedule_reason',
        'rescheduled_by',
        'rescheduled_at',
        'applicant_rejection_reason',
        'status',
        'is_over_quota',
        'admin_note',
        'resignation_reason',
        'resignation_proof',
        'resignation_requested_at',
        'resignation_approved_by',
        'resignation_approved_at',
        'resignation_admin_note',
        'approved_by',
        'approved_at',
        // Backward-compatibility
        'ketua_user_id',
        'divisi_id',
        'tipe',
        'nama_kelompok',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_mulai_saran',
        'tanggal_selesai_saran',
        'catatan_revisi_jadwal',
        'revisi_by',
        'revisi_at',
        'alasan_penolakan_peserta',
        'catatan_admin',
        'alasan_undur_diri',
        'bukti_undur_diri',
        'tgl_undur_diri',
        'undur_diri_approved_by',
        'undur_diri_approved_at',
        'catatan_undur_diri_admin',
    ];

    protected $casts = [
        'start_date' => 'date:d/m/Y',
        'end_date' => 'date:d/m/Y',
        'suggested_start_date' => 'date:d/m/Y',
        'suggested_end_date' => 'date:d/m/Y',
        'is_over_quota' => 'boolean',
        'rescheduled_at' => 'datetime',
        'approved_at' => 'datetime',
        'resignation_requested_at' => 'datetime',
        'resignation_approved_at' => 'datetime',
    ];

    protected $appends = [
        'start_date_dmy',
        'end_date_dmy',
        'duration_days',
        // Backward-compatible appends
        'tanggal_mulai_dmy',
        'tanggal_selesai_dmy',
        'durasi_hari',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_user_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(ApplicationMember::class, 'application_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rescheduledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rescheduled_by');
    }

    public function resignationApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resignation_approved_by');
    }

    // Backward-compatible relations
    public function ketua(): BelongsTo
    {
        return $this->leader();
    }

    public function divisi(): BelongsTo
    {
        return $this->division();
    }

    public function anggotas(): HasMany
    {
        return $this->members();
    }

    public function revisiBy(): BelongsTo
    {
        return $this->rescheduledByUser();
    }

    public function undurDiriApprover(): BelongsTo
    {
        return $this->resignationApprover();
    }

    // Accessors
    public function getDurationDaysAttribute(): int
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date) + 1;
        }
        return 0;
    }

    public function getStartDateDmyAttribute(): ?string
    {
        $val = $this->start_date;
        return $val ? $val->format('d/m/Y') : null;
    }

    public function getEndDateDmyAttribute(): ?string
    {
        $val = $this->end_date;
        return $val ? $val->format('d/m/Y') : null;
    }

    // Backward-compatible accessors
    public function getDurasiHariAttribute(): int { return $this->getDurationDaysAttribute(); }
    public function getTanggalMulaiDmyAttribute(): ?string { return $this->getStartDateDmyAttribute(); }
    public function getTanggalSelesaiDmyAttribute(): ?string { return $this->getEndDateDmyAttribute(); }

    public function getKetuaUserIdAttribute() { return $this->attributes['leader_user_id'] ?? null; }
    public function setKetuaUserIdAttribute($v) { $this->attributes['leader_user_id'] = $v; }

    public function getDivisiIdAttribute() { return $this->attributes['division_id'] ?? null; }
    public function setDivisiIdAttribute($v) { $this->attributes['division_id'] = $v; }

    public function getTipeAttribute() { return $this->attributes['type'] ?? 'individu'; }
    public function setTipeAttribute($v) { $this->attributes['type'] = $v; }

    public function getNamaKelompokAttribute() { return $this->attributes['group_name'] ?? null; }
    public function setNamaKelompokAttribute($v) { $this->attributes['group_name'] = $v; }

    public function getTanggalMulaiAttribute() { return $this->start_date; }
    public function setTanggalMulaiAttribute($v) { $this->attributes['start_date'] = $v; }

    public function getTanggalSelesaiAttribute() { return $this->end_date; }
    public function setTanggalSelesaiAttribute($v) { $this->attributes['end_date'] = $v; }

    public function getTanggalMulaiSaranAttribute() { return $this->suggested_start_date; }
    public function setTanggalMulaiSaranAttribute($v) { $this->attributes['suggested_start_date'] = $v; }

    public function getTanggalSelesaiSaranAttribute() { return $this->suggested_end_date; }
    public function setTanggalSelesaiSaranAttribute($v) { $this->attributes['suggested_end_date'] = $v; }

    public function getCatatanAdminAttribute() { return $this->attributes['admin_note'] ?? null; }
    public function setCatatanAdminAttribute($v) { $this->attributes['admin_note'] = $v; }

    public function getAlasanUndurDiriAttribute() { return $this->attributes['resignation_reason'] ?? null; }
    public function setAlasanUndurDiriAttribute($v) { $this->attributes['resignation_reason'] = $v; }

    public function getBuktiUndurDiriAttribute() { return $this->attributes['resignation_proof'] ?? null; }
    public function setBuktiUndurDiriAttribute($v) { $this->attributes['resignation_proof'] = $v; }

    public function getTglUndurDiriAttribute() { return $this->resignation_requested_at; }
    public function setTglUndurDiriAttribute($v) { $this->attributes['resignation_requested_at'] = $v; }

    public function getUndurDiriApprovedByAttribute() { return $this->attributes['resignation_approved_by'] ?? null; }
    public function setUndurDiriApprovedByAttribute($v) { $this->attributes['resignation_approved_by'] = $v; }

    public function getUndurDiriApprovedAtAttribute() { return $this->resignation_approved_at; }
    public function setUndurDiriApprovedAtAttribute($v) { $this->attributes['resignation_approved_at'] = $v; }

    public function getCatatanUndurDiriAdminAttribute() { return $this->attributes['resignation_admin_note'] ?? null; }
    public function setCatatanUndurDiriAdminAttribute($v) { $this->attributes['resignation_admin_note'] = $v; }

    public function getCatatanRevisiJadwalAttribute() { return $this->attributes['reschedule_reason'] ?? null; }
    public function setCatatanRevisiJadwalAttribute($v) { $this->attributes['reschedule_reason'] = $v; }

    public function getRevisiByAttribute() { return $this->attributes['rescheduled_by'] ?? null; }
    public function setRevisiByAttribute($v) { $this->attributes['rescheduled_by'] = $v; }

    public function getRevisiAtAttribute() { return $this->rescheduled_at; }
    public function setRevisiAtAttribute($v) { $this->attributes['rescheduled_at'] = $v; }

    public function getAlasanPenolakanPesertaAttribute() { return $this->attributes['applicant_rejection_reason'] ?? null; }
    public function setAlasanPenolakanPesertaAttribute($v) { $this->attributes['applicant_rejection_reason'] = $v; }
}
