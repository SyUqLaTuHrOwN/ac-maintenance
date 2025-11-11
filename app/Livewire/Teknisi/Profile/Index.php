<?php

namespace App\Livewire\Teknisi\Profile;

use Livewire\Component;

class Index extends Component
{
    public string $name = '';
    public ?string $phone = null;

    public bool $editing = false;

    public function mount(): void
    {
        $u = auth()->user();
        $this->name  = $u->name ?? '';
        $this->phone = $u->phone ?? null;
    }

    public function startEdit(): void
    {
        $this->editing = true;
    }

    public function cancelEdit(): void
    {
        $u = auth()->user();
        $this->name  = $u->name ?? '';
        $this->phone = $u->phone ?? null;
        $this->editing = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function saveProfile(): void
    {
        $this->validate([
            'name'  => ['required','string','max:120'],
            'phone' => ['nullable','string','max:50'],
        ]);

        auth()->user()->update([
            'name'  => $this->name,
            'phone' => $this->phone,
        ]);

        $this->editing = false;
        session()->flash('ok_profile','Profil diperbarui.');
    }

    public function render()
    {
        return view('livewire.teknisi.profile.index')
            ->layout('layouts.app', ['title'=>'Profil','header'=>'Teknisi • Profil']);
    }
}
