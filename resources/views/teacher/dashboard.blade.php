<x-app-layout>
    @push('styles')
    <style>
        /* 🎯 CUSTOM CYBER CURSOR */
        body { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%2300fff9" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>') 12 12, auto; }
        html:not(.dark) body { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%234f46e5" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>') 12 12, auto; }
        
        a, button, .cyber-row { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23f43f5e" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 0v5M12 19v5M0 12h5M19 12h5"/></svg>') 12 12, pointer !important; }
        
        .hud-cut-bl { clip-path: polygon(0 0, 100% 0, 100% 100%, 20px 100%, 0 calc(100% - 20px)); }
        
        .scanlines-bg { position: fixed; inset: 0; pointer-events: none; z-index: 0; background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; opacity: 0.3; }
        .dark .scanlines-bg { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.2)); }
        
        /* Menyembunyikan elemen AlpineJS sebelum dimuat */
        [x-cloak] { display: none !important; }
    </style>
    @endpush

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="relative w-full min-h-[calc(100vh-5rem)] bg-slate-50 dark:bg-[#030305] transition-colors duration-500 py-12 px-4 md:px-6 overflow-hidden">
        
        <div class="fixed inset-0 bg-[linear-gradient(rgba(79,70,229,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(79,70,229,0.05)_1px,transparent_1px)] dark:bg-[linear-gradient(rgba(0,255,249,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,249,0.05)_1px,transparent_1px)] z-[0] transition-colors duration-500" style="background-size: 40px 40px;"></div>
        <div class="scanlines-bg"></div>
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-indigo-500/10 dark:bg-indigo-600/10 blur-[150px] rounded-full pointer-events-none z-0 transition-colors duration-500"></div>

        <div class="max-w-6xl mx-auto relative z-10">
            
            <div class="bg-white/80 dark:bg-indigo-900/40 border-l-4 border-l-indigo-500 dark:border-l-[#00fff9] border-y border-r border-slate-200 dark:border-indigo-500/50 p-6 md:p-8 mb-8 backdrop-blur-md flex flex-col md:flex-row justify-between items-start gap-6 shadow-[0_0_30px_rgba(99,102,241,0.1)] dark:shadow-[0_0_30px_rgba(0,255,249,0.1)] transition-colors duration-500">
                
                <div class="flex-1">
                    <p class="text-indigo-600 dark:text-[#00fff9] font-mono text-[10px] uppercase tracking-[0.3em] mb-2">> WELCOME_INSTRUCTOR</p>
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white uppercase tracking-tight transition-colors">{{ auth()->user()->name }}</h1>
                    <div class="inline-flex items-center gap-2 mt-3 px-3 py-1 bg-slate-100 dark:bg-black/50 border border-slate-300 dark:border-indigo-500/30 text-slate-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">
                        <span class="w-1.5 h-1.5 bg-rose-500 animate-pulse"></span>
                        Base Station: <span class="text-slate-900 dark:text-white font-bold">{{ auth()->user()->school->name ?? 'Unknown Base' }}</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3 items-end w-full md:w-auto">
                    <div class="text-right bg-slate-100/80 dark:bg-black/40 border border-slate-200 dark:border-slate-800 p-3 min-w-[200px] flex justify-between items-center w-full transition-colors duration-500">
                        <p class="text-slate-500 dark:text-slate-400 font-mono text-[10px] uppercase tracking-widest">> TOTAL_KADET</p>
                        <p class="text-2xl font-black text-indigo-600 dark:text-[#00fff9] transition-colors">{{ $students->count() }}</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full">
                        <a href="{{ route('dashboard') }}" class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 px-4 py-2 bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-400 dark:border-emerald-500 text-emerald-700 dark:text-emerald-400 font-mono font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all shadow-[0_0_15px_rgba(16,185,129,0.2)] cyber-link" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            > ENTER_SIMULATION
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 flex-1 sm:flex-none">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-rose-100 dark:bg-rose-500/10 border border-rose-400 dark:border-rose-500 text-rose-700 dark:text-rose-500 font-mono font-bold text-[10px] uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-[0_0_15px_rgba(244,63,94,0.2)] cyber-link">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                > DISCONNECT
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 dark:bg-[#0a0a0c]/90 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-2xl hud-cut-bl relative overflow-hidden transition-colors duration-500">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 blur-3xl pointer-events-none"></div>

                <div class="bg-slate-100/80 dark:bg-slate-900/80 px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between transition-colors gap-2">
                    <h2 class="text-indigo-600 dark:text-[#00fff9] font-bold font-mono tracking-widest uppercase text-sm transition-colors">> KLASEMEN EVALUASI POST-TEST</h2>
                    <div class="flex items-center gap-3 text-[10px] font-mono font-bold text-slate-500">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-400"></span> Post-Test Awal</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-indigo-600 dark:bg-[#00fff9]"></span> Post-Test Tertinggi</span>
                    </div>
                </div>
                
                <div class="overflow-x-auto relative z-10 w-full">
                    <table class="w-full text-left font-mono text-sm whitespace-nowrap">
                        <thead class="bg-indigo-50 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-400 border-b border-slate-200 dark:border-slate-800 transition-colors">
                            <tr>
                                <th class="px-6 py-4 font-bold tracking-widest text-center">RANK</th>
                                <th class="px-6 py-4 font-bold tracking-widest">NAMA_KADET</th>
                                <th class="px-6 py-4 font-bold tracking-widest text-center bg-slate-200/50 dark:bg-black/20">AVG_AWAL (POST)</th>
                                <th class="px-6 py-4 font-bold tracking-widest text-center bg-indigo-100/50 dark:bg-indigo-900/20">AVG_TERBAIK (POST)</th>
                                <th class="px-6 py-4 text-center font-bold tracking-widest">AKSI</th>
                            </tr>
                        </thead>
                        
                        @forelse($students as $student)
                        <tbody x-data="{ open: false }" class="text-slate-600 dark:text-slate-300 transition-colors border-b border-slate-100 dark:border-slate-800/50">
                            
                            <tr @click="open = !open" class="hover:bg-slate-50 dark:hover:bg-indigo-900/20 transition-colors cyber-row">
                                
                                <td class="px-6 py-4 text-center">
                                    @if($loop->iteration == 1)
                                        <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1 bg-amber-100 dark:bg-amber-500/20 border border-amber-400 dark:border-amber-500/50 text-amber-600 dark:text-amber-400 rounded-md font-black shadow-[0_0_10px_rgba(245,158,11,0.3)]">
                                            <span>🥇</span> 1st
                                        </div>
                                    @elseif($loop->iteration == 2)
                                        <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1 bg-slate-200 dark:bg-slate-400/20 border border-slate-400 dark:border-slate-400/50 text-slate-700 dark:text-slate-300 rounded-md font-black">
                                            <span>🥈</span> 2nd
                                        </div>
                                    @elseif($loop->iteration == 3)
                                        <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1 bg-orange-100 dark:bg-orange-700/30 border border-orange-400 dark:border-orange-600/50 text-orange-600 dark:text-orange-400 rounded-md font-black">
                                            <span>🥉</span> 3rd
                                        </div>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 font-bold"># {{ sprintf('%02d', $loop->iteration) }}</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-slate-900 dark:text-white font-bold uppercase tracking-tight">
                                            {{ $student->name }}
                                            @if($loop->iteration == 1) <span class="ml-2 text-amber-500 text-xs animate-pulse">✨ MVP</span> @endif
                                        </span>
                                        <span class="text-[10px] text-indigo-500 dark:text-indigo-400/70">{{ $student->email }}</span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 text-center bg-slate-50 dark:bg-black/20 border-x border-slate-100 dark:border-slate-800/50">
                                    <span class="text-lg font-black text-slate-500 dark:text-slate-400">{{ $student->avg_initial ?? 0 }}</span>
                                </td>

                                <td class="px-6 py-4 text-center bg-indigo-50/50 dark:bg-indigo-900/10 border-r border-slate-100 dark:border-slate-800/50">
                                    <span class="text-xl font-black text-indigo-600 dark:text-[#00fff9] drop-shadow-[0_0_8px_rgba(0,255,249,0.3)]">{{ $student->avg_best ?? 0 }}</span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <button class="px-3 py-1 bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-300 dark:border-indigo-500/30 text-xs font-bold hover:bg-indigo-500 hover:text-white transition-colors pointer-events-none">
                                        <span x-show="!open">DETAIL +</span>
                                        <span x-show="open" x-cloak>TUTUP -</span>
                                    </button>
                                </td>
                            </tr>

                            <tr x-show="open" x-cloak class="bg-slate-100 dark:bg-slate-900/80 border-t border-indigo-500/30">
                                <td colspan="5" class="px-6 py-6">
                                    <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 mb-3">> DECRYPTING LOGS: PER-LEVEL PERFORMANCE</p>
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        
                                        @for($lvl = 1; $lvl <= 5; $lvl++)
                                            @php 
                                                $pretest = $student->scores ? $student->scores->where('level_id', $lvl)->where('quiz_type', 'pretest')->first() : null;
                                                $posttest = $student->scores ? $student->scores->where('level_id', $lvl)->where('quiz_type', 'theory')->first() : null;
                                            @endphp
                                            <div class="bg-white dark:bg-black/50 border border-slate-300 dark:border-slate-700 p-3 rounded-md shadow-sm transition-colors">
                                                <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase mb-2 text-center border-b border-slate-200 dark:border-slate-800 pb-1">LEVEL {{ $lvl }}</p>
                                                
                                                <div class="mb-3">
                                                    <span class="text-[9px] text-slate-500 uppercase block mb-0.5">Pre-Test (Diag):</span>
                                                    <div class="text-center bg-slate-50 dark:bg-black/30 py-1 rounded border border-slate-200 dark:border-slate-800">
                                                        <span class="text-xs font-bold text-indigo-500 dark:text-[#00fff9]">{{ $pretest->best_score ?? '-' }}</span>
                                                    </div>
                                                </div>

                                                <div>
                                                    <span class="text-[9px] text-slate-500 uppercase block mb-0.5">Post-Test (Remidi):</span>
                                                    <div class="flex justify-between items-center text-[10px] font-bold px-1">
                                                        <div class="flex flex-col items-center">
                                                            <span class="text-[8px] text-slate-400 uppercase font-mono">Awal</span>
                                                            <span class="text-slate-400">{{ $posttest->initial_score ?? '-' }}</span>
                                                        </div>
                                                        <div class="w-[1px] h-4 bg-slate-300 dark:bg-slate-700"></div>
                                                        <div class="flex flex-col items-center">
                                                            <span class="text-[8px] text-emerald-500 uppercase font-mono">Best</span>
                                                            <span class="text-emerald-500">{{ $posttest->best_score ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endfor

                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        @empty
                        <tbody class="text-slate-600 dark:text-slate-300">
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-rose-500">
                                    <svg class="w-8 h-8 mx-auto mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    > SYSTEM LOG: BELUM ADA DATA SKOR KADET YANG TEREKAM.
                                </td>
                            </tr>
                        </tbody>
                        @endforelse
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>