<div class="space-y-6">

    <div class="bg-white border rounded-2xl overflow-x-auto">
        <div class="px-4 py-3 font-semibold">Daftar Unit AC</div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Gedung / Lokasi - Klien</th>
                <th class="p-3 text-left">Unit AC</th>
                <th class="p-3 text-left">Jumlah Unit</th>
                <th class="p-3 text-left">Periode</th>
                <th class="p-3 text-left">Jml Service/Tahun</th>
                <th class="p-3 text-left">Last Maintenance</th>
                <th class="p-3 text-left">Status</th>
            </tr>
            </thead>

            <tbody>
            @forelse($units as $u)

                @php
                    $totalServiceYear = (
                      $u->units_count && $u->services_per_year
                    ) ? $u->units_count * $u->services_per_year : null;
                @endphp

                <tr class="border-t align-top">

                    <td class="p-3">
                        <div class="font-medium">{{ $u->location?->name }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $u->location?->client?->company_name }}
                        </div>
                    </td>

                    <td class="p-3">
                        <div class="font-medium">{{ $u->brand }} {{ $u->model }}</div>
                        <div class="text-xs text-gray-500">
                            Tipe: {{ $u->type ?? '-' }} <br>
                            Kapasitas:
                            {{ $u->capacity_btu ? number_format($u->capacity_btu).' BTU' : '-' }}
                        </div>
                    </td>

                    <td class="p-3">
                        {{ $u->units_count }} unit
                    </td>

                    <td class="p-3">
                        @if($u->service_period_months)
                            Setiap {{ $u->service_period_months }} bulan
                        @else
                            -
                        @endif
                    </td>

                    <td class="p-3">
                        @if($totalServiceYear)
                            <div class="font-medium">{{ $totalServiceYear }} service/tahun</div>
                            <div class="text-xs text-gray-400">
                                ({{ $u->services_per_year }}x per unit)
                            </div>
                        @else
                            -
                        @endif
                    </td>

                    <td class="p-3">
                        {{ optional($u->last_maintenance_date)->format('d M Y') ?? '-' }}
                    </td>

                    <td class="p-3">
                        <span class="px-2 py-1 rounded bg-gray-100">{{ $u->status }}</span>
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="7" class="p-5 text-center text-gray-500">
                        Belum ada unit AC.
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>

</div>
