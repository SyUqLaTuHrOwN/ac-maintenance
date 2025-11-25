<?php

namespace App\Livewire\Client\History;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceReport;

class Index extends Component
{
    use WithPagination;

    public bool $showDetail = false;
    public $detailReport = null;

    protected $paginationTheme = 'tailwind';

    public function showDetailReport($id)
    {
        $this->detailReport = MaintenanceReport::with([
            'schedule.location.client',
            'schedule.units',
            'user'
        ])->findOrFail($id);

        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->detailReport = null;
    }

    public function render()
    {
        $clientId = auth()->user()->client->id;

        $histories = MaintenanceReport::with([
            'schedule.location.client',
            'schedule.units',
            'user'
        ])
        ->where('status', 'client_approved')
        ->whereHas('schedule', fn($q) =>
            $q->where('client_id', $clientId)
              ->where('status', 'selesai_servis')
        )
        ->orderByDesc('report_date')
        ->paginate(10);

        return view('livewire.client.history.index', [
            'histories'    => $histories,
            'detailReport' => $this->detailReport,
            'showDetail'   => $this->showDetail,
        ])->layout('layouts.app', [
            'title'  => 'History',
            'header' => 'Client • History'
        ]);
    }
}
