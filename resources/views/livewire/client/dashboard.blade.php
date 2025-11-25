<div>
  <div class="grid md:grid-cols-4 gap-4">
    <div class="p-5 rounded-2xl border bg-white">
      <div class="text-sm text-gray-500">Perusahaan</div>
      <div class="text-lg font-semibold">{{ $client->company_name ?? '-' }}</div>
    </div>

    <div class="p-5 rounded-2xl border bg-white">
      <div class="text-sm text-gray-500">Total Unit AC</div>
      <div class="text-2xl font-bold">{{ $units }}</div>
    </div>

    <div class="p-5 rounded-2xl border bg-white">
      <div class="text-sm text-gray-500">Jadwal Akan Datang</div>
      <div class="text-2xl font-bold">{{ $upcoming->count() }}</div>
    </div>

    <div class="p-5 rounded-2xl border bg-white">
      <div class="text-sm text-gray-500">Laporan Terakhir</div>
      <div class="text-2xl font-bold">{{ $latestReports->count() }}</div>
    </div>
  </div>


  {{-- JADWAL --}}
  <div class="mt-8 grid md:grid-cols-2 gap-6">
    <div class="bg-white border rounded-2xl">
      <div class="p-4 font-semibold">Jadwal Maintenance</div>

      <div class="border-t">
        <table class="w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left p-3">Tanggal</th>
              <th class="text-left p-3">Lokasi</th>
              <th class="text-left p-3">Status</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($upcoming as $s)
              <tr class="border-t">
                <td class="p-3">{{ $s->scheduled_at->timezone('Asia/Jakarta')->format('d M Y') }}</td>
                <td class="p-3">{{ $s->location->name }}</td>
                <td class="p-3">
                  <span class="px-2 py-1 rounded bg-gray-100">{{ $s->status }}</span>
                </td>
              </tr>
            @empty
              <tr><td colspan="3" class="p-3">Belum ada jadwal.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>


    {{-- LAPORAN --}}
    <div class="bg-white border rounded-2xl">
      <div class="p-4 font-semibold">Laporan Terbaru</div>

      <div class="border-t">
        <table class="w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left p-3">Tanggal</th>
              <th class="text-left p-3">Status</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($latestReports as $r)
              <tr class="border-t">
                <td class="p-3">{{ $r->scheduled_at->timezone('Asia/Jakarta')->format('d M Y') }}</td>

                @php
                  $rep = $r->report instanceof \Illuminate\Support\Collection
                    ? $r->report->last()
                    : $r->report;
                @endphp

                <td class="p-3">
                  <span class="px-2 py-1 rounded bg-gray-100">
                    {{ $rep->status ?? '-' }}
                  </span>
                </td>
              </tr>
            @empty
              <tr><td colspan="2" class="p-3">Belum ada laporan.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
