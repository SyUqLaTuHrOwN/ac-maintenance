<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceReport;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $client = Auth::user()->client;

        // Total unit AC milik client
        $units = $client->units()->count();

        // Jadwal maintenance mendatang
        $upcoming = MaintenanceSchedule::where('client_id', $client->id)
            ->whereDate('scheduled_at', '>=', now('Asia/Jakarta'))
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        // 🔥 FIX: pakai 'reports' (bukan report)
        // Ambil laporan terbaru dari jadwal client
        $latestReports = MaintenanceSchedule::with(['reports', 'location'])
            ->where('client_id', $client->id)
            ->whereHas('reports')
            ->orderByDesc('scheduled_at')
            ->take(5)
            ->get();

        return view('livewire.client.dashboard', compact(
            'client',
            'units',
            'upcoming',
            'latestReports'
        ))->layout('layouts.app', [
            'title'  => 'Dashboard',
            'header' => 'Client • Dashboard'
        ]);
    }
}
