<x-layout.app title="{{ setting('welcome_title', 'Laravel Superapp CMS') }}" :pakai-sidebar="false" :pakaiFluid="false" :pakaiTopBar="false">

  <!-- Tambah AOS animate-on-scroll -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <style>
    .card:hover {
      transform: scale(1.05);
      transition: transform 0.3s ease;
      z-index: 1;
    }
    .faq-section {
      background: url('/storage/bg-pattern.png') no-repeat center top;
      background-size: cover;
      border-top: 1px solid #e0e0e0;
    }
    .accordion-button:not(.collapsed) {
      background-color: #0056b3;
      color: white;
    }
    .accordion-button::after {
      transition: transform 0.2s ease-in-out;
    }
    .accordion-button:not(.collapsed)::after {
      transform: rotate(180deg);
    }
    .accordion-body {
      padding: 1rem 1.5rem;
    }
    .btn-faq-more {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 500;
      background-color: #f5f5f5;
      color: #0056b3;
      border: 1px solid #d0d0d0;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .btn-faq-more:hover {
      background-color: #0056b3;
      color: white;
    }
  </style>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      AOS.init({
        once: false,         // animasi bisa diputar ulang
        duration: 600,       // durasi animasi (opsional)
        easing: 'ease-in-out' // efek transisi (opsional)
        });
    });
  </script>

  <header class="hero">
    <div class="container">
      <div class="row g-8 align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <div class="hero-subheader">Selamat Datang di</div>
          <h1 class="hero-title">
            Super App Dinkominfo,<br />
            <span class="text-primary" id="typed">satu portal</span>
          </h1>
          <p class="hero-description mt-4">
            Portal layanan terintegrasi untuk mengakses seluruh aplikasi dan informasi dari dinas dan OPD di lingkungan pemerintahan daerah.
          </p>
          <div class="mt-3 mt-lg-3">
            <div class="row justify-content-center justify-content-md-start">
              <div class="col-auto"><a href="{{ route('register') }}" class="btn fw-semibold px-3 py-2 border">Daftar</a></div>
              <div class="col-auto"><a href="{{ route('login') }}" class="btn btn-primary px-3 py-2">Masuk</a></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
        <div class="hero-img-side">
            <div id="carousel-controls" class="carousel slide" data-bs-ride="carousel">

            <!-- Slide Indicators -->
            <div class="carousel-indicators mb-0">
                <button type="button" data-bs-target="#carousel-controls" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carousel-controls" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carousel-controls" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <!-- Gambar Slide -->
            <div class="carousel-inner rounded shadow-sm">
                <div class="carousel-item active">
                <img src="https://picsum.photos/600/400?random=1" class="d-block w-100" alt="Gambar Slide 1">
                </div>
                <div class="carousel-item">
                <img src="https://picsum.photos/600/400?random=2" class="d-block w-100" alt="Gambar Slide 2">
                </div>
                <div class="carousel-item">
                <img src="https://picsum.photos/600/400?random=3" class="d-block w-100" alt="Gambar Slide 3">
                </div>
            </div>
            </div>
            <div class="text-center text-muted small mt-2">Gambar dapat diganti sesuai kebutuhan halaman utama</div>
        </div>
        </div>

    </div>
  </header>

  <div class="page-wrapper">
  <div class="container my-5">
    <div class="row g-4">
      @foreach (range(1, 4) as $i)
        <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
          <div class="card card-link card-link-pop h-100">
            <div class="card-cover card-cover-blurred text-center" style="background-image: url('/static/photos/home-office.jpg'); min-height: 160px;">
              <!-- Tambahkan overlay opsional -->
              <span class="avatar avatar-xl bg-white text-primary shadow-sm mt-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-archive" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z"/>
                  <rect x="3" y="4" width="18" height="4" rx="2" />
                  <path d="M5 8v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1 -1v-12" />
                  <line x1="10" y1="12" x2="14" y2="12" />
                </svg>
              </span>
            </div>
            <div class="card-body text-center">
              <div class="card-title fw-bold">Layanan {{ $i }}</div>
              <div class="text-muted text-sm">Deskripsi singkat tentang layanan dari Dinas terkait.</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

    <div class="hero bg-light py-5 mt-5 mb-4">
      <div class="container text-center" data-aos="fade-up">
        <h1 class="hero-title">Tentang Super App</h1>
        <p class="hero-description hero-description-wide">
          SuperApp adalah platform layanan digital terpadu yang dirancang untuk memudahkan masyarakat dalam mengakses berbagai layanan publik dari
          instansi pemerintahan di Kota Surabaya. Pengguna dapat menjelajahi, mengajukan, dan memantau layanan dari dinas seperti Pendidikan, Kominfo,
          Kesehatan, hingga Perizinan.
        </p>
      </div>
    </div>

    <section class="faq-section py-5 bg-white border-top">
  <div class="container">
    <div class="row g-5 align-items-start">

      {{-- Kolom Kiri --}}
      <div class="col-lg-4 d-flex flex-column justify-content-between" data-aos="fade-up" data-aos-delay="100">
        <div>
          <h2 class="fw-bold mb-3">Pertanyaan yang Sering Diajukan</h2>
          <p class="text-muted">
            Tidak menemukan jawaban yang Anda butuhkan?<br>
            Hubungi tim pengelola sistem.
          </p>
        </div>
        <div class="mt-4">
          <a href="#" class="btn btn-primary fw-semibold px-4 py-2">
            📖 Baca Selengkapnya
          </a>
        </div>
      </div>

      {{-- Kolom Kanan --}}
      <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
        <div class="card shadow-sm border-0">
          <div class="accordion accordion-flush" id="faqAccordion">

            {{-- Item 1 --}}
            <div class="accordion rounded shadow-sm" id="faqAccordion">
  <div class="accordion-item border-bottom">
    <h2 class="accordion-header">
      <button
        class="accordion-button collapsed fw-semibold"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#faq1"
        aria-expanded="false"
        aria-controls="faq1"
      >
        Siapa saja yang dapat mengakses CMS ini?
      </button>
    </h2>
    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
      <div class="accordion-body">
        CMS ini dapat diakses oleh pengguna yang memiliki akun aktif...
      </div>
    </div>
  </div>

    {{-- Item 2 --}}
  <div class="accordion-item border-bottom">
    <h2 class="accordion-header">
      <button
        class="accordion-button collapsed fw-semibold"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#faq2"
      >
        Saya lupa kata sandi. Apa yang harus saya lakukan?
      </button>
    </h2>
    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
      <div class="accordion-body">
        Gunakan fitur “Lupa Kata Sandi” atau hubungi admin.
      </div>
    </div>
  </div>

  {{-- Item 3 --}}
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button
        class="accordion-button collapsed fw-semibold"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#faq3"
      >
        Apakah sistem ini terintegrasi dengan portal pusat?
      </button>
    </h2>
    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
      <div class="accordion-body">
        Ya, sistem dapat diintegrasikan dengan API dari portal pusat.
      </div>
    </div>
  </div>
</div>

          </div>
        </div>
      </div>

    </div>
  </div>
</section>


    <div class="section pt-5 mt-5">
    <div class="container">
        <div class="row gx-3">
        <div class="col mb-3">
            <div class="bg-cover rounded" style="height: 15rem; background-image: url('https://picsum.photos/400/240?random=1'); background-size: cover; background-position: center;"></div>
        </div>
        <div class="col-3 d-none d-md-block mb-3">
            <div class="bg-cover rounded" style="height: 15rem; background-image: url('https://picsum.photos/400/240?random=2'); background-size: cover; background-position: center;"></div>
        </div>
        <div class="col mb-3">
            <div class="bg-cover rounded" style="height: 15rem; background-image: url('https://picsum.photos/400/240?random=3'); background-size: cover; background-position: center;"></div>
        </div>
        <div class="w-100"></div>
        <div class="col mb-3 mb-md-0">
            <div class="bg-cover rounded" style="height: 15rem; background-image: url('https://picsum.photos/400/240?random=4'); background-size: cover; background-position: center;"></div>
        </div>
        <div class="col-4 d-none d-md-block mb-3 mb-md-0">
            <div class="bg-cover rounded" style="height: 15rem; background-image: url('https://picsum.photos/400/240?random=5'); background-size: cover; background-position: center;"></div>
        </div>
        <div class="col">
            <div class="bg-cover rounded" style="height: 15rem; background-image: url('https://picsum.photos/400/240?random=6'); background-size: cover; background-position: center;"></div>
        </div>
        </div>
    </div>
    </div>

<footer class="footer bg-dark text-white" style="width: 100vw; margin-left: calc(-50vw + 50%);">
  <div class="container-fluid">
    <div class="py-3">
      <div class="row g-4">
        <div class="col-lg-7">
          <div class="row g-4">
            <div class="col-md">
              <div class="subheader mb-3 text-white">Dinas Komunikasi dan Informatika Kota Surabaya</div>
              <ul class="list-unstyled list-separated gap-2">
                <li><a class="text-white" href="">Jl. Jimerto No. 25-27 Lantai 5 Surabaya, Jawa Timur 60272</a></li>
                <li>
                  <a class="text-white" href="">dinkominfo@surabaya.go.id</a>
                </li>
                <li>
                  <a class="text-white" href="">mediacenter@surabaya.go.id</a>
                </li>
              </ul>
            </div>
            <div class="col-md">
              <div class="subheader mb-3 text-white">KONTAK KAMI</div>
              <ul class="list-unstyled list-separated gap-2">
                <li><a class="text-white" href="">Telepon:</a></li>
                <li><a class="text-white" href="">(031) 99277339</a></li>
                <li><a class="text-white" href="">(031) 5312144 Psw. 384 / 241</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row g-4 mt-0 mt-md-2">
        <div class="col-sm-7 col-md-6 col-lg-4">
          <h5 class="subheader text-white">Supported by:</h5>
          <ul class="list-inline mb-0 mt-3">
            <li class="list-inline-item">
              <a href="#"><span class="payment payment-1 payment-provider-paypal"></span></a>
            </li>
            <li class="list-inline-item">
              <a href="#"><span class="payment payment-1 payment-provider-visa"></span></a>
            </li>
            <li class="list-inline-item">
              <a href="#"><span class="payment payment-1 payment-provider-mastercard"></span></a>
            </li>
          </ul>
        </div>
        <div class="col-sm-5 col-md-6 col-lg-3 ms-auto">
        <h5 class="subheader text-white">Follow us on</h5>
        <ul class="list-inline mb-0 mt-3 d-flex gap-2">
            <li class="list-inline-item">
              <a class="btn btn-icon text-white" href="#">
                <!-- Facebook Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                  <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                </svg>
              </a>
            </li>
            <li class="list-inline-item">
              <a class="btn btn-icon text-white" href="#">
                <!-- Instagram Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                  <path d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                  <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                  <path d="M16.5 7.5v.01" />
                </svg>
              </a>
            </li>
            <li class="list-inline-item">
              <a class="btn btn-icon text-white" href="#">
                <!-- Twitter Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                  <path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z" />
                </svg>
              </a>
            </li>
            <li class="list-inline-item">
              <a class="btn btn-icon text-white" href="#">
                <!-- LinkedIn Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                  <path d="M8 11v5" />
                  <path d="M8 8v.01" />
                  <path d="M12 16v-5" />
                  <path d="M16 16v-3a2 2 0 1 0 -4 0" />
                  <path d="M3 7a4 4 0 0 1 4 -4h10a4 4 0 0 1 4 4v10a4 4 0 0 1 -4 4h-10a4 4 0 0 1 -4 -4z" />
                </svg>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      new Typed("#typed", {
        strings: ["satu portal", "semua dinas", "super mudah"],
        typeSpeed: 100,
        backSpeed: 50,
        backDelay: 1000,
        loop: true,
      });
    });
  </script>
</x-layout.app>
