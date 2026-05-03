<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\UserProgress;
use App\Models\StudentScore; // 🔥 WAJIB ADA: Import model skor
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // --- LAPIS 1: GAME SIMULATOR ---
    public function showSimulator($level_id)
    {
        $quiz = Quiz::where('level_id', $level_id)->where('type', 'post_test')->firstOrFail();
        return view('quest-view', compact('quiz', 'level_id'));
    }

    public function submitSimulator(Request $request, $quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $user = Auth::user();

        UserProgress::firstOrCreate(
            ['user_id' => $user->id, 'level_id' => $quiz->level_id],
            ['status' => 'locked', 'highest_score' => 0]
        );

        // Catat: Tambahkan logika Poin EXP gamifikasi di sini nanti
        
        // Lanjut ke ujian teori Lapis 2
        return redirect()->route('quest.theory.show', $quiz->level_id);
    }


    // --- LAPIS 2: UJIAN TEORI ---
    public function showTheory($level_id)
    {
        $quiz = Quiz::where('level_id', $level_id)->where('type', 'post_test')->with('questions')->firstOrFail();
        return view('quest-theory-view', compact('quiz', 'level_id'));
    }

    public function submitTheory(Request $request, $quiz_id)
    {
        $quiz = Quiz::with('questions')->findOrFail($quiz_id);
        $userAnswers = $request->input('answers', []);
        
        $rawScore = 0;
        $maxScore = 0; // Menghitung total skor maksimal yang bisa didapat

        // 1. Hitung poin jawaban benar
        foreach ($quiz->questions as $question) {
            $correct = $question->correct_answer;
            $userAns = $userAnswers[$question->id] ?? null;
            $maxScore += $question->points; // Tambahkan poin soal ke max score

            if (str_contains(strtolower($question->type), 'multiple')) {
                if ($userAns == $correct) { $rawScore += $question->points; }
            } else {
                if (strtolower(trim((string)$userAns)) == strtolower(trim((string)$correct))) {
                    $rawScore += $question->points;
                }
            }
        }

        // 2. Konversi nilai ke Skala 100 (Biar seragam dan gampang dihitung rata-ratanya)
        $finalScore = $maxScore > 0 ? round(($rawScore / $maxScore) * 100) : 0;
        if($finalScore > 100) $finalScore = 100;

        $user = Auth::user();
        
        // 🔥 3. INI DIA YANG HILANG! LOGIKA PENCATAT NILAI AWAL & TERBAIK 🔥
        $quizType = 'theory'; // Tipe kuis harus sama dengan yang dipanggil di Dashboard Guru

        $scoreRecord = StudentScore::where('user_id', $user->id)
            ->where('level_id', $quiz->level_id)
            ->where('quiz_type', $quizType)
            ->first();

        if (!$scoreRecord) {
            // JIKA BARU PERTAMA KALI MENGERJAKAN POST-TEST
            StudentScore::create([
                'user_id' => $user->id,
                'level_id' => $quiz->level_id,
                'quiz_type' => $quizType,
                'initial_score' => $finalScore, // Rekam Nilai Awal!
                'best_score' => $finalScore,    // Rekam Nilai Terbaik!
            ]);
        } else {
            // JIKA MENGULANG (REMIDIAL)
            if ($finalScore > $scoreRecord->best_score) {
                $scoreRecord->update(['best_score' => $finalScore]); // Update nilai terbaik saja!
            }
        }

        // 4. Sinkronisasi (Kalkulasi ulang) Total EXP karakter murid
        $user->recalculateTotalPoints();

        // 5. Update Status Kelulusan
        $progress = UserProgress::firstOrCreate(['user_id' => $user->id, 'level_id' => $quiz->level_id]);
        
        $passed = $finalScore >= $quiz->minimum_score_to_pass;
        if ($passed) { $progress->update(['status' => 'completed']); }

        $progress->update(['highest_score' => max($progress->highest_score, $finalScore)]);

        // 6. Lempar nilai akhir ke layar Debriefing
        $score = $finalScore; 
        return view('quest-result', compact('score', 'passed', 'quiz'));
    }
}