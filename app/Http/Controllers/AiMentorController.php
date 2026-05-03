<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AiMentorController extends Controller
{
    public function index()
    {
        return view('ai-mentor');
    }

    public function chat(Request $request)
    {
        $userMessage = $request->input('message');
        $chatHistory = $request->input('history', []);
        $playerName = Auth::user()->name ?? 'Komandan';

        // 🔥 PROMPT TERBARU: SINKRON NAMA DENGAN MENU 🔥
        $systemPrompt = "Kamu adalah 'Neural Assistant', asisten AI pintar di dalam sistem DB-Quest. 
        Tugasmu adalah membantu user bernama {$playerName} untuk belajar Basis Data, mengatasi error kode (PHP/Laravel/SQL), atau sekadar ngobrol seputar IT.
        ATURAN WAJIB:
        1. Jangan menanyakan skor atau modul ujian, karena ini adalah mode chat bebas.
        2. Gaya bahasa harus asik, gaul, santai ala anak IT Indonesia (pakai kata 'kuy', 'banget', 'dong').
        3. Panggil user dengan sebutan '{$playerName}' atau 'Komandan'.
        4. Berikan jawaban yang singkat, padat, dan langsung ke solusi (maksimal 2-3 paragraf pendek).";

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
                'temperature' => 0.8,
                'max_tokens' => 600
            ]);

            if ($response->successful()) {
                return response()->json(['reply' => $response->json()['choices'][0]['message']['content']]);
            }

            return response()->json(['reply' => 'Waduh Komandan, server Groq lagi ngambek nih! Coba refresh ya.'], 500);
            
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Sinyal putus! Error: ' . $e->getMessage()], 500);
        }
    }
}