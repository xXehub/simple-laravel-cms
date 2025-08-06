<x-layout.app title="Menus Management - Panel Admin" :pakaiSidebar="false" :pakaiTopBar="false" :pakaiFluid="false" :hasDivPage="false">
    <div class="page page-center">
        <div class="container container-normal py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg d-none d-lg-block">
                    <div class="col-lg d-none d-lg-flex justify-content-center align-items-center">
                <img src="{{ asset('static/logo-surabaya-hebat.png') }}"
                    alt="Logo Surabaya Hebat"
                    class="img-fluid"
                    style="max-width: 60%; height: auto;">
            </div>

                </div>
                <div class="col-lg">
                    <div class="container-tight">
                        <div class="card card-md">
                            <div class="card-body">
                                {{-- Error untuk reCAPTCHA --}}
                                @if ($errors->has('g-recaptcha-response'))
                                    <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
                                        <svg class="icon me-2" width="24" height="24" stroke="currentColor" fill="none">
                                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.054 0 1.623-1.14 1.054-2L13.054 5c-.527-.9-1.581-.9-2.108 0L3.028 17c-.569.86 0 2 1.054 2z"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div>{{ $errors->first('g-recaptcha-response') }}</div>
                                    </div>
                                @endif

                            <form method="POST" action="{{ route('register') }}">
                            @csrf
                                <h2 class="card-title text-center mb-4">Daftar Akun</h2>
                                <div class="mb-3">
                                    <label class="form-label">Nama lengkap</label>
                                    <input id="name" type="text" class="form-control" placeholder="Masukkan nama lengkap" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input id="username" type="text" class="form-control" placeholder="Masukkan username unik" name="username" value="{{ old('username') }}" required autocomplete="username" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input id="email" type="email" class="form-control" placeholder="Masukkan email aktif" name="email" value="{{ old('email') }}" required autocomplete="email" />
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kata Sandi</label>
                                    <div class="input-group input-group-flat">
                                        <input id="password" type="password" class="form-control" placeholder="Buat kata sandi kuat"
                                            name="password" required autocomplete="new-password" />
                                        <span class="input-group-text"> ... </span>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Konfirmasi Kata Sandi</label>
                                    <div class="input-group input-group-flat">
                                        <input id="password_confirmation" type="password" class="form-control" placeholder="Ulangi kata sandi"
                                            autocomplete="off" name="password_confirmation" required autocomplete="new-password" />
                                        <span class="input-group-text">
                                            <a href="#" class="link-secondary" title="Show password"
                                                data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler.io/icons/icon/eye -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-1">
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg></a>
                                        </span>
                                    </div>
                                </div>

                                {{-- reCAPTCHA Widget --}}
                                <div class="mb-3 d-flex justify-content-center">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}
                                </div>

                                <div class="form-footer">
                                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="text-center text-secondary mt-3">Sudah punya akun? <a href="{{ route(name: 'login') }}"
                            tabindex="-1">Masuk.</a></div>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
