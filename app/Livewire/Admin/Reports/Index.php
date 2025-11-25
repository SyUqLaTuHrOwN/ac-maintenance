<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceReport;
use App\Models\Client;
use App\Models\User;
use App\Support\Role;

class Index extends Component
{
    use WithPagination;

    public ?int $clientFilter      = null;
    public ?int $technicianFilter  = null;
    public ?string $statusFilter   = 'submitted';
    public ?string $dateFrom       = null;
    public ?string $dateTo         = null;
    public ?string $search         = '';

    // modal revisi
    public ?int $reviewId          = null;
    public ?string $review_note    = null;

    // modal detail
    public $detail                 = null;   // MaintenanceReport|null
    public bool $detailMode        = false;

    protected $paginationTheme     = 'tailwind';

    public function updating($field)
    {
        if (in_array($field, [
            'clientFilter', 'technicianFilter', 'statusFilter',
            'dateFrom', 'dateTo', 'search',
        ], true)) {
            $this->resetPage();
        }
    }

    public function openReview(int $id, bool $asDetail = false): void
    {
        if ($asDetail) {
            // buka modal detail
            $this->detail = MaintenanceReport::with([
                'schedule.location.client',
                'schedule.units',
                'user',
            ])->findOrFail($id);

            $this->detailMode   = true;
            $this->reviewId     = null;
            $this->review_note  = null;

            return;
        }

        // buka modal minta revisi
        $this->detailMode   = false;
        $this->reviewId     = $id;
        $this->review_note  = null;
    }

    public function closeDetail(): void
    {
        $this->detail       = null;
        $this->detailMode   = false;
    }

    public function closeReview(): void
    {
        $this->reviewId     = null;
        $this->review_note  = null;
    }

    public function approve(int $id): void
    {
        // load schedule + semua reports utk hitung progress schedule
        $report = MaintenanceReport::with('schedule.reports', 'schedule.units')
            ->findOrFail($id);

        $report->update([
            'status'               => MaintenanceReport::ST_APPROVED,
            'verified_by_admin_id' => auth()->id(),
            'verified_at'          => now('Asia/Jakarta'),
        ]);

        $sched = $report->schedule;

        if ($sched) {
            // total rencana, fallback ke jumlah unit di relasi
            $totalUnits = $sched->total_units ?: $sched->unit_count;

            // hitung total unit yg approved
            $approvedUnits = $sched->reports
                ->where('status', MaintenanceReport::ST_APPROVED)
                ->sum('units_done');

            // simpan ke progress_units utk dipakai di sisi client
            $sched->progress_units = $approvedUnits;

            // jika penuh -> selesai_servis (masih menunggu konfirmasi client)
            if ($totalUnits && $approvedUnits >= $totalUnits) {
                $sched->status = 'selesai_servis';
            }

            $sched->save();
        }

        session()->flash('ok', 'Laporan disetujui.');
    }

    public function sendRevision(): void
    {
        $this->validate([
            'reviewId'    => ['required', 'exists:maintenance_reports,id'],
            'review_note' => ['required', 'string', 'min:5'],
        ]);

        $report = MaintenanceReport::findOrFail($this->reviewId);

        $report->update([
            'status'      => MaintenanceReport::ST_REVISION,
            'review_note' => $this->review_note,
            'verified_at' => null,
        ]);

        $this->closeReview();
        session()->flash('ok', 'Laporan diminta revisi.');
    }

    public function render()
    {
        $clients = Client::orderBy('company_name')->get(['id', 'company_name']);
        $technicians = User::where('role', Role::TEKNISI)
            ->orderBy('name')
            ->get(['id', 'name']);

        $reports = MaintenanceReport::with([
                'schedule.location.client',
                'schedule.units',
                'user',
            ])
            ->when($this->statusFilter, fn ($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->when($this->clientFilter, function ($q) {
                $q->whereHas('schedule.client', fn ($qq) =>
                    $qq->where('id', $this->clientFilter)
                );
            })
            ->when($this->technicianFilter, fn ($q) =>
                $q->where('user_id', $this->technicianFilter)
            )
            ->when($this->dateFrom, fn ($q) =>
                $q->whereDate('report_date', '>=', $this->dateFrom)
            )
            ->when($this->dateTo, fn ($q) =>
                $q->whereDate('report_date', '<=', $this->dateTo)
            )
            ->when($this->search, function($q){
    $s = "%{$this->search}%";

    $q->where(function($w) use ($s){
        $w->whereHas('schedule.location', fn($l)=>
                $l->where('name','like',$s)
            )
          ->orWhereHas('schedule.client', fn($c)=>
                $c->where('company_name','like',$s)
            )
          ->orWhereHas('user', fn($u)=>
                $u->where('name','like',$s)
            );
    });
})
            ->orderByDesc('report_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.admin.reports.index', [
            'reports'     => $reports,
            'clients'     => $clients,
            'technicians' => $technicians,
        ])->layout('layouts.app', [
            'title'  => 'Laporan Maintenance',
            'header' => 'Operasional • Laporan',
        ]);
    }
}
