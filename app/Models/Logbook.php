<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Batas pengisian: logbook tanggal H masih bisa diisi pada H dan H+1.
     * Mulai H+2 logbook terkunci dan tidak dapat diubah lagi.
     */
    public const EDIT_GRACE_DAYS = 1;

    protected $table = 'logbooks';

    protected $fillable = [
        'student_id',
        'date',
        'activity',
        'result',
        'status',
        'review_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Apakah tanggal logbook ini masih boleh diisi/diubah hari ini.
     */
    public static function isDateEditable($date, ?Carbon $today = null): bool
    {
        $today = ($today ? $today->copy() : Carbon::today())->startOfDay();
        $date = Carbon::parse($date)->startOfDay();

        return $today->betweenIncluded($date, $date->copy()->addDays(self::EDIT_GRACE_DAYS));
    }

    /**
     * Rentang tanggal yang masih dapat dipilih siswa (kemarin & hari ini).
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    public static function editableRange(?Carbon $today = null): array
    {
        $today = ($today ? $today->copy() : Carbon::today())->startOfDay();

        return [$today->copy()->subDays(self::EDIT_GRACE_DAYS), $today];
    }

    public function isDateStillEditable(?Carbon $today = null): bool
    {
        return static::isDateEditable($this->date, $today);
    }

    public function isEditableByStudent(?Carbon $today = null): bool
    {
        return $this->status !== self::STATUS_APPROVED
            && $this->isDateStillEditable($today);
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            default => 'Menunggu Review',
        };
    }

    /**
     * Kelas Tailwind untuk badge status.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            self::STATUS_REJECTED => 'bg-rose-50 text-rose-800 border-rose-200',
            default => 'bg-amber-50 text-amber-800 border-amber-200',
        };
    }

    /**
     * Tanggal terakhir logbook ini masih bisa diisi (H+1).
     */
    public function getEditableUntilAttribute(): Carbon
    {
        return $this->date->copy()->addDays(self::EDIT_GRACE_DAYS);
    }
}
