<div>
  <div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-2">
      <select class="rounded-xl border-gray-300" wire:model.live="clientFilter">
        <option value="">Semua Klien</option>
        @foreach($clients as $c) <option value="{{ $c->id }}">{{ $c->company_name }}</option> @endforeach
      </select>
      <select class="rounded-xl border-gray-300" wire:model.live="locationFilter">
        <option value="">Semua Lokasi</option>
        @foreach($locations as $l) <option value="{{ $l->id }}">{{ $l->name }}</option> @endforeach
      </select>
      <input type="text" wire:model.live.debounce.400ms="search" class="rounded-xl border-gray-300" placeholder="Cari brand/model...">
      @if(session('ok')) <span class="text-sm text-emerald-700">{{ session('ok') }}</span>@endif
    </div>
    <button wire:click="createNew" class="rounded-xl bg-indigo-600 text-white px-4 py-2">+ Unit Baru</button>
  </div>

  <div class="bg-white border rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-3 text-left">Unit</th>
          <th class="p-3 text-left">Lokasi</th>
          <th class="p-3 text-left">Kapasitas</th>
          <th class="p-3 text-left">Pemasangan</th> 
    <th class="p-3 text-left">Last Maint.</th>
          <th class="p-3 text-left">Status</th>
          <th class="p-3 w-28">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($units as $u)
          <tr class="border-t">
            <td class="p-3">{{ $u->brand }} {{ $u->model }} <div class="text-xs text-gray-500">SN: {{ $u->serial_number ?? '-' }}</div></td>
            <td class="p-3">{{ $u->location?->name }} — <span class="text-xs text-gray-500">{{ $u->location?->client?->company_name }}</span></td>
            <td class="p-3">{{ $u->capacity_btu ? number_format($u->capacity_btu).' BTU' : '-' }}</td>
                <td class="p-3">{{ optional($u->install_date)->format('d M Y') ?? '-' }}</td>
    <td class="p-3">{{ optional($u->last_maintenance_date)->format('d M Y') ?? '-' }}</td>
            <td class="p-3"><span class="px-2 py-1 rounded bg-gray-100">{{ $u->status }}</span></td>
            <td class="p-3">
              <div class="flex gap-2">
                <button class="px-3 py-1 rounded-lg border" wire:click="edit({{ $u->id }})">Edit</button>
                <button class="px-3 py-1 rounded-lg border text-red-600" onclick="return confirm('Hapus unit?')"
                        wire:click="delete({{ $u->id }})">Hapus</button>
              </div>
            </td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="5">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-3">{{ $units->links() }}</div>

  @if(!is_null($editingId))
    <div class="fixed inset-0 bg-black/30 z-40" wire:click="$set('editingId', null)"></div>
    <div class="fixed right-0 top-0 bottom-0 w-full max-w-lg bg-white z-50 border-l overflow-y-auto">
      <div class="p-6 border-b flex items-center justify-between">
        <div class="font-semibold">{{ $editingId ? 'Edit Unit' : 'Unit Baru' }}</div>
        <button class="text-gray-500" wire:click="$set('editingId', null)">✕</button>
      </div>
      <form wire:submit.prevent="save" class="p-6 grid gap-4">
        <div>
          <label class="text-sm">Lokasi</label>
          <select class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="location_id" required>
            <option value="">— pilih lokasi —</option>
            @foreach($locations as $l)
              <option value="{{ $l->id }}">{{ $l->name }}</option>
            @endforeach
          </select>
          @error('location_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-sm">Brand</label><input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="brand"></div>
          <div><label class="text-sm">Model</label><input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="model"></div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-sm">Serial</label><input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="serial_number"></div>
          <div><label class="text-sm">Tipe</label><input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="type"></div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-sm">Kapasitas (BTU)</label><input type="number" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="capacity_btu"></div>
          <div><label class="text-sm">Tanggal Pasang</label><input type="date" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="install_date"></div>
        </div>
        <div class="grid grid-cols-2 gap-3">
  <div>
    <label class="text-sm">Last Maintenance</label>
    <input type="date" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="last_maintenance_date">
  </div>
        <div>
          <label class="text-sm">Status</label>
          <select class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="status">
            <option value="aktif">aktif</option>
            <option value="nonaktif">nonaktif</option>
            <option value="rusak">rusak</option>
          </select>
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" class="px-4 py-2 rounded-xl border" wire:click="$set('editingId', null)">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Simpan</button>
        </div>
      </form>
    </div>
  @endif
</div>
