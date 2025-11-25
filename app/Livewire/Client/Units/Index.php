<?php

namespace App\Livewire\Client\Units;

use Livewire\Component;
use App\Models\UnitAc;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public function render()
    {
        $client = Auth::user()->clientProfile;

        // ambil unit berdasarkan lokasi milik client
        $units = UnitAc::with(['location.client'])
            ->whereHas('location', function ($q) use ($client) {
                $q->where('client_id', $client->id);
            })
            ->orderBy('location_id')
            ->orderBy('brand')
            ->orderBy('model')
            ->get();

        return view('livewire.client.units.index', compact('units'))
            ->layout('layouts.app', [
                'title'  => 'Unit AC Saya',
                'header' => 'Client • Unit AC',
            ]);
    }
}
