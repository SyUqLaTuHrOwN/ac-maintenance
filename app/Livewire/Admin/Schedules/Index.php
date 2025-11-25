<?php

namespace App\Livewire\Admin\Schedules;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceSchedule;
use App\Models\Client;
use App\Models\Location;
use App\Models\UnitAc;
use App\Models\User;
use App\Models\TechnicianLeave;
use App\Support\Role;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    /* =============================
     | FILTER
     ============================= */
    public ?string $search = '';
    public ?int $clientFilter = null;
    public ?string $statusFilter = null;

    /* =============================
     | FORM
     ============================= */
    public ?int $client_id = null;
    public ?int $location_id = null;
    public ?string $scheduled_at = null;
    public ?int $assigned_user_id = null;
    public string $status = 'menunggu';
    public ?string $notes = null;
    public array $unit_ids = [];

    public ?int $total_units = null;
    public ?int $daily_capacity = null;
    public ?int $estimated_days = null;

    public ?int $editingId = null;

    /* =============================
     | RULES
     ============================= */
    protected function rules(): array
    {
        return [
            'client_id'        => ['required', 'exists:clients,id'],
            'location_id'      => ['required', 'exists:locations,id'],
            'scheduled_at'     => ['required', 'date'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'status'           => ['required', 'string'],
            'notes'            => ['nullable', 'string'],
            'unit_ids'         => ['array'],
            'unit_ids.*'       => ['integer', 'exists:unit_acs,id'],
            'total_units'      => ['nullable', 'integer', 'min:1'],
            'daily_capacity'   => ['nullable', 'integer', 'min:1'],
            'estimated_days'   => ['nullable', 'integer', 'min:1'],
        ];
    }

    /* =============================
     | FORM EVENTS
     ============================= */

    public function updatedClientId()
    {
        $this->location_id = null;
        $this->unit_ids = [];
        $this->resetCounts();
    }

    public function updatedLocationId()
    {
        $this->unit_ids = [];
        $this->resetCounts();
    }

    public function updatedUnitIds()
    {
        $this->recalculateTotals();
    }

    public function updatedDailyCapacity()
    {
        $this->recalculateEstimatedDays();
    }

    protected function resetCounts()
    {
        $this->total_units    = null;
        $this->estimated_days = null;
    }

    protected function recalculateTotals()
    {
        if (!count($this->unit_ids)) {
            $this->resetCounts();
            return;
        }

        $this->total_units = UnitAc::whereIn('id', $this->unit_ids)->sum('units_count');
        $this->recalculateEstimatedDays();
    }

    protected function recalculateEstimatedDays()
    {
        if ($this->total_units && $this->daily_capacity) {
            $this->estimated_days = (int) ceil($this->total_units / $this->daily_capacity);
        } else {
            $this->estimated_days = null;
        }
    }

    /* =============================
     | CREATE / EDIT
     ============================= */

    public function createNew()
    {
        $this->resetForm();
        $this->editingId = 0;        // trigger drawer
    }

    public function edit(int $id)
    {
        $this->resetErrorBag();

        $this->editingId = $id;
        $s = MaintenanceSchedule::with('units')->findOrFail($id);

        $this->client_id        = $s->client_id;
        $this->location_id      = $s->location_id;
        $this->scheduled_at     = $s->scheduled_at?->format('Y-m-d');
        $this->assigned_user_id = $s->assigned_user_id;
        $this->status           = $s->status;
        $this->notes            = $s->notes;
        $this->unit_ids         = $s->units->pluck('id')->toArray();

        $this->total_units    = $s->total_units;
        $this->daily_capacity = $s->daily_capacity;
        $this->estimated_days = $s->estimated_days;

        if (!$this->total_units) {
            $this->recalculateTotals();
        }
    }

    /* =============================
     | SAVE
     ============================= */

    public function save()
    {
        $data = $this->validate();

        $date = Carbon::parse($data['scheduled_at'])->toDateString();

        /* 🔥 VALIDASI: TEKNISI TIDAK BOLEH CUTI / PUNYA JADWAL LAIN */
        if (!empty($data['assigned_user_id'])) {

            // sedang cuti?
            $isOnLeave = TechnicianLeave::approved()
                ->where('user_id', $data['assigned_user_id'])
                ->overlaps($date)
                ->exists();

            if ($isOnLeave) {
                throw ValidationException::withMessages([
                    'assigned_user_id' => 'Teknisi sedang cuti pada tanggal tersebut.',
                ]);
            }

            // sudah punya jadwal lain di tanggal itu? (kecuali jadwal yang sedang diedit)
            $hasJob = MaintenanceSchedule::where('assigned_user_id', $data['assigned_user_id'])
                ->whereDate('scheduled_at', $date)
                ->whereIn('status', ['menunggu', 'dalam_proses'])
                ->when($this->editingId, fn($q) =>
                    $q->where('id', '!=', $this->editingId)
                )
                ->exists();

            if ($hasJob) {
                throw ValidationException::withMessages([
                    'assigned_user_id' => 'Teknisi sudah memiliki tugas pada tanggal tersebut.',
                ]);
            }
        }

        /* HITUNG TOTAL UNIT */
        if (empty($data['total_units'])) {
            $data['total_units'] = UnitAc::whereIn('id', $this->unit_ids)->sum('units_count');
        }

        if (!empty($data['daily_capacity']) && !empty($data['total_units'])) {
            $data['estimated_days'] = (int) ceil($data['total_units'] / $data['daily_capacity']);
        }

        $payload = [
            'client_id'        => $data['client_id'],
            'location_id'      => $data['location_id'],
            'scheduled_at'     => $date,
            'assigned_user_id' => $data['assigned_user_id'] ?? null,
            'status'           => $data['status'],
            'notes'            => $data['notes'],
            'total_units'      => $data['total_units'],
            'daily_capacity'   => $data['daily_capacity'],
            'estimated_days'   => $data['estimated_days'] ?? null,
        ];

        $schedule = $this->editingId
            ? tap(MaintenanceSchedule::findOrFail($this->editingId))->update($payload)
            : MaintenanceSchedule::create($payload);

        $schedule->units()->sync($this->unit_ids);

        session()->flash('ok', $this->editingId ? 'Jadwal diperbarui.' : 'Jadwal dibuat.');

        $this->resetForm();
        $this->editingId = null;
    }

    /* =============================
     | DELETE
     ============================= */

    public function delete(int $id)
    {
        MaintenanceSchedule::findOrFail($id)->delete();
        session()->flash('ok', 'Jadwal dihapus.');
        $this->resetPage();
    }

    /* =============================
     | RESET FORM
     ============================= */

    private function resetForm()
    {
        $this->reset([
            'client_id',
            'location_id',
            'scheduled_at',
            'assigned_user_id',
            'status',
            'notes',
            'unit_ids',
            'total_units',
            'daily_capacity',
            'estimated_days',
        ]);

        $this->status    = 'menunggu';
        $this->unit_ids  = [];
    }

    /* =============================
     | RENDER
     ============================= */

    public function render()
    {
        $clients = Client::orderBy('company_name')->get();

        $locations = $this->client_id
            ? Location::where('client_id', $this->client_id)->orderBy('name')->get()
            : collect();

        $unitsForLocation = $this->location_id
            ? UnitAc::where('location_id', $this->location_id)->orderBy('brand')->get()
            : collect();

        $date = $this->scheduled_at
            ? Carbon::parse($this->scheduled_at)->toDateString()
            : null;

        /* 🔥 FILTER TEKNISI UNTUK DROPDOWN */
        $availableTechs = User::where('role', Role::TEKNISI)
            ->whereHas('technicianProfile', fn($p) =>
                $p->where('is_active', true)
                  ->where('status', 'aktif')       // berdasarkan kolom status profil
            )
            ->when($date, function ($q) use ($date) {

                // tidak sedang cuti
                $q->whereDoesntHave('technicianLeaves', function ($l) use ($date) {
                    $l->approved()->overlaps($date);
                });

                // tidak punya tugas lain di tanggal tsb
                $q->whereDoesntHave('maintenanceSchedules', function ($s) use ($date) {
                    $s->whereDate('scheduled_at', $date)
                      ->whereIn('status', ['menunggu', 'dalam_proses']);
                });
            })
            ->orderBy('name')
            ->get();

        // Saat edit, pastikan teknisi yg sudah ter-assign tetap muncul di dropdown
        if ($this->editingId && $this->assigned_user_id) {
            $exists = $availableTechs->firstWhere('id', $this->assigned_user_id);
            if (!$exists) {
                $fallback = User::find($this->assigned_user_id);
                if ($fallback) {
                    $availableTechs->push($fallback);
                }
            }
        }

        /* LIST JADWAL DI TABEL */
        $schedules = MaintenanceSchedule::with([
                'client',
                'location',
                'technician.technicianProfile',
                'units',
            ])
            ->whereNotIn('status', ['selesai_servis', 'selesai'])
            ->when($this->clientFilter, fn($q) =>
                $q->where('client_id', $this->clientFilter)
            )
            ->when($this->statusFilter, fn($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->when($this->search, function ($q) {
                $s = "%{$this->search}%";
                $q->where('notes', 'like', $s)
                  ->orWhereHas('location', fn($l) => $l->where('name', 'like', $s))
                  ->orWhereHas('client', fn($c) => $c->where('company_name', 'like', $s));
            })
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.schedules.index', [
            'clients'         => $clients,
            'locations'       => $locations,
            'techs'           => $availableTechs,
            'schedules'       => $schedules,
            'unitsForLocation'=> $unitsForLocation,
        ])->layout('layouts.app', [
            'title'  => 'Jadwal Maintenance',
            'header' => 'Operasional • Jadwal',
        ]);
    }
}
