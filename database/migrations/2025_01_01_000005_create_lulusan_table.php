<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000005_create_lulusan_table.php
// For Landing Page — profil lulusan + tracer study
// Source: profillulusan.html, tracerstudy.html
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lulusan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('angkatan')->nullable();         // "Angkatan 54 - 2024"
            $table->year('tahun_lulus')->nullable();
            $table->text('testimoni')->nullable();
            $table->string('foto')->nullable();
            $table->string('instansi_kerja')->nullable();   // RS Siloam, TNI AD, dll
            $table->string('posisi_kerja')->nullable();     // Analis Kesehatan, dll
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('lulusan'); }
};