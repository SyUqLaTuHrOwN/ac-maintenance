<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFeedback
 */
class Feedback extends Model
{
    protected $table = 'feedback';
    protected $fillable = ['report_id','client_user_id','rating','comment'];

    public function report(){ return $this->belongsTo(MaintenanceReport::class); }
    public function user(){ return $this->belongsTo(User::class,'client_user_id'); }
}
