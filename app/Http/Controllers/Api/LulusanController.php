<?php
// ============================================================
// FILE: app/Http/Controllers/Api/LulusanController.php
// Public API — alumni profiles and testimonials
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class LulusanController extends Controller
{
    public function index()
    {
        $lulusan = \App\Models\Lulusan::where('is_active', true)
            ->select(['id','nama','angkatan','tahun_lulus','testimoni','foto','instansi_kerja','posisi_kerja'])
            ->orderByDesc('tahun_lulus')
            ->get();

        return response()->json(['success' => true, 'data' => $lulusan]);
    }
}