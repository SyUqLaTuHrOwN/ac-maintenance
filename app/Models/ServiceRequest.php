<?php

// app/Models/ServiceRequest.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = [
        'client_id','location_id','preferred_at','notes','status',
    ];

    protected $casts = [
        'preferred_at' => 'datetime',
    ];

    public function client()   { return $this->belongsTo(Client::class); }
    public function location() { return $this->belongsTo(Location::class); }

    // pivot service_request_units: request_id, unit_ac_id
   public function units()
{
    return $this->belongsToMany(
        \App\Models\UnitAc::class,
        'service_request_units',   // <- nama pivot
        'request_id',              // FK ke service_requests
        'unit_ac_id'               // FK ke unit_acs
    )->withTimestamps();
}

}
