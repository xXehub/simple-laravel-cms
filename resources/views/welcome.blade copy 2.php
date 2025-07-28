<x-layout.app title="{{ setting('welcome_title', 'Laravel Superapp CMS') }}" :pakai-sidebar="false" :pakaiFluid="false">
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <!-- Welcome Section -->
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="row row-0">
                            <div class="col-3 order-md-last">
                                <!-- Photo -->
                                <div class="card-body">
                                    <img src="https://via.placeholder.com/300x200/3b82f6/ffffff?text=SuperApp"
                                        class="w-60 h-100 object-cover card-img-end">
                                </div>
                            </div>
                            <div class="col">
                                <div class="card-body">
                                    <h3 class="h2">SuperApp</h3>
                                    <p class="text-muted"> SuperApp adalah platform layanan digital terpadu yang
                                        dirancang untuk memudahkan masyarakat
                                        dalam mengakses berbagai layanan publik dari berbagai instansi pemerintahan di
                                        Kota Surabaya.
                                        Melalui satu pintu aplikasi ini, pengguna dapat menjelajahi, mengajukan, dan
                                        memantau layanan
                                        dari dinas-dinas seperti Pendidikan, Kominfo, Kesehatan, hingga Perizinan.</p>

                                    <div class="row g-5 mt-10">
                                        <div class="col-auto">
                                            <div class="d-flex align-items-baseline">
                                                <!-- Dummy authenticated state - showing logged in user -->
                                                <button class="btn btn-primary">
                                                    Panduan Pengguna
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter and Search Section -->
                <div class="d-flex justify-content-between flex-wrap align-items-center mb-1 gap-2">
                    <!-- Filter buttons -->
                    <div class="form-selectgroup d-flex flex-wrap">
                        <label class="form-selectgroup-item">
                            <input type="radio" name="icons" value="tampilkan semua" class="form-selectgroup-input"
                                checked />
                            <span class="form-selectgroup-label">
                                Tampilkan Semua
                            </span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="icons" value="kesehatan" class="form-selectgroup-input" />
                            <span class="form-selectgroup-label">
                                Kesehatan
                            </span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="icons" value="pendidikan" class="form-selectgroup-input" />
                            <span class="form-selectgroup-label">
                                Pendidikan
                            </span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="icons" value="pariwisata" class="form-selectgroup-input" />
                            <span class="form-selectgroup-label">
                                Pariwisata
                            </span>
                        </label>
                    </div>

                    <!-- Search bar -->
                    <div class="input-icon mb-2">
                        <input type="text" value="" class="form-control" placeholder="Search…" />
                        <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/search -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- List of pages (Dummy Data) -->
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3"
                                style="background-image: url(https://via.placeholder.com/80x80/dc2626/ffffff?text=K)">
                            </span>
                            <h3 class="m-0 mb-1"><a href="#">Dinas Kesehatan</a></h3>
                            <div class="text-secondary">
                                Menyelenggarakan urusan pemerintahan daerah bidang kesehatan
                            </div>
                        </div>
                        <div class="d-flex">
                            <!-- Dummy superadmin access -->
                            <a href="#" class="card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                    <path
                                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Setting
                            </a>
                            <a href="#" class="card-btn">Selengkapnya</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3"
                                style="background-image: url(https://via.placeholder.com/80x80/2563eb/ffffff?text=P)">
                            </span>
                            <h3 class="m-0 mb-1"><a href="#">Dinas Pendidikan</a></h3>
                            <div class="text-secondary">
                                Menyelenggarakan urusan pemerintahan daerah bidang pendidikan
                            </div>
                        </div>
                        <div class="d-flex">
                            <a href="#" class="card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                    <path
                                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Setting
                            </a>
                            <a href="#" class="card-btn">Selengkapnya</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3"
                                style="background-image: url(https://via.placeholder.com/80x80/059669/ffffff?text=W)">
                            </span>
                            <h3 class="m-0 mb-1"><a href="#">Dinas Pariwisata</a></h3>
                            <div class="text-secondary">
                                Menyelenggarakan urusan pemerintahan daerah bidang pariwisata
                            </div>
                        </div>
                        <div class="d-flex">
                            <a href="#" class="card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                    <path
                                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Setting
                            </a>
                            <a href="#" class="card-btn">Selengkapnya</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3">
                                K
                            </span>
                            <h3 class="m-0 mb-1"><a href="#">Dinas Kominfo</a></h3>
                            <div class="text-secondary">
                                Komunikasi dan Informatika Kota Surabaya
                            </div>
                        </div>
                        <div class="d-flex">
                            <a href="#" class="card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                    <path
                                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Setting
                            </a>
                            <a href="#" class="card-btn">Selengkapnya</a>
                        </div>
                    </div>
                </div>

                <!-- Pagination (Dummy Data) -->
                <div class="d-flex justify-content-between flex-wrap align-items-center mb-1 gap-2">
                    <p class="m-0 text-secondary">Showing <span>1</span> to
                        <span>4</span> of <span>12</span>
                        entries
                    </p>
                    <ul class="pagination m-0 ms-auto">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M15 6l-6 6l6 6" />
                                </svg>
                                sebelumnya
                            </a>
                        </li>

                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>

                        <li class="page-item">
                            <a class="page-link" href="#">
                                selanjutnya
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M9 6l6 6l-6 6" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
</x-layout.app>
