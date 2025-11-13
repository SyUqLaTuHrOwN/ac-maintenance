<div>
  <!-- HERO -->
  <section 
    id="home"
    class="relative h-[650px] flex flex-col items-center justify-center text-center text-white overflow-hidden bg-fixed bg-center bg-cover"
    style="background-image: url('https://png.pngtree.com/thumb_back/fh260/back_our/20190621/ourmid/pngtree-summer-refrigerated-air-conditioning-banner-background-image_194711.jpg');">
    
    <!-- Overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-transparent"></div>

    <!-- Konten -->
    <div class="relative z-10 max-w-3xl px-4" data-aos="fade-up" data-fade>
      <h1 class="text-4xl md:text-5xl font-bold leading-tight drop-shadow-lg">
        Servis AC Cepat & Profesional untuk Kantor & Gedung
      </h1>
      <p class="mt-4 text-lg text-gray-200 drop-shadow-sm">
        Teknisi tersertifikasi, laporan digital, dan penjadwalan otomatis.
      </p>
      <div class="mt-6 flex flex-wrap justify-center gap-3">
        <a href="/login"
           class="rounded-xl bg-indigo-600 text-white px-6 py-3 hover:bg-indigo-500 transition transform hover:scale-105 duration-300 hover-glow">
          Masuk untuk Kelola
        </a>
        <a href="#features" class="scroll-link rounded-xl border border-white text-white px-6 py-3 hover:bg-white hover:text-indigo-600 transition transform hover:scale-105 duration-300 hover-glow">
          Lihat Kelebihan
        </a>
      </div>
    </div>

    <!-- Panah Scroll -->
    <div class="absolute bottom-8 z-10 animate-bounce">
      <a href="#features" class="scroll-link text-white text-3xl">⬇</a>
    </div>
  </section>

  <!-- KELEBIHAN -->
  <section id="features" class="max-w-7xl mx-auto px-4 py-20">
    <h2 class="text-3xl font-semibold text-center" data-aos="fade-up" data-fade>Kelebihan Perusahaan</h2>
    <div class="mt-10 grid md:grid-cols-3 gap-6">
      <div data-aos="zoom-in" data-fade class="p-6 rounded-2xl border bg-white hover:shadow-lg hover:-translate-y-1 transition">
        <h3 class="font-semibold text-lg">Penjadwalan Otomatis</h3>
        <p class="text-gray-600 mt-2">Reminder H-7, reschedule fleksibel, dan konfirmasi via email.</p>
      </div>
      <div data-aos="zoom-in" data-fade class="p-6 rounded-2xl border bg-white hover:shadow-lg hover:-translate-y-1 transition">
        <h3 class="font-semibold text-lg">Teknisi Tersertifikasi</h3>
        <p class="text-gray-600 mt-2">Monitoring performa & histori pekerjaan lengkap.</p>
      </div>
      <div data-aos="zoom-in" data-fade class="p-6 rounded-2xl border bg-white hover:shadow-lg hover:-translate-y-1 transition">
        <h3 class="font-semibold text-lg">Laporan Digital</h3>
        <p class="text-gray-600 mt-2">Foto, catatan servis, dan tanda tangan digital tersedia online.</p>
      </div>
    </div>
  </section>

  <!-- DOKUMENTASI -->
  <section id="docs" class="max-w-7xl mx-auto px-4 py-20">
    <h2 class="text-3xl font-semibold text-center" data-aos="fade-up" data-fade>Dokumentasi Lapangan</h2>
    <div class="mt-10 grid sm:grid-cols-2 md:grid-cols-3 gap-4">
      @for ($i=0; $i<6; $i++)
        <img data-aos="fade-up" data-fade class="rounded-xl object-cover aspect-video hover:scale-[1.02] transition-all duration-300"
          src="https://images.unsplash.com/photo-1581092921461-eab62e97a780?q=80&w=800&auto=format&fit=crop" alt="doc">
      @endfor
    </div>
  </section>

  <!-- TESTIMONI -->
  <section id="testi" class="max-w-7xl mx-auto px-4 py-20">
    <h2 class="text-3xl font-semibold text-center" data-aos="fade-up" data-fade>Testimoni Klien</h2>
    <div class="mt-10 swiper mySwiper" data-aos="fade-up">
      <div class="swiper-wrapper">
        <div class="swiper-slide p-6 rounded-2xl border bg-white hover:shadow-lg hover:-translate-y-1 transition">
          <p class="italic text-lg">“Penjadwalan rapi, laporan jelas.”</p>
          <div class="mt-3 text-sm text-gray-500">— Budi</div>
        </div>
        <div class="swiper-slide p-6 rounded-2xl border bg-white hover:shadow-lg hover:-translate-y-1 transition">
          <p class="italic text-lg">“Teknisi sigap dan rapi.”</p>
          <div class="mt-3 text-sm text-gray-500">— Sari</div>
        </div>
        <div class="swiper-slide p-6 rounded-2xl border bg-white hover:shadow-lg hover:-translate-y-1 transition">
          <p class="italic text-lg">“Histori perawatan online sangat membantu.”</p>
          <div class="mt-3 text-sm text-gray-500">— Dimas</div>
        </div>
      </div>
    </div>
  </section>

  <!-- KONTAK -->
  <section id="contact" class="max-w-3xl mx-auto px-4 py-20">
    <h2 class="text-3xl font-semibold text-center" data-aos="fade-up" data-fade>Kontak Admin</h2>
    <form class="mt-10 grid gap-4" method="POST" action="#" data-aos="fade-up">
      <input class="border rounded-xl p-3" placeholder="Nama Anda">
      <input class="border rounded-xl p-3" placeholder="Email/No. HP">
      <textarea class="border rounded-xl p-3" rows="4" placeholder="Pesan Anda"></textarea>
      <button type="button" class="rounded-xl bg-indigo-600 text-white px-5 py-3 hover:bg-indigo-500 transition hover-glow">
        Kirim (demo)
      </button>
      <p class="text-sm text-gray-500 text-center mt-2">
        Form demo — nanti bisa diarahkan ke email/WhatsApp admin.
      </p>
    </form>
  </section>

  <!-- FLOATING WA -->
  <a href="https://wa.me/6281234567890" target="_blank"
     class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg hover:scale-110 transition text-xl float">
     💬
  </a>

  <!-- SCROLL TO TOP -->
  <button id="toTopBtn" class="hidden fixed bottom-20 right-6 bg-indigo-600 text-white p-3 rounded-full shadow-lg hover:bg-indigo-500 transition text-xl">
    ↑
  </button>

  @push('scripts')
  <style>
    [data-fade] {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.7s ease-out;
    }
    [data-fade].show {
      opacity: 1;
      transform: translateY(0);
    }
    .hover-glow:hover {
      box-shadow: 0 0 15px rgba(99, 102, 241, 0.6);
    }
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }
    .float { animation: float 3s ease-in-out infinite; }
  </style>

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    // ===== SMOOTH SCROLL SUPER HALUS =====
    function smoothScrollTo(targetY, duration = 1000) {
      const startY = window.pageYOffset;
      const diff = targetY - startY;
      let start;
      function easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
      }
      function step(timestamp) {
        if (!start) start = timestamp;
        const time = timestamp - start;
        const progress = Math.min(time / duration, 1);
        const eased = easeInOutCubic(progress);
        window.scrollTo(0, startY + diff * eased);
        if (time < duration) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    }

    // Scroll link listener
    document.querySelectorAll('.scroll-link').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const target = document.querySelector(link.getAttribute('href'));
        if (target) {
          smoothScrollTo(target.offsetTop - 60, 1000);
        }
      });
    });

    // Fade-in observer
    const fades = document.querySelectorAll('[data-fade]');
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('show');
      });
    });
    fades.forEach(fade => observer.observe(fade));

    // Scroll to top
    const toTopBtn = document.getElementById('toTopBtn');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 400) toTopBtn.classList.remove('hidden');
      else toTopBtn.classList.add('hidden');
    });
    toTopBtn.addEventListener('click', () => smoothScrollTo(0, 800));

    // Highlight aktif navbar
    const sections = document.querySelectorAll('section[id]');
    window.addEventListener('scroll', () => {
      const scrollY = window.pageYOffset;
      sections.forEach(current => {
        const sectionTop = current.offsetTop - 100;
        const sectionHeight = current.offsetHeight;
        const sectionId = current.getAttribute('id');
        const navLink = document.querySelector('a[href="#' + sectionId + '"]');
        if (navLink) {
          if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            navLink.classList.add('text-indigo-600', 'font-semibold');
          } else {
            navLink.classList.remove('text-indigo-600', 'font-semibold');
          }
        }
      });
    });

    // Parallax
    window.addEventListener('scroll', () => {
      const hero = document.querySelector('#home');
      const offset = window.pageYOffset;
      hero.style.backgroundPositionY = offset * 0.4 + 'px';
    });

    // Init AOS + Swiper
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
