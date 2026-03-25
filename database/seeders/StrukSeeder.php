<?php
// ============================================================
// FILE: database/seeders/StrukSeeder.php
// Seeds sample Struk (examination records) data
// Links existing Siswa, Pasien, and JenisPemeriksaan records
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Struk;
use App\Models\Siswa;
use App\Models\Pasien;
use App\Models\JenisPemeriksaan;
use Illuminate\Support\Str;

class StrukSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = Siswa::all();
        $pasien = Pasien::all();
        $pemeriksaan = JenisPemeriksaan::all();

        if ($siswa->isEmpty() || $pasien->isEmpty() || $pemeriksaan->isEmpty()) {
            return; // Skip if no related data exists
        }

        $struks = [
            // Struk 1 - Draft status
            [
                'kode_struk' => 'STRUK-250325-' . strtoupper(Str::random(6)),
                'siswa_id' => $siswa->where('nis', 'S002')->first()->id,
                'pasien_id' => $pasien->where('nama_pasien', 'Hendra Gunawan')->first()->id,
                'pemeriksaans' => [
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Hemoglobin')->first()->id,
                        'subPeriksa' => 'Hemoglobin',
                        'bidangPeriksa' => 'Hematologi',
                        'tarif' => 25000,
                        'hasilPemeriksaan' => null,
                    ],
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Leukosit')->first()->id,
                        'subPeriksa' => 'Leukosit',
                        'bidangPeriksa' => 'Hematologi',
                        'tarif' => 30000,
                        'hasilPemeriksaan' => null,
                    ],
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Glukosa Puasa')->first()->id,
                        'subPeriksa' => 'Glukosa Puasa',
                        'bidangPeriksa' => 'Kimia Klinik',
                        'tarif' => 35000,
                        'hasilPemeriksaan' => null,
                    ],
                ],
                'total_tarif' => 90000,
                'status' => 'draft',
                'catatan_koreksi' => null,
                'tanggal_pemeriksaan' => now(),
                'submitted_at' => null,
            ],
            // Struk 2 - Menunggu Koreksi status
            [
                'kode_struk' => 'STRUK-250324-' . strtoupper(Str::random(6)),
                'siswa_id' => $siswa->where('nis', 'S002')->first()->id,
                'pasien_id' => $pasien->where('nama_pasien', 'Ubaidah')->first()->id,
                'pemeriksaans' => [
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'SGOT (AST)')->first()->id,
                        'subPeriksa' => 'SGOT (AST)',
                        'bidangPeriksa' => 'Kimia Klinik',
                        'tarif' => 45000,
                        'hasilPemeriksaan' => '25 U/L',
                    ],
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'SGPT (ALT)')->first()->id,
                        'subPeriksa' => 'SGPT (ALT)',
                        'bidangPeriksa' => 'Kimia Klinik',
                        'tarif' => 45000,
                        'hasilPemeriksaan' => '28 U/L',
                    ],
                ],
                'total_tarif' => 90000,
                'status' => 'menunggu_koreksi',
                'catatan_koreksi' => null,
                'tanggal_pemeriksaan' => now()->subDay(),
                'submitted_at' => now()->subHours(2),
            ],
            // Struk 3 - Approved
            [
                'kode_struk' => 'STRUK-250323-' . strtoupper(Str::random(6)),
                'siswa_id' => $siswa->where('nis', 'S002')->first()->id,
                'pasien_id' => $pasien->where('nama_pasien', 'Slamet')->first()->id,
                'pemeriksaans' => [
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Hemoglobin')->first()->id,
                        'subPeriksa' => 'Hemoglobin',
                        'bidangPeriksa' => 'Hematologi',
                        'tarif' => 25000,
                        'hasilPemeriksaan' => '13.5 g/dL',
                    ],
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Trombosit')->first()->id,
                        'subPeriksa' => 'Trombosit',
                        'bidangPeriksa' => 'Hematologi',
                        'tarif' => 30000,
                        'hasilPemeriksaan' => '250 ribu/µL',
                    ],
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Ureum')->first()->id,
                        'subPeriksa' => 'Ureum',
                        'bidangPeriksa' => 'Kimia Klinik',
                        'tarif' => 40000,
                        'hasilPemeriksaan' => '32 mg/dL',
                    ],
                ],
                'total_tarif' => 95000,
                'status' => 'approve',
                'catatan_koreksi' => 'Hasil diterima, siap untuk pembayaran',
                'approved_at' => now()->subDays(1),
                'approved_by' => 1, // Admin user
                'tanggal_pemeriksaan' => now()->subDays(2),
                'submitted_at' => now()->subDays(2),
            ],
            // Struk 4 - Tolak (Rejected)
            [
                'kode_struk' => 'STRUK-250322-' . strtoupper(Str::random(6)),
                'siswa_id' => $siswa->where('nis', 'S001')->first()->id,
                'pasien_id' => $pasien->where('nama_pasien', 'Agus Setiawan')->first()->id,
                'pemeriksaans' => [
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Kreatinin')->first()->id,
                        'subPeriksa' => 'Kreatinin',
                        'bidangPeriksa' => 'Kimia Klinik',
                        'tarif' => 40000,
                        'hasilPemeriksaan' => '0.8 mg/dL',
                    ],
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'HBsAg')->first()->id,
                        'subPeriksa' => 'HBsAg',
                        'bidangPeriksa' => 'Imunoserologi',
                        'tarif' => 75000,
                        'hasilPemeriksaan' => 'Non Reaktif',
                    ],
                ],
                'total_tarif' => 115000,
                'status' => 'tolak',
                'catatan_koreksi' => 'Format hasil pemeriksaan kurang jelas. Mohon diulangi dengan format yang telah ditentukan.',
                'approved_at' => now()->subDays(1),
                'approved_by' => 1, // Admin user
                'tanggal_pemeriksaan' => now()->subDays(3),
                'submitted_at' => now()->subDays(3),
            ],
            // Struk 5 - Another draft example
            [
                'kode_struk' => 'STRUK-250321-' . strtoupper(Str::random(6)),
                'siswa_id' => $siswa->where('nis', 'S003')->first()->id,
                'pasien_id' => $pasien->where('nama_pasien', 'Fitri Handayani')->first()->id,
                'pemeriksaans' => [
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Warna Urin')->first()->id,
                        'subPeriksa' => 'Warna Urin',
                        'bidangPeriksa' => 'Urinalisis',
                        'tarif' => 15000,
                        'hasilPemeriksaan' => null,
                    ],
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'pH Urin')->first()->id,
                        'subPeriksa' => 'pH Urin',
                        'bidangPeriksa' => 'Urinalisis',
                        'tarif' => 15000,
                        'hasilPemeriksaan' => null,
                    ],
                ],
                'total_tarif' => 30000,
                'status' => 'draft',
                'catatan_koreksi' => null,
                'tanggal_pemeriksaan' => now(),
                'submitted_at' => null,
            ],
            // Struk 6 - Approved example
            [
                'kode_struk' => 'STRUK-250320-' . strtoupper(Str::random(6)),
                'siswa_id' => $siswa->where('nis', 'S002')->first()->id,
                'pasien_id' => $pasien->where('nama_pasien', 'Bambang Susilo')->first()->id,
                'pemeriksaans' => [
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Hemoglobin')->first()->id,
                        'subPeriksa' => 'Hemoglobin',
                        'bidangPeriksa' => 'Hematologi',
                        'tarif' => 25000,
                        'hasilPemeriksaan' => '14.2 g/dL',
                    ],
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Hematokrit')->first()->id,
                        'subPeriksa' => 'Hematokrit',
                        'bidangPeriksa' => 'Hematologi',
                        'tarif' => 25000,
                        'hasilPemeriksaan' => '42%',
                    ],
                    [
                        'idPemeriksaan' => $pemeriksaan->where('sub_periksa', 'Glukosa Puasa')->first()->id,
                        'subPeriksa' => 'Glukosa Puasa',
                        'bidangPeriksa' => 'Kimia Klinik',
                        'tarif' => 35000,
                        'hasilPemeriksaan' => '85 mg/dL',
                    ],
                ],
                'total_tarif' => 85000,
                'status' => 'approve',
                'catatan_koreksi' => 'Semua hasil baik, siap pembayaran',
                'approved_at' => now()->subDays(2),
                'approved_by' => 1, // Admin user
                'tanggal_pemeriksaan' => now()->subDays(4),
                'submitted_at' => now()->subDays(4),
            ],
        ];

        foreach ($struks as $struk) {
            Struk::create($struk);
        }
    }
}
