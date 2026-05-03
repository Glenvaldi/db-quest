@extends('layouts.app')

@section('title', 'DB-QUEST | Data Archives')

@push('styles')
<style>
    /* 💥 GLITCH TEKS CYBERPUNK UTAMA */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after {
        content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: transparent;
    }
    .glitch::before { left: 2px; text-shadow: -2px 0 #10b981; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .glitch::after { left: -2px; text-shadow: -2px 0 #00fff9, 2px 2px #10b981; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); } 80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); } 40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); } 80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); } }

    /* 🎛️ HUD ASYMMETRIC CARD (Emerald Theme) */
    .hud-card { transition: all 0.4s ease; backdrop-filter: blur(12px); position: relative; overflow: hidden; }
    .hud-cut-tr { border-right: 4px solid #10b981; border-top: 1px solid rgba(16,185,129,0.3); clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%); }
    .hud-cut-locked { border-top: 4px solid #64748b; border-right: 1px solid rgba(100,116,139,0.3); clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%); }

    @media (min-width: 768px) {
        .hud-cut-tr { clip-path: polygon(0 0, calc(100% - 30px) 0, 100% 30px, 100% 100%, 0 100%); }
        .hud-cut-locked { clip-path: polygon(0 0, calc(100% - 30px) 0, 100% 30px, 100% 100%, 0 100%); }
    }

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
    .card-success { color: #10b981; }

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
</style>
@endpush

@section('content')
<div class="relative w-full min-h-[calc(100vh-5rem)] bg-slate-50 dark:bg-[#050505] transition-colors duration-500 py-12 overflow-hidden">
    
    <div class="absolute inset-0 opacity-10 bg-[linear-gradient(45deg,transparent_25%,rgba(16,185,129,0.2)_50%,transparent_75%,transparent_100%)] dark:bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.1)_50%,transparent_75%,transparent_100%)] bg-[length:30px_30px] z-0 pointer-events-none"></div>
    <div class="scanlines-bg"></div>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-emerald-500/5 dark:bg-emerald-600/10 blur-[120px] rounded-full pointer-events-none z-0"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-6">
        
        <header class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 border-l-2 border-r-2 border-emerald-500 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-mono font-black tracking-[0.3em] uppercase mb-6 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                    <span class="w-2 h-2 bg-emerald-500 animate-pulse shadow-[0_0_10px_#10b981]"></span> 
                    SECURE_ARCHIVES // READ_ONLY
                </div>
                
                <div class="glitch-wrapper block mb-4">
                    <h1 class="text-4xl md:text-6xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch scramble-text drop-shadow-md" data-text="DATA ARCHIVES.">
                        DATA ARCHIVES.
                    </h1>
                </div>
                <p class="text-slate-600 dark:text-slate-400 font-mono text-sm md:text-base max-w-xl">
                    > Akses riwayat dekripsi sistem. Semua teori dan log basis data yang berhasil diretas tersimpan di sini._
                </p>
            </div>
            
            <div data-aos="fade-left" data-aos-duration="1000">
                <a href="/dashboard" class="group cyber-link flex items-center gap-3 px-6 py-3 bg-transparent border-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-black tracking-widest text-sm uppercase transition-all shadow-[0_0_20px_rgba(16,185,129,0.2)] hover:shadow-[0_0_30px_#10b981] overflow-hidden" onmouseenter="playHover()" onclick="playClick()">
                    <div class="absolute inset-0 w-0 bg-emerald-500 transition-all duration-300 ease-out group-hover:w-full z-0"></div>
                    <svg class="w-5 h-5 relative z-10 group-hover:-translate-x-1 transition-transform group-hover:text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span class="relative z-10 group-hover:text-slate-900">> RETURN_HOME</span>
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 perspective-1000">
            @foreach($levelData as $index => $level)
            
            @if($level['status'] != 'locked')
            <a href="/level/{{ $level['id'] }}/material" class="tilt-card block group cyber-link card-success h-full" data-aos="zoom-in-up" data-aos-delay="{{ ($index % 3) * 150 }}" onmouseenter="playHover()" onclick="playClick()">
                <div class="hud-card hud-cut-tr h-full bg-white/90 dark:bg-[#0f172a]/90 backdrop-blur-xl border border-slate-200 dark:border-white/5 hover:border-emerald-400 dark:hover:border-emerald-500 p-8 shadow-xl dark:shadow-[0_0_30px_rgba(16,185,129,0.1)] hover:shadow-[0_20px_50px_rgba(16,185,129,0.3)] flex flex-col justify-between">
                    
                    <div class="target-bracket bracket-tl"></div><div class="target-bracket bracket-tr"></div><div class="target-bracket bracket-bl"></div><div class="target-bracket bracket-br"></div>
                    <div class="card-glare"></div>

                    <div class="relative z-10 pop-out">
                        <div class="flex items-start justify-between mb-8">
                            <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/50 group-hover:scale-110 group-hover:-rotate-12 transition-transform shadow-inner">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            </div>
                            <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-mono text-[10px] font-black border border-emerald-200 dark:border-emerald-500/30 uppercase tracking-widest shadow-sm">> VOL_0{{ $level['order_number'] }}</span>
                        </div>
                        
                        <h3 class="text-2xl font-display font-black text-slate-800 dark:text-white mb-3 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 uppercase tracking-tight drop-shadow-md scramble-text" data-text="{{ $level['name'] }}">{{ $level['name'] }}</h3>
                        <p class="text-slate-600 dark:text-slate-400 font-mono text-xs leading-relaxed line-clamp-3 mb-8 group-hover:text-slate-300 transition-colors border-l-2 border-emerald-500/30 pl-3">
                            {{ $level['description'] ?? 'Data log telah terdekripsi. Akses dokumen untuk mempelajari algoritma sistem lebih lanjut.' }}
                        </p>
                    </div>

                    <div class="relative z-10 pop-out mt-auto">
                        <div class="w-full py-3.5 bg-emerald-600 dark:bg-transparent text-white font-black text-center text-xs tracking-widest uppercase border-2 border-emerald-500 flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(16,185,129,0.4)] group-hover:bg-emerald-500 group-hover:text-slate-900 transition-all">
                            > ACCESS_LOG
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                    </div>
                </div>
            </a>

            @else
            <div class="hud-card hud-cut-locked h-full p-8 bg-slate-100/90 dark:bg-[#050505]/90 border border-slate-300 dark:border-slate-800 backdrop-blur-xl opacity-80 flex flex-col justify-between" data-aos="zoom-in-up" data-aos-delay="{{ ($index % 3) * 150 }}">
                
                <div class="cyber-tape"></div>

                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-14 h-14 bg-slate-200 dark:bg-zinc-900 border border-slate-300 dark:border-slate-800 flex items-center justify-center text-slate-400 dark:text-zinc-600">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <span class="px-3 py-1 bg-slate-200 dark:bg-white/5 text-slate-500 dark:text-zinc-500 font-mono text-[10px] font-black border border-slate-300 dark:border-white/10 uppercase tracking-widest">> VOL_0{{ $level['order_number'] }}</span>
                    </div>
                    
                    <h3 class="text-2xl font-display font-black text-slate-500 dark:text-slate-600 mb-4 uppercase tracking-tight scramble-text" data-text="{{ $level['name'] }}">{{ $level['name'] }}</h3>
                    
                    <div class="space-y-3 mb-8">
                        <div class="h-2 w-3/4 bg-slate-300 dark:bg-zinc-800/50 rounded-full"></div>
                        <div class="h-2 w-full bg-slate-300 dark:bg-zinc-800/50 rounded-full"></div>
                        <div class="h-2 w-1/2 bg-slate-300 dark:bg-zinc-800/50 rounded-full"></div>
                    </div>
                </div>

                <div class="relative z-10 mt-auto">
                    <div class="flex items-center justify-center w-full py-3.5 bg-slate-200 dark:bg-zinc-900/80 text-slate-500 dark:text-zinc-600 font-mono font-bold text-xs tracking-widest uppercase border border-slate-300 dark:border-white/5 cursor-not-allowed">
                        > ENCRYPTED
                    </div>
                </div>
            </div>
            @endif

            @endforeach
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
            
            iteration += 1 / 2; 
            
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

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !hasDecrypted.has(entry.target)) {
                    scrambleText(entry.target);
                    hasDecrypted.add(entry.target);
                }
            });
        }, { threshold: 0.5 }); 

        scrambleElements.forEach(el => observer.observe(el));

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