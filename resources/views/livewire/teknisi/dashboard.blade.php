<div class="grid md:grid-cols-2 gap-6">
  <div class="bg-white border rounded-2xl">
    <div class="p-4 font-semibold">Tugas Aktif</div>
    <div class="border-t">
      <table class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left p-3">Tanggal</th>
            <th class="text-left p-3">Klien/Lokasi</th>
            <th class="text-left p-3">Status</th>
          </tr>
        </thead>
        <tbody>
        @forelse ($activeTasks as $t)
          <tr class="border-t">
            <td class="p-3">{{ $t->scheduled_at->format('d M Y') }}</td>
            <td class="p-3">{{ $t->client->company_name ?? '-' }} — {{ $t->location->name ?? '-' }}</td>
            <td class="p-3"><span class="px-2 py-1 rounded bg-gray-100">{{ $t->status }}</span></td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="3">Belum ada tugas.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="bg-white border rounded-2xl">
    <div class="p-4 font-semibold">Riwayat Terbaru</div>
    <div class="border-t">
      <table class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left p-3">Tanggal</th>
            <th class="text-left p-3">Klien/Lokasi</th>
            <th class="text-left p-3">Status</th>
          </tr>
        </thead>
        <tbody>
        @forelse ($history as $h)
          <tr class="border-t">
            <td class="p-3">{{ $h->scheduled_at->format('d M Y') }}</td>
            <td class="p-3">{{ $h->client->company_name ?? '-' }} — {{ $h->location->name ?? '-' }}</td>
            <td class="p-3"><span class="px-2 py-1 rounded bg-emerald-100">Selesai</span></td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="3">Belum ada riwayat.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
