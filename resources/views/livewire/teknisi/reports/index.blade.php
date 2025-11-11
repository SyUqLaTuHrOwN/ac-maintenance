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
        <option value="draft">draft</option>
        <option value="submitted">submitted</option>
        <option value="revisi">revisi</option>
        <option value="disetujui">disetujui</option>
      </select>
      <input type="text" class="rounded-xl border-gray-300" placeholder="Cari klien..."
             wire:model.debounce.400ms="search">
      @if(session('ok')) <span class="text-sm text-emerald-700">{{ session('ok') }}</span> @endif
    </div>
  </div>

  <div class="bg-white border rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-3 text-left">Jadwal</th>
          <th class="p-3 text-left">Klien/Lokasi</th>
          <th class="p-3 text-left">Mulai</th>
          <th class="p-3 text-left">Selesai</th>
          <th class="p-3 text-left">Status</th>
          <th class="p-3 text-left">Berkas</th>
          <th class="p-3 w-40">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reports as $r)
          @php
            $badge = match($r->status){
              'disetujui' => 'bg-emerald-100 text-emerald-700',
              'revisi'    => 'bg-amber-100 text-amber-700',
              'submitted' => 'bg-indigo-100 text-indigo-700',
              default     => 'bg-gray-100 text-gray-700',
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
            <td class="p-3">{{ $r->started_at?->format('d M Y H:i') ?? '-' }}</td>
            <td class="p-3">{{ $r->finished_at?->format('d M Y H:i') ?? '-' }}</td>
            <td class="p-3">
              <span class="px-2 py-1 rounded {{ $badge }}">{{ $r->status ?? 'draft' }}</span>
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
              <button class="px-3 py-1 rounded-lg border" wire:click="openEdit({{ $r->id }})">Edit</button>
            </td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="7">Belum ada laporan pada periode ini.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $reports->links() }}</div>

  {{-- Modal Edit --}}
  @if($editingId)
    <div class="fixed inset-0 bg-black/40 z-40" wire:click="$set('editingId', null)"></div>
    <div class="fixed inset-x-0 top-16 mx-auto max-w-lg bg-white rounded-2xl z-50 border">
      <div class="p-4 border-b font-semibold">Perbarui Laporan</div>
      <form wire:submit.prevent="save" class="p-4 space-y-3">
        <div>
          <label class="text-sm">Catatan</label>
          <textarea rows="3" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="notes"></textarea>
          @error('notes') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
          <label class="text-sm">Unit yang diservis (opsional)</label>
          <input type="number" min="0" class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="units_serviced">
          @error('units_serviced') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
          <label class="text-sm">Tambah Foto ke Galeri (opsional)</label>
          <input type="file" accept="image/*" class="mt-1 w-full" wire:model="add_photo">
          @error('add_photo') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
          <label class="text-sm">Ganti Nota (opsional) — pdf/jpg/png</label>
          <input type="file" accept=".pdf,image/*" class="mt-1 w-full" wire:model="replace_receipt">
          @error('replace_receipt') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" class="px-4 py-2 rounded-xl border" wire:click="$set('editingId', null)">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Simpan</button>
        </div>
      </form>
    </div>
  @endif
</div>
