<div class="space-y-6">

    {{-- FLASH --}}
    @if(session('ok'))
        <div class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-sm">
            {{ session('ok') }}
        </div>
    @endif

    {{-- FILTER BAR --}}
    <div class="flex flex-wrap items-center gap-3">

        <select wire:model.live="statusFilter" class="rounded-xl border-gray-300 text-sm">
            <option value="">Semua status</option>
            <option value="approved">Disetujui Admin</option>
            <option value="client_approved">Dikonfirmasi Client</option>
        </select>

        <input type="date" wire:model.live="dateFrom" class="rounded-xl border-gray-300 text-sm">
        <span class="text-sm">s/d</span>
        <input type="date" wire:model.live="dateTo" class="rounded-xl border-gray-300 text-sm">

        <input type="text" placeholder="Cari klien / lokasi..."
               wire:model.live.debounce.500ms="search"
               class="rounded-xl border-gray-300 text-sm">
    </div>


    {{-- TABEL --}}
    <div class="bg-white border rounded-2xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Tanggal</th>
                <th class="p-3 text-left">Lokasi & Unit</th>
                <th class="p-3 text-left">Teknisi</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Progress</th>
                <th class="p-3 text-left">Jumlah Unit</th>
                <th class="p-3 w-32">Aksi</th>
            </tr>
            </thead>

            <tbody>
            @forelse($reports as $r)

                @php
                    $sched = $r->schedule;
                    $loc   = $sched?->location;
                    $cli   = $loc?->client;

                    $total = $sched?->units?->count() ?? 0;
                    $done  = $r->units_done ?? 0;
                @endphp

                <tr class="border-t">
                    <td class="p-3">
                        {{ $r->report_date?->format('d M Y') }}
                        <div class="text-xs text-gray-500">
                            Jadwal: {{ $sched?->scheduled_at?->format('d M Y H:i') }}
                        </div>
                    </td>

                    <td class="p-3">
                        <div class="font-medium">{{ $loc?->name }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $sched?->units->first()?->brand }} —
                            {{ $sched?->units->first()?->model }}
                        </div>
                    </td>

                    <td class="p-3">{{ $r->user?->name }}</td>

                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-xs 
                            @if($r->status=='approved') bg-emerald-100 text-emerald-700 
                            @elseif($r->status=='client_approved') bg-indigo-100 text-indigo-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ $r->status }}
                        </span>
                    </td>

                    <td class="p-3">{{ $done }} / {{ $total }}</td>

                    <td class="p-3">{{ $total }} unit</td>

                    <td class="p-3">
                        <button wire:click="openDetail({{ $r->id }})"
                                class="px-3 py-1 rounded-lg border text-sm">
                            Detail Laporan
                        </button>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-500 p-4">
                        Tidak ada laporan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $reports->links() }}</div>


    {{-- DETAIL MODAL --}}
    @if($detail)
        <div class="fixed inset-0 bg-black/40 z-40" wire:click="closeDetail"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-white w-full max-w-4xl rounded-xl p-6 shadow-xl overflow-y-auto max-h-[90vh]"
                 wire:click.stop>

                <div class="flex justify-between mb-4">
                    <div class="font-semibold text-lg">Detail Laporan</div>
                    <button wire:click="closeDetail" class="text-gray-600">✕</button>
                </div>

                @php
                    $sched = $detail->schedule;
                    $loc   = $sched?->location;
                    $cli   = $loc?->client;
                    $units = $sched?->units ?? collect();
                @endphp

                <div class="grid grid-cols-2 gap-6 text-sm mb-6">
                    <div>
                        <div><b>Teknisi:</b> {{ $detail->user?->name }}</div>
                        <div><b>Tanggal Laporan:</b> {{ $detail->report_date?->format('d M Y') }}</div>
                        <div><b>Unit Selesai:</b> {{ $detail->units_done }} unit</div>
                    </div>

                    <div>
                        <div><b>Lokasi:</b> {{ $loc?->name }}</div>
                        <div><b>Klien:</b> {{ $cli?->company_name }}</div>
                        <div><b>Jadwal:</b> {{ $sched?->scheduled_at?->format('d M Y') }}</div>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="font-medium mb-2">Unit AC</div>
                    @foreach($units as $u)
                        <div class="text-xs">
                            {{ $u->brand }} — {{ $u->model }} ({{ $u->type }})
                        </div>
                    @endforeach
                </div>

                {{-- FOTO --}}
                <div class="grid grid-cols-3 gap-4 mb-6">

                    {{-- mulai --}}
                    <div>
                        <div class="font-medium mb-1">Foto Mulai</div>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($detail->photos_start ?? [] as $p)
                                <img src="{{ asset('storage/'.$p) }}" class="rounded-xl h-24 object-cover">
                            @endforeach
                        </div>
                    </div>

                    {{-- selesai --}}
                    <div>
                        <div class="font-medium mb-1">Foto Selesai</div>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($detail->photos_finish ?? [] as $p)
                                <img src="{{ asset('storage/'.$p) }}" class="rounded-xl h-24 object-cover">
                            @endforeach
                        </div>
                    </div>

                    {{-- ekstra --}}
                    <div>
                        <div class="font-medium mb-1">Foto Tambahan</div>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($detail->photos_extra ?? [] as $p)
                                <img src="{{ asset('storage/'.$p) }}" class="rounded-xl h-24 object-cover">
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- CATATAN --}}
                <div class="mb-6">
                    <div class="font-medium mb-1">Catatan Teknisi</div>
                    <div class="text-sm bg-gray-50 rounded-xl p-3">
                        {{ $detail->notes ?: '-' }}
                    </div>
                </div>

                {{-- BUTTON KONFIRMASI --}}
                @if($detail->status === 'approved')
                    <button wire:click="confirmReport({{ $detail->id }})"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-xl">
                        Konfirmasi Service
                    </button>
                @endif

            </div>
        </div>
    @endif
</div>
