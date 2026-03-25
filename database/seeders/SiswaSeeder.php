<?php
// ============================================================
// FILE: database/seeders/SiswaSeeder.php
// Migrates from: petugas table in Supabase
// P001 → S001/John Doe, P002 → S002/Andi Pratama, P003 → S003/Citra Lestari
// IMPORTANT: Passwords are hashed properly (was plain text in Supabase!)
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;

class SiswaSeeder extends Seeder
{
  public function run(): void
  {
    $data = [
      [
        'nis' => 'S001',
        'nama' => 'John Doe',
        'kelas' => 'XII TLM 1',
        'jurusan' => 'Teknologi Laboratorium Medik',
        'password' => Hash::make('123456'),        // was: plain "123456"
        'keterangan' => 'Test Student',
        'is_active' => true,
      ],
      [
        'nis' => 'S002',
        'nama' => 'Andi Pratama',
        'kelas' => 'XII TLM 1',
        'jurusan' => 'Teknologi Laboratorium Medik',
        'password' => Hash::make('password123'),   // was: plain "password123"
        'keterangan' => 'Siswa Aktif Angkatan 2024',
        'is_active' => true,
      ],
      [
        'nis' => 'S003',
        'nama' => 'Citra Lestari',
        'kelas' => 'XII TLM 2',
        'jurusan' => 'Teknologi Laboratorium Medik',
        'password' => Hash::make('password456'),   // was: plain "password456"
        'keterangan' => 'Koordinator Lab',
        'is_active' => true,
      ],
    ];

    foreach ($data as $siswa) {
      Siswa::updateOrCreate(['nis' => $siswa['nis']], $siswa);
    }
  }
}
