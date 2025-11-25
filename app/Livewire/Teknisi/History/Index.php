<?php

namespace App\Livewire\Teknisi\History;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceReport;

class Index extends Component
{
    use WithPagination;

    public int $year;
    public int $month;
    public string $search = '';
    public ?string $status = null;

    // modal detail
    public ?int $detailScheduleId = null;
    public ?MaintenanceSchedule $detailSchedule = null;
    public $detailReports = [];

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        $now = now('Asia/Jakarta');
        $this->year = (int) $now->year;
        $this->month = (int) $now->month;
    }

    public function updating($field)
    {
        if (in_array($field, ['year', 'month', 'search', 'status'])) {
            $this->resetPage();
        }
    }

    public function openDetail(int $scheduleId): void
{
    $this->detailScheduleId = $scheduleId;

    $this->detailSchedule = MaintenanceSchedule::with([
        'client',
        'location',
        'units',
        'reports.user',
    ])->find($scheduleId);

    $this->detailReports = $this->detailSchedule
        ? $this->detailSchedule->reports()->orderBy('report_date')->get()
        : collect();
}

    public function closeDetail(): void
    {
        $this->detailScheduleId = null;
        $this->detailSchedule   = null;
        $this->detailReports    = [];
    }

    public function render()
    {
        $from = now('Asia/Jakarta')->setDate($this->year, $this->month, 1)->startOfMonth();
        $to   = (clone $from)->endOfMonth();

      $q = MaintenanceSchedule::with(['client','location','units','reports'])
            ->where('assigned_user_id', auth()->id())
            ->whereBetween('scheduled_at', [$from, $to])
            ->where(function($w){
    $w->whereIn('status', ['selesai_servis','selesai'])
      ->whereHas('reports', function($r){
          $r->whereIn('status', ['approved','client_approved']);
      });
})
            ->when($this->search, function($qq){
                $term = "%{$this->search}%";
                $qq->whereHas('client', fn($c)=>$c->where('company_name','like',$term))
                   ->orWhereHas('location', fn($l)=>$l->where('name','like',$term));
            })
            ->when($this->status, fn($qq)=> $qq->where('status', $this->status))
            ->orderByDesc('scheduled_at');

        $items = $q->paginate(10);

        return view('livewire.teknisi.history.index', compact('items'))
            ->layout('layouts.app', ['title'=>'Riwayat Tugas','header'=>'Teknisi • Riwayat']);
    }
}
