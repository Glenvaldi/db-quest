<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara masal.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school_id',
        'total_points', // 🔥 BERUBAH: Menyesuaikan dengan database (total_points)
    ];

    /**
     * Atribut yang harus disembunyikan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 🔥 KEAMANAN FILAMENT ADMIN 🔥
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin' || $this->email === 'admin@dbquest.com'; 
    }

    /**
     * Relasi ke Sekolah.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * 🔥 RELASI: Menghubungkan User dengan Rekaman Nilai 🔥
     */
    public function scores()
    {
        return $this->hasMany(StudentScore::class);
    }

    /**
     * 🔥 KALKULATOR CERDAS: RECALCULATE TOTAL POINTS 🔥
     */
    public function recalculateTotalPoints()
    {
        // Menjumlahkan seluruh nilai terbaik murid dari berbagai level & tipe kuis
        $totalBestScores = $this->scores()->sum('best_score');
        
        // Update kolom total_points pada tabel users
        $this->update([
            'total_points' => $totalBestScores // 🔥 BERUBAH: Menggunakan total_points
        ]);

        return $this->total_points; // 🔥 BERUBAH
    }
}