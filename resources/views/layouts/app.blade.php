<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? 'Dashboard' }} • AC Maintenance</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
  {{-- Alpine untuk toast popup --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- Expose flash ke JS (AMAN, pakai @json) --}}
  <script>
    window.__flash_ok  = @json(session('ok'));
    window.__flash_err = @json(session('err'));
  </script>
</head>
<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="hidden md:block w-64 bg-white border-r">
      <div class="p-4 text-xl font-semibold">CoolCare AC</div>
      <nav class="px-2 space-y-1 text-sm">
        @auth
          @php
           $role = auth()->user()->role ?? 'client';
             $pendingRequestsCount = \App\Models\ServiceRequest::where('status','menunggu')->count();
           @endphp

          @if ($role==='admin')
            <x-nav.link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav.link>

            <x-nav.section title="Master Data" />
            <x-nav.link :href="route('admin.clients')" :active="request()->routeIs('admin.clients')">Klien</x-nav.link>
            <x-nav.link :href="route('admin.locations')" :active="request()->routeIs('admin.locations')">Lokasi</x-nav.link>
            <x-nav.link :href="route('admin.units')" :active="request()->routeIs('admin.units')">Unit AC</x-nav.link>
            <x-nav.link :href="route('admin.requests')" :active="request()->routeIs('admin.requests')">
  Permintaan
  @if($pendingRequestsCount > 0)
    <span class="ml-2 inline-flex items-center justify-center text-[11px] px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">
      {{ $pendingRequestsCount }}
    </span>
  @endif
</x-nav.link>


            <x-nav.section title="Operasional" />
            <x-nav.link :href="route('admin.schedules')" :active="request()->routeIs('admin.schedules')">Jadwal Maintenance</x-nav.link>
            <x-nav.link :href="route('admin.technicians')" :active="request()->routeIs('admin.technicians')">Teknisi</x-nav.link>
            <x-nav.link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')">Laporan</x-nav.link>

            <x-nav.section title="Sistem" />
            <x-nav.link :href="route('admin.users')" :active="request()->routeIs('admin.users')">Pengguna</x-nav.link>
            <x-nav.link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">Pengaturan</x-nav.link>
            <x-nav.link :href="route('admin.register')" :active="request()->routeIs('admin.register')">Register (buat akun)</x-nav.link>

          @elseif ($role==='teknisi')
            <x-nav.link :href="route('teknisi.dashboard')" :active="request()->routeIs('teknisi.dashboard')">Dashboard</x-nav.link>
            <x-nav.link :href="route('teknisi.tasks')" :active="request()->routeIs('teknisi.tasks')">Tugas Saya</x-nav.link>
            <x-nav.link :href="route('teknisi.reports')" :active="request()->routeIs('teknisi.reports')">Laporan</x-nav.link>
            <x-nav.link :href="route('teknisi.history')" :active="request()->routeIs('teknisi.history')">Riwayat</x-nav.link>
            <x-nav.link :href="route('teknisi.profile')" :active="request()->routeIs('teknisi.profile')">Profil</x-nav.link>

          @else
            <x-nav.link :href="route('client.dashboard')"  :active="request()->routeIs('client.dashboard')">Dashboard</x-nav.link>
            <x-nav.link :href="route('client.units')"      :active="request()->routeIs('client.units')">Unit AC Saya</x-nav.link>
            <x-nav.link :href="route('client.schedules')"  :active="request()->routeIs('client.schedules')">Jadwal Maintenance</x-nav.link>
            <x-nav.link :href="route('client.reports')"    :active="request()->routeIs('client.reports')">Laporan</x-nav.link>
            <x-nav.link :href="route('client.feedback')"   :active="request()->routeIs('client.feedback')">Feedback</x-nav.link>
            <x-nav.link :href="route('client.requests')"   :active="request()->routeIs('client.requests')">Permintaan Maintenance</x-nav.link>
            <x-nav.link :href="route('client.complaints')" :active="request()->routeIs('client.complaints')">Komplain</x-nav.link>
          @endif
        @endauth

        @guest
          <div class="px-3 py-2 text-gray-500 text-sm">
            Silakan <a class="underline" href="{{ route('login') }}">login</a>.
          </div>
        @endguest
      </nav>
    </aside>

    <!-- Content -->
    <div class="flex-1 min-w-0">
      <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
          <div class="font-semibold">{{ $header ?? 'Panel' }}</div>
          <div class="flex items-center gap-3">
            @auth
              <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm rounded-lg border px-3 py-1.5 hover:bg-gray-100">Logout</button>
              </form>
            @endauth
            @guest
              <a href="{{ route('login') }}" class="text-sm rounded-lg border px-3 py-1.5 hover:bg-gray-100">Login</a>
            @endguest
          </div>
        </div>
      </header>

      <main class="max-w-7xl mx-auto px-4 py-8">
        {{ $slot }}
      </main>
    </div>
  </div>

  {{-- TOAST POPUP (Alpine) --}}
  <div
    x-data="{
      show:false, msg:'', type:'ok',
      pop(m,t='ok'){ this.msg=m; this.type=t; this.show=true; setTimeout(()=>this.show=false,3000); }
    }"
    x-init="
      if (window.__flash_ok)  pop(window.__flash_ok,  'ok');
      if (window.__flash_err) pop(window.__flash_err, 'err');

      window.addEventListener('toast', e => pop(e.detail.message, e.detail.type || 'ok'));
    "
    class="fixed inset-0 pointer-events-none z-[100]"
  >
    <div
      x-show="show"
      x-transition
      class="pointer-events-auto fixed top-5 right-5 min-w-[260px] max-w-sm rounded-xl border shadow-lg px-4 py-3 bg-white"
      :class="type==='ok' ? 'border-emerald-300' : 'border-rose-300'"
    >
      <div class="text-sm font-medium" :class="type==='ok' ? 'text-emerald-700' : 'text-rose-700'">
        <span x-text="msg"></span>
      </div>
    </div>
  </div>

  @livewireScripts
</body>
</html>
