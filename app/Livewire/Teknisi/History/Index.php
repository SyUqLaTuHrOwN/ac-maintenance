<?php

namespace App\Livewire\Teknisi\History;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceSchedule;

class Index extends Component
{
    use WithPagination;

    public int $year;
    public int $month;
    public string $search = '';
    public ?string $status = null;

    public function mount(): void
    {
        $now = now();
        $this->year = (int) $now->year;
        $this->month = (int) $now->month;
    }

    public function render()
    {
        $from = now()->setDate($this->year, $this->month, 1)->startOfMonth();
        $to   = (clone $from)->endOfMonth();

        $q = MaintenanceSchedule::with(['client','location','units','report'])
            ->where('assigned_user_id', auth()->id())
            ->whereBetween('scheduled_at', [$from, $to])
            ->where(function($w){
                $w->whereIn('status', ['selesai_servis','selesai','dibatalkan_oleh_klien'])
                  ->orWhere('scheduled_at', '<', now());
            })
            ->when($this->search, function($qq){
                $term = "%{$this->search}%";
                $qq->whereHas('client', fn($c)=>$c->where('company_name','like',$term))
                   ->orWhereHas('location', fn($l)=>$l->where('name','like',$term));
            })
            ->when($this->status, fn($qq)=> $qq->where('status', $this->status))
            ->orderByDesc('scheduled_at');

        $items = $q->paginate(10);

        return view('livewire.teknisi.history.index', compact('items'))
            ->layout('layouts.app', ['title'=>'Riwayat Tugas','header'=>'Teknisi • Riwayat']);
    }
}
