<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000011_create_hasil_lab_table.php
// Maps from: pemeriksaan_hasillab in Supabase
// Asesor reviews submitted struk → creates hasil_lab (final report)
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hasil_lab', function (Blueprint $table) {
            $table->id();
            $table->string('kode_hasil')->unique();         // HASIL-250410-xxxxx
            $table->foreignId('struk_id')->constrained('struk');
            $table->foreignId('siswa_id')->constrained('siswa');  // siswa yg diperiksa
            $table->foreignId('pasien_id')->constrained('pasien');
            $table->string('nama_asesor');                  // nama asesor/guru yang mengoreksi
            $table->json('pemeriksaans');                   // full results with nilai normal
            $table->text('catatan_asesor')->nullable();     // feedback dari asesor
            $table->unsignedTinyInteger('nilai')->nullable(); // nilai praktik 0-100
            $table->timestamp('tanggal_pemeriksaan')->nullable();
            $table->timestamp('tanggal_cetak')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('hasil_lab'); }
};