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
                'password' => Hash::make('admin123456'), // Change this in production!
            ],
            [
                'name' => 'Guru Koreksi',
                'email' => 'guru@smakditkesad.local',
                'email_verified_at' => now(),
                'password' => Hash::make('guru123456'),
            ],
            [
                'name' => 'Petugas Lab',
                'email' => 'petugas@smakditkesad.local',
                'email_verified_at' => now(),
                'password' => Hash::make('petugas123456'),
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
