<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperLocation
 */
class Location extends Model
{
    use HasFactory;

    protected $fillable = ['client_id','name','address'];

    public function client() { return $this->belongsTo(Client::class); }
    public function unitAcs() { return $this->hasMany(UnitAc::class); }
}
