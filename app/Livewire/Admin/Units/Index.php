<?php

namespace App\Livewire\Admin\Units;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UnitAc;
use App\Models\Location;
use App\Models\Client;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $clientFilter = null;
    public ?int $locationFilter = null;

    public ?int $editingId = null;
    public ?int $location_id = null;
    public ?string $brand = null;
    public ?string $model = null;
    public ?string $serial_number = null;
    public ?string $type = null;
    public ?int $capacity_btu = null;
    public ?string $install_date = null;
public ?string $last_maintenance_date = null;

    public string $status = 'aktif';
    

   protected function rules(): array
{
    return [
        'location_id' => ['required','exists:locations,id'],
        'brand' => ['nullable','string','max:255'],
        'model' => ['nullable','string','max:255'],
        'serial_number' => ['nullable','string','max:255'],
        'type' => ['nullable','string','max:255'],
        'capacity_btu' => ['nullable','integer'],
        'install_date' => ['nullable','date'],
        'last_maintenance_date' => ['nullable','date'], // <— baru
        'status' => ['required','string','max:50'],
    ];
}


    public function updatedClientFilter()
    {
        $this->locationFilter = null;
    }

    public function createNew()
    {
        $this->resetForm();
        $this->editingId = 0;
    }

    public function edit(int $id)
    {
        $this->resetErrorBag();
        $this->editingId = $id;
        $u = UnitAc::findOrFail($id);
        $this->location_id = $u->location_id;
        $this->brand = $u->brand;
        $this->model = $u->model;
        $this->serial_number = $u->serial_number;
        $this->type = $u->type;
        $this->capacity_btu = $u->capacity_btu;
        $this->install_date = $u->install_date?->format('Y-m-d');
        $this->status = $u->status;
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId && $this->editingId > 0) {
            UnitAc::findOrFail($this->editingId)->update($data);
            session()->flash('ok','Unit diperbarui.');
        } else {
            UnitAc::create($data);
            session()->flash('ok','Unit dibuat.');
        }
        $this->resetForm();
        $this->editingId = null;
    }

    public function delete(int $id)
    {
        UnitAc::findOrFail($id)->delete();
        session()->flash('ok','Unit dihapus.');
        $this->resetPage();
    }

    private function resetForm(): void
{
    $this->reset([
        'location_id','brand','model','serial_number','type',
        'capacity_btu','install_date','last_maintenance_date','status'
    ]);
    $this->status = 'aktif';
}


    public function render()
    {
        $clients = Client::orderBy('company_name')->get(['id','company_name']);
        $locations = Location::when($this->clientFilter, fn($q)=>$q->where('client_id',$this->clientFilter))
                             ->orderBy('name')->get(['id','name','client_id']);

        $q = UnitAc::with('location.client')
            ->when($this->locationFilter, fn($qq)=>$qq->where('location_id',$this->locationFilter))
            ->when($this->search, fn($qq)=>$qq->where('model','like',"%{$this->search}%")->orWhere('brand','like',"%{$this->search}%"))
            ->orderBy('id','desc');

        $units = $q->paginate(10);

        return view('livewire.admin.units.index', compact('clients','locations','units'))
            ->layout('layouts.app', ['title'=>'Unit AC','header'=>'Master • Unit AC']);
    }
}
