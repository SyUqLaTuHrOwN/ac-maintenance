<?php

namespace App\Livewire\Admin\Locations;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Location;
use App\Models\Client;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $clientFilter = null;
    public ?int $editingId = null;

    public ?int $client_id = null;
    public string $name = '';
    public ?string $address = null;

    protected function rules(): array
    {
        return [
            'client_id' => ['required','exists:clients,id'],
            'name' => ['required','string','max:255'],
            'address' => ['nullable','string','max:255'],
        ];
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
        $l = Location::findOrFail($id);
        $this->client_id = $l->client_id;
        $this->name = $l->name;
        $this->address = $l->address;
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId && $this->editingId > 0) {
            Location::findOrFail($this->editingId)->update($data);
            session()->flash('ok','Lokasi diperbarui.');
        } else {
            Location::create($data);
            session()->flash('ok','Lokasi dibuat.');
        }
        $this->resetForm();
        $this->editingId = null;
    }

    public function delete(int $id)
    {
        Location::findOrFail($id)->delete();
        session()->flash('ok','Lokasi dihapus.');
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->reset(['client_id','name','address']);
    }

    public function render()
    {
        $clients = Client::orderBy('company_name')->get(['id','company_name']);

        $q = Location::with('client')
            ->when($this->clientFilter, fn($qq)=>$qq->where('client_id',$this->clientFilter))
            ->when($this->search, fn($qq)=>$qq->where('name','like',"%{$this->search}%"))
            ->orderBy('name');

        $locations = $q->paginate(10);

        return view('livewire.admin.locations.index', compact('clients','locations'))
            ->layout('layouts.app', ['title'=>'Lokasi','header'=>'Master • Lokasi']);
    }
}
