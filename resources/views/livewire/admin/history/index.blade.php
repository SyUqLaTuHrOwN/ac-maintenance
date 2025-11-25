<div class="space-y-6">

    {{-- FILTER BAR --}}
    <div class="flex flex-wrap items-center gap-3 mb-4">

        {{-- DATE RANGE --}}
        <div wire:ignore>
            <input
                type="text"
                placeholder="Rentang tanggal"
                class="rounded-xl border-gray-300 text-sm w-full sm:w-52"
                x-data
                x-init="
                    flatpickr($el, {
                        mode: 'range',
                        dateFormat: 'Y-m-d',
                        onChange: function(selectedDates, dateStr) {
                            @this.set('dateRange', dateStr);
                        }
                    });
                "
            />
        </div>

        {{-- CLIENT --}}
        <select wire:model="clientFilter" class="rounded-xl border-gray-300 text-sm w-full sm:w-48">
            <option value="">Semua Klien</option>
            @foreach($clients as $c)
                <option value="{{ $c->id }}">{{ $c->company_name }}</option>
            @endforeach
        </select>

        {{-- LOCATION --}}
        <select wire:model="locationFilter" class="rounded-xl border-gray-300 text-sm w-full sm:w-48">
            <option value="">Semua Lokasi</option>
            @foreach($locations as $l)
                <option value="{{ $l->id }}">{{ $l->name }}</option>
            @endforeach
        </select>

        {{-- TECHNICIAN --}}
        <select wire:model="technicianFilter" class="rounded-xl border-gray-300 text-sm w-full sm:w-48">
            <option value="">Semua Teknisi</option>
            @foreach($technicians as $t)
                <option value="{{ $t->id }}">{{ $t->name }}</option>
            @endforeach
        </select>

        {{-- UNIT --}}
        <select wire:model="unitFilter" class="rounded-xl border-gray-300 text-sm w-full sm:w-48">
            <option value="">Semua Unit</option>
            @foreach($units as $u)
                <option value="{{ $u->id }}">
                    {{ $u->brand }} {{ $u->model }}
                    @if($u->serial_number) — SN {{ $u->serial_number }} @endif
                </option>
            @endforeach
        </select>

        {{-- SEARCH --}}
        <input type="text"
               wire:model.debounce.500ms="search"
               placeholder="Cari klien / lokasi / catatan..."
               class="rounded-xl border-gray-300 text-sm w-full sm:w-60" />
    </div>


    {{-- TABLE --}}
    <div class="bg-white border rounded-2xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3">Tanggal</th>
                <th class="p-3">Klien / Lokasi</th>
                <th class="p-3">Teknisi</th>
                <th class="p-3">Unit</th>
                <th class="p-3">Unit Selesai</th>
                <th class="p-3">Status</th>
                <th class="p-3">Aksi</th>
            </tr>
            </thead>

            <tbody>
            @forelse($items as $s)
                @php
                    $labels = $s->units->map(function($u){
                        $label = $u->brand.' '.$u->model;
                        if ($u->serial_number) $label .= ' — SN '.$u->serial_number;
                        return $label;
                    });
                @endphp

                <tr class="border-t">
                    <td class="p-3 align-top">{{ $s->scheduled_at?->format('d M Y') }}</td>

                    <td class="p-3 align-top">
                        {{ $s->client?->company_name }} — {{ $s->location?->name }}
                    </td>

                    <td class="p-3 align-top">
                        {{ $s->technician?->name ?? '-' }}
                    </td>

                    <td class="p-3 align-top">
                        @if($labels->isEmpty())
                            <span class="text-gray-400">-</span>
                        @else
                            {{ $labels->take(2)->join(', ') }}
                            @if($labels->count() > 2)
                                , +{{ $labels->count() - 2 }} lainnya
                            @endif
                        @endif
                    </td>

                    <td class="p-3 align-top">
                        {{ $s->approved_units }} / {{ $s->unit_count }}
                    </td>

                    <td class="p-3 align-top">
                        <span class="px-2 py-1 bg-gray-100 rounded">
                            {{ $s->status }}
                        </span>
                    </td>

                    <td class="p-3 align-top">
                        <button wire:click="openDetail({{ $s->id }})"
                                class="px-3 py-1 border rounded-lg text-sm">
                            Detail
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center p-4 text-gray-500">
                        Tidak ada data.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>


    {{-- =============================== --}}
    {{--          MODAL DETAIL          --}}
    {{-- =============================== --}}
    @if($detailSchedule)
        <div class="fixed inset-0 bg-black/40 z-40" wire:click="closeDetail"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-white w-full max-w-5xl rounded-xl shadow-xl p-6 max-h-[90vh] overflow-y-auto"
                 wire:click.stop>

                @php
                    $reports = $detailSchedule->reports;
                    $totalUnits = $detailSchedule->unit_count;
                    $doneUnits = $detailSchedule->approved_units;
                    $first = $reports->min('report_date');
                    $last = $reports->max('report_date');
                @endphp

                <div class="flex items-center justify-between mb-3">
                    <div class="text-lg font-semibold">Detail Riwayat Tugas</div>
                    <button class="text-gray-600" wire:click="closeDetail">✕</button>
                </div>

                <div class="text-sm text-gray-500 mb-4">
                    Jadwal:
                    {{ $detailSchedule->scheduled_at?->format('d M Y') }} •
                    {{ $detailSchedule->location?->name }}

                    @if($first)
                        • Laporan:
                        {{ $first->format('d M Y') }}
                        @if(!$first->equalTo($last))
                            s/d {{ $last->format('d M Y') }}
                        @endif
                    @endif
                </div>

                {{-- INFO --}}
                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <div><b>Klien:</b> {{ $detailSchedule->client?->company_name }}</div>
                        <div><b>Lokasi:</b> {{ $detailSchedule->location?->name }}</div>
                        <div><b>Status:</b> {{ $detailSchedule->status }}</div>
                    </div>

                    <div>
                        <div><b>Teknisi:</b> {{ $detailSchedule->technician?->name ?? '-' }}</div>
                        <div><b>Total Unit:</b> {{ $totalUnits }}</div>
                        <div><b>Unit Selesai:</b> {{ $doneUnits }}</div>
                        <div><b>Progress:</b> {{ $doneUnits }} / {{ $totalUnits }}</div>
                    </div>
                </div>

                {{-- UNIT --}}
                <div class="mb-6">
                    <div class="font-medium mb-1">Unit AC</div>
                    @forelse($detailSchedule->units as $u)
                        <div class="text-xs mb-1">
                            <b>{{ $u->brand }} {{ $u->model }}</b>
                            — {{ $u->type ?? '-' }},
                            {{ number_format($u->capacity_btu) }} BTU,
                            SN {{ $u->serial_number ?? '-' }}
                        </div>
                    @empty
                        <div class="text-gray-400">Tidak ada unit.</div>
                    @endforelse
                </div>

                {{-- REPORT LOOP --}}
                <div class="space-y-4 text-xs">
                    @foreach($reports as $r)
                        <div class="border rounded-xl p-4">
                            <div class="flex justify-between mb-2">
                                <div class="font-medium">
                                    Laporan {{ $r->report_date?->format('d M Y') }}
                                    ({{ $r->units_done }} unit)
                                </div>
                                <div class="text-gray-500">
                                    Teknisi: {{ $r->user?->name }}
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">

                                {{-- MULAI --}}
                                <div>
                                    <div class="font-semibold mb-1">Foto Mulai</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($r->photos_start ?? [] as $p)
                                            <img src="{{ asset('storage/'.$p) }}"
                                                 class="h-20 rounded object-cover" />
                                        @endforeach
                                    </div>
                                </div>

                                {{-- SELESAI --}}
                                <div>
                                    <div class="font-semibold mb-1">Foto Selesai</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($r->photos_finish ?? [] as $p)
                                            <img src="{{ asset('storage/'.$p) }}"
                                                 class="h-20 rounded object-cover" />
                                        @endforeach
                                    </div>
                                </div>

                                {{-- EXTRA --}}
                                <div>
                                    <div class="font-semibold mb-1">Foto Tambahan</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($r->photos_extra ?? [] as $p)
                                            <img src="{{ asset('storage/'.$p) }}"
                                                 class="h-20 rounded object-cover" />
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @if($r->invoice_path)
                                <div class="mt-3">
                                    <a href="{{ asset('storage/'.$r->invoice_path) }}"
                                       target="_blank"
                                       class="px-3 py-1 rounded border inline-block">
                                        Lihat Nota / Invoice
                                    </a>
                                </div>
                            @endif

                            @if($r->notes)
                                <div class="mt-2">
                                    <b>Catatan:</b> {{ $r->notes }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="text-right mt-6">
                    <button class="px-4 py-2 border rounded-xl" wire:click="closeDetail">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
