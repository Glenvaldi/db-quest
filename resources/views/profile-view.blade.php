@extends('layouts.app')

@section('title', 'DB-QUEST | Player Profile')

@push('styles')
<style>
    /* 💥 GLITCH TEKS CYBERPUNK UTAMA */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after {
        content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: transparent;
    }
    .glitch::before { left: 2px; text-shadow: -2px 0 #4f46e5; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .glitch::after { left: -2px; text-shadow: -2px 0 #00fff9, 2px 2px #4f46e5; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); } 80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); } 40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); } 80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); } }

    /* 🎛️ HUD ASYMMETRIC CARD */
    .hud-card { transition: all 0.4s ease; backdrop-filter: blur(12px); position: relative; overflow: hidden; }
    .hud-cut-bl { border-left: 4px solid #4f46e5; border-bottom: 1px solid rgba(79,70,229,0.3); clip-path: polygon(0 0, 100% 0, 100% 100%, 30px 100%, 0 calc(100% - 30px)); }
    .hud-cut-tr { clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%); }
    .hud-cut-locked { border-top: 4px solid #64748b; border-right: 1px solid rgba(100,116,139,0.3); clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%); }

    /* 🌟 SUPER 3D TILT & GLARE EFFECT 🌟 */
    .tilt-card { transform-style: preserve-3d; transform: perspective(1500px); transition: transform 0.1s ease-out; }
    .tilt-card.resetting { transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1); }
    .pop-out { transform: translateZ(40px); transition: transform 0.3s ease; }
    .tilt-card:hover .pop-out { transform: translateZ(70px); } 

    .card-glare {
        position: absolute; inset: 0; pointer-events: none; z-index: 10;
        background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.2) 0%, transparent 60%);
        opacity: 0; transition: opacity 0.3s ease; mix-blend-mode: overlay;
    }

    /* 🎯 TARGET LOCK BRACKETS 🎯 */
    .target-bracket { position: absolute; width: 20px; height: 20px; border: 2px solid transparent; transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1); opacity: 0; z-index: 5; pointer-events: none; }
    .bracket-tl { top: 10px; left: 10px; border-top-color: currentColor; border-left-color: currentColor; transform: translate(-10px, -10px); }
    .bracket-tr { top: 10px; right: 10px; border-top-color: currentColor; border-right-color: currentColor; transform: translate(10px, -10px); }
    .bracket-bl { bottom: 10px; left: 10px; border-bottom-color: currentColor; border-left-color: currentColor; transform: translate(-10px, 10px); }
    .bracket-br { bottom: 10px; right: 10px; border-bottom-color: currentColor; border-right-color: currentColor; transform: translate(10px, 10px); }

    .tilt-card:hover .target-bracket { opacity: 1; transform: translate(0, 0); }

    /* 🚨 CYBER WARNING TAPE (KARTU TERKUNCI) 🚨 */
    .cyber-tape {
        position: absolute; inset: 0; pointer-events: none; z-index: 20; opacity: 0.15;
        background: repeating-linear-gradient(45deg, #64748b, #64748b 10px, transparent 10px, transparent 20px);
        background-size: 28px 28px; animation: moveTape 1s linear infinite;
    }
    @keyframes moveTape { 0% { background-position: 0 0; } 100% { background-position: 28px 0; } }

    /* Scanlines Khusus Latar */
    .scanlines-bg {
        position: absolute; inset: 0; pointer-events: none; z-index: 0;
        background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1));
        background-size: 100% 4px; opacity: 0.2;
    }

    /* Animasi Laser Scanner Avatar */
    .avatar-scan { animation: avatarScan 2.5s infinite linear; }
    @keyframes avatarScan { 0% { top: -10%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 110%; opacity: 0; } }

    /* Progress Bar Halus */
    @keyframes fillBar { from { width: 0; } }
    .animate-fill { animation: fillBar 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endpush

@section('content')
<div class="relative w-full min-h-[calc(100vh-5rem)] bg-slate-50 dark:bg-[#050505] transition-colors duration-500 py-12 overflow-hidden">
    
    <div class="absolute inset-0 opacity-10 bg-[linear-gradient(45deg,transparent_25%,rgba(99,102,241,0.2)_50%,transparent_75%,transparent_100%)] dark:bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.1)_50%,transparent_75%,transparent_100%)] bg-[length:30px_30px] z-0 pointer-events-none"></div>
    <div class="scanlines-bg"></div>
    
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-indigo-500/10 dark:bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none z-0 transition-colors duration-500"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6">
        
        <header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 border-l-2 border-r-2 border-indigo-500 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-mono font-black tracking-[0.3em] uppercase mb-6 shadow-[0_0_20px_rgba(99,102,241,0.2)]">
                    <span class="w-2 h-2 bg-indigo-500 animate-pulse shadow-[0_0_10px_#4f46e5]"></span> 
                    SYS.PROFILE // IDENTIFICATION
                </div>
                
                <div class="glitch-wrapper block mb-4">
                    <h1 class="text-4xl md:text-6xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch scramble-text drop-shadow-md" data-text="USER CONFIGURATION.">
                        USER CONFIGURATION.
                    </h1>
                </div>
                <p class="text-slate-600 dark:text-slate-400 font-mono text-sm md:text-base max-w-xl">
                    > Memuat kredensial operator. Pantau pangkat, poin pengalaman, dan status koneksimu di sini._
                </p>
            </div>
            
            <div data-aos="fade-left" data-aos-duration="1000" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto group cyber-link flex items-center justify-center gap-3 px-6 py-3 bg-rose-500/10 border border-rose-500 text-rose-500 font-black tracking-widest text-sm uppercase transition-all shadow-[0_0_15px_rgba(244,63,94,0.2)] hover:shadow-[0_0_30px_#f43f5e] hover:bg-rose-500 hover:text-white overflow-hidden" onmouseenter="typeof playSound !== 'undefined' ? playSound('hover') : ''" onclick="typeof playSound !== 'undefined' ? playSound('click') : ''">
                        <svg class="w-5 h-5 relative z-10 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span class="relative z-10">> DISCONNECT</span>
                    </button>
                </form>

                <a href="/dashboard" class="w-full sm:w-auto group cyber-link flex items-center justify-center gap-3 px-6 py-3 bg-transparent border border-indigo-500 text-indigo-600 dark:text-indigo-400 font-black tracking-widest text-sm uppercase transition-all shadow-[0_0_20px_rgba(99,102,241,0.2)] hover:shadow-[0_0_30px_#4f46e5] overflow-hidden" onmouseenter="typeof playSound !== 'undefined' ? playSound('hover') : ''" onclick="typeof playSound !== 'undefined' ? playSound('click') : ''">
                    <div class="absolute inset-0 w-0 bg-indigo-500 transition-all duration-300 ease-out group-hover:w-full z-0"></div>
                    <svg class="w-5 h-5 relative z-10 group-hover:-translate-x-1 transition-transform group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span class="relative z-10 group-hover:text-white">> RETURN_HOME</span>
                </a>
            </div>
        </header>

        <div class="hud-card hud-cut-bl bg-white/90 dark:bg-[#0a0a0c]/90 border border-slate-200 dark:border-white/5 rounded-none p-8 md:p-12 mb-16 shadow-2xl dark:shadow-[0_0_40px_rgba(99,102,241,0.1)] hover:border-indigo-400 transition-colors duration-500" data-aos="zoom-in" data-aos-delay="200">
            
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-8 md:gap-12">
                
                <div class="relative group shrink-0">
                    <svg class="absolute inset-[-15%] w-[130%] h-[130%] text-indigo-500 animate-[spin_6s_linear_infinite] opacity-50" viewBox="0 0 100 100"><circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="20 10 5 15" /></svg>
                    <svg class="absolute inset-[-15%] w-[130%] h-[130%] text-[#00fff9] animate-[spin_8s_linear_infinite_reverse] opacity-30" viewBox="0 0 100 100"><circle cx="50" cy="50" r="35" fill="none" stroke="currentColor" stroke-width="1" stroke-dasharray="10 30" /></svg>
                    
                    <div class="relative w-32 h-32 md:w-40 md:h-40 rounded-full bg-slate-100 dark:bg-zinc-900 border-4 border-indigo-500 dark:border-[#00fff9] flex items-center justify-center text-6xl font-black text-white bg-gradient-to-br from-indigo-500 to-purple-600 shadow-[0_0_30px_rgba(99,102,241,0.5)] overflow-hidden z-10">
                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                        
                        <div class="avatar-scan absolute left-0 w-full h-[3px] bg-[#00fff9] shadow-[0_0_15px_#00fff9] z-20"></div>
                        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.1)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.1)_1px,transparent_1px)] bg-[length:5px_5px] z-10 pointer-events-none"></div>
                    </div>
                    
                    <div class="absolute -bottom-2 right-2 bg-indigo-600 text-[#00fff9] text-xs font-black px-4 py-1.5 rounded-full border-2 border-[#00fff9] shadow-[0_0_15px_#00fff9] z-20 uppercase tracking-widest animate-pulse">
                        Lv. {{ ($levelsCompleted ?? 0) + 1 }}
                    </div>
                </div>

                <div class="text-center md:text-left flex-1 w-full mt-4 md:mt-0 flex flex-col justify-between">
                    <div class="flex flex-col md:flex-row justify-between items-center md:items-start mb-6 gap-4">
                        <div class="flex flex-col items-center md:items-start">
                            <p class="text-indigo-600 dark:text-indigo-400 font-mono font-bold tracking-widest uppercase text-xs mb-1">> IDENTITY_REVEALED</p>
                            <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tighter mb-1 uppercase scramble-text drop-shadow-md" data-text="{{ Auth::user()->name ?? 'EXPLORER' }}">{{ Auth::user()->name ?? 'EXPLORER' }}</h2>
                            <p class="text-slate-500 dark:text-zinc-400 font-mono text-sm tracking-wide">{{ Auth::user()->email ?? 'user@db-quest.sys' }}</p>
                            
                            <a href="{{ route('profile.settings') }}" class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 dark:bg-zinc-800/50 border border-slate-300 dark:border-white/10 text-slate-600 dark:text-slate-400 font-mono text-[10px] font-bold uppercase tracking-widest hover:text-indigo-600 hover:border-indigo-500 dark:hover:text-[#00fff9] dark:hover:border-[#00fff9] transition-all cyber-link">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                > CONFIG_SYSTEM
                            </a>
                        </div>

                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-300 dark:border-emerald-500/30 text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest shadow-[0_0_15px_rgba(16,185,129,0.2)] h-fit">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            ACTIVE_NODE
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 md:gap-6 mb-8 mt-4">
                        <div class="bg-slate-100 dark:bg-[#050505]/50 px-5 py-4 border border-slate-200 dark:border-white/5 transition-colors border-l-4 border-l-indigo-500">
                            <p class="text-[10px] text-slate-500 dark:text-zinc-500 font-mono font-bold uppercase tracking-widest mb-1">Current_Rank</p>
                            <p class="text-xl md:text-2xl font-black text-indigo-600 dark:text-indigo-400 uppercase">Newbie Coder</p>
                        </div>
                        <div class="bg-slate-100 dark:bg-[#050505]/50 px-5 py-4 border border-slate-200 dark:border-white/5 transition-colors border-l-4 border-l-[#00fff9]">
                            <p class="text-[10px] text-slate-500 dark:text-zinc-500 font-mono font-bold uppercase tracking-widest mb-1">Total_EXP</p>
                            <p class="text-xl md:text-2xl font-black text-slate-800 dark:text-white">{{ $totalPoints ?? '0' }} <span class="text-xs md:text-sm font-bold text-[#00fff9]">PT</span></p>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-end mb-2 font-mono">
                            <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">> Next Rank: Explorer</p>
                            <p class="text-sm font-black text-indigo-600 dark:text-[#00fff9] animate-pulse">{{ round((($levelsCompleted ?? 0) / 3) * 100) }}%</p>
                        </div>
                        <div class="h-4 w-full bg-slate-200 dark:bg-[#050505] overflow-hidden border border-slate-300 dark:border-white/10 shadow-inner p-[2px]">
                            <div class="h-full bg-gradient-to-r from-indigo-500 to-[#00fff9] animate-fill shadow-[0_0_15px_rgba(0,255,249,0.5)] relative overflow-hidden" style="width: {{ (($levelsCompleted ?? 0) / 3) * 100 }}%;">
                                <div class="absolute inset-0 w-full h-full bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.3)_50%,transparent_75%,transparent_100%)] bg-[length:15px_15px] animate-[moveGrid_1s_linear_infinite]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="animate-fade-in-up" style="animation-delay: 300ms;">
            <div class="flex items-center gap-4 mb-8">
                <div class="h-px bg-slate-300 dark:bg-slate-800 flex-1"></div>
                <h3 class="text-slate-500 dark:text-slate-400 font-mono font-bold tracking-widest uppercase text-sm">> ACHIEVEMENTS_LOG</h3>
                <div class="h-px bg-slate-300 dark:bg-slate-800 flex-1"></div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 perspective-1000">
                
                <div class="tilt-card block group">
                    <div class="hud-card hud-cut-tr h-full bg-white/90 dark:bg-[#0f172a]/90 backdrop-blur-xl border border-slate-200 dark:border-white/5 hover:border-indigo-400 dark:hover:border-indigo-500 p-6 shadow-xl dark:shadow-none hover:shadow-[0_15px_30px_rgba(99,102,241,0.2)] text-center text-indigo-500 flex flex-col justify-center">
                        <div class="target-bracket bracket-tl"></div><div class="target-bracket bracket-tr"></div><div class="target-bracket bracket-bl"></div><div class="target-bracket bracket-br"></div>
                        <div class="card-glare"></div>

                        <div class="pop-out w-full flex flex-col items-center">
                            <div class="mb-4 text-indigo-500 filter drop-shadow-[0_0_15px_rgba(99,102,241,0.8)] group-hover:brightness-125 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-14 h-14 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h4 class="font-black text-slate-900 dark:text-white text-sm md:text-base uppercase tracking-widest mb-1 group-hover:text-indigo-500 transition-colors">Newbie Coder</h4>
                            <p class="text-[10px] font-mono text-slate-500 dark:text-zinc-400 uppercase">> Access Granted</p>
                        </div>
                    </div>
                </div>

                @if(($levelsCompleted ?? 0) >= 1)
                <div class="tilt-card block group">
                    <div class="hud-card hud-cut-tr h-full bg-white/90 dark:bg-[#0f172a]/90 backdrop-blur-xl border border-slate-200 dark:border-white/5 hover:border-emerald-400 dark:hover:border-emerald-500 p-6 shadow-xl dark:shadow-none hover:shadow-[0_15px_30px_rgba(16,185,129,0.2)] text-center text-emerald-500 flex flex-col justify-center">
                        <div class="target-bracket bracket-tl"></div><div class="target-bracket bracket-tr"></div><div class="target-bracket bracket-bl"></div><div class="target-bracket bracket-br"></div>
                        <div class="card-glare"></div>

                        <div class="pop-out w-full flex flex-col items-center">
                            <div class="mb-4 text-emerald-500 filter drop-shadow-[0_0_15px_rgba(16,185,129,0.8)] group-hover:brightness-125 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-14 h-14 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                            </div>
                            <h4 class="font-black text-slate-900 dark:text-white text-sm md:text-base uppercase tracking-widest mb-1 group-hover:text-emerald-500 transition-colors">Data Miner</h4>
                            <p class="text-[10px] font-mono text-slate-500 dark:text-zinc-400 uppercase">> Modul_01 Cleared</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="hud-card hud-cut-locked h-full p-6 bg-slate-100/90 dark:bg-[#050505]/90 border border-slate-300 dark:border-slate-800 backdrop-blur-xl opacity-70 flex flex-col justify-center items-center text-center">
                    <div class="cyber-tape"></div>
                    <div class="mb-4 text-slate-400 dark:text-slate-600 opacity-50 flex justify-center">
                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <h4 class="font-black text-slate-500 dark:text-slate-600 text-sm uppercase tracking-widest mb-1">Terkunci</h4>
                    <p class="text-[10px] font-mono text-slate-400 dark:text-slate-700 uppercase">> Butuh Clearance</p>
                </div>
                @endif

                @if(($levelsCompleted ?? 0) >= 2)
                <div class="tilt-card block group">
                    <div class="hud-card hud-cut-tr h-full bg-white/90 dark:bg-[#0f172a]/90 backdrop-blur-xl border border-slate-200 dark:border-white/5 hover:border-purple-400 dark:hover:border-purple-500 p-6 shadow-xl dark:shadow-none hover:shadow-[0_15px_30px_rgba(168,85,247,0.2)] text-center text-purple-500 flex flex-col justify-center">
                        <div class="target-bracket bracket-tl"></div><div class="target-bracket bracket-tr"></div><div class="target-bracket bracket-bl"></div><div class="target-bracket bracket-br"></div>
                        <div class="card-glare"></div>

                        <div class="pop-out w-full flex flex-col items-center">
                            <div class="mb-4 text-purple-500 filter drop-shadow-[0_0_15px_rgba(168,85,247,0.8)] group-hover:brightness-125 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-14 h-14 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                                </svg>
                            </div>
                            <h4 class="font-black text-slate-900 dark:text-white text-sm md:text-base uppercase tracking-widest mb-1 group-hover:text-purple-500 transition-colors">ERD Architect</h4>
                            <p class="text-[10px] font-mono text-slate-500 dark:text-zinc-400 uppercase">> Modul_02 Cleared</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="hud-card hud-cut-locked h-full p-6 bg-slate-100/90 dark:bg-[#050505]/90 border border-slate-300 dark:border-slate-800 backdrop-blur-xl opacity-70 flex flex-col justify-center items-center text-center">
                    <div class="cyber-tape"></div>
                    <div class="mb-4 text-slate-400 dark:text-slate-600 opacity-50 flex justify-center">
                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <h4 class="font-black text-slate-500 dark:text-slate-600 text-sm uppercase tracking-widest mb-1">Terkunci</h4>
                    <p class="text-[10px] font-mono text-slate-400 dark:text-slate-700 uppercase">> Butuh Clearance</p>
                </div>
                @endif

                @if(($levelsCompleted ?? 0) >= 3)
                <div class="tilt-card block group">
                    <div class="hud-card hud-cut-tr h-full bg-white/90 dark:bg-[#0f172a]/90 backdrop-blur-xl border border-slate-200 dark:border-white/5 hover:border-amber-400 dark:hover:border-amber-500 p-6 shadow-xl dark:shadow-none hover:shadow-[0_15px_30px_rgba(245,158,11,0.2)] text-center text-amber-500 flex flex-col justify-center">
                        <div class="target-bracket bracket-tl"></div><div class="target-bracket bracket-tr"></div><div class="target-bracket bracket-bl"></div><div class="target-bracket bracket-br"></div>
                        <div class="card-glare"></div>

                        <div class="pop-out w-full flex flex-col items-center">
                            <div class="mb-4 text-amber-500 filter drop-shadow-[0_0_15px_rgba(245,158,11,0.8)] group-hover:brightness-125 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-14 h-14 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h4 class="font-black text-slate-900 dark:text-white text-sm md:text-base uppercase tracking-widest mb-1 group-hover:text-amber-500 transition-colors">Query Master</h4>
                            <p class="text-[10px] font-mono text-slate-500 dark:text-zinc-400 uppercase">> Modul_03 Cleared</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="hud-card hud-cut-locked h-full p-6 bg-slate-100/90 dark:bg-[#050505]/90 border border-slate-300 dark:border-slate-800 backdrop-blur-xl opacity-70 flex flex-col justify-center items-center text-center">
                    <div class="cyber-tape"></div>
                    <div class="mb-4 text-slate-400 dark:text-slate-600 opacity-50 flex justify-center">
                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <h4 class="font-black text-slate-500 dark:text-slate-600 text-sm uppercase tracking-widest mb-1">Terkunci</h4>
                    <p class="text-[10px] font-mono text-slate-400 dark:text-slate-700 uppercase">> Butuh Clearance</p>
                </div>
                @endif
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 🚀 LOGIKA DEKRIPSI TEKS CYBERPUNK 🚀
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*X";
    let hasDecrypted = new Set(); 

    function scrambleText(element) {
        const originalText = element.getAttribute('data-text');
        if (!originalText) return;
        
        let iteration = 0;
        const interval = setInterval(() => {
            element.innerText = originalText.split("").map((letter, index) => {
                if(index < iteration) return originalText[index];
                return chars[Math.floor(Math.random() * chars.length)];
            }).join("");
            
            iteration += 1 / 3; 
            
            if(iteration >= originalText.length) {
                clearInterval(interval);
                element.innerText = originalText;
            }
        }, 30);
    }

    // 🚀 LOGIKA 3D TILT EFFECT & TRIGGER DEKRIPSI PADA SCROLL 🚀
    document.addEventListener("DOMContentLoaded", () => {
        const cards = document.querySelectorAll('.tilt-card');
        const scrambleElements = document.querySelectorAll('.scramble-text');

        // Observer buat decrypt text
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !hasDecrypted.has(entry.target)) {
                    scrambleText(entry.target);
                    hasDecrypted.add(entry.target);
                }
            });
        }, { threshold: 0.5 }); 

        scrambleElements.forEach(el => observer.observe(el));

        // 3D Hover Tilt Logic
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
                    
                    const rotateX = ((y - centerY) / centerY) * -15;
                    const rotateY = ((x - centerX) / centerX) * 15;
                    
                    card.style.transform = `perspective(1500px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
                    
                    if(glare) {
                        glare.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(255,255,255,0.3) 0%, transparent 60%)`;
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
</script>
@endpush