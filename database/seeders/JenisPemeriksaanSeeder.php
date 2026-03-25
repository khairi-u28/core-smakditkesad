<?php
// ============================================================
// FILE: database/seeders/JenisPemeriksaanSeeder.php
// Migrates from: pemeriksaan table in Supabase (12 existing tests)
// This is the complete master catalog
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPemeriksaan;

class JenisPemeriksaanSeeder extends Seeder
{
  public function run(): void
  {
    $pemeriksaan = [
      // Hematologi - Darah Lengkap
      ['kode' => '1',  'bidang_periksa' => 'Hematologi',    'tipe_periksa' => 'Darah Lengkap',            'sub_periksa' => 'Hemoglobin',    'nilai_normal' => '12.0-14.0(P) 13.0-16.0(L)', 'satuan' => 'g/dL',     'tarif' => 25000],
      ['kode' => '2',  'bidang_periksa' => 'Hematologi',    'tipe_periksa' => 'Darah Lengkap',            'sub_periksa' => 'Leukosit',      'nilai_normal' => '4.0-10.0',                   'satuan' => 'ribu/µL',   'tarif' => 30000],
      ['kode' => '3',  'bidang_periksa' => 'Hematologi',    'tipe_periksa' => 'Darah Lengkap',            'sub_periksa' => 'Trombosit',     'nilai_normal' => '150-450',                    'satuan' => 'ribu/µL',   'tarif' => 30000],
      ['kode' => '4',  'bidang_periksa' => 'Hematologi',    'tipe_periksa' => 'Darah Lengkap',            'sub_periksa' => 'Hematokrit',    'nilai_normal' => '37-43(P) 40-50(L)',          'satuan' => '%',         'tarif' => 25000],
      // Kimia Klinik
      ['kode' => '5',  'bidang_periksa' => 'Kimia Klinik',  'tipe_periksa' => 'Fungsi Hati',              'sub_periksa' => 'SGOT (AST)',    'nilai_normal' => '< 31(P) < 37(L)',            'satuan' => 'U/L',       'tarif' => 45000],
      ['kode' => '6',  'bidang_periksa' => 'Kimia Klinik',  'tipe_periksa' => 'Fungsi Hati',              'sub_periksa' => 'SGPT (ALT)',    'nilai_normal' => '< 31(P) < 41(L)',            'satuan' => 'U/L',       'tarif' => 45000],
      ['kode' => '7',  'bidang_periksa' => 'Kimia Klinik',  'tipe_periksa' => 'Fungsi Ginjal',            'sub_periksa' => 'Ureum',         'nilai_normal' => '10-50',                      'satuan' => 'mg/dL',     'tarif' => 40000],
      ['kode' => '8',  'bidang_periksa' => 'Kimia Klinik',  'tipe_periksa' => 'Fungsi Ginjal',            'sub_periksa' => 'Kreatinin',     'nilai_normal' => '0.6-1.1(P) 0.7-1.3(L)',     'satuan' => 'mg/dL',     'tarif' => 40000],
      ['kode' => '9',  'bidang_periksa' => 'Kimia Klinik',  'tipe_periksa' => 'Metabolisme Karbohidrat', 'sub_periksa' => 'Glukosa Puasa', 'nilai_normal' => '70-100',                     'satuan' => 'mg/dL',     'tarif' => 35000],
      // Urinalisis
      ['kode' => '10', 'bidang_periksa' => 'Urinalisis',    'tipe_periksa' => 'Urin Lengkap',             'sub_periksa' => 'Warna Urin',    'nilai_normal' => 'Kuning Jernih',              'satuan' => '-',         'tarif' => 15000],
      ['kode' => '11', 'bidang_periksa' => 'Urinalisis',    'tipe_periksa' => 'Urin Lengkap',             'sub_periksa' => 'pH Urin',       'nilai_normal' => '4.5-8.0',                    'satuan' => '-',         'tarif' => 15000],
      // Imunoserologi
      ['kode' => '12', 'bidang_periksa' => 'Imunoserologi', 'tipe_periksa' => 'Hepatitis',                'sub_periksa' => 'HBsAg',         'nilai_normal' => 'Non Reaktif',                'satuan' => '-',         'tarif' => 75000],
    ];

    foreach ($pemeriksaan as $p) {
      JenisPemeriksaan::updateOrCreate(['kode' => $p['kode']], $p);
    }
  }
}
