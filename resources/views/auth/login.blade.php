<x-guest-layout>
    <!-- Left Sidebar -->
    <div class="auth-sidebar">
        <h2>Welcome Back!</h2>
        <p>Login to access your voting dashboard and participate in active elections.</p>
        <ul class="feature-list">
            <li>
                <i class="bi bi-shield-check"></i>
                <span>Secure & Encrypted Login</span>
            </li>
            <li>
                <i class="bi bi-lightning-charge"></i>
                <span>Quick Access to Elections</span>
            </li>
            <li>
                <i class="bi bi-graph-up"></i>
                <span>Track Your Voting History</span>
            </li>
            <li>
                <i class="bi bi-bell"></i>
                <span>Real-time Election Updates</span>
            </li>
        </ul>
    </div>

    <!-- Right Content -->
    <div class="auth-content">
        <a href="/" class="auth-logo">
            <i class="bi bi-patch-check-fill"></i>
            <span>VoteSecure</span>
        </a>

        <h1 class="auth-title">Sign In</h1>
        <p class="auth-subtitle">Enter your credentials to access your account</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <x-auth-session-status class="mb-4" :status="session('error')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-with-icon">
                    <i class="bi bi-envelope"></i>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email">
                </div>
                <x-input-error :messages="$errors->get('email')" class="error-message" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-with-icon">
                    <i class="bi bi-lock"></i>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
                </div>
                <x-input-error :messages="$errors->get('password')" class="error-message" />
            </div>

            <!-- Remember Me -->
            <div class="form-check">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Remember me</label>
            </div>

            <button type="submit" class="btn-primary">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>

            <div class="text-center mt-3">
                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif
            </div>

            <div class="text-center mt-3">
                <span class="text-muted">Don't have an account? </span>
                <a class="auth-link" href="{{ route('register') }}">Register now</a>
            </div>
        </form>
    </div>
</x-guest-layout>
