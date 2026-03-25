<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000004_create_pages_table.php
// For Landing Page — static pages editable via Filament
// Covers: profil, sejarah, ppdb, fasilitas, visi-misi
// Source: profilsekolah.html, sejarah.html, ppdb.html, etc.
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();   // profil|sejarah|ppdb|fasilitas|visi-misi
            $table->string('judul');
            $table->json('konten');             // flexible JSON blocks per page
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pages'); }
};