<div class="bg-white border rounded-2xl p-6">
  <div class="font-semibold mb-3">Laporan Teknisi</div>
  <table class="w-full text-sm">
    <thead class="bg-gray-50">
      <tr>
        <th class="p-3 text-left">Tanggal</th>
        <th class="p-3 text-left">Lokasi</th>
        <th class="p-3 text-left">Teknisi</th>
        <th class="p-3 text-left">Status</th>
        <th class="p-3">Berkas</th>
      </tr>
    </thead>
    <tbody>
      @forelse($reports as $r)
        <tr class="border-t">
          <td class="p-3">{{ $r->finished_at?->format('d M Y H:i') ?? '-' }}</td>
          <td class="p-3">{{ $r->schedule?->location?->name }}</td>
          <td class="p-3">{{ $r->technician?->name }}</td>
          <td class="p-3"><span class="px-2 py-1 rounded bg-gray-100">{{ $r->status }}</span></td>
          <td class="p-3">
            <div class="flex gap-2 justify-center">
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
        </tr>
      @empty
        <tr><td class="p-3" colspan="5">Belum ada laporan.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
