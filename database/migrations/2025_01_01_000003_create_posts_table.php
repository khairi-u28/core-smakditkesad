<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000003_create_posts_table.php
// For Landing Page — berita, pengumuman, kegiatan
// Source: hardcoded in kegiatan.html → now DB-driven
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->enum('kategori', ['berita', 'pengumuman', 'kegiatan']);
            $table->longText('konten')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('penulis')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('posts'); }
};