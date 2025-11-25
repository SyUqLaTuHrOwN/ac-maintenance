<div class="space-y-6">

    {{-- FILTER BAR --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div class="flex flex-wrap items-center gap-2">
            <select class="rounded-xl border-gray-300" wire:model="month">
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}">{{ str_pad($m,2,'0',STR_PAD_LEFT) }}</option>
                @endfor
            </select>
            <select class="rounded-xl border-gray-300" wire:model="year">
                @for($y=now()->year-2; $y<=now()->year+2; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
            <select class="rounded-xl border-gray-300" wire:model="status">
                <option value="">Semua status</option>
                <option value="selesai_servis">selesai_servis</option>
                <option value="selesai">selesai</option>
                <option value="dibatalkan_oleh_klien">dibatalkan_oleh_klien</option>
            </select>
            <input type="text" class="rounded-xl border-gray-300"
                   placeholder="Cari klien/lokasi..."
                   wire:model.debounce.400ms="search">
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border rounded-2xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Tanggal</th>
                <th class="p-3 text-left">Klien/Lokasi</th>
                <th class="p-3 text-left">Unit</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Laporan</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $t)
                @php
                    $labels = $t->units->map(function($u){
                        $label = trim(($u->brand.' '.$u->model));
                        if ($u->serial_number) {
                            $label .= ' — SN '.$u->serial_number;
                        }
                        return $label;
                    });
                @endphp
                <tr class="border-t">
                    <td class="p-3 align-top">{{ $t->scheduled_at->format('d M Y') }}</td>
                    <td class="p-3 align-top">
                        {{ $t->client?->company_name }} — {{ $t->location?->name }}
                    </td>
                    <td class="p-3 align-top">
                        @if($labels->isEmpty())
                            <span class="text-gray-400">-</span>
                        @else
                            {{ $labels->take(3)->join(', ') }}
                            @if($labels->count() > 3)
                                , +{{ $labels->count() - 3 }} unit lain
                            @endif
                        @endif
                          <div class="text-xs text-emerald-700 font-medium mt-1">
        Selesai: {{ $t->reports->sum('units_done') }} unit
    </div>
                    </td>
                    <td class="p-3 align-top">
                        <span class="px-2 py-1 rounded bg-gray-100">{{ $t->status }}</span>
                    </td>
                    <td class="p-3 align-top">
    @if($t->reports->count() > 0)
        <button class="px-3 py-1 rounded-lg border text-sm"
                wire:click="openDetail({{ $t->id }})">
            Detail
        </button>
    @else
        <span class="text-gray-400 text-xs">Belum ada laporan.</span>
    @endif
</td>
                </tr>
            @empty
                <tr><td class="p-3" colspan="5">Belum ada riwayat untuk periode ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $items->links() }}</div>

    {{-- MODAL DETAIL LAPORAN --}}
    @if($detailSchedule)
        <div class="fixed inset-0 bg-black/40 z-40" wire:click="closeDetail"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-white w-full max-w-5xl rounded-xl shadow-xl p-6 max-h-[90vh] overflow-y-auto"
                 wire:click.stop>

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="font-semibold text-lg">Detail Laporan</div>
                        <div class="text-sm text-gray-500">
                            Jadwal: {{ $detailSchedule->scheduled_at?->format('d M Y') }}
                            • {{ $detailSchedule->location?->name }}
                        </div>
                    </div>
                    <button class="text-gray-500" wire:click="closeDetail">✕</button>
                </div>

                {{-- Info utama --}}
                <div class="grid md:grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <div><span class="font-medium">Klien:</span> {{ $detailSchedule->client?->company_name }}</div>
                        <div><span class="font-medium">Lokasi:</span> {{ $detailSchedule->location?->name }}</div>
                        <div><span class="font-medium">Status Jadwal:</span> {{ $detailSchedule->status }}</div>
                    </div>
                    <div>
                        @php
    $totalUnits = $detailSchedule->units->sum(fn($u) => $u->pivot->requested_units);
    $progress = $detailSchedule->reports->sum('units_done');
@endphp

<div><span class="font-medium">Total Unit:</span> {{ $totalUnits }}</div>

<div><span class="font-medium">Progress:</span>
    {{ $progress }} / {{ $totalUnits }}
</div>
                          
                    </div>
                </div>

                {{-- Unit AC --}}
                <div class="mb-6">
                    <div class="font-medium mb-1">Unit AC</div>
                    @forelse($detailSchedule->units as $u)
                        <div class="text-xs mb-1">
                            <span class="font-medium">
                                {{ $u->brand }} {{ $u->model }}
                            </span>
                            <span class="text-gray-500">
                                — {{ $u->type ?? '-' }},
                                {{ $u->capacity_btu ? number_format($u->capacity_btu).' BTU' : '-' }},
                                SN: {{ $u->serial_number ?? '-' }}
                                @if($u->units_count) ({{ $u->units_count }} unit) @endif
                            </span>
                        </div>
                    @empty
                        <div class="text-xs text-gray-400">Tidak ada data unit.</div>
                    @endforelse
                </div>

                {{-- Laporan harian --}}
                <div class="space-y-6">
                    @forelse($detailReports as $rep)
                        <div class="border rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-medium text-sm">
                                    Laporan tanggal {{ $rep->report_date?->format('d M Y') }}
                                    ({{ $rep->units_done }} unit)
                                </div>
                                <div class="text-xs text-gray-500">
                                    Teknisi: {{ $rep->user?->name ?? '-' }}
                                </div>
                            </div>

                            <div class="grid md:grid-cols-3 gap-4 text-xs">

                                {{-- Foto mulai --}}
                                <div>
                                    <div class="font-medium mb-1">Foto Mulai</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @forelse($rep->photos_start ?? [] as $p)
                                            <a href="{{ asset('storage/'.$p) }}" target="_blank">
                                                <img src="{{ asset('storage/'.$p) }}"
                                                     class="w-full h-20 object-cover rounded-lg">
                                            </a>
                                        @empty
                                            <div class="text-gray-400">-</div>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Foto selesai --}}
                                <div>
                                    <div class="font-medium mb-1">Foto Selesai</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @forelse($rep->photos_finish ?? [] as $p)
                                            <a href="{{ asset('storage/'.$p) }}" target="_blank">
                                                <img src="{{ asset('storage/'.$p) }}"
                                                     class="w-full h-20 object-cover rounded-lg">
                                            </a>
                                        @empty
                                            <div class="text-gray-400">-</div>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Foto tambahan --}}
                                <div>
                                    <div class="font-medium mb-1">Foto Tambahan</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @forelse($rep->photos_extra ?? [] as $p)
                                            <a href="{{ asset('storage/'.$p) }}" target="_blank">
                                                <img src="{{ asset('storage/'.$p) }}"
                                                     class="w-full h-20 object-cover rounded-lg">
                                            </a>
                                        @empty
                                            <div class="text-gray-400">-</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            @if($rep->invoice_path)
                                <div class="mt-3">
                                    <a href="{{ asset('storage/'.$rep->invoice_path) }}" target="_blank"
                                       class="px-3 py-1 rounded-lg border text-xs">
                                        Lihat Nota / Invoice
                                    </a>
                                </div>
                            @endif

                            @if($rep->notes)
                                <div class="mt-2 text-xs">
                                    <span class="font-medium">Catatan:</span>
                                    <span class="whitespace-pre-line">{{ $rep->notes }}</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-xs text-gray-400">
                            Belum ada laporan yang tersimpan untuk jadwal ini.
                        </div>
                    @endforelse
                </div>

                <div class="flex justify-end mt-6">
                    <button class="px-4 py-2 rounded-xl border" wire:click="closeDetail">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
