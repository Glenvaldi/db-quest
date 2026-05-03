<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserProgress;
use App\Models\Level;

class CheckLevelAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil ID level dari URL (misal: website.com/level/2)
        $levelId = $request->route('level_id'); 
        $userId = auth()->id();

        $level = Level::find($levelId);
        if (!$level) {
            return redirect()->route('adventure.index')->with('error', 'Level tidak ditemukan.');
        }

        // Level urutan 1 (Materi Awal) selalu bisa diakses
        if ($level->order_number == 1) {
            return $next($request);
        }

        // Cek progress siswa untuk level yang ingin diakses
        $progress = UserProgress::where('user_id', $userId)
                                ->where('level_id', $levelId)
                                ->first();

        // Jika progress belum ada atau masih 'locked', tendang balik!
        if (!$progress || $progress->status === 'locked') {
            return redirect()->route('adventure.index')->with('error', 'Eits! Level ini masih terkunci. Selesaikan materi dan quest di level sebelumnya dulu ya!');
        }

        // Kalau statusnya 'unlocked' atau 'completed', silakan masuk
        return $next($request);
    }
}