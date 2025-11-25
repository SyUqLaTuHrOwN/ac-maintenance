<?php

namespace App\Livewire\Admin\History;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Client;
use App\Models\Location;
use App\Models\UnitAc;
use App\Support\Role;

class Index extends Component
{
    use WithPagination;

    // FILTER
    public ?string $dateRange = null;
    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?int $clientFilter = null;
    public ?int $locationFilter = null;
    public ?int $technicianFilter = null;
    public ?int $unitFilter = null;
    public string $search = '';

    // DETAIL MODAL
    public ?int $detailScheduleId = null;
    public ?MaintenanceSchedule $detailSchedule = null;

    protected $paginationTheme = 'tailwind';

    public function updating($field)
    {
        if (in_array($field, [
            'dateRange','dateFrom','dateTo',
            'clientFilter','locationFilter','technicianFilter','unitFilter',
            'search'
        ])) {
            $this->resetPage();
        }
    }

    public function updatedDateRange($value): void
    {
        if (!$value) {
            $this->dateFrom = null;
            $this->dateTo = null;
            return;
        }

        $parts = explode(' to ', $value);
        $this->dateFrom = $parts[0] ?? null;
        $this->dateTo = $parts[1] ?? $parts[0];
    }

    public function updatedClientFilter(): void
    {
        $this->locationFilter = null;
        $this->unitFilter = null;
        $this->resetPage();
    }

    public function updatedLocationFilter(): void
    {
        $this->unitFilter = null;
        $this->resetPage();
    }

    public function openDetail(int $id): void
    {
        $this->detailScheduleId = $id;

        $this->detailSchedule = MaintenanceSchedule::with([
            'client',
            'location',
            'units',
            'reports.user',
            'technician',
        ])->find($id);
    }

    public function closeDetail(): void
    {
        $this->detailSchedule = null;
        $this->detailScheduleId = null;
    }

    public function render()
    {
        // LIST FILTER
        $clients = Client::orderBy('company_name')->get();
        $locations = Location::when($this->clientFilter, fn($q) =>
            $q->where('client_id', $this->clientFilter)
        )->get();

        $technicians = User::where('role', Role::TEKNISI)->orderBy('name')->get();

        $units = UnitAc::when($this->locationFilter, fn($q) =>
            $q->where('location_id', $this->locationFilter)
        )->get();

        // QUERY HISTORY
        $query = MaintenanceSchedule::with([
            'client',
            'location',
            'units',
            'reports',
            'technician',
        ])
        ->where('status', 'selesai_servis')

        ->when($this->dateFrom, fn($q) =>
            $q->whereDate('scheduled_at', '>=', $this->dateFrom)
        )
        ->when($this->dateTo, fn($q) =>
            $q->whereDate('scheduled_at', '<=', $this->dateTo)
        )
        ->when($this->clientFilter, fn($q) =>
            $q->where('client_id', $this->clientFilter)
        )
        ->when($this->locationFilter, fn($q) =>
            $q->where('location_id', $this->locationFilter)
        )
        ->when($this->technicianFilter, fn($q) =>
            $q->where('assigned_user_id', $this->technicianFilter)
        )
        ->when($this->unitFilter, fn($q) =>
            $q->whereHas('units', fn($u) =>
                $u->where('unit_acs.id', $this->unitFilter)
            )
        )
        ->when($this->search, function($q){
            $s = "%{$this->search}%";
            $q->where(function($w) use ($s){
                $w->whereHas('client', fn($x)=>$x->where('company_name','like',$s))
                  ->orWhereHas('location', fn($x)=>$x->where('name','like',$s))
                  ->orWhere('notes','like',$s);
            });
        })
        ->orderBy('scheduled_at','desc');

        // PAGINATE & COUNT UNITS
        $items = $query->paginate(15)->through(function($s){

            // Correct total units from pivot
            $s->unit_count = $s->units->sum(fn($u)=>$u->pivot->requested_units ?? 1);

            // Correct units finished (approved reports)
          $s->approved_units = $s->reports
    ->whereIn('status', ['approved', 'client_approved'])
    ->sum('units_done');

            return $s;
        });

        return view('livewire.admin.history.index', [
            'items' => $items,
            'clients' => $clients,
            'locations' => $locations,
            'technicians' => $technicians,
            'units' => $units,
        ])->layout('layouts.app', [
            'title' => 'Riwayat Tugas',
            'header' => 'Operasional • Riwayat Teknisi',
        ]);
    }
}
