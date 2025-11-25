<?php

namespace App\Livewire\Teknisi\Leave;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\TechnicianLeave;
use App\Models\MaintenanceSchedule;
use Illuminate\Support\Carbon;

class Index extends Component
{
    use WithFileUploads;

    public $start_date, $end_date, $reason, $proof;
    public $filter_status = 'all';

    protected function rules()
    {
        return [
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'reason'     => ['nullable', 'string'],
            'proof'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
        ];
    }

   public function submit()
{
    $data = $this->validate();

    $start = Carbon::parse($this->start_date);
    $end   = Carbon::parse($this->end_date);

    // ❌ Cek bentrok cuti approved lain
    $overlap = TechnicianLeave::approved()
        ->where('user_id', auth()->id())
        ->overlaps($start)
        ->exists();

    if ($overlap) {
        $this->addError('start_date', 'Tanggal cuti bertabrakan dengan cuti lain.');
        return;
    }

    $hasTomorrow = MaintenanceSchedule::where('assigned_user_id', auth()->id())
    ->whereDate('scheduled_at', '=', $start->copy()->subDay()->toDateString())
    ->exists();

if ($hasTomorrow) {
    $this->addError('start_date', 'Tidak bisa cuti karena ada jadwal H+1.');
    return;
}

    // Upload bukti
    $path = $this->proof ? $this->proof->store('leave_proofs', 'public') : null;

   $leave = TechnicianLeave::create([
    'user_id' => auth()->id(),
    'start_date' => $this->start_date,
    'end_date' => $this->end_date,
    'reason' => $this->reason,
    'proof_path' => $path,
    'status' => 'pending',
]);
auth()->user()->profile?->markAsCuti();
    $this->reset(['start_date','end_date','reason','proof']);

    session()->flash('ok','Pengajuan cuti berhasil dikirim.');
}

    public function render()
    {
        $q = TechnicianLeave::where('user_id', auth()->id())->latest();

        if ($this->filter_status !== 'all') {
            $q->where('status', $this->filter_status);
        }

        return view('livewire.teknisi.leave.index', [
            'leaves' => $q->paginate(10),
        ])->layout('layouts.app', [
            'title'  => 'Ajukan Cuti',
            'header' => 'Teknisi • Ajukan Cuti',
        ]);
    }
}
