<?php

namespace App\Livewire\Admin\Requests;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServiceRequest;
use App\Models\MaintenanceSchedule;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'menunggu'; // menunggu|ditolak|terjadwal|*

    public function schedule(int $requestId): void
    {
        $req = ServiceRequest::with(['client', 'location', 'units'])->findOrFail($requestId);

        if ($req->status !== 'menunggu') {
            throw ValidationException::withMessages([
                'aksi' => 'Status tidak valid untuk penjadwalan.',
            ]);
        }

        // tanggal rencana
        $when = $req->preferred_at
            ? Carbon::parse($req->preferred_at)
            : now()->addDays(2)->setTime(10, 0);

        // hitung total unit yang diminta di permintaan ini
        $totalRequested = $req->units->sum(fn($u) => $u->pivot->requested_units ?? 1);

        $schedule = MaintenanceSchedule::create([
            'client_id'     => $req->client_id,
            'location_id'   => $req->location_id,
            'technician_id' => null,
            'scheduled_at'  => $when,
            'status'        => 'menunggu',
            'total_units'   => $totalRequested,
            'progress_units'=> 0,
            'notes'         => 'Dari permintaan client #' . $req->id,
        ]);

        // pivot unit → jadwal
        if ($req->units->isNotEmpty()) {
            $attach = [];
            foreach ($req->units as $u) {
                $attach[$u->id] = [
                    'requested_units' => $u->pivot->requested_units ?? 1,
                ];
            }
            $schedule->units()->sync($attach);
        }

        $req->update(['status' => 'terjadwal']);

        $this->dispatch('toast', message: 'Jadwal dibuat dari permintaan client.', type: 'ok');
    }

    public function reject(int $requestId): void
    {
        $req = ServiceRequest::findOrFail($requestId);
        if ($req->status !== 'menunggu') {
            throw ValidationException::withMessages([
                'aksi' => 'Status tidak valid untuk ditolak.',
            ]);
        }

        $req->update(['status' => 'ditolak']);

        $this->dispatch('toast', message: 'Permintaan ditolak.', type: 'ok');
    }

    public function delete(int $requestId): void
    {
        $req = ServiceRequest::findOrFail($requestId);
        $req->delete();

        $this->dispatch('toast', message: 'Permintaan dihapus.', type: 'ok');
        $this->resetPage();
    }

    public function render()
    {
        $q = ServiceRequest::with(['client.user', 'location', 'units'])
            ->when($this->status !== '*', fn($qq) => $qq->where('status', $this->status))
            ->when(strlen($this->search) > 0, function ($qq) {
                $s = '%' . $this->search . '%';
                $qq->where(function ($w) use ($s) {
                    $w->where('notes', 'like', $s)
                        ->orWhereHas('location', fn($x) => $x->where('name', 'like', $s))
                        ->orWhereHas('client', fn($x) => $x->where('company_name', 'like', $s))
                        ->orWhereHas('client.user', fn($x) => $x->where('name', 'like', $s));
                });
            })
            ->latest();

        $requests = $q->paginate(10);

        return view('livewire.admin.requests.index', compact('requests'))
            ->layout('layouts.app', [
                'title'  => 'Permintaan',
                'header' => 'Operasional • Permintaan',
            ]);
    }
}
