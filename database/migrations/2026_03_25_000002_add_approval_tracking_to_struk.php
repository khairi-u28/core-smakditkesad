<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('struk', function (Blueprint $table) {
            // Add approval tracking columns
            $table->timestamp('approved_at')->nullable()->after('catatan_koreksi');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('struk', function (Blueprint $table) {
            $table->dropForeignIdFor('users');
            $table->dropColumn('approved_at');
            $table->dropColumn('approved_by');
        });
    }
};
