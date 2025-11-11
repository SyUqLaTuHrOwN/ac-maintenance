<div>
  <section class="relative">
    <div class="max-w-7xl mx-auto px-4 py-16 grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl md:text-5xl font-bold leading-tight">
          Jasa Maintenance AC Rutin untuk Kantor & Gedung
        </h1>
        <p class="mt-4 text-lg text-gray-600">
          Monitoring, penjadwalan, dan laporan digital—semua dalam satu sistem.
        </p>
        <div class="mt-6 flex gap-3">
          <a href="/login"
   onclick="window.location.assign('/login'); return false;"
   target="_self"
   rel="external"
   class="rounded-xl bg-indigo-600 text-white px-5 py-3 hover:bg-indigo-500">
  Masuk untuk Kelola
</a>

          <a href="#features" class="rounded-xl border px-5 py-3 hover:bg-gray-100">Lihat Kelebihan</a>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow p-6">
        <div class="grid grid-cols-3 gap-4">
          <div class="p-4 rounded-xl bg-indigo-50">
            <div class="text-2xl font-bold">99%</div>
            <div class="text-sm text-gray-600">On-time Schedule</div>
          </div>
          <div class="p-4 rounded-xl bg-emerald-50">
            <div class="text-2xl font-bold">24/7</div>
            <div class="text-sm text-gray-600">Support</div>
          </div>
          <div class="p-4 rounded-xl bg-amber-50">
            <div class="text-2xl font-bold">1000+</div>
            <div class="text-sm text-gray-600">Unit Terawat</div>
          </div>
        </div>
        <img alt="hero" class="mt-6 rounded-xl"
             src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=1200&auto=format&fit=crop" />
      </div>
    </div>
  </section>

  <section id="features" class="max-w-7xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-semibold">Kelebihan Perusahaan</h2>
    <div class="mt-6 grid md:grid-cols-3 gap-6">
      <div class="p-6 rounded-2xl border bg-white"><h3 class="font-semibold">Penjadwalan Otomatis</h3><p class="text-gray-600 mt-2">Reminder H-7, reschedule fleksibel, konfirmasi via email.</p></div>
      <div class="p-6 rounded-2xl border bg-white"><h3 class="font-semibold">Teknisi Tersertifikasi</h3><p class="text-gray-600 mt-2">Monitoring performa & histori pekerjaan lengkap.</p></div>
      <div class="p-6 rounded-2xl border bg-white"><h3 class="font-semibold">Laporan Digital</h3><p class="text-gray-600 mt-2">Foto, catatan servis, tanda tangan digital.</p></div>
    </div>
  </section>

  <section id="docs" class="max-w-7xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-semibold">Dokumentasi</h2>
    <div class="mt-6 grid sm:grid-cols-2 md:grid-cols-3 gap-4">
      @for ($i=0; $i<6; $i++)
        <img class="rounded-xl"
          src="https://images.unsplash.com/photo-1581092921461-eab62e97a780?q=80&w=800&auto=format&fit=crop" alt="doc">
      @endfor
    </div>
  </section>

  <section id="testi" class="max-w-7xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-semibold">Testimoni</h2>
    <div class="mt-6 grid md:grid-cols-3 gap-6">
      <div class="p-6 rounded-2xl border bg-white"><p class="italic">“Penjadwalan rapi, laporan jelas.”</p><div class="mt-3 text-sm text-gray-500">— Budi</div></div>
      <div class="p-6 rounded-2xl border bg-white"><p class="italic">“Teknisi sigap dan rapi.”</p><div class="mt-3 text-sm text-gray-500">— Sari</div></div>
      <div class="p-6 rounded-2xl border bg-white"><p class="italic">“Histori perawatan online.”</p><div class="mt-3 text-sm text-gray-500">— Dimas</div></div>
    </div>
  </section>

  <section id="contact" class="max-w-3xl mx-auto px-4 py-16">
    <h2 class="text-2xl font-semibold">Kontak Admin</h2>
    <form class="mt-6 grid gap-4" method="POST" action="#">
      <input class="border rounded-xl p-3" placeholder="Nama Anda">
      <input class="border rounded-xl p-3" placeholder="Email/No. HP">
      <textarea class="border rounded-xl p-3" rows="4" placeholder="Pesan Anda"></textarea>
      <button type="button" class="rounded-xl bg-indigo-600 text-white px-5 py-3 hover:bg-indigo-500">
        Kirim (demo)
      </button>
      <p class="text-sm text-gray-500">Form demo—nanti bisa diarahkan ke email/WhatsApp admin.</p>
    </form>
  </section>
</div>
