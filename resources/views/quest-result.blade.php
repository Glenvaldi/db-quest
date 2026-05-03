@extends('layouts.app')

@section('title', 'DB-QUEST | Mission Debrief')

@push('styles')
<style>
    /* 💥 GLITCH TEKS */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after { content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: transparent; }
    
    /* Glitch Lulus (Emerald) */
    .passed-glitch::before { left: 2px; text-shadow: -2px 0 #10b981; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .passed-glitch::after { left: -2px; text-shadow: -2px 0 #00fff9, 2px 2px #10b981; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    
    /* Glitch Gagal (Rose) */
    .failed-glitch::before { left: 2px; text-shadow: -2px 0 #f43f5e; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .failed-glitch::after { left: -2px; text-shadow: -2px 0 #ff00c1, 2px 2px #f43f5e; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    
    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); } 80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); } 40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); } 80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); } }

    /* 🎛️ EFEK HUD CARD */
    .hud-card { backdrop-filter: blur(12px); position: relative; overflow: hidden; }
    .hud-cut-bl { clip-path: polygon(0 0, 100% 0, 100% 100%, 40px 100%, 0 calc(100% - 40px)); }
    .hud-cut-br { clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%); }

    /* Scanlines */
    .scanlines-bg { position: absolute; inset: 0; pointer-events: none; z-index: 0; background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; opacity: 0.3; }

    /* Animasi Pop-up */
    @keyframes popInCyber { 0% { opacity: 0; transform: scale(0.9) translateY(40px); filter: blur(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); } }
    .animate-pop-in-cyber { animation: popInCyber 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    #tsparticles { position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: 1; pointer-events: none; }
</style>
@endpush

@section('content')
<div class="relative w-full min-h-[calc(100vh-5rem)] bg-slate-50 dark:bg-[#030305] transition-colors duration-500 overflow-hidden flex items-center justify-center py-12">
    
    <div id="tsparticles"></div>
    <div class="scanlines-bg"></div>
    <div class="absolute inset-0 bg-[linear-gradient(rgba(79,70,229,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(79,70,229,0.05)_1px,transparent_1px)] dark:bg-[linear-gradient(rgba(0,255,249,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,249,0.05)_1px,transparent_1px)] z-0 pointer-events-none" style="background-size: 40px 40px;"></div>
    
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] {{ $passed ? 'bg-emerald-500/10 dark:bg-emerald-500/15' : 'bg-rose-500/10 dark:bg-rose-500/15' }} blur-[150px] rounded-full pointer-events-none z-0 transition-colors duration-1000"></div>

    <div class="relative z-10 w-full max-w-lg mx-auto px-6">
        
        <div class="hud-card hud-cut-bl bg-white/90 dark:bg-[#0a0a0c]/90 border {{ $passed ? 'border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)]' : 'border-rose-300 dark:border-rose-500/50 shadow-[0_0_50px_rgba(244,63,94,0.2)]' }} p-8 md:p-12 text-center animate-pop-in-cyber">
            
            <div class="relative mx-auto w-24 h-24 mb-8 flex items-center justify-center">
                <div class="absolute inset-0 rounded-none rotate-45 {{ $passed ? 'bg-emerald-100 dark:bg-emerald-500/20 animate-[spin_4s_linear_infinite] opacity-75' : 'bg-rose-100 dark:bg-rose-500/20 animate-pulse' }}"></div>
                <div class="relative w-20 h-20 bg-slate-900 border-2 {{ $passed ? 'border-emerald-400 shadow-[0_0_30px_#10b981] text-emerald-400' : 'border-rose-400 shadow-[0_0_30px_#f43f5e] text-rose-400' }} flex items-center justify-center rotate-45 z-10">
                    <div class="-rotate-45">
                        @if($passed)
                            <svg class="w-10 h-10 drop-shadow-[0_0_10px_currentColor]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        @else
                            <svg class="w-10 h-10 drop-shadow-[0_0_10px_currentColor]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        @endif
                    </div>
                </div>
            </div>

            <div class="glitch-wrapper block mb-2">
                <h1 class="text-3xl md:text-4xl font-black uppercase tracking-tighter glitch {{ $passed ? 'passed-glitch text-slate-800 dark:text-emerald-500' : 'failed-glitch text-slate-800 dark:text-rose-500' }}" data-text="{{ $passed ? 'MISSION ACCOMPLISHED' : 'SYSTEM ERROR' }}">
                    {{ $passed ? 'MISSION ACCOMPLISHED' : 'SYSTEM ERROR' }}
                </h1>
            </div>
            
            <p class="text-slate-600 dark:text-slate-400 font-mono text-xs md:text-sm mb-8 px-4">
                > {{ $passed ? 'Sistem otorisasi terbuka. Analisis data menunjukkan pemahaman log basis data yang memadai.' : 'Otorisasi ditolak. Poin yang kamu kumpulkan mengalami korupsi data / di bawah standar.' }}
            </p>

            <div class="bg-slate-100 dark:bg-black/50 p-6 border {{ $passed ? 'border-emerald-200 dark:border-emerald-500/30 border-l-4 border-l-emerald-500' : 'border-rose-200 dark:border-rose-500/30 border-l-4 border-l-rose-500' }} mb-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.05)_50%,transparent_75%,transparent_100%)] bg-[length:10px_10px] pointer-events-none"></div>
                <p class="text-[10px] uppercase tracking-[0.2em] font-mono font-bold text-slate-500 dark:text-slate-400 mb-2">> AKURASI_LOG</p>
                <div class="flex items-baseline justify-center gap-2 relative z-10">
                    <span class="text-6xl md:text-7xl font-black {{ $passed ? 'text-emerald-600 dark:text-emerald-400 drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]' : 'text-rose-600 dark:text-rose-500 drop-shadow-[0_0_10px_rgba(244,63,94,0.8)]' }} leading-none">
                        {{ $score }}
                    </span>
                    <span class="text-lg font-black text-slate-400 dark:text-slate-500">PT</span>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-300 dark:border-white/10 flex justify-between items-center px-2 font-mono relative z-10">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">TARGET_MIN:</span>
                    <span class="text-xs font-black text-slate-800 dark:text-white">{{ $quiz->minimum_score_to_pass }} PT</span>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                
                <a href="{{ route('ai.evaluator.show', $quiz->level_id) }}?score={{ $score }}" class="group hud-cut-br w-full py-4 md:py-5 bg-indigo-600 dark:bg-indigo-600/20 hover:bg-indigo-500 dark:hover:bg-indigo-500/40 text-white dark:text-indigo-400 font-black text-sm tracking-widest uppercase transition-all border border-transparent dark:border-indigo-500/50 shadow-[0_0_20px_rgba(79,70,229,0.3)] dark:shadow-[0_0_30px_rgba(79,70,229,0.2)] flex justify-center items-center gap-3 cyber-link {{ !$passed ? 'animate-pulse' : '' }}" onmouseenter="typeof playSound !== 'undefined' ? playSound('hover') : ''" onclick="typeof playSound !== 'undefined' ? playSound('click') : ''">
                    <span class="relative z-10">> {{ $passed ? 'ANALISIS_AI' : 'EVALUASI_AI_GAGAL' }}</span>
                    <svg class="w-5 h-5 relative z-10 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </a>

                @if($passed)
                    <a href="{{ route('adventure.index') }}" class="w-full py-3.5 bg-slate-100 dark:bg-emerald-500/10 hover:bg-slate-200 dark:hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-black text-xs tracking-widest uppercase transition-all border border-slate-300 dark:border-emerald-500/30 flex justify-center items-center gap-2 cyber-link" onmouseenter="typeof playSound !== 'undefined' ? playSound('hover') : ''">
                        > CONTINUE_MAP
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </a>
                @else
                    <a href="{{ route('quest.simulator.show', $quiz->level_id) }}" class="w-full py-3.5 bg-slate-100 dark:bg-rose-500/10 hover:bg-slate-200 dark:hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 font-black text-xs tracking-widest uppercase transition-all border border-slate-300 dark:border-rose-500/30 flex justify-center items-center gap-2 cyber-link" onmouseenter="typeof playSound !== 'undefined' ? playSound('hover') : ''">
                        > RETRIGGER_MISSION
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </a>
                @endif
                
                <a href="{{ route('dashboard') }}" class="mt-2 w-full text-center text-slate-500 hover:text-indigo-500 dark:hover:text-[#00fff9] font-mono font-bold text-[10px] tracking-widest uppercase transition-colors cyber-link" onmouseenter="typeof playSound !== 'undefined' ? playSound('hover') : ''">
                    [ Return_To_Cockpit ]
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>
<script>
    const isPassed = {{ $passed ? 'true' : 'false' }};
    
    // Auto-play Audio Background
    window.addEventListener('DOMContentLoaded', () => {
        if(typeof playSound !== 'undefined') {
            setTimeout(() => {
                if(isPassed) playSound('win');
                else playSound('error');
            }, 300);
        }
    });

    tsParticles.load("tsparticles", {
        fpsLimit: 60,
        particles: {
            number: { value: isPassed ? 50 : 80 },
            color: { value: isPassed ? ["#10b981", "#34d399", "#00fff9"] : ["#f43f5e", "#ef4444", "#ff00c1"] },
            shape: { type: isPassed ? "square" : "triangle" },
            opacity: {
                value: { min: 0.1, max: 0.8 },
                animation: { enable: true, speed: 2, minimumValue: 0.1 }
            },
            size: {
                value: { min: 2, max: 6 },
                animation: { enable: !isPassed, speed: 10, minimumValue: 1 }
            },
            move: {
                enable: true,
                speed: isPassed ? 2 : 4, 
                direction: isPassed ? "bottom" : "top",
                random: !isPassed,
                straight: isPassed,
                outModes: { default: "out" }
            }
        },
        detectRetina: true
    });
</script>
@endpush