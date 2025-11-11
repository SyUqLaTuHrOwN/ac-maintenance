<?php

namespace App\Livewire\Teknisi\Tasks;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceReport;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public int $year;
    public int $month;

    public ?int $startScheduleId = null;
    public ?int $finishScheduleId = null;

    public $start_photo;
    public $end_photo;
    public $receipt;
    public ?string $notes = null;

    public function mount(): void
    {
        $now = now();
        $this->year = (int) $now->year;
        $this->month = (int) $now->month;
    }

    public function openStartModal(int $scheduleId): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->startScheduleId = $scheduleId;
        $this->start_photo = null;
    }

    public function openFinishModal(int $scheduleId): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->finishScheduleId = $scheduleId;
        $this->end_photo = null;
        $this->receipt = null;
        $this->notes = null;
    }

    public function startWork(): void
    {
        $this->validate([
            'start_photo' => ['required','image','max:4096'],
        ]);

        $schedule = MaintenanceSchedule::where('assigned_user_id', auth()->id())
            ->findOrFail($this->startScheduleId);

        $path = $this->start_photo->store('reports/photos', 'public');

        $report = MaintenanceReport::firstOrNew([
            'schedule_id'   => $schedule->id,
            'technician_id' => auth()->id(),
        ]);

        $report->started_at       = now();
        $report->start_photo_path = $path;
        $report->status           = $report->status ?: 'draft';
        $report->save();

        $schedule->update(['status' => 'dalam_proses']);

        $this->startScheduleId = null;
        session()->flash('ok', 'Pekerjaan dimulai.');
    }

    public function finishWork(): void
    {
        $this->validate([
            'end_photo' => ['required','image','max:4096'],
            'receipt'   => ['nullable','file','max:6144'],
            'notes'     => ['nullable','string'],
        ]);

        $schedule = MaintenanceSchedule::where('assigned_user_id', auth()->id())
            ->findOrFail($this->finishScheduleId);

        $report = MaintenanceReport::firstOrNew([
            'schedule_id'   => $schedule->id,
            'technician_id' => auth()->id(),
        ]);

        if (!$report->started_at) {
            $report->started_at = now();
        }

        $report->finished_at    = now();
        $report->end_photo_path = $this->end_photo->store('reports/photos', 'public');
        if ($this->receipt) {
            $report->receipt_path = $this->receipt->store('reports/receipts', 'public');
        }
        $report->notes  = $this->notes;
        $report->status = 'submitted';
        $report->save();

        $schedule->update(['status' => 'selesai_servis']);

        foreach ($schedule->units as $u) {
            $u->update(['last_maintenance_date' => now()]);
        }

        $this->finishScheduleId = null;
        $this->reset(['end_photo','receipt','notes']);
        session()->flash('ok', 'Laporan selesai diunggah.');
    }

    public function render()
    {
        $from = now()->setDate($this->year, $this->month, 1)->startOfMonth();
        $to   = (clone $from)->endOfMonth();

        $tasks = MaintenanceSchedule::with(['client','location','units','report'])
            ->where('assigned_user_id', auth()->id())
            ->whereBetween('scheduled_at', [$from, $to])
            ->orderBy('scheduled_at')
            ->paginate(10);

        $onDuty = MaintenanceReport::where('technician_id', auth()->id())
            ->whereNull('finished_at')
            ->exists();

        return view('livewire.teknisi.tasks.index', [
            'tasks'  => $tasks,
            'onDuty' => $onDuty,
        ])->layout('layouts.app', [
            'title'  => 'Tugas Saya',
            'header' => 'Teknisi • Tugas Bulanan',
        ]);
    }
}
