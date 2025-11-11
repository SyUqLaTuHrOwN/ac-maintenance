<div>
  <div class="bg-white border rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr><th class="p-3 text-left">Nama</th><th class="p-3 text-left">Email</th><th class="p-3 text-left">Aktif?</th><th class="p-3 w-28">Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($techs as $t)
          <tr class="border-t">
            <td class="p-3">{{ $t->name }}</td>
            <td class="p-3">{{ $t->email }}</td>
            <td class="p-3">{{ $t->remember_token ? 'Ya' : 'Tidak' }}</td>
            <td class="p-3">
              <button class="px-3 py-1 rounded-lg border" wire:click="toggle({{ $t->id }})">Toggle</button>
            </td>
          </tr>
        @empty
          <tr><td class="p-3" colspan="4">Belum ada teknisi.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
