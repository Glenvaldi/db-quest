<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void {
    Schema::table('quizzes', function (Blueprint $table) {
        // Tipe defaultnya adalah 'post_test' (Quest Utama)
        $table->string('type')->default('post_test')->after('level_id'); 
    });
}
public function down(): void {
    Schema::table('quizzes', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}
};
