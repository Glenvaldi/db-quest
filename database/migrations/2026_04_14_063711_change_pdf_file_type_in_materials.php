<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // 👈 Wajib tambahkan baris ini

return new class extends Migration {
    public function up(): void {
        // 1. Kosongkan dulu isi kolom pdf_file yang lama agar MySQL tidak protes
        DB::table('materials')->update(['pdf_file' => null]);

        // 2. Baru ubah tipe datanya menjadi JSON
        Schema::table('materials', function (Blueprint $table) {
            $table->json('pdf_file')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('materials', function (Blueprint $table) {
            $table->string('pdf_file')->nullable()->change();
        });
    }
};