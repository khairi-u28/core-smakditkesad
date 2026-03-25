<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000007_create_buku_table.php
// For E-Library — PDF files stored in storage/app/buku/
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_buku')->nullOnDelete();
            $table->string('judul');
            $table->string('pengarang')->nullable();
            $table->string('penerbit')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->string('isbn')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('file_pdf');                     // path: buku/pdfs/filename.pdf
            $table->string('cover')->nullable();            // path: buku/covers/filename.jpg
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('buku'); }
};