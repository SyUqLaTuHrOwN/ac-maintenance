<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = [
        'client_id',
        'location_id',
        'preferred_at',
        'notes',
        'status',
        'created_by',
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }

    // RELASI PIVOT request -> units
    public function units() {
        return $this->belongsToMany(UnitAc::class, 'service_request_units', 'request_id', 'unit_ac_id')
                    ->withPivot(['requested_units'])
                    ->withTimestamps();
    }
}
