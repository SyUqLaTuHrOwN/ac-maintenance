<?php

namespace App\Livewire\Teknisi;

use Livewire\Component;
use App\Models\MaintenanceSchedule;

class Dashboard extends Component
{
    public function render()
    {
        $userId = auth()->id();

        $activeTasks = MaintenanceSchedule::with(['client','location'])
            ->where('assigned_user_id',$userId)
            ->whereIn('status',['menunggu','dalam_proses'])
            ->orderBy('scheduled_at')->get();

        $history = MaintenanceSchedule::with(['client','location'])
            ->where('assigned_user_id',$userId)
            ->whereIn('status',['selesai_servis','selesai'])
            ->latest('scheduled_at')->limit(5)->get();

        return view('livewire.teknisi.dashboard', compact('activeTasks','history'))
            ->layout('layouts.app', [
                'title' => 'Dashboard Teknisi',
                'header' => 'Dashboard Teknisi',
            ]);
    }
}
