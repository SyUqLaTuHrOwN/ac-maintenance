<?php

namespace App\Livewire\Admin\Register;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Support\Role;

class Index extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = Role::CLIENT; // default client

    public function save()
    {
        $this->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6'],
            'role'     => ['required','in:admin,teknisi,client'],
        ]);

        User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'role'     => $this->role,
        ]);

        $this->reset(['name','email','password','role']);
        $this->role = Role::CLIENT;

        session()->flash('ok','Akun berhasil dibuat. Hubungkan ke “Klien” nanti melalui field “User Terkait”.');
    }

    public function render()
    {
        return view('livewire.admin.register.index')
            ->layout('layouts.app', ['title'=>'Register Akun','header'=>'Sistem • Register Akun']);
    }
}
