<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceReport;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $status = null; // null semua, atau: draft|submitted|revisi|disetujui
    public int $month;
    public int $year;

    public function mount(): void
    {
        $now = now();
        $this->month = (int) $now->month;
        $this->year  = (int) $now->year;
    }

    public function verify(int $id): void
    {
        $r = MaintenanceReport::findOrFail($id);
        $r->status = 'disetujui';
        $r->verified_by_admin_id = auth()->id();
        $r->verified_at = now();
        $r->save();

        session()->flash('ok','Laporan disetujui.');
    }

    public function requestRevision(int $id): void
    {
        $r = MaintenanceReport::findOrFail($id);
        $r->status = 'revisi';
        $r->verified_by_admin_id = null;
        $r->verified_at = null;
        $r->save();

        session()->flash('ok','Status diubah ke revisi. Minta teknisi perbarui berkas/catatan.');
    }

    public function render()
    {
        $from = now()->setDate($this->year, $this->month, 1)->startOfMonth();
        $to   = (clone $from)->endOfMonth();

        $q = MaintenanceReport::with([
                'schedule.client','schedule.location','technician'
            ])
            // filter tanggal berdasarkan jadwalnya
            ->whereHas('schedule', fn($s)=> $s->whereBetween('scheduled_at', [$from, $to]))
            // search klien/teknisi
            ->when($this->search, function ($qq) {
                $term = "%{$this->search}%";
                $qq->whereHas('schedule.client', fn($c)=> $c->where('company_name','like',$term))
                   ->orWhereHas('technician', fn($t)=> $t->where('name','like',$term));
            })
            // filter status
            ->when($this->status, fn($qq)=> $qq->where('status', $this->status))
            ->latest();

        $reports = $q->paginate(12);

        return view('livewire.admin.reports.index', compact('reports'))
            ->layout('layouts.app', [
                'title'=>'Laporan',
                'header'=>'Operasional • Laporan'
            ]);
    }
}
