<div class="space-y-6">

    <div class="bg-white border rounded-2xl p-6">
        <div class="font-semibold mb-3">History Servis</div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Lokasi & Unit</th>
                    <th class="p-3 text-left">Teknisi</th>
                    <th class="p-3 text-left">Progress</th>
                    <th class="p-3 text-left">Jumlah Unit</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($histories as $r)
                    @php
                        $s = $r->schedule;
                        $units = $s?->units ?? collect();
                        $totalUnit = $units->sum(fn($u)=>$u->pivot->requested_units);
                        $progress  = $r->units_done . " / " . $totalUnit;

                        $labels = $units->map(function($u){
                            return trim($u->brand.' '.$u->model.' — SN '.$u->serial_number);
                        });
                    @endphp

                    <tr class="border-t align-top">

                        {{-- Tanggal --}}
                        <td class="p-3">
                            {{ $r->report_date?->format('d M Y') }}
                            <div class="text-xs text-gray-500">
                                Jadwal: {{ $s?->scheduled_at?->format('d M Y') }}
                            </div>
                        </td>

                        {{-- Lokasi & Unit --}}
                        <td class="p-3">
                            <div class="font-medium">{{ $s?->location?->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $labels->take(1)->join(', ') }}
                                @if($labels->count() > 1)
                                    , +{{ $labels->count() - 1 }} lainnya
                                @endif
                            </div>
                        </td>

                        {{-- Teknisi --}}
                        <td class="p-3">{{ $r->user?->name }}</td>

                        {{-- Progress --}}
                        <td class="p-3">{{ $progress }}</td>

                        {{-- Jumlah Unit --}}
                        <td class="p-3">{{ $totalUnit }} unit</td>

                        {{-- Status --}}
                        <td class="p-3">
                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-700">
                                {{ $r->status }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="p-3 text-center">
                            <button wire:click="showDetailReport({{ $r->id }})"
                                    class="px-3 py-1.5 rounded-lg border hover:bg-gray-50">
                                Detail
                            </button>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td class="p-4 text-center text-gray-500" colspan="7">
                            Belum ada history servis.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $histories->links() }}
        </div>
    </div>


    {{-- DETAIL MODAL --}}
    @if($showDetail && $detailReport)
        <div class="fixed inset-0 bg-black/40 z-40"></div>

        <div class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-5xl w-full max-h-[90vh] overflow-y-auto p-6 relative">

                <button class="absolute top-3 right-3 text-gray-500"
                        wire:click="closeDetail">✕</button>

                @php
                    $s = $detailReport->schedule;
                    $units = $s->units ?? collect();
                    $totalUnit = $units->sum(fn($u)=>$u->pivot->requested_units);
                @endphp

                <h2 class="text-xl font-semibold mb-4">Detail Laporan Servis</h2>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div><strong>Jadwal:</strong> {{ $s?->scheduled_at?->format('d M Y') }}</div>
                        <div><strong>Tanggal Laporan:</strong> {{ $detailReport->report_date?->format('d M Y') }}</div>
                        <div><strong>Status:</strong> {{ $detailReport->status }}</div>
                    </div>

                    <div>
                        <div><strong>Teknisi:</strong> {{ $detailReport->user?->name }}</div>
                        <div><strong>Total Unit:</strong> {{ $totalUnit }}</div>
                        <div><strong>Progress:</strong> {{ $detailReport->units_done }} / {{ $totalUnit }}</div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- FOTO START --}}
                <h3 class="font-semibold mb-2">Foto Mulai</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($detailReport->photos_start ?? [] as $img)
                        <img src="{{ asset('storage/'.$img) }}" class="rounded-xl border" />
                    @endforeach
                </div>

                {{-- FOTO END --}}
                <h3 class="font-semibold mt-6 mb-2">Foto Selesai</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($detailReport->photos_finish ?? [] as $img)
                        <img src="{{ asset('storage/'.$img) }}" class="rounded-xl border" />
                    @endforeach
                </div>

                {{-- FOTO EXTRA --}}
                <h3 class="font-semibold mt-6 mb-2">Foto Tambahan</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($detailReport->photos_extra ?? [] as $img)
                        <img src="{{ asset('storage/'.$img) }}" class="rounded-xl border" />
                    @endforeach
                </div>

                <div class="mt-6 text-right">
                    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white"
                            wire:click="closeDetail">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
