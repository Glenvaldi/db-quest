<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DB-QUEST | Belajar Basis Data Jadi Seru')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: { 
                    fontFamily: { sans: ['Inter', 'sans-serif'], display: ['Poppins', 'sans-serif'] },
                    colors: { darkbg: '#050505', }
                }
            }
        }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">
    
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #4f46e5; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #6366f1; }

        .cyber-grid {
            position: fixed; inset: 0; z-index: -2; pointer-events: none;
            background-size: 50px 50px;
            background-image: 
                linear-gradient(to right, rgba(99, 102, 241, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(99, 102, 241, 0.05) 1px, transparent 1px);
            animation: moveGrid 20s linear infinite;
        }
        .dark .cyber-grid {
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
        @keyframes moveGrid { 0% { transform: translateY(0); } 100% { transform: translateY(50px); } }

        .scanlines {
            position: fixed; inset: 0; z-index: 9999; pointer-events: none;
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1));
            background-size: 100% 4px; opacity: 0.15;
        }

        .btn-cyber {
            position: relative; overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-cyber::after {
            content: ''; position: absolute; top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg) translateX(-100%);
            transition: transform 0.6s ease;
        }
        .btn-cyber:hover::after { transform: rotate(45deg) translateX(100%); }
        .btn-cyber:hover { transform: translateY(-3px) scale(1.02); filter: brightness(1.2); }
        .btn-cyber:active { transform: scale(0.95); }

        /* 🔥 FIX: TRANSISI PAGE RESPONSIVE TEMA 🔥 */
        #page-transition {
            position: fixed; inset: 0; z-index: 99999;
            transform: scaleY(0); transform-origin: top;
            transition: transform 0.4s cubic-bezier(0.85, 0, 0.15, 1);
            display: flex; align-items: center; justify-content: center; flex-direction: column;
        }
        
        /* Tema Terang (Light Mode) */
        html:not(.dark) #page-transition { background-color: #f8fafc; }
        
        /* Tema Gelap (Dark Mode) */
        html.dark #page-transition { background-color: #050505; }
        
        #page-transition.active { transform: scaleY(1); transform-origin: bottom; }
        
        #main-navbar { transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease; }

        #theme-flash {
            position: fixed; inset: 0; z-index: 999999; pointer-events: none; opacity: 0;
            background: white;
        }
        .flash-active { animation: systemReboot 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
        
        @keyframes systemReboot {
            0% { opacity: 0; backdrop-filter: invert(0) hue-rotate(0deg); }
            15% { opacity: 1; backdrop-filter: invert(1) hue-rotate(90deg); background: rgba(0, 255, 249, 0.2); transform: skewX(3deg); }
            30% { opacity: 1; backdrop-filter: invert(1) hue-rotate(180deg); background: rgba(255, 0, 193, 0.2); transform: skewX(-3deg) scale(1.05); }
            45% { opacity: 1; backdrop-filter: invert(0); background: white; transform: skewX(0) scale(1); }
            100% { opacity: 0; backdrop-filter: invert(0); background: transparent; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 dark:bg-darkbg text-slate-700 dark:text-zinc-300 antialiased selection:bg-indigo-500/30 flex flex-col min-h-screen overflow-x-hidden">

    <div id="theme-flash"></div>
    <div class="cyber-grid"></div>
    <div class="scanlines"></div>
    <div class="fixed top-[-20%] left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-indigo-600/10 blur-[150px] rounded-full pointer-events-none z-[-1]"></div>

    <div id="page-transition">
        <div class="w-16 h-16 border-4 border-indigo-200 dark:border-indigo-500/20 border-t-indigo-600 dark:border-t-indigo-500 rounded-full animate-spin mb-4"></div>
        <span class="text-indigo-600 dark:text-indigo-500 font-mono font-black tracking-[0.5em] text-xl animate-pulse">PROCESSING...</span>
    </div>

    <div id="mobile-menu" class="fixed inset-0 z-[60] bg-white/95 dark:bg-[#050505]/95 backdrop-blur-2xl transform translate-x-full transition-transform duration-500 ease-out flex flex-col items-center justify-center md:hidden">
        <button id="close-menu-btn" class="absolute top-6 right-6 p-3 text-slate-500 dark:text-slate-400 hover:text-rose-500 dark:hover:text-rose-500 transition-colors btn-cyber rounded-full bg-slate-200/50 dark:bg-white/5 border border-slate-300 dark:border-white/10" onclick="playClick()">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <div class="flex flex-col gap-8 text-center w-full px-10">
            <div class="mb-4">
                <span class="text-indigo-500 font-mono tracking-[0.3em] text-xs uppercase border-b border-indigo-500/30 pb-2">Navigasi Sistem</span>
            </div>
            @auth
                <a href="{{ route('dashboard') }}" class="text-3xl font-display font-black text-slate-800 dark:text-white tracking-widest uppercase cyber-link hover:text-indigo-500 transition-colors" onclick="playHover()">MARKAS</a>
                <a href="{{ route('adventure.index') }}" class="text-3xl font-display font-black text-slate-800 dark:text-white tracking-widest uppercase cyber-link hover:text-indigo-500 transition-colors" onclick="playHover()">PETA MISI</a>
                <a href="{{ route('materials.index') }}" class="text-3xl font-display font-black text-slate-800 dark:text-white tracking-widest uppercase cyber-link hover:text-indigo-500 transition-colors" onclick="playHover()">DATABASE</a>
                
                <!-- MENU AI MOBILE BARU -->
                <a href="{{ route('ai.mentor.index') }}" class="text-3xl font-display font-black text-fuchsia-500 dark:text-[#e879f9] tracking-widest uppercase cyber-link hover:text-fuchsia-400 transition-colors flex items-center justify-center gap-3" onclick="playHover()">
                    <span class="w-3 h-3 rounded-full bg-current animate-pulse shadow-[0_0_10px_currentColor]"></span> NEURAL ASSISTANT
                </a>

                @if(auth()->user()->role === 'teacher')
                    <a href="{{ route('teacher.dashboard') }}" class="text-3xl font-display font-black text-emerald-500 tracking-widest uppercase cyber-link hover:text-emerald-400 transition-colors" onclick="playHover()">RUANG GURU</a>
                @endif
                @if(auth()->user()->role === 'admin')
                    <a href="/admin" class="text-3xl font-display font-black text-rose-500 tracking-widest uppercase cyber-link hover:text-rose-400 transition-colors" onclick="playHover()">PANEL ADMIN</a>
                @endif

                <a href="{{ route('player.profile') }}" class="mt-8 text-xl font-display font-bold text-indigo-500 tracking-widest uppercase cyber-link border border-indigo-500/30 py-3 rounded-2xl hover:bg-indigo-500/10 transition-colors" onclick="playHover()">PROFIL PLAYER</a>
            @else
                <a href="/login" class="text-3xl font-display font-black text-indigo-500 tracking-widest uppercase cyber-link hover:text-indigo-400 transition-colors" onclick="playHover()">LOGIN SYSTEM</a>
            @endauth
        </div>
    </div>

    <nav id="main-navbar" class="fixed top-4 left-1/2 -translate-x-1/2 w-[95%] max-w-6xl z-50 rounded-2xl border border-slate-200 dark:border-indigo-500/20 bg-white/70 dark:bg-[#0a0a0c]/80 backdrop-blur-xl shadow-[0_10px_30px_rgba(99,102,241,0.1)] transition-colors duration-500">
        <div class="px-4 md:px-6 h-20 flex items-center justify-between">
            
            <a href="/" class="flex items-center gap-2 md:gap-4 group cyber-link btn-cyber rounded-xl px-2 py-1" onmouseenter="playHover()" onclick="playClick()">
                <div class="relative w-10 h-10 md:w-12 md:h-12 flex items-center justify-center">
                    <svg class="absolute inset-0 w-full h-full text-indigo-500 animate-[spin_4s_linear_infinite]" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="3" stroke-dasharray="30 15 10 20" />
                    </svg>
                    <svg class="absolute inset-0 w-full h-full text-emerald-500 opacity-70 animate-[spin_6s_linear_infinite_reverse]" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="35" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="10 30" />
                    </svg>
                    <div class="w-4 h-4 md:w-5 md:h-5 bg-indigo-600 rounded-sm rotate-45 group-hover:bg-emerald-400 group-hover:rotate-90 transition-all duration-500 shadow-[0_0_15px_rgba(99,102,241,0.8)]"></div>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-xl md:text-2xl font-display font-black tracking-widest text-slate-900 dark:text-white leading-none group-hover:text-indigo-500 dark:group-hover:text-emerald-400 transition-colors">
                        DB<span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-emerald-400">-QUEST</span>
                    </h1>
                    <span class="text-[0.55rem] md:text-[0.65rem] text-indigo-600 dark:text-indigo-400 tracking-[0.4em] font-mono leading-none mt-1 opacity-70 group-hover:opacity-100 transition-opacity">SYS_ACTIVE</span>
                </div>
            </a>
            
            <div class="flex items-center gap-3 md:gap-6">
                @auth
                <div class="hidden md:flex items-center gap-2 p-1 bg-slate-100 dark:bg-black/50 rounded-full border border-slate-200 dark:border-white/5 transition-colors">
                    <a href="{{ route('dashboard') }}" class="cyber-link px-4 py-2 rounded-full text-sm font-bold text-slate-500 hover:text-slate-900 hover:bg-white dark:text-zinc-400 dark:hover:text-white dark:hover:bg-indigo-600/30 transition-all btn-cyber" onmouseenter="playHover()" onclick="playClick()">Markas</a>
                    <a href="{{ route('adventure.index') }}" class="cyber-link px-4 py-2 rounded-full text-sm font-bold text-slate-500 hover:text-slate-900 hover:bg-white dark:text-zinc-400 dark:hover:text-white dark:hover:bg-indigo-600/30 transition-all btn-cyber" onmouseenter="playHover()" onclick="playClick()">Peta Misi</a>
                    <a href="{{ route('materials.index') }}" class="cyber-link px-4 py-2 rounded-full text-sm font-bold text-slate-500 hover:text-slate-900 hover:bg-white dark:text-zinc-400 dark:hover:text-white dark:hover:bg-indigo-600/30 transition-all btn-cyber" onmouseenter="playHover()" onclick="playClick()">Database</a>
                    
                    <!-- MENU AI DESKTOP BARU -->
                    <a href="{{ route('ai.mentor.index') }}" class="cyber-link px-4 py-2 rounded-full text-sm font-bold text-fuchsia-600 bg-fuchsia-100/50 hover:bg-fuchsia-500 hover:text-white dark:text-[#e879f9] dark:bg-fuchsia-500/10 dark:hover:bg-fuchsia-500 dark:hover:text-white border border-fuchsia-500/30 transition-all btn-cyber shadow-[0_0_10px_rgba(217,70,239,0.2)] flex items-center gap-1.5" onmouseenter="playHover()" onclick="playClick()">
                        <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                        Neural Assistant
                    </a>

                    @if(auth()->user()->role === 'teacher')
                        <a href="{{ route('teacher.dashboard') }}" class="cyber-link px-4 py-2 rounded-full text-sm font-bold text-emerald-600 bg-emerald-100/50 hover:bg-emerald-500 hover:text-white dark:text-emerald-400 dark:bg-emerald-500/10 dark:hover:bg-emerald-500 dark:hover:text-white border border-emerald-500/30 transition-all btn-cyber shadow-[0_0_10px_rgba(16,185,129,0.2)]" onmouseenter="playHover()" onclick="playClick()">Ruang Guru</a>
                    @endif
                    
                    @if(auth()->user()->role === 'admin')
                        <a href="/admin" class="cyber-link px-4 py-2 rounded-full text-sm font-bold text-rose-600 bg-rose-100/50 hover:bg-rose-500 hover:text-white dark:text-rose-400 dark:bg-rose-500/10 dark:hover:bg-rose-500 dark:hover:text-white border border-rose-500/30 transition-all btn-cyber shadow-[0_0_10px_rgba(244,63,94,0.2)]" onmouseenter="playHover()" onclick="playClick()">Panel Admin</a>
                    @endif
                </div>
                @endauth

                <button id="theme-toggle" class="p-2 md:p-2.5 rounded-full text-slate-500 hover:bg-slate-200 dark:text-zinc-400 dark:hover:bg-white/10 transition-all duration-300 btn-cyber border border-transparent dark:hover:border-white/20" onmouseenter="playHover()">
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </button>

                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-xl text-slate-600 dark:text-zinc-300 hover:bg-slate-200 dark:hover:bg-white/10 btn-cyber border border-transparent dark:hover:border-white/20 transition-colors" onclick="playClick()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                @auth
                <a href="{{ route('player.profile') }}" class="hidden md:flex cyber-link group relative items-center gap-3 p-1 pr-4 rounded-full bg-slate-100/50 dark:bg-[#050505]/50 border border-slate-300 dark:border-indigo-500/30 hover:border-indigo-500 dark:hover:border-emerald-400 transition-all shadow-sm overflow-hidden" onmouseenter="playHover()" onclick="playClick()">
                    <div class="absolute inset-0 w-0 bg-gradient-to-r from-indigo-500/10 to-emerald-500/10 group-hover:w-full transition-all duration-500"></div>

                    <div class="relative w-9 h-9 rounded-full border-2 border-indigo-400 dark:border-indigo-500 p-[2px] group-hover:border-emerald-400 transition-colors bg-white dark:bg-black">
                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-black text-sm uppercase">
                            {{ substr(Auth::user()->name ?? 'P', 0, 1) }}
                        </div>
                        <div class="absolute bottom-0 right-[-2px] w-3 h-3 bg-emerald-400 border-2 border-white dark:border-[#0a0a0c] rounded-full animate-pulse shadow-[0_0_5px_#10b981]"></div>
                    </div>
                    
                    <div class="flex flex-col items-start justify-center relative z-10">
                        <span class="text-[0.6rem] font-mono text-slate-500 dark:text-zinc-400 tracking-wider leading-none mb-0.5">AGENT_ID</span>
                        <span class="text-sm font-bold text-indigo-700 dark:text-white leading-none group-hover:text-indigo-600 dark:group-hover:text-emerald-400 transition-colors truncate max-w-[100px] uppercase">{{ Auth::user()->name ?? 'Profil' }}</span>
                    </div>
                </a>
                @else
                <a href="/login" class="hidden md:flex cyber-link px-8 py-2.5 bg-indigo-600 text-white font-black tracking-widest text-sm rounded-xl shadow-[0_0_15px_rgba(99,102,241,0.4)] btn-cyber" onmouseenter="playHover()" onclick="playClick()">LOGIN</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow relative z-10 w-full pt-28 md:pt-32">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <footer class="w-full border-t border-slate-200/80 dark:border-white/5 bg-white/60 dark:bg-[#050505]/80 backdrop-blur-xl mt-auto z-20" data-aos="fade-up" data-aos-offset="0">
        <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-6 transition-colors">
            <div class="flex items-center gap-4 opacity-70 hover:opacity-100 transition-opacity btn-cyber rounded-xl px-4 py-2 cursor-default group" onmouseenter="playHover()">
                <div class="relative w-10 h-10 flex items-center justify-center">
                    <svg class="absolute inset-0 w-full h-full text-indigo-500 animate-[spin_4s_linear_infinite]" viewBox="0 0 100 100"><circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="30 15 10 20" /></svg>
                    <svg class="absolute inset-0 w-full h-full text-emerald-500 opacity-70 animate-[spin_6s_linear_infinite_reverse]" viewBox="0 0 100 100"><circle cx="50" cy="50" r="30" fill="none" stroke="currentColor" stroke-width="3" stroke-dasharray="10 30" /></svg>
                    <div class="w-3 h-3 bg-indigo-600 rounded-sm rotate-45 group-hover:bg-emerald-400 group-hover:rotate-90 transition-all duration-500 shadow-[0_0_10px_rgba(99,102,241,0.8)]"></div>
                </div>
                <span class="font-display font-black tracking-widest text-slate-900 dark:text-white text-lg group-hover:text-indigo-600 dark:group-hover:text-emerald-400 transition-colors">DB-QUEST</span>
            </div>
            <p class="text-slate-500 dark:text-zinc-500 text-sm font-medium text-center md:text-left">
                &copy; {{ date('Y') }} DB-Quest Project | Universitas Negeri Malang
            </p>
            <div class="flex gap-4">
                <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 text-xs font-bold border border-emerald-300 dark:border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.2)]">SYSTEM ONLINE</span>
                <span class="px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-500 text-xs font-bold border border-indigo-300 dark:border-indigo-500/20">V 2.0.5</span>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: false, mirror: true, offset: 50, easing: 'ease-out-back' });

        // MOBILE MENU LOGIC
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const closeBtn = document.getElementById('close-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('translate-x-full');
            mobileMenu.classList.add('translate-x-0');
        });

        closeBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('translate-x-0');
            mobileMenu.classList.add('translate-x-full');
        });

        // SMART NAVBAR LOGIC
        let lastScrollY = window.scrollY;
        const navbar = document.getElementById('main-navbar');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                if (window.scrollY > lastScrollY) {
                    navbar.style.transform = 'translate(-50%, -150%)';
                } else {
                    navbar.style.transform = 'translate(-50%, 0)';
                    navbar.classList.add('shadow-[0_15px_40px_rgba(99,102,241,0.15)]');
                }
            } else {
                navbar.classList.remove('shadow-[0_15px_40px_rgba(99,102,241,0.15)]');
            }
            lastScrollY = window.scrollY;
        });

        // PAGE TRANSITION LOGIC
        document.querySelectorAll('a.cyber-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && !href.startsWith('#') && !this.hasAttribute('target')) {
                    e.preventDefault();
                    playClick();
                    const overlay = document.getElementById('page-transition');
                    overlay.classList.add('active');
                    setTimeout(() => { window.location.href = href; }, 400);
                }
            });
        });

        // GLITCH THEME LOGIC
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        const themeFlasher = document.getElementById('theme-flash');

        if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.remove('dark');
            darkIcon.classList.remove('hidden');
        } else {
            document.documentElement.classList.add('dark');
            lightIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            if(actx.state === 'suspended') actx.resume();
            const osc = actx.createOscillator(); const gain = actx.createGain();
            osc.connect(gain); gain.connect(actx.destination);
            osc.type = 'sawtooth'; osc.frequency.setValueAtTime(100, actx.currentTime); osc.frequency.exponentialRampToValueAtTime(800, actx.currentTime + 0.3);
            gain.gain.setValueAtTime(0.3, actx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, actx.currentTime + 0.3);
            osc.start(); osc.stop(actx.currentTime + 0.3);

            themeFlasher.classList.remove('flash-active');
            void themeFlasher.offsetWidth; 
            themeFlasher.classList.add('flash-active');

            setTimeout(() => {
                darkIcon.classList.toggle('hidden');
                lightIcon.classList.toggle('hidden');
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }, 250); 
        });

        const actx = new (window.AudioContext || window.webkitAudioContext)();
        function playHover() {
            if(actx.state === 'suspended') actx.resume();
            const osc = actx.createOscillator(); const gain = actx.createGain();
            osc.connect(gain); gain.connect(actx.destination);
            osc.type = 'sine'; osc.frequency.setValueAtTime(1200, actx.currentTime);
            gain.gain.setValueAtTime(0.02, actx.currentTime); gain.gain.exponentialRampToValueAtTime(0.001, actx.currentTime + 0.05);
            osc.start(); osc.stop(actx.currentTime + 0.05);
        }
        function playClick() {
            if(actx.state === 'suspended') actx.resume();
            const osc = actx.createOscillator(); const gain = actx.createGain();
            osc.connect(gain); gain.connect(actx.destination);
            osc.type = 'square'; osc.frequency.setValueAtTime(400, actx.currentTime); osc.frequency.exponentialRampToValueAtTime(100, actx.currentTime + 0.1);
            gain.gain.setValueAtTime(0.05, actx.currentTime); gain.gain.exponentialRampToValueAtTime(0.001, actx.currentTime + 0.1);
            osc.start(); osc.stop(actx.currentTime + 0.1);
        }
    </script>
    @stack('scripts')
</body>
</html>