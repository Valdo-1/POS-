<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KETARA - Login Point of Sales</title>

    <meta name="description" content="KETARA - Sistem Informasi Point of Sales PPKD Jakarta Pusat">
    <meta name="author" content="KETARA Team">

    <base href="{{ asset('assets/assets') }}/">
    @include('inc.css')
</head>

<body class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden font-sans">
    
    <!-- Decorative background glow blobs -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Glassmorphism Container Card -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-emerald-500/20 rounded-3xl p-8 shadow-2xl shadow-emerald-950/50">
            
            <!-- Brand Identity -->
            <div class="text-center mb-6">
                <a href="#" class="inline-flex items-center justify-center gap-2 text-3xl font-black text-white tracking-wider mb-2">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-lg shadow-emerald-600/40">
                        <i class="bi bi-cup-hot-fill text-2xl"></i>
                    </div>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-300 via-white to-teal-200">KETARA</span>
                </a>
                <p class="text-emerald-300/80 text-sm font-medium">Point of Sales Resto PPKD Jakarta Pusat</p>
            </div>

            <!-- Flash Alert Success -->
            @if (session('success'))
            <div class="mb-5 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-emerald-400 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Flash Alert Error -->
            @if ($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <i class="bi bi-exclamation-triangle-fill text-rose-400"></i>
                    <span>Gagal Masuk</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs text-rose-200/90">
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
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-400/70">
                            <i class="bi bi-envelope text-lg"></i>
                        </div>
                        <input name="email" type="email" id="email" 
                               value="{{ old('email') }}"
                               class="w-full pl-11 pr-4 py-3 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-400 text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 transition-all duration-200" 
                               placeholder="admin@gmail.com" required>
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-400/70">
                            <i class="bi bi-shield-lock text-lg"></i>
                        </div>
                        <input name="password" type="password" id="password" 
                               class="w-full pl-11 pr-11 py-3 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-400 text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 transition-all duration-200" 
                               placeholder="••••••••" required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition-colors" id="toggle-password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 hover:shadow-emerald-500/50 flex items-center justify-center gap-2 transition-all duration-200 cursor-pointer text-sm">
                    <span>Masuk ke KETARA</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="mt-6 text-center border-t border-slate-800 pt-4">
                <span class="text-xs text-slate-400">Point of Sales PPKD Jakarta Pusat &copy; {{ date('Y') }}</span>
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
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
                    }
                });
            }
        });
    </script>
</body>

</html>