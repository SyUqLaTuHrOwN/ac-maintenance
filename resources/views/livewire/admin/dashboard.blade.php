<div> {{-- ROOT TUNGGAL --}}
  <div class="grid md:grid-cols-4 gap-4">
    <div class="p-5 rounded-2xl border bg-white">
      <div class="text-sm text-gray-500">Total Klien</div>
      <div class="text-2xl font-bold">{{ $totalClients }}</div>
    </div>
    <div class="p-5 rounded-2xl border bg-white">
      <div class="text-sm text-gray-500">Total Unit AC</div>
      <div class="text-2xl font-bold">{{ $totalUnits }}</div>
    </div>
    <div class="p-5 rounded-2xl border bg-white">
      <div class="text-sm text-gray-500">Jadwal Bulan Ini</div>
      <div class="text-2xl font-bold">{{ $scheduledThisMonth }}</div>
    </div>
    <div class="p-5 rounded-2xl border bg-white">
      <div class="text-sm text-gray-500">Status Menunggu</div>
      <div class="text-2xl font-bold">{{ $pending }}</div>
    </div>
  </div>

  <div class="mt-8">
    <h3 class="font-semibold mb-3">Jadwal Mendatang</h3>
    <div class="bg-white border rounded-2xl overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left p-3">Tanggal</th>
            <th class="text-left p-3">Klien</th>
            <th class="text-left p-3">Lokasi</th>
            <th class="text-left p-3">Teknisi</th>
            <th class="text-left p-3">Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($nextSchedules as $row)
            <tr class="border-t">
              <td class="p-3">{{ $row->scheduled_at->format('d M Y') }}</td>
              <td class="p-3">{{ $row->client->company_name ?? '-' }}</td>
              <td class="p-3">{{ $row->location->name ?? '-' }}</td>
              <td class="p-3">{{ $row->technician->name ?? '-' }}</td>
              <td class="p-3"><span class="px-2 py-1 rounded-lg bg-gray-100">{{ $row->status }}</span></td>
            </tr>
          @empty
            <tr><td class="p-3" colspan="5">Belum ada jadwal.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
