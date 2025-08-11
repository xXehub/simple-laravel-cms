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
            'welcome_title' => [
                'value' => 'Super App Dinkominfo',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Judul halaman welcome'
            ],
            'welcome_subtitle' => [
                'value' => 'Sistem Informasi Terintegrasi untuk melayani masyarakat dengan lebih baik',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Subtitle halaman welcome'
            ],
            
            // ========================================
            // BRANDING SETTINGS
            // ========================================
            'logo_light' => [
                'value' => 'logos/logo-light.png',
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Logo untuk tema terang'
            ],
            'logo_dark' => [
                'value' => 'logos/logo-dark.png',
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Logo untuk tema gelap'
            ],
            'favicon' => [
                'value' => 'logos/favicon.ico',
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Icon favorit website'
            ],
            'primary_color' => [
                'value' => '#206bc4',
                'type' => 'color',
                'group' => 'branding',
                'description' => 'Warna utama aplikasi'
            ],
            'secondary_color' => [
                'value' => '#6c757d',
                'type' => 'color',
                'group' => 'branding',
                'description' => 'Warna sekunder aplikasi'
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
