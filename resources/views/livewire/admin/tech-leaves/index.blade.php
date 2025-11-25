<div class="space-y-6">

    <div class="flex gap-3">
        <select wire:model="statusFilter" class="rounded-xl border-gray-300 text-sm">
            <option value="all">Semua</option>
            <option value="pending">Menunggu</option>
            <option value="approved">Disetujui</option>
            <option value="rejected">Ditolak</option>
        </select>
    </div>

    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Teknisi</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Alasan</th>
                    <th class="p-3">Bukti</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 w-32">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($leaves as $lv)
                <tr class="border-t">
                    <td class="p-3">
                        <div class="font-semibold">{{ $lv->user->name }}</div>
                    </td>

                    <td class="p-3">
                        {{ $lv->start_date->format('d M Y') }} - 
                        {{ $lv->end_date->format('d M Y') }}
                    </td>

                    <td class="p-3">{{ $lv->reason ?? '-' }}</td>

                    <td class="p-3">
                        @if($lv->proof_path)
                            <a href="{{ asset('storage/'.$lv->proof_path) }}" target="_blank" class="text-blue-600 underline">
                                Lihat Bukti
                            </a>
                        @else
                            -
                        @endif
                    </td>

                    <td class="p-3">
                        <span class="px-2 py-1 rounded bg-gray-100">{{ $lv->status }}</span>
                    </td>

                    <td class="p-3">
                        <button wire:click="openDetail({{ $lv->id }})"
                                class="px-3 py-1 rounded-lg border">Detail</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $leaves->links() }}


    {{-- DETAIL MODAL --}}
    @if($showDetail && $detailLeave)
        <div class="fixed inset-0 bg-black/40 z-40"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl p-6 max-w-xl w-full relative">

                <button class="absolute right-3 top-3" wire:click="closeDetail">✕</button>

                <div class="text-lg font-semibold mb-3">Detail Pengajuan Cuti</div>

                <div class="text-sm space-y-2">
                    <div><strong>Nama:</strong> {{ $detailLeave->user->name }}</div>
                    <div><strong>Tanggal:</strong> 
                        {{ $detailLeave->start_date->format('d M Y') }} -
                        {{ $detailLeave->end_date->format('d M Y') }}
                    </div>
                    <div><strong>Alasan:</strong> {{ $detailLeave->reason }}</div>

                    <div>
                        <strong>Bukti:</strong><br>
                        @if($detailLeave->proof_path)
                            <a href="{{ asset('storage/'.$detailLeave->proof_path) }}"
                               target="_blank"
                               class="text-blue-600 underline">
                                Unduh Bukti
                            </a>
                        @else
                            Tidak ada
                        @endif
                    </div>

                    <div><strong>Status:</strong> {{ $detailLeave->status }}</div>
                </div>

                @if($detailLeave->status === 'pending')
                    <div class="flex justify-end gap-2 mt-5">
                        <button wire:click="reject({{ $detailLeave->id }})"
                                class="px-4 py-2 bg-red-500 text-white rounded-lg">
                            Tolak
                        </button>
                        <button wire:click="approve({{ $detailLeave->id }})"
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg">
                            Setujui
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
