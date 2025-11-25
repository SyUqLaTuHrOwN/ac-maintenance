<?php

namespace App\Livewire\Teknisi\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceReport;
use Illuminate\Support\Carbon;

class Index extends Component
{
    use WithPagination;

    public ?string $search = '';
    public ?string $dateFrom = null;
    public ?string $dateTo   = null;
    public ?string $statusFilter = null;

    public ?int $detailId = null;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function showDetail(int $id)
    {
        $this->detailId = $id;
    }

    public function closeDetail()
    {
        $this->detailId = null;
    }

    public function render()
    {
        $userId = auth()->id();
$reports = MaintenanceReport::with(['schedule.location.client','user'])
    ->where('user_id', $userId)
    ->whereIn('status', ['draft','submitted','revision','rejected']) 
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
                    )
                  ->orWhereHas('schedule.client', fn($qq) =>
                        $qq->where('company_name', 'like', $s)
                    );
            })
            ->orderByDesc('report_date')
            ->orderByDesc('id')
            ->paginate(10);

        $detail = null;
        if ($this->detailId) {
            $detail = MaintenanceReport::with([
                'schedule.location.client',
                'user',
            ])->find($this->detailId);
        }

        return view('livewire.teknisi.reports.index', [
            'reports' => $reports,
            'detail'  => $detail,
        ])->layout('layouts.app', [
            'title'  => 'Laporan Teknisi',
            'header' => 'Operasional • Laporan Teknisi',
        ]);
    }
}
