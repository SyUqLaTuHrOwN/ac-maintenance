<?php

namespace App\Livewire\Client\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceReport;
use Illuminate\Support\Carbon;

class Index extends Component
{
    use WithPagination;

    public ?string $statusFilter = '';
    public ?string $dateFrom = '';
    public ?string $dateTo   = '';
    public ?string $search   = '';

    public $detail = null;

    protected $paginationTheme = 'tailwind';

    public function updating($field)
    {
        if (in_array($field, ['statusFilter','dateFrom','dateTo','search'])) {
            $this->resetPage();
        }
    }

    public function openDetail(int $id): void
    {
        $this->detail = MaintenanceReport::with([
            'user',
            'schedule.location.client',
            'schedule.units'
        ])->findOrFail($id);
    }

    public function closeDetail(): void
    {
        $this->detail = null;
    }

    // ✔ PERBAIKAN UTAMA
   public function confirmReport(int $id)
{
    $report = MaintenanceReport::with('schedule')->findOrFail($id);

    // update laporan
    $report->update([
        'status'             => 'client_approved',
        'client_approved_by' => auth()->id(),
        'client_approved_at' => now('Asia/Jakarta'),
    ]);

    // update jadwal
    if ($report->schedule) {
        $sched = $report->schedule;

        $sched->status = 'selesai_servis';
        $sched->completed_at = $report->report_date;

        // tambahkan kolom baru
        $sched->client_approved_at = now('Asia/Jakarta');
        $sched->client_approved_by = auth()->id();

        $sched->save();
    }

    session()->flash('ok', 'Terima kasih, service sudah dikonfirmasi.');
    $this->resetPage();
}


    public function render()
    {
        $clientId = auth()->user()->client->id;
        

        $reports = MaintenanceReport::with([
            'schedule.location.client',
            'schedule.units',
            'user'
        ])
       ->whereHas('schedule', fn($q)=>
    $q->where('client_id', $clientId)
)
        ->where('status', 'approved')                 // hanya approved admin
        ->whereNull('client_approved_at')            // belum dikonfirmasi client
        ->when($this->dateFrom, fn($q) =>
            $q->whereDate('report_date', '>=', $this->dateFrom)
        )
        ->when($this->dateTo, fn($q) =>
            $q->whereDate('report_date', '<=', $this->dateTo)
        )
        ->when($this->search, function ($q) {
            $s = '%'.$this->search.'%';
            $q->whereHas('schedule.location', fn($qq) =>
                $qq->where('name', 'like', $s)
            );
        })
        ->orderByDesc('report_date')
        ->paginate(10);

        return view('livewire.client.reports.index', [
            'reports' => $reports,
        ])->layout('layouts.app', [
            'title'  => 'Laporan',
            'header' => 'Client • Laporan Teknisi'
        ]);
    }
}
