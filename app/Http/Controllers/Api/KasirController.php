<?php
// ============================================================
// FILE: app/Http/Controllers/Api/KasirController.php
// Handles all Lab Asnakes simulation endpoints for Siswa.
// Auth: auth:sanctum — $request->user() returns Siswa instance.
// Status flow: draft -> menunggu_koreksi -> approve | tolak
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisPemeriksaan;
use App\Models\Pasien;
use Illuminate\Http\Request;

class KasirController extends Controller
{
  // GET /api/v1/kasir/paket
  public function paketIndex()
  {
    $paket = JenisPemeriksaan::where('is_active', true)
      ->select(['id', 'kode', 'bidang_periksa', 'tipe_periksa', 'sub_periksa', 'nilai_normal', 'satuan', 'tarif'])
      ->orderBy('bidang_periksa')->orderBy('tipe_periksa')
      ->get()->groupBy('bidang_periksa')->map(fn($i) => $i->values());
    return response()->json(['success' => true, 'data' => $paket]);
  }

  // GET /api/v1/kasir/pasien
  public function pasienIndex(Request $request)
  {
    return response()->json(['success' => true, 'data' => $request->user()->pasien()
      ->select(['id','kode_registrasi','nama_pasien','kategori_pasien','jenis_kelamin','golongan_darah','no_telepon','alamat','tanggal_registrasi'])
      ->orderByDesc('tanggal_registrasi')->get()]);
  }

  // POST /api/v1/kasir/pasien
  public function pasienStore(Request $request)
  {
    $v = $request->validate([
      'nama_pasien'       =>'required|string|max:255',
      'kategori_pasien'   =>'required|in:Umum,BPJS,Asuransi Lain',
      'jenis_kelamin'     =>'required|in:Laki-laki,Perempuan',
      'golongan_darah'    =>'nullable|in:A,B,AB,O,-',
      'status_pernikahan' =>'nullable|in:Belum Menikah,Menikah,Cerai',
      'no_telepon'        =>'nullable|string|max:20',
      'pekerjaan'         =>'nullable|string|max:255',
      'no_kk'             =>'nullable|string|max:20',
      'nama_ayah'         =>'nullable|string|max:255',
      'nama_ibu'          =>'nullable|string|max:255',
      'alamat'            =>'nullable|string|max:1000',
      'dokter_pengirim'   =>'nullable|string|max:255',
      'tanggal_registrasi'=>'nullable|date',
    ]);
    return response()->json(['success'=>true,'data'=>$request->user()->pasien()->create($v)],201);
  }

  // GET /api/v1/kasir/struk
  public function strukIndex(Request $request)
  {
    return response()->json(['success'=>true,'data'=> $request->user()->struks()
      ->with('pasien:id,nama_pasien,kode_registrasi')
      ->select(['id','kode_struk','pasien_id','total_tarif','status','catatan_koreksi','tanggal_pemeriksaan','submitted_at','approved_at'])
      ->orderByDesc('tanggal_pemeriksaan')->get()]);
  }

  // POST /api/v1/kasir/struk
  public function strukStore(Request $request)
  {
    $v = $request->validate([
      'pasien_id'                    =>'required|integer',
      'pemeriksaans'                 =>'required|array|min:1',
      'pemeriksaans.*.idPemeriksaan' =>'required|integer|exists:jenis_pemeriksaan,id',
      'tanggal_pemeriksaan'          =>'nullable|date',
    ]);
    $pasien = Pasien::where('id',$v['pasien_id'])->where('siswa_id',$request->user()->id)->firstOrFail();
    $jenisMap = JenisPemeriksaan::whereIn('id', collect($v['pemeriksaans'])->pluck('idPemeriksaan')->unique())->get()->keyBy('id');
    $pem = collect($v['pemeriksaans'])->map(function($item) use($jenisMap){
      $j=$jenisMap->get($item['idPemeriksaan']); if(!$j)return null;
      return ['idPemeriksaan'=>$j->id,'bidangPeriksa'=>$j->bidang_periksa,'tipePeriksa'=>$j->tipe_periksa,
              'subPeriksa'=>$j->sub_periksa,'nilaiNormal'=>$j->nilai_normal,'satuan'=>$j->satuan,
              'tarif'=>$j->tarif,'hasilPemeriksaan'=>null];
    })->filter()->values()->all();
    $struk=$request->user()->struks()->create([
      'pasien_id'=>$pasien->id,'pemeriksaans'=>$pem,'total_tarif'=>collect($pem)->sum('tarif'),
      'status'=>'draft','tanggal_pemeriksaan'=>$v['tanggal_pemeriksaan']??now(),
    ]);
    return response()->json(['success'=>true,'data'=>$struk->load('pasien:id,nama_pasien,kode_registrasi')],201);
  }

  // PUT /api/v1/kasir/struk/{id}
  public function strukUpdate(Request $request, $id)
  {
    $struk=$request->user()->struks()->where('id',$id)->firstOrFail();
    if($struk->status!=='draft')
      return response()->json(['success'=>false,'message'=>'Hanya struk berstatus draft yang dapat diubah.'],422);
    $v=$request->validate([
      'pemeriksaans'                    =>'required|array|min:1',
      'pemeriksaans.*.idPemeriksaan'    =>'required|integer',
      'pemeriksaans.*.hasilPemeriksaan' =>'nullable|string|max:500',
    ]);
    $existing=collect($struk->pemeriksaans)->keyBy('idPemeriksaan');
    $updated=collect($v['pemeriksaans'])->map(function($item) use($existing){
      $base=$existing->get($item['idPemeriksaan']); if(!$base)return null;
      $base=is_array($base)?$base:(array)$base;
      $base['hasilPemeriksaan']=$item['hasilPemeriksaan']??null; return $base;
    })->filter()->values()->all();
    $struk->update(['pemeriksaans'=>$updated]);
    return response()->json(['success'=>true,'data'=>$struk]);
  }

  // POST /api/v1/kasir/struk/{id}/submit
  public function strukSubmit(Request $request, $id)
  {
    $struk=$request->user()->struks()->where('id',$id)->firstOrFail();
    if($struk->status!=='draft')
      return response()->json(['success'=>false,'message'=>'Hanya struk berstatus draft yang dapat disubmit.'],422);
    $struk->update(['status'=>'menunggu_koreksi','submitted_at'=>now()]);
    return response()->json(['success'=>true,'message'=>'Struk berhasil disubmit. Menunggu persetujuan admin.','data'=>$struk]);
  }

  // GET /api/v1/kasir/hasil
  public function hasilIndex(Request $request)
  {
    return response()->json(['success'=>true,'data'=>$request->user()->struks()
      ->where('status','approve')->with('pasien:id,nama_pasien,kode_registrasi')
      ->select(['id','kode_struk','pasien_id','pemeriksaans','total_tarif','status','tanggal_pemeriksaan','approved_at'])
      ->orderByDesc('approved_at')->get()]);
  }

  // GET /api/v1/kasir/hasil/{id}
  public function hasilShow(Request $request, $id)
  {
    $struk=$request->user()->struks()->where('id',$id)->where('status','approve')
      ->with(['pasien:id,kode_registrasi,nama_pasien,kategori_pasien,jenis_kelamin,golongan_darah,no_telepon,alamat,tanggal_registrasi'])
      ->firstOrFail();
    $jenisMap=JenisPemeriksaan::whereIn('id',collect($struk->pemeriksaans)->pluck('idPemeriksaan')->filter()->unique())->get()->keyBy('id');
    $enriched=collect($struk->pemeriksaans)->map(function($p) use($jenisMap){
      $p=is_array($p)?$p:(array)$p; $j=$jenisMap->get($p['idPemeriksaan']??null);
      return array_merge($p,['tipePeriksa'=>$j?->tipe_periksa??($p['tipePeriksa']??null),
        'nilaiNormal'=>$j?->nilai_normal??($p['nilaiNormal']??null),'satuan'=>$j?->satuan??($p['satuan']??null)]);
    })->values();
    return response()->json(['success'=>true,'data'=>array_merge($struk->toArray(),
      ['pemeriksaans'=>$enriched,'siswa'=>$request->user()->only(['id','nis','nama','kelas','jurusan'])])]);
  }
}
