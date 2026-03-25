<?php
// ============================================================
// FILE: app/Models/JenisPemeriksaan.php
// Maps from: pemeriksaan table in Supabase
// Master catalog of all 12 lab tests
// ============================================================
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JenisPemeriksaan extends Model
{
    protected $table = 'jenis_pemeriksaan';
    protected $fillable = [
        'kode', 'bidang_periksa', 'tipe_periksa', 'sub_periksa',
        'nilai_normal', 'satuan', 'tarif', 'is_active'
    ];
    protected $casts = [
        'tarif' => 'integer',
        'is_active' => 'boolean',
    ];
}