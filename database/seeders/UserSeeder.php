<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat admin users dulu
        $superadmin = User::create([
            'name' => 'Super Administrator',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $superadmin->assignRole('superadmin');

        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Generate 500 user dummy pake factory
        User::factory()->count(2000)->create()->each(function ($user) {
            $user->assignRole('user');
        });

        $this->command->info("✅ 502 users created (2 admin + 500 dummy users)");
    }
}
