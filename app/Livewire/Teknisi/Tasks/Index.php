<?php

namespace App\Livewire\Teknisi\Tasks;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceReport;
use Illuminate\Support\Carbon;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    // Modal laporan
    public ?int $reportScheduleId = null;
    public ?int $units_done = null;

    public array $photos_start = [];  // foto mulai (multiple)
    public array $photos_finish = []; // foto selesai (multiple)
    public array $photos_extra = [];  // foto tambahan (multiple)
    public $invoice;                  // 1 file nota (optional)

    public ?string $notes = null;

    protected $paginationTheme = 'tailwind';

    /* ==========================
       AKSI: MULAI TUGAS
    =========================== */

    public function startTask(int $id): void
    {
        $schedule = MaintenanceSchedule::findOrFail($id);

        if ($schedule->status !== 'menunggu') {
            return;
        }

        // Pastikan minimal sudah hari H
        $today = now('Asia/Jakarta')->toDateString();
        if ($today < $schedule->scheduled_at->toDateString()) {
            session()->flash('err', 'Belum waktunya memulai tugas.');
            return;
        }

        $schedule->update([
            'status' => 'dalam_proses',
        ]);

        session()->flash('ok', 'Tugas dimulai.');
    }

    /* ==========================
       BUKA MODAL LAPORAN
    =========================== */

    public function openReportModal(int $scheduleId): void
    {
        $this->reportScheduleId = $scheduleId;
        $this->resetReportForm();
    }

    private function resetReportForm(): void
    {
        $this->reset([
            'units_done',
            'photos_start',
            'photos_finish',
            'photos_extra',
            'invoice',
            'notes',
        ]);
    }

    /* ==========================
       KIRIM LAPORAN HARIAN
    =========================== */

    public function submitReport(): void
    {
        if (!$this->units_done) {
    session()->flash('err', 'Jumlah unit selesai wajib diisi.');
    return;
}

      $this->validate([
    'reportScheduleId' => ['required','integer','exists:maintenance_schedules,id'],
    'units_done' => ['required','integer','min:1'],    //<— WAJIB
    'photos_start.*' => ['nullable','image','max:4096'],
    'photos_finish.*' => ['nullable','image','max:4096'],
    'photos_extra.*' => ['nullable','image','max:4096'],
    'invoice' => ['nullable','file','max:5120'],
    'notes' => ['nullable','string'],
]);

        $schedule = MaintenanceSchedule::with('units')->findOrFail($this->reportScheduleId);

        // Hitung total unit & sisa
        $totalUnits = $schedule->total_units ?? $schedule->units->sum('units_count');
        $current    = $schedule->progress_units ?? 0;
        $remaining  = max(0, $totalUnits - $current);

        if ($remaining <= 0) {
            session()->flash('err', 'Semua unit sudah selesai untuk jadwal ini.');
            $this->reportScheduleId = null;
            return;
        }

        $unitsDone = min($this->units_done, $remaining);
        if ($unitsDone <= 0) {
            session()->flash('err', 'Jumlah unit yang dilaporkan tidak valid.');
            return;
        }

        $today   = now('Asia/Jakarta')->toDateString();
        $baseDir = "tasks/{$schedule->id}/{$today}";

        $startPaths  = [];
        $finishPaths = [];
        $extraPaths  = [];
        $invoicePath = null;

        foreach ($this->photos_start as $file) {
            $startPaths[] = $file->store($baseDir.'/start', 'public');
        }

        foreach ($this->photos_finish as $file) {
            $finishPaths[] = $file->store($baseDir.'/finish', 'public');
        }

        foreach ($this->photos_extra as $file) {
            $extraPaths[] = $file->store($baseDir.'/extra', 'public');
        }

        if ($this->invoice) {
            $invoicePath = $this->invoice->store($baseDir.'/invoice', 'public');
        }

        // Simpan laporan harian
        MaintenanceReport::create([
    'schedule_id'   => $schedule->id,
    'user_id'       => auth()->id(),
    'report_date' => now('Asia/Jakarta'),
    'units_done'    => $unitsDone,
    'photos_start'  => $startPaths,
    'photos_finish' => $finishPaths,
    'photos_extra'  => $extraPaths,
    'invoice_path'  => $invoicePath,
    'notes'         => $this->notes,
    'status'        => 'submitted',
]);


        // Update progress jadwal
        $schedule->progress_units = $current + $unitsDone;

        if ($schedule->progress_units >= $totalUnits) {
            $schedule->status = 'selesai_servis';
        }

        $schedule->save();

        $this->reportScheduleId = null;
        $this->resetReportForm();
        session()->flash('ok', "Laporan tersimpan. {$unitsDone} unit tercatat selesai hari ini.");
    }

    /* ==========================
       RENDER
    =========================== */

    public function render()
    {
        $user = auth()->user();

        $tasks = MaintenanceSchedule::with(['client','location','units'])
            ->where('assigned_user_id', $user->id)
            ->whereIn('status', ['menunggu','dalam_proses'])
            ->orderBy('scheduled_at')
            ->paginate(10);

        return view('livewire.teknisi.tasks.index', [
            'tasks' => $tasks,
        ])->layout('layouts.app', [
            'title'  => 'Tugas Teknisi',
            'header' => 'Operasional • Tugas Teknisi',
        ]);
    }
}
