@extends('layouts.app')

@section('title', 'DB-QUEST | Learning Path')

@push('styles')
<style>
    /* 💥 GLITCH TEKS CYBERPUNK UTAMA */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after {
        content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: transparent;
    }
    .glitch::before { left: 2px; text-shadow: -2px 0 #ff00c1; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .glitch::after { left: -2px; text-shadow: -2px 0 #00fff9, 2px 2px #ff00c1; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); } 80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); } 40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); } 80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); } }

    /* 💥 KARTU GLITCH HOVER (EFEK GETAR RUSAK) */
    .hover-glitch:hover { animation: cardGlitch 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94) both infinite; }
    @keyframes cardGlitch {
        0% { transform: translate(0) scale3d(1.05, 1.05, 1.05); }
        20% { transform: translate(-2px, 2px) scale3d(1.05, 1.05, 1.05); filter: hue-rotate(90deg); }
        40% { transform: translate(-2px, -2px) scale3d(1.05, 1.05, 1.05); }
        60% { transform: translate(2px, 2px) scale3d(1.05, 1.05, 1.05); filter: invert(0.2); }
        80% { transform: translate(2px, -2px) scale3d(1.05, 1.05, 1.05); }
        100% { transform: translate(0) scale3d(1.05, 1.05, 1.05); filter: none; }
    }

    /* 🎛️ HUD ASYMMETRIC CARD (Responsive Cut-out) */
    .hud-card { transition: all 0.4s ease; backdrop-filter: blur(12px); position: relative; overflow: hidden; }
    .hud-cut-active { border-left: 4px solid #00fff9; border-top: 1px solid rgba(0,255,249,0.3); clip-path: polygon(0 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%); }
    .hud-cut-completed { border-right: 4px solid #10b981; border-top: 1px solid rgba(16,185,129,0.3); clip-path: polygon(0 0, 100% 0, 100% 100%, 15px 100%, 0 calc(100% - 15px)); }
    .hud-cut-locked { border-top: 4px solid #f43f5e; border-right: 1px solid rgba(244,63,94,0.3); clip-path: polygon(15px 0, 100% 0, 100% 100%, 0 100%, 0 15px); }

    @media (min-width: 768px) {
        .hud-cut-active { clip-path: polygon(0 0, 100% 0, 100% calc(100% - 30px), calc(100% - 30px) 100%, 0 100%); }
        .hud-cut-completed { clip-path: polygon(0 0, 100% 0, 100% 100%, 30px 100%, 0 calc(100% - 30px)); }
        .hud-cut-locked { clip-path: polygon(30px 0, 100% 0, 100% 100%, 0 100%, 0 30px); }
    }

    /* 🌟 SUPER 3D TILT & GLARE EFFECT 🌟 */
    .tilt-card { transform-style: preserve-3d; transform: perspective(1500px); transition: transform 0.1s ease-out; }
    .tilt-card.resetting { transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1); }
    .pop-out { transform: translateZ(40px); transition: transform 0.3s ease; }
    .tilt-card:hover .pop-out { transform: translateZ(80px); } /* Makin loncat di hover */

    .card-glare {
        position: absolute; inset: 0; pointer-events: none; z-index: 10;
        background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.2) 0%, transparent 60%);
        opacity: 0; transition: opacity 0.3s ease; mix-blend-mode: overlay;
    }

    /* 🎯 TARGET LOCK BRACKETS 🎯 */
    .target-bracket { position: absolute; width: 25px; height: 25px; border: 2px solid transparent; transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1); opacity: 0; z-index: 5; pointer-events: none; }
    .bracket-tl { top: 10px; left: 10px; border-top-color: currentColor; border-left-color: currentColor; transform: translate(-15px, -15px); }
    .bracket-tr { top: 10px; right: 10px; border-top-color: currentColor; border-right-color: currentColor; transform: translate(15px, -15px); }
    .bracket-bl { bottom: 10px; left: 10px; border-bottom-color: currentColor; border-left-color: currentColor; transform: translate(-15px, 15px); }
    .bracket-br { bottom: 10px; right: 10px; border-bottom-color: currentColor; border-right-color: currentColor; transform: translate(15px, 15px); }

    .tilt-card:hover .target-bracket { opacity: 1; transform: translate(0, 0); }
    .card-active { color: #00fff9; }

    /* 🚨 CYBER WARNING TAPE (KARTU TERKUNCI) 🚨 */
    .cyber-tape {
        position: absolute; inset: 0; pointer-events: none; z-index: 20; opacity: 0.15;
        background: repeating-linear-gradient(45deg, #f43f5e, #f43f5e 10px, transparent 10px, transparent 20px);
        background-size: 28px 28px; animation: moveTape 1s linear infinite;
    }
    @keyframes moveTape { 0% { background-position: 0 0; } 100% { background-position: 28px 0; } }

    /* ⚡ JALUR PETA OPTIK & RADAR ⚡ */
    .path-line { position: absolute; top: 0; bottom: 0; left: 50%; width: 4px; background: rgba(99, 102, 241, 0.1); transform: translateX(-50%); z-index: 0; box-shadow: 0 0 10px rgba(99, 102, 241, 0.2); }
    .path-stream { width: 100%; height: 30%; background: linear-gradient(to bottom, transparent, #00fff9, #4f46e5, transparent); box-shadow: 0 0 30px #00fff9, 0 0 10px #4f46e5; animation: stream 2s linear infinite; }
    @keyframes stream { 0% { transform: translateY(-100vh); } 100% { transform: translateY(100vh); } }
    
    .node-point { position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; }
    .radar-ping { position: absolute; width: 300%; height: 300%; left: -100%; top: -100%; border-radius: 50%; border: 1px solid #00fff9; animation: pingRadar 2s cubic-bezier(0, 0, 0.2, 1) infinite; pointer-events: none; }
    @keyframes pingRadar { 0% { transform: scale(0.5); opacity: 1; border-width: 2px; } 100% { transform: scale(2.5); opacity: 0; border-width: 0; } }
    
    /* Barcode Dekorasi */
    .cyber-barcode { display: flex; gap: 2px; height: 12px; opacity: 0.5; }
    .bar { background-color: currentColor; }

    @media (max-width: 768px) {
        .path-line { left: 30px; width: 3px; }
        .node-point { left: -36px !important; right: auto !important; }
        .level-card { width: 100%; padding-left: 60px; margin-left: 0 !important; margin-right: 0 !important; justify-content: flex-start !important; }
    }
</style>
@endpush

@section('content')
<div class="relative w-full py-12 overflow-hidden transition-colors duration-500">
    
    <div class="absolute inset-0 opacity-10 bg-[linear-gradient(45deg,transparent_25%,rgba(99,102,241,0.2)_50%,transparent_75%,transparent_100%)] dark:bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.2)_50%,transparent_75%,transparent_100%)] bg-[length:30px_30px] z-[-1] pointer-events-none"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6">
        
        <div class="mb-24 text-center">
            <div class="inline-flex items-center gap-2 px-6 py-2 rounded-none border-l-2 border-r-2 border-indigo-600 dark:border-indigo-500 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-xs font-mono font-black tracking-[0.3em] uppercase mb-8 shadow-sm dark:shadow-[0_0_20px_rgba(99,102,241,0.3)]" data-aos="fade-down" data-aos-duration="1000">
                <span class="w-3 h-3 bg-indigo-600 dark:bg-indigo-500 animate-pulse shadow-[0_0_10px_#4f46e5]"></span> 
                SYS.MAP // TRACING CONNECTION
            </div>
            
            <div class="glitch-wrapper block mb-6" data-aos="zoom-in" data-aos-duration="1200">
                <h1 class="text-5xl md:text-7xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch scramble-text" data-text="LEARNING PATH.">
                    LEARNING PATH.
                </h1>
            </div>
            <p class="text-slate-600 dark:text-slate-400 font-mono text-base md:text-lg max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                > Selesaikan modul secara berurutan untuk menembus sekuritas basis data._
            </p>
        </div>

        <div class="relative w-full pb-20">
            <div class="path-line hidden md:block">
                <div class="path-stream"></div>
                <div class="path-stream" style="animation-delay: -1.5s; opacity: 0.5;"></div>
            </div>
            <div class="absolute top-0 bottom-0 left-[30px] w-[3px] bg-indigo-500/20 md:hidden">
                <div class="path-stream"></div>
            </div>

            @foreach($levelData as $index => $level)
                @php $isLeft = $index % 2 == 0; @endphp
                
                <div class="relative flex w-full mb-20 md:mb-32 level-card {{ $isLeft ? 'md:justify-start' : 'md:justify-end' }}" 
                     data-aos="{{ $isLeft ? 'fade-right' : 'fade-left' }}" 
                     data-aos-delay="{{ ($index % 3) * 150 }}">
                    
                    <div class="relative w-full md:w-[48%] tilt-card group {{ $level['status'] === 'unlocked' ? 'hover-glitch' : '' }}">
                        
                        <div class="node-point hidden md:flex w-10 h-10 rounded-full bg-[#050505] border-[3px] {{ $level['status'] === 'completed' ? 'border-emerald-500 shadow-[0_0_20px_#10b981]' : ($level['status'] === 'unlocked' ? 'border-[#00fff9] shadow-[0_0_30px_#00fff9] animate-pulse' : 'border-slate-700') }} items-center justify-center {{ $isLeft ? '-right-[calc(10.41%+20px)]' : '-left-[calc(10.41%+20px)]' }}">
                            @if($level['status'] === 'unlocked')
                                <div class="radar-ping"></div> @endif
                            <div class="w-4 h-4 rounded-full {{ $level['status'] === 'completed' ? 'bg-emerald-500' : ($level['status'] === 'unlocked' ? 'bg-[#00fff9] shadow-[0_0_10px_#00fff9]' : 'bg-slate-700') }}"></div>
                        </div>

                        <div class="absolute left-[-42px] top-8 w-6 h-6 rounded-full border-[3px] border-[#050505] md:hidden {{ $level['status'] === 'completed' ? 'bg-emerald-500 border-emerald-500 shadow-[0_0_10px_#10b981]' : ($level['status'] === 'unlocked' ? 'bg-[#00fff9] border-[#00fff9] shadow-[0_0_20px_#00fff9] animate-pulse' : 'bg-slate-700 border-slate-700') }}">
                            @if($level['status'] === 'unlocked') <div class="radar-ping"></div> @endif
                        </div>

                        @if($level['status'] === 'completed')
                        <div class="hud-card hud-cut-completed p-8 md:p-10 bg-white/90 dark:bg-[#0f172a]/90 backdrop-blur-xl hover:border-emerald-400 dark:hover:border-emerald-500 shadow-xl dark:shadow-[0_0_30px_rgba(16,185,129,0.15)] hover:shadow-[0_20px_50px_rgba(16,185,129,0.4)] border border-slate-200 dark:border-white/5">
                            <div class="card-glare"></div>
                            
                            <div class="flex justify-between items-start pop-out mb-4">
                                <p class="text-emerald-600 dark:text-emerald-400 font-mono font-black tracking-widest text-xs">> ACCESS_GRANTED // MODUL 0{{ $level['order_number'] }}</p>
                                <div class="cyber-barcode text-emerald-500">
                                    <div class="bar w-1"></div><div class="bar w-2"></div><div class="bar w-1"></div><div class="bar w-3"></div><div class="bar w-1"></div><div class="bar w-2"></div>
                                </div>
                            </div>
                            
                            <h3 class="text-3xl md:text-4xl font-display font-black text-slate-800 dark:text-white mb-4 leading-tight pop-out tracking-tight drop-shadow-md scramble-text" data-text="{{ $level['name'] }}">{{ $level['name'] }}</h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-8 font-mono text-sm leading-relaxed border-l-2 border-emerald-500/50 pl-4 pop-out">{{ $level['description'] }}</p>
                            
                            <div class="flex items-center justify-between pop-out">
                                <span class="px-4 py-1.5 bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-bold border border-emerald-300 dark:border-emerald-500/30 uppercase tracking-widest shadow-[0_0_10px_rgba(16,185,129,0.2)]">Decrypted</span>
                                <a href="/level/{{ $level['id'] }}/material" class="px-6 py-2.5 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-white font-bold transition-all text-sm border border-slate-300 dark:border-white/10 cyber-link btn-cyber shadow-inner" onmouseenter="playHover()" onclick="playClick()">Review Log</a>
                            </div>
                        </div>

                        @elseif($level['status'] === 'unlocked')
                        <div class="hud-card hud-cut-active p-8 md:p-10 bg-white/95 dark:bg-[#050505]/95 backdrop-blur-2xl border border-slate-200 dark:border-white/10 hover:border-[#00fff9] shadow-2xl dark:shadow-[0_0_50px_rgba(0,255,249,0.3)] hover:shadow-[0_0_80px_rgba(0,255,249,0.6)] card-primary">
                            <div class="target-bracket bracket-tl"></div><div class="target-bracket bracket-tr"></div><div class="target-bracket bracket-bl"></div><div class="target-bracket bracket-br"></div>
                            <div class="card-glare"></div>
                            
                            <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-[#00fff9] to-transparent animate-pulse shadow-[0_0_10px_#00fff9]"></div>
                            
                            <div class="flex justify-between items-start pop-out mb-4">
                                <p class="text-indigo-600 dark:text-[#00fff9] font-mono font-black tracking-widest text-xs animate-pulse">BREACH IMMINENT // MODUL 0{{ $level['order_number'] }}</p>
                                <div class="cyber-barcode text-[#00fff9] animate-pulse">
                                    <div class="bar w-2"></div><div class="bar w-1"></div><div class="bar w-3"></div><div class="bar w-1"></div><div class="bar w-2"></div><div class="bar w-1"></div>
                                </div>
                            </div>

                            <h3 class="text-3xl md:text-5xl font-display font-black text-slate-800 dark:text-white mb-4 leading-tight pop-out tracking-tighter drop-shadow-lg scramble-text text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-[#00fff9] dark:from-white dark:to-[#00fff9]" data-text="{{ $level['name'] }}">{{ $level['name'] }}</h3>
                            <p class="text-slate-600 dark:text-slate-300 mb-8 font-mono text-sm md:text-base leading-relaxed border-l-4 border-[#00fff9] pl-4 pop-out bg-slate-100/50 dark:bg-[#00fff9]/5 py-2 px-2">{{ $level['description'] }}</p>
                            
                            <div class="pop-out">
                                <a href="/level/{{ $level['id'] }}/material" class="group relative w-full py-5 bg-indigo-600 dark:bg-transparent text-white font-black text-center text-sm md:text-lg tracking-widest uppercase overflow-hidden border-2 border-indigo-400 dark:border-[#00fff9] flex items-center justify-center gap-3 cyber-link shadow-[0_0_30px_rgba(99,102,241,0.6)] dark:shadow-[0_0_30px_rgba(0,255,249,0.4)] hover:shadow-[0_0_60px_#00fff9] transition-shadow" onmouseenter="playHover()" onclick="playClick()">
                                    <div class="absolute inset-0 w-0 bg-gradient-to-r from-purple-500 dark:from-[#00fff9] to-indigo-500 transition-all duration-[400ms] ease-out group-hover:w-full opacity-60"></div>
                                    <span class="relative z-10 drop-shadow-md">> EXECUTE_BREACH</span>
                                    <svg class="w-6 h-6 relative z-10 group-hover:translate-x-3 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </a>
                            </div>
                        </div>

                        @else
                        <div class="hud-card hud-cut-locked p-8 md:p-10 bg-slate-100/90 dark:bg-[#050505]/90 border border-slate-300 dark:border-slate-800 backdrop-blur-xl opacity-80 hover:opacity-100 transition-opacity">
                            <div class="card-glare"></div>
                            <div class="cyber-tape"></div>
                            
                            <div class="flex justify-between items-start pop-out mb-4">
                                <p class="text-rose-500 dark:text-rose-600 font-mono font-black tracking-widest text-xs">> ACCESS_DENIED // MODUL 0{{ $level['order_number'] }}</p>
                                <svg class="w-6 h-6 text-rose-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-display font-bold text-slate-400 dark:text-slate-600 mb-4 pop-out tracking-tight scramble-text" data-text="{{ $level['name'] }}">{{ $level['name'] }}</h3>
                            <p class="text-slate-500 dark:text-slate-700 text-sm font-mono pop-out bg-slate-200/50 dark:bg-white/5 p-3 border-l-2 border-rose-500/50">
                                <span class="text-rose-500 font-bold">WARNING:</span> Requires security clearance from previous module to decrypt data.
                            </p>
                        </div>
                        @endif

                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 🚀 LOGIKA DEKRIPSI TEKS CYBERPUNK 🚀
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*X";
    let hasDecrypted = new Set(); // Simpan elemen yang udah didekrip

    function scrambleText(element) {
        const originalText = element.getAttribute('data-text');
        if (!originalText) return;
        
        let iteration = 0;
        const maxIterations = 15; // Berapa kali dia ngacak
        
        const interval = setInterval(() => {
            element.innerText = originalText.split("").map((letter, index) => {
                if(index < iteration) {
                    return originalText[index]; // Huruf asli mulai muncul
                }
                return chars[Math.floor(Math.random() * chars.length)]; // Sisa huruf masih ngacak
            }).join("");
            
            iteration += 1 / 2; // Kecepatan dekripsi
            
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

        // Observer buat deteksi kalau elemen masuk layar (Scroll)
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !hasDecrypted.has(entry.target)) {
                    scrambleText(entry.target);
                    hasDecrypted.add(entry.target); // Biar cuma sekali dekripsinya
                }
            });
        }, { threshold: 0.5 }); // Trigger pas 50% elemen keliatan

        scrambleElements.forEach(el => observer.observe(el));

        // Tilt logic (Sama seperti Dashboard)
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