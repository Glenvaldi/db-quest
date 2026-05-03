<?php

use App\Models\User;
use App\Models\School;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    
    public string $role = '';
    public string $school_id = '';

    public function with(): array
    {
        return [
            'schools' => School::all(),
        ];
    }

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:student,teacher'],
            'school_id' => ['required', 'exists:schools,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        event(new Registered($user));

        Auth::login($user);

        // 🔥 KITA BUANG "navigate: true" AGAR BROWSER REFRESH OTOMATIS 🔥
        if ($user->role === 'teacher') {
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
        
        select.terminal-input { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2300fff9'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.5em; }
        select option { background-color: #0a0a0c; color: #00fff9; padding: 10px; font-family: monospace; }
        
        /* Mencegah icon mata berkedip saat diload */
        [x-cloak] { display: none !important; }
    </style>

    <div class="fixed inset-0 bg-[#030305] z-[-2]"></div>
    <div class="fixed inset-0 bg-[linear-gradient(rgba(0,255,249,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,249,0.05)_1px,transparent_1px)] z-[-1]" style="background-size: 40px 40px;"></div>
    <div class="scanlines-bg"></div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="w-full max-w-xl hud-card bg-[#0a0a0c]/90 border-2 border-indigo-500/50 p-8 md:p-12 shadow-[0_0_50px_rgba(79,70,229,0.2)] relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#00fff9]/10 blur-3xl pointer-events-none"></div>

            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 border border-[#00fff9]/30 bg-[#00fff9]/10 text-[#00fff9] text-[10px] font-mono font-black tracking-[0.4em] uppercase mb-4">
                    <span class="w-1.5 h-1.5 bg-[#00fff9] animate-pulse"></span> SYSTEM_REGISTRATION
                </div>
                <h2 class="text-3xl font-black text-white uppercase tracking-tighter drop-shadow-[0_0_10px_rgba(0,255,249,0.5)]">
                    CREATE_NEW_ID
                </h2>
            </div>

            <form wire:submit="register" class="space-y-6">

                <div>
                    <label class="block text-[10px] font-mono font-bold text-indigo-400 uppercase tracking-widest mb-2">> OPERATOR_NAME</label>
                    <input type="text" wire:model="name" required autofocus class="w-full terminal-input px-4 py-3" placeholder="Ketik nama lengkapmu...">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-500 font-mono text-xs" />
                </div>

                <div>
                    <label class="block text-[10px] font-mono font-bold text-indigo-400 uppercase tracking-widest mb-2">> EMAIL_ADDRESS</label>
                    <input type="email" wire:model="email" required class="w-full terminal-input px-4 py-3" placeholder="contoh@dbquest.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 font-mono text-xs" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-mono font-bold text-emerald-400 uppercase tracking-widest mb-2">> ASSIGN_ROLE</label>
                        <select wire:model="role" required class="w-full terminal-input px-4 py-3 cursor-pointer">
                            <option value="" disabled selected>-- PILIH OTORITAS --</option>
                            <option value="student">[ USER ] - Siswa / Kadet</option>
                            <option value="teacher">[ ADMIN ] - Guru / Instruktur</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2 text-rose-500 font-mono text-xs" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-mono font-bold text-emerald-400 uppercase tracking-widest mb-2">> BASE_STATION (SEKOLAH)</label>
                        <select wire:model="school_id" required class="w-full terminal-input px-4 py-3 cursor-pointer">
                            <option value="" disabled selected>-- PILIH MARKAS --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('school_id')" class="mt-2 text-rose-500 font-mono text-xs" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div x-data="{ show: false }">
                        <label class="block text-[10px] font-mono font-bold text-rose-400 uppercase tracking-widest mb-2">> PASSWORD</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password" required class="w-full terminal-input px-4 py-3 pr-10" placeholder="********">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#00fff9]/50 hover:text-[#00fff9] transition-colors focus:outline-none">
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-cloak x-show="show" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 font-mono text-xs" />
                    </div>

                    <div x-data="{ show: false }">
                        <label class="block text-[10px] font-mono font-bold text-rose-400 uppercase tracking-widest mb-2">> CONFIRM_PASS</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" required class="w-full terminal-input px-4 py-3 pr-10" placeholder="********">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#00fff9]/50 hover:text-[#00fff9] transition-colors focus:outline-none">
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-cloak x-show="show" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-500 font-mono text-xs" />
                    </div>

                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-indigo-500/30 mt-8 gap-4">
                    <a class="text-xs font-mono font-bold text-slate-400 hover:text-[#00fff9] transition-colors cyber-link" href="{{ route('login') }}" wire:navigate>
                        [ SUDAH PUNYA AKSES? ]
                    </a>

                    <button type="submit" class="w-full sm:w-auto group relative px-8 py-3 bg-indigo-600 text-white font-black text-xs uppercase tracking-widest overflow-hidden border border-indigo-400 flex justify-center items-center gap-2 hover:shadow-[0_0_30px_#00fff9] transition-shadow cyber-link">
                        <div class="absolute inset-0 w-0 bg-gradient-to-r from-[#00fff9] to-indigo-500 transition-all duration-[400ms] ease-out group-hover:w-full opacity-50 z-0"></div>
                        <span class="relative z-10">> EXECUTE_REGISTER</span>
                        <svg class="w-4 h-4 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>