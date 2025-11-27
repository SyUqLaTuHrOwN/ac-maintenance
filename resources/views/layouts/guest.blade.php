<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? 'AC Maintenance • Landing' }}</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles

  {{-- biar halaman bisa inject CSS (AOS/Swiper dsb) --}}
  @stack('styles')
</head>
<body class="antialiased bg-gray-50 text-gray-800">

  <header class="sticky top-0 z-40 bg-white/70 backdrop-blur border-b">
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
    
    <!-- KIRI: Logo + Nama PT -->
    <div class="flex items-center gap-3">
      <img src="{{ asset('images/SAS.jpg') }}" alt="Logo" class="h-10 w-auto">
      <a href="{{ route('home') }}" class="font-semibold text-xl">
        PT Sriwijaya Abadi Solusindo
      </a>
    </div>

    @php
      $authPage = request()->routeIs('login')
                 || request()->routeIs('register')
                 || request()->is('password/*');
    @endphp

    @unless($authPage)
      <!-- KANAN: Menu Navbar -->
      <div class="flex items-center gap-8">
        <nav class="hidden md:flex items-center gap-8 mr-10">  <!-- ⭐ Tambahkan ini -->
          <a href="#features" class="scroll-link hover:text-indigo-600">Kelebihan</a>
          <a href="#docs"     class="scroll-link hover:text-indigo-600">Dokumentasi</a>
          <a href="#testi"    class="scroll-link hover:text-indigo-600">Testimoni</a>
          <a href="#contact"  class="scroll-link hover:text-indigo-600">Kontak</a>
        </nav>

        <a href="{{ route('login') }}"
           class="inline-flex items-center rounded-xl border px-4 py-2 hover:bg-gray-100">
          Login
        </a>
      </div>
    @endunless

  </div>
</header>


  <main>
    {{ $slot }}
  </main>

  <footer class="border-t mt-16">
    <div class="max-w-7xl mx-auto px-4 py-10 text-sm text-gray-500">
      © {{ date('Y') }} CoolCare AC. All rights reserved.
    </div>
  </footer>

  @livewireScripts

  {{-- biar halaman bisa inject JS (smooth-scroll, AOS, Swiper) --}}
  @stack('scripts')
</body>
</html>
