<?php

namespace App\Livewire\Admin\Requests;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServiceRequest;
use App\Models\MaintenanceSchedule;
use Illuminate\Validation\ValidationException;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'menunggu'; // menunggu|ditolak|terjadwal|* (semua)

    // Buat jadwal dari 1 permintaan
    public function schedule(int $requestId): void
    {
        $req = ServiceRequest::with(['client','location','units'])->findOrFail($requestId);

        // amankan: hanya yang menunggu yang bisa dijadwalkan
        if ($req->status !== 'menunggu') {
            throw ValidationException::withMessages(['aksi' => 'Status tidak valid untuk penjadwalan.']);
        }

        // ambil tanggal preferensi jika ada; kalau kosong, jadwalkan H+2 pukul 10:00
        $when = $req->preferred_at
            ? \Carbon\Carbon::parse($req->preferred_at)
            : now()->addDays(2)->setTime(10,0);

        // buat jadwal minimal
        $schedule = MaintenanceSchedule::create([
            'client_id'     => $req->client_id,
            'location_id'   => $req->location_id,
            'technician_id' => null, // admin bisa tetapkan nanti di halaman Jadwal
            'scheduled_at'  => $when,
            'status'        => 'menunggu',
            'note'          => 'Dari permintaan client ID #'.$req->id,
        ]);

        // optional: jika kamu punya pivot maintenance_schedule_units → sync unit-unitnya
        if (method_exists($schedule, 'units') && $req->relationLoaded('units')) {
            $schedule->units()->sync($req->units->pluck('id')->all());
        }

        // update status permintaan
        $req->update(['status' => 'terjadwal']);

        // notifikasi UI
        $this->dispatch('toast', message: 'Jadwal dibuat. Silakan tetapkan teknisi di menu Jadwal.', type: 'ok');

    }

    // Tolak permintaan
    public function reject(int $requestId): void
    {
        $req = ServiceRequest::findOrFail($requestId);
        if ($req->status !== 'menunggu') {
            throw ValidationException::withMessages(['aksi' => 'Status tidak valid untuk ditolak.']);
        }
        $req->update(['status' => 'ditolak']);

        $this->dispatch('toast', message: 'Permintaan ditolak.', type: 'ok');
    }

    // Hapus (opsional)
    public function delete(int $requestId): void
    {
        $req = ServiceRequest::findOrFail($requestId);
        $req->delete();

        $this->dispatch('toast', message: 'Permintaan dihapus.', type: 'ok');
        // reset halaman bila list menjadi kosong
        $this->resetPage();
    }

    public function render()
    {
        $q = ServiceRequest::with(['client.user','location','units'])
            ->when($this->status !== '*', fn($qq) => $qq->where('status',$this->status))
            ->when(strlen($this->search) > 0, function ($qq) {
                $s = '%'.$this->search.'%';
                $qq->where(function ($w) use ($s) {
                    $w->where('notes', 'like', $s)
                      ->orWhereHas('location', fn($x) => $x->where('name', 'like', $s))
                      ->orWhereHas('client', fn($x) => $x->where('company', 'like', $s))
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
