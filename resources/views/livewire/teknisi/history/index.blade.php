<div>
  <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <div class="flex flex-wrap items-center gap-2">
      <select class="rounded-xl border-gray-300" wire:model="month">
        @for($m=1;$m<=12;$m++)
          <option value="{{ $m }}">{{ str_pad($m,2,'0',STR_PAD_LEFT) }}</option>
        @endfor
      </select>
      <select class="rounded-xl border-gray-300" wire:model="year">
        @for($y=now()->year-2; $y<=now()->year+2; $y++)
          <option value="{{ $y }}">{{ $y }}</option>
        @endfor
      </select>
      <select class="rounded-xl border-gray-300" wire:model="status">
        <option value="">Semua status</option>
        <option value="selesai_servis">selesai_servis</option>
        <option value="selesai">selesai</option>
        <option value="dibatalkan_oleh_klien">dibatalkan_oleh_klien</option>
      </select>
      <input type="text" class="rounded-xl border-gray-300" placeholder="Cari klien/lokasi..."
             wire:model.debounce.400ms="search">
    </div>
  </div>

  <div class="bg-white border rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-3 text-left">Tanggal</th>
          <th class="p-3 text-left">Klien/Lokasi</th>
          <th class="p-3 text-left">Unit</th>
          <th class="p-3 text-left">Status</th>
          <th class="p-3 text-left">Berkas</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $t)
          <tr class="border-t">
            <td class="p-3">{{ $t->scheduled_at->format('d M Y H:i') }}</td>
            <td class="p-3">{{ $t->client?->company_name }} — {{ $t->location?->name }}</td>
            <td class="p-3">
              @php
                $labels = $t->units->map(fn($u)=> $u->serial_number ? 'SN '.$u->serial_number : trim(($u->brand.' '.$u->model)));
              @endphp
              {{ $labels->take(3)->join(', ') }}@if($labels->count() > 3), +{{ $labels->count()-3 }} lagi @endif
            </td>
            <td class="p-3"><span class="px-2 py-1 rounded bg-gray-100">{{ $t->status }}</span></td>
            <td class="p-3">
              @if($t->report)
                <div class="flex flex-wrap gap-2">
                  @if($t->report->start_photo_path)
                    <a class="px-3 py-1 rounded-lg border" target="_blank" href="{{ asset('storage/'.$t->report->start_photo_path) }}">Foto Mulai</a>
                  @endif
                  @if($t->report->end_photo_path)
                    <a class="px-3 py-1 rounded-lg border" target="_blank" href="{{ asset('storage/'.$t->report->end_photo_path) }}">Foto Selesai</a>
                  @endif
                  @if($t->report->receipt_path)
                    <a class="px-3 py-1 rounded-lg border" target="_blank" href="{{ asset('storage/'.$t->report->receipt_path) }}">Nota</a>
                  @endif
                </div>
              @else
                <span class="text-gray-400">—</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="5">Belum ada riwayat untuk periode ini.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $items->links() }}</div>
</div>
