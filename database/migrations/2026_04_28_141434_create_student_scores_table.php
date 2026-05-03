<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('level_id');
            // Menandakan ini ujian apa: pretest, theory (kognitif), atau simulator (praktek)
            $table->enum('quiz_type', ['pretest', 'theory', 'simulator']); 
            $table->integer('initial_score')->default(0); // Nilai percobaan pertama
            $table->integer('best_score')->default(0);    // Nilai tertinggi yang pernah diraih
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_scores');
    }
};