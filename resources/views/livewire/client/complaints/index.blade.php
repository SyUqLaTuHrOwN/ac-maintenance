<div class="grid md:grid-cols-2 gap-6">
  <div class="bg-white border rounded-2xl p-6">
    <div class="font-semibold mb-3">Buat Komplain</div>
    @if(session('ok')) <div class="mb-2 text-sm text-emerald-700">{{ session('ok') }}</div> @endif

    <form wire:submit.prevent="submit" class="grid gap-3">
      <div>
        <label class="text-sm">Terkait Jadwal (opsional)</label>
        <select class="mt-1 w-full rounded-xl border-gray-300" wire:model="schedule_id">
          <option value="">— Tidak terkait —</option>
          @foreach($schedules as $s)
            <option value="{{ $s->id }}">{{ $s->scheduled_at->format('d M Y H:i') }} — {{ $s->location?->name }}</option>
          @endforeach
        </select>
        @error('schedule_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="text-sm">Subjek</label>
        <input type="text" class="mt-1 w-full rounded-xl border-gray-300" wire:model="subject">
        @error('subject') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="text-sm">Deskripsi</label>
        <textarea rows="4" class="mt-1 w-full rounded-xl border-gray-300" wire:model="message"></textarea>
        @error('message') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-sm">Prioritas</label>
          <select class="mt-1 w-full rounded-xl border-gray-300" wire:model="priority">
            <option value="low">Low</option>
            <option value="normal">Normal</option>
            <option value="high">High</option>
          </select>
        </div>
        <div>
          <label class="text-sm">Lampiran (opsional)</label>
          <input type="file" multiple class="mt-1 w-full" wire:model="files">
          @error('files.*') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="text-right">
        <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Kirim</button>
      </div>
    </form>
  </div>

  <div class="bg-white border rounded-2xl p-6">
    <div class="font-semibold mb-3">Tiket Komplain</div>
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-3 text-left">Tanggal</th>
          <th class="p-3 text-left">Subjek</th>
          <th class="p-3 text-left">Prioritas</th>
          <th class="p-3 text-left">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $c)
          <tr class="border-t">
            <td class="p-3">{{ $c->created_at->format('d M Y H:i') }}</td>
            <td class="p-3">{{ $c->subject }}</td>
            <td class="p-3">{{ $c->priority }}</td>
            <td class="p-3"><span class="px-2 py-1 rounded bg-gray-100">{{ $c->status }}</span></td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="4">Belum ada komplain.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
