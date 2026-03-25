<?php
// ============================================================
// FILE: database/seeders/DatabaseSeeder.php
// Migrates existing data from Supabase + seeds master data
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SiswaSeeder::class,
            JenisPemeriksaanSeeder::class,
            PasienSeeder::class,
            KategoriBukuSeeder::class,
        ]);
    }
}