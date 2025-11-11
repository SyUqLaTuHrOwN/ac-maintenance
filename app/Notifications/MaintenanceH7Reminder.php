<?php

namespace App\Notifications;

use App\Models\MaintenanceSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceH7Reminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public MaintenanceSchedule $schedule) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $s   = $this->schedule;
        $dt  = $s->scheduled_at->timezone(config('app.timezone'))->format('d M Y H:i');
        $kl  = $s->client?->company_name;
        $lok = $s->location?->name;

        // signed links (aman, expire 3 hari)
        $confirmUrl    = \URL::temporarySignedRoute('schedule.client.confirm', now()->addDays(3), ['schedule' => $s->id]);
        $rescheduleUrl = \URL::temporarySignedRoute('schedule.client.reschedule.form', now()->addDays(3), ['schedule' => $s->id]);
        $cancelUrl     = \URL::temporarySignedRoute('schedule.client.cancel', now()->addDays(3), ['schedule' => $s->id]);

        return (new MailMessage)
            ->subject('Reminder H-7 Maintenance AC')
            ->greeting("Halo, {$kl}")
            ->line("Jadwal maintenance Anda akan dilaksanakan pada **{$dt}** di lokasi **{$lok}**.")
            ->line('Silakan konfirmasi:')
            ->action('✅ Konfirmasi Jadwal', $confirmUrl)
            ->line('Atau:')
            ->action('🔄 Ajukan Jadwal Ulang', $rescheduleUrl)
            ->line('Jika perlu membatalkan sementara:')
            ->action('❌ Batalkan Jadwal', $cancelUrl)
            ->line('Terima kasih.');
    }
}
