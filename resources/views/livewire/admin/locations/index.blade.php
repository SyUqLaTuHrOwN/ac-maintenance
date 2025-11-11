<div>
  <div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-2">
      <select class="rounded-xl border-gray-300" wire:model.live="clientFilter">
        <option value="">Semua Klien</option>
        @foreach($clients as $c)
          <option value="{{ $c->id }}">{{ $c->company_name }}</option>
        @endforeach
      </select>
      <input type="text" wire:model.live.debounce.400ms="search" class="rounded-xl border-gray-300" placeholder="Cari lokasi...">
      @if(session('ok')) <span class="text-sm text-emerald-700">{{ session('ok') }}</span>@endif
    </div>
    <button wire:click="createNew" class="rounded-xl bg-indigo-600 text-white px-4 py-2">+ Lokasi Baru</button>
  </div>

  <div class="bg-white border rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr><th class="p-3 text-left">Lokasi</th><th class="p-3 text-left">Klien</th><th class="p-3 text-left">Alamat</th><th class="p-3 w-28">Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($locations as $l)
          <tr class="border-t">
            <td class="p-3">{{ $l->name }}</td>
            <td class="p-3">{{ $l->client?->company_name }}</td>
            <td class="p-3">{{ $l->address }}</td>
            <td class="p-3">
              <div class="flex gap-2">
                <button class="px-3 py-1 rounded-lg border" wire:click="edit({{ $l->id }})">Edit</button>
                <button class="px-3 py-1 rounded-lg border text-red-600" onclick="return confirm('Hapus lokasi?')"
                        wire:click="delete({{ $l->id }})">Hapus</button>
              </div>
            </td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="4">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-3">{{ $locations->links() }}</div>

  @if(!is_null($editingId))
    <div class="fixed inset-0 bg-black/30 z-40" wire:click="$set('editingId', null)"></div>
    <div class="fixed right-0 top-0 bottom-0 w-full max-w-lg bg-white z-50 border-l overflow-y-auto">
      <div class="p-6 border-b flex items-center justify-between">
        <div class="font-semibold">{{ $editingId ? 'Edit Lokasi' : 'Lokasi Baru' }}</div>
        <button class="text-gray-500" wire:click="$set('editingId', null)">✕</button>
      </div>
      <form wire:submit.prevent="save" class="p-6 grid gap-4">
        <div>
          <label class="text-sm">Klien</label>
          <select class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="client_id" required>
            <option value="">— pilih klien —</option>
            @foreach($clients as $c)
              <option value="{{ $c->id }}">{{ $c->company_name }}</option>
            @endforeach
          </select>
          @error('client_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="text-sm">Nama Lokasi</label>
          <input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="name" required>
          @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="text-sm">Alamat</label>
          <input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="address">
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" class="px-4 py-2 rounded-xl border" wire:click="$set('editingId', null)">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Simpan</button>
        </div>
      </form>
    </div>
  @endif
</div>
