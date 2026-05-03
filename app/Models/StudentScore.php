<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model
{
    // Mengizinkan semua kolom diisi
    protected $guarded = [];

    // Relasi ke Pemain (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}