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

    // Form
    public ?int $location_id = null;
    public ?string $brand = null;
    public ?string $model = null;
    public ?string $serial_number = null;
    public ?string $type = null;
    public ?int $capacity_btu = null;

    public ?int $units_count = null;
    public ?int $service_period_months = null;
    public ?int $services_per_year = null;
    public ?string $last_maintenance_date = null;


    protected function rules(): array
    {
        return [
            'location_id' => ['required','exists:locations,id'],
            'brand' => ['nullable','string','max:255'],
            'model' => ['nullable','string','max:255'],
            'serial_number' => ['nullable','string','max:255'],
            'type' => ['nullable','string','max:255'],
            'capacity_btu' => ['nullable','integer'],

            'units_count' => ['required','integer','min:1'],
            'service_period_months' => ['nullable','integer','min:1','max:12'],
            'services_per_year' => ['nullable','integer','min:1'],
            'last_maintenance_date' => ['nullable', 'date'],
        ];
    }

    public function updatedServicePeriodMonths($val)
    {
        if ($val > 0) {
            $this->services_per_year = (int) floor(12 / $val);
        }
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

        $this->units_count = $u->units_count;
        $this->service_period_months = $u->service_period_months;
        $this->services_per_year = $u->services_per_year;
        $this->last_maintenance_date = $u->last_maintenance_date?->format('Y-m-d');

    }

    public function save()
    {
        $data = $this->validate();

        if ($data['service_period_months'] && !$data['services_per_year']) {
            $data['services_per_year'] = (int) floor(12 / $data['service_period_months']);
        }

        if ($this->editingId > 0) {
            UnitAc::findOrFail($this->editingId)->update($data);
            session()->flash('ok','Unit diperbarui.');
        } else {
            UnitAc::create($data);
            session()->flash('ok','Unit dibuat.');
        }

        $this->resetForm();
        $this->editingId = null;
        $this->resetPage();
    }

    public function delete(int $id)
    {
        UnitAc::findOrFail($id)->delete();
        session()->flash('ok','Unit dihapus.');
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'location_id','brand','model','serial_number','type',
            'capacity_btu','units_count','service_period_months',
            'services_per_year','last_maintenance_date',

        ]);
    }

    public function render()
    {
        $clients = Client::orderBy('company_name')->get();
        $locations = Location::when($this->clientFilter, fn($q)=>$q->where('client_id',$this->clientFilter))
                             ->orderBy('name')->get();

        $units = UnitAc::with('location.client')
    ->when($this->locationFilter, fn($q)=>$q->where('location_id',$this->locationFilter))
    ->when($this->clientFilter, function($q){
        $q->whereHas('location.client', fn($qq)=>
            $qq->where('id', $this->clientFilter)
        );
    })
    ->when($this->search, function($q){
        $s = "%{$this->search}%";

        $q->where(function($w) use ($s){
            $w->where('brand','like',$s)
              ->orWhere('model','like',$s)
              ->orWhere('serial_number','like',$s)
              ->orWhere('type','like',$s);
        });
    })
    ->orderBy('id','desc')
    ->paginate(10);

        return view('livewire.admin.units.index', compact('clients','locations','units'))
            ->layout('layouts.app', [
                'title'=>'Unit AC',
                'header'=>'Master • Unit AC'
            ]);
    }
}
