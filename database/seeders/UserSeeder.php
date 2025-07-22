<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        $user = User::create([
            'name' => 'Regular User',
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('user');

        $this->command->info("✅ 3 users created with roles assigned");
    }
}
