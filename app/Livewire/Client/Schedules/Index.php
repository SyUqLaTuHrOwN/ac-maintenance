<?php

namespace App\Livewire\Client\Schedules;

use Livewire\Component;
use App\Models\MaintenanceSchedule;

class Index extends Component
{
    public function render()
    {
       $client = auth()->user()->client;


        $schedules = MaintenanceSchedule::with(['location.client', 'technician', 'units'])
            ->where('client_id', $client->id)
            // HANYA jadwal yg belum selesai servis
            ->where('status', '!=', 'selesai_servis')
            ->orderByDesc('scheduled_at')
            ->get();

        return view('livewire.client.schedules.index', compact('schedules'))
            ->layout('layouts.app', [
                'title'  => 'Jadwal Maintenance',
                'header' => 'Client • Jadwal',
            ]);
    }
}
