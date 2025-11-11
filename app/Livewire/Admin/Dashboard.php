<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Client;
use App\Models\MaintenanceSchedule;
use App\Models\UnitAc;

class Dashboard extends Component
{
    public function render()
    {
        $totalClients = Client::count();
        $totalUnits = UnitAc::count();
        $scheduledThisMonth = MaintenanceSchedule::whereMonth('scheduled_at', now()->month)->count();
        $pending = MaintenanceSchedule::where('status','menunggu')->count();

        $nextSchedules = MaintenanceSchedule::with(['client','location','technician'])
            ->where('scheduled_at','>=',now())
            ->orderBy('scheduled_at')
            ->limit(5)->get();

        return view('livewire.admin.dashboard', compact(
            'totalClients','totalUnits','scheduledThisMonth','pending','nextSchedules'
        ))->layout('layouts.app', [
            'title' => 'Dashboard Admin',
            'header' => 'Dashboard Admin',
        ]);
    }
}
