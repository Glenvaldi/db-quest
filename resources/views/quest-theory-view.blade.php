@extends('layouts.app')

@section('title', 'DB-QUEST | Ujian Teori')

@push('styles')
<style>
    /* 🎯 CUSTOM CYBER CURSOR */
    body { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%2300fff9" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>') 12 12, auto; }
    a, button, .cyber-link, label { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23f43f5e" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 0v5M12 19v5M0 12h5M19 12h5"/></svg>') 12 12, pointer !important; }

    /* 💥 GLITCH TEKS */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after { content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: transparent; }
    .dark .glitch::before { left: 2px; text-shadow: -2px 0 #00fff9; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .dark .glitch::after { left: -2px; text-shadow: -2px 0 #ff00c1, 2px 2px #00fff9; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); } 80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); } 40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); } 80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); } }

    /* 🎛️ EFEK HUD CARD */
    .hud-card { transition: all 0.4s ease; backdrop-filter: blur(12px); position: relative; overflow: hidden; }
    .hud-cut-tl { border-left: 4px solid #4f46e5; border-top: 1px solid rgba(79,70,229,0.3); clip-path: polygon(25px 0, 100% 0, 100% 100%, 0 100%, 0 25px); }
    .dark .hud-cut-tl { border-left-color: #00fff9; border-top-color: rgba(0,255,249,0.3); }

    /* 🎯 TARGET LOCK BRACKETS */
    .target-bracket { position: absolute; width: 15px; height: 15px; border: 2px solid transparent; opacity: 1; z-index: 5; pointer-events: none; }
    .bracket-tr { top: 10px; right: 10px; border-top-color: currentColor; border-right-color: currentColor; }
    .bracket-bl { bottom: 10px; left: 10px; border-bottom-color: currentColor; border-left-color: currentColor; }

    /* Scanlines */
    .scanlines-bg { position: absolute; inset: 0; pointer-events: none; z-index: 0; background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; opacity: 0.3; }

    /* 🔔 TOAST NOTIF */
    .cyber-toast { clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px); animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; z-index: 99999; }
    .toast-leave { animation: slideOutRight 0.5s forwards; }
    @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
</style>
@endpush

@section('content')

<div id="toast-container" class="fixed top-24 right-4 md:right-8 z-[99999] flex flex-col gap-3 pointer-events-none"></div>

<div class="relative w-full min-h-[calc(100vh-5rem)] bg-slate-50 dark:bg-[#030305] text-slate-800 dark:text-slate-200 py-12 overflow-hidden transition-colors duration-500">
    
    <div class="scanlines-bg"></div>
    <div class="absolute inset-0 bg-[linear-gradient(rgba(79,70,229,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(79,70,229,0.05)_1px,transparent_1px)] dark:bg-[linear-gradient(rgba(0,255,249,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,249,0.05)_1px,transparent_1px)] z-0 pointer-events-none" style="background-size: 30px 30px;"></div>
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-indigo-600/5 dark:bg-indigo-600/10 blur-[150px] rounded-full pointer-events-none z-0"></div>

    <div class="max-w-5xl mx-auto px-6 mb-12 relative z-10 flex flex-col md:flex-row items-center justify-between gap-4">
        <a href="/level/{{ $level_id }}/material" class="group cyber-link flex items-center gap-2 px-6 py-2.5 rounded-none bg-slate-200/50 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-700 dark:text-zinc-300 font-mono font-bold text-xs tracking-widest uppercase hover:bg-rose-500 hover:border-rose-500 hover:text-white transition-all shadow-sm" onmouseenter="playHover()" onclick="playClick()">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            ABORT_MISSION
        </a>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        
        <div class="text-center mb-16" data-aos="fade-down">
            <div class="inline-flex items-center gap-2 px-6 py-2 rounded-none border-l-2 border-r-2 border-emerald-500 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-mono font-black tracking-[0.3em] uppercase mb-6 shadow-sm">
                <span class="w-2 h-2 bg-current animate-pulse shadow-[0_0_10px_currentColor]"></span> 
                PHASE_02 : COGNITIVE_TEST
            </div>
            
            <div class="glitch-wrapper block mb-4">
                <h1 class="text-3xl md:text-5xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch drop-shadow-md" data-text="{{ $quiz->title }}">
                    {{ $quiz->title }}
                </h1>
            </div>
            <p class="text-slate-600 dark:text-slate-400 font-mono text-sm">
                > Analisis sistem dan pilih sintaks/jawaban yang paling tepat._
            </p>
        </div>

        <form id="theory-form" action="{{ route('quest.theory.submit', $quiz->id) }}" method="POST" class="space-y-8" novalidate>
            @csrf
            
            @if($quiz->questions->isEmpty())
                <div class="hud-card border-2 border-dashed border-rose-500/50 p-10 text-center bg-rose-500/5 backdrop-blur-md">
                    <p class="text-rose-500 font-mono font-bold tracking-widest uppercase">> WARNING: SOAL UJIAN KOGNITIF BELUM TERSEDIA DI MAINFRAME.</p>
                </div>
            @else
                @foreach($quiz->questions as $index => $q)
                    @php $content = is_array($q->content) ? $q->content : json_decode($q->content, true); @endphp
                    
                    <div class="hud-card hud-cut-tl bg-white/90 dark:bg-[#0a0a0c]/90 backdrop-blur-xl border border-slate-200 dark:border-slate-800 p-8 shadow-xl dark:shadow-[0_0_30px_rgba(0,255,249,0.05)] group hover:border-indigo-400 dark:hover:border-[#00fff9]/50 transition-colors" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                        
                        <div class="target-bracket bracket-tr text-indigo-400 dark:text-[#00fff9]/50"></div>
                        <div class="target-bracket bracket-bl text-indigo-400 dark:text-[#00fff9]/50"></div>

                        <div class="flex gap-4 mb-6">
                            <div class="w-10 h-10 shrink-0 bg-indigo-100 dark:bg-[#00fff9]/10 border border-indigo-200 dark:border-[#00fff9]/30 flex items-center justify-center font-black text-indigo-600 dark:text-[#00fff9] shadow-sm">
                                0{{ $index + 1 }}
                            </div>
                            <p class="text-lg font-bold text-slate-800 dark:text-white pt-1 leading-relaxed">
                                {{ $content['question'] ?? '' }}
                            </p>
                        </div>

                        <div class="space-y-3 pl-0 md:pl-14">
                            @if(str_contains(strtolower($q->type), 'multiple'))
                                @if(isset($content['options']))
                                    @foreach($content['options'] as $opt)
                                    <label class="w-full block relative group/btn cursor-pointer">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="peer absolute opacity-0 w-0 h-0" required onchange="typeof playSound !== 'undefined' ? playSound('click') : ''">
                                        <div class="px-5 py-4 bg-slate-50 dark:bg-[#050505] border border-slate-300 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-mono text-sm transition-all group-hover/btn:border-indigo-500 dark:group-hover/btn:border-[#00fff9] peer-checked:bg-indigo-600 peer-checked:border-indigo-500 dark:peer-checked:bg-[#00fff9]/20 dark:peer-checked:border-[#00fff9] peer-checked:text-white dark:peer-checked:text-[#00fff9] flex gap-3 items-start shadow-sm peer-checked:shadow-[0_0_15px_rgba(99,102,241,0.4)] dark:peer-checked:shadow-[0_0_15px_rgba(0,255,249,0.4)]">
                                            <span class="font-black mt-0.5 peer-checked:animate-pulse">></span>
                                            <span>{{ $opt }}</span>
                                        </div>
                                    </label>
                                    @endforeach
                                @endif
                            @else
                                <div class="relative flex items-center bg-slate-50 dark:bg-[#050505] border border-slate-300 dark:border-slate-800 focus-within:border-indigo-500 dark:focus-within:border-[#00fff9] focus-within:shadow-[0_0_15px_rgba(99,102,241,0.3)] dark:focus-within:shadow-[0_0_15px_rgba(0,255,249,0.3)] transition-all p-4">
                                    <span class="text-indigo-500 dark:text-[#00fff9] font-black mr-3 animate-pulse">></span>
                                    <input type="text" name="answers[{{ $q->id }}]" placeholder="Ketik eksekusi jawaban..." class="w-full bg-transparent border-none outline-none font-mono text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600" required>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                <button type="submit" class="w-full mt-12 py-5 bg-indigo-600 dark:bg-[#00fff9]/10 border border-transparent dark:border-[#00fff9] text-white dark:text-[#00fff9] hover:bg-indigo-500 dark:hover:bg-[#00fff9] dark:hover:text-slate-900 font-black text-lg tracking-widest uppercase shadow-[0_0_20px_rgba(99,102,241,0.4)] dark:shadow-[0_0_30px_rgba(0,255,249,0.3)] transition-all flex justify-center items-center gap-3 cyber-link group" onmouseenter="typeof playSound !== 'undefined' ? playSound('hover') : ''">
                    <span class="relative z-10">> SUBMIT_COGNITIVE_REPORT</span>
                    <svg class="w-6 h-6 relative z-10 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </button>
            @endif
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // FUNGSI UNTUK TOAST NOTIFICATION CYBERPUNK
    function showCyberToast(msg) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `cyber-toast p-4 border-l-4 backdrop-blur-md flex items-center gap-3 border-rose-500 bg-white dark:bg-rose-500/10 text-rose-600 dark:text-rose-500 shadow-xl pointer-events-auto`;
        toast.innerHTML = `<span class="w-2 h-2 animate-ping bg-current rounded-full"></span><p class="font-mono text-[11px] md:text-xs font-bold uppercase tracking-widest">${msg}</p>`;
        
        container.appendChild(toast);
        setTimeout(() => { toast.classList.add('toast-leave'); setTimeout(() => toast.remove(), 500); }, 4000);
    }

    // VALIDASI FORM SEBELUM SUBMIT
    document.getElementById('theory-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Tahan pengiriman asli
        
        let isValid = true;
        const inputs = this.querySelectorAll('input[required]');
        
        // Cek manual khusus radio group dan input text
        const requiredNames = new Set();
        inputs.forEach(input => requiredNames.add(input.name));
        
        for (let name of requiredNames) {
            const elements = this.querySelectorAll(`input[name="${name}"]`);
            if (elements[0].type === 'radio') {
                const isChecked = Array.from(elements).some(el => el.checked);
                if (!isChecked) isValid = false;
            } else if (elements[0].type === 'text') {
                if (elements[0].value.trim() === '') isValid = false;
            }
        }

        if(!isValid) {
            // Tampilkan Toast Peringatan
            showCyberToast("WARNING: MISSING DATA! Jawab seluruh soal sebelum eksekusi.");
            if(typeof playSound !== 'undefined') playSound('error');
        } else {
            // Jika Lolos, Tampilkan Animasi Layar Matrix
            const overlay = document.getElementById('page-transition');
            if(overlay) overlay.classList.add('active');
            if(typeof playSound !== 'undefined') playSound('click');
            
            // Kirim Form Beneran
            this.submit();
        }
    });
</script>
@endpush