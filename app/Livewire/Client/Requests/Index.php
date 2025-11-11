<?php

namespace App\Livewire\Client\Requests;

use Livewire\Component;
use App\Models\ServiceRequest;
use App\Models\Location;
use App\Models\UnitAc; // pastikan pakai UnitAc
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class Index extends Component
{
    /** Form state */
    public ?int $location_id = null;
    /** @var array<int> */
    public array $selected_units = [];
    public ?string $preferred_at = null;
    public ?string $notes = null;

    /** Opsi yang dirender di checkbox unit */
    public array $unitsOptions = [];

    /** Riwayat permintaan user saat ini */
    public $requests;

    public function mount(): void
    {
        $client = auth()->user()->clientProfile;

        // pilih lokasi pertama milik client (jika ada)
        $this->location_id = Location::where('client_id', $client->id)
            ->orderBy('name')->value('id');

        $this->refreshUnitsOptions();
        $this->loadHistory();
    }

    /** Setiap ganti lokasi -> reset unit & refresh opsi */
    public function updatedLocationId($value): void
    {
        $this->selected_units = [];
        $this->refreshUnitsOptions();
    }

    private function refreshUnitsOptions(): void
    {
        $this->unitsOptions = [];

        if (!$this->location_id) {
            return;
        }

        $rows = UnitAc::query()
            ->where('location_id', $this->location_id)
            ->orderBy('brand')
            ->get(['id','brand','model','serial_number']);

        $this->unitsOptions = $rows->map(fn ($u) => [
            'id'   => (int) $u->id,
            'text' => trim($u->brand.' '.$u->model.' (SN '.$u->serial_number.')'),
        ])->all();
    }

    private function loadHistory(): void
    {
        $client = auth()->user()->clientProfile;

        $this->requests = ServiceRequest::with('location')
            ->where('client_id', $client->id)
            ->latest()->get();
    }

    public function submit(): void
    {
        $client = auth()->user()->clientProfile;

        $this->validate([
            'location_id' => [
                'required',
                Rule::exists('locations','id')->where('client_id', $client->id),
            ],
            'selected_units'   => ['array'],
            'selected_units.*' => [
                'integer',
                // unit harus milik lokasi yang dipilih
                Rule::exists('unit_acs','id')->where('location_id', $this->location_id),
            ],
            'preferred_at' => ['nullable','date'],
            'notes'        => ['nullable','string','max:1000'],
        ], [], [
            'location_id'   => 'lokasi',
            'selected_units'=> 'unit terkait',
            'preferred_at'  => 'tanggal preferensi',
            'notes'         => 'catatan',
        ]);

        $req = ServiceRequest::create([
            'client_id'    => $client->id,
            'location_id'  => $this->location_id,
            'preferred_at' => $this->preferred_at ? Carbon::parse($this->preferred_at) : null,
            'notes'        => $this->notes,
            'status'       => 'menunggu',
            'created_by'   => auth()->id(),  // kolom ini diminta oleh migrasi Anda
        ]);

        if (!empty($this->selected_units)) {
            // pivot: service_request_units (request_id, unit_ac_id)
            $req->units()->sync($this->selected_units);
        }

        // bersihkan form
        $this->selected_units = [];
        $this->preferred_at   = null;
        $this->notes          = null;

        $this->loadHistory();

        // tampilkan popup toast
        $this->dispatch('toast', message: 'Permintaan terkirim. Admin akan memproses.', type: 'ok');
    }

    public function render()
    {
        $client = auth()->user()->clientProfile;

        $locations = Location::where('client_id', $client->id)
            ->orderBy('name')->get(['id','name']);

        return view('livewire.client.requests.index', [
                'locations' => $locations,
                'unitsOptions' => $this->unitsOptions,
                'requests' => $this->requests,
            ])
            ->layout('layouts.app', [
                'title'  => 'Permintaan Maintenance',
                'header' => 'Client • Permintaan',
            ]);
    }
}
