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
            'contact_phone_2' => [
                'value' => '(031) 5312144 Psw. 384 / 241',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Nomor telepon kontak kedua'
            ],
            'contact_email_2' => [
                'value' => 'mediacenter@surabaya.go.id',
                'type' => 'email',
                'group' => 'contact',
                'description' => 'Email kontak kedua'
            ],
            'footer_description' => [
                'value' => 'Platform digital terpadu untuk layanan pemerintahan yang lebih baik dan transparan.',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Deskripsi footer aplikasi'
            ],
            'welcome_image' => [
                'value' => 'https://via.placeholder.com/300x200/3b82f6/ffffff?text=SuperApp',
                'type' => 'url',
                'group' => 'general',
                'description' => 'Gambar welcome di halaman beranda'
            ],
            'pagination_per_page' => [
                'value' => '12',
                'type' => 'number',
                'group' => 'general',
                'description' => 'Jumlah item per halaman di beranda'
            ],
            'auth_logo' => [
                'value' => 'static/logo-surabaya-hebat.png',
                'type' => 'image',
                'group' => 'general',
                'description' => 'Logo yang ditampilkan di halaman login dan register'
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
            'captcha_setting' => [
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
            // SOCIAL MEDIA SETTINGS
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
            
            // Hero Carousel Images
            'landing_hero_image_1' => [
                'value' => '/static/illustrations/boy-with-key.svg',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar hero carousel slide 1'
            ],
            'landing_hero_image_2' => [
                'value' => '/static/illustrations/boy-girl.svg',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar hero carousel slide 2'
            ],
            'landing_hero_image_3' => [
                'value' => '/static/illustrations/growth.svg',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar hero carousel slide 3'
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
                'value' => 'Layanan Digital',
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
            'landing_card_1_description' => [
                'value' => 'Akses berbagai layanan digital pemerintahan dengan mudah dan cepat melalui satu platform terintegrasi.',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Deskripsi card section 2 posisi 1'
            ],
            'landing_card_2_title' => [
                'value' => 'Transparansi Informasi',
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
            'landing_card_2_description' => [
                'value' => 'Informasi publik yang lengkap dan transparan untuk mendukung keterbukaan pemerintahan.',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Deskripsi card section 2 posisi 2'
            ],
            'landing_card_3_title' => [
                'value' => 'Pelayanan Prima',
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
            'landing_card_3_description' => [
                'value' => 'Pelayanan berkualitas tinggi dengan standar yang jelas dan proses yang efisien.',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Deskripsi card section 2 posisi 3'
            ],
            'landing_card_4_title' => [
                'value' => 'Inovasi Berkelanjutan',
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
            'landing_card_4_description' => [
                'value' => 'Terus berinovasi dalam menghadirkan solusi teknologi terdepan untuk masyarakat.',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Deskripsi card section 2 posisi 4'
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
                'value' => 'Baca Selengkapnya',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Text tombol FAQ'
            ],
            
            // FAQ Questions & Answers
            'landing_faq_1_question' => [
                'value' => 'Apa itu SuperApp Dinkominfo?',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Pertanyaan FAQ nomor 1'
            ],
            'landing_faq_1_answer' => [
                'value' => 'SuperApp Dinkominfo adalah platform digital terpadu yang menghubungkan berbagai layanan pemerintahan dalam satu aplikasi untuk memudahkan masyarakat mengakses layanan publik.',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Jawaban FAQ nomor 1'
            ],
            'landing_faq_2_question' => [
                'value' => 'Bagaimana cara mendaftar dan menggunakan aplikasi ini?',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Pertanyaan FAQ nomor 2'
            ],
            'landing_faq_2_answer' => [
                'value' => 'Anda dapat mendaftar dengan mengklik tombol "Daftar" di halaman utama, mengisi formulir pendaftaran dengan data yang valid, kemudian verifikasi email untuk mengaktifkan akun.',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Jawaban FAQ nomor 2'
            ],
            'landing_faq_3_question' => [
                'value' => 'Layanan apa saja yang tersedia di platform ini?',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Pertanyaan FAQ nomor 3'
            ],
            'landing_faq_3_answer' => [
                'value' => 'Platform ini menyediakan akses ke berbagai layanan dinas seperti Pendidikan, Kesehatan, Perizinan, Kependudukan, dan layanan publik lainnya dalam satu tempat.',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Jawaban FAQ nomor 3'
            ],
            'landing_faq_4_question' => [
                'value' => 'Apakah aplikasi ini gratis untuk digunakan?',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Pertanyaan FAQ nomor 4'
            ],
            'landing_faq_4_answer' => [
                'value' => 'Ya, aplikasi ini sepenuhnya gratis untuk digunakan oleh seluruh masyarakat. Tidak ada biaya pendaftaran atau biaya berlangganan.',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Jawaban FAQ nomor 4'
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
            
            // Footer Organization Details
            'landing_footer_org_name' => [
                'value' => 'Dinas Komunikasi dan Informatika Kota Surabaya',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Nama organisasi di footer'
            ],
            'landing_footer_address_label' => [
                'value' => 'Alamat:',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Label alamat di footer'
            ],
            'landing_footer_address_value' => [
                'value' => 'Jl. Jimerto No.25-27, Wonokromo, Kec. Wonokromo, Surabaya, Jawa Timur 60243',
                'type' => 'textarea',
                'group' => 'landing',
                'description' => 'Alamat lengkap organisasi'
            ],
            'landing_footer_contact_title' => [
                'value' => 'Kontak Kami',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Judul section kontak di footer'
            ],
            'landing_footer_email_label' => [
                'value' => 'Email:',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Label email di footer'
            ],
            'landing_footer_email_1' => [
                'value' => 'dinkominfo@surabaya.go.id',
                'type' => 'email',
                'group' => 'landing',
                'description' => 'Email utama organisasi'
            ],
            'landing_footer_email_2' => [
                'value' => 'mediacenter@surabaya.go.id',
                'type' => 'email',
                'group' => 'landing',
                'description' => 'Email kedua organisasi'
            ],
            'landing_footer_phone_label' => [
                'value' => 'Telepon:',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Label telepon di footer'
            ],
            'landing_footer_phone_1' => [
                'value' => '(031) 99277339',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Nomor telepon utama'
            ],
            'landing_footer_phone_2' => [
                'value' => '(031) 5312144 Psw. 384 / 241',
                'type' => 'text',
                'group' => 'landing',
                'description' => 'Nomor telepon kedua'
            ],
            
            // Supported Icon (Single Image)
            'landing_supported_icon_image' => [
                'value' => 'https://smb.telkomuniversity.ac.id/wp-content/uploads/2023/03/Logo-Utama-Telkom-University.png',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL gambar supported by'
            ],
            'landing_supported_icon_url' => [
                'value' => 'https://telkomuniversity.ac.id',
                'type' => 'url',
                'group' => 'landing',
                'description' => 'URL link untuk supported by'
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