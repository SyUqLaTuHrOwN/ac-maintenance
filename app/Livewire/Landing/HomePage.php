<?php

namespace App\Livewire\Landing;

use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        return view('livewire.landing.home-page')->layout('layouts.guest');
    }
}
