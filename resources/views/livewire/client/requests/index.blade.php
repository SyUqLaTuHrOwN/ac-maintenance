<div class="grid md:grid-cols-2 gap-6">

    {{-- FORM --}}
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

        {{-- UNIT --}}
        <label class="block text-sm font-medium mb-2">Unit terkait (opsional)</label>

        @forelse($unitsOptions as $u)
            <div class="mb-3">
                <div class="font-medium">{{ $u['text'] }}</div>
                <div class="text-xs text-gray-500 mb-1">Maks: {{ $u['max'] }} unit</div>

                <input type="number"
                       min="0"
                       max="{{ $u['max'] }}"
                       wire:model.live="unitQuantities.{{ $u['id'] }}"
                       class="w-24 rounded-lg border px-2 py-1">
            </div>
        @empty
            <p class="text-sm text-gray-500">Tidak ada unit.</p>
        @endforelse

        {{-- Tanggal --}}
        <label class="block text-sm font-medium mt-4 mb-1">Tanggal preferensi (opsional)</label>
        <input type="datetime-local"
               wire:model="preferred_at"
               class="w-full rounded-lg border px-3 py-2 mb-4">

        {{-- Catatan --}}
        <label class="block text-sm font-medium mb-1">Catatan</label>
        <textarea rows="4"
                  wire:model="notes"
                  class="w-full rounded-lg border px-3 py-2 mb-4"></textarea>

        <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white"
                wire:click="submit">
            Kirim
        </button>
    </div>

    {{-- HISTORY --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="font-semibold mb-4">Riwayat Permintaan</h3>

        <table class="w-full text-sm">
            <thead>
                <tr>
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
                    <tr>
                        <td colspan="3" class="p-2 text-gray-500">Belum ada permintaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>
