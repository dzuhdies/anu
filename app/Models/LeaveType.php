<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    protected $table = 'leave_types';

    protected $fillable = [
        'name',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'leave_type_id');
    }

    // Backward-compatibility alias
    public function absensis(): HasMany
    {
        return $this->attendances();
    }
}
