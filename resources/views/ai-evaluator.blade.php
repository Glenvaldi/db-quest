@extends('layouts.app')

@section('title', 'DB-QUEST | Neural-Link AI')

@push('styles')
<style>
    /* 🎯 CUSTOM CYBER CURSOR */
    body { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%2300fff9" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>') 12 12, auto; }
    a, button, .cyber-link, input { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23f43f5e" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 0v5M12 19v5M0 12h5M19 12h5"/></svg>') 12 12, pointer !important; }

    /* 💥 GLITCH TEKS (Hanya untuk Header) */
    .glitch-wrapper { position: relative; display: inline-block; }
    .glitch { position: relative; font-weight: 900; }
    .glitch::before, .glitch::after { content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: transparent; }
    .dark .glitch::before { left: 2px; text-shadow: -2px 0 #00fff9; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .dark .glitch::after { left: -2px; text-shadow: -2px 0 #ff00c1, 2px 2px #00fff9; clip: rect(44px, 450px, 56px, 0); animation: glitch-anim2 5s infinite linear alternate-reverse; }
    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(16px, 9999px, 78px, 0); } 60% { clip: rect(89px, 9999px, 13px, 0); } 80% { clip: rect(52px, 9999px, 53px, 0); } 100% { clip: rect(21px, 9999px, 34px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 20% { clip: rect(3px, 9999px, 76px, 0); } 40% { clip: rect(53px, 9999px, 22px, 0); } 60% { clip: rect(76px, 9999px, 89px, 0); } 80% { clip: rect(12px, 9999px, 55px, 0); } 100% { clip: rect(44px, 9999px, 12px, 0); } }

    /* 🎛️ EFEK HUD & CHAT */
    .hud-card { transition: all 0.4s ease; backdrop-filter: blur(12px); position: relative; overflow: hidden; }
    .hud-cut-tr { clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%); }
    .hud-cut-bl { clip-path: polygon(0 0, 100% 0, 100% 100%, 20px 100%, 0 calc(100% - 20px)); }
    
    .chat-bubble-ai { 
        clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px); 
        animation: slideInLeft 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    .chat-bubble-user { 
        clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px));
        animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    
    @keyframes slideInLeft { from { transform: translateX(-50px) scale(0.9); opacity: 0; } to { transform: translateX(0) scale(1); opacity: 1; } }
    @keyframes slideInRight { from { transform: translateX(50px) scale(0.9); opacity: 0; } to { transform: translateX(0) scale(1); opacity: 1; } }

    .scanlines-bg { position: absolute; inset: 0; pointer-events: none; z-index: 0; background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; opacity: 0.3; }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4f46e5; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #00fff9; }

    /* AI Typing Pulse */
    .ai-typing-dot { animation: typingPulse 1.4s infinite ease-in-out both; }
    .ai-typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .ai-typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes typingPulse { 0%, 80%, 100% { transform: scale(0); opacity: 0.3; } 40% { transform: scale(1); opacity: 1; } }
</style>
@endpush

@section('content')
<div class="relative w-full h-[calc(100vh-6rem)] md:h-[calc(100vh-7rem)] bg-slate-50 dark:bg-[#030305] transition-colors duration-500 overflow-hidden flex flex-col items-center">
    
    <div class="absolute inset-0 bg-[linear-gradient(rgba(79,70,229,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(79,70,229,0.05)_1px,transparent_1px)] dark:bg-[linear-gradient(rgba(0,255,249,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,249,0.05)_1px,transparent_1px)] z-0 pointer-events-none" style="background-size: 40px 40px;"></div>
    <div class="scanlines-bg"></div>
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[800px] {{ $passed ? 'bg-emerald-500/5 dark:bg-emerald-600/10' : 'bg-rose-500/5 dark:bg-rose-600/10' }} blur-[150px] rounded-full pointer-events-none z-0 transition-colors duration-1000"></div>

    <div class="w-full max-w-5xl mx-auto px-4 py-4 relative z-10 flex flex-col h-full">
        
        <div class="shrink-0 w-full flex flex-col md:flex-row items-center md:justify-between bg-white/90 dark:bg-[#0a0a0c]/90 backdrop-blur-md p-4 md:p-5 border {{ $passed ? 'border-emerald-500/30' : 'border-rose-500/30' }} shadow-[0_0_30px_rgba(0,0,0,0.1)] dark:shadow-[0_0_30px_rgba(0,0,0,0.3)] hud-card hud-cut-tr mb-4" data-aos="fade-down">
            
            <div class="flex items-center gap-4 md:gap-5 w-full md:w-auto mb-4 md:mb-0">
                <div class="relative w-12 h-12 md:w-16 md:h-16 shrink-0 flex items-center justify-center">
                    <svg class="absolute inset-0 w-full h-full text-indigo-500 dark:text-[#00fff9] animate-[spin_4s_linear_infinite]" viewBox="0 0 100 100"><circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="20 10 5 15" /></svg>
                    <svg class="absolute inset-0 w-full h-full text-purple-500 dark:text-indigo-500 animate-[spin_6s_linear_infinite_reverse]" viewBox="0 0 100 100"><circle cx="50" cy="50" r="35" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="10 30" /></svg>
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-indigo-600 dark:bg-[#00fff9] rounded-sm rotate-45 flex items-center justify-center text-white dark:text-slate-900 font-black text-[8px] md:text-[10px] shadow-[0_0_10px_currentColor] z-10">AI</div>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-2 py-0.5 bg-indigo-500/10 border border-indigo-500/30 text-indigo-600 dark:text-[#00fff9] text-[8px] md:text-[9px] font-mono font-black tracking-widest uppercase mb-1">
                        <span class="w-1.5 h-1.5 bg-current animate-pulse"></span> LLaMA-3.1 Core
                    </div>
                    <h2 class="text-lg md:text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight glitch-wrapper"><span class="glitch" data-text="DB-NEURAL MENTOR">DB-NEURAL MENTOR</span></h2>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-800 pt-4 md:pt-0 md:pl-6">
                
                <div class="flex items-center justify-between w-full md:w-auto gap-4">
                    <div class="text-left md:text-right">
                        <p class="text-[9px] md:text-[10px] font-mono font-bold tracking-widest uppercase mb-1 {{ $passed ? 'text-emerald-500' : 'text-rose-500' }}">>> MISSION_REPORT</p>
                        <p class="text-xs md:text-sm font-black text-slate-700 dark:text-slate-300 uppercase truncate max-w-[150px] md:max-w-[180px]">{{ $levelName }}</p>
                    </div>
                    <div class="flex flex-col items-center justify-center min-w-[70px] md:min-w-[80px] p-2 bg-slate-100 dark:bg-black/50 border {{ $passed ? 'border-emerald-500/50 shadow-[0_0_15px_rgba(16,185,129,0.2)]' : 'border-rose-500/50 shadow-[0_0_15px_rgba(244,63,94,0.2)]' }}">
                        <span class="text-xl md:text-2xl font-black leading-none {{ $passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-500' }}">{{ $score }}</span>
                        <span class="text-[7px] md:text-[8px] font-mono font-bold uppercase tracking-widest {{ $passed ? 'text-emerald-500' : 'text-rose-500' }}">{{ $passed ? 'CLEARED' : 'FAILED' }}</span>
                    </div>
                </div>

                <a href="{{ route('adventure.index') }}" class="w-full md:w-auto px-4 py-2.5 bg-rose-500/10 border border-rose-500/50 hover:bg-rose-500 hover:text-white text-rose-500 dark:text-rose-400 font-mono text-[10px] font-bold uppercase tracking-widest transition-colors flex items-center justify-center gap-2 cyber-link" onmouseenter="playHover()" onclick="playClick()">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    AKHIRI SESI
                </a>

            </div>
        </div>

        <div id="chat-box" class="flex-1 min-h-0 bg-white/70 dark:bg-[#050508]/80 border border-slate-200 dark:border-slate-800/80 p-4 md:p-6 overflow-y-auto flex flex-col gap-6 shadow-inner custom-scrollbar relative z-10 backdrop-blur-md rounded-lg md:rounded-xl" data-aos="zoom-in" data-aos-delay="100">
            </div>

        <div class="shrink-0 w-full mt-4" data-aos="fade-up" data-aos-delay="200">
            <form id="chat-form" class="hud-card hud-cut-bl flex relative bg-white dark:bg-[#0a0a0c] border border-slate-300 dark:border-indigo-500/30 shadow-lg dark:shadow-[0_0_20px_rgba(0,0,0,0.1)] focus-within:border-indigo-500 dark:focus-within:border-[#00fff9] focus-within:shadow-[0_0_20px_rgba(99,102,241,0.3)] dark:focus-within:shadow-[0_0_20px_rgba(0,255,249,0.3)] transition-all">
                
                <div class="flex items-center pl-4 pr-2 text-indigo-500 dark:text-[#00fff9] font-black">
                    >
                </div>
                
                <input type="text" id="user-input" class="w-full bg-transparent border-none text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 font-mono text-sm md:text-base px-2 py-4 focus:outline-none focus:ring-0" placeholder="Ketik pesan atau tanyakan error ke AI..." autocomplete="off" required>
                
                <button type="submit" id="send-btn" class="group absolute right-1 top-1 bottom-1 aspect-square bg-indigo-600 dark:bg-[#00fff9]/10 border border-transparent dark:border-[#00fff9]/30 text-white dark:text-[#00fff9] hover:bg-indigo-500 dark:hover:bg-[#00fff9] dark:hover:text-slate-900 flex items-center justify-center transition-all cyber-link" onmouseenter="playHover()">
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // 🔊 AUDIO ENGINE
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    const aiActx = new AudioContext();
    function playSound(type) {
        if(aiActx.state === 'suspended') aiActx.resume();
        const osc = aiActx.createOscillator(); const gain = aiActx.createGain();
        osc.connect(gain); gain.connect(aiActx.destination);
        if(type === 'click' || type === 'pop') {
            osc.type = 'sine'; osc.frequency.setValueAtTime(800, aiActx.currentTime); gain.gain.setValueAtTime(0.1, aiActx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, aiActx.currentTime + 0.1); osc.start(); osc.stop(aiActx.currentTime + 0.1);
        } else if(type === 'receive') {
            osc.type = 'triangle'; osc.frequency.setValueAtTime(600, aiActx.currentTime); osc.frequency.exponentialRampToValueAtTime(1200, aiActx.currentTime + 0.2); gain.gain.setValueAtTime(0.15, aiActx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, aiActx.currentTime + 0.2); osc.start(); osc.stop(aiActx.currentTime + 0.2);
        }
    }

    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');
    
    let chatHistory = [];

    function appendMessage(sender, text) {
        const wrapper = document.createElement('div');
        wrapper.className = `flex w-full ${sender === 'user' ? 'justify-end' : 'justify-start'}`;

        const div = document.createElement('div');
        if(sender === 'user') {
            div.className = `chat-bubble-user max-w-[85%] md:max-w-[70%] p-4 text-sm md:text-base leading-relaxed bg-indigo-600 text-white shadow-[0_0_15px_rgba(99,102,241,0.4)] border border-indigo-400 font-medium`;
            div.innerHTML = text.replace(/\n/g, '<br>');
            wrapper.appendChild(div);
        } else {
            // Format Pesan AI
            const isDark = document.documentElement.classList.contains('dark');
            const aiColor = isDark ? '#00fff9' : '#10b981';
            const aiBg = isDark ? 'rgba(0, 255, 249, 0.05)' : 'rgba(16, 185, 129, 0.05)';
            const aiBorder = isDark ? 'rgba(0, 255, 249, 0.3)' : 'rgba(16, 185, 129, 0.3)';

            div.className = `chat-bubble-ai max-w-[90%] md:max-w-[80%] p-5 text-sm md:text-base leading-relaxed font-mono shadow-lg relative flex flex-col gap-2`;
            div.style.backgroundColor = aiBg;
            div.style.borderColor = aiBorder;
            div.style.borderWidth = '1px';
            div.style.borderStyle = 'solid';
            div.style.color = isDark ? '#cbd5e1' : '#475569'; 

            // Header "SYS.REPLY"
            const header = document.createElement('div');
            header.className = `text-[10px] font-black tracking-widest uppercase border-b pb-2 mb-2 flex items-center gap-2`;
            header.style.borderColor = aiBorder;
            header.style.color = aiColor;
            header.innerHTML = `<span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: ${aiColor}"></span> DB-NEURAL MENTOR // RESPONDING...`;
            
            const textContainer = document.createElement('div');
            textContainer.className = `text-slate-800 dark:text-slate-300`;
            textContainer.innerHTML = text.replace(/\n/g, '<br>');
            
            div.appendChild(header);
            div.appendChild(textContainer);
            wrapper.appendChild(div);
        }

        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function showTyping() {
        const wrapper = document.createElement('div');
        wrapper.id = "typing-indicator";
        wrapper.className = "flex w-full justify-start";

        const div = document.createElement('div');
        div.className = "chat-bubble-ai bg-slate-200/50 dark:bg-white/5 border border-slate-300 dark:border-white/10 p-4 flex gap-1 items-center shadow-sm";
        div.innerHTML = `<div class="w-2 h-2 rounded-full bg-indigo-500 dark:bg-[#00fff9] ai-typing-dot"></div><div class="w-2 h-2 rounded-full bg-indigo-500 dark:bg-[#00fff9] ai-typing-dot"></div><div class="w-2 h-2 rounded-full bg-indigo-500 dark:bg-[#00fff9] ai-typing-dot"></div>`;
        
        wrapper.appendChild(div);
        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function removeTyping() {
        const el = document.getElementById('typing-indicator');
        if(el) el.remove();
    }

    async function sendMessage(message) {
        if(message) {
            playSound('click');
            appendMessage('user', message);
            chatHistory.push({ role: 'user', content: message });
            userInput.value = '';
        }

        showTyping();
        sendBtn.disabled = true;
        sendBtn.classList.add('opacity-50', 'cursor-not-allowed');

        try {
            const response = await fetch("{{ route('ai.evaluator.chat') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    message: message,
                    score: {{ $score }},
                    passed: {{ $passed ? 1 : 0 }},
                    levelName: "{{ $levelName }}",
                    history: chatHistory
                })
            });

            const data = await response.json();
            removeTyping();
            playSound('receive');
            appendMessage('ai', data.reply);
            chatHistory.push({ role: 'assistant', content: data.reply });
        } catch (error) {
            removeTyping();
            playSound('error');
            appendMessage('ai', "⚠️ FATAL_ERROR: Koneksi ke Mainframe AI terputus. Silakan periksa jaringan komandan.");
        }
        
        sendBtn.disabled = false;
        sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        userInput.focus();
    }

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = userInput.value.trim();
        if(text) sendMessage(text);
    });

    window.onload = () => {
        sendMessage(""); // Memancing sapaan awal dari AI
    };
</script>
@endpush