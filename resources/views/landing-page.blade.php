@extends('layouts.app')

@section('title', 'DB-QUEST | Pengalaman Baru Belajar Basis Data')

@push('styles')
<style>
    /* 💥 EFEK GLITCH CYBERPUNK */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after {
        content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: transparent;
    }
    .glitch::before {
        left: 2px; text-shadow: -2px 0 #ff00c1; clip: rect(44px, 450px, 56px, 0);
        animation: glitch-anim 5s infinite linear alternate-reverse;
    }
    .glitch::after {
        left: -2px; text-shadow: -2px 0 #00fff9, 2px 2px #ff00c1; clip: rect(44px, 450px, 56px, 0);
        animation: glitch-anim2 5s infinite linear alternate-reverse;
    }
    @keyframes glitch-anim {
        0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); }
        40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); }
        80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); }
    }
    @keyframes glitch-anim2 {
        0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); }
        40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); }
        80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); }
    }

    /* 🏎️ MARQUEE (Teks Berjalan) */
    .marquee-container { width: 100%; overflow: hidden; white-space: nowrap; transition: background 0.5s; }
    .marquee-content { display: inline-block; animation: marquee 25s linear infinite; }
    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

    /* 🎛️ HUD ASYMMETRIC CARD (Responsive Cut-out) */
    .hud-card { transition: all 0.4s ease; }
    .hud-card:hover { transform: scale(1.02) translateY(-5px); }
    
    /* Mobile Cut (Potongan kecil) */
    .hud-left { border-left: 4px solid #4f46e5; clip-path: polygon(0 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%); }
    .hud-right { border-right: 4px solid #10b981; clip-path: polygon(0 0, 100% 0, 100% 100%, 15px 100%, 0 calc(100% - 15px)); }
    .hud-center { border-top: 4px solid #f43f5e; clip-path: polygon(20px 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%, 0 20px); }
    
    /* Desktop Cut (Potongan ekstrem) */
    @media (min-width: 768px) {
        .hud-left { clip-path: polygon(0 0, 100% 0, 100% calc(100% - 30px), calc(100% - 30px) 100%, 0 100%); }
        .hud-right { clip-path: polygon(0 0, 100% 0, 100% 100%, 30px 100%, 0 calc(100% - 30px)); }
        .hud-center { clip-path: polygon(30px 0, calc(100% - 30px) 0, 100% 30px, 100% 100%, 0 100%, 0 30px); }
    }

    .clickable { pointer-events: auto; }
    .content-layer { position: relative; z-index: 20; pointer-events: none; }
</style>
@endpush

@section('content')
<div id="tsparticles" class="fixed inset-0 z-0 pointer-events-auto mix-blend-screen opacity-70"></div>

<div class="relative w-full min-h-screen flex flex-col items-center justify-center pt-24 pb-12 px-4 sm:px-6 overflow-hidden">
    
    <div class="hidden md:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border-[0.5px] border-indigo-300 dark:border-indigo-500/20 rounded-full animate-[spin_10s_linear_infinite] pointer-events-none"></div>
    <div class="hidden md:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] border-[0.5px] border-emerald-300 dark:border-emerald-500/10 rounded-full animate-[spin_15s_linear_infinite_reverse] pointer-events-none border-dashed"></div>

    <div class="content-layer max-w-5xl mx-auto w-full flex flex-col items-center text-center">
        
        <div class="inline-flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-1.5 sm:py-2 rounded-none border-l-2 border-r-2 border-indigo-600 dark:border-indigo-500 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-[0.65rem] sm:text-xs font-mono font-black tracking-[0.2em] sm:tracking-[0.3em] uppercase mb-8 sm:mb-10 shadow-sm dark:shadow-[0_0_20px_rgba(99,102,241,0.3)]" data-aos="fade-down" data-aos-duration="1000">
            <span class="w-2 h-2 sm:w-3 sm:h-3 bg-indigo-600 dark:bg-indigo-500 animate-pulse shadow-[0_0_10px_#4f46e5]"></span> 
            SYS.INIT // MISSION READY
        </div>
        
        <div class="glitch-wrapper mb-4 sm:mb-6" data-aos="zoom-in" data-aos-duration="1200">
            <h1 class="text-5xl sm:text-6xl md:text-8xl font-display font-black text-slate-900 dark:text-white tracking-tighter leading-none glitch" data-text="HACK THE DB.">
                HACK THE DB.
            </h1>
        </div>
        
        <h2 class="text-2xl sm:text-3xl md:text-5xl font-display font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-emerald-600 to-indigo-600 dark:from-indigo-400 dark:via-emerald-400 dark:to-indigo-400 mb-6 sm:mb-8 tracking-tight px-2" data-aos="fade-up" data-aos-duration="1400" data-aos-delay="200">
            Praktik Langsung, Bukan Cuma Teori.
        </h2>
        
        <p class="text-sm sm:text-lg md:text-xl text-slate-600 dark:text-slate-400 font-mono mb-10 sm:mb-12 max-w-3xl mx-auto leading-relaxed border-l-4 border-slate-300 dark:border-slate-700 pl-4 sm:pl-6 text-left" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="400">
            > DB-QUEST menghadirkan simulasi nyata.<br>
            > Sambungkan ERD. Basmi Anomali Tabel.<br>
            <span class="text-indigo-600 dark:text-indigo-400 animate-pulse">> Menunggu otorisasi user..._</span>
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6 clickable w-full px-4 sm:px-0" data-aos="zoom-out-up" data-aos-duration="1000" data-aos-delay="600">
            <a href="/login" class="group relative w-full sm:w-auto px-6 sm:px-8 py-4 bg-indigo-600 text-white font-black text-base sm:text-xl tracking-widest uppercase transition-all shadow-[0_0_20px_rgba(99,102,241,0.5)] hover:shadow-[0_0_40px_#4f46e5] dark:hover:shadow-[0_0_40px_#00fff9] overflow-hidden rounded-none border border-indigo-400 text-center flex justify-center items-center" onmouseenter="playHover()" onclick="playClick()">
                <div class="absolute inset-0 w-0 bg-gradient-to-r from-purple-500 dark:from-[#00fff9] to-indigo-500 transition-all duration-[500ms] ease-out group-hover:w-full opacity-50"></div>
                <span class="relative z-10 flex items-center justify-center gap-2 sm:gap-3">
                    [ INITIALIZE ]
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </span>
            </a>
            
            <a href="#arsenal" class="w-full sm:w-auto px-6 sm:px-8 py-4 bg-white/50 dark:bg-transparent backdrop-blur-md text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-white font-bold text-base sm:text-lg tracking-widest uppercase border border-slate-300 dark:border-slate-600 hover:border-indigo-500 dark:hover:border-emerald-400 transition-colors text-center flex justify-center items-center gap-3" onmouseenter="playHover()" onclick="playClick()">
                LIHAT ARSENAL ↓
            </a>
        </div>
    </div>
</div>

<div class="marquee-container py-2 sm:py-3 relative z-20 shadow-[0_0_20px_rgba(99,102,241,0.1)] bg-white/80 dark:bg-[#050505]/80 border-t border-b border-indigo-200 dark:border-indigo-500/30">
    <div class="marquee-content text-indigo-700 dark:text-indigo-400 font-mono text-xs sm:text-sm font-bold tracking-[0.2em] uppercase">
        <span class="mx-4 sm:mx-8">🔥 CONNECTION SECURED</span>
        <span class="mx-4 sm:mx-8 text-emerald-600 dark:text-emerald-400">///</span>
        <span class="mx-4 sm:mx-8">5+ MODUL MISI TERSEDIA</span>
        <span class="mx-4 sm:mx-8 text-emerald-600 dark:text-emerald-400">///</span>
        <span class="mx-4 sm:mx-8">AI MENTOR LLaMA-3 AKTIF</span>
        <span class="mx-4 sm:mx-8 text-emerald-600 dark:text-emerald-400">///</span>
        <span class="mx-4 sm:mx-8">TERMINAL SQL STANDBY</span>
        <span class="mx-4 sm:mx-8 text-emerald-600 dark:text-emerald-400">///</span>
        <span class="mx-4 sm:mx-8">🔥 CONNECTION SECURED</span>
        <span class="mx-4 sm:mx-8 text-emerald-600 dark:text-emerald-400">///</span>
        <span class="mx-4 sm:mx-8">5+ MODUL MISI TERSEDIA</span>
        <span class="mx-4 sm:mx-8 text-emerald-600 dark:text-emerald-400">///</span>
        <span class="mx-4 sm:mx-8">AI MENTOR LLaMA-3 AKTIF</span>
        <span class="mx-4 sm:mx-8 text-emerald-600 dark:text-emerald-400">///</span>
        <span class="mx-4 sm:mx-8">TERMINAL SQL STANDBY</span>
        <span class="mx-4 sm:mx-8 text-emerald-600 dark:text-emerald-400">///</span>
    </div>
</div>

<div id="arsenal" class="relative z-10 w-full py-20 sm:py-32 bg-slate-50 dark:bg-[#050505] overflow-hidden transition-colors duration-500">
    <div class="absolute inset-0 opacity-10 bg-[linear-gradient(45deg,transparent_25%,rgba(99,102,241,0.2)_50%,transparent_75%,transparent_100%)] dark:bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.2)_50%,transparent_75%,transparent_100%)] bg-[length:20px_20px]"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 clickable relative z-10">
        
        <div class="mb-16 sm:mb-24 border-l-4 border-indigo-500 pl-4 sm:pl-6" data-aos="fade-right" data-aos-duration="1000">
            <h2 class="text-4xl sm:text-5xl font-display font-black text-slate-900 dark:text-white mb-2 uppercase tracking-tighter leading-tight">Sistem <br class="sm:hidden"><span class="text-indigo-600 dark:text-indigo-500">Persenjataan.</span></h2>
            <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-lg font-mono mt-2">Modul dirancang menembus batas teori tradisional.</p>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-8 md:gap-10 mb-16 sm:mb-24" data-aos="fade-up-right" data-aos-duration="1200">
            <div class="w-full md:w-1/2">
                <div class="hud-card hud-left p-6 sm:p-10 relative group bg-white dark:bg-[#0f172a] shadow-xl dark:shadow-none hover:border-indigo-400">
                    <div class="absolute -top-4 -left-4 sm:-top-6 sm:-left-6 text-5xl sm:text-6xl font-black text-slate-200 dark:text-slate-800 opacity-50 group-hover:text-indigo-200 dark:group-hover:text-indigo-900 transition-colors">01</div>
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 sm:mb-6 border border-indigo-300 dark:border-indigo-500/50">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" /></svg>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mb-3 sm:mb-4 uppercase tracking-wider leading-tight">Simulator <span class="text-indigo-600 dark:text-indigo-400">Visual 3D</span></h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-mono text-xs sm:text-sm border-l-2 border-slate-300 dark:border-slate-700 pl-3 sm:pl-4">Praktekkan logika Database secara langsung. Tarik kabel ERD, basmi anomali redundansi, dan visualisasikan data abstrak menjadi arena tempur nyata.</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 text-right hidden md:block" data-aos="fade-left" data-aos-delay="300">
                <div class="inline-block p-4 border border-indigo-200 dark:border-indigo-500/30 bg-indigo-50 dark:bg-indigo-500/5 rounded-full animate-pulse shadow-lg dark:shadow-none">
                    <img src="https://api.iconify.design/mdi:database-search.svg?color=%234f46e5" alt="Icon" class="w-32 h-32 opacity-70">
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row-reverse items-center gap-8 md:gap-10 mb-16 sm:mb-24" data-aos="fade-up-left" data-aos-duration="1200">
            <div class="w-full md:w-1/2">
                <div class="hud-card hud-right p-6 sm:p-10 relative group bg-white dark:bg-[#0f172a] shadow-xl dark:shadow-none hover:border-emerald-400">
                    <div class="absolute -top-4 -right-4 sm:-top-6 sm:-right-6 text-5xl sm:text-6xl font-black text-slate-200 dark:text-slate-800 opacity-50 group-hover:text-emerald-200 dark:group-hover:text-emerald-900 transition-colors">02</div>
                    
                    <div class="flex justify-end mb-4 sm:mb-6">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-300 dark:border-emerald-500/50">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mb-3 sm:mb-4 uppercase tracking-wider text-right leading-tight">Tutor AI <br class="sm:hidden"><span class="text-emerald-600 dark:text-emerald-400">LLaMA-3.1</span></h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-mono text-xs sm:text-sm border-r-2 border-slate-300 dark:border-slate-700 pr-3 sm:pr-4 text-right">Gagal misi? Sistem kami terintegrasi kecerdasan buatan. AI Evaluator siap menganalisis kesalahanmu dan memberi petunjuk logis.</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 text-left hidden md:block" data-aos="fade-right" data-aos-delay="300">
                 <div class="inline-block p-4 border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/5 rounded-full animate-pulse shadow-lg dark:shadow-none">
                    <img src="https://api.iconify.design/mdi:robot-outline.svg?color=%2310b981" alt="Icon" class="w-32 h-32 opacity-70">
                </div>
            </div>
        </div>

        <div class="w-full mt-10" data-aos="flip-up" data-aos-duration="1500">
            <div class="hud-card hud-center p-8 sm:p-12 text-center bg-white dark:bg-[#0f172a] shadow-xl dark:shadow-none hover:border-rose-400">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-500 flex items-center justify-center mb-4 sm:mb-6 border border-rose-300 dark:border-rose-500/50 rounded-full animate-bounce">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <h3 class="text-2xl sm:text-4xl font-black text-slate-800 dark:text-white mb-3 sm:mb-4 uppercase tracking-widest leading-tight">Real-World <span class="text-rose-600 dark:text-rose-500">Terminal</span></h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-mono text-xs sm:text-base max-w-2xl mx-auto px-2">Tinggalkan UI yang membosankan. Eksekusi query SQL langsung dari terminal hologram interaktif. Latih mentalmu menghadapi server sungguhan.</p>
            </div>
        </div>

    </div>
</div>

<div class="relative z-10 w-full py-20 sm:py-32 bg-slate-100 dark:bg-[#020202] border-t border-slate-200 dark:border-slate-800 transition-colors duration-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 clickable" data-aos="zoom-in-down" data-aos-duration="1500">
        
        <div class="w-full bg-black border-2 border-indigo-500/50 rounded-xl overflow-hidden shadow-[0_15px_30px_rgba(79,70,229,0.3)] sm:shadow-[0_20px_50px_rgba(79,70,229,0.3)]">
            <div class="bg-zinc-900 px-3 sm:px-4 py-2 border-b border-indigo-500/30 flex items-center gap-2">
                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-rose-500"></div>
                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-amber-500"></div>
                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-emerald-500"></div>
                <span class="text-indigo-400 text-[0.65rem] sm:text-xs ml-2 sm:ml-4 font-mono">root@db-quest:~/auth</span>
            </div>
            
            <div class="p-6 sm:p-12 font-mono text-left">
                <p class="text-emerald-400 text-sm sm:text-lg mb-1 sm:mb-2">> System status: <span class="text-white">ONLINE</span></p>
                <p class="text-emerald-400 text-sm sm:text-lg mb-4 sm:mb-6">> Checking modules... <span class="text-white">[OK] 5 Modules</span></p>
                <p class="text-emerald-400 text-sm sm:text-lg mb-6">> Requesting payload...</p>
                
                <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white mb-6 sm:mb-8 tracking-tighter uppercase leading-tight">
                    Siap meretas <br class="hidden sm:block"><span class="text-indigo-500">sistem?</span>
                </h2>
                
                <a href="/login" class="block sm:inline-block group relative w-full sm:w-auto px-6 py-4 sm:px-10 sm:py-5 bg-transparent border-2 border-indigo-500 text-indigo-400 font-black text-sm sm:text-xl hover:bg-indigo-500 hover:text-white transition-all overflow-hidden text-center" onmouseenter="playHover()" onclick="playClick()">
                    <span class="relative z-10">> EXECUTE_LOGIN.sh</span>
                    <div class="absolute inset-0 w-0 bg-indigo-500 transition-all duration-300 ease-out group-hover:w-full z-0"></div>
                </a>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>
<script>
    // Jaring Partikel - Jumlah dikurangi dikit biar enteng di HP
    tsParticles.load("tsparticles", {
        fpsLimit: 60, // Ubah ke 60fps untuk stabilitas di HP
        particles: {
            number: { value: 45, density: { enable: true, value_area: 800 } },
            color: { value: "#4f46e5" },
            shape: { type: "triangle" },
            opacity: { value: 0.6, random: true },
            size: { value: 3, random: true },
            links: { enable: true, distance: 160, color: "#6366f1", opacity: 0.4, width: 1.5 },
            move: { enable: true, speed: 1.2, direction: "none", outModes: { default: "bounce" } }
        },
        interactivity: {
            detectsOn: "canvas",
            events: { onHover: { enable: true, mode: "grab" }, onClick: { enable: true, mode: "push" }, resize: true },
            modes: { grab: { distance: 250, links: { opacity: 1, color: "#00fff9" } } }
        },
        detectRetina: true
    });
</script>
@endpush