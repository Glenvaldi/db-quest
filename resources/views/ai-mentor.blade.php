@extends('layouts.app')

@section('title', 'DB-QUEST | Neural Assistant')

@push('styles')
<style>
    body { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23d946ef" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>') 12 12, auto; }
    a, button, input { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23f43f5e" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 0v5M12 19v5M0 12h5M19 12h5"/></svg>') 12 12, pointer !important; }

    .hud-card { backdrop-filter: blur(12px); border-top: 1px solid rgba(217,70,239,0.3); border-left: 4px solid #d946ef; clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%); }
    
    .chat-bubble-ai { clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px); animation: slideInLeft 0.4s ease forwards; }
    .chat-bubble-user { clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px)); animation: slideInRight 0.4s ease forwards; }
    
    @keyframes slideInLeft { from { transform: translateX(-50px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes slideInRight { from { transform: translateX(50px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d946ef; border-radius: 10px; }

    .ai-typing-dot { animation: typingPulse 1.4s infinite ease-in-out both; }
    .ai-typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .ai-typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes typingPulse { 0%, 80%, 100% { transform: scale(0); opacity: 0.3; } 40% { transform: scale(1); opacity: 1; } }
</style>
@endpush

@section('content')
<div class="relative w-full h-[calc(100vh-6rem)] md:h-[calc(100vh-7rem)] bg-slate-50 dark:bg-[#030305] overflow-hidden flex flex-col items-center">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-fuchsia-500/10 dark:bg-fuchsia-600/10 blur-[150px] rounded-full pointer-events-none z-0"></div>

    <div class="w-full max-w-4xl mx-auto px-4 py-4 relative z-10 flex flex-col h-full">
        <div class="shrink-0 w-full flex items-center justify-between bg-white/90 dark:bg-[#0a0a0c]/90 backdrop-blur-md p-4 md:p-5 shadow-lg hud-card mb-4" data-aos="fade-down">
            <div class="flex items-center gap-4">
                <div class="relative w-12 h-12 flex items-center justify-center">
                    <svg class="absolute inset-0 w-full h-full text-fuchsia-500 animate-[spin_4s_linear_infinite]" viewBox="0 0 100 100"><circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="20 10" /></svg>
                    <div class="w-6 h-6 bg-fuchsia-600 rounded-sm rotate-45 flex items-center justify-center text-white font-black text-[8px] shadow-[0_0_15px_#d946ef] z-10">AI</div>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-2 py-0.5 bg-fuchsia-500/10 border border-fuchsia-500/30 text-fuchsia-600 dark:text-[#e879f9] text-[9px] font-mono font-black tracking-widest uppercase mb-1">
                        <span class="w-1.5 h-1.5 bg-current animate-pulse"></span> SYSTEM COMPANION
                    </div>
                    <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">NEURAL ASSISTANT</h2>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 border border-slate-300 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-white transition-all cyber-link" onclick="playClick()">KEMBALI</a>
        </div>

        <div id="chat-box" class="flex-1 min-h-0 bg-white/70 dark:bg-[#050508]/80 border border-slate-200 dark:border-fuchsia-500/20 p-4 md:p-6 overflow-y-auto flex flex-col gap-6 custom-scrollbar rounded-lg" data-aos="zoom-in" data-aos-delay="100">
        </div>

        <div class="shrink-0 w-full mt-4" data-aos="fade-up" data-aos-delay="200">
            <form id="chat-form" class="flex relative bg-white dark:bg-[#0a0a0c] border border-slate-300 dark:border-fuchsia-500/30 shadow-lg focus-within:border-fuchsia-500 focus-within:shadow-[0_0_20px_rgba(217,70,239,0.3)] transition-all">
                <div class="flex items-center pl-4 pr-2 text-fuchsia-500 font-black">></div>
                <input type="text" id="user-input" class="w-full bg-transparent border-none text-slate-800 dark:text-white placeholder-slate-400 font-mono px-2 py-4 focus:outline-none focus:ring-0" placeholder="Tanyakan apapun ke asisten..." autocomplete="off" required>
                <button type="submit" id="send-btn" class="absolute right-1 top-1 bottom-1 aspect-square bg-fuchsia-600/10 border border-transparent hover:border-fuchsia-500/50 text-fuchsia-600 dark:text-[#e879f9] hover:bg-fuchsia-500 hover:text-white flex items-center justify-center transition-all">
                    <svg class="w-5 h-5 hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
            div.className = `chat-bubble-user max-w-[85%] md:max-w-[70%] p-4 text-sm leading-relaxed bg-fuchsia-600 text-white shadow-[0_0_15px_rgba(217,70,239,0.4)]`;
            div.innerHTML = text.replace(/\n/g, '<br>');
        } else {
            const isDark = document.documentElement.classList.contains('dark');
            div.className = `chat-bubble-ai max-w-[90%] md:max-w-[80%] p-5 text-sm leading-relaxed font-mono shadow-lg flex flex-col gap-2`;
            div.style.backgroundColor = isDark ? 'rgba(217, 70, 239, 0.05)' : '#fdf4ff';
            div.style.borderColor = 'rgba(217, 70, 239, 0.3)'; div.style.borderWidth = '1px';
            div.style.color = isDark ? '#cbd5e1' : '#475569'; 
            const header = document.createElement('div');
            header.className = `text-[10px] font-black tracking-widest uppercase border-b border-fuchsia-500/30 pb-2 mb-2 flex items-center gap-2 text-fuchsia-500`;
            header.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-fuchsia-500 animate-pulse"></span> NEURAL ASSISTANT`;
            const textContainer = document.createElement('div');
            textContainer.innerHTML = text.replace(/\n/g, '<br>');
            div.appendChild(header); div.appendChild(textContainer);
        }
        wrapper.appendChild(div); chatBox.appendChild(wrapper); chatBox.scrollTop = chatBox.scrollHeight;
    }

    function showTyping() {
        const wrapper = document.createElement('div');
        wrapper.id = "typing-indicator"; wrapper.className = "flex w-full justify-start";
        const div = document.createElement('div');
        div.className = "chat-bubble-ai bg-slate-200/50 dark:bg-white/5 border border-slate-300 dark:border-white/10 p-4 flex gap-1 shadow-sm";
        div.innerHTML = `<div class="w-2 h-2 rounded-full bg-fuchsia-500 ai-typing-dot"></div><div class="w-2 h-2 rounded-full bg-fuchsia-500 ai-typing-dot"></div><div class="w-2 h-2 rounded-full bg-fuchsia-500 ai-typing-dot"></div>`;
        wrapper.appendChild(div); chatBox.appendChild(wrapper); chatBox.scrollTop = chatBox.scrollHeight;
    }

    async function sendMessage(message) {
        if(message) { appendMessage('user', message); chatHistory.push({ role: 'user', content: message }); userInput.value = ''; }
        showTyping(); sendBtn.disabled = true; sendBtn.classList.add('opacity-50');
        try {
            const response = await fetch("{{ route('ai.mentor.chat') }}", {
                method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ message: message, history: chatHistory })
            });
            const data = await response.json();
            document.getElementById('typing-indicator')?.remove();
            appendMessage('ai', data.reply);
            chatHistory.push({ role: 'assistant', content: data.reply });
        } catch (error) {
            document.getElementById('typing-indicator')?.remove();
            appendMessage('ai', "⚠️ Koneksi putus Komandan! AI tidak merespons.");
        }
        sendBtn.disabled = false; sendBtn.classList.remove('opacity-50'); userInput.focus();
    }

    chatForm.addEventListener('submit', (e) => { e.preventDefault(); const text = userInput.value.trim(); if(text) sendMessage(text); });
    window.onload = () => { sendMessage(""); };
</script>
@endpush