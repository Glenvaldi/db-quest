@extends('layouts.app')

@section('title', 'DB-QUEST | Quest Arena')

@push('styles')
<style>
    body { font-family: 'Poppins', sans-serif; }

    /* 💥 EFEK GLITCH CYBERPUNK */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after { content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: transparent; }
    .glitch::before { left: 2px; text-shadow: -2px 0 #ff00c1; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .glitch::after { left: -2px; text-shadow: -2px 0 #00fff9, 2px 2px #ff00c1; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); } 80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); } 40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); } 80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); } }

    /* 🎛️ HUD ASYMMETRIC CARD */
    .hud-card { transition: all 0.4s ease; backdrop-filter: blur(12px); position: relative; }
    .hud-cut { border-left: 4px solid #4f46e5; border-bottom: 1px solid rgba(79,70,229,0.3); clip-path: polygon(0 0, 100% 0, 100% 100%, 30px 100%, 0 calc(100% - 30px)); }

    /* 🔌 ANIMASI KABEL LASER (LEVEL 2) */
    .line-cable { stroke: #00fff9; stroke-width: 4; stroke-linecap: round; stroke-dasharray: 12 12; animation: flowData 0.8s linear infinite; filter: drop-shadow(0 0 8px rgba(0, 255, 249, 0.8)); pointer-events: none; }
    @keyframes flowData { to { stroke-dashoffset: -24; } }
    
    /* 🎛️ STYLING NODE INTERAKTIF (LEVEL 2) */
    .game-node { cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); backdrop-filter: blur(10px); z-index: 20; }
    .game-node:hover { transform: scale(1.05) translateY(-5px); box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.4); }
    .game-node:active { transform: scale(0.95); }
    .active-node { border-color: #00fff9 !important; background: linear-gradient(135deg, rgba(0, 255, 249, 0.2), rgba(99, 102, 241, 0.2)) !important; box-shadow: 0 0 30px rgba(0, 255, 249, 0.6), inset 0 0 15px rgba(0, 255, 249, 0.4); }
    .solved-node { border-color: #10b981 !important; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1)) !important; box-shadow: 0 0 20px rgba(16, 185, 129, 0.4); cursor: default; pointer-events: none; }
    
    /* 💥 ANIMASI ERROR & EFEK GAME */
    @keyframes shakeError { 0%, 100% {transform: translateX(0);} 25% {transform: translateX(-8px) rotate(-2deg);} 75% {transform: translateX(8px) rotate(2deg);} }
    .shake-error { animation: shakeError 0.4s ease-in-out; border-color: #f43f5e !important; background: rgba(244, 63, 94, 0.2) !important; box-shadow: 0 0 20px rgba(244, 63, 94, 0.6); }

    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slideUpFade { animation: slideUpFade 0.5s ease-out forwards; }
    .pop-in { animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
    @keyframes popIn { 0% { transform: scale(0.5); opacity: 0; } 70% { transform: scale(1.1); opacity: 1; } 100% { transform: scale(1); opacity: 1; } }

    /* 🎯 KHUSUS GAME LEVEL 3 (SHOOTER) */
    @keyframes scanline { 0% { top: -10%; } 100% { top: 110%; } }
    .animate-scanline { animation: scanline 3s linear infinite; }
    .target-virus { cursor: crosshair; animation: floatVirus 3s ease-in-out infinite alternate; transition: all 0.2s; pointer-events: auto; }
    .target-virus:hover { filter: brightness(1.5) drop-shadow(0 0 15px rgba(244,63,94,0.8)); transform: scale(1.1); }
    @keyframes floatVirus { 0% { transform: translateY(0px) scale(1); } 100% { transform: translateY(-15px) scale(1.05); } }
    .explode { animation: blast 0.3s ease-out forwards; pointer-events: none; }
    @keyframes blast { 0% { transform: scale(1); opacity: 1; filter: brightness(1); } 50% { transform: scale(1.8); opacity: 0.8; filter: brightness(2); background: #fff; } 100% { transform: scale(0); opacity: 0; } }
    .shake-screen { animation: shakeError 0.2s ease-in-out; }

    /* 💻 KHUSUS GAME LEVEL 4 (TERMINAL) */
    .terminal-screen { font-family: 'Courier New', Courier, monospace; }
    .term-btn { transition: all 0.2s; }
    .term-btn:active { transform: scale(0.9); }
    .blink-cursor { animation: blink 1s step-end infinite; }
    @keyframes blink { 50% { opacity: 0; } }
    .glitch-error { text-shadow: 2px 0 #f43f5e, -2px 0 #3b82f6; animation: shakeError 0.3s; color: #f43f5e !important; }

    /* ⚙️ KHUSUS GAME LEVEL 5 (AGGREGATE CORE) */
    .core-btn { transition: all 0.3s; }
    .core-btn:hover { transform: translateY(-5px); filter: brightness(1.2); }
    .core-btn:active { transform: scale(0.95); }
    .monitor-pulse { animation: pulseMonitor 2s infinite alternate; }
    @keyframes pulseMonitor { 0% { box-shadow: 0 0 20px rgba(56, 189, 248, 0.2); } 100% { box-shadow: 0 0 40px rgba(56, 189, 248, 0.6); } }
</style>
@endpush

@section('content')
<div class="relative w-full min-h-[calc(100vh-5rem)] bg-slate-50 dark:bg-[#050505] transition-colors duration-500 py-8" id="main-screen">
    
    <div class="absolute inset-0 opacity-10 bg-[linear-gradient(45deg,transparent_25%,rgba(99,102,241,0.2)_50%,transparent_75%,transparent_100%)] dark:bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.1)_50%,transparent_75%,transparent_100%)] bg-[length:30px_30px] z-[-1] pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-96 bg-indigo-500/5 dark:bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none z-0"></div>

    <div class="max-w-5xl mx-auto px-6 mb-12 relative z-10 flex flex-col md:flex-row items-center justify-between gap-4">
        <a href="/level/{{ $level_id }}/material" class="group cyber-link flex items-center gap-2 px-6 py-2.5 rounded-none bg-slate-200/50 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-700 dark:text-zinc-300 font-mono font-bold text-xs tracking-widest uppercase hover:bg-rose-500 hover:border-rose-500 hover:text-white transition-all shadow-sm" onmouseenter="playHover()" onclick="playClick()">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            ABORT_MISSION
        </a>
        <div class="px-6 py-2.5 bg-white/80 dark:bg-[#0a0a0c]/80 border border-slate-300 dark:border-indigo-500/30 rounded-none flex items-center gap-3 shadow-[0_0_20px_rgba(99,102,241,0.15)] backdrop-blur-md">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse shadow-[0_0_10px_#f43f5e]"></span>
            <span class="text-slate-800 dark:text-white text-xs font-black tracking-widest uppercase font-mono">SYS_REC // Lvl_0{{ $level_id }}</span>
        </div>
    </div>

    @if($level_id == 1)
    <div class="max-w-4xl mx-auto px-6 relative z-10">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-6 py-1.5 border-l-2 border-r-2 border-orange-500 bg-orange-500/10 text-orange-600 dark:text-orange-400 text-xs font-mono font-black tracking-[0.3em] uppercase mb-4 animate-pulse">
                PHASE_01 // DATA CLASSIFIER
            </div>
            <div class="glitch-wrapper block mb-4">
                <h1 class="text-4xl md:text-5xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch" data-text="ORGANIZE THE CHAOS" id="title-game">Organize the Chaos</h1>
            </div>
            <p class="text-slate-600 dark:text-zinc-400 font-mono text-sm" id="desc-game">> Klasifikasikan objek: Mana yang termasuk <b>Basis Data Digital</b> dan <b>Arsip Manual</b>?</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-10" id="bin-container">
            <div id="bin-manual" class="hud-card hud-cut h-40 border-2 border-dashed border-rose-500/30 bg-rose-500/5 hover:bg-rose-500/20 hover:border-rose-500 flex flex-col items-center justify-center cursor-pointer transition-all shadow-lg group">
                <svg class="w-12 h-12 text-rose-500 mb-2 pointer-events-none group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                <span class="text-rose-600 dark:text-rose-400 font-black uppercase tracking-widest text-sm pointer-events-none">Arsip Manual</span>
            </div>
            <div id="bin-database" class="hud-card hud-cut h-40 border-2 border-dashed border-indigo-500/30 bg-indigo-500/5 hover:bg-indigo-500/20 hover:border-indigo-500 flex flex-col items-center justify-center cursor-pointer transition-all shadow-lg group" style="border-left-color: #10b981;">
                <svg class="w-12 h-12 text-indigo-500 mb-2 pointer-events-none group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                <span class="text-indigo-600 dark:text-indigo-400 font-black uppercase tracking-widest text-sm pointer-events-none">Basis Data Digital</span>
            </div>
        </div>

        <div id="card-arena" class="relative h-48 flex items-center justify-center pointer-events-none">
            <div id="current-card" class="bg-white dark:bg-[#0f172a] px-8 py-8 shadow-[0_15px_30px_rgba(99,102,241,0.3)] transform transition-all duration-300 border-l-4 border-r-4 border-indigo-500 w-full max-w-md text-center select-none scale-0">
                <span id="card-text" class="text-slate-900 dark:text-white font-black text-2xl uppercase tracking-tighter drop-shadow-md">MEMUAT_DATA...</span>
            </div>
        </div>

        <form id="auto-submit-form" action="{{ route('quest.simulator.submit', $quiz->id) }}" method="POST" class="mt-8 hidden animate-slideUpFade">
            @csrf
            <button type="submit" class="w-full py-5 bg-emerald-500 hover:bg-emerald-400 text-white font-black text-xl tracking-widest uppercase transition-all shadow-[0_0_40px_rgba(16,185,129,0.5)] flex justify-center items-center gap-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                > DECRYPT_COMPLETED
            </button>
        </form>
    </div>

    @elseif($level_id == 2)
    <div class="max-w-5xl mx-auto px-6 relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-6 py-1.5 border-l-2 border-r-2 border-indigo-500 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-mono font-black tracking-[0.3em] uppercase mb-4 animate-pulse">
                PHASE_02 // SYSTEM ARCHITECT
            </div>
            <div class="glitch-wrapper block mb-4">
                <h1 class="text-4xl md:text-5xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch" data-text="DATA RELATIONS">Data Relations</h1>
            </div>
            <p class="text-slate-600 dark:text-zinc-400 font-mono text-sm">> Ketuk Entitas (Kotak Biru), lalu hubungkan ke Atribut (Pill Hijau) yang sejajar dengan logikanya.</p>
            
            <div class="mt-8 inline-flex items-center gap-3 px-6 py-2.5 bg-white dark:bg-[#0a0a0c] border border-slate-300 dark:border-indigo-500/30 shadow-[0_0_20px_rgba(99,102,241,0.2)]">
                <span class="w-2 h-2 bg-[#00fff9] animate-ping"></span>
                <span id="game-status" class="text-indigo-700 dark:text-[#00fff9] font-mono font-bold tracking-widest uppercase text-sm">TARGET_LINK: 0 / 4</span>
            </div>
        </div>

        <div id="game-container" class="relative w-full h-[500px] md:h-[600px] bg-slate-100 dark:bg-[#0a0a0c] border border-slate-300 dark:border-indigo-500/20 overflow-hidden shadow-[0_20px_50px_rgba(99,102,241,0.1)] dark:shadow-[0_0_50px_rgba(99,102,241,0.2)] bg-[radial-gradient(#94a3b8_1px,transparent_1px)] dark:bg-[radial-gradient(#ffffff0a_1px,transparent_1px)]" style="background-size: 30px 30px;">
            <svg id="svg-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-10"></svg>
            
            <div class="absolute left-6 md:left-16 top-0 bottom-0 flex flex-col justify-evenly z-20 pointer-events-none">
                <div id="E1" onclick="handleNodeClick('E1', 'entitas')" class="game-node w-32 md:w-44 h-24 bg-white dark:bg-slate-900/90 border-2 border-indigo-400 dark:border-indigo-500 flex flex-col items-center justify-center shadow-lg pointer-events-auto"><span class="text-[10px] text-indigo-500 dark:text-indigo-400 font-mono font-black tracking-widest mb-1 pointer-events-none">ENTITAS_A</span><span class="text-slate-800 dark:text-white font-black text-2xl uppercase tracking-tighter pointer-events-none">SISWA</span></div>
                
                <div id="E2" onclick="handleNodeClick('E2', 'entitas')" class="game-node w-32 md:w-44 h-24 bg-white dark:bg-slate-900/90 border-2 border-indigo-400 dark:border-indigo-500 flex flex-col items-center justify-center shadow-lg pointer-events-auto"><span class="text-[10px] text-indigo-500 dark:text-indigo-400 font-mono font-black tracking-widest mb-1 pointer-events-none">ENTITAS_B</span><span class="text-slate-800 dark:text-white font-black text-2xl uppercase tracking-tighter pointer-events-none">BUKU</span></div>
            </div>
            
            <div class="absolute right-6 md:right-16 top-0 bottom-0 flex flex-col justify-evenly z-20 pointer-events-none">
                <div id="A1" onclick="handleNodeClick('A1', 'atribut')" class="game-node px-6 py-4 min-w-[120px] md:min-w-[180px] bg-white dark:bg-slate-900/90 border-2 border-emerald-400 dark:border-emerald-500 flex items-center justify-center shadow-lg pointer-events-auto"><span class="text-emerald-700 dark:text-emerald-400 font-black text-sm md:text-base uppercase tracking-widest pointer-events-none">NISN</span></div>
                <div id="A2" onclick="handleNodeClick('A2', 'atribut')" class="game-node px-6 py-4 min-w-[120px] md:min-w-[180px] bg-white dark:bg-slate-900/90 border-2 border-emerald-400 dark:border-emerald-500 flex items-center justify-center shadow-lg pointer-events-auto"><span class="text-emerald-700 dark:text-emerald-400 font-black text-sm md:text-base uppercase tracking-widest pointer-events-none">THN_TERBIT</span></div>
                <div id="A3" onclick="handleNodeClick('A3', 'atribut')" class="game-node px-6 py-4 min-w-[120px] md:min-w-[180px] bg-white dark:bg-slate-900/90 border-2 border-emerald-400 dark:border-emerald-500 flex items-center justify-center shadow-lg pointer-events-auto"><span class="text-emerald-700 dark:text-emerald-400 font-black text-sm md:text-base uppercase tracking-widest pointer-events-none">NAMA_SISWA</span></div>
                <div id="A4" onclick="handleNodeClick('A4', 'atribut')" class="game-node px-6 py-4 min-w-[120px] md:min-w-[180px] bg-white dark:bg-slate-900/90 border-2 border-emerald-400 dark:border-emerald-500 flex items-center justify-center shadow-lg pointer-events-auto"><span class="text-emerald-700 dark:text-emerald-400 font-black text-sm md:text-base uppercase tracking-widest pointer-events-none">JUDUL_BUKU</span></div>
            </div>
        </div>

        <form id="auto-submit-form" action="{{ route('quest.simulator.submit', $quiz->id) }}" method="POST" class="mt-8 hidden animate-slideUpFade">
            @csrf
            <button type="submit" class="w-full py-5 bg-[#00fff9] hover:bg-teal-400 text-slate-900 font-black text-xl tracking-widest uppercase shadow-[0_0_40px_rgba(0,255,249,0.5)] transition-all flex justify-center items-center gap-3 animate-pulse">
                > CONNECTION_ESTABLISHED
            </button>
        </form>
    </div>

    @elseif($level_id == 3)
    <div class="max-w-5xl mx-auto px-6 relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-6 py-1.5 border-l-2 border-r-2 border-rose-500 bg-rose-500/10 text-rose-600 dark:text-rose-400 text-xs font-mono font-black tracking-[0.3em] uppercase mb-4 animate-pulse">
                PHASE_03 // ANOMALY PURGE
            </div>
            <div class="glitch-wrapper block mb-4">
                <h1 class="text-4xl md:text-5xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch" data-text="THREAT ERADICATION">Threat Eradication</h1>
            </div>
            <p class="text-slate-600 dark:text-zinc-400 font-mono text-sm">> Radar mendeteksi anomali! Tembak (Klik) semua virus untuk menormalisasi basis data ke bentuk 3NF!</p>
            
            <div class="mt-8 inline-flex items-center gap-3 px-6 py-2.5 bg-rose-50 dark:bg-[#0a0a0c] border border-rose-200 dark:border-rose-500/30 transition-all duration-500 shadow-[0_0_20px_rgba(244,63,94,0.2)]" id="l3-status-box">
                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <span id="game-status-3" class="text-rose-700 dark:text-rose-400 font-mono font-bold tracking-widest uppercase text-sm">TARGETS_LEFT: 5</span>
            </div>
        </div>

        <div id="shooter-arena" class="relative w-full h-[500px] md:h-[600px] bg-slate-900 dark:bg-[#050505] border border-rose-500/50 overflow-hidden shadow-[0_0_50px_rgba(244,63,94,0.15)] cursor-crosshair transition-colors duration-1000">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(244,63,94,0.1)_1px,transparent_1px),linear-gradient(90deg,rgba(244,63,94,0.1)_1px,transparent_1px)] pointer-events-none z-0" style="background-size: 40px 40px;"></div>
            <div class="absolute inset-0 w-full h-8 bg-gradient-to-b from-transparent via-rose-500/30 to-transparent shadow-[0_0_20px_rgba(244,63,94,0.6)] animate-scanline pointer-events-none z-10"></div>
            
            <div id="targets-container" class="absolute inset-0 z-20 pointer-events-none"></div>
        </div>

        <form id="auto-submit-form" action="{{ route('quest.simulator.submit', $quiz->id) }}" method="POST" class="mt-8 hidden animate-slideUpFade">
            @csrf
            <button type="submit" class="w-full py-5 bg-emerald-500 hover:bg-emerald-400 text-white font-black text-xl tracking-widest uppercase shadow-[0_0_40px_rgba(16,185,129,0.5)] transition-all flex justify-center items-center gap-3 animate-pulse">
                > NORMALIZATION_COMPLETE
            </button>
        </form>
    </div>

    @elseif($level_id == 4)
    <div class="max-w-4xl mx-auto px-6 relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-6 py-1.5 border-l-2 border-r-2 border-emerald-500 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-mono font-black tracking-[0.3em] uppercase mb-4 animate-pulse">
                PHASE_04 // TERMINAL OVERRIDE
            </div>
            <div class="glitch-wrapper block mb-4">
                <h1 class="text-4xl md:text-5xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch" data-text="QUERY CODE BREAKER">Query Code Breaker</h1>
            </div>
            <p class="text-slate-600 dark:text-zinc-400 font-mono text-sm">> Bantu Admin menghapus semua data mahasiswa dari jurusan 1. Susun instruksi SQL yang tepat!</p>
        </div>

        <div class="w-full bg-[#050505] border border-emerald-500/50 overflow-hidden shadow-[0_0_50px_rgba(16,185,129,0.2)] mb-10 terminal-screen relative">
            <div class="bg-zinc-900/80 px-4 py-3 border-b border-emerald-500/30 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-rose-500"></div><div class="w-3 h-3 rounded-full bg-amber-500"></div><div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                </div>
                <span class="text-emerald-500/70 text-xs font-bold tracking-widest">root@db-quest:~#</span>
            </div>
            <div class="p-6 md:p-10 min-h-[180px]">
                <p class="text-slate-500 text-sm mb-6">> Misi: Hapus semua mahasiswa yang memiliki kode_jurusan = 1</p>
                <div class="text-[#00fff9] text-xl md:text-3xl font-bold flex flex-wrap gap-2 items-center" id="terminal-output">
                    <span class="text-emerald-500 mr-2">SYS></span><span class="w-4 h-8 bg-[#00fff9] blink-cursor inline-block" id="term-cursor"></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10" id="syntax-blocks">
            <button onclick="typeCode('DROP')" class="term-btn py-4 bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 hover:border-[#00fff9] text-slate-300 hover:text-[#00fff9] font-mono font-bold tracking-widest uppercase shadow-lg">DROP</button>
            <button onclick="typeCode('mahasiswa')" class="term-btn py-4 bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 hover:border-[#00fff9] text-slate-300 hover:text-[#00fff9] font-mono font-bold tracking-widest shadow-lg">mahasiswa</button>
            <button onclick="typeCode('DELETE')" class="term-btn py-4 bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 hover:border-[#00fff9] text-slate-300 hover:text-[#00fff9] font-mono font-bold tracking-widest uppercase shadow-lg">DELETE</button>
            <button onclick="typeCode('kode_jurusan = 1;')" class="term-btn py-4 bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 hover:border-[#00fff9] text-slate-300 hover:text-[#00fff9] font-mono font-bold tracking-widest shadow-lg">kode_jurusan = 1;</button>
            <button onclick="typeCode('WHERE')" class="term-btn py-4 bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 hover:border-[#00fff9] text-slate-300 hover:text-[#00fff9] font-mono font-bold tracking-widest uppercase shadow-lg">WHERE</button>
            <button onclick="typeCode('*')" class="term-btn py-4 bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 hover:border-[#00fff9] text-slate-300 hover:text-[#00fff9] font-mono font-bold tracking-widest shadow-lg">*</button>
            <button onclick="typeCode('UPDATE')" class="term-btn py-4 bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 hover:border-[#00fff9] text-slate-300 hover:text-[#00fff9] font-mono font-bold tracking-widest uppercase shadow-lg">UPDATE</button>
            <button onclick="typeCode('FROM')" class="term-btn py-4 bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 hover:border-[#00fff9] text-slate-300 hover:text-[#00fff9] font-mono font-bold tracking-widest uppercase shadow-lg">FROM</button>
        </div>

        <div class="flex gap-4">
            <button onclick="clearTerminal()" class="flex-1 py-4 bg-slate-200 dark:bg-zinc-800 hover:bg-rose-500 hover:text-white text-slate-600 dark:text-zinc-400 font-black tracking-widest uppercase transition-colors">CLEAR</button>
            <button onclick="checkQuery()" class="flex-1 py-4 bg-[#00fff9] hover:bg-teal-400 text-slate-900 font-black tracking-widest uppercase transition-all shadow-[0_0_20px_rgba(0,255,249,0.4)]">EXECUTE_CMD</button>
        </div>

        <form id="auto-submit-form" action="{{ route('quest.simulator.submit', $quiz->id) }}" method="POST" class="mt-8 hidden animate-slideUpFade">
            @csrf
            <button type="submit" class="w-full py-5 bg-emerald-500 hover:bg-emerald-400 text-white font-black text-xl tracking-widest uppercase shadow-[0_0_40px_rgba(16,185,129,0.5)] transition-all flex justify-center items-center gap-3 animate-pulse">
                > QUERY_ACCEPTED. PROCEED.
            </button>
        </form>
    </div>

    @elseif($level_id == 5)
    <div class="max-w-4xl mx-auto px-6 relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-6 py-1.5 border-l-2 border-r-2 border-sky-500 bg-sky-500/10 text-sky-600 dark:text-sky-400 text-xs font-mono font-black tracking-[0.3em] uppercase mb-4 animate-pulse">
                PHASE_05 // AGGREGATE CORE
            </div>
            <div class="glitch-wrapper block mb-4">
                <h1 class="text-4xl md:text-5xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch" data-text="DATA CRUNCHER ENGINE">Data Cruncher Engine</h1>
            </div>
            <p class="text-slate-600 dark:text-zinc-400 font-mono text-sm">> Sistem meminta rekapitulasi data. Hubungkan dengan instruksi mesin Agregat yang tepat!</p>
        </div>

        <div id="aggregate-monitor" class="w-full h-48 md:h-56 bg-[#020617] border border-sky-500/50 flex flex-col items-center justify-center mb-10 shadow-[0_0_50px_rgba(56,189,248,0.2)] monitor-pulse transition-colors duration-500 relative overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(56,189,248,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(56,189,248,0.05)_1px,transparent_1px)]" style="background-size: 20px 20px;"></div>
            <p class="text-sky-500 font-mono font-bold text-xs tracking-[0.3em] uppercase mb-4 relative z-10">> TASK_LOG (<span id="task-counter">1</span>/5)</p>
            <h2 id="task-question" class="text-2xl md:text-3xl font-display font-black text-white text-center px-6 leading-tight pop-in tracking-tight relative z-10 drop-shadow-[0_0_10px_rgba(56,189,248,0.8)]">
                [ INITIALIZING_CORE ]
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-10" id="aggregate-machines">
            <button onclick="checkAggregate('COUNT')" class="core-btn aspect-square bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 flex flex-col items-center justify-center gap-3 shadow-lg hover:border-amber-500 hover:bg-amber-500/10 group">
                <div class="w-14 h-14 rounded-none border border-amber-500/30 bg-amber-500/10 text-amber-500 flex items-center justify-center font-black text-2xl group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-black transition-all shadow-[0_0_15px_rgba(245,158,11,0.2)]">#</div>
                <span class="text-slate-300 dark:text-zinc-400 font-mono font-black tracking-widest group-hover:text-amber-500 transition-colors">COUNT</span>
            </button>
            
            <button onclick="checkAggregate('SUM')" class="core-btn aspect-square bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 flex flex-col items-center justify-center gap-3 shadow-lg hover:border-rose-500 hover:bg-rose-500/10 group">
                <div class="w-14 h-14 rounded-none border border-rose-500/30 bg-rose-500/10 text-rose-500 flex items-center justify-center font-black text-2xl group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white transition-all shadow-[0_0_15px_rgba(244,63,94,0.2)]">Σ</div>
                <span class="text-slate-300 dark:text-zinc-400 font-mono font-black tracking-widest group-hover:text-rose-500 transition-colors">SUM</span>
            </button>
            
            <button onclick="checkAggregate('AVG')" class="core-btn aspect-square bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 flex flex-col items-center justify-center gap-3 shadow-lg hover:border-emerald-500 hover:bg-emerald-500/10 group">
                <div class="w-14 h-14 rounded-none border border-emerald-500/30 bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-black text-2xl group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all shadow-[0_0_15px_rgba(16,185,129,0.2)]">x̄</div>
                <span class="text-slate-300 dark:text-zinc-400 font-mono font-black tracking-widest group-hover:text-emerald-500 transition-colors">AVG</span>
            </button>
            
            <button onclick="checkAggregate('MIN')" class="core-btn aspect-square bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 flex flex-col items-center justify-center gap-3 shadow-lg hover:border-indigo-500 hover:bg-indigo-500/10 group">
                <div class="w-14 h-14 rounded-none border border-indigo-500/30 bg-indigo-500/10 text-indigo-500 flex items-center justify-center font-black text-2xl group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">▼</div>
                <span class="text-slate-300 dark:text-zinc-400 font-mono font-black tracking-widest group-hover:text-indigo-500 transition-colors">MIN</span>
            </button>
            
            <button onclick="checkAggregate('MAX')" class="core-btn aspect-square bg-slate-900 dark:bg-zinc-900 border border-slate-700 dark:border-zinc-800 flex flex-col items-center justify-center gap-3 shadow-lg hover:border-purple-500 hover:bg-purple-500/10 group md:col-span-1 col-span-2">
                <div class="w-14 h-14 rounded-none border border-purple-500/30 bg-purple-500/10 text-purple-500 flex items-center justify-center font-black text-2xl group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all shadow-[0_0_15px_rgba(168,85,247,0.2)]">▲</div>
                <span class="text-slate-300 dark:text-zinc-400 font-mono font-black tracking-widest group-hover:text-purple-500 transition-colors">MAX</span>
            </button>
        </div>

        <form id="auto-submit-form" action="{{ route('quest.simulator.submit', $quiz->id) }}" method="POST" class="mt-8 hidden animate-slideUpFade">
            @csrf
            <button type="submit" class="w-full py-5 bg-[#00fff9] hover:bg-teal-400 text-slate-900 font-black text-xl tracking-widest uppercase shadow-[0_0_40px_rgba(0,255,249,0.5)] transition-all flex justify-center items-center gap-3 animate-pulse">
                > ENGINE_OVERRIDE_SUCCESS
            </button>
        </form>
    </div>

    @else
    <div class="max-w-3xl mx-auto px-6 relative z-10 text-center">
        <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-4">Simulator Belum Tersedia</h1>
        <p class="text-slate-500 dark:text-zinc-400 font-medium mb-8">Game untuk modul ini masih dalam tahap konstruksi oleh Game Master.</p>
        
        <form action="{{ route('quest.simulator.submit', $quiz->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl transition-all shadow-lg">
                Lewati Simulasi & Lanjut ke Teori
            </button>
        </form>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    // 🔊 AUDIO ENGINE (Diubah jadi questActx agar tidak bentrok dengan app.blade.php)
    const QuestAudioContext = window.AudioContext || window.webkitAudioContext;
    const questActx = new QuestAudioContext();
    
    function playSound(type) {
        if(questActx.state === 'suspended') questActx.resume();
        const osc = questActx.createOscillator();
        const gain = questActx.createGain();
        osc.connect(gain); gain.connect(questActx.destination);
        if(type === 'click' || type === 'pop') {
            osc.type = 'sine'; osc.frequency.setValueAtTime(800, questActx.currentTime); gain.gain.setValueAtTime(0.1, questActx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, questActx.currentTime + 0.1); osc.start(); osc.stop(questActx.currentTime + 0.1);
        } else if(type === 'connect' || type === 'correct') {
            osc.type = 'triangle'; osc.frequency.setValueAtTime(600, questActx.currentTime); osc.frequency.exponentialRampToValueAtTime(1200, questActx.currentTime + 0.2); gain.gain.setValueAtTime(0.2, questActx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, questActx.currentTime + 0.3); osc.start(); osc.stop(questActx.currentTime + 0.3);
        } else if(type === 'error') {
            osc.type = 'sawtooth'; osc.frequency.setValueAtTime(150, questActx.currentTime); osc.frequency.exponentialRampToValueAtTime(100, questActx.currentTime + 0.3); gain.gain.setValueAtTime(0.2, questActx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, questActx.currentTime + 0.3); osc.start(); osc.stop(questActx.currentTime + 0.3);
        } else if(type === 'win') {
            osc.type = 'square'; osc.frequency.setValueAtTime(400, questActx.currentTime); osc.frequency.setValueAtTime(600, questActx.currentTime + 0.1); osc.frequency.setValueAtTime(800, questActx.currentTime + 0.2); gain.gain.setValueAtTime(0.1, questActx.currentTime); gain.gain.linearRampToValueAtTime(0, questActx.currentTime + 0.5); osc.start(); osc.stop(questActx.currentTime + 0.5);
        } else if(type === 'shoot') {
            osc.type = 'sawtooth'; osc.frequency.setValueAtTime(900, questActx.currentTime); osc.frequency.exponentialRampToValueAtTime(100, questActx.currentTime + 0.15); gain.gain.setValueAtTime(0.2, questActx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, questActx.currentTime + 0.15); osc.start(); osc.stop(questActx.currentTime + 0.15);
        }
    }
    
    // 🎉 CYBER CONFETTI
    function fireConfetti() {
        const colors = ['#00fff9', '#3b82f6', '#8b5cf6', '#f43f5e', '#10b981'];
        for(let i=0; i<100; i++) {
            let conf = document.createElement('div');
            conf.style.position = 'fixed'; conf.style.left = '50%'; conf.style.top = '50%';
            conf.style.width = (Math.random() * 8 + 4) + 'px'; conf.style.height = (Math.random() * 8 + 4) + 'px';
            conf.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)]; 
            conf.style.boxShadow = `0 0 10px ${conf.style.backgroundColor}`;
            conf.style.zIndex = '9999'; conf.style.pointerEvents = 'none'; document.body.appendChild(conf);
            let angle = Math.random() * Math.PI * 2; let velocity = 15 + Math.random() * 30; let vx = Math.cos(angle) * velocity; let vy = Math.sin(angle) * velocity - 15; let gravity = 0.8; let opacity = 1; let rotation = 0;
            function animate() { let rect = conf.getBoundingClientRect(); conf.style.left = rect.left + vx + 'px'; conf.style.top = rect.top + vy + 'px'; conf.style.transform = `rotate(${rotation}deg)`; vy += gravity; opacity -= 0.015; rotation += 10; conf.style.opacity = opacity; if(opacity > 0) requestAnimationFrame(animate); else conf.remove(); }
            animate();
        }
    }
</script>

@if($level_id == 1)
<script>
    const dataItems = [{ text: "Buku Telepon Kertas", type: "manual" }, { text: "Aplikasi Kontak HP", type: "database" }, { text: "Lemari Map Sekolah", type: "manual" }, { text: "Tabel Nilai Excel", type: "database" }, { text: "Catatan Post-it", type: "manual" }, { text: "Sistem Kasir", type: "database" }]; 
    let currentIndex = 0; const card = document.getElementById('current-card'); const cardText = document.getElementById('card-text');
    
    function loadCard() { 
        if(currentIndex < dataItems.length) { 
            cardText.innerText = dataItems[currentIndex].text; 
            card.style.display = "block"; card.classList.remove('scale-0'); card.classList.add('pop-in'); 
            playSound('pop'); 
            setTimeout(() => card.classList.remove('pop-in'), 300); 
        } else { 
            card.style.display = "none"; 
            document.getElementById('bin-container').style.display = "none"; 
            document.getElementById('title-game').innerText = "SYSTEM SECURED!"; 
            document.getElementById('auto-submit-form').classList.remove('hidden'); 
            playSound('win'); fireConfetti(); 
        } 
    }
    
    document.getElementById('bin-manual').onclick = () => checkChoice('manual'); 
    document.getElementById('bin-database').onclick = () => checkChoice('database');
    
    function checkChoice(choice) { 
        if(choice === dataItems[currentIndex].type) { 
            playSound('correct'); currentIndex++; card.classList.add('scale-0'); setTimeout(loadCard, 300); 
        } else { 
            playSound('error'); card.classList.add('shake-error'); setTimeout(() => card.classList.remove('shake-error'), 400); 
        } 
    } 
    setTimeout(loadCard, 800);
</script>

@elseif($level_id == 2)
<script>
    // BUG FIXED: 'history' diubah menjadi 'connectedHistory' agar tidak crash dengan history Browser!
    const correctPairs = ['E1-A1', 'E1-A3', 'E2-A2', 'E2-A4']; 
    let selectedNode = null, connectionsMade = 0, connectedHistory = [];
    
    function handleNodeClick(id, type) { 
        const el = document.getElementById(id); 
        if(el.classList.contains('solved-node')) return; 
        playSound('click'); 
        
        if (!selectedNode) { 
            selectedNode = { id, type, el }; 
            el.classList.add('active-node'); 
        } else { 
            if (selectedNode.id === id) { 
                el.classList.remove('active-node'); 
                selectedNode = null; return; 
            } 
            if (selectedNode.type === type) { 
                selectedNode.el.classList.remove('active-node'); 
                selectedNode = { id, type, el }; 
                el.classList.add('active-node'); return; 
            } 
            
            const pair = selectedNode.type === 'entitas' ? `${selectedNode.id}-${id}` : `${id}-${selectedNode.id}`; 
            
            if (correctPairs.includes(pair) && !connectedHistory.includes(pair)) { 
                playSound('connect'); 
                drawKabel(selectedNode.id, id); 
                connectedHistory.push(pair); 
                connectionsMade++; 
                selectedNode.el.classList.remove('active-node'); 
                if (selectedNode.type === 'atribut') selectedNode.el.classList.add('solved-node'); 
                if (type === 'atribut') el.classList.add('solved-node'); 
                
                document.getElementById('game-status').innerText = `TARGET_LINK: ${connectionsMade} / 4`; 
                if(connectionsMade === 4) { 
                    setTimeout(() => { playSound('win'); fireConfetti(); document.getElementById('game-status').innerText = "ALL_NODES_SECURED!"; document.getElementById('auto-submit-form').classList.remove('hidden'); }, 500); 
                } 
            } else { 
                playSound('error'); 
                el.classList.add('shake-error'); 
                selectedNode.el.classList.add('shake-error'); 
                setTimeout(() => { el.classList.remove('shake-error'); selectedNode.el.classList.remove('shake-error', 'active-node'); }, 400); 
            } 
            selectedNode = null; 
        } 
    }
    
    function drawKabel(id1, id2) { 
        const el1 = document.getElementById(id1); const el2 = document.getElementById(id2); const container = document.getElementById('game-container').getBoundingClientRect(); const rect1 = el1.getBoundingClientRect(); const rect2 = el2.getBoundingClientRect(); const x1 = rect1.left - container.left + rect1.width / 2; const y1 = rect1.top - container.top + rect1.height / 2; const x2 = rect2.left - container.left + rect2.width / 2; const y2 = rect2.top - container.top + rect2.height / 2; const svg = document.getElementById('svg-canvas'); const line = document.createElementNS('http://www.w3.org/2000/svg', 'line'); line.setAttribute('x1', x1); line.setAttribute('y1', y1); line.setAttribute('x2', x2); line.setAttribute('y2', y2); line.setAttribute('class', 'line-cable'); svg.appendChild(line); 
    }
</script>

@elseif($level_id == 3)
<script>
    const anomalyTypes = ["REDUNDANSI_DATA.exe", "INSERT_ANOMALY.bat", "UPDATE_ANOMALY.dll", "DELETE_ANOMALY.sys", "PARTIAL_DEP.vbs", "TRANSITIVE_DEP.bin"]; 
    let anomaliesLeft = 5; 
    const arena = document.getElementById('targets-container'); 
    const mainScreen = document.getElementById('main-screen');
    
    function spawnAnomaly() { 
        if(anomaliesLeft <= 0) return; 
        const virus = document.createElement('div'); 
        const randomText = anomalyTypes[Math.floor(Math.random() * anomalyTypes.length)]; 
        virus.className = 'target-virus absolute px-4 py-2 bg-rose-600 border border-rose-400 text-white font-mono font-bold text-[10px] shadow-[0_0_20px_rgba(244,63,94,0.8)] flex items-center gap-2 select-none pointer-events-auto'; 
        virus.style.left = (10 + Math.random() * 70) + '%'; 
        virus.style.top = (10 + Math.random() * 70) + '%'; 
        virus.innerHTML = `<svg class="w-4 h-4 text-white animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>${randomText}`;
        
        virus.onclick = function() { 
            playSound('shoot'); 
            mainScreen.classList.add('shake-screen'); 
            setTimeout(() => mainScreen.classList.remove('shake-screen'), 200); 
            virus.classList.add('explode'); 
            anomaliesLeft--; 
            document.getElementById('game-status-3').innerText = `TARGETS_LEFT: ${anomaliesLeft}`; 
            
            if(anomaliesLeft <= 0) setTimeout(winLevel3, 500); 
            else setTimeout(spawnAnomaly, 300); 
        }; 
        arena.appendChild(virus);
    }
    
    function winLevel3() { 
        playSound('win'); fireConfetti(); 
        const arenaBg = document.getElementById('shooter-arena'); 
        arenaBg.classList.replace('border-rose-500/50', 'border-emerald-500/50'); 
        const statusBox = document.getElementById('l3-status-box'); 
        statusBox.classList.replace('bg-rose-50', 'bg-emerald-50'); 
        statusBox.classList.replace('border-rose-200', 'border-emerald-200'); 
        statusBox.classList.replace('dark:bg-[#0a0a0c]', 'dark:bg-emerald-500/10'); 
        statusBox.classList.replace('dark:border-rose-500/30', 'dark:border-emerald-500/30'); 
        document.getElementById('game-status-3').innerText = "SYSTEM NORMALIZED (3NF)"; 
        document.getElementById('game-status-3').className = "text-emerald-700 dark:text-emerald-400 font-mono font-bold tracking-widest uppercase text-sm"; 
        document.getElementById('auto-submit-form').classList.remove('hidden'); 
    }
    setTimeout(() => { spawnAnomaly(); setTimeout(spawnAnomaly, 500); }, 1000);
</script>

@elseif($level_id == 4)
<script>
    let currentQuery = []; const targetQuery = ['DELETE', 'FROM', 'mahasiswa', 'WHERE', 'kode_jurusan = 1;'];
    
    function typeCode(word) { playSound('click'); currentQuery.push(word); renderTerminal(); }
    
    function clearTerminal() { playSound('pop'); currentQuery = []; renderTerminal(); document.getElementById('terminal-output').classList.remove('glitch-error'); }
    
    function renderTerminal() { 
        const cursor = `<span class="w-4 h-8 bg-[#00fff9] blink-cursor inline-block" id="term-cursor"></span>`; 
        let html = '<span class="text-emerald-500 mr-2">SYS></span>'; 
        currentQuery.forEach(word => { 
            if(['DELETE', 'FROM', 'WHERE', 'DROP', 'UPDATE'].includes(word)) { 
                html += `<span class="text-fuchsia-400">${word}</span> `; 
            } else { 
                html += `<span>${word}</span> `; 
            } 
        }); 
        document.getElementById('terminal-output').innerHTML = html + cursor; 
    }
    
    function checkQuery() { 
        const outDiv = document.getElementById('terminal-output'); 
        if(JSON.stringify(currentQuery) === JSON.stringify(targetQuery)) { 
            playSound('win'); fireConfetti(); 
            outDiv.innerHTML = `<span class="text-[#00fff9] drop-shadow-[0_0_10px_#00fff9]">> 10 Rows affected. Query OK!</span>`; 
            document.getElementById('syntax-blocks').style.display = 'none'; 
            setTimeout(() => { document.getElementById('auto-submit-form').classList.remove('hidden'); }, 1000); 
        } else { 
            playSound('error'); outDiv.classList.add('glitch-error'); 
            outDiv.innerHTML = `<span class="text-rose-500">> FATAL_ERR 1064: SQL syntax mismatch!</span>`; 
            setTimeout(() => { clearTerminal(); }, 1500); 
        } 
    }
</script>

@elseif($level_id == 5)
<script>
    const aggregateTasks = [
        { q: "Hitung total baris data dari database mahasiswa!", ans: "COUNT" },
        { q: "Kalkulasi total pendapatan penjualan di kasir hari ini!", ans: "SUM" },
        { q: "Cari rata-rata nilai ujian matematika kelas 12!", ans: "AVG" },
        { q: "Identifikasi pegawai dengan usia paling muda!", ans: "MIN" },
        { q: "Cari data transaksi dengan harga tertinggi!", ans: "MAX" }
    ];
    let currentTask = 0; 
    const taskText = document.getElementById('task-question'); 
    const counterText = document.getElementById('task-counter'); 
    const monitor = document.getElementById('aggregate-monitor');

    function loadAggregateTask() {
        if(currentTask < aggregateTasks.length) {
            taskText.classList.remove('pop-in'); void taskText.offsetWidth; 
            taskText.innerText = aggregateTasks[currentTask].q;
            counterText.innerText = (currentTask + 1); taskText.classList.add('pop-in'); playSound('pop');
        } else {
            monitor.classList.replace('border-sky-500/50', 'border-[#00fff9]/50');
            monitor.classList.replace('shadow-[0_0_50px_rgba(56,189,248,0.2)]', 'shadow-[0_0_50px_rgba(0,255,249,0.5)]');
            taskText.innerText = "[ ALL_TASKS_COMPUTED ]";
            taskText.classList.replace('text-white', 'text-[#00fff9]');
            document.getElementById('aggregate-machines').style.display = "none";
            document.getElementById('auto-submit-form').classList.remove('hidden');
            playSound('win'); fireConfetti();
        }
    }

    function checkAggregate(answer) {
        if(answer === aggregateTasks[currentTask].ans) {
            playSound('correct'); monitor.classList.add('bg-sky-500/20');
            setTimeout(() => monitor.classList.remove('bg-sky-500/20'), 200);
            currentTask++; setTimeout(loadAggregateTask, 400);
        } else {
            playSound('error'); monitor.classList.add('shake-error');
            setTimeout(() => monitor.classList.remove('shake-error'), 400);
        }
    }
    setTimeout(loadAggregateTask, 800);
</script>
@endif
@endpush