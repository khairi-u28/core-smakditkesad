<?php
// ============================================================
// FILE: app/Http/Controllers/Api/PostController.php
// Public API for Landing Page — berita, pengumuman, kegiatan
// Uses ISR on Next.js side (revalidate: 300)
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
  public function index(Request $request)
  {
    $posts = \App\Models\Post::where('published', true)
      ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
      ->select(['id', 'judul', 'slug', 'kategori', 'thumbnail', 'penulis', 'published_at'])
      ->orderByDesc('published_at')
      ->paginate(10);

    return response()->json(['success' => true, 'data' => $posts]);
  }

  public function show($slug)
  {
    $post = \App\Models\Post::where('slug', $slug)
      ->where('published', true)
      ->firstOrFail();

    return response()->json(['success' => true, 'data' => $post]);
  }
}
