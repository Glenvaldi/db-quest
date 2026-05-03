@extends('layouts.app')

@section('title', 'DB-QUEST | System Login')

@section('content')
<div class="relative w-full min-h-[calc(100vh-5rem)] flex items-center justify-center bg-slate-50 dark:bg-[#09090b] transition-colors duration-500 overflow-hidden py-12 px-6">
    
    <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] dark:bg-[radial-gradient(rgba(255,255,255,0.05)_1px,transparent_1px)]" style="background-size: 30px 30px;"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md bg-white/80 dark:bg-[#111115]/90 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-indigo-500/10">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-400 mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </div>
            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Otorisasi Sistem</h2>
            <p class="text-slate-500 dark:text-zinc-400 text-sm mt-2">Masukkan kredensial untuk mengakses misi.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">Email Agen</label>
                <input id="email" type="email" name="email" required autofocus class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">Kata Sandi</label>
                <input id="password" type="password" name="password" required class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
            </div>

            <button type="submit" class="w-full py-4 mt-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5">
                INISIASI LOGIN
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-white/5 text-center">
            <p class="text-slate-500 dark:text-zinc-400 text-sm">
                Belum punya akses? 
                <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Daftar Rekrut Baru</a>
            </p>
        </div>
    </div>
</div>
@endsection