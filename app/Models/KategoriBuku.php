<?php
// ============================================================
// FILE: app/Models/KategoriBuku.php
// ============================================================
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriBuku extends Model
{
    protected $table = 'kategori_buku';
    protected $fillable = ['nama', 'slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($k) => $k->slug = Str::slug($k->nama));
    }

    public function buku()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}