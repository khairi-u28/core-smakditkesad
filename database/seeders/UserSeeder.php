<?php
// ============================================================
// FILE: database/seeders/UserSeeder.php
// Seeds default users for the application
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Dashboard',
                'email' => 'admin@smakditkesad.local',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123456'),
                'user_type' => 'admin',
                'is_corrector' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Guru Koreksi',
                'email' => 'guru@smakditkesad.local',
                'email_verified_at' => now(),
                'password' => Hash::make('guru123456'),
                'user_type' => 'guru',
                'is_corrector' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Petugas Lab',
                'email' => 'petugas@smakditkesad.local',
                'email_verified_at' => now(),
                'password' => Hash::make('petugas123456'),
                'user_type' => 'petugas',
                'is_corrector' => false,
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
