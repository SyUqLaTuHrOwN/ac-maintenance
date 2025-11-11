<div>
  <div class="mb-3 text-sm text-emerald-700">{{ session('ok') }}</div>

  <div class="bg-white border rounded-2xl p-6">
    <div class="font-semibold mb-3">Daftar Laporan (beri rating)</div>
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-3 text-left">Tanggal</th>
          <th class="p-3 text-left">Lokasi</th>
          <th class="p-3 text-left">Status</th>
          <th class="p-3 text-left">Feedback</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reports as $r)
          <tr class="border-t">
            <td class="p-3">{{ $r->finished_at?->format('d M Y H:i') ?: '-' }}</td>
            <td class="p-3">{{ $r->schedule?->location?->name }}</td>
            <td class="p-3">{{ $r->status }}</td>
            <td class="p-3">
              @if($r->feedback)
                <span class="px-2 py-1 rounded bg-emerald-50 text-emerald-700">Sudah dinilai ({{ $r->feedback->rating }}/5)</span>
              @else
                <button class="px-3 py-1 rounded-lg border" wire:click="open({{ $r->id }})">Beri Rating</button>
              @endif
            </td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="4">Tidak ada laporan untuk dinilai.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($reportId)
    <div class="fixed inset-0 bg-black/40 z-40" wire:click="$set('reportId', null)"></div>
    <div class="fixed inset-x-0 top-16 mx-auto max-w-md bg-white rounded-2xl z-50 border">
      <div class="p-4 border-b font-semibold">Beri Feedback</div>
      <form wire:submit.prevent="submit" class="p-4 space-y-3">
        <div>
          <label class="text-sm">Rating (1-5)</label>
          <input type="number" min="1" max="5" class="mt-1 w-full rounded-xl border-gray-300" wire:model="rating">
          @error('rating') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
          <label class="text-sm">Komentar</label>
          <textarea rows="3" class="mt-1 w-full rounded-xl border-gray-300" wire:model="comment"></textarea>
          @error('comment') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" class="px-4 py-2 rounded-xl border" wire:click="$set('reportId', null)">Batal</button>
          <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Kirim</button>
        </div>
      </form>
    </div>
  @endif
</div>
