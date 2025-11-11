<?php

namespace App\Livewire\Client\Units;

use Livewire\Component;
use App\Models\UnitAc; // ← pakai model yang ada
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public function render()
    {
        $client = Auth::user()->clientProfile;

        // Ambil unit milik client melalui relasi lokasi (location.client_id)
        $units = UnitAc::with(['location']) // kalau Location punya relasi client, boleh tambah 'location.client'
            ->whereHas('location', function ($q) use ($client) {
                $q->where('client_id', $client->id);
            })
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
