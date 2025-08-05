<x-layout.app title="Menus Management - Panel Admin" :pakaiSidebar="false" :pakaiTopBar="false" :pakaiFluid="false" :hasDivPage="false">
    <div class="page page-center">
        <div class="container container-normal py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg d-none d-lg-flex justify-content-center align-items-center">
                <img src="{{ asset('static/logo-surabaya-hebat.png') }}"
                    alt="Logo Surabaya Hebat"
                    class="img-fluid"
                    style="max-width: 60%; height: auto;">
            </div>


                {{-- gawe card login --}}
                <div class="col-lg">
                    <div class="container-tight">
                        <div class="card card-md">
                            <div class="card-body">
                                <h2 class="h2 text-center mb-4">Masuk ke Akun</h2>
                                <form method="POST" action="{{ route('login') }}">

                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email/Username</label>
                                        <input id="email" name="email" value="{{ old('email') }}" type="text"
                                            class="form-control" placeholder="your@email.com atau username"
                                            autocomplete="off" />
                                    </div>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="mb-2">
                                        <label class="form-label">
                                            Kata Sandi
                                            <span class="form-label-description">
                                                <a href="./forgot-password.html">Lupa Kata Sandi?</a>
                                            </span>
                                        </label>
                                        <div class="input-group input-group-flat">
                                            <input id="password" name="password" type="password"
                                                class="form-control" placeholder="Your password"
                                                autocomplete="off" />
                                            <span class="input-group-text">
                                                <a href="#" class="link-secondary" title="Lihat Kata Sandi"
                                                    data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler.io/icons/icon/eye -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="icon icon-1">
                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                        <path
                                                            d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                    </svg></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-check">
                                            <input type="checkbox" class="form-check-input" />
                                            <span class="form-check-label">Ingat Saya</span>
                                        </label>
                                    </div>
                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                                    </div>
                                </form>
                            </div>
                            <div class="hr-text">atau</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <a href="#" class="btn btn-4 w-100">
                                            Masuk dengan Aplikasi Lain
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center text-secondary mt-3">Belum punya akun? <a
                                href="{{ route('register') }}" tabindex="-1">Daftar disini.</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
