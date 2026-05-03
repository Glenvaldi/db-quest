@extends('layouts.app')

@section('title', 'DB-QUEST | Registrasi Rekrut')

@section('content')
<div class="relative w-full min-h-[calc(100vh-5rem)] flex items-center justify-center bg-slate-50 dark:bg-[#09090b] transition-colors duration-500 overflow-hidden py-12 px-6">
    
    <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] dark:bg-[radial-gradient(rgba(255,255,255,0.05)_1px,transparent_1px)]" style="background-size: 30px 30px;"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-600/10 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md bg-white/80 dark:bg-[#111115]/90 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-emerald-500/10">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
            </div>
            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Rekrut Baru</h2>
            <p class="text-slate-500 dark:text-zinc-400 text-sm mt-2">Buat profil agen untuk mulai simulasi.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1">Nama Panggilan</label>
                <input id="name" type="text" name="name" required autofocus class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1">Email Sekolah</label>
                <input id="email" type="email" name="email" required class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1">Kata Sandi</label>
                <input id="password" type="password" name="password" required class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1">Ulangi Sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
            </div>

            <button type="submit" class="w-full py-4 mt-6 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:-translate-y-0.5">
                DAFTAR SEKARANG
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-white/5 text-center">
            <p class="text-slate-500 dark:text-zinc-400 text-sm">
                Sudah punya akses? 
                <a href="{{ route('login') }}" class="text-emerald-600 dark:text-emerald-400 font-bold hover:underline">Login di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection