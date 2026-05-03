<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();

        // 🔥 KITA HAPUS "navigate: true" AGAR BROWSER ME-REFRESH SEMPURNA 🔥
        if ($user->role === 'admin') {
            $this->redirect('/admin');
        } elseif ($user->role === 'teacher') {
            $this->redirect(route('teacher.dashboard', absolute: false));
        } else {
            $this->redirect(route('dashboard', absolute: false));
        }
    }
}; ?>

<div>
    <style>
        /* 🎯 CUSTOM CYBER CURSOR */
        body { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%2300fff9" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>') 12 12, auto; }
        a, button, select, input, label { cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23f43f5e" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 0v5M12 19v5M0 12h5M19 12h5"/></svg>') 12 12, pointer !important; }

        .scanlines-bg { position: fixed; inset: 0; pointer-events: none; z-index: 50; background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.2)); background-size: 100% 4px; opacity: 0.3; }
        .hud-card { backdrop-filter: blur(12px); position: relative; overflow: hidden; clip-path: polygon(30px 0, 100% 0, 100% calc(100% - 30px), calc(100% - 30px) 100%, 0 100%, 0 30px); }
        .terminal-input { background: rgba(5,5,5,0.8); border: 1px solid rgba(0,255,249,0.3); color: #00fff9; font-family: monospace; transition: all 0.3s; }
        .terminal-input:focus { border-color: #00fff9; box-shadow: 0 0 15px rgba(0,255,249,0.3); outline: none; }
    </style>

    <div class="fixed inset-0 bg-[#030305] z-[-2]"></div>
    <div class="fixed inset-0 bg-[linear-gradient(rgba(0,255,249,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,249,0.05)_1px,transparent_1px)] z-[-1]" style="background-size: 40px 40px;"></div>
    <div class="scanlines-bg"></div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="w-full max-w-md hud-card bg-[#0a0a0c]/90 border-2 border-indigo-500/50 p-8 md:p-10 shadow-[0_0_50px_rgba(79,70,229,0.2)] relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#00fff9]/10 blur-3xl pointer-events-none"></div>

            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 border border-[#00fff9]/30 bg-[#00fff9]/10 text-[#00fff9] text-[10px] font-mono font-black tracking-[0.4em] uppercase mb-4">
                    <span class="w-1.5 h-1.5 bg-[#00fff9] animate-pulse"></span> SYSTEM_AUTHORIZATION
                </div>
                <h2 class="text-3xl font-black text-white uppercase tracking-tighter drop-shadow-[0_0_10px_rgba(0,255,249,0.5)]">
                    AGENT_LOGIN
                </h2>
            </div>

            @if (session('status'))
                <div class="mb-4 text-sm text-emerald-400 text-center font-mono font-bold uppercase tracking-widest border border-emerald-500/30 bg-emerald-500/10 p-2">
                    > {{ session('status') }}
                </div>
            @endif

            <form wire:submit="login" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-mono font-bold text-indigo-400 uppercase tracking-widest mb-2">> EMAIL_ADDRESS</label>
                    <input type="email" wire:model="form.email" required autofocus class="w-full terminal-input px-4 py-3" placeholder="contoh@dbquest.com">
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-rose-500 font-mono text-xs" />
                </div>

                <div>
                    <label class="block text-[10px] font-mono font-bold text-indigo-400 uppercase tracking-widest mb-2">> PASSWORD</label>
                    <input type="password" wire:model="form.password" required class="w-full terminal-input px-4 py-3" placeholder="********">
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-rose-500 font-mono text-xs" />
                </div>

                <div class="flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-[#00fff9]/30 bg-transparent text-[#00fff9] shadow-sm focus:ring-[#00fff9]">
                    <label for="remember" class="ms-2 text-xs font-mono text-slate-400 uppercase tracking-widest cursor-pointer">Keep Me Logged In</label>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-indigo-500/30 mt-8">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[10px] font-mono font-bold text-slate-400 hover:text-rose-400 transition-colors" wire:navigate>
                            [ LOST_PASSWORD? ]
                        </a>
                    @endif

                    <button type="submit" class="group relative px-6 py-3 bg-indigo-600 text-white font-black text-xs uppercase tracking-widest overflow-hidden border border-indigo-400 flex items-center gap-2 hover:shadow-[0_0_30px_#00fff9] transition-shadow">
                        <div class="absolute inset-0 w-0 bg-gradient-to-r from-[#00fff9] to-indigo-500 transition-all duration-[400ms] ease-out group-hover:w-full opacity-50 z-0"></div>
                        <span class="relative z-10">> EXECUTE_LOGIN</span>
                        <svg class="w-4 h-4 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-slate-500 font-mono text-xs">
                    > Access Denied? <a href="{{ route('register') }}" class="text-[#00fff9] font-bold hover:underline" wire:navigate>Request New ID</a>
                </p>
            </div>
        </div>
    </div>
</div>