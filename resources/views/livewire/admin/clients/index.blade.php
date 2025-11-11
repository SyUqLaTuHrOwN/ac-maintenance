<div>
  <div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-2">
      <input type="text" wire:model.live.debounce.400ms="search"
             class="rounded-xl border-gray-300" placeholder="Cari nama/email klien...">
      @if (session('ok'))
        <span class="text-sm text-emerald-700">{{ session('ok') }}</span>
      @endif
    </div>
    <button wire:click="createNew"
            class="rounded-xl bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-500">+ Klien Baru</button>
  </div>

  <div class="bg-white border rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-3 text-left">Perusahaan</th>
          <th class="p-3 text-left">PIC</th>
          <th class="p-3 text-left">Kontak</th>
          <th class="p-3 text-left">User Terkait</th>
          <th class="p-3 text-left w-28">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($clients as $c)
          <tr class="border-t">
            <td class="p-3">
              <div class="font-medium">{{ $c->company_name }}</div>
              <div class="text-gray-500 text-xs">{{ $c->address }}</div>
            </td>
            <td class="p-3">
              {{ $c->pic_name ?? '-' }}
              <div class="text-gray-500 text-xs">{{ $c->pic_phone }}</div>
            </td>
            <td class="p-3">
              {{ $c->email ?? '-' }} <div class="text-gray-500 text-xs">{{ $c->phone }}</div>
            </td>
            <td class="p-3">
              {{ $c->user?->name ?? '-' }}
              <div class="text-gray-500 text-xs">{{ $c->user?->email }}</div>
            </td>
            <td class="p-3">
              <div class="flex gap-2">
                <button wire:click="edit({{ $c->id }})" class="px-3 py-1 rounded-lg border hover:bg-gray-50">Edit</button>
                <button wire:click="delete({{ $c->id }})" onclick="return confirm('Hapus klien?')"
                        class="px-3 py-1 rounded-lg border text-red-600 hover:bg-red-50">Hapus</button>
              </div>
            </td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="5">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $clients->links() }}</div>

  {{-- Drawer/Form --}}
  @if(!is_null($editingId))
    <div class="fixed inset-0 bg-black/30 z-40" wire:click="$set('editingId', null)"></div>
    <div class="fixed right-0 top-0 bottom-0 w-full max-w-lg bg-white z-50 border-l overflow-y-auto">
      <div class="p-6 border-b flex items-center justify-between">
        <div class="font-semibold">{{ $editingId ? 'Edit Klien' : 'Klien Baru' }}</div>
        <button class="text-gray-500" wire:click="$set('editingId', null)">✕</button>
      </div>

      <form wire:submit.prevent="save" class="p-6 grid gap-4">
        <div>
          <label class="text-sm">Nama Perusahaan</label>
          <input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="company_name" required>
          @error('company_name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="text-sm">Alamat</label>
          <input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="address">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-sm">Email</label>
            <input type="email" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="email">
            @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="text-sm">Telepon</label>
            <input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="phone">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-sm">PIC</label>
            <input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="pic_name">
          </div>
          <div>
            <label class="text-sm">HP PIC</label>
            <input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="pic_phone">
          </div>
        </div>

        <div>
  <label class="text-sm">User Terkait (Client)</label>
  <select class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="user_id">
    <option value="">— pilih user client —</option>
    @foreach($clientUsers as $u)
      <option value="{{ $u->id }}">{{ $u->name }} — {{ $u->email }}</option>
    @endforeach
  </select>
  <p class="text-xs text-gray-500 mt-1">Daftar hanya berisi akun role=client yang belum terhubung.</p>
</div>


        <div class="flex justify-end gap-2">
          <button type="button" wire:click="$set('editingId', null)" class="px-4 py-2 rounded-xl border">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Simpan</button>
        </div>
      </form>
    </div>
  @endif
</div>
