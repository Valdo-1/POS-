<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login POS</title>

    <!-- SEO Optimization -->
    <meta name="description" content="Login Screen - Point of Sales Restoran">
    <meta name="author" content="WEB_Aldo_POS Team">

    <base href="{{ asset('assets/assets') }}/">

    <!-- style -->
    @include('inc.css')
</head>

<body>

    <!-- ==========================================
         START: Authentication Container & Login Card
         ========================================== -->
    <div class="login-wrapper">
        <!-- Glowing background shapes for modern visual appearance -->
        <div class="login-bg-shape login-bg-shape-1"></div>
        <div class="login-bg-shape login-bg-shape-2"></div>

        <!-- Main centered login card -->
        <div class="login-card">

            <!-- Brand Identity -->
            <a href="#" class="login-brand text-decoration-none">
                <i class="bi bi-cup-hot-fill text-success"></i>
                <span>WEB_Aldo_POS</span>
            </a>

            <p class="login-subtitle">Point of Sales Resto di PPKD Jakarta Pusat</p>

            {{-- flash message sukses logout dsb --}}
            @if (session('success'))
            <div class="alert alert-success mb-3 py-2 small">
                {{ session('success') }}
            </div>
            @endif

            {{-- notif kalau email/password salah --}}
            @if ($errors->any())
            <div class="alert alert-danger mb-3 py-2 small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('action-login') }}" method="POST" id="loginForm" class="needs-validation" novalidate>
                @csrf
                {{-- inputan email login --}}
                <div class="login-form-group">
                    <label for="email" class="login-form-label">Email Address</label>
                    <div class="login-input-group">
                        <i class="bi bi-envelope input-icon"></i>
                        <input name="email" type="email" id="email" class="login-input @error('email') is-invalid @enderror" placeholder="name@company.com" required>
                    </div>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Input Group -->
                <div class="login-form-group">
                    <label for="password" class="login-form-label">Password</label>
                    <div class="login-input-group">
                        <i class="bi bi-shield-lock input-icon"></i>
                        <input name="password" type="password" id="password" class="login-input login-input-password @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        <button type="button" class="password-toggle-btn" id="toggle-password" aria-label="Show password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Options (Remember me & Forgot Password) -->
                <div class="login-options">
                    <label class="custom-control-label">
                        <input type="checkbox" class="custom-checkbox-input" id="rememberMe">
                        <span>Remember Me</span>
                    </label>
                    <a href="#" class="forgot-password-link">Forgot Password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login" id="btn-submit">
                    <span>Login</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
    <!-- END: Authentication Container -->
    @include('inc.js')
</body>

</html>