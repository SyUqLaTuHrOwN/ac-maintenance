<div class="space-y-6">

    {{-- FILTER BAR --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">

            {{-- Klien --}}
            <select class="rounded-xl border-gray-300 text-sm" wire:model.live="clientFilter">
                <option value="">Semua Klien</option>
                @foreach($clients as $c)
                    <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                @endforeach
            </select>

            {{-- Teknisi --}}
            <select class="rounded-xl border-gray-300 text-sm" wire:model.live="technicianFilter">
                <option value="">Semua Teknisi</option>
                @foreach($technicians as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>

            {{-- Status laporan --}}
            <select class="rounded-xl border-gray-300 text-sm" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="submitted">Menunggu Verifikasi</option>
                <option value="approved">Disetujui</option>
                <option value="revision">Revisi</option>
                <option value="rejected">Ditolak</option>
            </select>

            {{-- Range tanggal laporan --}}
            <input type="date" class="rounded-xl border-gray-300 text-sm"
                   wire:model.live="dateFrom">
            <span class="text-xs text-gray-500">s/d</span>
            <input type="date" class="rounded-xl border-gray-300 text-sm"
                   wire:model.live="dateTo">

            {{-- Search --}}
            <input type="text" placeholder="Cari klien / lokasi..."
                   class="rounded-xl border-gray-300 text-sm"
                   wire:model.live.debounce.400ms="search">

        </div>

        {{-- Flash --}}
        @if(session('ok'))
            <div class="text-sm text-emerald-700 font-medium">{{ session('ok') }}</div>
        @endif
        @if(session('err'))
            <div class="text-sm text-red-700 font-medium">{{ session('err') }}</div>
        @endif
    </div>


    {{-- TABLE --}}
    <div class="bg-white border rounded-2xl overflow-x-auto">

        <table class="w-full text-sm">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Tanggal</th>
                <th class="p-3 text-left">Klien / Lokasi</th>
                <th class="p-3 text-left">Teknisi</th>
                <th class="p-3 text-left">Unit Selesai</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 w-40">Aksi</th>
            </tr>
            </thead>

            <tbody>
            @forelse($reports as $r)

                @php
                    $sched = $r->schedule;
                    $loc   = $sched?->location;
                    $cli   = $loc?->client;
                @endphp

                <tr class="border-t align-top">

                    {{-- Tanggal laporan --}}
                    <td class="p-3">
                        {{ $r->report_date?->timezone('Asia/Jakarta')->format('d M Y') ?? $r->created_at->timezone('Asia/Jakarta')->format('d M Y') }}
                        <div class="text-xs text-gray-500">
                            Jadwal: {{ $sched?->scheduled_at?->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}
                        </div>
                    </td>

                    {{-- Klien + lokasi --}}
                    <td class="p-3">
                        <div class="font-medium">{{ $cli?->company_name ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $loc?->name ?? '-' }}</div>
                    </td>

                    {{-- Teknisi --}}
                    <td class="p-3">
                        {{ $r->user?->name ?? '-' }}
                    </td>

                    {{-- Unit selesai --}}
                    <td class="p-3">
                        {{ $r->units_done }} unit
                    </td>

                    {{-- Status --}}
                    <td class="p-3">
                        <span class="px-2 py-1 rounded {{ $r->status_badge_class }}">
                            {{ $r->status_label }}
                        </span>

                        @if($r->verified_at)
                            <div class="text-xs text-gray-500 mt-1">
                                Verif: {{ $r->verified_at->timezone('Asia/Jakarta')->format('d M Y') }}
                            </div>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="p-3">
                        <div class="flex flex-wrap gap-2">

                            {{-- Detail --}}
                            <button class="px-3 py-1 rounded-lg border text-sm"
                                    wire:click="openReview({{ $r->id }}, true)">
                                Detail
                            </button>

                            {{-- Setujui --}}
                            @if($r->status !== 'approved')
                                <button class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-sm"
                                        wire:click="approve({{ $r->id }})">
                                    Setujui
                                </button>
                            @endif

                            {{-- Revisi --}}
                            @if($r->status !== 'revision')
                                <button class="px-3 py-1 bg-amber-600 text-white rounded-lg text-sm"
                                        wire:click="openReview({{ $r->id }})">
                                    Revisi
                                </button>
                            @endif
                        </div>
                    </td>

                </tr>

            @empty
                <tr>
                    <td class="p-3 text-center text-gray-500" colspan="6">
                        Tidak ada laporan untuk periode ini.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $reports->links() }}</div>


    {{-- MODAL DETAIL LAPORAN --}}
    @if($detail)
        <div class="fixed inset-0 bg-black/40 z-40" wire:click="closeDetail"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-white w-full max-w-5xl rounded-xl shadow-xl p-6 max-h-[90vh] overflow-y-auto"
                 wire:click.stop>

                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="font-semibold text-lg">Detail Laporan</div>

                        <div class="text-sm text-gray-500">
                            Dibuat {{ $detail->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} •
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

                {{-- INFO UTAMA --}}
                <div class="grid md:grid-cols-2 gap-4 text-sm mb-6">

                    <div class="space-y-1">
                        <div><span class="font-medium">Teknisi:</span> {{ $detail->user?->name ?? '-' }}</div>
                        <div><span class="font-medium">Tanggal Laporan:</span>
                            {{ $detail->report_date?->timezone('Asia/Jakarta')->format('d M Y') ?? '-' }}
                        </div>
                        <div><span class="font-medium">Unit Selesai:</span> {{ $detail->units_done }} unit</div>
                    </div>

                    <div class="space-y-1">
                        <div><span class="font-medium">Lokasi:</span> {{ $loc?->name ?? '-' }}</div>
                        <div><span class="font-medium">Klien:</span> {{ $cli?->company_name ?? '-' }}</div>
                        <div><span class="font-medium">Jadwal:</span>
                            {{ $sched?->scheduled_at?->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>

                </div>

                {{-- UNIT AC --}}
                <div class="mb-6">
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

                {{-- FOTO --}}
                <div class="grid md:grid-cols-3 gap-4 mb-6">

                    {{-- Foto Mulai --}}
                    <div>
                        <div class="font-medium mb-1">Foto Mulai</div>
                        <div class="grid grid-cols-2 gap-2">
                            @forelse($detail->photos_start ?? [] as $p)
                                <a href="{{ asset('storage/'.$p) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$p) }}"
                                         class="w-full h-24 object-cover rounded-xl">
                                </a>
                            @empty
                                <div class="text-xs text-gray-400">Tidak ada foto.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Foto Selesai --}}
                    <div>
                        <div class="font-medium mb-1">Foto Selesai</div>
                        <div class="grid grid-cols-2 gap-2">
                            @forelse($detail->photos_finish ?? [] as $p)
                                <a href="{{ asset('storage/'.$p) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$p) }}"
                                         class="w-full h-24 object-cover rounded-xl">
                                </a>
                            @empty
                                <div class="text-xs text-gray-400">Tidak ada foto.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Foto Tambahan --}}
                    <div>
                        <div class="font-medium mb-1">Foto Tambahan</div>
                        <div class="grid grid-cols-2 gap-2">
                            @forelse($detail->photos_extra ?? [] as $p)
                                <a href="{{ asset('storage/'.$p) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$p) }}"
                                         class="w-full h-24 object-cover rounded-xl">
                                </a>
                            @empty
                                <div class="text-xs text-gray-400">Tidak ada.</div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- INVOICE --}}
                @if($detail->invoice_path)
                    <div class="mb-6">
                        <div class="font-medium mb-1">Invoice / Nota</div>
                        <a href="{{ asset('storage/'.$detail->invoice_path) }}" target="_blank"
                           class="px-3 py-1 rounded-lg border text-sm">
                            Lihat / Unduh Nota
                        </a>
                    </div>
                @endif

                {{-- CATATAN TEKNISI --}}
                <div class="mb-6">
                    <div class="font-medium mb-1">Catatan Teknisi</div>
                    <div class="text-sm whitespace-pre-line bg-gray-50 rounded-xl p-3">
                        {{ $detail->notes ?: '-' }}
                    </div>
                </div>

                {{-- CATATAN ADMIN --}}
                @if($detail->review_note)
                    <div class="mb-6">
                        <div class="font-medium mb-1">Catatan Admin</div>
                        <div class="text-sm text-amber-700 whitespace-pre-line bg-amber-50 rounded-xl p-3">
                            {{ $detail->review_note }}
                        </div>
                    </div>
                @endif

                {{-- FOOTER --}}
                <div class="flex justify-end">
                    <button class="px-4 py-2 rounded-xl border" wire:click="closeDetail">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif


    {{-- MODAL REVISI --}}
    @if($reviewId && !$detailMode)

        <div class="fixed inset-0 bg-black/40 z-40" wire:click="closeReview"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6"
                 wire:click.stop>

                <div class="flex items-center justify-between mb-4">
                    <div class="font-semibold text-lg">Minta Revisi</div>
                    <button class="text-gray-500" wire:click="closeReview">✕</button>
                </div>

                <div class="text-sm text-gray-600 mb-2">
                    Berikan catatan revisi untuk teknisi.
                </div>

                <textarea rows="4"
                          class="w-full rounded-xl border-gray-300"
                          wire:model.defer="review_note"></textarea>
                @error('review_note')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror

                <div class="flex justify-end gap-2 mt-4">
                    <button class="px-4 py-2 rounded-xl border" wire:click="closeReview">Batal</button>
                    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white"
                            wire:click="sendRevision">Kirim Revisi</button>
                </div>

            </div>
        </div>

    @endif
</div>
