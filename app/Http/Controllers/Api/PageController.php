<?php
// ============================================================
// FILE: app/Http/Controllers/Api/PageController.php
// Public API — static pages (profil, sejarah, ppdb, fasilitas)
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->firstOrFail();
        return response()->json(['success' => true, 'data' => $page]);
    }
}
