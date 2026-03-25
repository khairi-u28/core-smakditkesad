<?php
// ============================================================
// FILE: app/Models/Pasien.php
// Maps from: pasien table in Supabase
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pasien extends Model
{
  protected $table = 'pasien';
  protected $fillable = [
    'kode_registrasi',
    'siswa_id',
    'nama_pasien',
    'kategori_pasien',
    'jenis_kelamin',
    'golongan_darah',
    'status_pernikahan',
    'no_telepon',
    'pekerjaan',
    'no_kk',
    'nama_ayah',
    'nama_ibu',
    'alamat',
    'dokter_pengirim',
    'tanggal_registrasi'
  ];
  protected $casts = ['tanggal_registrasi' => 'datetime'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($pasien) {
      if (empty($pasien->kode_registrasi)) {
        // Format: REG250410-XXXXX (same as existing system)
        $pasien->kode_registrasi = 'REG' .
          now()->format('ymd') . '-' .
          strtoupper(Str::random(5));
      }
    });
  }

  public function siswa()
  {
    return $this->belongsTo(Siswa::class);
  }

  public function struks()
  {
    return $this->hasMany(Struk::class);
  }
}
