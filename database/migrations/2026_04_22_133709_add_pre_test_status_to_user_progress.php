<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->boolean('pre_test_done')->default(false); // Default: Belum ngerjain
        });
    }
    public function down(): void {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropColumn('pre_test_done');
        });
    }
};