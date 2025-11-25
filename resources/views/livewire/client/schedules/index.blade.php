<div class="space-y-6">

    {{-- TITLE --}}
    <div class="text-lg font-semibold">Jadwal Maintenance</div>

    {{-- TABLE --}}
    <div class="bg-white border rounded-2xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Tanggal</th>
                <th class="p-3 text-left">Lokasi & Klien</th>
                <th class="p-3 text-left">Unit AC</th>
                <th class="p-3 text-left">Teknisi</th>
                <th class="p-3 text-left">Total Unit</th>
                <th class="p-3 text-left">Service / Tahun</th>
                <th class="p-3 text-left">Progress</th>
                <th class="p-3 text-left">Status</th>
            </tr>
            </thead>

            <tbody>
            @forelse($schedules as $s)

                @php
                    $loc = $s->location;
                    $client = $loc?->client;
                    $units = $s->units ?? collect();
                    $totalUnits = $s->total_units ?: $units->sum(fn($u)=>$u->pivot->requested_units);
                    $progress = $s->progress_units . ' / ' . $totalUnits;
                @endphp

                <tr class="border-t align-top">

                    {{-- Tanggal --}}
                    <td class="p-3">
                        {{ $s->scheduled_at?->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                    </td>

                    {{-- Lokasi / Klien --}}
                    <td class="p-3">
                        <div class="font-medium">{{ $loc?->name }}</div>
                        <div class="text-xs text-gray-500">{{ $client?->company_name }}</div>
                    </td>

                    {{-- Unit --}}
                    <td class="p-3">
                        @forelse($units as $u)
                            <div class="text-xs">
                                <strong>{{ $u->brand }} {{ $u->model }}</strong>
                                — {{ $u->type }},
                                {{ number_format($u->capacity_btu) }} BTU
                                ({{ $u->pivot->requested_units }} unit)
                            </div>
                        @empty
                            <span class="text-gray-400">-</span>
                        @endforelse
                    </td>

                    {{-- Teknisi --}}
                    <td class="p-3">
                        {{ $s->technician?->name ?? '-' }}
                    </td>

                    {{-- Total Unit --}}
                    <td class="p-3">{{ $totalUnits }}</td>

                    {{-- Service per tahun --}}
                    <td class="p-3">
                        {{ $s->service_per_year ?? '-' }} <span class="text-xs text-gray-500">/ tahun</span>
                    </td>

                    {{-- Progress --}}
                    <td class="p-3">{{ $progress }}</td>

                    {{-- Status --}}
                    <td class="p-3">
                        <span class="px-2 py-1 rounded bg-gray-100">
                            {{ $s->status }}
                        </span>
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="8" class="p-3 text-center text-gray-500">
                        Tidak ada jadwal ditemukan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
