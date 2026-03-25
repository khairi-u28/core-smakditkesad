<?php
// ============================================================
// FILE: app/Models/Staff.php
// ============================================================
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
    protected $fillable = [
        'nama', 'nip', 'jabatan', 'bidang', 'pendidikan',
        'spesialisasi', 'jenis', 'foto', 'urutan', 'is_active'
    ];
    protected $casts = ['is_active' => 'boolean'];
}