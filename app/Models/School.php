<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relasi: Satu sekolah punya banyak User (Siswa/Guru)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}