<?php

namespace App\Livewire\Admin\Technicians;

use Livewire\Component;
use App\Models\User;
use App\Support\Role;

class Index extends Component
{
    public function toggle(int $id)
    {
        $u = User::findOrFail($id);
        // contoh sederhana: aktif/nonaktif via column 'phone' kosong? (atau tambah kolom 'active' di users)
        // Di sini kita pakai dummy toggle on 'remember_token' untuk contoh:
        $u->remember_token = $u->remember_token ? null : 'active';
        $u->save();
    }

    public function render()
    {
        $techs = User::where('role', Role::TEKNISI)->orderBy('name')->get();
        return view('livewire.admin.technicians.index', compact('techs'))
            ->layout('layouts.app', ['title'=>'Teknisi','header'=>'Operasional • Teknisi']);
    }
}
