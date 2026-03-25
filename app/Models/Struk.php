<?php
// ============================================================
// FILE: app/Models/Struk.php
// Maps from: pemeriksaan_struk in Supabase
// Student's lab transaction - selected tests + filled results
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Struk extends Model
{
  protected $table = 'struk';
  protected $fillable = [
    'kode_struk',
    'siswa_id',
    'pasien_id',
    'pemeriksaans',
    'total_tarif',
    'status',
    'catatan_koreksi',
    'approved_at',
    'approved_by',
    'tanggal_pemeriksaan',
    'submitted_at'
  ];
  protected $casts = [
    'pemeriksaans' => 'array',          // JSON <-> PHP array auto-cast
    'total_tarif' => 'decimal:2',
    'approved_at' => 'datetime',
    'tanggal_pemeriksaan' => 'datetime',
    'submitted_at' => 'datetime',
  ];

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($struk) {
      if (empty($struk->kode_struk)) {
        // Format: STRUK-250410-JH2DJY (same as existing)
        $struk->kode_struk = 'STRUK-' .
          now()->format('ymd') . '-' .
          strtoupper(Str::random(6));
      }
    });
  }

  public function siswa()
  {
    return $this->belongsTo(Siswa::class);
  }

  public function pasien()
  {
    return $this->belongsTo(Pasien::class);
  }

  public function approvedBy()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  public function hasilLab()
  {
    return $this->hasOne(HasilLab::class);
  }
}
