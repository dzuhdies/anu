<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    public function employeeProfile()
    {
        return $this->hasOne(EmployeeProfile::class, 'user_id');
    }

    public function participantProfile()
    {
        return $this->hasOne(ParticipantProfile::class, 'user_id');
    }

    public function ledApplications()
    {
        return $this->hasMany(InternshipApplication::class, 'leader_user_id');
    }

    public function memberApplications()
    {
        return $this->hasMany(ApplicationMember::class, 'user_id');
    }

    public function activeApplication()
    {
        return InternshipApplication::where(function ($q) {
            $q->where('leader_user_id', $this->id)
              ->orWhereHas('members', function ($sub) {
                  $sub->where('user_id', $this->id);
              });
        })->latest()->first();
    }

    // Backward-compatible aliases
    public function pendaftaranKetua()
    {
        return $this->ledApplications();
    }

    public function pendaftaranAnggota()
    {
        return $this->memberApplications();
    }

    public function pendaftaranAktif()
    {
        return $this->activeApplication();
    }

    public function siswaProfile()
    {
        return $this->studentProfile();
    }

    public function pegawaiProfile()
    {
        return $this->employeeProfile();
    }

    public function pesertaProfile()
    {
        return $this->participantProfile();
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }
}
