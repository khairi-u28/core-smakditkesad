<?php
// ============================================================
// FILE: app/Http/Controllers/Api/AuthController.php
// Single login endpoint for both Kasir Lab and E-Library
// Auth by NIS + password → Sanctum token
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $request->validate([
      'nis'      => 'required|string',
      'password' => 'required|string',
    ]);

    $siswa = Siswa::where('nis', $request->nis)
      ->where('is_active', true)
      ->first();

    if (!$siswa || !Hash::check($request->password, $siswa->password)) {
      return response()->json([
        'success' => false,
        'message' => 'NIS atau password salah.',
      ], 401);
    }

    // Revoke all previous tokens, create new one
    $siswa->tokens()->delete();
    $token = $siswa->createToken('auth-token')->plainTextToken;

    return response()->json([
      'success' => true,
      'token'   => $token,
      'siswa'   => [
        'id'      => $siswa->id,
        'nis'     => $siswa->nis,
        'nama'    => $siswa->nama,
        'kelas'   => $siswa->kelas,
        'jurusan' => $siswa->jurusan,
      ],
    ]);
  }

  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['success' => true, 'message' => 'Logout berhasil.']);
  }

  public function me(Request $request)
  {
    return response()->json([
      'success' => true,
      'data'    => $request->user()->only(['id', 'nis', 'nama', 'kelas', 'jurusan']),
    ]);
  }
}
