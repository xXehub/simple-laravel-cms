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
                                        <div class="me-2">
                                            <svg class="icon" width="24" height="24" stroke="currentColor" fill="none">
                                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.054 0 1.623-1.14 1.054-2L13.054 5c-.527-.9-1.581-.9-2.108 0L3.028 17c-.569.86 0 2 1.054 2z"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>{{ $errors->first('g-recaptcha-response') }}</div>
                                    </div>
                                @endif

                            <form method="POST" action="{{ route('register') }}">
                            @csrf
                                <h2 class="card-title text-center mb-4">Daftar Akun</h2>
                                <div class="mb-3">
                                    <label class="form-label">Nama lengkap</label>
                                    <input id="name" type="text" class="form-control" placeholder="Masukkan nama lengkap" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus />
                                    <div id="name-error" class="text-danger small mt-1 d-none">Nama lengkap wajib diisi.</div>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input id="username" type="text" class="form-control" placeholder="Masukkan username unik" name="username" value="{{ old('username') }}" required autocomplete="username" />
                                    <div id="username-error" class="text-danger small mt-1 d-none">Username wajib diisi dan hanya boleh huruf, angka, dan underscore.</div>
                                    @error('username')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input id="email" type="email" class="form-control" placeholder="Masukkan email aktif" name="email" value="{{ old('email') }}" required autocomplete="email" />
                                    <div id="email-error" class="text-danger small mt-1 d-none">Masukkan email yang valid.</div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kata Sandi</label>
                                    <div class="input-group input-group-flat">
                                        <input id="password" type="password" class="form-control" placeholder="Buat kata sandi kuat"
                                            name="password" required autocomplete="new-password" />
                                        <span class="input-group-text">
                                            <a href="#" class="link-secondary toggle-password"
                                                data-target="#password">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                    class="icon icon-eye">
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                    class="icon icon-eye-off d-none">
                                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                                    <line x1="2" y1="2" x2="22" y2="22"></line>
                                                </svg>
                                            </a>
                                        </span>
                                    </div>
                                    <div id="password-error" class="text-danger small mt-1 d-none">Kata sandi minimal 8 karakter.</div>
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
                                            <a href="#" class="link-secondary toggle-password"
                                                data-target="#password_confirmation">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-eye">
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                    class="icon icon-eye-off d-none">
                                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                                    <line x1="2" y1="2" x2="22" y2="22"></line>
                                                </svg>
                                            </a>
                                        </span>
                                    </div>
                                    <div id="password-confirmation-error" class="text-danger small mt-1 d-none">Kata sandi tidak cocok.</div>
                                    @error('password_confirmation')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if(setting('captcha_enabled', false))
                                    {{-- reCAPTCHA Widget --}}
                                    <div class="mb-3 d-flex justify-content-center">
                                        {!! NoCaptcha::renderJs() !!}
                                        {!! NoCaptcha::display() !!}
                                    </div>
                                @endif

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

    {{-- JavaScript untuk Toggle Password dan Real-time Validation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle password toggle
            document.querySelectorAll('.toggle-password').forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.querySelector(targetId);
                    const eyeIcon = this.querySelector('.icon-eye');
                    const eyeOffIcon = this.querySelector('.icon-eye-off');
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        eyeIcon.classList.add('d-none');
                        eyeOffIcon.classList.remove('d-none');
                    } else {
                        passwordInput.type = 'password';
                        eyeIcon.classList.remove('d-none');
                        eyeOffIcon.classList.add('d-none');
                    }
                });
            });

            // Real-time validation functions
            function showError(inputId, errorId, message) {
                const input = document.getElementById(inputId);
                const error = document.getElementById(errorId);
                
                // Untuk password fields, tampilkan border merah tanpa icon
                if (inputId === 'password' || inputId === 'password_confirmation') {
                    const inputGroup = input.closest('.input-group');
                    if (inputGroup) {
                        inputGroup.style.borderColor = '#dc3545';
                        inputGroup.style.border = '1px solid #dc3545';
                        inputGroup.style.borderRadius = '6px';
                    }
                    input.style.borderColor = '#dc3545';
                    error.classList.remove('d-none');
                    error.textContent = message;
                } else {
                    input.classList.add('is-invalid');
                    input.classList.remove('is-valid');
                    error.classList.remove('d-none');
                    error.textContent = message;
                }
            }

            function showSuccess(inputId, errorId) {
                const input = document.getElementById(inputId);
                const error = document.getElementById(errorId);
                
                // Untuk password fields, tampilkan border hijau tanpa icon
                if (inputId === 'password' || inputId === 'password_confirmation') {
                    const inputGroup = input.closest('.input-group');
                    if (inputGroup) {
                        inputGroup.style.borderColor = '#198754';
                        inputGroup.style.border = '1px solid #198754';
                        inputGroup.style.borderRadius = '6px';
                    }
                    input.style.borderColor = '#198754';
                    error.classList.add('d-none');
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                    error.classList.add('d-none');
                }
            }

            function hideValidation(inputId, errorId) {
                const input = document.getElementById(inputId);
                const error = document.getElementById(errorId);
                
                // Untuk password fields, reset border ke default
                if (inputId === 'password' || inputId === 'password_confirmation') {
                    const inputGroup = input.closest('.input-group');
                    if (inputGroup) {
                        inputGroup.style.borderColor = '';
                        inputGroup.style.border = '';
                        inputGroup.style.borderRadius = '';
                    }
                    input.style.borderColor = '';
                    error.classList.add('d-none');
                } else {
                    input.classList.remove('is-invalid', 'is-valid');
                    error.classList.add('d-none');
                }
            }

            // Name validation
            const nameInput = document.getElementById('name');
            nameInput.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    showError('name', 'name-error', 'Nama lengkap wajib diisi.');
                } else if (this.value.trim().length < 2) {
                    showError('name', 'name-error', 'Nama lengkap minimal 2 karakter.');
                } else {
                    showSuccess('name', 'name-error');
                }
            });

            nameInput.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    hideValidation('name', 'name-error');
                }
            });

            // Username validation
            const usernameInput = document.getElementById('username');
            usernameInput.addEventListener('blur', function() {
                const value = this.value.trim();
                if (value === '') {
                    showError('username', 'username-error', 'Username wajib diisi.');
                } else if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                    showError('username', 'username-error', 'Username hanya boleh huruf, angka, dan underscore.');
                } else if (value.length < 3) {
                    showError('username', 'username-error', 'Username minimal 3 karakter.');
                } else {
                    showSuccess('username', 'username-error');
                }
            });

            usernameInput.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    hideValidation('username', 'username-error');
                }
            });

            // Email validation
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value.trim() === '') {
                    showError('email', 'email-error', 'Email wajib diisi.');
                } else if (!emailRegex.test(this.value)) {
                    showError('email', 'email-error', 'Masukkan email yang valid.');
                } else {
                    showSuccess('email', 'email-error');
                }
            });

            emailInput.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    hideValidation('email', 'email-error');
                }
            });

            // Password validation
            const passwordInput = document.getElementById('password');
            passwordInput.addEventListener('blur', function() {
                if (this.value === '') {
                    showError('password', 'password-error', 'Kata sandi wajib diisi.');
                } else if (this.value.length < 8) {
                    showError('password', 'password-error', 'Kata sandi minimal 8 karakter.');
                } else {
                    showSuccess('password', 'password-error');
                    // Re-validate password confirmation if it has value
                    const confirmInput = document.getElementById('password_confirmation');
                    if (confirmInput.value !== '') {
                        validatePasswordConfirmation();
                    }
                }
            });

            passwordInput.addEventListener('input', function() {
                if (this.value !== '') {
                    hideValidation('password', 'password-error');
                }
                // Re-validate password confirmation on password change
                const confirmInput = document.getElementById('password_confirmation');
                if (confirmInput.value !== '') {
                    validatePasswordConfirmation();
                }
            });

            // Password confirmation validation
            const passwordConfirmInput = document.getElementById('password_confirmation');
            
            function validatePasswordConfirmation() {
                const password = passwordInput.value;
                const confirmation = passwordConfirmInput.value;
                
                if (confirmation === '') {
                    showError('password_confirmation', 'password-confirmation-error', 'Ulangi kata sandi.');
                } else if (password !== confirmation) {
                    showError('password_confirmation', 'password-confirmation-error', 'Kata sandi tidak cocok.');
                } else {
                    showSuccess('password_confirmation', 'password-confirmation-error');
                }
            }

            passwordConfirmInput.addEventListener('blur', validatePasswordConfirmation);

            passwordConfirmInput.addEventListener('input', function() {
                if (this.value !== '') {
                    hideValidation('password_confirmation', 'password-confirmation-error');
                }
            });
        });
    </script>
</x-layout.app>
