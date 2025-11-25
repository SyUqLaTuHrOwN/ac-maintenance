<div class="grid md:grid-cols-2 gap-6">

    {{-- FORM CUTI --}}
    <div class="bg-white p-5 rounded-xl shadow-sm">
        <div class="font-semibold text-lg mb-3">Form Cuti</div>

        <div class="space-y-3">

            {{-- TANGGAL MULAI --}}
            <div>
                <label class="text-sm">Mulai</label>
                <input type="date" wire:model="start_date"
                       class="w-full rounded-lg border-gray-300">
                @error('start_date') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
            </div>

            {{-- TANGGAL SELESAI --}}
            <div>
                <label class="text-sm">Selesai</label>
                <input type="date" wire:model="end_date"
                       class="w-full rounded-lg border-gray-300">
                @error('end_date') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
            </div>

            {{-- ALASAN --}}
            <div>
                <label class="text-sm">Alasan</label>
                <input type="text" wire:model="reason"
                       class="w-full rounded-lg border-gray-300">
            </div>

            {{-- BUKTI --}}
            <div>
                <label class="text-sm">Bukti (foto/pdf, maks 2MB)</label>
                <input type="file" wire:model="proof"
                       class="w-full text-sm">

                @error('proof') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror

                {{-- PREVIEW --}}
                @if ($proof)
                    <div class="mt-2">
                        <div class="text-xs text-gray-600">Preview:</div>

                        @if (in_array($proof->extension(), ['jpg','jpeg','png','webp']))
                            <img src="{{ $proof->temporaryUrl() }}"
                                 class="w-32 h-32 object-cover rounded-lg border mt-1">
                        @else
                            <div class="text-xs text-gray-500 mt-1">File terunggah: {{ $proof->getClientOriginalName() }}</div>
                        @endif
                    </div>
                @endif
            </div>

            <button wire:click="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl">
                Kirim Pengajuan
            </button>

        </div>
    </div>

    {{-- RIWAYAT CUTI --}}
    <div class="bg-white p-5 rounded-xl shadow-sm">
        <div class="flex justify-between items-center mb-3">
            <div class="font-semibold text-lg">Riwayat Pengajuan</div>

            <select wire:model="filter_status" class="rounded-lg border-gray-300 text-sm">
                <option value="all">Semua</option>
                <option value="pending">Pending</option>
                <option value="approved">Disetujui</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Rentang</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Bukti</th>
                </tr>
            </thead>

            <tbody>
            @forelse($leaves as $l)
                <tr class="border-b">
                    <td class="p-2">
                        {{ $l->start_date }} – {{ $l->end_date }}
                    </td>

                    <td class="p-2">
                        <span class="px-2 py-1 rounded text-xs
                            @if($l->status=='pending') bg-amber-100 text-amber-700
                            @elseif($l->status=='approved') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $l->status }}
                        </span>
                    </td>

                    <td class="p-2">
                        @if($l->proof_path)
                            <a href="{{ asset('storage/'.$l->proof_path) }}" target="_blank"
                               class="text-indigo-600 underline text-xs">
                                Lihat Bukti
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-3 text-center text-gray-500 text-sm">
                        Belum ada pengajuan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $leaves->links() }}
        </div>

    </div>

</div>
