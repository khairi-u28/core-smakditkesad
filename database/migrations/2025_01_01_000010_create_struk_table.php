<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000010_create_struk_table.php
// Maps from: pemeriksaan_struk in Supabase
// A student creates a "struk" = selecting tests + filling results for a patient
// pemeriksaans JSON stores: [{idPemeriksaan, subPeriksa, tarif, hasilPemeriksaan, ...}]
// status: draft (siswa belum submit) | submitted (menunggu koreksi) | selesai
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('struk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_struk')->unique();         // STRUK-250410-JH2DJY format
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->foreignId('pasien_id')->constrained('pasien');
            $table->json('pemeriksaans');                   // array of selected tests + results
            $table->decimal('total_tarif', 12, 2)->default(0);
            $table->enum('status', ['draft', 'submitted', 'dikoreksi', 'selesai'])->default('draft');
            $table->timestamp('tanggal_pemeriksaan')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('struk'); }
};