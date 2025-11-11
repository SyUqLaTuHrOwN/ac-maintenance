<div class="space-y-5">

  {{-- Filter bar --}}
  <div class="flex items-center gap-3">
    <input type="text" wire:model.live.debounce.400ms="search"
           class="border rounded-lg px-3 py-2 w-72" placeholder="Cari client/lokasi/catatan...">

    <select wire:model.live="status" class="border rounded-lg px-3 py-2">
      <option value="menunggu">Menunggu</option>
      <option value="terjadwal">Terjadwal</option>
      <option value="ditolak">Ditolak</option>
      <option value="*">Semua</option>
    </select>
  </div>

  {{-- List --}}
  <div class="bg-white border rounded-xl overflow-hidden">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 text-gray-600">
        <tr>
          <th class="p-3 text-left">Dibuat</th>
          <th class="p-3 text-left">Client</th>
          <th class="p-3 text-left">Lokasi</th>
          <th class="p-3 text-left">Unit</th>
          <th class="p-3 text-left">Preferensi</th>
          <th class="p-3 text-left">Catatan</th>
          <th class="p-3 text-left">Status</th>
          <th class="p-3 text-left">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse ($requests as $r)
        <tr class="border-t">
          <td class="p-3 whitespace-nowrap">{{ $r->created_at->format('d M Y H:i') }}</td>
          <td class="p-3">{{ $r->client?->company ?? '-' }}</td>
          <td class="p-3">{{ $r->location?->name ?? '-' }}</td>
          <td class="p-3">
            @if($r->relationLoaded('units') && $r->units->isNotEmpty())
              <div class="space-y-1">
                @foreach($r->units as $u)
                  <div class="text-gray-700">{{ $u->brand }} {{ $u->model }} (SN {{ $u->serial_number }})</div>
                @endforeach
              </div>
            @else
              <span class="text-gray-400">—</span>
            @endif
          </td>
          <td class="p-3">{{ $r->preferred_at ? \Carbon\Carbon::parse($r->preferred_at)->format('d M Y H:i') : '-' }}</td>
          <td class="p-3 max-w-[260px]">
            <div class="line-clamp-2">{{ $r->notes ?: '-' }}</div>
          </td>
          <td class="p-3">
            <span class="px-2 py-1 rounded bg-gray-100">{{ $r->status }}</span>
          </td>
          <td class="p-3">
            <div class="flex items-center gap-2">
              @if($r->status === 'menunggu')
                <button wire:click="schedule({{ $r->id }})"
                        class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                  Buat Jadwal
                </button>
                <button wire:click="reject({{ $r->id }})"
                        class="px-3 py-1.5 rounded-lg border hover:bg-gray-50">
                  Tolak
                </button>
              @endif
              <button wire:click="delete({{ $r->id }})"
                      class="px-3 py-1.5 rounded-lg border hover:bg-gray-50">
                Hapus
              </button>
            </div>
          </td>
        </tr>
      @empty
        <tr><td class="p-6 text-center text-gray-500" colspan="8">Belum ada data.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div>
    {{ $requests->links() }}
  </div>
</div>
