<div class="max-w-3xl mx-auto space-y-6">

  <div class="bg-white rounded-xl shadow p-6 space-y-4">
    <h2 class="text-lg font-semibold">Profil Ketua Tim</h2>

    <div class="grid grid-cols-2 gap-4 text-sm">
      <div>
        <div class="text-slate-500">Nama Ketua / Nama Tim</div>
        <div class="font-medium">{{ $profile->leader_name ?? $user->name }}</div>
      </div>

      <div>
        <div class="text-slate-500">Email (login)</div>
        <div class="font-medium">{{ $user->email }}</div>
      </div>

      <div>
        <div class="text-slate-500">No HP Ketua</div>
        <div class="font-medium">{{ $profile->phone ?? '-' }}</div>
      </div>

      <div>
        <div class="text-slate-500">Status</div>
        <div class="font-medium">
          {{ $profile->status_label ?? 'Aktif' }}
        </div>
      </div>

      <div class="col-span-2">
        <div class="text-slate-500">Alamat / Area Kerja</div>
        <div class="font-medium">{{ $profile->address ?? '-' }}</div>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-xl shadow p-6 space-y-4">
    <h2 class="text-lg font-semibold">Anggota Tim</h2>

    <div class="space-y-2 text-sm">
      <div>
        <div class="text-slate-500">Anggota 1</div>
        <div class="font-medium">{{ $profile->member_1_name ?? '-' }}</div>
      </div>
      <div>
        <div class="text-slate-500">Anggota 2</div>
        <div class="font-medium">{{ $profile->member_2_name ?? '-' }}</div>
      </div>
    </div>
  </div>

  @if($profile?->extra_bio)
    <div class="bg-white rounded-xl shadow p-6 space-y-4">
      <h2 class="text-lg font-semibold">Biodata Tambahan</h2>
      <pre class="text-xs bg-slate-50 rounded-lg p-3 overflow-x-auto">
{{ json_encode($profile->extra_bio, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}
      </pre>
    </div>
  @endif

</div>
