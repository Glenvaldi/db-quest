@extends('layouts.app')

@section('title', 'DB-QUEST | ' . ($materi?->title ?? 'Materi'))

@push('styles')
<style>
    /* 💥 EFEK GLITCH CYBERPUNK */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after {
        content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: transparent;
    }
    .glitch::before { left: 2px; text-shadow: -1px 0 #ff00c1; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .glitch::after { left: -2px; text-shadow: -1px 0 #00fff9, 1px 1px #ff00c1; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); } 80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); } 40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); } 80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); } }

    /* 🎛️ HUD ASYMMETRIC CARD */
    .hud-card { transition: all 0.4s ease; backdrop-filter: blur(12px); position: relative; }
    .hud-cut-bl { border-left: 4px solid #4f46e5; border-bottom: 1px solid rgba(79,70,229,0.3); clip-path: polygon(0 0, 100% 0, 100% 100%, 20px 100%, 0 calc(100% - 20px)); }
    .hud-cut-tr { border-right: 4px solid #10b981; border-top: 1px solid rgba(16,185,129,0.3); clip-path: polygon(0 20px, 20px 0, 100% 0, 100% 100%, 0 100%); }

    @media (min-width: 768px) {
        .hud-cut-bl { clip-path: polygon(0 0, 100% 0, 100% 100%, 40px 100%, 0 calc(100% - 40px)); }
        .hud-cut-tr { clip-path: polygon(0 30px, 30px 0, 100% 0, 100% 100%, 0 100%); }
    }

    /* 🎯 TARGET BRACKETS */
    .target-bracket { position: absolute; width: 20px; height: 20px; border: 2px solid transparent; opacity: 1; z-index: 5; pointer-events: none; }
    .bracket-tl { top: 10px; left: 10px; border-top-color: currentColor; border-left-color: currentColor; }
    .bracket-tr { top: 10px; right: 10px; border-top-color: currentColor; border-right-color: currentColor; }
    .bracket-bl { bottom: 10px; left: 10px; border-bottom-color: currentColor; border-left-color: currentColor; }
    .bracket-br { bottom: 10px; right: 10px; border-bottom-color: currentColor; border-right-color: currentColor; }

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 10px; }

    /* Style Konten Materi */
    .materi-content h2 { 
        font-size: 1.5rem; font-weight: 900; text-transform: uppercase;
        margin-top: 2.5rem; margin-bottom: 1rem; 
        border-left: 4px solid #00fff9; padding-left: 1rem; letter-spacing: 0.05em;
        color: #1e293b;
    }
    .materi-content p { line-height: 1.8; margin-bottom: 1.5rem; font-weight: 500; color: #475569; }
    .dark .materi-content h2 { color: #f8fafc; }
    .dark .materi-content p { color: #cbd5e1; }

    .materi-content pre { 
        background: #050505 !important; padding: 1.5rem; border-radius: 0.5rem; 
        border: 1px solid rgba(99,102,241,0.3); border-left: 4px solid #4f46e5;
        margin-bottom: 2rem; overflow-x: auto; box-shadow: inset 0 0 20px rgba(99,102,241,0.1);
        color: #00fff9 !important; font-family: 'Courier New', Courier, monospace;
    }
</style>
@endpush

@section('content')
<div class="relative w-full min-h-screen transition-colors duration-500 pb-20">
    
    <div class="absolute inset-0 opacity-10 bg-[linear-gradient(45deg,transparent_25%,rgba(99,102,241,0.2)_50%,transparent_75%,transparent_100%)] dark:bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.1)_50%,transparent_75%,transparent_100%)] bg-[length:30px_30px] z-[-1] pointer-events-none"></div>

    @if($materi?->youtube_url)
        @php
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $materi->youtube_url, $matches);
            $youtubeId = $matches[1] ?? null;
        @endphp

        @if($youtubeId)
        <div class="relative w-full bg-slate-100 dark:bg-[#050505] transition-colors duration-500 flex justify-center border-b-2 border-indigo-500 shadow-[0_20px_50px_rgba(99,102,241,0.2)] py-4" data-aos="fade-down" data-aos-duration="1000">
            
            <div class="absolute top-4 left-4 z-20 hidden md:flex items-center gap-2 px-3 py-1 bg-white/80 dark:bg-black/60 border border-indigo-500/50 backdrop-blur-md transition-colors duration-500">
                <div class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></div>
                <span class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 tracking-widest uppercase">REC_LIVE // {{ $level->name }}</span>
            </div>

            <div class="w-full max-w-4xl aspect-video relative z-0 mx-4 shadow-2xl">
                <!-- 🔥 PERBAIKAN YOUTUBE: playsinline=1 agar video tidak loncat ke aplikasi di HP 🔥 -->
                <iframe 
                    src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1&playsinline=1" 
                    class="w-full h-full border border-indigo-500/30"
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>
        @endif
    @endif

    <div class="max-w-5xl mx-auto px-4 md:px-8 mt-12 relative z-10">
        <div class="space-y-12">
            
            <!-- HEADER MATERI & BUTTONS -->
            <div class="pb-2">
                <div class="flex items-center gap-3 mb-6" data-aos="fade-right">
                    <a href="/start-adventure" class="group cyber-link flex items-center gap-2 px-4 py-2 bg-transparent border border-indigo-500 text-indigo-600 dark:text-indigo-400 font-bold text-xs uppercase tracking-widest transition-all hover:shadow-[0_0_15px_#4f46e5] overflow-hidden" onmouseenter="playHover()" onclick="playClick()">
                        <div class="absolute inset-0 w-0 bg-indigo-500 transition-all duration-300 ease-out group-hover:w-full z-0"></div>
                        <svg class="w-4 h-4 relative z-10 group-hover:-translate-x-1 transition-transform group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        <span class="relative z-10 group-hover:text-white">> BACK_TRACE</span>
                    </a>
                    <span class="text-indigo-500 font-mono text-[10px] font-black uppercase tracking-widest hidden sm:inline-block">
                        // {{ $level->name }}
                    </span>
                </div>
                
                <div class="glitch-wrapper block mb-6" data-aos="fade-up" data-aos-delay="100">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-black leading-tight text-slate-900 dark:text-white uppercase tracking-tighter scramble-text drop-shadow-md" data-text="{{ $materi?->title ?? 'DECRYPTING_DATA...' }}">
                        {{ $materi?->title ?? 'DECRYPTING_DATA...' }}
                    </h1>
                </div>
                
                <div class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-white/80 dark:bg-[#0a0a0c]/80 backdrop-blur-xl border border-slate-200 dark:border-indigo-500/20 gap-4 mt-8 transition-colors duration-500" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-4">
                        <div class="relative w-12 h-12 flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-indigo-500 animate-[spin_4s_linear_infinite]" viewBox="0 0 100 100"><circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="3" stroke-dasharray="20 10 5 15" /></svg>
                            <div class="w-8 h-8 rounded-sm bg-indigo-600 flex items-center justify-center font-black text-white text-xs z-10">DB</div>
                        </div>
                        <div>
                            <p class="text-[10px] font-mono text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-widest mb-0.5">SYS.AUTHOR</p>
                            <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase transition-colors">{{ $materi?->author ?? 'Instruktur DB-Quest' }}</h4>
                            <p class="text-[11px] text-slate-500 dark:text-zinc-500 font-mono mt-0.5 transition-colors">SMK Negeri 1 // SECURE_LINE</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <a href="/level/{{ $level->id }}/pretest" class="group relative px-6 py-3 bg-transparent border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs uppercase tracking-widest overflow-hidden cyber-link hover:border-indigo-500 hover:text-white transition-colors text-center" onmouseenter="playHover()" onclick="playClick()">
                            <div class="absolute inset-0 w-0 bg-indigo-500 transition-all duration-300 ease-out group-hover:w-full z-0"></div>
                            <span class="relative z-10">> REVIEW_PRETEST</span>
                        </a>
                        
                        <a href="{{ route('quest.simulator.show', $level->id) }}" class="group relative px-6 py-3 bg-indigo-600 text-white font-black text-xs uppercase tracking-widest overflow-hidden border border-indigo-400 flex justify-center items-center gap-2 cyber-link shadow-[0_0_20px_rgba(99,102,241,0.4)] hover:shadow-[0_0_30px_#00fff9] transition-shadow w-full md:w-auto" onmouseenter="playHover()" onclick="playClick()">
                            <div class="absolute inset-0 w-0 bg-gradient-to-r from-[#00fff9] to-indigo-500 transition-all duration-[400ms] ease-out group-hover:w-full opacity-50 z-0"></div>
                            <span class="relative z-10">> EXECUTE_QUEST</span>
                            <svg class="w-4 h-4 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- KONTEN MATERI (TEKS) -->
            <div class="hud-card hud-cut-bl bg-white/95 dark:bg-[#0a0a0c]/95 border border-slate-200 dark:border-white/5 p-6 md:p-10 shadow-2xl relative transition-colors duration-500" data-aos="fade-up" data-aos-delay="300">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 dark:bg-indigo-500/10 blur-3xl rounded-full pointer-events-none"></div>
                <div class="absolute top-10 right-10 opacity-[0.03] dark:opacity-[0.02] pointer-events-none transform rotate-12">
                    <span class="text-9xl font-black font-display uppercase tracking-tighter">DATA</span>
                </div>

                <div class="materi-content relative z-10">
                    {!! $materi?->content_html ?? $materi?->content ?? '<p class="font-mono text-indigo-500 animate-pulse">> AWAITING_DATA_STREAM...</p>' !!}
                </div>
            </div>

            <!-- 🔥 PERBAIKAN PDF KEMBALI KE IFRAME UNTUK HP DAN DESKTOP 🔥 -->
            <div data-aos="fade-up" data-aos-delay="400">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-3 h-3 bg-emerald-500 animate-pulse"></span>
                    <h3 class="text-lg font-black uppercase tracking-[0.2em] text-slate-800 dark:text-white transition-colors">
                        ENCRYPTED_FILES <span class="text-emerald-500">[{{ count($materi?->pdf_file ?? []) }}]</span>
                    </h3>
                </div>

                <div class="grid grid-cols-1 gap-8">
                    @if(!empty($materi?->pdf_file))
                        @foreach($materi->pdf_file as $index => $pdf)
                        <div class="group relative bg-white dark:bg-[#050505] border border-slate-300 dark:border-slate-800 p-2 hover:border-emerald-400 dark:hover:border-emerald-500 transition-colors duration-300 shadow-xl">
                            
                            <div class="target-bracket bracket-tl text-emerald-500"></div>
                            <div class="target-bracket bracket-tr text-emerald-500"></div>
                            <div class="target-bracket bracket-bl text-emerald-500"></div>
                            <div class="target-bracket bracket-br text-emerald-500"></div>

                            <div class="bg-slate-100 dark:bg-zinc-900 px-4 py-3 border-b border-slate-200 dark:border-white/5 flex flex-row items-center justify-between gap-3 relative z-10 transition-colors">
                                <span class="text-[10px] sm:text-sm font-mono font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest truncate">
                                    > DOC_0{{ $index + 1 }}.pdf
                                </span>
                                <!-- Tombol ini kini selalu muncul di HP maupun Desktop -->
                                <a href="{{ asset('storage/' . $pdf) }}" target="_blank" class="flex px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/50 text-[10px] font-mono font-bold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500 hover:text-white transition-all items-center justify-center gap-2 whitespace-nowrap" onmouseenter="playHover()">
                                    <span>[ EXTRACT ]</span> <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                </a>
                            </div>
                            
                            <!-- 🔥 Kotak khusus anti "Mleber" untuk Browser HP 🔥 -->
                            <div class="relative w-full h-[350px] md:h-[500px] overflow-hidden bg-slate-200 dark:bg-[#0a0a0c] z-10">
                                <!-- Wrapper overflow khusus untuk iOS Safari & Chrome Mobile -->
                                <div class="absolute inset-0 overflow-y-auto custom-scrollbar" style="-webkit-overflow-scrolling: touch;">
                                     <iframe src="{{ asset('storage/' . $pdf) }}#toolbar=0&view=FitH" class="w-full h-[350px] md:h-[500px] border-0" type="application/pdf"></iframe>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    @else
                        <div class="p-12 border-2 border-dashed border-slate-300 dark:border-slate-800 bg-slate-100 dark:bg-[#050505] text-center transition-colors">
                            <svg class="w-12 h-12 mx-auto text-slate-400 dark:text-slate-600 mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <p class="text-sm font-mono text-slate-500 dark:text-slate-500">> SYSTEM LOG: NO_FILES_ATTACHED</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

    document.addEventListener("DOMContentLoaded", () => {
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
    });
</script>
@endpush