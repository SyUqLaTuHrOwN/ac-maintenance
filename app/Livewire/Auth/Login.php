<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function submit()
    {
        $creds = ['email' => $this->email, 'password' => $this->password];

        if (! Auth::attempt($creds, $this->remember)) {
            $this->addError('email', 'Email atau password salah.');
            return;
        }

        request()->session()->regenerate();

        // ← PAKSAKAN KE REDIRECT SELECTOR (role-based)
        return redirect()->route('redirect');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest', [
            'title' => 'Login',
        ]);
    }
}
