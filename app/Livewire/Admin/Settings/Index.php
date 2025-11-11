<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;

class Index extends Component
{
    public string $company = 'CoolCare AC';
    public string $timezone = 'Asia/Jakarta';

    public function save()
    {
        // simpan ke .env / table settings (nanti)
        session()->flash('ok','Pengaturan disimpan (demo).');
    }

    public function render()
    {
        return view('livewire.admin.settings.index')
            ->layout('layouts.app', ['title'=>'Pengaturan','header'=>'Sistem • Pengaturan']);
    }
}
