@extends('layouts.app')

@section('title', 'DB-QUEST | Command Center')

@push('styles')
<style>
    /* 💥 EFEK GLITCH CYBERPUNK */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after {
        content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: transparent;
    }
    .glitch::before { left: 2px; text-shadow: -2px 0 #00fff9; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .glitch::after { left: -2px; text-shadow: -2px 0 #ff00c1, 2px 2px #00fff9; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); } 80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); } 40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); } 80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); } }

    /* 🎛️ HUD ASYMMETRIC CARD */
    .hud-card { transition: all 0.4s ease; backdrop-filter: blur(12px); position: relative; overflow: hidden; }
    .hud-cut-left { border-left: 4px solid #4f46e5; clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%); }
    .hud-cut-right { border-right: 4px solid #10b981; clip-path: polygon(0 0, 100% 0, 100% 100%, 20px 100%, 0 calc(100% - 20px)); }
    .hud-cut-tl { border-top: 4px solid #f43f5e; clip-path: polygon(20px 0, 100% 0, 100% 100%, 0 100%, 0 20px); }
    .hud-cut-br { border-bottom: 4px solid #0ea5e9; clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%); }

    @media (min-width: 768px) {
        .hud-cut-left { clip-path: polygon(0 0, 100% 0, 100% calc(100% - 30px), calc(100% - 30px) 100%, 0 100%); }
        .hud-cut-right { clip-path: polygon(0 0, 100% 0, 100% 100%, 30px 100%, 0 calc(100% - 30px)); }
        .hud-cut-tl { clip-path: polygon(30px 0, 100% 0, 100% 100%, 0 100%, 0 30px); }
        .hud-cut-br { clip-path: polygon(0 0, calc(100% - 30px) 0, 100% 30px, 100% 100%, 0 100%); }
    }

    /* 🌟 SUPER 3D TILT & GLARE EFFECT 🌟 */
    .tilt-card { transform-style: preserve-3d; transform: perspective(1500px); transition: transform 0.1s ease-out; }
    .tilt-card.resetting { transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1); }
    .pop-out { transform: translateZ(40px); transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1); }
    .tilt-card:hover .pop-out { transform: translateZ(80px); } 

    .card-glare {
        position: absolute; inset: 0; pointer-events: none; z-index: 10;
        background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.15) 0%, transparent 60%);
        opacity: 0; transition: opacity 0.3s ease; mix-blend-mode: overlay;
    }

    /* 🎯 TARGET LOCK BRACKETS 🎯 */
    .target-bracket { position: absolute; width: 25px; height: 25px; border: 2px solid transparent; transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1); opacity: 0; z-index: 5; pointer-events: none; }
    .bracket-tl { top: 15px; left: 15px; border-top-color: currentColor; border-left-color: currentColor; transform: translate(-10px, -10px); }
    .bracket-tr { top: 15px; right: 15px; border-top-color: currentColor; border-right-color: currentColor; transform: translate(10px, -10px); }
    .bracket-bl { bottom: 15px; left: 15px; border-bottom-color: currentColor; border-left-color: currentColor; transform: translate(-10px, 10px); }
    .bracket-br { bottom: 15px; right: 15px; border-bottom-color: currentColor; border-right-color: currentColor; transform: translate(10px, 10px); }
    .tilt-card:hover .target-bracket { opacity: 1; transform: translate(0, 0); }

    /* Animasi Laser Scanner Avatar */
    .avatar-scan { animation: avatarScan 2.5s infinite linear; }
    @keyframes avatarScan { 0% { top: -10%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 110%; opacity: 0; } }

    /* Boot Screen System */
    #system-boot {
        position: fixed; inset: 0; z-index: 999999; background: #020202;
        display: flex; flex-col; items-center; justify-center;
        transition: transform 0.8s cubic-bezier(0.85, 0, 0.15, 1);
    }
    .boot-done { transform: translateY(-100%); pointer-events: none; }
</style>
@endpush

@section('content')

<div id="system-boot" class="flex flex-col items-center justify-center font-mono">
    <div class="w-24 h-24 relative mb-8">
        <svg class="absolute inset-0 w-full h-full text-indigo-500 animate-[spin_2s_linear_infinite]" viewBox="0 0 100 100"><circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="30 15 10 20" /></svg>
        <svg class="absolute inset-0 w-full h-full text-[#00fff9] animate-[spin_3s_linear_infinite_reverse]" viewBox="0 0 100 100"><circle cx="50" cy="50" r="30" fill="none" stroke="currentColor" stroke-width="3" stroke-dasharray="10 30" /></svg>
        <div class="absolute inset-0 m-auto w-4 h-4 bg-indigo-600 rounded-sm rotate-45 animate-pulse shadow-[0_0_20px_#4f46e5]"></div>
    </div>
    
    <div class="text-left w-72 md:w-96">
        <p class="text-[#00fff9] text-sm md:text-base font-bold mb-1">> INITIALIZING MAINFRAME...</p>
        <p class="text-indigo-400 text-xs md:text-sm mb-4">> AUTHENTICATING COMMANDER <span class="animate-pulse">_</span></p>
        
        <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden shadow-[0_0_15px_rgba(99,102,241,0.3)]">
            <div id="boot-progress-bar" class="h-full bg-[#00fff9] w-0 transition-all duration-100 ease-out relative">
                <div class="absolute inset-0 w-full h-full bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.4)_50%,transparent_75%,transparent_100%)] bg-[length:10px_10px] animate-[moveGrid_1s_linear_infinite]"></div>
            </div>
        </div>
        <p class="text-right text-[#00fff9] text-xs mt-2 font-black tracking-widest"><span id="boot-progress-text">0</span>%</p>
    </div>
</div>

<div class="relative w-full py-12 overflow-hidden transition-colors duration-500 opacity-0" id="main-dashboard-content">
    
    <div class="absolute inset-0 opacity-10 bg-[linear-gradient(45deg,transparent_25%,rgba(99,102,241,0.2)_50%,transparent_75%,transparent_100%)] dark:bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.1)_50%,transparent_75%,transparent_100%)] bg-[length:30px_30px] z-[-1] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        
        <div class="mb-12 md:mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-none border-l-2 border-indigo-500 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-mono font-black tracking-[0.2em] uppercase mb-6 shadow-sm" data-aos="fade-right" data-aos-delay="600">
                <span class="w-2 h-2 bg-[#00fff9] animate-pulse shadow-[0_0_10px_#00fff9]"></span> SYSTEM.DASHBOARD // SECURE
            </div>
            
            <div class="glitch-wrapper block mb-8" data-aos="fade-down" data-aos-delay="700">
                <h1 class="text-5xl md:text-7xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch" data-text="COMMAND CENTER.">
                    COMMAND CENTER.
                </h1>
            </div>

            <div class="hud-card hud-cut-left bg-white/90 dark:bg-[#0a0a0c]/90 border border-slate-200 dark:border-white/5 p-6 md:p-10 shadow-2xl dark:shadow-[0_0_40px_rgba(99,102,241,0.1)] flex flex-col md:flex-row items-center justify-between gap-8 hover:border-indigo-400 relative overflow-hidden group" data-aos="zoom-in" data-aos-delay="800">
                
                <div class="absolute -right-20 -top-20 w-56 h-56 bg-indigo-500/10 blur-3xl rounded-full group-hover:bg-[#00fff9]/20 transition-colors duration-700"></div>

                <div class="flex items-center gap-6 w-full md:w-auto relative z-10">
                    <div class="relative w-24 h-24 md:w-28 md:h-28 flex items-center justify-center overflow-hidden rounded-2xl border border-indigo-500/50 shadow-[0_0_20px_rgba(99,102,241,0.4)]">
                        <div class="w-full h-full bg-gradient-to-br from-indigo-900 to-[#0a0a0c] flex items-center justify-center text-4xl font-black text-white z-0">
                            {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="avatar-scan absolute left-0 w-full h-[3px] bg-[#00fff9] shadow-[0_0_15px_#00fff9] z-10"></div>
                        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[length:4px_4px] z-0 pointer-events-none"></div>
                    </div>
                    
                    <div>
                        <p class="text-indigo-600 dark:text-indigo-400 font-mono font-bold tracking-widest uppercase text-[10px] mb-1">Active Commander</p>
                        <h2 class="text-3xl md:text-4xl font-black text-slate-800 dark:text-white tracking-tight mb-2 truncate max-w-[200px] md:max-w-[300px]">
                            {{ Auth::user()->name ?? 'Explorer' }}
                        </h2>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-600 dark:text-zinc-300">
                            <span class="w-2 h-2 bg-[#00fff9] animate-pulse"></span> Rank: NOVICE HACKER
                        </div>
                    </div>
                </div>
                
                <div class="w-full md:w-1/3 relative z-10">
                    <div class="flex justify-between items-end mb-2">
                        <p class="text-xs font-mono font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Hacking Progress</p>
                        <p class="text-sm font-black text-indigo-600 dark:text-[#00fff9] animate-pulse">{{ $totalPoints ?? '1200' }} <span class="text-[10px] text-slate-400 dark:text-slate-500">PT</span></p>
                    </div>
                    <div class="h-4 w-full bg-slate-200 dark:bg-[#050505] overflow-hidden border border-slate-300 dark:border-white/10 shadow-inner p-[2px]">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-[#00fff9] relative shadow-[0_0_15px_rgba(0,255,249,0.5)] w-[60%] flex items-center overflow-hidden">
                            <div class="absolute inset-0 w-full h-full bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.3)_50%,transparent_75%,transparent_100%)] bg-[length:15px_15px] animate-[moveGrid_1s_linear_infinite]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 perspective-1000">
            
            <a href="{{ route('adventure.index') }}" class="tilt-card block group cyber-link" onmouseenter="playHover()" onclick="playClick()" data-aos="zoom-out-up" data-aos-delay="900" data-aos-duration="1000">
                <div class="hud-card hud-cut-left h-full bg-white/80 dark:bg-[#0f172a]/90 backdrop-blur-xl border border-slate-200 dark:border-white/5 hover:border-[#00fff9] p-8 shadow-xl dark:shadow-none hover:shadow-[0_20px_50px_rgba(0,255,249,0.3)]">
                    <div class="target-bracket bracket-tl text-[#00fff9]"></div><div class="target-bracket bracket-tr text-[#00fff9]"></div><div class="target-bracket bracket-bl text-[#00fff9]"></div><div class="target-bracket bracket-br text-[#00fff9]"></div>
                    <div class="card-glare"></div>

                    <div class="flex items-start justify-between pop-out mb-8">
                        <div class="w-16 h-16 bg-indigo-100 dark:bg-[#00fff9]/10 flex items-center justify-center text-indigo-600 dark:text-[#00fff9] border border-indigo-200 dark:border-[#00fff9]/50 group-hover:scale-110 group-hover:rotate-12 transition-transform shadow-inner">
                            <svg class="w-8 h-8 drop-shadow-[0_0_10px_#00fff9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="px-3 py-1 bg-indigo-50 dark:bg-white/5 text-indigo-600 dark:text-[#00fff9] font-mono text-[10px] font-black border border-indigo-200 dark:border-white/10 animate-pulse uppercase tracking-widest shadow-[0_0_10px_rgba(0,255,249,0.2)]">
                            > Execute
                        </div>
                    </div>
                    <div class="pop-out">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-[#00fff9] uppercase tracking-tight drop-shadow-md">Misi Utama</h2>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-mono leading-relaxed group-hover:text-slate-300 transition-colors border-l-2 border-[#00fff9]/50 pl-3">Masuki simulasi! Lanjutkan perjalananmu meretas dan memecahkan kasus basis data.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('materials.index') }}" class="tilt-card block group cyber-link" onmouseenter="playHover()" onclick="playClick()" data-aos="zoom-out-up" data-aos-delay="1000" data-aos-duration="1000">
                <div class="hud-card hud-cut-right h-full bg-white/80 dark:bg-[#0f172a]/90 backdrop-blur-xl border border-slate-200 dark:border-white/5 hover:border-emerald-400 p-8 shadow-xl dark:shadow-none hover:shadow-[0_20px_50px_rgba(16,185,129,0.3)]">
                    <div class="target-bracket bracket-tl text-emerald-400"></div><div class="target-bracket bracket-tr text-emerald-400"></div><div class="target-bracket bracket-bl text-emerald-400"></div><div class="target-bracket bracket-br text-emerald-400"></div>
                    <div class="card-glare"></div>

                    <div class="flex items-start justify-between pop-out mb-8">
                        <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/50 group-hover:scale-110 group-hover:-rotate-12 transition-transform shadow-inner">
                            <svg class="w-8 h-8 drop-shadow-[0_0_10px_#10b981]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <div class="px-3 py-1 bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-zinc-400 font-mono text-[10px] font-black border border-slate-200 dark:border-white/10 uppercase tracking-widest shadow-sm">
                            > Read_Only
                        </div>
                    </div>
                    <div class="pop-out">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 uppercase tracking-tight drop-shadow-md">Database Log</h2>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-mono leading-relaxed group-hover:text-slate-300 transition-colors border-r-2 border-emerald-500/50 pr-3 text-right">Akses cepat ke seluruh arsip teori dan dokumentasi sistem yang telah dipelajari.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('quests.index') }}" class="tilt-card block group cyber-link" onmouseenter="playHover()" onclick="playClick()" data-aos="zoom-out-up" data-aos-delay="1100" data-aos-duration="1000">
                <div class="hud-card hud-cut-tl h-full bg-white/80 dark:bg-[#0f172a]/90 backdrop-blur-xl border border-slate-200 dark:border-white/5 hover:border-rose-500 p-8 shadow-xl dark:shadow-none hover:shadow-[0_20px_50px_rgba(244,63,94,0.3)]">
                    <div class="target-bracket bracket-tl text-rose-500"></div><div class="target-bracket bracket-tr text-rose-500"></div><div class="target-bracket bracket-bl text-rose-500"></div><div class="target-bracket bracket-br text-rose-500"></div>
                    <div class="card-glare"></div>

                    <div class="flex items-start justify-between pop-out mb-8">
                        <div class="w-16 h-16 bg-rose-100 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-500 border border-rose-200 dark:border-rose-500/50 group-hover:scale-110 group-hover:rotate-12 transition-transform shadow-inner">
                            <svg class="w-8 h-8 drop-shadow-[0_0_10px_#f43f5e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                    </div>
                    <div class="pop-out">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2 group-hover:text-rose-600 dark:group-hover:text-rose-500 uppercase tracking-tight drop-shadow-md">Arena Latihan</h2>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-mono leading-relaxed group-hover:text-slate-300 transition-colors border-l-2 border-rose-500/50 pl-3">Ulangi tantangan simulasi, pecahkan rekor waktu tercepat, dan raih skor tertinggimu.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('player.profile') }}" class="tilt-card block group cyber-link" onmouseenter="playHover()" onclick="playClick()" data-aos="zoom-out-up" data-aos-delay="1200" data-aos-duration="1000">
                <div class="hud-card hud-cut-br h-full bg-white/80 dark:bg-[#0f172a]/90 backdrop-blur-xl border border-slate-200 dark:border-white/5 hover:border-sky-400 p-8 shadow-xl dark:shadow-none hover:shadow-[0_20px_50px_rgba(56,189,248,0.3)]">
                    <div class="target-bracket bracket-tl text-sky-400"></div><div class="target-bracket bracket-tr text-sky-400"></div><div class="target-bracket bracket-bl text-sky-400"></div><div class="target-bracket bracket-br text-sky-400"></div>
                    <div class="card-glare"></div>

                    <div class="flex items-start justify-between pop-out mb-8">
                        <div class="w-16 h-16 bg-sky-100 dark:bg-sky-500/10 flex items-center justify-center text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-500/50 group-hover:scale-110 group-hover:-rotate-12 transition-transform shadow-inner">
                            <svg class="w-8 h-8 drop-shadow-[0_0_10px_#38bdf8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                    </div>
                    <div class="pop-out">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2 group-hover:text-sky-600 dark:group-hover:text-sky-400 uppercase tracking-tight drop-shadow-md">User Config</h2>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-mono leading-relaxed group-hover:text-slate-300 transition-colors border-r-2 border-sky-400/50 pr-3 text-right">Kustomisasi identitas akun, periksa riwayat pencapaian, dan atur konfigurasi pemain.</p>
                    </div>
                </div>
            </a>

        </div>

        <div class="mt-32 mb-10" data-aos="zoom-in-up" data-aos-offset="50">
            <div class="flex items-center gap-4 mb-6">
                <div class="h-px bg-slate-300 dark:bg-slate-700 flex-1"></div>
                <h3 class="text-slate-500 dark:text-slate-400 font-mono font-bold tracking-widest uppercase text-sm">System Logs</h3>
                <div class="h-px bg-slate-300 dark:bg-slate-700 flex-1"></div>
            </div>

            <div class="w-full bg-slate-100 dark:bg-black border-2 border-slate-300 dark:border-slate-800 rounded-xl overflow-hidden shadow-2xl">
                <div class="bg-slate-200 dark:bg-zinc-900 px-4 py-2 border-b border-slate-300 dark:border-white/5 flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-rose-500"></div><div class="w-3 h-3 rounded-full bg-amber-500"></div><div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    <span class="text-slate-500 dark:text-zinc-500 text-xs ml-4 font-mono">recent_activity.log</span>
                </div>
                <div class="p-6 md:p-8 font-mono text-sm md:text-base text-left min-h-[200px]" id="terminal-log-container">
                    <p class="text-[#00fff9] dark:text-emerald-500/50 mb-2">-- Monitoring recent user activity in mainframe</p>
                    <div id="typewriter-text" class="text-slate-700 dark:text-slate-300 leading-relaxed"></div>
                    <div class="text-[#00fff9] dark:text-emerald-400 font-bold mt-2">
                        <span class="mr-2">></span><span class="w-3 h-6 bg-[#00fff9] animate-pulse inline-block align-middle"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const username = "{{ Auth::user()->name ?? 'Player' }}";

    // 🚀 LOGIKA SYSTEM BOOT (LAYAR LOADING AWAL) 🚀
    document.addEventListener("DOMContentLoaded", () => {
        let progress = 0;
        const bootText = document.getElementById('boot-progress-text');
        const bootBar = document.getElementById('boot-progress-bar');
        const bootScreen = document.getElementById('system-boot');
        const dashboardContent = document.getElementById('main-dashboard-content');
        
        document.body.style.overflow = 'hidden';
        
        const bootInterval = setInterval(() => {
            progress += Math.floor(Math.random() * 20) + 5; 
            if (progress >= 100) {
                progress = 100;
                clearInterval(bootInterval);
                setTimeout(() => {
                    bootScreen.classList.add('boot-done');
                    dashboardContent.classList.remove('opacity-0');
                    document.body.style.overflow = 'auto'; 
                    
                    if(typeof playSound !== 'undefined') playSound('win');
                    else {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = ctx.createOscillator(); const gain = ctx.createGain();
                        osc.connect(gain); gain.connect(ctx.destination);
                        osc.type = 'square'; osc.frequency.setValueAtTime(400, ctx.currentTime); osc.frequency.exponentialRampToValueAtTime(800, ctx.currentTime + 0.2);
                        gain.gain.setValueAtTime(0.1, ctx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.2);
                        osc.start(); osc.stop(ctx.currentTime + 0.2);
                    }
                    setTimeout(() => { AOS.refreshHard(); }, 100);
                }, 400); 
            }
            bootText.innerText = progress;
            bootBar.style.width = progress + '%';
        }, 80);
    });

    // 🚀 LOGIKA 3D TILT EFFECT & GLARE SUPER TAJAM 🚀
    document.addEventListener("DOMContentLoaded", () => {
        const cards = document.querySelectorAll('.tilt-card');
        if(window.innerWidth > 768) {
            cards.forEach(card => {
                const glare = card.querySelector('.card-glare');
                
                card.addEventListener('mousemove', (e) => {
                    card.classList.remove('resetting');
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    const rotateX = ((y - centerY) / centerY) * -20;
                    const rotateY = ((x - centerX) / centerX) * 20;
                    
                    card.style.transform = `perspective(1500px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
                    
                    if(glare) {
                        glare.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(255,255,255,0.2) 0%, transparent 60%)`;
                        glare.style.opacity = '1';
                    }
                });
                
                card.addEventListener('mouseleave', () => {
                    card.classList.add('resetting');
                    card.style.transform = `perspective(1500px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
                    if(glare) glare.style.opacity = '0';
                });
            });
        }
    });

    // 🚀 LOGIKA AUTO-TYPING TERMINAL LOGS 🚀
    const logMessages = [
        `[${new Date().toLocaleTimeString()}] SYSTEM: User ${username} logged in successfully.<br>`,
        `[${new Date().toLocaleTimeString()}] SECURITY: Handshake verified. Access granted to Command Center.<br>`,
        `[${new Date().toLocaleTimeString()}] MODULE: Waiting for user execution command...`
    ];
    let msgIndex = 0;
    let charIndex = 0;
    const typeWriterEl = document.getElementById('typewriter-text');
    let hasTyped = false;

    function typeTerminal() {
        if (msgIndex < logMessages.length) {
            const currentMsg = logMessages[msgIndex];
            if (currentMsg.charAt(charIndex) === '<') {
                let tag = '';
                while (currentMsg.charAt(charIndex) !== '>' && charIndex < currentMsg.length) {
                    tag += currentMsg.charAt(charIndex);
                    charIndex++;
                }
                tag += '>';
                typeWriterEl.innerHTML += tag;
                charIndex++;
            } else {
                typeWriterEl.innerHTML += currentMsg.charAt(charIndex);
                charIndex++;
            }
            if (charIndex < currentMsg.length) {
                setTimeout(typeTerminal, Math.random() * 30 + 20); 
            } else {
                msgIndex++; charIndex = 0;
                setTimeout(typeTerminal, 400); 
            }
        }
    }

    window.addEventListener('scroll', () => {
        const logContainer = document.getElementById('terminal-log-container');
        if(!logContainer) return;
        const rect = logContainer.getBoundingClientRect();
        if (rect.top < window.innerHeight && !hasTyped) {
            hasTyped = true;
            setTimeout(typeTerminal, 500); 
        }
    });
</script>
@endpush