<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom 'role' sudah ada. Kalau BELUM ADA, baru dibikin.
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('student')->after('email');
            }
            
            // Cek apakah kolom 'school_id' sudah ada. Kalau BELUM ADA, baru dibikin.
            if (!Schema::hasColumn('users', 'school_id')) {
                $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback dengan aman
            if (Schema::hasColumn('users', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }
            
            // Kita abaikan drop 'role' karena ternyata itu bawaan lama database kamu
        });
    }
};