<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'location_id',
        'scheduled_at',
        'assigned_user_id',
        'status',
        'notes',
        'total_units',
        'daily_capacity',
        'estimated_days',
        'progress_units',
        'client_response',
        'client_requested_date',
        'client_response_notes',
        'completed_at',
'client_approved_at',
'client_approved_by',

    ];

    protected $casts = [
        'scheduled_at'         => 'datetime',
        'client_requested_date'=> 'datetime',
        'completed_at' => 'datetime',
'client_approved_at' => 'datetime',

    ];

    /* ============================================================
     | RELATIONS
     ============================================================*/

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /** Semua laporan teknisi untuk jadwal ini */
    public function reports()
    {
        return $this->hasMany(MaintenanceReport::class, 'schedule_id');
    }

    public function units()
    {
        return $this->belongsToMany(UnitAc::class, 'maintenance_schedule_units', 'schedule_id', 'unit_ac_id')
            ->withPivot(['requested_units'])
            ->withTimestamps();
    }

    /* ============================================================
     | ACCESSOR – PERHITUNGAN
     ============================================================*/

    /** Total unit berdasarkan pivot (requested_units) */
    public function getUnitCountAttribute()
    {
        if ($this->relationLoaded('units')) {
            return $this->units->sum(fn ($u) => $u->pivot->requested_units ?? 1);
        }

        return (int) $this->units()->sum('requested_units');
    }

    /** Jumlah unit yang sudah disetujui (approved laporan) */
    public function getApprovedUnitsAttribute()
{
    return (int) $this->reports()
        ->whereIn('status', ['approved', 'client_approved'])
        ->sum('units_done');
}

    /** Text progress X / Y */
   public function getProgressTextAttribute()
{
    $done = $this->reports()->sum('units_completed'); // kolom baru
    $total = $this->total_units ?? 0;

    return "{$done} / {$total}";
}

    public function getCapacityTextAttribute()
    {
        return $this->daily_capacity
            ? "{$this->daily_capacity} unit/hari"
            : '-';
    }

    public function getEstimasiTextAttribute()
    {
        return $this->estimated_days
            ? "± {$this->estimated_days} hari"
            : null;
    }

    /* ============================================================
     | CLIENT RESPONSE
     ============================================================*/

    public function getClientResponseLabelAttribute()
    {
        return match ($this->client_response) {
            'confirmed'            => 'Disetujui Client',
            'reschedule_requested' => 'Client Minta Jadwal Ulang',
            'cancelled_by_client'  => 'Dibatalkan Client',
            default                => 'Tidak ada respon',
        };
    }
    
    public function getHasPendingRescheduleAttribute()
    {
        return $this->client_response === 'reschedule_requested'
            && $this->client_requested_date !== null;
    }
}
