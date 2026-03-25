<?php
// ============================================================
// FILE: app/Models/Post.php
// ============================================================
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'judul', 'slug', 'kategori', 'konten',
        'thumbnail', 'penulis', 'published', 'published_at'
    ];
    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->judul);
            }
        });
    }
}