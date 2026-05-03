<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    // Karena kita pakai JSON untuk puzzle, kita ubah otomatis jadi Array
    protected $casts = [
        'content' => 'array',
        'correct_answer' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}