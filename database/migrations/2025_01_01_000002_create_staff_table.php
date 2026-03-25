<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000002_create_staff_table.php
// For Landing Page — profil tenaga pendidik
// Source: hardcoded in staf.html, staf2.html → now DB-driven
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('jabatan');
            $table->string('bidang')->nullable();           // kurikulum | kesiswaan | sarpras
            $table->string('pendidikan')->nullable();       // S1, S2, S3, D4, D3
            $table->string('spesialisasi')->nullable();     // Kimia Klinis, Hematologi, dll
            $table->enum('jenis', ['guru', 'tendik'])->default('guru');
            $table->string('foto')->nullable();             // stored in storage/app/staff/
            $table->integer('urutan')->default(0);          // for ordering display
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('staff'); }
};