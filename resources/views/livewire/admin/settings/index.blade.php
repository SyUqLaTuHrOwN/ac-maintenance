<div>
  @if(session('ok'))<div class="mb-3 text-sm text-emerald-700">{{ session('ok') }}</div>@endif
  <form wire:submit.prevent="save" class="max-w-xl bg-white border rounded-2xl p-6 grid gap-4">
    <div>
      <label class="text-sm">Nama Perusahaan</label>
      <input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="company">
    </div>
    <div>
      <label class="text-sm">Timezone</label>
      <input class="mt-1 w-full rounded-xl border-gray-300" wire:model.defer="timezone">
    </div>
    <div class="flex justify-end">
      <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">Simpan</button>
    </div>
  </form>
</div>
