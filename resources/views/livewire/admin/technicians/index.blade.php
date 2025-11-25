<div x-data="{ open:@entangle('showModal') }" class="space-y-6">

    {{-- SEARCH + BUTTON --}}
    <div class="flex items-center gap-3">
        <input type="text"
               wire:model.live.debounce.500ms="search"
               class="w-64 rounded-lg border px-3 py-2"
               placeholder="Cari nama tim / email…">

        <button class="ml-auto px-3 py-2 rounded-lg bg-indigo-600 text-white"
                wire:click="openCreate">
            + Tim Teknisi Baru
        </button>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto bg-white rounded-xl shadow">
        <table class="min-w-full border-collapse">
            <thead class="bg-slate-100">
            <tr class="text-left text-sm">
                <th class="px-4 py-2">Nama Ketua Tim</th>
                <th class="px-4 py-2">Anggota</th>
                <th class="px-4 py-2">No HP</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
            </thead>

            <tbody class="text-sm">
            @forelse($teams as $team)

                @php
                    $status = $team->auto_status;   // <── status otomatis
                @endphp

                <tr class="border-t">

                    {{-- Ketua --}}
                    <td class="px-4 py-2 font-semibold">
                        {{ $team->team_name }}
                    </td>

                    {{-- Anggota --}}
                    <td class="px-4 py-2">
                        {{ $team->member_1_name ?: '-' }} /
                        {{ $team->member_2_name ?: '-' }}
                    </td>

                    {{-- Phone --}}
                    <td class="px-4 py-2">{{ $team->phone ?: '-' }}</td>

                    {{-- Email --}}
                    <td class="px-4 py-2">{{ $team->user?->email }}</td>

                    {{-- STATUS BADGE --}}
                    <td class="px-4 py-2">

                        @if($status === 'aktif')
                            <span class="px-2 py-1 rounded bg-green-100 text-green-700">
                                Aktif
                            </span>

                        @elseif($status === 'cuti')
                            <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700">
                                Cuti
                            </span>

                        @elseif($status === 'sedang_bertugas')
                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-700">
                                Sedang Bertugas
                            </span>

                        @elseif($status === 'nonaktif')
                            <span class="px-2 py-1 rounded bg-gray-200 text-gray-700">
                                Nonaktif
                            </span>
                        @endif

                    </td>

                    {{-- AKSI --}}
                    <td class="px-4 py-2 space-x-2">

                        <button wire:click="openEdit({{ $team->id }})"
                                class="text-xs px-2 py-1 rounded bg-slate-100 hover:bg-slate-200">
                            Edit
                        </button>

                        <button wire:click="toggleActive({{ $team->id }})"
                                class="text-xs px-2 py-1 rounded
                                    {{ $team->is_active ? 'bg-orange-100 text-orange-700' :
                                                         'bg-green-100 text-green-700' }}">
                            {{ $team->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>

                        <button wire:click="delete({{ $team->id }})"
                                onclick="return confirm('Hapus tim teknisi?')"
                                class="text-xs px-2 py-1 rounded bg-red-100 text-red-700">
                            Hapus
                        </button>

                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-slate-400">
                        Belum ada tim teknisi.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    {{ $teams->links() }}

</div>
