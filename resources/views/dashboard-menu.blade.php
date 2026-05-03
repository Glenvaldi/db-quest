@extends('layouts.app')

@section('title', 'DB-QUEST | Mainframe Cockpit')

@push('styles')
<style>
    /* 🎯 CUSTOM CYBER CURSOR */
    body { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%2300fff9" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>') 12 12, auto; }
    html:not(.dark) body { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%234f46e5" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>') 12 12, auto; }
    
    a, button, .cyber-link, .nav-btn, .tilt-card, .reactor-core a { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23f43f5e" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 0v5M12 19v5M0 12h5M19 12h5"/></svg>') 12 12, pointer !important; }

    /* 💥 GLITCH TEKS RESPONSIVE */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after { content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: transparent; }
    
    /* Glitch Dark Mode */
    .dark .glitch::before { left: 2px; text-shadow: -2px 0 #00fff9; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .dark .glitch::after { left: -2px; text-shadow: -2px 0 #ff00c1, 2px 2px #00fff9; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    
    /* Glitch Light Mode */
    html:not(.dark) .glitch::before { left: 2px; text-shadow: -2px 0 #4f46e5; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    html:not(.dark) .glitch::after { left: -2px; text-shadow: -2px 0 #f43f5e, 2px 2px #4f46e5; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    
    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); } 80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); } 40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); } 80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); } }

    /* 🎛️ EFEK HUD (Support Light & Dark) */
    .cyber-cut-l { border-left: 4px solid #4f46e5; clip-path: polygon(0 0, 100% 0, 100% calc(100% - 25px), calc(100% - 25px) 100%, 0 100%); }
    .dark .cyber-cut-l { border-left-color: #00fff9; }
    
    .cyber-cut-r { border-right: 4px solid #f43f5e; clip-path: polygon(0 0, 100% 0, 100% 100%, 25px 100%, 0 calc(100% - 25px)); }
    
    .nav-btn { clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px); transition: all 0.3s ease; }
    .nav-btn:hover { transform: translateX(-10px); }

    /* ☢️ REACTOR CORE */
    .reactor-core { animation: floatCore 4s ease-in-out infinite; }
    @keyframes floatCore { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
    
    .pulse-ring { position: absolute; border-radius: 50%; border: 2px solid #4f46e5; animation: ring-pulse 2.5s cubic-bezier(0.16, 1, 0.3, 1) infinite; }
    .dark .pulse-ring { border-color: #00fff9; }
    @keyframes ring-pulse { 0% { transform: scale(0.8); opacity: 1; border-width: 4px; } 100% { transform: scale(2.5); opacity: 0; border-width: 0; } }

    /* 📊 ANIMASI SCREEN SWEEP */
    .screen-sweep { position: fixed; top: -100%; left: 0; width: 100%; height: 20vh; background: linear-gradient(to bottom, transparent, rgba(79, 70, 229, 0.08), transparent); z-index: 50; pointer-events: none; animation: sweep 8s infinite linear; }
    .dark .screen-sweep { background: linear-gradient(to bottom, transparent, rgba(0, 255, 249, 0.05), transparent); }
    @keyframes sweep { 0% { top: -100%; } 50% { top: 100%; } 100% { top: 100%; } }

    /* Animasi Denyut Latar Tengah */
    .breathing-glow { animation: breatheGlow 4s ease-in-out infinite alternate; }
    @keyframes breatheGlow { 0% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.6; } 100% { transform: translate(-50%, -50%) scale(1.1); opacity: 1; } }

    /* 🔔 TOAST NOTIF */
    .cyber-toast { clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px); animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
    .toast-leave { animation: slideOutRight 0.5s forwards; }
    @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }

    /* 🔥 FIX: BOOT SCREEN RESPONSIVE TEMA 🔥 */
    #system-boot { position: fixed; inset: 0; z-index: 999999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: transform 0.8s cubic-bezier(0.85, 0, 0.15, 1), background-color 0.5s; }
    html:not(.dark) #system-boot { background-color: #f8fafc; color: #4f46e5; }
    html.dark #system-boot { background-color: #020202; color: #00fff9; }
    
    .boot-done { transform: translateY(-100%); pointer-events: none; }
    .scanlines-bg { position: absolute; inset: 0; pointer-events: none; z-index: 0; background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; opacity: 0.3; }
    .dark .scanlines-bg { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.2)); }
    
    .avatar-scan { animation: avatarScan 2s infinite linear; }
    @keyframes avatarScan { 0% { top: -10%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 110%; opacity: 0; } }
</style>
@endpush

@section('content')

<div id="system-boot" class="flex flex-col items-center justify-center font-mono">
    <div class="scanlines-bg"></div>
    <div class="w-24 h-24 relative mb-8 z-10">
        <svg class="absolute inset-0 w-full h-full text-indigo-600 dark:text-[#00fff9] animate-[spin_2s_linear_infinite]" viewBox="0 0 100 100"><circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="30 15 10 20" /></svg>
        <svg class="absolute inset-0 w-full h-full text-rose-500 dark:text-indigo-500 animate-[spin_3s_linear_infinite_reverse]" viewBox="0 0 100 100"><circle cx="50" cy="50" r="30" fill="none" stroke="currentColor" stroke-width="3" stroke-dasharray="10 30" /></svg>
        <div class="absolute inset-0 m-auto w-4 h-4 bg-indigo-600 dark:bg-[#00fff9] rounded-sm rotate-45 animate-pulse shadow-[0_0_20px_currentColor]"></div>
    </div>
    <div class="text-left w-72 md:w-96 relative z-10 text-slate-800 dark:text-[#00fff9]">
        <p class="text-sm md:text-base font-bold mb-1">> BOOTING MAINFRAME...</p>
        <p class="text-indigo-500 dark:text-indigo-400 text-xs md:text-sm mb-4">> VERIFYING HACKER ID <span class="animate-pulse">_</span></p>
        <div class="w-full h-1.5 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden shadow-inner dark:shadow-[0_0_15px_rgba(0,255,249,0.3)]">
            <div id="boot-progress-bar" class="h-full bg-indigo-600 dark:bg-[#00fff9] w-0 transition-all duration-100 ease-out relative"></div>
        </div>
        <p class="text-right text-xs mt-2 font-black tracking-widest"><span id="boot-progress-text">0</span>%</p>
    </div>
</div>

<div id="toast-container" class="fixed top-24 right-4 md:right-8 z-[99999] flex flex-col gap-3 pointer-events-none"></div>
<canvas id="matrix-canvas" class="fixed inset-0 w-full h-full z-[80] pointer-events-none opacity-0 transition-opacity duration-1000"></canvas>
<div class="screen-sweep"></div>

<div class="relative w-full min-h-[calc(100vh-5rem)] bg-slate-50 dark:bg-[#030305] py-8 md:py-12 overflow-hidden transition-colors duration-500 opacity-0" id="main-dashboard-content">
    
    <div class="absolute inset-0 bg-[linear-gradient(rgba(79,70,229,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(79,70,229,0.05)_1px,transparent_1px)] dark:bg-[linear-gradient(rgba(0,255,249,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,249,0.05)_1px,transparent_1px)] z-0 pointer-events-none transition-colors duration-500" style="background-size: 40px 40px;"></div>
    <div class="scanlines-bg"></div>
    
    <div class="breathing-glow absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-500/10 dark:bg-indigo-600/10 blur-[150px] rounded-full pointer-events-none z-0 transition-colors duration-500"></div>

    <div class="max-w-[1500px] mx-auto px-4 md:px-8 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        
        <div class="lg:col-span-3 flex flex-col gap-6" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="500">
            
            <div class="bg-white/90 dark:bg-[#0a0a0c]/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 p-6 relative overflow-hidden group cyber-cut-l shadow-xl dark:shadow-[0_0_30px_rgba(0,255,249,0.1)] transition-colors duration-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 dark:bg-[#00fff9]/10 blur-2xl pointer-events-none"></div>
                
                <p class="text-[10px] text-indigo-600 dark:text-[#00fff9] font-mono font-bold tracking-widest uppercase mb-4">> OPERATOR_ID</p>
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative w-16 h-16 border-2 border-indigo-500 dark:border-[#00fff9] bg-slate-50 dark:bg-black rounded-lg flex items-center justify-center text-indigo-600 dark:text-[#00fff9] font-black text-2xl shadow-md dark:shadow-[0_0_15px_#00fff9] overflow-hidden shrink-0 transition-colors">
                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                        <div class="avatar-scan absolute left-0 w-full h-[2px] bg-indigo-500 dark:bg-[#00fff9] shadow-[0_0_10px_currentColor] z-10"></div>
                    </div>
                    <div class="overflow-hidden">
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white truncate w-full tracking-tight transition-colors">{{ Auth::user()->name ?? 'Explorer' }}</h3>
                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-indigo-100 dark:bg-[#00fff9]/10 border border-indigo-300 dark:border-[#00fff9]/30 text-xs font-bold text-indigo-600 dark:text-[#00fff9] mt-1 transition-colors">
                            <span class="w-1.5 h-1.5 bg-current animate-pulse"></span> Lv. {{ ($levelsCompleted ?? 0) + 1 }}
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <p class="text-[10px] font-mono text-slate-500 dark:text-slate-400 uppercase tracking-widest">Hacking EXP</p>
                        <p class="text-xs font-black text-indigo-600 dark:text-[#00fff9] transition-colors">{{ $totalPoints ?? '1200' }} PT</p>
                    </div>
                    <div class="h-2 w-full bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-[1px] transition-colors">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-600 dark:to-[#00fff9] shadow-[0_0_10px_currentColor] relative" style="width: 60%;"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 dark:bg-[#0a0a0c]/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 p-6 relative overflow-hidden cyber-cut-r shadow-xl transition-colors duration-500">
                <p class="text-[10px] text-rose-500 font-mono font-bold tracking-widest uppercase mb-4">> SYSTEM_LOAD</p>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-[10px] font-mono mb-1">
                            <span class="text-slate-500 dark:text-slate-400 uppercase tracking-tighter">CPU_CORE</span><span class="text-emerald-600 dark:text-emerald-400 font-bold" id="cpu-val">42%</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-900 overflow-hidden transition-colors"><div id="cpu-bar" class="h-full bg-emerald-500 stat-bar" style="width: 42%;"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] font-mono mb-1">
                            <span class="text-slate-500 dark:text-slate-400 uppercase tracking-tighter">MEMORY_HEAP</span><span class="text-amber-600 dark:text-amber-400 font-bold" id="mem-val">68%</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-900 overflow-hidden transition-colors"><div id="mem-bar" class="h-full bg-amber-500 stat-bar" style="width: 68%;"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] font-mono mb-1">
                            <span class="text-slate-500 dark:text-slate-400 uppercase tracking-tighter">SERVER_PING</span><span class="text-indigo-600 dark:text-indigo-400 font-bold" id="net-val">12 ms</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-900 overflow-hidden transition-colors"><div class="h-full bg-indigo-500 animate-pulse" style="width: 100%;"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-6 flex flex-col gap-8 items-center" data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="700">
            <div class="text-center mt-4">
                <div class="inline-flex items-center gap-2 px-4 py-1 border border-indigo-500/30 dark:border-[#00fff9]/30 bg-indigo-50 dark:bg-[#00fff9]/10 text-indigo-700 dark:text-[#00fff9] text-[10px] font-mono font-black tracking-[0.4em] uppercase mb-4 shadow-sm transition-colors">
                    <span class="w-1.5 h-1.5 bg-current animate-pulse"></span> MAINFRAME ONLINE
                </div>
                <div class="glitch-wrapper block">
                    <h1 class="text-4xl md:text-6xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch drop-shadow-md dark:drop-shadow-[0_0_20px_rgba(0,255,249,0.5)] transition-colors" data-text="COMMAND CENTER">COMMAND CENTER</h1>
                </div>
            </div>

            <div class="relative w-full aspect-square max-h-[350px] flex items-center justify-center reactor-core">
                <div class="absolute w-[280px] h-[280px] md:w-[320px] md:h-[320px] border border-indigo-500/20 dark:border-[#00fff9]/20 rounded-full animate-[spin_10s_linear_infinite] transition-colors"></div>
                <div class="absolute w-[230px] h-[230px] md:w-[260px] md:h-[260px] border-2 border-dashed border-indigo-500/40 rounded-full animate-[spin_8s_linear_infinite_reverse] transition-colors"></div>
                <div class="pulse-ring w-[140px] h-[140px] md:w-[160px] md:h-[160px]"></div>

                <a href="{{ route('adventure.index') }}" class="relative z-10 w-32 h-32 md:w-36 md:h-36 bg-white dark:bg-[#050505] border-4 border-indigo-600 dark:border-[#00fff9] rounded-full flex flex-col items-center justify-center shadow-[0_0_30px_rgba(79,70,229,0.4)] dark:shadow-[0_0_40px_rgba(0,255,249,0.6)] hover:scale-110 hover:shadow-[0_0_60px_#4f46e5] dark:hover:shadow-[0_0_80px_#00fff9] transition-all group overflow-hidden cyber-link" onmouseenter="playHover()" onclick="playClick()">
                    <div class="absolute inset-0 bg-indigo-600 dark:bg-[#00fff9] opacity-0 group-hover:opacity-10 dark:group-hover:opacity-20 transition-opacity"></div>
                    <svg class="w-10 h-10 md:w-12 md:h-12 text-indigo-600 dark:text-[#00fff9] group-hover:scale-110 transition-transform relative z-10 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-indigo-600 dark:text-[#00fff9] font-black text-[10px] tracking-widest mt-2 relative z-10 uppercase transition-colors">Execute</span>
                </a>
            </div>

            <div class="w-full bg-white/90 dark:bg-black/80 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-2xl backdrop-blur-md transition-colors duration-500">
                <div class="bg-slate-100 dark:bg-[#0a0a0c] px-4 py-2 border-b border-slate-200 dark:border-slate-800 flex items-center gap-2 transition-colors">
                    <div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div><div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div><div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                    <span class="text-slate-500 dark:text-slate-600 text-[10px] ml-2 font-mono uppercase">system_activity.log</span>
                </div>
                <div class="p-4 md:p-6 font-mono text-xs md:text-sm text-left h-[120px] md:h-[150px] overflow-y-auto" id="terminal-log-container">
                    <p class="text-indigo-600 dark:text-[#00fff9] mb-2 transition-colors">-- Monitoring mainframe connection...</p>
                    <div id="typewriter-text" class="text-slate-700 dark:text-slate-300 leading-relaxed transition-colors"></div>
                    <div class="text-indigo-600 dark:text-[#00fff9] font-bold mt-2 transition-colors"><span class="mr-2">></span><span class="w-2.5 h-5 bg-current animate-pulse inline-block align-middle"></span></div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 flex flex-col gap-4 justify-center" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="900">
            <p class="text-[10px] text-slate-500 font-mono font-bold tracking-widest uppercase mb-2 pl-4">> SYS_NAVIGATION</p>

            <a href="{{ route('materials.index') }}" class="nav-btn group block w-full bg-white dark:bg-[#0a0a0c]/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 hover:border-emerald-500 p-5 md:p-6 relative overflow-hidden cyber-link shadow-lg dark:shadow-none transition-colors duration-500" onmouseenter="playHover()" onclick="playClick()">
                <div class="absolute inset-0 bg-emerald-50 dark:bg-emerald-500/10 translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] text-emerald-600 font-mono mb-1">> ARCHIVES</p>
                        <h3 class="text-lg md:text-xl font-black text-slate-800 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors uppercase tracking-tight">Data Archives</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg border border-emerald-300 dark:border-emerald-500/50 bg-emerald-100 dark:bg-emerald-500/5 flex items-center justify-center group-hover:rotate-12 transition-all text-emerald-600 dark:text-emerald-400 shadow-sm dark:shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('quests.index') }}" class="nav-btn group block w-full bg-white dark:bg-[#0a0a0c]/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 hover:border-rose-500 p-5 md:p-6 relative overflow-hidden cyber-link shadow-lg dark:shadow-none mt-2 transition-colors duration-500" onmouseenter="playHover()" onclick="playClick()">
                <div class="absolute inset-0 bg-rose-50 dark:bg-rose-500/10 translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] text-rose-500 font-mono mb-1">> COMBAT_MODE</p>
                        <h3 class="text-lg md:text-xl font-black text-slate-800 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors uppercase tracking-tight">Arena Latihan</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg border border-rose-300 dark:border-rose-500/50 bg-rose-100 dark:bg-rose-500/5 flex items-center justify-center group-hover:-rotate-12 transition-all text-rose-600 dark:text-rose-500 shadow-sm dark:shadow-[0_0_10px_rgba(244,63,94,0.2)]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('player.profile') }}" class="nav-btn group block w-full bg-white dark:bg-[#0a0a0c]/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 hover:border-indigo-500 dark:hover:border-indigo-400 p-5 md:p-6 relative overflow-hidden cyber-link shadow-lg dark:shadow-none mt-2 transition-colors duration-500" onmouseenter="playHover()" onclick="playClick()">
                <div class="absolute inset-0 bg-indigo-50 dark:bg-indigo-500/10 translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] text-indigo-600 dark:text-indigo-400 font-mono mb-1">> SYSTEM_CONFIG</p>
                        <h3 class="text-lg md:text-xl font-black text-slate-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors uppercase tracking-tight">User Config</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg border border-indigo-300 dark:border-indigo-500/50 bg-indigo-100 dark:bg-indigo-500/5 flex items-center justify-center group-hover:rotate-12 transition-all text-indigo-600 dark:text-indigo-400 shadow-sm dark:shadow-[0_0_10px_rgba(99,102,241,0.2)]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const username = "{{ Auth::user()->name ?? 'Player' }}";

    // 🚀 SYSTEM BOOTING
    document.addEventListener("DOMContentLoaded", () => {
        let progress = 0;
        const bootText = document.getElementById('boot-progress-text');
        const bootBar = document.getElementById('boot-progress-bar');
        const bootScreen = document.getElementById('system-boot');
        const dashboardContent = document.getElementById('main-dashboard-content');
        
        document.body.style.overflow = 'hidden';
        
        // Cek Cursor
        if(!document.documentElement.classList.contains('dark')) {
            document.body.classList.add('light-mode-cursor');
        }

        const bootInterval = setInterval(() => {
            progress += Math.floor(Math.random() * 20) + 5; 
            if (progress >= 100) {
                progress = 100; clearInterval(bootInterval);
                setTimeout(() => {
                    bootScreen.classList.add('boot-done');
                    dashboardContent.classList.remove('opacity-0');
                    document.body.style.overflow = 'auto'; 
                    if(typeof playSound !== 'undefined') playSound('win');
                    setTimeout(() => { AOS.refreshHard(); typeTerminal(); startRandomToasts(); }, 300);
                }, 400); 
            }
            bootText.innerText = progress; bootBar.style.width = progress + '%';
        }, 60); 
    });

    // 📊 FAKE LIVE STATS
    setInterval(() => {
        const cpu = Math.floor(Math.random() * 40 + 40);
        const mem = Math.floor(Math.random() * 30 + 60);
        document.getElementById('cpu-bar').style.width = cpu + '%';
        document.getElementById('cpu-val').innerText = cpu + '%';
        document.getElementById('mem-bar').style.width = mem + '%';
        document.getElementById('mem-val').innerText = mem + '%';
        
        const cpuBar = document.getElementById('cpu-bar');
        const cpuVal = document.getElementById('cpu-val');
        if (cpu > 75) {
            cpuBar.classList.add('bg-rose-500'); cpuBar.classList.remove('bg-emerald-500');
            cpuVal.classList.add('text-rose-500', 'animate-pulse'); cpuVal.classList.remove('text-emerald-500', 'text-emerald-600', 'text-emerald-400');
        } else {
            cpuBar.classList.add('bg-emerald-500'); cpuBar.classList.remove('bg-rose-500');
            cpuVal.classList.add(document.documentElement.classList.contains('dark') ? 'text-emerald-400' : 'text-emerald-600');
            cpuVal.classList.remove('text-rose-500', 'animate-pulse');
        }
    }, 2000);

    // 🚀 TERMINAL TYPEWRITER
    const logMessages = [
        `[${new Date().toLocaleTimeString()}] SYSTEM: Operator ${username} terautentikasi.<br>`,
        `[${new Date().toLocaleTimeString()}] SECURITY: Handshake diverifikasi.<br>`,
        `[${new Date().toLocaleTimeString()}] MODULE: Menunggu perintah eksekusi...`
    ];
    let msgIndex = 0; let charIndex = 0;
    const typeWriterEl = document.getElementById('typewriter-text');

    function typeTerminal() {
        if (msgIndex < logMessages.length) {
            const currentMsg = logMessages[msgIndex];
            if (currentMsg.charAt(charIndex) === '<') {
                let tag = '';
                while (currentMsg.charAt(charIndex) !== '>' && charIndex < currentMsg.length) { tag += currentMsg.charAt(charIndex); charIndex++; }
                tag += '>'; typeWriterEl.innerHTML += tag; charIndex++;
            } else { typeWriterEl.innerHTML += currentMsg.charAt(charIndex); charIndex++; }
            if (charIndex < currentMsg.length) setTimeout(typeTerminal, 20); 
            else { msgIndex++; charIndex = 0; setTimeout(typeTerminal, 400); }
        }
    }

    // 🔔 HOLOGRAPHIC TOASTS
    const toastMessages = [
        { text: "INTRUSION BLOCKED: Unknown IP rejected.", type: "danger" },
        { text: "SYNC COMPLETE: Database logs updated.", type: "success" },
        { text: "WARNING: High CPU temp detected.", type: "danger" }
    ];

    function showCyberToast(msgObj) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        let colorClass = "border-indigo-500 bg-white dark:bg-[#00fff9]/10 text-indigo-600 dark:border-[#00fff9] dark:text-[#00fff9] shadow-xl";
        if(msgObj.type === 'danger') colorClass = "border-rose-500 bg-white dark:bg-rose-500/10 text-rose-600 dark:text-rose-500 shadow-xl";
        if(msgObj.type === 'success') colorClass = "border-emerald-500 bg-white dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 shadow-xl";
        toast.className = `cyber-toast p-4 border-l-4 backdrop-blur-md flex items-center gap-3 ${colorClass}`;
        toast.innerHTML = `<span class="w-2 h-2 animate-ping bg-current rounded-full"></span><p class="font-mono text-[10px] font-bold uppercase tracking-widest">${msgObj.text}</p>`;
        container.appendChild(toast);
        if(typeof playSound !== 'undefined') playSound('pop');
        setTimeout(() => { toast.classList.add('toast-leave'); setTimeout(() => toast.remove(), 500); }, 4000);
    }

    function startRandomToasts() {
        setInterval(() => { if(Math.random() > 0.6) showCyberToast(toastMessages[Math.floor(Math.random() * toastMessages.length)]); }, 8000);
    }

    // 🌧️ MATRIX RAIN (Ketik HACK)
    let konamiCode = ''; const canvas = document.getElementById('matrix-canvas'); const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth; canvas.height = window.innerHeight;
    const letters = '01'.split(''); const fontSize = 16; const columns = canvas.width / fontSize;
    const drops = []; for(let x = 0; x < columns; x++) drops[x] = 1;

    function drawMatrix() {
        ctx.fillStyle = 'rgba(0, 0, 0, 0.05)'; ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#0F0'; ctx.font = fontSize + 'px monospace';
        for(let i = 0; i < drops.length; i++) {
            const text = letters[Math.floor(Math.random() * letters.length)]; ctx.fillText(text, i * fontSize, drops[i] * fontSize);
            if(drops[i] * fontSize > canvas.height && Math.random() > 0.975) drops[i] = 0; drops[i]++;
        }
    }

    let matrixInterval;
    window.addEventListener('keydown', (e) => {
        konamiCode += e.key.toLowerCase(); if(konamiCode.length > 4) konamiCode = konamiCode.substring(1);
        if(konamiCode === 'hack') {
            canvas.classList.replace('opacity-0', 'opacity-40'); if(typeof playSound !== 'undefined') playSound('win');
            if(!matrixInterval) matrixInterval = setInterval(drawMatrix, 33);
            setTimeout(() => { canvas.classList.replace('opacity-40', 'opacity-0'); clearInterval(matrixInterval); matrixInterval = null; }, 8000);
        }
    });
</script>
@endpush