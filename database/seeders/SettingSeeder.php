<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // ========================================
            // GENERAL SETTINGS
            // ========================================
            'app_name' => [
                'value' => 'KantorKu SuperApp',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nama aplikasi yang akan ditampilkan di seluruh aplikasi'
            ],
            'app_description' => [
                'value' => 'Sistem Informasi Terintegrasi Pemerintah Kota Surabaya',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Deskripsi singkat aplikasi'
            ],
            'app_version' => [
                'value' => '1.0.0',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Versi aplikasi saat ini'
            ],
            'timezone' => [
                'value' => 'Asia/Jakarta',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Zona waktu default aplikasi'
            ],
            'locale' => [
                'value' => 'id',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Bahasa default aplikasi'
            ],
            // untuk landing page
            // 'judul_landing' => [
            //     'value' => 'Super App Dinkominfo',
            //     'type' => 'text',
            //     'group' => 'landing',
            //     'description' => 'Judul halaman welcome'
            // ],
            'subtitle_landing' => [
                'value' => 'Sistem Informasi Terintegrasi untuk melayani masyarakat dengan lebih baik',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Subtitle halaman welcome'
            ],
            
            
            // ========================================
            // BRANDING SETTINGS
            // ========================================
            'logo_app' => [
                'value' => 'logos/logo-light.png',
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Logo untuk tema terang'
            ],
            'favicon' => [
                'value' => 'logos/favicon.ico',
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Icon favorit website'
            ],
           
            
            // ========================================
            // SEO SETTINGS
            // ========================================
            'meta_title' => [
                'value' => 'KantorKu SuperApp - Sistem Informasi Terintegrasi',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Judul meta default untuk halaman'
            ],
            'meta_description' => [
                'value' => 'Sistem informasi terintegrasi untuk meningkatkan efisiensi dan transparansi layanan pemerintahan',
                'type' => 'textarea',
                'group' => 'seo',
                'description' => 'Deskripsi meta default untuk halaman'
            ],
            'meta_keywords' => [
                'value' => 'sistem informasi, pemerintahan, terintegrasi, transparansi',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Kata kunci meta default untuk halaman'
            ],
            
            // ========================================
            // CONTACT SETTINGS
            // ========================================
            'contact_email' => [
                'value' => 'info@kantorku.com',
                'type' => 'email',
                'group' => 'contact',
                'description' => 'Email kontak utama'
            ],
            'contact_phone' => [
                'value' => '+62 31 123 4567',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Nomor telepon kontak utama'
            ],
            'contact_address' => [
                'value' => 'Jl. Pemuda No. 1, Surabaya, Jawa Timur 60271',
                'type' => 'textarea',
                'group' => 'contact',
                'description' => 'Alamat kantor utama'
            ],
            
            // ========================================
            // SOCIAL MEDIA SETTINGS
            // ========================================
            'social_facebook' => [
                'value' => 'https://facebook.com/kantorku',
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL halaman Facebook resmi'
            ],
            'social_twitter' => [
                'value' => 'https://twitter.com/kantorku',
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL halaman Twitter resmi'
            ],
            'social_instagram' => [
                'value' => 'https://instagram.com/kantorku',
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL halaman Instagram resmi'
            ],
            'social_youtube' => [
                'value' => 'https://youtube.com/kantorku',
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL channel YouTube resmi'
            ],
            
            // ========================================
            // FEATURE SETTINGS
            // ========================================
            'maintenance_mode' => [
                'value' => '0',
                'type' => 'boolean',
                'group' => 'feature',
                'description' => 'Mode maintenance aplikasi'
            ],
            'user_registration' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'feature',
                'description' => 'Izinkan pendaftaran user baru'
            ],
            'email_verification' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'feature',
                'description' => 'Wajibkan verifikasi email'
            ],
            'captcha_enabled' => [
                'value' => '0',
                'type' => 'boolean',
                'group' => 'feature',
                'description' => 'Aktifkan CAPTCHA pada form'
            ],
            'max_file_upload' => [
                'value' => '10240',
                'type' => 'number',
                'group' => 'feature',
                'description' => 'Maksimal ukuran file upload (KB)'
            ],
            'items_per_page' => [
                'value' => '25',
                'type' => 'number',
                'group' => 'feature',
                'description' => 'Jumlah item per halaman pada tabel'
            ],
            
            // ========================================
            // LANDING PAGE SETTINGS
            // ========================================
            'landing_hero_subheader' => [
                'value' => 'Selamat Datang di',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Sub header pada hero section landing page'
            ],
            'landing_typed_strings' => [
                'value' => '["satu portal", "semua dinas", "super mudah"]',
                'type' => 'json',
                'group' => 'landing',
                'description' => 'Array string untuk animasi typed (format JSON)'
            ],
            'landing_typed_speed' => [
                'value' => '100',
                'type' => 'number',
                'group' => 'landing',
                'description' => 'Kecepatan typing animasi (ms)'
            ],
            'landing_typed_back_speed' => [
                'value' => '50',
                'type' => 'number',
                'group' => 'landing',
                'description' => 'Kecepatan backspace animasi (ms)'
            ],
            'landing_typed_back_delay' => [
                'value' => '1000',
                'type' => 'number',
                'group' => 'landing',
                'description' => 'Delay sebelum backspace (ms)'
            ],
            'landing_typed_start_delay' => [
                'value' => '1000',
                'type' => 'number',
                'group' => 'landing',
                'description' => 'Delay sebelum mulai typing (ms)'
            ],
            'landing_btn_register' => [
                'value' => 'Daftar',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Text tombol daftar di hero section'
            ],
            'landing_btn_login' => [
                'value' => 'Masuk',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Text tombol masuk di hero section'
            ],
            
            // Gallery Section Images
            'landing_gallery_image_1' => [
                'value' => 'https://picsum.photos/400/240?random=1',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar galeri posisi 1'
            ],
            'landing_gallery_image_2' => [
                'value' => 'https://picsum.photos/400/240?random=2',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar galeri posisi 2'
            ],
            'landing_gallery_image_3' => [
                'value' => 'https://picsum.photos/400/240?random=3',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar galeri posisi 3'
            ],
            'landing_gallery_image_4' => [
                'value' => 'https://picsum.photos/400/240?random=4',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar galeri posisi 4'
            ],
            'landing_gallery_image_5' => [
                'value' => 'https://picsum.photos/400/240?random=5',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar galeri posisi 5'
            ],
            'landing_gallery_image_6' => [
                'value' => 'https://picsum.photos/400/240?random=6',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar galeri posisi 6'
            ],
            
            // Section 2 Cards
            'landing_card_1_title' => [
                'value' => 'Card with top image',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Judul card section 2 posisi 1'
            ],
            'landing_card_1_image' => [
                'value' => './static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar card section 2 posisi 1'
            ],
            'landing_card_2_title' => [
                'value' => 'Card with top image',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Judul card section 2 posisi 2'
            ],
            'landing_card_2_image' => [
                'value' => './static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar card section 2 posisi 2'
            ],
            'landing_card_3_title' => [
                'value' => 'Card with top image',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Judul card section 2 posisi 3'
            ],
            'landing_card_3_image' => [
                'value' => './static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar card section 2 posisi 3'
            ],
            'landing_card_4_title' => [
                'value' => 'Card with top image',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Judul card section 2 posisi 4'
            ],
            'landing_card_4_image' => [
                'value' => './static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar card section 2 posisi 4'
            ],
            
            // FAQ Section
            'landing_faq_title' => [
                'value' => 'Pertanyaan yang Sering Diajukan',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Judul section FAQ'
            ],
            'landing_faq_subtitle' => [
                'value' => 'Tidak menemukan jawaban yang Anda butuhkan?<br>Hubungi tim pengelola sistem.',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Subtitle section FAQ'
            ],
            'landing_faq_btn_text' => [
                'value' => '📖 Baca Selengkapnya',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Text tombol FAQ'
            ],
            
            // Footer Section
            'landing_footer_text' => [
                'value' => 'Teks bebas',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Teks footer landing page'
            ],
            'landing_footer_supported_title' => [
                'value' => 'Supported by:',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Judul section supported by di footer'
            ],
            'landing_footer_social_title' => [
                'value' => 'Ikuti Media Sosial Kami:',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Judul section social media di footer'
            ],
        ];

        foreach ($settings as $key => $config) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $config['value'],
                    'type' => $config['type'],
                    'group' => $config['group'],
                    'description' => $config['description'],
                ]
            );
        }
    }
}