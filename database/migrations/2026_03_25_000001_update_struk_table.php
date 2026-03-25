<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('struk', function (Blueprint $table) {
            // Change status enum values
            $table->enum('status', ['draft', 'menunggu_koreksi', 'approve', 'tolak'])->default('draft')->change();
            // Add notes field for correction notes
            $table->text('catatan_koreksi')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('struk', function (Blueprint $table) {
            $table->enum('status', ['draft', 'submitted', 'dikoreksi', 'selesai'])->default('draft')->change();
            $table->dropColumn('catatan_koreksi');
        });
    }
};
