<div>
  <!-- HERO -->
  <section class="relative h-[600px] flex items-center justify-center text-center text-white">
    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
      <source src="/videos/4dd6c240-0642-4b18-a8c9-0ffa1575e353.mp4" type="video/mp4">
    </video>
    <div class="absolute inset-0 bg-black/60"></div>
    <div class="relative z-10 max-w-3xl px-4" data-aos="fade-up">
      <h1 class="text-4xl md:text-5xl font-bold leading-tight">
        Servis AC Cepat & Profesional untuk Kantor & Gedung
      </h1>
      <p class="mt-4 text-lg text-gray-200">
        Teknisi tersertifikasi, laporan digital, dan penjadwalan otomatis.
      </p>
      <div class="mt-6 flex flex-wrap justify-center gap-3">
        <a href="/login"
           class="rounded-xl bg-indigo-600 text-white px-6 py-3 hover:bg-indigo-500 transition">
          Masuk untuk Kelola
        </a>
        <a href="#features" class="rounded-xl border border-white text-white px-6 py-3 hover:bg-white hover:text-indigo-600 transition">
          Lihat Kelebihan
        </a>
      </div>
    </div>
  </section>

  <!-- KEUNGGULAN -->
  <section id="features" class="max-w-7xl mx-auto px-4 py-20">
    <h2 class="text-3xl font-semibold text-center" data-aos="fade-up">Kelebihan Perusahaan</h2>
    <div class="mt-10 grid md:grid-cols-3 gap-6">
      <div data-aos="zoom-in" class="p-6 rounded-2xl border bg-white hover:shadow-lg hover:-translate-y-1 transition">
        <h3 class="font-semibold text-lg">Penjadwalan Otomatis</h3>
        <p class="text-gray-600 mt-2">Reminder H-7, reschedule fleksibel, dan konfirmasi via email.</p>
      </div>
      <div data-aos="zoom-in" class="p-6 rounded-2xl border bg-white hover:shadow-lg hover:-translate-y-1 transition">
        <h3 class="font-semibold text-lg">Teknisi Tersertifikasi</h3>
        <p class="text-gray-600 mt-2">Monitoring performa & histori pekerjaan lengkap.</p>
      </div>
      <div data-aos="zoom-in" class="p-6 rounded-2xl border bg-white hover:shadow-lg hover:-translate-y-1 transition">
        <h3 class="font-semibold text-lg">Laporan Digital</h3>
        <p class="text-gray-600 mt-2">Foto, catatan servis, dan tanda tangan digital tersedia online.</p>
      </div>
    </div>
  </section>

  <!-- DOKUMENTASI -->
  <section id="docs" class="max-w-7xl mx-auto px-4 py-20">
    <h2 class="text-3xl font-semibold text-center" data-aos="fade-up">Dokumentasi Lapangan</h2>
    <div class="mt-10 grid sm:grid-cols-2 md:grid-cols-3 gap-4">
      @for ($i=0; $i<6; $i++)
        <img data-aos="fade-up" class="rounded-xl object-cover aspect-video"
          src="https://images.unsplash.com/photo-1581092921461-eab62e97a780?q=80&w=800&auto=format&fit=crop" alt="doc">
      @endfor
    </div>
  </section>

  <!-- TESTIMONI -->
  <section id="testi" class="max-w-7xl mx-auto px-4 py-20">
    <h2 class="text-3xl font-semibold text-center" data-aos="fade-up">Testimoni Klien</h2>
    <div class="mt-10 swiper mySwiper" data-aos="fade-up">
      <div class="swiper-wrapper">
        <div class="swiper-slide p-6 rounded-2xl border bg-white">
          <p class="italic text-lg">“Penjadwalan rapi, laporan jelas.”</p>
          <div class="mt-3 text-sm text-gray-500">— Budi</div>
        </div>
        <div class="swiper-slide p-6 rounded-2xl border bg-white">
          <p class="italic text-lg">“Teknisi sigap dan rapi.”</p>
          <div class="mt-3 text-sm text-gray-500">— Sari</div>
        </div>
        <div class="swiper-slide p-6 rounded-2xl border bg-white">
          <p class="italic text-lg">“Histori perawatan online sangat membantu.”</p>
          <div class="mt-3 text-sm text-gray-500">— Dimas</div>
        </div>
      </div>
    </div>
  </section>

  <!-- KONTAK -->
  <section id="contact" class="max-w-3xl mx-auto px-4 py-20">
    <h2 class="text-3xl font-semibold text-center" data-aos="fade-up">Kontak Admin</h2>
    <form class="mt-10 grid gap-4" method="POST" action="#" data-aos="fade-up">
      <input class="border rounded-xl p-3" placeholder="Nama Anda">
      <input class="border rounded-xl p-3" placeholder="Email/No. HP">
      <textarea class="border rounded-xl p-3" rows="4" placeholder="Pesan Anda"></textarea>
      <button type="button" class="rounded-xl bg-indigo-600 text-white px-5 py-3 hover:bg-indigo-500 transition">
        Kirim (demo)
      </button>
      <p class="text-sm text-gray-500 text-center mt-2">
        Form demo — nanti bisa diarahkan ke email/WhatsApp admin.
      </p>
    </form>
  </section>

  <!-- FLOATING WA -->
  <a href="https://wa.me/6281234567890" target="_blank"
     class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg hover:scale-110 transition text-xl">
     💬
  </a>

  @push('scripts')
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    AOS.init({ duration: 800, once: true });
    new Swiper('.mySwiper', {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      autoplay: { delay: 3000 },
      breakpoints: { 768: { slidesPerView: 3 } },
    });
  </script>
  @endpush
</div>
