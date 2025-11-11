<div class="bg-white border rounded-2xl p-6">
  <div class="font-semibold mb-3">Daftar Unit AC</div>

  <table class="w-full text-sm">
    <thead class="bg-gray-50">
      <tr>
        <th class="p-3 text-left">Brand/Model</th>
        <th class="p-3 text-left">Tipe</th>
        <th class="p-3 text-left">SN</th>
        <th class="p-3 text-left">Kapasitas</th>
        <th class="p-3 text-left">Lokasi</th>
        <th class="p-3 text-left">Tgl Pasang</th>
        <th class="p-3 text-left">Last Maintenance</th>
        <th class="p-3 text-left">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($units as $u)
        <tr class="border-t">
          <td class="p-3">{{ $u->brand }} {{ $u->model }}</td>
          <td class="p-3">{{ $u->type ?: '—' }}</td>
          <td class="p-3">{{ $u->serial_number ?: '—' }}</td>
          <td class="p-3">{{ $u->capacity_btu ? $u->capacity_btu.' BTU' : '—' }}</td>
          <td class="p-3">{{ $u->location?->name ?: '—' }}</td>
          <td class="p-3">{{ optional($u->install_date)->format('d M Y') ?: '—' }}</td>
          <td class="p-3">{{ optional($u->last_maintenance_date)->format('d M Y') ?: '—' }}</td>
          <td class="p-3">
            <span class="px-2 py-1 rounded bg-gray-100">
              {{ $u->status ?? 'aktif' }}
            </span>
          </td>
        </tr>
      @empty
        <tr>
          <td class="p-3" colspan="8">Belum ada unit untuk ditampilkan.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
