<div class="max-w-3xl">
  <div class="bg-white border rounded-2xl p-6">
    <div class="flex items-center justify-between mb-4">
      <div class="font-semibold text-lg">Biodata</div>
      @if(session('ok_profile'))
        <span class="text-sm text-emerald-700">{{ session('ok_profile') }}</span>
      @endif
    </div>

    @if(!$editing)
      {{-- tampilan hanya lihat --}}
      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
        <div>
          <dt class="text-gray-500">Nama</dt>
          <dd class="mt-1 font-medium">{{ auth()->user()->name }}</dd>
        </div>
        <div>
          <dt class="text-gray-500">Email</dt>
          <dd class="mt-1 font-medium">{{ auth()->user()->email }}</dd>
        </div>
        <div>
          <dt class="text-gray-500">Telepon</dt>
          <dd class="mt-1 font-medium">{{ auth()->user()->phone ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-gray-500">Role</dt>
          <dd class="mt-1">
            <span class="px-2 py-1 rounded bg-indigo-50 text-indigo-700 text-xs">teknisi</span>
          </dd>
        </div>
      </dl>

      <div class="mt-6">
        <button class="px-4 py-2 rounded-xl border hover:bg-gray-50" wire:click="startEdit">
          Edit Nama/Telepon
        </button>
      </div>
    @else
      {{-- mode edit: hanya nama & telepon --}}
      <form wire:submit.prevent="saveProfile" class="grid gap-4">
        <div>
          <label class="text-sm text-gray-600">Nama</label>
          <input type="text" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="name">
          @error('name') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
          <label class="text-sm text-gray-600">Telepon</label>
          <input type="text" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="phone">
          @error('phone') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        <div class="flex gap-2">
          <button type="button" class="px-4 py-2 rounded-xl border" wire:click="cancelEdit">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Simpan</button>
        </div>
      </form>
    @endif

    <hr class="my-6">

    <div class="text-sm text-gray-500">
      Perubahan password hanya dapat dilakukan oleh <span class="font-medium text-gray-700">Admin</span>.
      Jika Anda perlu reset password, hubungi admin.
    </div>
  </div>
</div>
