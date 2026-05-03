<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AiEvaluatorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

Route::get('/', function () {
    return view('landing-page');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // RUTE DASHBOARD SISWA
    Route::get('/dashboard', function() { return view('dashboard-menu'); })->name('dashboard');

    // 🔥 RUTE DASHBOARD GURU (REVISI: AVG KHUSUS POST-TEST) 🔥
    Route::get('/teacher-dashboard', function () {
        if (auth()->user()->role !== 'teacher') {
            abort(403, 'Akses Ditolak: Ruangan ini khusus untuk Instruktur.');
        }

        $students = User::where('role', 'student')
                        ->where('school_id', auth()->user()->school_id)
                        ->with('scores') 
                        ->get()
                        ->map(function ($student) {
                            // 🔥 Ambil hanya data Post-Test untuk perhitungan rata-rata di tabel utama
                            $postTestScores = $student->scores->where('quiz_type', 'theory');
                            
                            $student->avg_initial = $postTestScores->count() > 0 ? round($postTestScores->avg('initial_score'), 1) : 0;
                            $student->avg_best = $postTestScores->count() > 0 ? round($postTestScores->avg('best_score'), 1) : 0;
                            
                            return $student;
                        })
                        ->sortByDesc('total_points') 
                        ->values();

        return view('teacher.dashboard', compact('students'));
    })->name('teacher.dashboard');

    Route::get('/start-adventure', [LevelController::class, 'index'])->name('adventure.index');
    Route::get('/materials', [LevelController::class, 'materialsIndex'])->name('materials.index');
    Route::get('/quests', [LevelController::class, 'questsIndex'])->name('quests.index');
    Route::get('/player-profile', [LevelController::class, 'profile'])->name('player.profile');
    Route::get('/leaderboard', [LevelController::class, 'leaderboard'])->name('leaderboard');

    // Pre-Test
    Route::get('/level/{level_id}/pretest', [LevelController::class, 'pretest'])->name('pretest.show');
    Route::post('/level/{level_id}/pretest/finish', [LevelController::class, 'finishPretest'])->name('pretest.finish');

    // Material
    Route::get('/level/{level_id}/material', [LevelController::class, 'material'])->name('level.material');

    // Post-Test
    Route::get('/level/{level_id}/quest/simulator', [QuizController::class, 'showSimulator'])->name('quest.simulator.show');
    Route::post('/quiz/{quiz_id}/submit-simulator', [QuizController::class, 'submitSimulator'])->name('quest.simulator.submit');
    Route::get('/level/{level_id}/quest/theory', [QuizController::class, 'showTheory'])->name('quest.theory.show');
    Route::post('/quiz/{quiz_id}/submit-theory', [QuizController::class, 'submitTheory'])->name('quest.theory.submit');

    // AI Evaluator
    Route::get('/level/{level_id}/ai-evaluation', [AiEvaluatorController::class, 'showRoom'])->name('ai.evaluator.show');
    Route::post('/api/ai-chat', [AiEvaluatorController::class, 'chatHandler'])->name('ai.evaluator.chat');

    // 🔥 TAMBAHAN BARU: GLOBAL AI MENTOR 🔥
    Route::get('/ai-mentor', [App\Http\Controllers\AiMentorController::class, 'index'])->name('ai.mentor.index');
    Route::post('/ai-mentor/chat', [App\Http\Controllers\AiMentorController::class, 'chat'])->name('ai.mentor.chat');

    // RUTE LOGOUT
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

});

Route::view('profile-settings', 'profile')->middleware(['auth'])->name('profile.settings');

require __DIR__.'/auth.php';