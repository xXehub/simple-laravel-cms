<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Page::updateOrCreate(['slug' => 'tentang-kami'], [
            'title' => 'Tentang Kami',
            'slug' => 'tentang-kami',
            'content' => "Selamat datang di Laravel Superapp CMS!\n\nIni adalah sistem manajemen konten (CMS) modern berbasis Laravel 12, dengan fitur:\n\n• Kontrol akses berbasis peran (role) menggunakan Spatie Laravel Permission\n• Menu dinamis berdasarkan peran pengguna\n• Arsitektur modular dan bersih\n• UI responsif berbasis Bootstrap\n• Routing halaman dinamis\n\nSistem ini dirancang fleksibel dan scalable, cocok untuk aplikasi web modern yang butuh manajemen user dan konten yang kuat.",
            'meta_title' => 'Tentang Laravel Superapp CMS',
            'meta_description' => 'Pelajari tentang Laravel Superapp CMS dengan fitur peran, menu dinamis, dan arsitektur modular.',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        Page::updateOrCreate(['slug' => 'kontak'], [
            'title' => 'Kontak Kami',
            'slug' => 'kontak',
            'content' => "Hubungi kami! Kami siap membantu Anda.\n\nInformasi Kontak:\n📧 Email: info@company.com\n📞 Telepon: +62 812-3456-7890\n📍 Alamat: Jl. Bisnis No. 123, Surabaya\n\nJam Operasional:\nSenin - Jumat: 09.00 - 18.00\nSabtu: 10.00 - 16.00\nMinggu: Libur\n\nUntuk dukungan teknis, email: support@company.com\nUntuk pertanyaan umum, email: info@company.com\n\nKami akan membalas setiap pertanyaan dalam 1x24 jam.",
            'meta_title' => 'Kontak Kami - Hubungi Superapp',
            'meta_description' => 'Hubungi kami untuk pertanyaan atau bantuan. Temukan email, telepon, dan alamat kami.',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        Page::updateOrCreate(['slug' => 'layanan'], [
            'title' => 'Layanan Kami',
            'slug' => 'layanan',
            'content' => "Kami menyediakan berbagai layanan untuk kebutuhan bisnis Anda.\n\nLayanan Utama:\n\n1. Pengembangan Web\n   - Website kustom\n   - Solusi e-commerce\n   - Sistem manajemen konten\n   - Desain responsif\n\n2. Digital Marketing\n   - SEO (Search Engine Optimization)\n   - Social media marketing\n   - Iklan digital\n   - Content marketing\n\n3. Konsultasi Bisnis\n   - Strategi bisnis\n   - Konsultasi teknologi\n   - Transformasi digital\n   - Optimasi proses\n\n4. Dukungan & Maintenance\n   - Dukungan teknis 24/7\n   - Update & maintenance rutin\n   - Monitoring keamanan\n   - Optimasi performa\n\nHubungi kami untuk solusi terbaik bagi bisnis Anda!",
            'meta_title' => 'Layanan Profesional Kami',
            'meta_description' => 'Jelajahi layanan pengembangan web, digital marketing, dan konsultasi bisnis dari kami.',
            'is_published' => true,
            'sort_order' => 3,
        ]);

        Page::updateOrCreate(['slug' => 'berita'], [
            'title' => 'Berita Terbaru',
            'slug' => 'berita',
            'content' => "Dapatkan update dan pengumuman terbaru dari kami.\n\nUpdate Terkini:\n\n📅 Desember 2024 - Website Baru Diluncurkan\nKami resmi meluncurkan website baru dengan navigasi lebih mudah, responsif, dan pengalaman pengguna yang lebih baik.\n\n📅 November 2024 - Layanan Baru\nKami menambah layanan analitik dan solusi business intelligence.\n\n📅 Oktober 2024 - Tim Bertambah\nSelamat datang untuk anggota tim baru! Kami terus berkembang untuk melayani Anda lebih baik.\n\n📅 September 2024 - Kerja Sama Strategis\nKami menjalin kerja sama dengan penyedia teknologi terkemuka untuk solusi yang lebih baik.\n\n📅 Agustus 2024 - Penghargaan\nPerusahaan kami meraih penghargaan atas layanan pelanggan dan inovasi.\n\nPantau terus untuk update menarik lainnya!",
            'meta_title' => 'Berita & Update Terbaru',
            'meta_description' => 'Ikuti berita, update, dan pengumuman terbaru dari Superapp.',
            'is_published' => true,
            'sort_order' => 4,
        ]);

        Page::updateOrCreate(['slug' => 'kebijakan-privasi'], [
            'title' => 'Kebijakan Privasi',
            'slug' => 'kebijakan-privasi',
            'content' => "KEBIJAKAN PRIVASI\n\nIni adalah contoh kebijakan privasi untuk keperluan demo.\n\nPengumpulan Data\nKami mengumpulkan informasi yang Anda berikan langsung, misal saat membuat akun, memperbarui profil, atau menghubungi kami.\n\nPenggunaan Data\nData digunakan untuk menyediakan, memelihara, dan meningkatkan layanan kami.\n\nPerlindungan Data\nKami menerapkan langkah keamanan yang sesuai untuk melindungi data pribadi Anda.\n\nHubungi Kami\nJika ada pertanyaan tentang kebijakan privasi ini, silakan hubungi kami.\n\nTerakhir diperbarui: " . now()->format('d F Y'),
            'meta_title' => 'Kebijakan Privasi - Perlindungan Data Anda',
            'meta_description' => 'Baca kebijakan privasi kami untuk memahami bagaimana data Anda dikumpulkan, digunakan, dan dilindungi.',
            'is_published' => true,
            'sort_order' => 10,
        ]);

        // Add more pages for pagination testing
        for ($i = 1; $i <= 15; $i++) {
            Page::updateOrCreate(['slug' => "contoh-page-{$i}"], [
                'title' => "Contoh Page {$i}",
                'slug' => "contoh-page-{$i}",
                'content' => "Ini cuman contoh halaman {$i}.\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
                'meta_title' => "Contoh Page {$i} - Testing Content",
                'meta_description' => "Ini adalah contoh halaman {$i} untuk testing fungsionalitas doang.",
                'is_published' => true,
                'sort_order' => 10 + $i,
            ]);
        }
    }
}
