<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceReport extends Model
{
    // Status baku
    public const ST_DRAFT    = 'draft';
    public const ST_SUBMIT   = 'submitted';
    public const ST_APPROVED = 'approved';
    public const ST_REVISION = 'revision';
    public const ST_REJECTED = 'rejected';

    protected $fillable = [
        'schedule_id',
        'user_id',
        'report_date',
        'units_done',
        'units_completed',
        'photos_start',
        'photos_finish',
        'photos_extra',
        'invoice_path',
        'notes',
        'status',
        'review_note',
        'verified_by_admin_id',
        'verified_at',
    ];

    protected $casts = [
        'photos_start'  => 'array',
        'photos_finish' => 'array',
        'photos_extra'  => 'array',
        'report_date' => 'datetime',
        'verified_at'   => 'datetime',
    ];
    protected $dates = [
    'report_date',
];

    /* ================== RELATIONS ================== */

    public function schedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function feedback()
    {
        return $this->hasOne(\App\Models\Feedback::class,'report_id');
    }

    /* ================== ACCESSORS ================== */

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::ST_DRAFT    => 'Draft',
            self::ST_SUBMIT   => 'Menunggu Verifikasi',
            self::ST_APPROVED => 'Disetujui',
            self::ST_REVISION => 'Revisi',
            self::ST_REJECTED => 'Ditolak',
            default           => ucfirst($this->status ?: 'draft'),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::ST_APPROVED => 'bg-emerald-100 text-emerald-700',
            self::ST_REVISION => 'bg-amber-100 text-amber-700',
            self::ST_SUBMIT   => 'bg-indigo-100 text-indigo-700',
            self::ST_REJECTED => 'bg-red-100 text-red-700',
            default           => 'bg-gray-100 text-gray-700',
        };
    }
}
