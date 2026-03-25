<?php
// ============================================================
// FILE: app/Models/Lulusan.php
// ============================================================
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lulusan extends Model
{
    protected $table = 'lulusan';

    protected $fillable = [
        'nama', 'angkatan', 'tahun_lulus', 'testimoni',
        'foto', 'instansi_kerja', 'posisi_kerja', 'is_active'
    ];
    protected $casts = ['is_active' => 'boolean'];
}