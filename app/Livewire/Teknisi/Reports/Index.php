<?php

namespace App\Livewire\Teknisi\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\MaintenanceReport;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public ?string $status = null;
    public int $month;
    public int $year;

    public ?int $editingId = null;
    public ?string $notes = null;
    public $add_photo = null;
    public $replace_receipt = null;
    public ?int $units_serviced = null;

    public function mount(): void
    {
        $now = now();
        $this->month = (int) $now->month;
        $this->year  = (int) $now->year;
    }

    public function openEdit(int $id): void
    {
        $r = MaintenanceReport::where('technician_id', auth()->id())->findOrFail($id);
        $this->editingId = $r->id;
        $this->notes = $r->notes;
        $this->units_serviced = $r->units_serviced;
        $this->resetErrorBag();
        $this->resetValidation();
        $this->add_photo = null;
        $this->replace_receipt = null;
    }

    public function save(): void
    {
        $r = MaintenanceReport::where('technician_id', auth()->id())->findOrFail($this->editingId);

        $data = $this->validate([
            'notes'           => ['nullable','string'],
            'units_serviced'  => ['nullable','integer','min:0'],
            'add_photo'       => ['nullable','image','max:4096'],
            'replace_receipt' => ['nullable','file','max:6144'],
        ]);

        $r->notes = $data['notes'] ?? $r->notes;
        if (isset($data['units_serviced'])) $r->units_serviced = $data['units_serviced'];

        if ($this->add_photo) {
            $path = $this->add_photo->store('reports/extra', 'public');
            $photos = $r->photos ?: [];
            $photos[] = $path;
            $r->photos = $photos;
        }

        if ($this->replace_receipt) {
            $r->receipt_path = $this->replace_receipt->store('reports/receipts', 'public');
        }

        if (in_array($r->status, ['draft','revisi'])) {
            $r->status = 'submitted';
        }

        $r->save();

        $this->editingId = null;
        $this->reset(['notes','add_photo','replace_receipt']);
        session()->flash('ok','Laporan diperbarui.');
    }

    public function render()
    {
        $from = now()->setDate($this->year, $this->month, 1)->startOfMonth();
        $to   = (clone $from)->endOfMonth();

        $q = MaintenanceReport::with(['schedule.client','schedule.location'])
            ->where('technician_id', auth()->id())
            ->whereHas('schedule', fn($s)=> $s->whereBetween('scheduled_at', [$from, $to]))
            ->when($this->search, function($qq){
                $term = "%{$this->search}%";
                $qq->whereHas('schedule.client', fn($c)=>$c->where('company_name','like',$term));
            })
            ->when($this->status, fn($qq)=> $qq->where('status', $this->status))
            ->latest();

        $reports = $q->paginate(10);

        return view('livewire.teknisi.reports.index', compact('reports'))
            ->layout('layouts.app', ['title'=>'Laporan Saya','header'=>'Teknisi • Laporan']);
    }
}
