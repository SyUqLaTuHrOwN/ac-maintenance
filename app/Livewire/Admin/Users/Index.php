<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Support\Role;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $roleFilter = null;

    // Modal ubah password
    public ?int $editingUserId = null;
    public string $new_password = '';
    public string $new_password_confirmation = '';

    // Flash menampilkan password acak sekali (opsional; untuk dev)
    public ?string $just_reset_password = null;

    // --- Guard sederhana agar hanya admin ---
    protected function ensureAdmin(): void
    {
        $u = auth()->user();
        if (!$u || $u->role !== Role::ADMIN) {
            abort(403, 'Only admin can do this.');
        }
    }

    public function updatingSearch()    { $this->resetPage(); }
    public function updatingRoleFilter(){ $this->resetPage(); }

    public function openChangePassword(int $userId): void
    {
        $this->ensureAdmin();
        $user = User::findOrFail($userId);

        $this->editingUserId = $user->id;
        $this->new_password = '';
        $this->new_password_confirmation = '';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cancelChange(): void
    {
        $this->editingUserId = null;
        $this->reset(['new_password','new_password_confirmation']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function saveChange(): void
    {
        $this->ensureAdmin();

        $this->validate([
            'new_password'              => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
            'new_password_confirmation' => ['required'],
        ], [], [
            'new_password' => 'Password baru',
        ]);

        $user = User::findOrFail($this->editingUserId);

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->cancelChange();
        session()->flash('ok', 'Password pengguna berhasil diganti.');
    }

    public function resetRandom(int $userId): void
    {
        $this->ensureAdmin();

        $user = User::findOrFail($userId);

        // Laravel 10+: Str::password(); kalau tidak ada, ganti ke Str::random(16)
        $plain = method_exists(Str::class, 'password') ? Str::password(12) : Str::random(16);

        $user->update([
            'password' => Hash::make($plain),
        ]);

        // TAMPILKAN SEKALI (untuk dev). Produksi: kirim via email/notifikasi.
        $this->just_reset_password = "Password baru {$user->email}: {$plain}";
        session()->flash('ok', 'Password direset (acak).');
    }

    public function render()
    {
        $q = User::query()
            ->when($this->search, function ($qq) {
                $term = "%{$this->search}%";
                $qq->where('name', 'like', $term)
                   ->orWhere('email', 'like', $term);
            })
            ->when($this->roleFilter, fn($qq) => $qq->where('role', $this->roleFilter))
            ->orderBy('name');

        $users = $q->paginate(10);

        return view('livewire.admin.users.index', compact('users'))
            ->layout('layouts.app', ['title'=>'Pengguna','header'=>'Sistem • Pengguna']);
    }
}
