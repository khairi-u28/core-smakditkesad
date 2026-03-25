<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000008_create_jenis_pemeriksaan_table.php
// Maps from: pemeriksaan table in Supabase
// This is the MASTER CATALOG of all available lab tests
// Existing data: 12 tests (Hematologi, Kimia Klinik, Urinalisis, Imunoserologi)
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jenis_pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();               // was: idpemeriksaan (1,2,PMK001)
            $table->string('bidang_periksa');               // Hematologi|Kimia Klinik|Urinalisis|Imunoserologi
            $table->string('tipe_periksa');                 // Darah Lengkap|Fungsi Hati|dll
            $table->string('sub_periksa');                  // Hemoglobin|Leukosit|dll (nama test)
            $table->string('nilai_normal')->nullable();     // "12.0-14.0(P) 13.0-16.0(L)"
            $table->string('satuan')->nullable();           // g/dL|ribu/µL|%|mg/dL|-
            $table->unsignedInteger('tarif')->default(0);  // in Rupiah
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('jenis_pemeriksaan'); }
};