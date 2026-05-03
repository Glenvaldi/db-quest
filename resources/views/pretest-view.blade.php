@extends('layouts.app')

@section('title', 'DB-QUEST | Diagnostic Scan')

@push('styles')
<style>
    /* 🎯 CUSTOM CYBER CURSOR */
    body { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%2300fff9" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>') 12 12, auto; }
    a, button, .cyber-link, .option-btn { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23f43f5e" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 0v5M12 19v5M0 12h5M19 12h5"/></svg>') 12 12, pointer !important; }

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
    .hud-cut-tr { border-right: 4px solid #4f46e5; border-top: 1px solid rgba(79,70,229,0.3); clip-path: polygon(0 0, calc(100% - 25px) 0, 100% 25px, 100% 100%, 0 100%); }
    .dark .hud-cut-tr { border-right-color: #00fff9; border-top-color: rgba(0,255,249,0.3); }

    /* 🎯 TARGET LOCK BRACKETS */
    .target-bracket { position: absolute; width: 15px; height: 15px; border: 2px solid transparent; opacity: 1; z-index: 5; pointer-events: none; }
    .bracket-tl { top: 10px; left: 10px; border-top-color: currentColor; border-left-color: currentColor; }
    .bracket-tr { top: 10px; right: 10px; border-top-color: currentColor; border-right-color: currentColor; }
    .bracket-bl { bottom: 10px; left: 10px; border-bottom-color: currentColor; border-left-color: currentColor; }
    .bracket-br { bottom: 10px; right: 10px; border-bottom-color: currentColor; border-right-color: currentColor; }

    /* Scanlines */
    .scan-line { position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: rgba(0, 255, 249, 0.4); box-shadow: 0 0 20px rgba(0, 255, 249, 0.6); animation: scanAnim 4s linear infinite; z-index: 50; pointer-events: none; }
    @keyframes scanAnim { 0% { top: 0; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 100%; opacity: 0; } }
    .scanlines-bg { position: absolute; inset: 0; pointer-events: none; z-index: 0; background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; opacity: 0.3; }

    /* QUIZ BUTTONS LOGIC */
    .option-btn { transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .option-btn:hover { transform: translateX(10px); }
    .option-btn.disabled { pointer-events: none; opacity: 0.5; transform: none !important; }
    
    .is-correct { background-color: rgba(16, 185, 129, 0.1) !important; border-color: #10b981 !important; color: #10b981 !important; box-shadow: 0 0 20px rgba(16, 185, 129, 0.3) !important; opacity: 1 !important; }
    .dark .is-correct { color: #34d399 !important; }
    
    .is-wrong { background-color: rgba(244, 63, 94, 0.1) !important; border-color: #f43f5e !important; color: #f43f5e !important; box-shadow: 0 0 20px rgba(244, 63, 94, 0.3) !important; opacity: 1 !important; }
    
    .hidden-btn { display: none; opacity: 0; transition: opacity 0.5s; }
    .show-btn { display: flex; opacity: 1; animation: fadeInUp 0.5s forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="relative w-full min-h-[calc(100vh-5rem)] bg-slate-50 dark:bg-[#030305] text-slate-800 dark:text-slate-200 py-12 overflow-hidden transition-colors duration-500">
    
    <div class="scan-line"></div>
    <div class="scanlines-bg"></div>
    <div class="absolute inset-0 bg-[linear-gradient(rgba(79,70,229,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(79,70,229,0.05)_1px,transparent_1px)] dark:bg-[linear-gradient(rgba(0,255,249,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,249,0.05)_1px,transparent_1px)] z-0 pointer-events-none" style="background-size: 30px 30px;"></div>
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-indigo-600/5 dark:bg-indigo-600/10 blur-[150px] rounded-full pointer-events-none z-0"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        
        <div class="text-center mb-16" data-aos="fade-down">
            <div class="inline-flex items-center gap-2 px-6 py-2 rounded-none border-l-2 border-r-2 border-indigo-500 dark:border-[#00fff9] bg-indigo-500/10 dark:bg-[#00fff9]/10 text-indigo-600 dark:text-[#00fff9] text-xs font-mono font-black tracking-[0.3em] uppercase mb-6 shadow-sm">
                <span class="w-2 h-2 bg-current animate-pulse shadow-[0_0_10px_currentColor]"></span> 
                SYSTEM CALIBRATION : Lvl_0{{ $level_id }}
            </div>
            
            <div class="glitch-wrapper block mb-4">
                <h1 class="text-4xl md:text-5xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tighter glitch drop-shadow-md" data-text="PRE-MISSION DIAGNOSTIC.">
                    PRE-MISSION DIAGNOSTIC.
                </h1>
            </div>
            <p class="text-slate-600 dark:text-slate-400 font-mono text-sm">
                > Pemindaian pemahaman dasar. Analisis data dan pilih argumen yang paling akurat._
            </p>
        </div>

        <div id="quiz-container" class="space-y-8">
            
            @if(isset($pretest) && $pretest->questions->count() > 0)
                @foreach($pretest->questions as $index => $q)
                    @php 
                        $content = is_array($q->content) ? $q->content : json_decode($q->content, true); 
                    @endphp
                    
                    <div class="hud-card hud-cut-tr bg-white/90 dark:bg-[#0a0a0c]/90 backdrop-blur-xl border border-slate-200 dark:border-slate-800 p-8 shadow-xl dark:shadow-[0_0_30px_rgba(0,255,249,0.05)] group hover:border-indigo-400 dark:hover:border-[#00fff9]/50 transition-colors" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                        
                        <div class="target-bracket bracket-tl text-indigo-400 dark:text-[#00fff9]/50"></div>
                        <div class="target-bracket bracket-bl text-indigo-400 dark:text-[#00fff9]/50"></div>
                        
                        <div class="flex gap-4 mb-6">
                            <div class="w-10 h-10 shrink-0 bg-indigo-100 dark:bg-[#00fff9]/10 border border-indigo-200 dark:border-[#00fff9]/30 flex items-center justify-center font-black text-indigo-600 dark:text-[#00fff9] shadow-sm">
                                0{{ $index + 1 }}
                            </div>
                            <p class="text-lg font-bold text-slate-800 dark:text-white pt-1 leading-relaxed">
                                {{ $content['question'] ?? '' }}
                            </p>
                        </div>
                        
                        <div class="space-y-3 pl-0 md:pl-14 options-wrapper">
                            @if(isset($content['options']))
                                @foreach($content['options'] as $opt)
                                    @php 
                                        $isCorrect = ($opt == $q->correct_answer) ? 'true' : 'false'; 
                                    @endphp
                                    <button type="button" class="option-btn w-full text-left px-5 py-4 bg-slate-50 dark:bg-[#050505] border border-slate-300 dark:border-slate-800 hover:border-indigo-500 dark:hover:border-[#00fff9] text-slate-700 dark:text-slate-300 font-mono text-sm transition-all relative overflow-hidden group/btn flex gap-3 items-start" data-correct="{{ $isCorrect }}" onclick="checkAnswer(this)">
                                        <span class="text-indigo-500 dark:text-[#00fff9] font-black group-hover/btn:animate-pulse mt-0.5">></span>
                                        <span>{{ $opt }}</span>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="hud-card border-2 border-dashed border-rose-500/50 p-10 text-center bg-rose-500/5 backdrop-blur-md">
                    <p class="text-rose-500 font-mono font-bold tracking-widest uppercase">> WARNING: SOAL PRE-TEST BELUM TERSEDIA DI MAINFRAME.</p>
                </div>
            @endif

        </div>

        <form action="{{ route('pretest.finish', $level_id) }}" method="POST" class="mt-12" onsubmit="showProcessingOverlay()">
            @csrf
            
            <input type="hidden" id="final_score_input" name="score" value="0">
            
            <button type="submit" id="continue-btn" class="hidden-btn w-full py-5 bg-indigo-600 dark:bg-[#00fff9]/10 border border-transparent dark:border-[#00fff9] text-white dark:text-[#00fff9] hover:bg-indigo-500 dark:hover:bg-[#00fff9] dark:hover:text-slate-900 font-black text-lg tracking-widest uppercase shadow-[0_0_20px_rgba(99,102,241,0.4)] dark:shadow-[0_0_30px_rgba(0,255,249,0.3)] transition-all justify-center items-center gap-3 cyber-link group">
                <span class="relative z-10">> ENTER_DATABASE</span>
                <svg class="w-6 h-6 relative z-10 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </button>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // TRIGGER LOADING ANIMASI SAAT KLIK SUBMIT
    function showProcessingOverlay() {
        const overlay = document.getElementById('page-transition');
        if(overlay) overlay.classList.add('active');
        if(typeof playSound !== 'undefined') playSound('click');
    }

    // 🔊 AUDIO ENGINE
    const PretestAudioContext = window.AudioContext || window.webkitAudioContext;
    const pretestActx = new PretestAudioContext();
    
    function playPretestSound(type) {
        if(pretestActx.state === 'suspended') pretestActx.resume();
        const osc = pretestActx.createOscillator(); const gain = pretestActx.createGain();
        osc.connect(gain); gain.connect(pretestActx.destination);
        if(type === 'click') {
            osc.type = 'sine'; osc.frequency.setValueAtTime(800, pretestActx.currentTime); gain.gain.setValueAtTime(0.1, pretestActx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, pretestActx.currentTime + 0.1); osc.start(); osc.stop(pretestActx.currentTime + 0.1);
        } else if(type === 'correct') {
            osc.type = 'triangle'; osc.frequency.setValueAtTime(600, pretestActx.currentTime); osc.frequency.exponentialRampToValueAtTime(1200, pretestActx.currentTime + 0.2); gain.gain.setValueAtTime(0.15, pretestActx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, pretestActx.currentTime + 0.3); osc.start(); osc.stop(pretestActx.currentTime + 0.3);
        } else if(type === 'error') {
            osc.type = 'sawtooth'; osc.frequency.setValueAtTime(150, pretestActx.currentTime); osc.frequency.exponentialRampToValueAtTime(100, pretestActx.currentTime + 0.3); gain.gain.setValueAtTime(0.2, pretestActx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, pretestActx.currentTime + 0.3); osc.start(); osc.stop(pretestActx.currentTime + 0.3);
        } else if(type === 'win') {
            osc.type = 'square'; osc.frequency.setValueAtTime(400, pretestActx.currentTime); osc.frequency.setValueAtTime(600, pretestActx.currentTime + 0.1); osc.frequency.setValueAtTime(800, pretestActx.currentTime + 0.2); gain.gain.setValueAtTime(0.1, pretestActx.currentTime); gain.gain.linearRampToValueAtTime(0, pretestActx.currentTime + 0.5); osc.start(); osc.stop(pretestActx.currentTime + 0.5);
        }
    }

    const totalQuestions = {{ isset($pretest) ? $pretest->questions->count() : 0 }}; 
    let answeredQuestions = 0;
    
    // 🔥 TAMBAHAN UNTUK KALKULATOR NILAI 🔥
    let correctAnswersCount = 0; 

    function checkAnswer(selectedButton) {
        const wrapper = selectedButton.closest('.options-wrapper');
        const allButtons = wrapper.querySelectorAll('.option-btn');

        let isAnswerCorrect = selectedButton.getAttribute('data-correct') === 'true';
        
        if(isAnswerCorrect) {
            playPretestSound('correct');
            correctAnswersCount++; // Menambah jumlah jawaban benar
        } else {
            playPretestSound('error');
        }

        allButtons.forEach(btn => {
            btn.classList.add('disabled');
            const arrowSpan = btn.querySelector('span:first-child');
            if (btn.getAttribute('data-correct') === 'true') {
                btn.classList.add('is-correct');
                arrowSpan.innerText = '[✓]'; // 🔥 Fix karakter alien menjadi centang
            }
        });

        if (!isAnswerCorrect) {
            selectedButton.classList.add('is-wrong');
            selectedButton.querySelector('span:first-child').innerText = '[x]';
        }

        answeredQuestions++;

        if (answeredQuestions >= totalQuestions && totalQuestions > 0) {
            
            // 🔥 KALKULASI NILAI AKHIR (Skala 100) 🔥
            let finalScore = Math.round((correctAnswersCount / totalQuestions) * 100);
            if (finalScore > 100) finalScore = 100; // Pastikan tidak lebih dari 100
            
            // Memasukkan nilai ke dalam input rahasia sebelum dikirim
            document.getElementById('final_score_input').value = finalScore;

            setTimeout(() => {
                playPretestSound('win');
                const continueBtn = document.getElementById('continue-btn');
                continueBtn.classList.remove('hidden-btn');
                continueBtn.classList.add('show-btn');
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            }, 600);
        }
    }
</script>
@endpush