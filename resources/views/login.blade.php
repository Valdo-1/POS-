<!DOCTYPE html>
<html lang="id" class="h-full bg-[#080604] text-stone-100 antialiased selection:bg-amber-900/40 selection:text-amber-200">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KETARA - Login Point of Sales</title>

    <meta name="description" content="KETARA - Sistem Informasi Point of Sales PPKD Jakarta Pusat">
    <meta name="author" content="KETARA Team">

    <base href="{{ asset('assets/assets') }}/">
    @include('inc.css')
</head>

<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden font-sans ambient-coffee-bg text-stone-200">
    
    <!-- Ambient Amber Glow Orbs -->
    <div class="fixed -top-32 -left-32 w-[30rem] h-[30rem] bg-amber-800/15 rounded-full blur-[100px] pointer-events-none warm-orb z-0"></div>
    <div class="fixed -bottom-36 right-10 w-[32rem] h-[32rem] bg-amber-950/25 rounded-full blur-[110px] pointer-events-none warm-orb z-0" style="animation-delay: 4s;"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Glassmorphism Container Card -->
        <div class="glass-espresso rounded-3xl p-8 border border-amber-700/30 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-20 -left-20 w-48 h-48 bg-amber-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Brand Identity -->
            <div class="text-center mb-8">
                <a href="#" class="inline-flex items-center justify-center gap-3.5 mb-2 group">
                    <div class="relative w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-700 via-amber-600 to-amber-400 p-[1px] shadow-glow-amber">
                        <div class="w-full h-full bg-[#100b08] rounded-[15px] flex items-center justify-center">
                            <i data-lucide="coffee" class="w-6 h-6 text-amber-300"></i>
                        </div>
                    </div>
                    <div class="text-left">
                        <span class="text-2xl font-black tracking-wider text-amber-100 uppercase block">KETARA</span>
                        <span class="text-[10px] font-mono tracking-widest text-amber-400 uppercase block -mt-1 font-semibold">POINT OF SALES</span>
                    </div>
                </a>
                <p class="text-xs text-stone-400 font-medium">Sistem Informasi Kasir PPKD Jakarta Pusat</p>
            </div>

            <!-- Flash Alert Success -->
            @if (session('success'))
            <div class="mb-5 p-4 rounded-2xl bg-amber-950/40 border border-amber-600/40 text-amber-200 text-xs flex items-center gap-2.5">
                <i data-lucide="check-circle" class="w-4 h-4 text-amber-400 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Flash Alert Error -->
            @if ($errors->any())
            <div class="mb-5 p-4 rounded-2xl bg-rose-950/40 border border-rose-600/40 text-rose-200 text-xs">
                <div class="flex items-center gap-2 font-bold mb-1.5 text-rose-300">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-400"></i>
                    <span>Gagal Otentikasi</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-[11px] text-rose-300/90 font-mono">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('action-login') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-[10px] font-mono font-bold uppercase tracking-wider text-amber-400/90 mb-2">Email Terminal</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 text-stone-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input name="email" type="email" id="email" 
                               value="{{ old('email') }}"
                               class="w-full h-11 pl-10 pr-4 rounded-xl bg-stone-900/80 border border-amber-900/30 text-xs text-stone-100 placeholder-stone-500 focus:outline-none focus:border-amber-500/60 transition font-sans" 
                               placeholder="admin@gmail.com" required>
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-[10px] font-mono font-bold uppercase tracking-wider text-amber-400/90 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 text-stone-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input name="password" type="password" id="password" 
                               class="w-full h-11 pl-10 pr-11 rounded-xl bg-stone-900/80 border border-amber-900/30 text-xs text-stone-100 placeholder-stone-500 focus:outline-none focus:border-amber-500/60 transition font-sans" 
                               placeholder="••••••••" required>
                        <button type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-stone-500 hover:text-amber-300 transition" id="toggle-password">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        onclick="playWarmChime('tap')"
                        class="w-full h-12 mt-2 rounded-2xl bg-gradient-to-r from-amber-700 via-amber-600 to-amber-700 hover:shadow-glow-bronze text-amber-50 font-bold text-xs uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2 active:scale-[0.98] border border-amber-500/40 cursor-pointer shadow-lg">
                    <span>Masuk ke KETARA</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-amber-200"></i>
                </button>
            </form>

            <div class="mt-8 text-center border-t border-amber-900/30 pt-4">
                <span class="text-[11px] font-mono text-stone-500">KETARA PPKD Jakarta Pusat &copy; {{ date('Y') }}</span>
            </div>
        </div>
    </div>

    @include('inc.js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                });
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>