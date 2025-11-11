<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>AC Maintenance • Landing</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body class="antialiased bg-gray-50 text-gray-800">
  <header class="sticky top-0 z-40 bg-white/70 backdrop-blur border-b">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
      <a href="{{ route('home') }}" class="font-semibold text-xl">CoolCare AC</a>
      <nav class="hidden md:flex items-center gap-6">
        <a href="#features" class="hover:text-indigo-600">Kelebihan</a>
        <a href="#docs" class="hover:text-indigo-600">Dokumentasi</a>
        <a href="#testi" class="hover:text-indigo-600">Testimoni</a>
        <a href="#contact" class="hover:text-indigo-600">Kontak</a>
      </nav>
      <div class="flex items-center gap-3">
       <a href="/login"
   onclick="window.location.assign('/login'); return false;"  {{-- hard navigate --}}
   target="_self"
   rel="external"
   class="inline-flex items-center rounded-xl border px-4 py-2 hover:bg-gray-100">
  Login
</a>
      </div>
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
</body>
</html>
