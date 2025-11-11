<div>
  {{-- Filter bar --}}
  <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <div class="flex flex-wrap items-center gap-2">
      <select class="rounded-xl border-gray-300" wire:model="month" title="Bulan">
        @for($m=1;$m<=12;$m++)
          <option value="{{ $m }}">{{ str_pad($m,2,'0',STR_PAD_LEFT) }}</option>
        @endfor
      </select>
      <select class="rounded-xl border-gray-300" wire:model="year" title="Tahun">
        @for($y=now()->year-2;$y<=now()->year+2;$y++)
          <option value="{{ $y }}">{{ $y }}</option>
        @endfor
      </select>

      <select class="rounded-xl border-gray-300" wire:model="status" title="Status">
        <option value="">Semua status</option>
        <option value="draft">draft</option>
        <option value="submitted">submitted</option>
        <option value="revisi">revisi</option>
        <option value="disetujui">disetujui</option>
      </select>

      <input type="text" placeholder="Cari klien/teknisi..."
             class="rounded-xl border-gray-300" wire:model.debounce.400ms="search">
    </div>

    @if(session('ok'))
      <div class="text-sm text-emerald-700">{{ session('ok') }}</div>
    @endif
  </div>

  {{-- Table --}}
  <div class="bg-white border rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-3 text-left">Jadwal</th>
          <th class="p-3 text-left">Klien/Lokasi</th>
          <th class="p-3 text-left">Teknisi</th>
          <th class="p-3 text-left">Mulai</th>
          <th class="p-3 text-left">Selesai</th>
          <th class="p-3 text-left">Status</th>
          <th class="p-3 text-left">Berkas</th>
          <th class="p-3 w-48">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reports as $r)
          @php
            $badge = match($r->status){
              'disetujui' => 'bg-emerald-100 text-emerald-700',
              'revisi'    => 'bg-amber-100 text-amber-700',
              'submitted' => 'bg-indigo-100 text-indigo-700',
              default     => 'bg-gray-100 text-gray-700'
            };
          @endphp
          <tr class="border-t">
            <td class="p-3">{{ $r->schedule?->scheduled_at?->format('d M Y H:i') ?? '-' }}</td>
            <td class="p-3">
              {{ $r->schedule?->client?->company_name ?? '-' }}
              @if($r->schedule?->location)
                <span class="text-gray-500">— {{ $r->schedule->location->name }}</span>
              @endif
            </td>
            <td class="p-3">{{ $r->technician?->name ?? '-' }}</td>
            <td class="p-3">{{ $r->started_at?->format('d M Y H:i') ?? '-' }}</td>
            <td class="p-3">{{ $r->finished_at?->format('d M Y H:i') ?? '-' }}</td>
            <td class="p-3">
              <span class="px-2 py-1 rounded {{ $badge }}">{{ $r->status ?? 'draft' }}</span>
              @if($r->verified_at)
                <div class="text-xs text-gray-500 mt-1">Verif: {{ $r->verified_at->format('d M Y H:i') }}</div>
              @endif
            </td>
            <td class="p-3">
              <div class="flex flex-wrap gap-2">
                @if($r->start_photo_path)
                  <a class="px-3 py-1 rounded-lg border" target="_blank" href="{{ asset('storage/'.$r->start_photo_path) }}">Foto Mulai</a>
                @endif
                @if($r->end_photo_path)
                  <a class="px-3 py-1 rounded-lg border" target="_blank" href="{{ asset('storage/'.$r->end_photo_path) }}">Foto Selesai</a>
                @endif
                @if($r->receipt_path)
                  <a class="px-3 py-1 rounded-lg border" target="_blank" href="{{ asset('storage/'.$r->receipt_path) }}">Nota</a>
                @endif
              </div>
            </td>
            <td class="p-3">
              <div class="flex flex-wrap gap-2">
                @if($r->status !== 'disetujui')
                  <button class="px-3 py-1 rounded-lg border bg-emerald-600 text-white"
                          wire:click="verify({{ $r->id }})">Setujui</button>
                @endif
                @if($r->status !== 'revisi')
                  <button class="px-3 py-1 rounded-lg border bg-amber-600 text-white"
                          wire:click="requestRevision({{ $r->id }})">Minta Revisi</button>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="8">Belum ada laporan pada periode ini.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $reports->links() }}</div>
</div>
