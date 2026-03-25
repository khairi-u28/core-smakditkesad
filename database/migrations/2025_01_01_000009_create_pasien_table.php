<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000009_create_pasien_table.php
// Maps from: pasien table in Supabase
// Patients registered by siswa during lab practice
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->string('kode_registrasi')->unique();    // was: koderegistrasi (REG250403-56789)
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('nama_pasien');
            $table->enum('kategori_pasien', ['Umum', 'BPJS', 'Asuransi Lain'])->default('Umum');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', '-'])->default('-');
            $table->enum('status_pernikahan', ['Belum Menikah', 'Menikah', 'Cerai'])->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->text('alamat')->nullable();
            $table->string('dokter_pengirim')->nullable();
            $table->timestamp('tanggal_registrasi')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pasien'); }
};