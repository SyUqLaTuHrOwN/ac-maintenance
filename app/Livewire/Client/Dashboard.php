<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Client;
use App\Models\MaintenanceSchedule;
use App\Models\Location;
use App\Models\UnitAc;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $client = Client::where('user_id',$user->id)->first();

        $units = UnitAc::whereIn('location_id', Location::where('client_id', $client?->id)->pluck('id'))->count();

        $upcoming = MaintenanceSchedule::with(['location'])
            ->where('client_id', $client?->id)
            ->where('scheduled_at','>=', now())
            ->orderBy('scheduled_at')->limit(5)->get();

        $latestReports = MaintenanceSchedule::with('report')
            ->where('client_id', $client?->id)
            ->latest('scheduled_at')->limit(5)->get();

        return view('livewire.client.dashboard', compact('client','units','upcoming','latestReports'))
            ->layout('layouts.app', [
                'title' => 'Dashboard Client',
                'header' => 'Dashboard Client',
            ]);
    }
}
