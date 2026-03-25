<?php
// ============================================================
// FILE: app/Http/Controllers/Api/KasirController.php
// Protected API — Full Kasir Lab workflow
// Maps from the existing Supabase API behavior
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KasirController extends Controller
{
  // GET /api/v1/kasir/paket → list semua jenis pemeriksaan aktif
  // Used by siswa to pick which tests to include in struk
  public function paketIndex()
  {
    $paket = \App\Models\JenisPemeriksaan::where('is_active', true)
      ->orderBy('bidang_periksa')
      ->orderBy('tipe_periksa')
      ->get();

    // Group by bidang for easier UI rendering
    $grouped = $paket->groupBy('bidang_periksa');

    return response()->json(['success' => true, 'data' => $grouped]);
  }

  // GET /api/v1/kasir/pasien → list pasien milik siswa ini saja
  public function pasienIndex(Request $request)
  {
    $pasien = \App\Models\Pasien::where('siswa_id', $request->user()->id)
      ->orderByDesc('tanggal_registrasi')
      ->get();

    return response()->json(['success' => true, 'data' => $pasien]);
  }

  // POST /api/v1/kasir/pasien → daftarkan pasien baru
  public function pasienStore(Request $request)
  {
    $validated = $request->validate([
      'nama_pasien'      => 'required|string',
      'kategori_pasien'  => 'required|in:Umum,BPJS,Asuransi Lain',
      'jenis_kelamin'    => 'required|in:Laki-laki,Perempuan',
      'golongan_darah'   => 'nullable|in:A,B,AB,O,-',
      'status_pernikahan' => 'nullable|in:Belum Menikah,Menikah,Cerai',
      'no_telepon'       => 'nullable|string',
      'pekerjaan'        => 'nullable|string',
      'no_kk'            => 'nullable|string',
      'nama_ayah'        => 'nullable|string',
      'nama_ibu'         => 'nullable|string',
      'alamat'           => 'nullable|string',
      'dokter_pengirim'  => 'nullable|string',
    ]);

    $validated['siswa_id'] = $request->user()->id;

    $pasien = \App\Models\Pasien::create($validated);

    return response()->json(['success' => true, 'data' => $pasien], 201);
  }

  // GET /api/v1/kasir/struk → list struk milik siswa ini
  public function strukIndex(Request $request)
  {
    $struks = \App\Models\Struk::with('pasien:id,nama_pasien,kategori_pasien')
      ->where('siswa_id', $request->user()->id)
      ->orderByDesc('tanggal_pemeriksaan')
      ->get();

    return response()->json(['success' => true, 'data' => $struks]);
  }

  // POST /api/v1/kasir/struk → buat struk baru
  // Body: { pasien_id, pemeriksaans: [{idPemeriksaan, subPeriksa, tarif, hasilPemeriksaan}] }
  public function strukStore(Request $request)
  {
    $validated = $request->validate([
      'pasien_id'                          => 'required|exists:pasien,id',
      'pemeriksaans'                       => 'required|array|min:1',
      'pemeriksaans.*.idPemeriksaan'       => 'required',
      'pemeriksaans.*.subPeriksa'          => 'required|string',
      'pemeriksaans.*.tarif'               => 'required|integer',
      'pemeriksaans.*.hasilPemeriksaan'    => 'nullable|string',
    ]);

    // Verify pasien belongs to this siswa
    $pasien = \App\Models\Pasien::where('id', $validated['pasien_id'])
      ->where('siswa_id', $request->user()->id)
      ->firstOrFail();

    // Calculate total
    $total = collect($validated['pemeriksaans'])->sum('tarif');

    $struk = \App\Models\Struk::create([
      'siswa_id'     => $request->user()->id,
      'pasien_id'    => $pasien->id,
      'pemeriksaans' => $validated['pemeriksaans'],
      'total_tarif'  => $total,
      'status'       => 'draft',
    ]);

    return response()->json(['success' => true, 'data' => $struk], 201);
  }

  // PUT /api/v1/kasir/struk/{id} → update hasil pemeriksaan (while in draft)
  public function strukUpdate(Request $request, $id)
  {
    $struk = \App\Models\Struk::where('id', $id)
      ->where('siswa_id', $request->user()->id)
      ->where('status', 'draft')
      ->firstOrFail();

    $validated = $request->validate([
      'pemeriksaans' => 'sometimes|array',
      'pemeriksaans.*.hasilPemeriksaan' => 'nullable|string',
    ]);

    if (isset($validated['pemeriksaans'])) {
      $total = collect($validated['pemeriksaans'])->sum('tarif');
      $struk->update([
        'pemeriksaans' => $validated['pemeriksaans'],
        'total_tarif'  => $total,
      ]);
    }

    return response()->json(['success' => true, 'data' => $struk->fresh()]);
  }

  // POST /api/v1/kasir/struk/{id}/submit → siswa submits for review
  public function strukSubmit(Request $request, $id)
  {
    $struk = \App\Models\Struk::where('id', $id)
      ->where('siswa_id', $request->user()->id)
      ->where('status', 'draft')
      ->firstOrFail();

    $struk->update([
      'status'       => 'submitted',
      'submitted_at' => now(),
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Struk berhasil disubmit, menunggu koreksi asesor.',
      'data'    => $struk,
    ]);
  }

  // GET /api/v1/kasir/hasil → get hasil lab (after asesor approves)
  public function hasilIndex(Request $request)
  {
    $hasil = \App\Models\HasilLab::with([
      'pasien:id,nama_pasien',
      'struk:id,kode_struk',
    ])
      ->where('siswa_id', $request->user()->id)
      ->orderByDesc('created_at')
      ->get();

    return response()->json(['success' => true, 'data' => $hasil]);
  }

  // GET /api/v1/kasir/hasil/{id} → detail hasil lab (for printing)
  public function hasilShow(Request $request, $id)
  {
    $hasil = \App\Models\HasilLab::with(['pasien', 'siswa:id,nis,nama'])
      ->where('id', $id)
      ->where('siswa_id', $request->user()->id)
      ->firstOrFail();

    return response()->json(['success' => true, 'data' => $hasil]);
  }
}
