<?php

namespace App\Livewire\Client\Requests;

use Livewire\Component;
use App\Models\ServiceRequest;
use App\Models\Location;
use App\Models\UnitAc;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class Index extends Component
{
    public ?int $location_id = null;

    /** jumlah unit yang ingin diservis */
    public array $unitQuantities = [];

    public ?string $preferred_at = null;
    public ?string $notes = null;

    public array $unitsOptions = [];
    public $requests;

    public function mount(): void
    {
        $client = auth()->user()->clientProfile;

        $this->location_id = Location::where('client_id', $client->id)
            ->orderBy('name')->value('id');

        $this->refreshUnitsOptions();
        $this->loadHistory();
    }

    public function updatedLocationId(): void
    {
        $this->unitQuantities = [];
        $this->refreshUnitsOptions();
    }

    private function refreshUnitsOptions(): void
    {
        $this->unitsOptions = [];

        if (!$this->location_id) return;

        $rows = UnitAc::where('location_id', $this->location_id)
            ->orderBy('brand')
            ->get();

        foreach ($rows as $u) {
            $this->unitsOptions[] = [
                'id' => $u->id,
                'text' => "{$u->brand} {$u->model} (SN {$u->serial_number})",
                'max' => $u->units_count
            ];
        }
    }

    private function loadHistory(): void
    {
        $client = auth()->user()->clientProfile;

        $this->requests = ServiceRequest::with('location')
            ->where('client_id', $client->id)
            ->latest()
            ->get();
    }

    public function submit(): void
    {
        $client = auth()->user()->clientProfile;

        // VALIDASI BARU
        $this->validate([
            'location_id' => [
                'required',
                Rule::exists('locations', 'id')->where('client_id', $client->id),
            ],

            'unitQuantities' => 'array',
            'unitQuantities.*' => 'nullable|integer|min:0',   // TIDAK VALIDASI BERDASARKAN UNIT LAGI

            'preferred_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // FILTER UNIT YANG > 0
        $cleanUnits = [];
        foreach ($this->unitQuantities as $unitId => $qty) {
            if ($qty > 0) {
                $cleanUnits[$unitId] = [
                    'requested_units' => $qty
                ];
            }
        }

        // SIMPAN REQUEST
        $req = ServiceRequest::create([
            'client_id'    => $client->id,
            'location_id'  => $this->location_id,
            'preferred_at' => $this->preferred_at ? Carbon::parse($this->preferred_at) : null,
            'notes'        => $this->notes,
            'status'       => 'menunggu',
            'created_by'   => auth()->id(),
        ]);

        // SIMPAN DETAIL UNIT REQUEST
        if (!empty($cleanUnits)) {
            $req->units()->sync($cleanUnits);
        }

        // RESET FORM
        $this->unitQuantities = [];
        $this->preferred_at = null;
        $this->notes = null;

        $this->loadHistory();

        $this->dispatch('toast', message: 'Permintaan berhasil dikirim.', type: 'ok');
    }

    public function render()
    {
        $client = auth()->user()->clientProfile;

        $locations = Location::where('client_id', $client->id)
            ->orderBy('name')->get();

        return view('livewire.client.requests.index', [
            'locations'     => $locations,
            'unitsOptions'  => $this->unitsOptions,
            'requests'      => $this->requests,
        ])->layout('layouts.app', [
            'title' => 'Permintaan Maintenance',
            'header' => 'Client • Permintaan',
        ]);
    }
}
