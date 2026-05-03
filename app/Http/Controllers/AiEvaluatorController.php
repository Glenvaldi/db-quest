<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiEvaluatorController extends Controller
{
    public function showRoom(Request $request, $level_id)
    {
        $user = Auth::user();
        $quiz = Quiz::where('level_id', $level_id)->where('type', 'post_test')->firstOrFail();
        
        // 👇 PERBAIKAN: Ambil score dari URL parameter (?score=XX) 👇
        $score = $request->query('score');
        
        // Jika tidak ada parameter score di URL, baru ambil history tertinggi dari database
        if ($score === null) {
            $progress = UserProgress::where('user_id', $user->id)->where('level_id', $level_id)->first();
            $score = $progress ? $progress->highest_score : 0;
        }

        $passed = $score >= $quiz->minimum_score_to_pass;

        // Daftar nama materi DB-Quest
        $levelNames = [
            1 => 'Pengenalan Basis Data',
            2 => 'Pemodelan Basis Data',
            3 => 'Normalisasi Data',
            4 => 'Dasar SQL',
            5 => 'Fungsi Agregat SQL'
        ];
        $levelName = $levelNames[$level_id] ?? "Modul $level_id";

        return view('ai-evaluator', compact('quiz', 'score', 'passed', 'levelName'));
    }

    public function chatHandler(Request $request)
    {
        $userMessage = $request->input('message');
        $score = $request->input('score');
        $isPassed = $request->input('passed'); // Akan bernilai 1 atau 0
        $levelName = $request->input('levelName');
        $chatHistory = $request->input('history', []);

        // Ubah angka menjadi teks agar AI benar-benar paham
        $statusTeks = ($isPassed == 1) ? "LULUS" : "GAGAL";

        // 👇 PROMPT SUPER KETAT AGAR AI TIDAK HALUSINASI 👇
        $systemPrompt = "Kamu adalah 'DB-Quest Mentor AI', asisten virtual yang asyik, gaul ala anak IT, dan ramah. 
        Kamu sedang mengevaluasi siswa pada materi: '{$levelName}'.
        HASIL TES TERBARU: Skor {$score}/100. Status Kelulusan: {$statusTeks}.

        SILABUS GAME DB-QUEST:
        Level 1: Pengenalan Basis Data (Konsep Dasar, DBMS, Arsip vs DB)
        Level 2: Pemodelan Basis Data (ERD, Entitas, Atribut, Relasi)
        Level 3: Normalisasi Data (1NF, 2NF, 3NF, Anomali)
        Level 4: Dasar SQL (DDL, DML, CRUD)
        Level 5: Fungsi Agregat SQL (SUM, COUNT, AVG, MIN, MAX)

        ATURAN WAJIB SAAT MEMBALAS (DILARANG DILANGGAR):
        1. JIKA STATUS 'LULUS': Puji keberhasilannya dengan skor {$score}! Rangkum intisari materi '{$levelName}' dalam 2-3 kalimat. Tanyakan apakah ada yang masih dibingungkan dari materi ini sebelum mereka kembali ke Peta. JANGAN PERNAH menyebutkan atau mengarang nama materi berikutnya di luar silabus di atas!
        2. JIKA STATUS 'GAGAL': Beri semangat dan motivasi. Beritahu dengan jujur dan tegas tapi halus bahwa karena skornya masih {$score}, dia BELUM BISA LANJUT dan HARUS MENGULANG misi '{$levelName}'. Bahas sedikit konsep tersulit di materi '{$levelName}', lalu pancing dia diskusi untuk mencari letak kesalahannya. DILARANG menyuruhnya lanjut ke level berikutnya.
        3. GAYA BAHASA: Santai (pakai kata 'kuy', 'banget', 'dong'), wajib pakai emoji, dan selalu sapa siswa dengan panggilan 'Komandan'. Jangan membalas terlalu panjang (maksimal 2 paragraf).";

        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        
        foreach ($chatHistory as $chat) { 
            $messages[] = ['role' => $chat['role'], 'content' => $chat['content']]; 
        }
        
        if ($userMessage) { 
            $messages[] = ['role' => 'user', 'content' => $userMessage]; 
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            if ($response->successful()) {
                return response()->json(['reply' => $response->json()['choices'][0]['message']['content']]);
            }

            return response()->json(['reply' => 'Waduh Komandan, sirkuit AI korslet nih! Coba refresh ya.'], 500);
            
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Maaf Komandan, sinyal putus! Error: ' . $e->getMessage()], 500);
        }
    }
}