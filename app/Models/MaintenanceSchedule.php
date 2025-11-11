<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperMaintenanceSchedule
 */
class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id','location_id','scheduled_at','assigned_user_id',
        'status','notes',
        'reminder_sent_at','client_response','client_response_at',
        'client_requested_date','client_response_note',
    ];

    protected $casts = [
        'scheduled_at'          => 'datetime',
        'reminder_sent_at'      => 'datetime',
        'client_response_at'    => 'datetime',
        'client_requested_date' => 'datetime',
    ];

    public function client()     { return $this->belongsTo(Client::class); }
    public function location()   { return $this->belongsTo(Location::class); }
    public function technician() { return $this->belongsTo(User::class, 'assigned_user_id'); }

    public function report() { return $this->hasOne(MaintenanceReport::class, 'schedule_id'); }

    public function units()
    {
        return $this->belongsToMany(
            UnitAc::class, 'maintenance_schedule_units', 'schedule_id', 'unit_ac_id'
        )->withTimestamps();
    }

    /** Label ramah untuk client_response */
    public function getClientResponseLabelAttribute(): string
    {
        return match ($this->client_response) {
            'confirmed'             => 'Diterima',
            'reschedule_requested'  => 'Usul Jadwal Ulang',
            'cancelled_by_client'   => 'Dibatalkan Klien',
            default                 => 'Belum Ada',
        };
    }

    /** Apakah ada permintaan reschedule dari klien yang perlu diproses admin? */
    public function getHasPendingRescheduleAttribute(): bool
    {
        return $this->client_response === 'reschedule_requested'
            && !is_null($this->client_requested_date)
            && in_array($this->status, ['menunggu','menunggu_persetujuan'], true);
    }
}
