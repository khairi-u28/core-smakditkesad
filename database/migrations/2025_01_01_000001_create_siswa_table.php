<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000001_create_siswa_table.php
// Maps from: petugas table in Supabase (idsiswa, namasiswa, password)
// Now unified auth for BOTH Kasir Lab and E-Library
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->unique();          // was: idsiswa (S001, S002)
            $table->string('nama');                        // was: namasiswa
            $table->string('kelas', 20)->nullable();
            $table->string('jurusan', 50)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('password');                    // bcrypt, was: plain text!
            $table->string('keterangan')->nullable();      // was: keterangan in petugas
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('siswa'); }
};