<?php
// ============================================================
// FILE: database/seeders/PasienSeeder.php
// Migrates from: pasien table in Supabase (11 existing patients)
// Assigns to siswa_id based on petugas mapping (P002=S002, P003=S003)
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Pasien;

class PasienSeeder extends Seeder
{
  public function run(): void
  {
    $siswaP002 = Siswa::where('nis', 'S002')->first();
    $siswaP003 = Siswa::where('nis', 'S003')->first();
    $siswaP001 = Siswa::where('nis', 'S001')->first();

    $pasien = [
      ['kode_registrasi' => 'REG250403-56789', 'siswa_id' => $siswaP002->id, 'nama_pasien' => 'Hendra Gunawan',  'kategori_pasien' => 'Umum',          'jenis_kelamin' => 'Laki-laki',  'golongan_darah' => 'A',  'status_pernikahan' => 'Belum Menikah', 'no_telepon' => '081890123456', 'pekerjaan' => 'Pelajar',         'alamat' => 'Jl. Flamboyan No. 3, Jakarta Pusat'],
      ['kode_registrasi' => 'REG250410-W43P2', 'siswa_id' => $siswaP002->id, 'nama_pasien' => 'Ubaidah',         'kategori_pasien' => 'BPJS',          'jenis_kelamin' => 'Laki-laki',  'golongan_darah' => 'B',  'status_pernikahan' => 'Menikah',       'no_telepon' => '081012345678', 'pekerjaan' => 'Pegawai Swasta',  'alamat' => 'Cipayung Munjul Raya 222',        'dokter_pengirim' => 'Dr. Who'],
      ['kode_registrasi' => 'REG250410-1IDSJ', 'siswa_id' => $siswaP002->id, 'nama_pasien' => 'Slamet',          'kategori_pasien' => 'Umum',          'jenis_kelamin' => 'Laki-laki',  'golongan_darah' => 'O',  'status_pernikahan' => 'Cerai',         'no_telepon' => '085161615038', 'pekerjaan' => 'Mulung',          'alamat' => 'Jl. Tepi Surga',                 'dokter_pengirim' => 'Dr. Deni Arisandi'],
      ['kode_registrasi' => 'REG250405-UVWXY', 'siswa_id' => $siswaP001->id, 'nama_pasien' => 'Agus Setiawan',  'kategori_pasien' => 'Umum',          'jenis_kelamin' => 'Laki-laki',  'golongan_darah' => 'O',  'status_pernikahan' => 'Menikah',       'no_telepon' => '081678901234', 'pekerjaan' => 'PNS',             'alamat' => 'Jl. Cempaka No. 8, Tangerang'],
      ['kode_registrasi' => 'REG250331-GHI03', 'siswa_id' => $siswaP003->id, 'nama_pasien' => 'Fitri Handayani', 'kategori_pasien' => 'Asuransi Lain', 'jenis_kelamin' => 'Perempuan',  'golongan_darah' => 'A',  'status_pernikahan' => 'Belum Menikah', 'no_telepon' => '081123456789', 'pekerjaan' => 'Karyawan Swasta', 'alamat' => 'Jl. Teratai No. 1, Cibubur',    'dokter_pengirim' => 'Dr. Wijaya'],
      ['kode_registrasi' => 'REG250409-ABCDE', 'siswa_id' => $siswaP002->id, 'nama_pasien' => 'Bambang Susilo', 'kategori_pasien' => 'Umum',          'jenis_kelamin' => 'Laki-laki',  'golongan_darah' => 'O',  'status_pernikahan' => 'Menikah',       'no_telepon' => '081234567890', 'pekerjaan' => 'Wiraswasta',      'alamat' => 'Jl. Melati No. 10, Jakarta Timur', 'dokter_pengirim' => 'Dr. Handoko'],
      ['kode_registrasi' => 'REG250408-FGHIJ', 'siswa_id' => $siswaP002->id, 'nama_pasien' => 'Siti Aminah',   'kategori_pasien' => 'BPJS',          'jenis_kelamin' => 'Perempuan',  'golongan_darah' => 'A',  'status_pernikahan' => 'Menikah',       'no_telepon' => '081345678901', 'pekerjaan' => 'Ibu Rumah Tangga', 'alamat' => 'Jl. Mawar No. 5, Bekasi',         'dokter_pengirim' => 'Dr. Puspita'],
      ['kode_registrasi' => 'REG250406-PQRST', 'siswa_id' => $siswaP002->id, 'nama_pasien' => 'Dewi Lestari',  'kategori_pasien' => 'Asuransi Lain', 'jenis_kelamin' => 'Perempuan',  'golongan_darah' => 'AB', 'status_pernikahan' => 'Belum Menikah', 'no_telepon' => '081567890123', 'pekerjaan' => 'Mahasiswa',       'alamat' => 'Jl. Anggrek No. 20, Jakarta Selatan', 'dokter_pengirim' => 'Dr. Wijaya'],
      ['kode_registrasi' => 'REG250410-LJ2SD', 'siswa_id' => $siswaP002->id, 'nama_pasien' => 'Khairi',        'kategori_pasien' => 'BPJS',          'jenis_kelamin' => 'Laki-laki',  'golongan_darah' => 'O',  'status_pernikahan' => 'Belum Menikah', 'no_telepon' => '08123456789',  'pekerjaan' => 'Pegawai Swasta',  'alamat' => 'Jl. Raya Yang Luas No. Berapa Saja'],
      ['kode_registrasi' => 'REG250410-VRADI', 'siswa_id' => $siswaP002->id, 'nama_pasien' => 'Khairi',        'kategori_pasien' => 'BPJS',          'jenis_kelamin' => 'Laki-laki',  'golongan_darah' => 'O',  'status_pernikahan' => 'Menikah',       'no_telepon' => '08123456789',  'pekerjaan' => 'Pegawai Swasta',  'alamat' => 'Indonesia Raya 123'],
      ['kode_registrasi' => 'REG250401-DEF02', 'siswa_id' => $siswaP003->id, 'nama_pasien' => 'Dedi Supardi',  'kategori_pasien' => 'BPJS',          'jenis_kelamin' => 'Laki-laki',  'golongan_darah' => 'AB', 'status_pernikahan' => 'Menikah',       'no_telepon' => '081012345678', 'pekerjaan' => 'Supir',           'alamat' => 'Jl. Seroja No. 11, Bekasi Utara', 'dokter_pengirim' => 'Dr. Puspita'],
    ];

    foreach ($pasien as $p) {
      Pasien::updateOrCreate(['kode_registrasi' => $p['kode_registrasi']], $p);
    }
  }
}
