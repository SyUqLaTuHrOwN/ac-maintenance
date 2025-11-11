<?php

namespace App\Livewire\Client\Schedules;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $client = auth()->user()->clientProfile;
        $schedules = \App\Models\MaintenanceSchedule::with(['location','technician','report'])
            ->where('client_id', $client->id)
            ->orderByDesc('scheduled_at')->get();

        return view('livewire.client.schedules.index', compact('schedules'))
            ->layout('layouts.app', ['title'=>'Jadwal Maintenance','header'=>'Client • Jadwal']);
    }
}
