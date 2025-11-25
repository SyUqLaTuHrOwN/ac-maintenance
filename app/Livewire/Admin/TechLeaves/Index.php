<?php

namespace App\Livewire\Admin\TechLeaves;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TechnicianLeave;
use App\Models\TechnicianProfile;
use Illuminate\Support\Carbon;

class Index extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';
    public ?TechnicianLeave $detailLeave = null;
    public bool $showDetail = false;

    protected $paginationTheme = 'tailwind';

    public function openDetail($id)
    {
        $this->detailLeave = TechnicianLeave::with(['user','decider'])->findOrFail($id);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->detailLeave = null;
        $this->showDetail = false;
    }

    /**
     * Admin menyetujui cuti
     */
    public function approve($id)
{
    $leave = TechnicianLeave::findOrFail($id);
    $leave->update([
        'status' => 'approved',
        'decided_by' => auth()->id(),
        'decided_at' => now('Asia/Jakarta')
    ]);

    $leave->user->profile?->markAsCuti();

    $this->dispatch('toast', message: 'Pengajuan cuti disetujui.');
}

    /**
     * Admin menolak
     */
   public function reject($id)
{
    $leave = TechnicianLeave::findOrFail($id);
    $leave->update([
        'status' => 'rejected',
        'decided_by' => auth()->id(),
        'decided_at' => now('Asia/Jakarta')
    ]);

    $leave->user->profile?->markAsActive();

    $this->dispatch('toast', message: 'Pengajuan cuti ditolak.');
}

    /**
     * Auto update → cuti selesai hari ini
     */
    public function autoUpdateLeaveStatus()
    {
        $today = Carbon::now()->toDateString();

        $doneLeaves = TechnicianLeave::where('status','approved')
            ->whereDate('end_date','<', $today)
            ->get();

        foreach ($doneLeaves as $leave) {
            TechnicianProfile::updateOrCreate(
                ['user_id' => $leave->user_id],
                ['status' => 'aktif']
            );
        }
    }

    public function render()
    {
        $this->autoUpdateLeaveStatus();

        $query = TechnicianLeave::with(['user','decider'])->orderBy('created_at','desc');

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.tech-leaves.index', [
            'leaves' => $query->paginate(10),
        ])->layout('layouts.app', [
            'title' => 'Permintaan Cuti Teknisi',
            'header' => 'Operasional • Permintaan Cuti Teknisi'
        ]);
    }
}
