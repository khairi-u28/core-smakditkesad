<?php
// ============================================================
// FILE: app/Models/Buku.php
// ============================================================
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = [
        'kategori_id', 'judul', 'pengarang', 'penerbit',
        'tahun_terbit', 'isbn', 'deskripsi',
        'file_pdf', 'cover', 'is_active'
    ];
    protected $casts = ['is_active' => 'boolean'];

    public function kategori()
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }
}