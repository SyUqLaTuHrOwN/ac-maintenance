<?php

namespace App\Jobs;

use App\Models\MaintenanceSchedule;
use App\Notifications\MaintenanceH7Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendH7Reminders implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Kirim email reminder H-7 ke user klien terkait jadwal
     * yang jatuh dalam 7 hari ke depan, sekali saja (reminder_sent_at = NULL).
     */
    public function handle(): void
    {
        $now = now();
        $to  = now()->addDays(7)->endOfDay();

        MaintenanceSchedule::with(['client.user', 'location'])
            ->whereNull('reminder_sent_at')
            ->whereIn('status', ['menunggu', 'menunggu_persetujuan'])
            ->whereBetween('scheduled_at', [$now, $to])
            ->chunkById(100, function ($items) {
                foreach ($items as $s) {
                    $recipient = $s->client?->user;

                    // Lewati jika tidak ada email tujuan
                    if (!$recipient?->email) {
                        continue;
                    }

                    // Kirim notifikasi email
                    $recipient->notify(new MaintenanceH7Reminder($s));

                    // Tandai sudah dikirim agar tidak dobel
                    $s->forceFill(['reminder_sent_at' => now()])->save();
                }
            });
    }
}
