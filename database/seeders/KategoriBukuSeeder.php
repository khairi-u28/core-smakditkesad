<?php
// ============================================================
// FILE: database/seeders/KategoriBukuSeeder.php
// Default categories for E-Library
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KategoriBukuSeeder extends Seeder
{
  public function run(): void
  {
    $kategori = [
      ['nama' => 'Hematologi',       'slug' => 'hematologi'],
      ['nama' => 'Kimia Klinik',     'slug' => 'kimia-klinik'],
      ['nama' => 'Mikrobiologi',     'slug' => 'mikrobiologi'],
      ['nama' => 'Urinalisis',       'slug' => 'urinalisis'],
      ['nama' => 'Imunoserologi',    'slug' => 'imunoserologi'],
      ['nama' => 'Parasitologi',     'slug' => 'parasitologi'],
      ['nama' => 'Umum Kesehatan',   'slug' => 'umum-kesehatan'],
    ];

    foreach ($kategori as $k) {
      \App\Models\KategoriBuku::updateOrCreate(['slug' => $k['slug']], $k);
    }
  }
}
