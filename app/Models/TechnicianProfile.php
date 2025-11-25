<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TechnicianProfile extends Model
{
    protected $fillable = [
        'user_id', 'team_name', 'phone',
        'member_1_name','member_2_name',
        'status','is_active','address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAutoStatusAttribute()
    {
        if (!$this->is_active) return 'nonaktif';

        $today = now('Asia/Jakarta')->toDateString();
        $user  = $this->user;

        // sedang cuti
        $onLeave = $user->technicianLeaves()
            ->where('status','approved')
            ->whereDate('start_date','<=',$today)
            ->whereDate('end_date','>=',$today)
            ->exists();

        if ($onLeave) return 'cuti';

        // sedang bertugas
        $busy = $user->maintenanceSchedules()
            ->whereIn('status',['menunggu','dalam_proses'])
            ->whereDate('scheduled_at',$today)
            ->exists();

        if ($busy) return 'sedang_bertugas';

        return 'aktif';
    }
}
