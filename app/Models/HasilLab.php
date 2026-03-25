<?php
// ============================================================
// FILE: app/Models/HasilLab.php
// Maps from: pemeriksaan_hasillab in Supabase
// Final lab report created by asesor after reviewing struk
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HasilLab extends Model
{
  protected $table = 'hasil_lab';
  protected $fillable = [
    'kode_hasil',
    'struk_id',
    'siswa_id',
    'pasien_id',
    'nama_asesor',
    'pemeriksaans',
    'catatan_asesor',
    'nilai',
    'tanggal_pemeriksaan',
    'tanggal_cetak'
  ];
  protected $casts = [
    'pemeriksaans' => 'array',
    'tanggal_pemeriksaan' => 'datetime',
    'tanggal_cetak' => 'datetime',
  ];

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($hasil) {
      if (empty($hasil->kode_hasil)) {
        $hasil->kode_hasil = 'HASIL-' .
          now()->format('ymd') . '-' .
          strtoupper(Str::random(5));
      }
    });
  }

  public function struk()
  {
    return $this->belongsTo(Struk::class);
  }

  public function siswa()
  {
    return $this->belongsTo(Siswa::class);
  }

  public function pasien()
  {
    return $this->belongsTo(Pasien::class);
  }
}
