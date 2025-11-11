<?php

namespace App\Livewire\Client\Feedback;

use Livewire\Component;
use App\Models\Feedback;

class Index extends Component
{
    public ?int $reportId = null;
    public int $rating = 5;
    public ?string $comment = null;

    public function open(int $reportId): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reportId = $reportId;
        $this->rating = 5;
        $this->comment = null;
    }

    public function submit(): void
    {
        $this->validate([
            'reportId' => ['required','integer','exists:maintenance_reports,id'],
            'rating'   => ['required','integer','between:1,5'],
            'comment'  => ['nullable','string','max:500'],
        ]);

        $user = auth()->user();
        $already = Feedback::where('report_id',$this->reportId)->where('client_user_id',$user->id)->exists();
        if ($already) {
            session()->flash('ok','Feedback sudah pernah diberikan.');
            $this->reportId = null;
            return;
        }

        Feedback::create([
            'report_id'      => $this->reportId,
            'client_user_id' => $user->id,
            'rating'         => $this->rating,
            'comment'        => $this->comment,
        ]);

        $this->reportId = null;
        session()->flash('ok','Terima kasih! Feedback tersimpan.');
    }

    public function render()
    {
        $client = auth()->user()->clientProfile;
        $reports = \App\Models\MaintenanceReport::with(['schedule.location','feedback'])
            ->whereHas('schedule', fn($q)=> $q->where('client_id',$client->id))
            ->whereNotNull('finished_at')
            ->latest()
            ->get();

        return view('livewire.client.feedback.index', compact('reports'))
            ->layout('layouts.app', ['title'=>'Feedback','header'=>'Client • Feedback']);
    }
}
