<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Messages
    |--------------------------------------------------------------------------
    |
    | Pesan-pesan yang ditampilkan saat proses login dan registrasi
    |
    */

    'login' => [
        'email_required' => 'Email atau username wajib diisi.',
        'password_required' => 'Kata sandi wajib diisi.',
        'invalid_credentials' => 'Email/username atau kata sandi yang Anda masukkan salah.',
        'recaptcha_required' => 'Silakan verifikasi reCAPTCHA.',
        'recaptcha_failed' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
    ],

    'register' => [
        'name_required' => 'Nama lengkap wajib diisi.',
        'name_max' => 'Nama lengkap maksimal 255 karakter.',
        'username_required' => 'Username wajib diisi.',
        'username_unique' => 'Username sudah digunakan, silakan pilih yang lain.',
        'username_format' => 'Username hanya boleh menggunakan huruf, angka, dan underscore.',
        'email_required' => 'Email wajib diisi.',
        'email_valid' => 'Format email tidak valid.',
        'email_unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
        'password_required' => 'Kata sandi wajib diisi.',
        'password_min' => 'Kata sandi minimal 8 karakter.',
        'password_confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        'recaptcha_required' => 'Silakan verifikasi reCAPTCHA.',
        'recaptcha_failed' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
    ],

    'general' => [
        'success_login' => 'Selamat datang! Login berhasil.',
        'success_register' => 'Registrasi berhasil! Selamat datang.',
        'logout' => 'Anda telah keluar dari sistem.',
    ],

];
