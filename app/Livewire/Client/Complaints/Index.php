<?php

namespace App\Livewire\Client\Complaints;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Complaint;

class Index extends Component
{
    use WithFileUploads;

    public ?int $schedule_id = null;
    public string $subject = '';
    public string $message = '';
    public string $priority = 'normal';
    public array $files = [];

    public function submit(): void
    {
        $client = auth()->user()->clientProfile;

        $this->validate([
            'schedule_id' => ['nullable','exists:maintenance_schedules,id'],
            'subject'     => ['required','string','max:200'],
            'message'     => ['required','string','max:2000'],
            'priority'    => ['required','in:low,normal,high'],
            'files.*'     => ['file','max:4096'],
        ]);

        $paths = [];
        foreach ($this->files as $f) {
            $paths[] = $f->store('complaints', 'public');
        }

        Complaint::create([
            'client_id'   => $client->id,
            'user_id'     => auth()->id(),
            'schedule_id' => $this->schedule_id,
            'subject'     => $this->subject,
            'message'     => $this->message,
            'priority'    => $this->priority,
            'attachments' => $paths ?: null,
            'status'      => 'open',
        ]);

        $this->reset(['schedule_id','subject','message','priority','files']);
        session()->flash('ok','Komplain terkirim. Tim kami akan menindaklanjuti.');
    }

    public function render()
    {
        $client = auth()->user()->clientProfile;
        $schedules = \App\Models\MaintenanceSchedule::where('client_id',$client->id)
            ->latest()->take(30)->get();

        $items = Complaint::where('client_id',$client->id)->latest()->get();

        return view('livewire.client.complaints.index', compact('schedules','items'))
            ->layout('layouts.app', ['title'=>'Komplain','header'=>'Client • Komplain']);
    }
}
