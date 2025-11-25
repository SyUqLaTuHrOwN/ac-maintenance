<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\TechnicianProfile;
use App\Models\TechnicianLeave;
use App\Models\MaintenanceSchedule;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $today = now('Asia/Jakarta')->toDateString();

        /*
        |--------------------------------------------------------------------------
        | 1) Auto Aktifkan Teknisi Yang Selesai Cuti
        |--------------------------------------------------------------------------
        */
        TechnicianProfile::where('status', 'cuti')
            ->with('user.technicianLeaves')
            ->get()
            ->each(function ($p) use ($today) {

                $leaveFinished = $p->user->technicianLeaves()
                    ->approved()
                    ->whereDate('end_date', '<', $today)
                    ->exists();

                if ($leaveFinished) {
                    $p->markAsActive(); // method dari model
                }
            });


        /*
        |--------------------------------------------------------------------------
        | 2) Tandai Teknisi Sedang Bertugas (jika ada jadwal hari ini)
        |--------------------------------------------------------------------------
        */
        TechnicianProfile::where('is_active', 1)
            ->with('user')
            ->get()
            ->each(function ($p) use ($today) {

                $hasJobToday = MaintenanceSchedule::where('assigned_user_id', $p->user_id)
                    ->whereDate('scheduled_at', $today)
                    ->whereIn('status', ['menunggu', 'dalam_proses'])
                    ->exists();

                if ($hasJobToday) {
                    $p->markAsBusy(); // method dari model
                }
            });
    }
}
