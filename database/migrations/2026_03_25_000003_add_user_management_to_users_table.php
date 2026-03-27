<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->default('admin')->after('email');
            $table->boolean('is_corrector')->default(false)->after('user_type')->comment('Dapat melakukan koreksi Struk');
            $table->boolean('is_active')->default(true)->after('is_corrector');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'is_corrector', 'is_active']);
        });
    }
};
