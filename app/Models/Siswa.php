<?php
// ============================================================
// FILE: app/Models/Siswa.php
// The single unified auth model for Kasir Lab + E-Library
// ============================================================
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'siswa';
    protected $fillable = [
        'nis', 'nama', 'kelas', 'jurusan',
        'jenis_kelamin', 'password', 'keterangan', 'is_active'
    ];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['is_active' => 'boolean'];

    public function pasien()
    {
        return $this->hasMany(Pasien::class);
    }

    public function struks()
    {
        return $this->hasMany(Struk::class);
    }

    public function hasilLabs()
    {
        return $this->hasMany(HasilLab::class);
    }
}