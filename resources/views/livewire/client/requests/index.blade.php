<div class="grid md:grid-cols-2 gap-6">
  {{-- Form --}}
  <div class="bg-white rounded-xl border p-5">
    <h3 class="font-semibold mb-4">Buat Permintaan</h3>

    {{-- Lokasi --}}
    <label class="block text-sm font-medium mb-1">Lokasi</label>
    <select class="w-full rounded-lg border px-3 py-2 mb-4"
            wire:model.live="location_id">
      <option value="">— pilih lokasi —</option>
      @foreach($locations as $loc)
        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
      @endforeach
    </select>
    @error('location_id') <p class="text-rose-600 text-sm mb-2">{{ $message }}</p> @enderror

    {{-- Unit terkait (otomatis mengikuti lokasi) --}}
    <label class="block text-sm font-medium mb-1">Unit terkait (opsional)</label>
    <div class="space-y-2 mb-4">
      @forelse($unitsOptions as $u)
        <label class="flex items-center gap-2">
          <input type="checkbox" value="{{ $u['id'] }}" wire:model.live="selected_units"
                 class="rounded border-gray-300">
          <span class="text-sm">{{ $u['text'] }}</span>
        </label>
      @empty
        <div class="text-sm text-gray-500">Tidak ada unit di lokasi ini.</div>
      @endforelse
    </div>
    @error('selected_units.*') <p class="text-rose-600 text-sm mb-2">{{ $message }}</p> @enderror

    {{-- Tanggal preferensi --}}
    <label class="block text-sm font-medium mb-1">Tanggal preferensi (opsional)</label>
    <input type="datetime-local" class="w-full rounded-lg border px-3 py-2 mb-4"
           wire:model="preferred_at">

    {{-- Catatan --}}
    <label class="block text-sm font-medium mb-1">Catatan</label>
    <textarea rows="4" class="w-full rounded-lg border px-3 py-2 mb-4"
              placeholder="Contoh: AC ruangan meeting sudah tidak dingin"
              wire:model="notes"></textarea>

    <button wire:click="submit"
            class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
      Kirim
    </button>
  </div>

  {{-- Riwayat --}}
  <div class="bg-white rounded-xl border p-5">
    <h3 class="font-semibold mb-4">Riwayat Permintaan</h3>

    <table class="w-full text-sm">
      <thead>
        <tr class="text-left text-gray-500">
          <th class="p-2">Dibuat</th>
          <th class="p-2">Lokasi</th>
          <th class="p-2">Status</th>
        </tr>
      </thead>
      <tbody>
      @forelse($requests as $r)
        <tr class="border-t">
          <td class="p-2">{{ $r->created_at->format('d M Y H:i') }}</td>
          <td class="p-2">{{ $r->location?->name }}</td>
          <td class="p-2">
            <span class="px-2 py-1 rounded bg-gray-100">{{ $r->status }}</span>
          </td>
        </tr>
      @empty
        <tr><td class="p-2 text-gray-500" colspan="3">Belum ada permintaan.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
