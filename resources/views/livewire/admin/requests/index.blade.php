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
          <th class="p-3 text-left">Unit Diminta</th>
          <th class="p-3 text-left">Preferensi</th>
          <th class="p-3 text-left">Catatan</th>
          <th class="p-3 text-left">Status</th>
          <th class="p-3 text-left">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse ($requests as $r)
        @php
          $totalRequested = $r->units->sum('pivot.requested_units');
        @endphp
        <tr class="border-t align-top">
          <td class="p-3 whitespace-nowrap">{{ $r->created_at->format('d M Y H:i') }}</td>
          <td class="p-3">{{ $r->client?->company_name ?? '-' }}</td>
          <td class="p-3">{{ $r->location?->name ?? '-' }}</td>

          {{-- UNIT DIMINTA --}}
          <td class="p-3">
            @if($r->units->isNotEmpty())
              <div class="font-medium">{{ $totalRequested }} unit</div>
              <div class="text-[11px] text-gray-500 mt-1 max-w-[220px]">
                @foreach($r->units as $u)
                  • {{ $u->brand }} {{ $u->model }}
                  ({{ $u->pivot->requested_units }} unit)
                  <br>
                @endforeach
              </div>
            @else
              <span class="text-gray-400">— tidak spesifik, cek catatan —</span>
            @endif
          </td>

          {{-- PREFERENSI --}}
          <td class="p-3">
            {{ $r->preferred_at ? \Carbon\Carbon::parse($r->preferred_at)->format('d M Y H:i') : '-' }}
          </td>

          {{-- CATATAN --}}
          <td class="p-3 max-w-[260px]">
            <div class="line-clamp-2">{{ $r->notes ?: '-' }}</div>
          </td>

          {{-- STATUS --}}
          <td class="p-3">
            <span class="px-2 py-1 rounded bg-gray-100">{{ $r->status }}</span>
          </td>

          {{-- AKSI --}}
          <td class="p-3">
            <div class="flex flex-col gap-2">
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
                      class="px-3 py-1.5 rounded-lg border hover:bg-gray-50 text-red-600">
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
