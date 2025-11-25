<div class="space-y-4">

    {{-- FILTER BAR --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">

            {{-- Filter Klien --}}
            <select class="rounded-xl border-gray-300" wire:model.live="clientFilter">
                <option value="">Semua Klien</option>
                @foreach($clients as $c)
                    <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                @endforeach
            </select>

            {{-- Filter Status --}}
            <select class="rounded-xl border-gray-300" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="menunggu">menunggu</option>
                <option value="dalam_proses">dalam_proses</option>
                <option value="selesai_servis">selesai_servis</option>
                <option value="selesai">selesai</option>
            </select>

            {{-- Search --}}
            <input type="text"
                   wire:model.live.debounce.400ms="search"
                   class="rounded-xl border-gray-300"
                   placeholder="Cari catatan...">
        </div>

        <button wire:click="createNew"
                class="rounded-xl bg-indigo-600 text-white px-4 py-2">
            + Buat Jadwal
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border rounded-2xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Tanggal</th>
                <th class="p-3 text-left">Lokasi - Klien</th>
                <th class="p-3 text-left">Unit AC</th>
                <th class="p-3 text-left">Tim Teknisi</th>
                <th class="p-3 text-left">Total Unit</th>
                <th class="p-3 text-left">Kapasitas & Estimasi</th>
                <th class="p-3 text-left">Progress</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 w-40 text-left">Aksi</th>
            </tr>
            </thead>

            <tbody>
            @foreach($schedules as $s)
                @php
                    // ambil total_units kolom, kalau kosong baru pakai accessor unit_count
                    $totalUnits = $s->total_units ?: $s->unit_count;
                @endphp

                <tr class="border-t align-top">

                    {{-- Tanggal --}}
                    <td class="p-3">
                        {{ $s->scheduled_at?->timezone('Asia/Jakarta')->format('d M Y') }}
                    </td>

                    {{-- Lokasi / Klien --}}
                    <td class="p-3">
                        <div class="font-medium">{{ $s->location?->name }}</div>
                        <div class="text-xs text-gray-500">{{ $s->client?->company_name }}</div>
                    </td>

                    {{-- Unit --}}
                    <td class="p-3">
                        @forelse($s->units as $u)
                            <div class="text-xs">
                                <strong>{{ $u->brand }} {{ $u->model }}</strong>
                                — {{ $u->type }},
                                {{ number_format($u->capacity_btu) }} BTU
                                ({{ $u->units_count }} unit)
                            </div>
                        @empty
                            <span class="text-gray-400">—</span>
                        @endforelse
                    </td>

                    {{-- Teknisi --}}
                    <td class="p-3">
                        @if($s->technician)
                            <div class="font-medium">{{ $s->technician->name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $s->technician->technicianProfile?->member_1_name ?? '-' }} /
                                {{ $s->technician->technicianProfile?->member_2_name ?? '-' }}
                            </div>
                        @else
                            <span class="text-gray-400">Belum ditentukan</span>
                        @endif
                    </td>

                    {{-- Total Unit --}}
                    <td class="p-3">{{ $totalUnits ?: '-' }}</td>

                    {{-- Kapasitas & Estimasi --}}
                    <td class="p-3">
                        {{ $s->capacity_text }}
                        <div class="text-xs text-gray-500">
                            {{ $s->estimasi_text }}
                        </div>
                    </td>

                    {{-- Progress --}}
                    <td class="p-3">{{ $s->progress_text }}</td>

                    {{-- Status --}}
                    <td class="p-3">
                        <span class="px-2 py-1 rounded bg-gray-100">
                            {{ $s->status }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="p-3">
                        <div class="flex gap-2 flex-wrap">
                            <button class="px-3 py-1 rounded-lg border"
                                    wire:click="edit({{ $s->id }})">
                                Edit
                            </button>

                            <button class="px-3 py-1 rounded-lg border text-red-600"
                                    wire:click="delete({{ $s->id }})"
                                    onclick="return confirm('Hapus jadwal?')">
                                Hapus
                            </button>
                        </div>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $schedules->links() }}
    </div>

    {{-- FORM DRAWER --}}
    @if(!is_null($editingId))
        <div class="fixed inset-0 bg-black/30 z-40"
             wire:click="$set('editingId', null)">
        </div>

        <div class="fixed right-0 top-0 bottom-0 w-full max-w-lg bg-white z-50 border-l overflow-y-auto">

            <div class="p-6 border-b flex items-center justify-between">
                <div class="font-semibold">
                    {{ $editingId ? 'Edit Jadwal' : 'Jadwal Baru' }}
                </div>
                <button class="text-gray-500"
                        wire:click="$set('editingId', null)">✕</button>
            </div>

            <form wire:submit.prevent="save" class="p-6 grid gap-4">

                {{-- Klien --}}
                <div>
                    <label class="text-sm">Klien</label>
                    <select class="mt-1 w-full rounded-xl border-gray-300"
                            wire:model.live="client_id" required>
                        <option value="">— pilih klien —</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Lokasi --}}
                <div>
                    <label class="text-sm">Lokasi</label>
                    <select class="mt-1 w-full rounded-xl border-gray-300"
                            wire:model.live="location_id" required>
                        <option value="">— pilih lokasi —</option>
                        @foreach($locations as $l)
                            <option value="{{ $l->id }}">{{ $l->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Unit --}}
                @if($location_id)
                    <div>
                        <label class="text-sm">Unit AC</label>
                        <div class="mt-2 max-h-52 overflow-y-auto border rounded-xl p-3 space-y-2">
                            @forelse($unitsForLocation as $u)
                                <label class="flex items-center gap-3 text-sm">
                                    <input type="checkbox"
                                           class="rounded border-gray-300"
                                           value="{{ $u->id }}"
                                           wire:model="unit_ids">
                                    <span>
                                        <span class="font-medium">{{ $u->brand }} {{ $u->model }}</span>
                                        <span class="text-gray-500">
                                            — {{ $u->type }},
                                            {{ number_format($u->capacity_btu) }} BTU
                                            ({{ $u->units_count }} unit)
                                        </span>
                                    </span>
                                </label>
                            @empty
                                <div class="text-sm text-gray-500">
                                    Belum ada unit pada lokasi ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                {{-- Tanggal --}}
                <div>
                    <label class="text-sm">Tanggal Maintenance</label>
                    <input type="date"
                           class="mt-1 w-full rounded-xl border-gray-300"
                           wire:model.defer="scheduled_at" required>
                    @error('scheduled_at') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Teknisi --}}
                <div>
                    <label class="text-sm">Tim Teknisi</label>
                    <select class="mt-1 w-full rounded-xl border-gray-300"
                            wire:model="assigned_user_id">
                        <option value="">— pilih tim teknisi —</option>
                        @foreach($techs as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_user_id') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Total unit, kapasitas, estimasi --}}
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-sm">Total Unit</label>
                        <input type="number"
                               class="mt-1 w-full rounded-xl border-gray-300"
                               wire:model.defer="total_units">
                        @error('total_units') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="text-sm">Kapasitas / hari</label>
                        <input type="number"
                               class="mt-1 w-full rounded-xl border-gray-300"
                               wire:model.defer="daily_capacity">
                        @error('daily_capacity') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="text-sm">Estimasi (hari)</label>
                        <input type="number"
                               class="mt-1 w-full rounded-xl border-gray-300"
                               wire:model.defer="estimated_days"
                               readonly>
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-sm">Status</label>
                    <select class="mt-1 w-full rounded-xl border-gray-300"
                            wire:model.defer="status">
                        <option value="menunggu">menunggu</option>
                        <option value="dalam_proses">dalam_proses</option>
                        <option value="selesai_servis">selesai_servis</option>
                        <option value="selesai">selesai</option>
                    </select>
                    @error('status') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="text-sm">Catatan</label>
                    <textarea rows="3"
                              class="mt-1 w-full rounded-xl border-gray-300"
                              wire:model.defer="notes"></textarea>
                    @error('notes') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button"
                            class="px-4 py-2 rounded-xl border"
                            wire:click="$set('editingId', null)">
                        Batal
                    </button>

                    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    @endif

</div>
