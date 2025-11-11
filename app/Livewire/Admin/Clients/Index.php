<?php

namespace App\Livewire\Admin\Clients;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;
use App\Models\User;
use App\Support\Role;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;

    // form fields
    public string $company_name = '';
    public ?string $address = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $pic_name = null;
    public ?string $pic_phone = null;
    public ?int $user_id = null;

    protected function rules(): array
    {
        return [
            'company_name' => ['required','string','max:255'],
            'address'      => ['nullable','string','max:255'],
            'email'        => ['nullable','email','max:255'],
            'phone'        => ['nullable','string','max:50'],
            'pic_name'     => ['nullable','string','max:255'],
            'pic_phone'    => ['nullable','string','max:50'],
            'user_id'      => ['nullable','exists:users,id'],
        ];
    }

    public function createNew()
    {
        $this->resetForm();
        $this->editingId = 0; // mode create
    }

    public function edit(int $id)
    {
        $this->resetErrorBag();
        $this->editingId = $id;
        $c = Client::findOrFail($id);
        $this->company_name = (string)$c->company_name;
        $this->address = $c->address;
        $this->email = $c->email;
        $this->phone = $c->phone;
        $this->pic_name = $c->pic_name;
        $this->pic_phone = $c->pic_phone;
        $this->user_id = $c->user_id;
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->editingId && $this->editingId > 0) {
            Client::findOrFail($this->editingId)->update($data);
            session()->flash('ok', 'Klien diperbarui.');
        } else {
            Client::create($data);
            session()->flash('ok', 'Klien dibuat.');
        }

        $this->resetForm();
        $this->editingId = null;
    }

    public function delete(int $id)
    {
        Client::findOrFail($id)->delete();
        session()->flash('ok', 'Klien dihapus.');
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->reset([
            'company_name','address','email','phone','pic_name','pic_phone','user_id'
        ]);
    }

   public function render()
{
    $q = \App\Models\Client::query()
        ->when($this->search, fn($query) =>
            $query->where('company_name','like',"%{$this->search}%")
                  ->orWhere('email','like',"%{$this->search}%")
        )
        ->orderBy('company_name');

    $clients = $q->paginate(10);

    // Ambil user client yang belum terhubung ke client manapun,
    // plus sertakan user yang sedang dipakai saat edit (agar tidak hilang dari opsi).
    $clientUsers = \App\Models\User::where('role', \App\Support\Role::CLIENT)
        ->where(function($q){
            $q->whereDoesntHave('client');             // belum dipakai
            if ($this->user_id) {
                $q->orWhere('id', $this->user_id);     // user yang sedang terpilih (mode edit)
            }
        })
        ->orderBy('name')
        ->get(['id','name','email']);

    return view('livewire.admin.clients.index', compact('clients','clientUsers'))
        ->layout('layouts.app', [
            'title'  => 'Klien',
            'header' => 'Master • Klien',
        ]);
}
}