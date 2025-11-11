<div>
  <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <div class="flex items-center gap-2">
      <input type="text" class="rounded-xl border-gray-300" placeholder="Cari nama/email..."
             wire:model.debounce.400ms="search">
      <select class="rounded-xl border-gray-300" wire:model="roleFilter">
        <option value="">Semua role</option>
        <option value="admin">admin</option>
        <option value="teknisi">teknisi</option>
        <option value="client">client</option>
      </select>
    </div>

    <div class="text-sm">
      @if(session('ok'))
        <span class="text-emerald-700">{{ session('ok') }}</span>
      @endif
    </div>
  </div>

  @if($just_reset_password)
    <div class="mb-3 p-3 rounded-xl bg-amber-50 text-amber-800 text-sm">
      {{ $just_reset_password }}
    </div>
  @endif

  <div class="bg-white border rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-3 text-left">Nama</th>
          <th class="p-3 text-left">Email</th>
          <th class="p-3 text-left">Role</th>
          <th class="p-3 w-72">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
          <tr class="border-t">
            <td class="p-3">{{ $u->name }}</td>
            <td class="p-3">{{ $u->email }}</td>
            <td class="p-3">
              <span class="px-2 py-1 rounded bg-gray-100">{{ $u->role }}</span>
            </td>
            <td class="p-3">
              <div class="flex flex-wrap gap-2">
                {{-- Ganti password (manual) --}}
                <button class="px-3 py-1 rounded-lg border"
                        wire:click="openChangePassword({{ $u->id }})">Ganti Password</button>

                {{-- Reset acak --}}
                <button class="px-3 py-1 rounded-lg border text-red-700"
                        onclick="confirm('Reset password acak untuk user ini?') && $wire.resetRandom({{ $u->id }})">
                  Reset (Acak)
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="4">Tidak ada pengguna.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $users->links() }}</div>

  {{-- Modal Ganti Password --}}
  @if($editingUserId)
    <div class="fixed inset-0 bg-black/40 z-40" wire:click="cancelChange"></div>
    <div class="fixed inset-x-0 top-16 mx-auto max-w-md bg-white rounded-2xl z-50 border">
      <div class="p-4 border-b font-semibold">Ganti Password Pengguna</div>
      <form wire:submit.prevent="saveChange" class="p-4 space-y-3">
        <div>
          <label class="text-sm">Password Baru</label>
          <input type="password" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="new_password">
          @error('new_password') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
          <label class="text-sm">Konfirmasi Password Baru</label>
          <input type="password" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="new_password_confirmation">
          @error('new_password_confirmation') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" class="px-4 py-2 rounded-xl border" wire:click="cancelChange">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Simpan</button>
        </div>
      </form>
      <div class="p-4 pt-0 text-xs text-gray-500">
        Minimal 8 karakter, campur huruf & angka.
      </div>
    </div>
  @endif
</div>
