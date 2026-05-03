<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    // 👇 INI DIA KUNCI PENYELAMATNYA 👇
    protected $casts = [
        'pdf_file' => 'array',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}