<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperMaintenanceReport
 */
class MaintenanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id','technician_id',
        'started_at','finished_at',
        'start_photo_path','end_photo_path','receipt_path',
        'units_serviced','notes','photos','invoice_number',
        'status','verified_by_admin_id','verified_at',
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
        'verified_at' => 'datetime',
        'photos'      => 'array', 
    ];

    public function schedule()   { return $this->belongsTo(MaintenanceSchedule::class, 'schedule_id'); }
    public function technician() { return $this->belongsTo(User::class, 'technician_id'); }
    public function verifier()   { return $this->belongsTo(User::class, 'verified_by_admin_id'); }
public function feedback(){ return $this->hasOne(\App\Models\Feedback::class,'report_id'); }

}
