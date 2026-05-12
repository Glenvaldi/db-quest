<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\UserProgress;
use App\Models\Quiz;
use App\Models\StudentScore; 
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    private function getLeveLProgress() {
        $levels = Level::orderBy('order_number', 'asc')->get();
        $user = Auth::user();

        return $levels->map(function ($level) use ($user) {
            $progress = UserProgress::firstOrCreate(
                ['user_id' => $user->id, 'level_id' => $level->id],
                ['status' => $level->order_number == 1 ? 'unlocked' : 'locked', 'highest_score' => 0]
            );

            if ($level->order_number > 1 && $progress->status === 'locked') {
                $prev = Level::where('order_number', $level->order_number - 1)->first();
                if ($prev) {
                    $prevProg = UserProgress::where('user_id', $user->id)->where('level_id', $prev->id)->first();
                    if ($prevProg && $prevProg->status === 'completed') {
                        $progress->update(['status' => 'unlocked']);
                    }
                }
            }

            return [
                'id' => $level->id,
                'name' => $level->name,
                'description' => $level->description,
                'order_number' => $level->order_number,
                'status' => $progress->status,
                'highest_score' => $progress->highest_score
            ];
        });
    }

    public function index() {
        $levelData = $this->getLeveLProgress();
        return view('start-adventure', compact('levelData'));
    }

    public function materialsIndex() {
        $levelData = $this->getLeveLProgress();
        return view('materials-list', compact('levelData'));
    }

    public function questsIndex() {
        $levelData = $this->getLeveLProgress();
        return view('quests-list', compact('levelData'));
    }

    public function profile() {
        $user = Auth::user();
        $progress = UserProgress::where('user_id', $user->id)->get();
        
        $totalPoints = $user->total_points; // 🔥 BERUBAH: Ambil dari total_points
        $levelsCompleted = $progress->where('status', 'completed')->count();

        return view('profile-view', compact('user', 'totalPoints', 'levelsCompleted'));
    }

    public function material($level_id) {
        $user = Auth::user();
        $progress = UserProgress::firstOrCreate(
            ['user_id' => $user->id, 'level_id' => $level_id],
            ['status' => 'locked', 'highest_score' => 0]
        );

        if (!$progress->pre_test_done) {
            return redirect()->route('pretest.show', $level_id);
        }

        $level = Level::with('materials')->findOrFail($level_id);
        $materi = $level->materials->first();
        return view('material-view', compact('level', 'materi'));
    }

    public function pretest($level_id) {
        $level = Level::findOrFail($level_id);
        
        $pretest = Quiz::where('level_id', $level_id)
                       ->where('type', 'pre_test')
                       ->with('questions')
                       ->first();

        return view('pretest-view', compact('level_id', 'level', 'pretest'));
    }

    public function finishPretest(Request $request, $level_id)
    {
        $user = Auth::user();
        $score = $request->input('score', 0); 
        $quizType = 'pretest';

        $scoreRecord = StudentScore::where('user_id', $user->id)
            ->where('level_id', $level_id)
            ->where('quiz_type', $quizType)
            ->first();

        if (!$scoreRecord) {
            StudentScore::create([
                'user_id' => $user->id,
                'level_id' => $level_id,
                'quiz_type' => $quizType,
                'initial_score' => $score,
                'best_score' => $score,
            ]);
        } else {
            if ($score > $scoreRecord->best_score) {
                $scoreRecord->update(['best_score' => $score]);
            }
        }

        $progress = UserProgress::where('user_id', $user->id)->where('level_id', $level_id)->first();
        if ($progress) {
            $progress->update(['pre_test_done' => true]);
        }

        $user->recalculateTotalPoints();

        return redirect()->route('level.material', $level_id)->with('status', 'Pre-Test Terenkripsi. Meluncur ke Materi.');
    }

    public function leaderboard() {
        $leaderboard = \App\Models\User::where('role', 'student')
                        ->orderBy('total_points', 'desc') // 🔥 BERUBAH: Sortir berdasarkan total_points
                        ->get();

        return view('leaderboard-view', compact('leaderboard'));
    }
}
