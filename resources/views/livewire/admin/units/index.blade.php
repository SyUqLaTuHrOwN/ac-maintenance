<div>

  {{-- FILTER & HEADER --}}
  <div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-2">
      <select class="rounded-xl border-gray-300" wire:model.live="clientFilter">
        <option value="">Semua Klien</option>
        @foreach($clients as $c)
          <option value="{{ $c->id }}">{{ $c->company_name }}</option>
        @endforeach
      </select>

      <select class="rounded-xl border-gray-300" wire:model.live="locationFilter">
        <option value="">Semua Lokasi</option>
        @foreach($locations as $l)
          <option value="{{ $l->id }}">{{ $l->name }}</option>
        @endforeach
      </select>

      <input type="text"
             wire:model.live.debounce.400ms="search"
             class="rounded-xl border-gray-300"
             placeholder="Cari brand/model/tipe...">

      @if(session('ok'))
        <span class="text-sm text-emerald-700">{{ session('ok') }}</span>
      @endif
    </div>

    <button wire:click="createNew"
            class="rounded-xl bg-indigo-600 text-white px-4 py-2">
      + Unit Baru
    </button>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-3 text-left">Gedung / Lokasi - Klien</th>
          <th class="p-3 text-left">Unit AC</th>
          <th class="p-3 text-left">Jumlah Unit</th>
          <th class="p-3 text-left">Periode</th>
          <th class="p-3 text-left">Jml Service/Tahun</th>
          <th class="p-3 text-left">Last Maintenance</th>
          <th class="p-3 text-left">Status</th>
          <th class="p-3 w-28 text-left">Aksi</th>
        </tr>
      </thead>

      <tbody>
        @forelse($units as $u)

          @php
            $totalServiceYear = (
              $u->units_count && $u->services_per_year
            ) ? $u->units_count * $u->services_per_year : null;
          @endphp

          <tr class="border-t align-top">

            {{-- GEDUNG / KLIEN --}}
            <td class="p-3">
              <div class="font-medium">
                {{ $u->location?->name }}
              </div>
              <div class="text-xs text-gray-500">
                {{ $u->location?->client?->company_name }}
              </div>
            </td>

            {{-- UNIT --}}
            <td class="p-3">
              <div class="font-medium">{{ $u->brand }} {{ $u->model }}</div>
              <div class="text-xs text-gray-500">
                Tipe: {{ $u->type ?? '-' }} <br>
                Kapasitas: {{ $u->capacity_btu ? number_format($u->capacity_btu).' BTU' : '-' }}
              </div>
            </td>
            

            {{-- JUMLAH UNIT --}}
            <td class="p-3">
              {{ $u->units_count }} unit
            </td>

            {{-- PERIODE --}}
            <td class="p-3">
              @if($u->service_period_months)
                Setiap {{ $u->service_period_months }} bulan
              @else
                -
              @endif
            </td>

            {{-- JUMLAH SERVICE TAHUN --}}
            <td class="p-3">
              @if($totalServiceYear)
                <div class="font-medium">{{ $totalServiceYear }} service/tahun</div>
                <div class="text-xs text-gray-400">
                  ({{ $u->services_per_year }}x per unit)
                </div>
              @else
                -
              @endif
            </td>

            {{-- LAST MAINT --}}
            <td class="p-3">
              {{ optional($u->last_maintenance_date)->format('d M Y') ?? '-' }}
            </td>

            {{-- STATUS --}}
            <td class="p-3">
              <span class="px-2 py-1 rounded bg-gray-100">
                {{ $u->status }}
              </span>
            </td>

            {{-- AKSI --}}
            <td class="p-3">
              <div class="flex gap-2">
                <button class="px-3 py-1 rounded-lg border"
                        wire:click="edit({{ $u->id }})">Edit</button>

                <button class="px-3 py-1 rounded-lg border text-red-600"
                        onclick="return confirm('Hapus unit?')"
                        wire:click="delete({{ $u->id }})">
                  Hapus
                </button>
              </div>
            </td>
          </tr>

        @empty
          <tr>
            <td class="p-3 text-center text-gray-500" colspan="8">
              Belum ada data unit AC.
            </td>
          </tr>
        @endforelse

      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $units->links() }}
  </div>

  {{-- MODAL CENTER FORM --}}
  @if(!is_null($editingId))
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/40 z-40"
         wire:click="$set('editingId', null)">
    </div>

    {{-- Modal --}}
    <div class="fixed inset-0 flex items-center justify-center z-50 px-4">
      <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 overflow-y-auto max-h-[90vh]">

        <div class="flex items-center justify-between mb-4">
          <div class="font-semibold text-lg">
            {{ $editingId ? 'Edit Unit' : 'Unit Baru' }}
          </div>
          <button class="text-gray-500"
                  wire:click="$set('editingId', null)">✕</button>
        </div>

        <form wire:submit.prevent="save" class="grid gap-4">

          {{-- LOKASI --}}
          <div>
            <label class="text-sm">Lokasi</label>
            <select class="mt-1 w-full rounded-xl border-gray-300"
                    wire:model.defer="location_id" required>
              <option value="">— pilih lokasi —</option>
              @foreach($locations as $l)
                <option value="{{ $l->id }}">{{ $l->name }}</option>
              @endforeach
            </select>
            @error('location_id')
              <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- BRAND & MODEL --}}
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="text-sm">Brand</label>
              <input class="mt-1 w-full rounded-xl border-gray-300"
                     wire:model.defer="brand">
            </div>
            <div>
              <label class="text-sm">Model</label>
              <input class="mt-1 w-full rounded-xl border-gray-300"
                     wire:model.defer="model">
            </div>
          </div>

          {{-- SERIAL & TYPE --}}
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="text-sm">Serial</label>
              <input class="mt-1 w-full rounded-xl border-gray-300"
                     wire:model.defer="serial_number">
            </div>
            <div>
              <label class="text-sm">Tipe</label>
              <input class="mt-1 w-full rounded-xl border-gray-300"
                     wire:model.defer="type">
            </div>
          </div>

          {{-- CAPACITY --}}
          <div>
            <label class="text-sm">Kapasitas (BTU)</label>
            <input type="number"
                   class="mt-1 w-full rounded-xl border-gray-300"
                   wire:model.defer="capacity_btu">
          </div>
          <div>
    <div>
    <label class="text-sm">Last Maintenance</label>
    <input type="date"
           class="mt-1 w-full rounded-xl border-gray-300"
           wire:model.defer="last_maintenance_date">
    @error('last_maintenance_date')
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


          {{-- UNITS + PERIODE + SERVICE/YEAR --}}
          <div class="grid grid-cols-3 gap-3">
            <div>
              <label class="text-sm">Jumlah Unit</label>
              <input type="number"
                     class="mt-1 w-full rounded-xl border-gray-300"
                     wire:model.defer="units_count">
            </div>

            <div>
              <label class="text-sm">Periode (bulan)</label>
              <input type="number" min="1" max="12"
                     class="mt-1 w-full rounded-xl border-gray-300"
                     wire:model.defer="service_period_months">
            </div>

            <div>
              <label class="text-sm">Service / Tahun</label>
              <input type="number" min="1"
                     class="mt-1 w-full rounded-xl border-gray-300"
                     wire:model.defer="services_per_year">
            </div>
          </div>

          <div class="flex justify-end gap-2 mt-4">
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
    </div>
  @endif

</div>
