<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperClient
 */
class Client extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'company_name',
        'address',
        'email',
        'phone',
        'pic_name',
        'pic_phone',
        'user_id',      // relasi ke users (akun client yang terkait)
    ];

    /*****************************************************************
     * RELATIONSHIPS
     *****************************************************************/

    /**
     * Akun user yang terkait dengan klien ini (role = client).
     * FK: clients.user_id -> users.id
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Daftar lokasi milik klien.
     * FK: locations.client_id -> clients.id
     */
    public function locations()
    {
        return $this->hasMany(Location::class, 'client_id');
    }

    /**
     * Semua unit AC milik klien (melalui lokasi).
     * hasManyThrough:
     *  - Model akhir      : UnitAc
     *  - Model perantara  : Location
     *  - FK pada perantara: locations.client_id
     *  - FK pada akhir    : unit_acs.location_id
     */
    public function units()
    {
        return $this->hasManyThrough(
            UnitAc::class,      // model akhir
            Location::class,    // model perantara
            'client_id',        // FK di locations mengarah ke clients
            'location_id',      // FK di unit_acs mengarah ke locations
            'id',               // PK di clients
            'id'                // PK di locations
        );
    }

    /**
     * Jadwal maintenance milik klien.
     * FK: maintenance_schedules.client_id -> clients.id
     */
    public function schedules()
    {
        return $this->hasMany(MaintenanceSchedule::class, 'client_id');
    }

    /**
     * (Opsional) Semua laporan maintenance milik klien melalui jadwal.
     * Catatan: sesuaikan nama FK di tabel reports.
     * Di contoh kita, reports punya kolom 'schedule_id'.
     */
    public function reports()
    {
        return $this->hasManyThrough(
            MaintenanceReport::class,     // model akhir
            MaintenanceSchedule::class,   // model perantara
            'client_id',                  // FK di schedules -> clients
            'schedule_id',                // FK di reports -> schedules
            'id',                         // PK di clients
            'id'                          // PK di schedules
        );
    }

    /*****************************************************************
     * SCOPES / ACCESSORS (opsional)
     *****************************************************************/

    /**
     * Scope pencarian sederhana berdasarkan nama/email.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        $t = "%{$term}%";
        return $query->where(function ($q) use ($t) {
            $q->where('company_name', 'like', $t)
              ->orWhere('email', 'like', $t)
              ->orWhere('pic_name', 'like', $t);
        });
    }
}
