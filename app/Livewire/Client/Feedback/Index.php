<?php

namespace App\Livewire\Client\Feedback;

use Livewire\Component;
use App\Models\Feedback;
use App\Models\MaintenanceReport;

class Index extends Component
{
    public ?int $reportId = null;
    public int $rating = 5;
    public ?string $comment = null;

    // =========================
    // BUKA MODAL / FORM RATING
    // =========================
    public function open(int $reportId): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->reportId = $reportId;
        $this->rating   = 5;
        $this->comment  = null;
    }

    // =========================
    // SIMPAN FEEDBACK
    // =========================
    public function submit(): void
    {
        $this->validate([
            'reportId' => ['required', 'integer', 'exists:maintenance_reports,id'],
            'rating'   => ['required', 'integer', 'between:1,5'],
            'comment'  => ['nullable', 'string', 'max:500'],
        ]);

        $user = auth()->user();

        // ✅ CEGAH DOUBLE RATING
        $already = Feedback::where('report_id', $this->reportId)
            ->where('client_user_id', $user->id)
            ->exists();

        if ($already) {
            session()->flash('ok', 'Feedback sudah pernah diberikan.');
            $this->reportId = null;
            return;
        }

        // ✅ SIMPAN FEEDBACK
        Feedback::create([
            'report_id'      => $this->reportId,
            'client_user_id' => $user->id,
            'rating'         => $this->rating,
            'comment'        => $this->comment,
        ]);

        // ✅ UPDATE STATUS LAPORAN SETELAH DIRATING
        $report = MaintenanceReport::find($this->reportId);

        if ($report) {
            $report->status = 'rated';
            $report->save();
        }

        $this->reportId = null;
        session()->flash('ok', 'Terima kasih! Feedback berhasil disimpan.');
    }

    // =========================
    // LOAD DATA LAPORAN CLIENT
    // =========================
    public function render()
    {
        $client = auth()->user()->clientProfile;

        $reports = MaintenanceReport::with(['schedule.location', 'feedback'])
            ->whereHas('schedule', function ($q) use ($client) {
                $q->where('client_id', $client->id);
            })
            ->whereIn('status', ['approved', 'client_approved']) // ✅ STATUS VALID
            ->whereDoesntHave('feedback') // ✅ HANYA YANG BELUM DIRATING
            ->latest()
            ->get();

        return view('livewire.client.feedback.index', compact('reports'))
            ->layout('layouts.app', [
                'title'  => 'Feedback',
                'header' => 'Client • Feedback'
            ]);
    }
}
