<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperUnitAc
 */
class UnitAc extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id','brand','model','serial_number','type',
        'capacity_btu','install_date','last_maintenance_date','status',
    ];

    protected $casts = [
        'install_date' => 'date',
        'last_maintenance_date' => 'date',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // >>> RELASI BARU: jadwal-jadwal yang melibatkan unit ini
    public function schedules()
    {
        return $this->belongsToMany(
            MaintenanceSchedule::class,
            'maintenance_schedule_units',
            'unit_ac_id',
            'schedule_id'
        )->withTimestamps();
    }
public function units()
{
    return $this->belongsToMany(
        \App\Models\UnitAc::class,
        'service_request_units', // nama pivot
        'request_id',            // FK ke service_requests
        'unit_ac_id'             // FK ke unit_acs
    )->withTimestamps();
}
  public function serviceRequests()
{
    return $this->belongsToMany(
        \App\Models\ServiceRequest::class,
        'service_request_units',
        'unit_ac_id',
        'request_id'
    )->withTimestamps();
}


}
