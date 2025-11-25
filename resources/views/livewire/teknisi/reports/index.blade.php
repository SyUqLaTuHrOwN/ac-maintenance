<div class="space-y-4">

    {{-- FILTER BAR --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <input type="date"
                   wire:model.live="dateFrom"
                   class="rounded-xl border-gray-300 text-sm">
            <span class="text-sm text-gray-500">s/d</span>
            <input type="date"
                   wire:model.live="dateTo"
                   class="rounded-xl border-gray-300 text-sm">

            <select wire:model.live="statusFilter"
                    class="rounded-xl border-gray-300 text-sm">
                <option value="">Semua status</option>
                <option value="draft">Draft</option>
                <option value="submitted">Menunggu verifikasi</option>
                <option value="approved">Disetujui</option>
                <option value="revision">Perlu revisi</option>
                <option value="rejected">Ditolak</option>
            </select>

            <input type="text"
                   wire:model.live.debounce.400ms="search"
                   class="rounded-xl border-gray-300 text-sm"
                   placeholder="Cari klien / lokasi...">
        </div>

        @if(session('ok'))
            <span class="text-sm text-emerald-700">{{ session('ok') }}</span>
        @endif
        @if(session('err'))
            <span class="text-sm text-red-600">{{ session('err') }}</span>
        @endif
    </div>

    {{-- TABEL LAPORAN --}}
    <div class="bg-white border rounded-2xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Tanggal Laporan</th>
                <th class="p-3 text-left">Teknisi</th>
                <th class="p-3 text-left">Lokasi - Klien</th>
                <th class="p-3 text-left">Unit AC</th>
                <th class="p-3 text-left">Unit Selesai</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 w-28 text-left">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($reports as $r)
                @php
                    $sched = $r->schedule;
                    $loc   = $sched?->location;
                    $cli   = $loc?->client;
                    $units = $sched?->units ?? collect();
                @endphp
                <tr class="border-t align-top">
                    <td class="p-3">
                        {{ $r->report_date?->format('d M Y H:i') ?? $r->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="p-3">
                        {{ $r->user?->name ?? '-' }}
                    </td>
                    <td class="p-3">
                        <div class="font-medium">{{ $loc?->name ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $cli?->company_name ?? '-' }}</div>
                    </td>
                    <td class="p-3">
                        @forelse($units as $u)
                            <div class="text-xs">
                                <span class="font-medium">{{ $u->brand }} {{ $u->model }}</span>
                                <span class="text-gray-500">
                                    — {{ $u->type ?? '-' }},
                                    {{ $u->capacity_btu ? number_format($u->capacity_btu).' BTU' : '-' }}
                                </span>
                            </div>
                        @empty
                            <span class="text-xs text-gray-400">-</span>
                        @endforelse
                    </td>
                    <td class="p-3">
                        {{ $r->units_done }} unit
                    </td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded {{ $r->status_badge_class }}">
                            {{ $r->status_label }}
                        </span>
                    </td>
                    <td class="p-3">
                        <button class="px-3 py-1 rounded-lg border text-sm"
                                wire:click="showDetail({{ $r->id }})">
                            Detail
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="p-3 text-center text-gray-500" colspan="7">
                        Belum ada laporan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $reports->links() }}
    </div>

    {{-- MODAL DETAIL LAPORAN --}}
    @if($detail)
        <div class="fixed inset-0 bg-black/40 z-40"
             wire:click="closeDetail"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-white w-full max-w-5xl rounded-xl shadow-xl p-6 max-h-[90vh] overflow-y-auto"
                 wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="font-semibold text-lg">Detail Laporan</div>
                        <div class="text-xs text-gray-500">
                            Dibuat {{ $detail->created_at->format('d M Y H:i') }}
                            • Status:
                            <span class="px-2 py-0.5 rounded {{ $detail->status_badge_class }}">
                                {{ $detail->status_label }}
                            </span>
                        </div>
                    </div>
                    <button class="text-gray-500" wire:click="closeDetail">✕</button>
                </div>

                @php
                    $sched = $detail->schedule;
                    $loc   = $sched?->location;
                    $cli   = $loc?->client;
                    $units = $sched?->units ?? collect();
                @endphp

                <div class="grid md:grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <div><span class="font-medium">Teknisi:</span> {{ $detail->user?->name }}</div>
                        <div><span class="font-medium">Tanggal Laporan:</span>
                            {{ $detail->report_date?->format('d M Y') ?? $detail->created_at->format('d M Y') }}
                        </div>
                        <div><span class="font-medium">Unit Selesai:</span> {{ $detail->units_done }} unit</div>
                    </div>
                    <div>
                        <div><span class="font-medium">Lokasi:</span> {{ $loc?->name }}</div>
                        <div><span class="font-medium">Klien:</span> {{ $cli?->company_name }}</div>
                        <div><span class="font-medium">Jadwal:</span> {{ $sched?->scheduled_at?->format('d M Y') }}</div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="font-medium mb-1">Unit AC</div>
                    @forelse($units as $u)
                        <div class="text-xs">
                            <span class="font-medium">{{ $u->brand }} {{ $u->model }}</span>
                            <span class="text-gray-500">
                                — {{ $u->type ?? '-' }},
                                {{ $u->capacity_btu ? number_format($u->capacity_btu).' BTU' : '-' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-xs text-gray-400">-</div>
                    @endforelse
                </div>

                <div class="grid md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <div class="font-medium mb-1 text-sm">Foto Mulai</div>
                        <div class="grid grid-cols-2 gap-2">
                            @forelse($detail->photos_start ?? [] as $p)
                                <a href="{{ asset('storage/'.$p) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$p) }}" class="w-full h-24 object-cover rounded-lg">
                                </a>
                            @empty
                                <div class="text-xs text-gray-400">Tidak ada foto.</div>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <div class="font-medium mb-1 text-sm">Foto Selesai</div>
                        <div class="grid grid-cols-2 gap-2">
                            @forelse($detail->photos_finish ?? [] as $p)
                                <a href="{{ asset('storage/'.$p) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$p) }}" class="w-full h-24 object-cover rounded-lg">
                                </a>
                            @empty
                                <div class="text-xs text-gray-400">Tidak ada foto.</div>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <div class="font-medium mb-1 text-sm">Foto Tambahan</div>
                        <div class="grid grid-cols-2 gap-2">
                            @forelse($detail->photos_extra ?? [] as $p)
                                <a href="{{ asset('storage/'.$p) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$p) }}" class="w-full h-24 object-cover rounded-lg">
                                </a>
                            @empty
                                <div class="text-xs text-gray-400">Tidak ada foto.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="font-medium mb-1 text-sm">Catatan Teknisi</div>
                    <div class="text-sm whitespace-pre-line">
                        {{ $detail->notes ?: '-' }}
                    </div>
                </div>

                @if($detail->invoice_path)
                    <div class="mb-4">
                        <div class="font-medium mb-1 text-sm">Invoice / Nota</div>
                        <a href="{{ asset('storage/'.$detail->invoice_path) }}"
                           target="_blank"
                           class="inline-flex items-center px-3 py-1 rounded-lg border text-sm">
                            Lihat / Unduh Nota
                        </a>
                    </div>
                @endif

                @if($detail->review_note)
                    <div class="mb-4">
                        <div class="font-medium mb-1 text-sm">Catatan Admin</div>
                        <div class="text-sm text-amber-700 whitespace-pre-line">
                            {{ $detail->review_note }}
                        </div>
                    </div>
                @endif

                <div class="flex justify-end">
                    <button class="px-4 py-2 rounded-xl border"
                            wire:click="closeDetail">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
