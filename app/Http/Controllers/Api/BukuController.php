<?php
// ============================================================
// FILE: app/Http/Controllers/Api/BukuController.php
// Protected API — E-Library books and PDF streaming
// PDFs served via Laravel, never direct URL
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BukuController extends Controller
{
  public function index(Request $request)
  {
    $buku = \App\Models\Buku::where('is_active', true)
      ->with('kategori:id,nama,slug')
      ->when($request->kategori, fn($q) =>
      $q->whereHas('kategori', fn($k) => $k->where('slug', $request->kategori)))
      ->when($request->q, fn($q) =>
      $q->where('judul', 'like', '%' . $request->q . '%')
        ->orWhere('pengarang', 'like', '%' . $request->q . '%'))
      ->select(['id', 'kategori_id', 'judul', 'pengarang', 'penerbit', 'tahun_terbit', 'deskripsi', 'cover'])
      // NOTE: file_pdf NOT selected — never expose the path
      ->paginate(12);

    return response()->json(['success' => true, 'data' => $buku]);
  }

  public function show($id)
  {
    $buku = \App\Models\Buku::with('kategori:id,nama')
      ->where('is_active', true)
      ->select(['id', 'kategori_id', 'judul', 'pengarang', 'penerbit', 'tahun_terbit', 'isbn', 'deskripsi', 'cover'])
      ->findOrFail($id);

    return response()->json(['success' => true, 'data' => $buku]);
  }

  public function streamPdf($id)
  {
    $buku = \App\Models\Buku::where('id', $id)->where('is_active', true)->firstOrFail();

    $path = storage_path('app/' . $buku->file_pdf);

    if (!file_exists($path)) {
      return response()->json(['message' => 'File tidak ditemukan'], 404);
    }

    // Log access
    \App\Models\HasilLab::create([]);  // TODO: implement buku_akses_log

    return response()->file($path, [
      'Content-Type'              => 'application/pdf',
      'Content-Disposition'       => 'inline; filename="' . rawurlencode($buku->judul) . '.pdf"',
      'X-Content-Type-Options'    => 'nosniff',
      'Cache-Control'             => 'no-store, no-cache',
    ]);
  }
}
