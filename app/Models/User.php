<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Role;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','phone','role'
    ];

    protected $hidden = [
        'password','remember_token'
    ];

    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    public function isTeknisi(): bool
    {
        return $this->role === Role::TEKNISI;
    }

    public function isClient(): bool
    {
        return $this->role === Role::CLIENT;
    }

    /* ===========================
       RELATION
    ============================ */

    public function clientProfile()
    {
        return $this->hasOne(Client::class, 'user_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'user_id');
    }

public function technicianProfile()
{
    return $this->hasOne(\App\Models\TechnicianProfile::class);
}

public function technicianLeaves()
{
    return $this->hasMany(\App\Models\TechnicianLeave::class, 'user_id');
}


    public function leaves()
    {
        return $this->hasMany(TechnicianLeave::class, 'user_id');
    }

    public function approvedLeaves()
    {
        return $this->leaves()->approved();
    }
    public function profile()
{
    return $this->hasOne(\App\Models\TechnicianProfile::class);
}


    /* ===========================
       LOGIC: CEK CUTI HARI INI / TGL TERTENTU
    ============================ */

    public function isOnLeave($date = null): bool
    {
        $d = $date
            ? Carbon::parse($date)->toDateString()
            : now('Asia/Jakarta')->toDateString();

        return $this->approvedLeaves()
            ->whereDate('start_date', '<=', $d)
            ->whereDate('end_date', '>=', $d)
            ->exists();
    }

public function maintenanceSchedules()
{
    return $this->hasMany(MaintenanceSchedule::class, 'assigned_user_id');
}
    /* ===========================
       ACCESSOR: STATUS LIVE
       Digunakan di tabel teknisi
    ============================ */
public function getStatusTeknisiAttribute()
{
    return $this->profile->status ?? 'aktif';
}

public function isCuti()
{
    return $this->status_teknisi === 'cuti';
}

public function isBertugas()
{
    return $this->status_teknisi === 'sedang_bertugas';
}

public function isAktif()
{
    return $this->status_teknisi === 'aktif';
}

    public function getLeaveStatusAttribute(): string
    {
        if ($this->isOnLeave()) {
            // jika ingin mendeteksi jenis cuti (izin/sakit/cuti), bisa tambahkan di sini
            return 'cuti';
        }

        $active = optional($this->technicianProfile)->is_active;

        return $active === 0 ? 'nonaktif' : 'aktif';
    }
}