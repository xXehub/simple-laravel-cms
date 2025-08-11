<x-layout.app title="{{ setting('welcome_title', 'Laravel Superapp CMS') }}" :pakai-sidebar="false" :pakaiFluid="false"
    :pakaiTopBar="false" :hasDivPage="true" bodyClass="body-marketing body-gradient">

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
            document.addEventListener("DOMContentLoaded", function() {
                AOS.init({
                    once: false, // animasi bisa diputar ulang
                    duration: 600, // durasi animasi (opsional)
                    easing: 'ease-in-out' // efek transisi (opsional)
                });
            });
        </script>

        {{-- [DONE] start - ini untuk header --}}
        <header class="hero">
            <div class="container">
                <div class="row g-8 align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <div class="hero-subheader">{{ setting('landing_hero_subheader', 'Selamat Datang di') }}</div>
                        <h1 class="hero-title">
                            {{ setting('app_name', 'Super App Dinkominfo') }},<br />
                            <span class="text-primary" id="typed">satu portal</span>
                        </h1>
                        <p class="hero-description mt-4">
                            {{ setting('app_description', 'Portal layanan terintegrasi untuk mengakses seluruh aplikasi dan informasi dari dinas dan OPD di lingkungan pemerintahan daerah.') }}
                        </p>
                        <div class="mt-3 mt-lg-3">
                            <div class="row justify-content-center justify-content-md-start">
                                <div class="col-auto"><a href="{{ route('register') }}"
                                        class="btn fw-semibold px-3 py-2 border">{{ setting('landing_btn_register', 'Daftar') }}</a>
                                </div>
                                <div class="col-auto"><a href="{{ route('login') }}"
                                        class="btn btn-primary px-3 py-2">{{ setting('landing_btn_login', 'Masuk') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hero-img-side">
                            <div id="carousel-controls" class="carousel slide" data-bs-ride="carousel"
                                data-interval="4000">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img class="img d-block mx-auto"
                                            src="{{ setting_image('landing_hero_image_1', '/static/illustrations/boy-with-key.svg') }}"
                                            alt="Hero Image 1" height="350"
                                            style="max-width: 100%; height: 350px; object-fit: contain;">
                                    </div>
                                    <div class="carousel-item">
                                        <img class="img d-block mx-auto"
                                            src="{{ setting_image('landing_hero_image_2', '/static/illustrations/boy-girl.svg') }}"
                                            alt="Hero Image 2" height="350"
                                            style="max-width: 100%; height: 350px; object-fit: contain;">
                                    </div>
                                    <div class="carousel-item">
                                        <img class="img d-block mx-auto"
                                            src="{{ setting_image('landing_hero_image_3', '/static/illustrations/growth.svg') }}"
                                            alt="Hero Image 3" height="350"
                                            style="max-width: 100%; height: 350px; object-fit: contain;">
                                    </div>
                                </div>
                                <a class="carousel-control-prev text-secondary" href="#carousel-controls" role="button"
                                    data-bs-slide="prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-md icon-2">
                                        <path d="M15 6l-6 6l6 6" />
                                    </svg>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next text-secondary" href="#carousel-controls" role="button"
                                    data-bs-slide="next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-md icon-2">
                                        <path d="M9 6l6 6l-6 6" />
                                    </svg>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        </header>
        {{-- [DONE] end - header sampe sini @jeje --}}

        {{-- [DONE]  start - masuk ke section 2 --}}
        <section class="section py-2">
            <div class="container">
                <div class="row items-center text-center g-4">
                    {{-- card 1 --}}
                    <div class="col-md-6 col-lg-3 mb-4">
                        <a href="#" class="card bg-surface-secondary">
                            <!-- Photo -->
                            <div class="img-responsive img-responsive-21x9 card-img-top"
                                style="background-image: url({{ setting_image('landing_card_1_image', './static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg') }})">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">{{ setting('landing_card_1_title', 'Card with top image') }}
                                </h3>
                                <p class="text-secondary">
                                    {{ setting('landing_card_1_description', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima neque pariatur perferendis sed suscipit velit vitae voluptatem.') }}
                                </p>
                            </div>
                        </a>
                    </div>
                    {{-- card 2 --}}
                    <div class="col-md-6 col-lg-3 mb-4">
                        <a href="#" class="card bg-surface-secondary">
                            <!-- Photo -->
                            <div class="img-responsive img-responsive-21x9 card-img-top"
                                style="background-image: url({{ setting_image('landing_card_2_image', './static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg') }})">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">{{ setting('landing_card_2_title', 'Card with top image') }}
                                </h3>
                                <p class="text-secondary">
                                    {{ setting('landing_card_2_description', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima neque pariatur perferendis sed suscipit velit vitae voluptatem.') }}
                                </p>
                            </div>
                        </a>
                    </div>
                    {{-- card 3 --}}
                    <div class="col-md-6 col-lg-3 mb-4">
                        <a href="#" class="card bg-surface-secondary">
                            <!-- Photo -->
                            <div class="img-responsive img-responsive-21x9 card-img-top"
                                style="background-image: url({{ setting_image('landing_card_3_image', './static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg') }})">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">{{ setting('landing_card_3_title', 'Card with top image') }}
                                </h3>
                                <p class="text-secondary">
                                    {{ setting('landing_card_3_description', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima neque pariatur perferendis sed suscipit velit vitae voluptatem.') }}
                                </p>
                            </div>
                        </a>
                    </div>
                    {{-- card 4 --}}
                    <div class="col-md-6 col-lg-3 mb-4">
                        <a href="#" class="card bg-surface-secondary">
                            <!-- Photo -->
                            <div class="img-responsive img-responsive-21x9 card-img-top"
                                style="background-image: url({{ setting_image('landing_card_4_image', './static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg') }})">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">{{ setting('landing_card_4_title', 'Card with top image') }}
                                </h3>
                                <p class="text-secondary">
                                    {{ setting('landing_card_4_description', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima neque pariatur perferendis sed suscipit velit vitae voluptatem.') }}
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        {{-- [DONE] end - kelar section 2 --}}

        {{-- [DONE] start - masuk ke section 3 --}}
        <section class="section section-light py-4 mt-6 ">
            <div class="text-center px-4 px-md-6" data-aos="fade-up">
                <h1 class="hero-title">Tentang {{ setting('app_name', 'Super App') }}</h1>
                <p class="hero-description hero-description-wide mx-auto" style="max-width: 720px;">
                    {{ setting('app_description', 'SuperApp adalah platform layanan digital terpadu yang dirancang untuk memudahkan masyarakat dalam mengakses berbagai layanan publik dari instansi pemerintahan di Kota Surabaya. Pengguna dapat menjelajahi, mengajukan, dan memantau layanan dari dinas seperti Pendidikan, Kominfo, Kesehatan, hingga Perizinan.') }}
                </p>
            </div>
        </section>

        {{-- [DONE] end - kelar section 3 --}}

        {{-- [DONE] start - masuk section 4 --}}
        <section class="section py-8">
            <div class="container">
                <div class="row gx-3">
                    {{-- Baris pertama --}}
                    <div class="col mb-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="bg-cover rounded"
                            style="height: 15rem; background-image: url('{{ setting_image('landing_gallery_image_1', 'https://picsum.photos/400/240?random=1') }}'); background-size: cover; background-position: center;">
                        </div>
                    </div>
                    <div class="col-3 d-none d-md-block mb-3" data-aos="zoom-in" data-aos-delay="200">
                        <div class="bg-cover rounded"
                            style="height: 15rem; background-image: url('{{ setting_image('landing_gallery_image_2', 'https://picsum.photos/400/240?random=2') }}'); background-size: cover; background-position: center;">
                        </div>
                    </div>
                    <div class="col mb-3" data-aos="fade-left" data-aos-delay="300">
                        <div class="bg-cover rounded"
                            style="height: 15rem; background-image: url('{{ setting_image('landing_gallery_image_3', 'https://picsum.photos/400/240?random=3') }}'); background-size: cover; background-position: center;">
                        </div>
                    </div>

                    <div class="w-100"></div> {{-- Baris baru --}}

                    {{-- Baris kedua --}}
                    {{-- Baris kedua --}}
                    <div class="col mb-3 mb-md-0" data-aos="flip-left" data-aos-delay="100">
                        <div class="bg-cover rounded"
                            style="height: 15rem; background-image: url('{{ setting_image('landing_gallery_image_4', 'https://picsum.photos/400/240?random=4') }}'); background-size: cover; background-position: center;">
                        </div>
                    </div>
                    <div class="col-4 d-none d-md-block mb-3 mb-md-0" data-aos="zoom-in-up" data-aos-delay="200">
                        <div class="bg-cover rounded"
                            style="height: 15rem; background-image: url('{{ setting_image('landing_gallery_image_5', 'https://picsum.photos/400/240?random=5') }}'); background-size: cover; background-position: center;">
                        </div>
                    </div>
                    <div class="col" data-aos="fade-up-right" data-aos-delay="300">
                        <div class="bg-cover rounded"
                            style="height: 15rem; background-image: url('{{ setting_image('landing_gallery_image_6', 'https://picsum.photos/400/240?random=6') }}'); background-size: cover; background-position: center;">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    {{-- [DONE] end - kelar section 4 --}}

    {{-- [DONE] start - masuk section 5 --}}
    <section class="section section-light py-4 mb-8">
        <div class="container">
            <div class="row g-4 align-items-start">
                {{-- Kolom Kiri --}}
                <div class="col-lg-4 d-flex flex-column justify-content-between" data-aos="fade-up"
                    data-aos-delay="100">
                    <div>
                        <h2 class="fw-bold mb-2">{{ setting('landing_faq_title', 'Pertanyaan yang Sering Diajukan') }}
                        </h2>
                        <p class="text-muted mb-2">
                            {!! setting(
                                'landing_faq_subtitle',
                                'Tidak menemukan jawaban yang Anda butuhkan?<br>Hubungi tim pengelola sistem.',
                            ) !!}
                        </p>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="btn btn-primary fw-semibold px-4 py-2">
                            {{ setting('landing_faq_btn_text', '📖 Baca Selengkapnya') }}
                        </a>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="col-md-8">
                    <div class="accordion accordion-tabs" id="accordion-tabs">
                        @foreach ([1, 2, 3, 4] as $i)
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent text-body fw-semibold"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $i }}-tabs" aria-expanded="false">
                                        {{ setting("landing_faq_{$i}_question", "Pertanyaan contoh nomor {$i}") }}
                                        <div class="accordion-button-toggle ms-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-1">
                                                <path d="M6 9l6 6l6 -6"></path>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                                <div id="collapse-{{ $i }}-tabs" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-tabs">
                                    <div class="accordion-body">
                                        {{ setting("landing_faq_{$i}_answer", "Ini adalah jawaban untuk pertanyaan nomor {$i}.") }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>
    {{-- [DONE] end - kelar section 5 --}}

    {{-- masuk ke footer --}}
    <footer class="footer mt-4 pt-4">
        <div class="container">
            <div class="py-3">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="row g-4">
                            <div class="col-md">
                                <div class="subheader mb-3">{{ setting('app_name', 'Super App Dinkominfo') }}</div>
                                <ul class="list-unstyled list-separated gap-2">
                                    <li><a class="link-secondary" href="">Alamat:</a></li>
                                    <li>
                                        <a class="link-secondary"
                                            href="">{{ setting('contact_address', 'Jl. Jimerto No. 25-27 Lantai 5 Surabaya, Jawa Timur 60272') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md">
                                <div class="subheader mb-3">Pengaduan</div>
                                <ul class="list-unstyled list-separated gap-2">
                                    <li><a class="link-secondary" href="">Email:</a></li>
                                    <li><a class="link-secondary"
                                            href="">{{ setting('contact_email', 'dinkominfo@surabaya.go.id') }}</a>
                                    </li>
                                    <li><a class="link-secondary"
                                            href="">{{ setting('contact_email_2', 'mediacenter@surabaya.go.id') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md">
                                <div class="subheader mb-3">Kontak Kami</div>
                                <ul class="list-unstyled list-separated gap-2">
                                    <li><a class="link-secondary" href="">Telepon:</a></li>
                                    <li><a class="link-secondary"
                                            href="">{{ setting('contact_phone', '(031) 99277339') }}</a></li>
                                    <li><a class="link-secondary"
                                            href="">{{ setting('contact_phone_2', '(031) 5312144 Psw. 384 / 241') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 ml-auto">
                        <div class="text-secondary">
                            {{ setting('footer_description', 'Platform digital terpadu untuk layanan pemerintahan yang lebih baik dan transparan.') }}
                        </div>
                    </div>
                </div>
                <div class="row g-4 justify-content-between mt-0 mt-md-2">
                    @if(setting('landing_supported_icon_image'))
                        <div class="col-sm-7 col-md-6 col-lg-4">
                            <h5 class="subheader">{{ setting('landing_footer_supported_title', 'Supported by:') }}</h5>
                            <div class="mt-3">
                                <a href="{{ setting('landing_supported_icon_url', '#') }}" class="d-inline-block">
                                    <img src="{{ setting_image('landing_supported_icon_image') }}" 
                                         alt="Supported by" 
                                         style="height: 40px; width: auto; opacity: 0.8; transition: opacity 0.3s;"
                                         onmouseover="this.style.opacity='1'" 
                                         onmouseout="this.style.opacity='0.8'">
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-5 col-md-6 col-lg-3 text-sm-end">
                        <h5 class="subheader">{{ setting('landing_footer_social_title', 'Ikuti Media Sosial Kami:') }}</h5>
                        <ul class="list-inline mb-0 mt-3">
                            <li class="list-inline-item">
                                <a class="btn btn-icon btn-facebook"
                                    href="{{ setting('social_facebook', '#') }}"><!-- Download SVG icon from http://tabler.io/icons/icon/brand-facebook -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path
                                            d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                                    </svg></a>
                            </li>
                            <li class="list-inline-item">
                                <a class="btn btn-icon btn-instagram"
                                    href="{{ setting('social_instagram', '#') }}"><!-- Download SVG icon from http://tabler.io/icons/icon/brand-instagram -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path
                                            d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        <path d="M16.5 7.5v.01" />
                                    </svg></a>
                            </li>
                            <li class="list-inline-item">
                                <a class="btn btn-icon btn-twitter"
                                    href="{{ setting('social_twitter', '#') }}"><!-- Download SVG icon from http://tabler.io/icons/icon/brand-twitter -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path
                                            d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z" />
                                    </svg></a>
                            </li>
                            <li class="list-inline-item">
                                <a class="btn btn-icon btn-linkedin"
                                    href="{{ setting('social_youtube', '#') }}"><!-- Download SVG icon from http://tabler.io/icons/icon/brand-youtube -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                        <path d="M8 11v5" />
                                        <path d="M8 8v.01" />
                                        <path d="M12 16v-5" />
                                        <path d="M16 16v-3a2 2 0 1 0 -4 0" />
                                        <path
                                            d="M3 7a4 4 0 0 1 4 -4h10a4 4 0 0 1 4 4v10a4 4 0 0 1 -4 4h-10a4 4 0 0 1 -4 -4z" />
                                    </svg></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--  END FOOTER  -->

    {{-- Script untuk typed.js --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var typedStrings = {!! setting('landing_typed_strings', '["satu portal", "semua dinas", "super mudah"]') !!};
            var typed = new Typed("#typed", {
                strings: typedStrings,
                typeSpeed: {{ setting('landing_typed_speed', '100') }},
                backSpeed: {{ setting('landing_typed_back_speed', '50') }},
                backDelay: {{ setting('landing_typed_back_delay', '1000') }},
                startDelay: {{ setting('landing_typed_start_delay', '1000') }},
                loop: true,
                fade: true,
            });
        });
    </script>
</x-layout.app>
