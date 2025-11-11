<div>
  <div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-2">
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

      @if($onDuty)
        <span class="px-2 py-1 rounded bg-amber-100 text-amber-700">Sedang bertugas</span>
      @else
        <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">Tidak sedang bertugas</span>
      @endif

      @if(session('ok')) <span class="text-sm text-emerald-700">{{ session('ok') }}</span> @endif
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
          <th class="p-3 w-56">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tasks as $t)
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
              <div class="flex flex-wrap gap-2">
                <button class="px-3 py-1 rounded-lg border" wire:click="openStartModal({{ $t->id }})">Mulai</button>
                <button class="px-3 py-1 rounded-lg border bg-indigo-600 text-white" wire:click="openFinishModal({{ $t->id }})">Selesaikan</button>

                @if($t->report)
                  <a class="px-3 py-1 rounded-lg border" target="_blank" href="{{ $t->report->start_photo_path ? asset('storage/'.$t->report->start_photo_path) : '#' }}">Foto Mulai</a>
                  <a class="px-3 py-1 rounded-lg border" target="_blank" href="{{ $t->report->end_photo_path ? asset('storage/'.$t->report->end_photo_path) : '#' }}">Foto Selesai</a>
                  @if($t->report->receipt_path)
                    <a class="px-3 py-1 rounded-lg border" target="_blank" href="{{ asset('storage/'.$t->report->receipt_path) }}">Nota</a>
                  @endif
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="5">Belum ada tugas bulan ini.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $tasks->links() }}</div>

  {{-- Modal MULAI --}}
  @if($startScheduleId)
    <div class="fixed inset-0 bg-black/40 z-40" wire:click="$set('startScheduleId', null)"></div>
    <div class="fixed inset-x-0 top-20 mx-auto max-w-md bg-white rounded-2xl z-50 border">
      <div class="p-4 border-b font-semibold">Mulai Pekerjaan</div>
      <form wire:submit.prevent="startWork" class="p-4 space-y-3">
        <div>
          <label class="text-sm">Foto mulai</label>
          <input type="file" wire:model="start_photo" accept="image/*" class="mt-1 w-full">
          @error('start_photo') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" class="px-4 py-2 rounded-xl border"
                  wire:click="$set('startScheduleId', null)">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Mulai</button>
        </div>
      </form>
    </div>
  @endif

  {{-- Modal SELESAIKAN --}}
  @if($finishScheduleId)
    <div class="fixed inset-0 bg-black/40 z-40" wire:click="$set('finishScheduleId', null)"></div>
    <div class="fixed inset-x-0 top-16 mx-auto max-w-md bg-white rounded-2xl z-50 border">
      <div class="p-4 border-b font-semibold">Selesaikan Pekerjaan</div>
      <form wire:submit.prevent="finishWork" class="p-4 space-y-3">
        <div>
          <label class="text-sm">Foto selesai</label>
          <input type="file" wire:model="end_photo" accept="image/*" class="mt-1 w-full">
          @error('end_photo') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
          <label class="text-sm">Nota / Bukti (opsional)</label>
          <input type="file" wire:model="receipt" accept=".pdf,image/*" class="mt-1 w-full">
          @error('receipt') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
          <label class="text-sm">Catatan (opsional)</label>
          <textarea rows="3" wire:model="notes" class="mt-1 w-full rounded-xl border-gray-300"></textarea>
          @error('notes') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" class="px-4 py-2 rounded-xl border"
                  wire:click="$set('finishScheduleId', null)">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Selesaikan</button>
        </div>
      </form>
    </div>
  @endif
</div>
