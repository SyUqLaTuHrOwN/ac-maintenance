<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitAc extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'brand',
        'model',
        'serial_number',
        'type',
        'capacity_btu',
        'units_count',
        'service_period_months',
        'services_per_year',
        'install_date',
        'last_maintenance_date',
        'status'
    ];

    protected $casts = [
        'install_date'          => 'date',
        'last_maintenance_date' => 'date',
        'units_count'           => 'integer',
        'service_period_months' => 'integer',
        'services_per_year'     => 'integer',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function schedules()
{
    return $this->belongsToMany(MaintenanceSchedule::class, 'maintenance_schedule_units', 'unit_ac_id', 'schedule_id')
        ->withPivot(['requested_units'])
        ->withTimestamps();
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
        return $this->belongsToMany(ServiceRequest::class, 'service_request_units', 'unit_ac_id', 'request_id')
            ->withPivot(['requested_units'])
            ->withTimestamps();
    }


}
