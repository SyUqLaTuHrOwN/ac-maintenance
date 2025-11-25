<?php

namespace App\Livewire\Teknisi\Profile;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $user = auth()->user()->load('technicianProfile');

        return view('livewire.teknisi.profile.index', [
            'user'  => $user,
            'profile' => $user->technicianProfile,
        ])->layout('layouts.app', [
            'title'  => 'Profil Tim Teknisi',
            'header' => 'Teknisi • Profil Tim',
        ]);
    }
}
