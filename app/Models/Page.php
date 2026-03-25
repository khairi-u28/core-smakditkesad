<?php
// ============================================================
// FILE: app/Models/Page.php
// ============================================================
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['slug', 'judul', 'konten'];
    protected $casts = ['konten' => 'array'];
}