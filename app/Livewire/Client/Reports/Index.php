<?php

namespace App\Livewire\Client\Reports;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $client = auth()->user()->clientProfile;
        $reports = \App\Models\MaintenanceReport::with(['schedule.location','technician'])
            ->whereHas('schedule', fn($q)=> $q->where('client_id',$client->id))
            ->latest()->get();

        return view('livewire.client.reports.index', compact('reports'))
            ->layout('layouts.app', ['title'=>'Laporan','header'=>'Client • Laporan Teknisi']);
    }
}
