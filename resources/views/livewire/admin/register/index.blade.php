<div>
  @if(session('ok'))<div class="mb-3 text-sm text-emerald-700">{{ session('ok') }}</div>@endif
  <form wire:submit.prevent="save" class="max-w-xl bg-white border rounded-2xl p-6 grid gap-4">
    <div>
      <label class="text-sm">Nama</label>
      <input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="name" required>
      @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="text-sm">Email</label>
      <input type="email" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="email" required>
      @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="grid grid-cols-2 gap-3">
      <div>
        <label class="text-sm">Password</label>
        <input type="password" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="password" required>
        @error('password')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="text-sm">Role</label>
        <select class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="role">
          <option value="client">client</option>
          <option value="teknisi">teknisi</option>
          <option value="admin">admin</option>
        </select>
      </div>
    </div>
    <div class="flex justify-end">
      <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Buat Akun</button>
    </div>
  </form>

  <p class="text-sm text-gray-500 mt-4">
    Catatan: jika role = client, sistem otomatis buat entri Client dengan nama perusahaan awal dari nama user (bisa diedit di menu Klien).
  </p>
</div>
