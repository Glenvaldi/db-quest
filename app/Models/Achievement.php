<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    // Taruh di sini:
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements', 'achievement_id', 'user_id')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }
}