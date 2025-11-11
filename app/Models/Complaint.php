<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperComplaint
 */
class Complaint extends Model
{
    protected $fillable = [
        'client_id','user_id','schedule_id','subject','message',
        'priority','status','attachments','responded_at','closed_at'
    ];
    protected $casts = [
        'attachments' => 'array',
        'responded_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function client(){ return $this->belongsTo(Client::class); }
    public function user(){ return $this->belongsTo(User::class); }
    public function schedule(){ return $this->belongsTo(MaintenanceSchedule::class); }
}
