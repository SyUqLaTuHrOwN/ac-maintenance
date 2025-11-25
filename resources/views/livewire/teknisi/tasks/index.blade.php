<div class="space-y-4">

    {{-- Flash message --}}
    @if(session('ok'))
        <div class="text-sm text-emerald-700">{{ session('ok') }}</div>
    @endif
    @if(session('err'))
        <div class="text-sm text-red-700">{{ session('err') }}</div>
    @endif

    {{-- TABEL TUGAS --}}
    <div class="bg-white border rounded-2xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Tanggal</th>
                <th class="p-3 text-left">Lokasi - Klien</th>
                <th class="p-3 text-left">Unit AC</th>
                <th class="p-3 text-left">Total Unit</th>
                <th class="p-3 text-left">Kapasitas & Est.</th>
                <th class="p-3 text-left">Progress</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($tasks as $t)
                @php
                    $totalUnits = $t->total_units ?? $t->units->sum('units_count');
                @endphp

                <tr class="border-t align-top">

                    {{-- Tanggal --}}
                    <td class="p-3">
                        {{ $t->scheduled_at?->format('d M Y') }}
                    </td>

                    {{-- Lokasi - Klien --}}
                    <td class="p-3">
                        <div class="font-medium">{{ $t->location?->name }}</div>
                        <div class="text-xs text-gray-500">{{ $t->client?->company_name }}</div>
                    </td>

                    {{-- Unit AC --}}
                    <td class="p-3">
                        @foreach($t->units as $u)
                            <div class="text-xs">
                                <span class="font-medium">{{ $u->brand }} {{ $u->model }}</span>
                                <span class="text-gray-500">
                                    — {{ $u->type ?? '-' }},
                                    {{ $u->capacity_btu ? number_format($u->capacity_btu).' BTU' : '-' }}
                                    ({{ $u->units_count ?? 1 }} unit)
                                </span>
                            </div>
                        @endforeach
                    </td>

                    {{-- Total Unit --}}
                    <td class="p-3">
                        {{ $totalUnits }}
                    </td>

                    {{-- Kapasitas & Estimasi --}}
                    <td class="p-3">
                        <div>{{ $t->daily_capacity ? $t->daily_capacity.' unit/hari' : '-' }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $t->estimated_days ? '± '.$t->estimated_days.' hari' : '-' }}
                        </div>
                    </td>

                    {{-- Progress --}}
                    <td class="p-3">
                        {{ $t->progress_units }}/{{ $totalUnits }}
                    </td>

                    {{-- Status --}}
                    <td class="p-3">
                        <span class="px-2 py-1 rounded bg-gray-100">
                            {{ $t->status }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="p-3">
                        <div class="flex flex-wrap gap-2">

                            {{-- MULAI --}}
                            @if($t->status === 'menunggu' && now('Asia/Jakarta')->toDateString() >= $t->scheduled_at->toDateString())
                                <button wire:click="startTask({{ $t->id }})"
                                        class="px-3 py-1 rounded-lg bg-emerald-600 text-white">
                                    Mulai
                                </button>
                            @endif

                            {{-- LAPORAN HARIAN --}}
                            @if($t->status === 'dalam_proses')
                                <button wire:click="openReportModal({{ $t->id }})"
                                        class="px-3 py-1 rounded-lg bg-indigo-600 text-white">
                                    Selesai Hari Ini
                                </button>
                            @endif

                            {{-- Detail (nanti bisa diarahkan ke halaman detail) --}}
                            <button class="px-3 py-1 rounded-lg border" type="button">
                                Detail
                            </button>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td class="p-3 text-center text-gray-500" colspan="8">
                        Tidak ada tugas untuk saat ini.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $tasks->links() }}
    </div>

    {{-- MODAL LAPORAN HARIAN --}}
    @if($reportScheduleId)
        <div class="fixed inset-0 bg-black/40 z-40"
             wire:click="$set('reportScheduleId', null)"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-white w-full max-w-3xl rounded-xl shadow-xl p-6 max-h-[90vh] overflow-y-auto"
                 wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-lg">Laporan Pekerjaan Hari Ini</h2>
                    <button class="text-gray-500"
                            wire:click="$set('reportScheduleId', null)">✕</button>
                </div>

                @php
                    $sched = \App\Models\MaintenanceSchedule::with(['location','client'])
                        ->find($reportScheduleId);
                    $totalUnits = $sched?->total_units ?? $sched?->units->sum('units_count');
                @endphp

                @if($sched)
                    <div class="mb-4 text-sm text-gray-600">
                        <div><span class="font-medium">Lokasi:</span> {{ $sched->location?->name }}</div>
                        <div><span class="font-medium">Klien:</span> {{ $sched->client?->company_name }}</div>
                        <div><span class="font-medium">Tanggal:</span> {{ $sched->scheduled_at?->format('d M Y') }}</div>
                        <div><span class="font-medium">Total Unit:</span> {{ $totalUnits }}</div>
                        <div><span class="font-medium">Progress Saat Ini:</span> {{ $sched->progress_units }}/{{ $totalUnits }}</div>
                    </div>
                @endif

                <form wire:submit.prevent="submitReport" class="space-y-4">

                    <div>
                        <label class="text-sm font-medium">Jumlah Unit Selesai Hari Ini</label>
                        <input type="number"
                               class="mt-1 w-full rounded-xl border-gray-300"
                               wire:model.defer="units_done"
                               min="1">
                        @error('units_done')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">Foto Mulai (boleh lebih dari 1)</label>
                            <input type="file" multiple
                                   class="mt-1 w-full text-sm"
                                   wire:model="photos_start">
                            @error('photos_start.*')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Foto Selesai (boleh lebih dari 1)</label>
                            <input type="file" multiple
                                   class="mt-1 w-full text-sm"
                                   wire:model="photos_finish">
                            @error('photos_finish.*')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Foto Tambahan / Dokumentasi</label>
                        <input type="file" multiple
                               class="mt-1 w-full text-sm"
                               wire:model="photos_extra">
                        @error('photos_extra.*')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Nota / Invoice (opsional)</label>
                        <input type="file"
                               class="mt-1 w-full text-sm"
                               wire:model="invoice">
                        @error('invoice')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Catatan</label>
                        <textarea rows="3"
                                  class="mt-1 w-full rounded-xl border-gray-300"
                                  wire:model.defer="notes"></textarea>
                        @error('notes')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button"
                                class="px-4 py-2 rounded-xl border"
                                wire:click="$set('reportScheduleId', null)">
                            Batal
                        </button>
                        <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">
                            Kirim Laporan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

</div>
