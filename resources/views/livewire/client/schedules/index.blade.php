<div class="space-y-6">
  <div class="flex items-center justify-between">
    <h2 class="text-lg font-semibold">Client • Jadwal</h2>
  </div>

  <div class="bg-white border rounded-xl overflow-hidden">
    <div class="px-4 py-3 border-b font-medium">Jadwal</div>

    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr class="text-left">
          <th class="p-3 w-48">Tanggal</th>
          <th class="p-3">Lokasi</th>
          <th class="p-3 w-40">Teknisi</th>
          <th class="p-3 w-40">Status</th>
          <th class="p-3 w-[320px] text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($schedules as $s)
          @php
            // Sesuaikan daftar status sesuai project-mu
            $final  = in_array($s->status, ['selesai_servis','selesai']);
            $canAct = in_array($s->status, ['menunggu','menunggu_persetujuan','dijadwalkan']);

            $confirm = URL::signedRoute('schedule.client.confirm', ['schedule'=>$s->id]);
            $resForm = URL::signedRoute('schedule.client.reschedule.form', ['schedule'=>$s->id]);
            $cancel  = URL::signedRoute('schedule.client.cancel', ['schedule'=>$s->id]);
          @endphp

          <tr class="border-t">
            <td class="p-3 align-top">{{ $s->scheduled_at?->format('d M Y H:i') }}</td>
            <td class="p-3 align-top">
              {{ $s->location?->name ?? '-' }}
              @if($s->client_requested_date)
                <div class="text-xs text-amber-600 mt-1">
                  Permintaan reschedule: {{ \Illuminate\Support\Carbon::parse($s->client_requested_date)->format('d M Y H:i') }}
                </div>
              @endif
            </td>
            <td class="p-3 align-top">{{ $s->technician?->name ?: '-' }}</td>
            <td class="p-3 align-top">
              <span class="px-2 py-1 rounded bg-gray-100">{{ $s->status }}</span>
            </td>
            <td class="p-3 align-top">
              <div class="flex gap-2 justify-center">
                <a href="{{ $canAct ? $confirm : '#' }}"
                   class="px-3 py-1.5 rounded-lg border {{ $canAct ? 'hover:bg-gray-50' : 'opacity-40 pointer-events-none' }}">
                  Konfirmasi
                </a>

                <a href="{{ $canAct ? $resForm : '#' }}"
                   class="px-3 py-1.5 rounded-lg border {{ $canAct ? 'hover:bg-gray-50' : 'opacity-40 pointer-events-none' }}">
                  Reschedule
                </a>

                <a href="{{ $canAct ? $cancel : '#' }}"
                   class="px-3 py-1.5 rounded-lg border {{ $canAct ? 'hover:bg-gray-50' : 'opacity-40 pointer-events-none' }}">
                  Batalkan
                </a>
              </div>

              @if($final)
                <div class="text-[11px] text-gray-500 text-center mt-2">Jadwal sudah selesai.</div>
              @elseif(!$canAct)
                <div class="text-[11px] text-gray-500 text-center mt-2">Menunggu proses internal.</div>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td class="p-5 text-center text-gray-500" colspan="5">Tidak ada jadwal.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
