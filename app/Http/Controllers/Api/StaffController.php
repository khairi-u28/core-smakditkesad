<?php
// ============================================================
// FILE: app/Http/Controllers/Api/StaffController.php
// Public API — tenaga pendidik for Landing Page
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffController extends Controller
{
  public function index(Request $request)
  {
    $staff = \App\Models\Staff::where('is_active', true)
      ->when($request->jenis, fn($q) => $q->where('jenis', $request->jenis))
      ->select(['id', 'nama', 'jabatan', 'bidang', 'pendidikan', 'spesialisasi', 'jenis', 'foto', 'urutan'])
      ->orderBy('urutan')
      ->get();

    return response()->json(['success' => true, 'data' => $staff]);
  }
}
