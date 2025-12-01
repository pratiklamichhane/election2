<x-guest-layout>
    <!-- Left Sidebar -->
    <div class="auth-sidebar">
        <h2>Join VoteSecure</h2>
        <p>Register now to participate in democratic elections and make your voice heard.</p>
        <ul class="feature-list">
            <li>
                <i class="bi bi-person-check"></i>
                <span>Secure KYC Verification</span>
            </li>
            <li>
                <i class="bi bi-shield-lock"></i>
                <span>Protected Personal Data</span>
            </li>
            <li>
                <i class="bi bi-clock-history"></i>
                <span>Quick Approval Process</span>
            </li>
            <li>
                <i class="bi bi-check-circle"></i>
                <span>Vote in Multiple Elections</span>
            </li>
        </ul>
    </div>

    <!-- Right Content -->
    <div class="auth-content">
        <a href="/" class="auth-logo">
            <i class="bi bi-patch-check-fill"></i>
            <span>VoteSecure</span>
        </a>

        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Fill in your details to register as a voter</p>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <div class="input-with-icon">
                    <i class="bi bi-person"></i>
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Enter your full name" pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces">
                </div>
                <x-input-error :messages="$errors->get('name')" class="error-message" />
                <small class="form-text text-muted" style="font-size: 0.8rem; color: #64748b;">Only letters and spaces allowed</small>
            </div>

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-with-icon">
                    <i class="bi bi-envelope"></i>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Enter your email" style="text-transform: lowercase;">
                </div>
                <x-input-error :messages="$errors->get('email')" class="error-message" />
                <small class="form-text text-muted" style="font-size: 0.8rem; color: #64748b;">Use lowercase letters only</small>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <div class="input-with-icon">
                    <i class="bi bi-telephone"></i>
                    <input id="phone" class="form-control" type="text" name="phone" value="{{ old('phone') }}" required placeholder="Enter 10 digit phone number" pattern="[0-9]{10}" maxlength="10" title="Phone number must be exactly 10 digits">
                </div>
                <x-input-error :messages="$errors->get('phone')" class="error-message" />
                <small class="form-text text-muted" style="font-size: 0.8rem; color: #64748b;">Enter exactly 10 digits</small>
            </div>

            <!-- Nagrita Number -->
            <div class="form-group">
                <label for="nagrita_number" class="form-label">Nagarikta Number</label>
                <div class="input-with-icon">
                    <i class="bi bi-card-text"></i>
                    <input id="nagrita_number" class="form-control" type="text" name="nagrita_number" value="{{ old('nagrita_number') }}" required placeholder="Enter your citizenship number" pattern="[0-9]+" title="Citizenship number should only contain numbers">
                </div>
                <x-input-error :messages="$errors->get('nagrita_number')" class="error-message" />
                <small class="form-text text-muted" style="font-size: 0.8rem; color: #64748b;">Only numbers allowed</small>
            </div>

            <!-- Nagrita Front -->
            <div class="form-group">
                <label for="nagrita_front" class="form-label">Nagarikta Front Image</label>
                <input id="nagrita_front" class="form-control" type="file" name="nagrita_front" required accept="image/*">
                <x-input-error :messages="$errors->get('nagrita_front')" class="error-message" />
            </div>

            <!-- Nagrita Back -->
            <div class="form-group">
                <label for="nagrita_back" class="form-label">Nagarikta Back Image</label>
                <input id="nagrita_back" class="form-control" type="file" name="nagrita_back" required accept="image/*">
                <x-input-error :messages="$errors->get('nagrita_back')" class="error-message" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-with-icon" style="position: relative;">
                    <i class="bi bi-lock"></i>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Create a strong password" style="padding-right: 45px;">
                    <i class="bi bi-eye password-toggle" id="togglePassword" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; font-size: 1.1rem;"></i>
                </div>
                <x-input-error :messages="$errors->get('password')" class="error-message" />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-with-icon" style="position: relative;">
                    <i class="bi bi-lock-fill"></i>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter your password" style="padding-right: 45px;">
                    <i class="bi bi-eye password-toggle" id="togglePasswordConfirm" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; font-size: 1.1rem;"></i>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
            </div>

            <button type="submit" class="btn-primary">
                <i class="bi bi-person-plus me-2"></i>Create Account
            </button>

            <div class="text-center mt-3">
                <span class="text-muted">Already have an account? </span>
                <a class="auth-link" href="{{ route('login') }}">Sign in</a>
            </div>
        </form>
    </div>

    <script>
        // Name validation - only letters and spaces
        document.getElementById('name').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^A-Za-z\s]/g, '');
        });

        // Email validation - convert to lowercase
        document.getElementById('email').addEventListener('input', function(e) {
            this.value = this.value.toLowerCase();
        });

        // Phone validation - only digits, max 10
        document.getElementById('phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        // Nagarikta number validation - only numbers
        document.getElementById('nagrita_number').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const passwordField = document.getElementById('password_confirmation');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const nagrita = document.getElementById('nagrita_number').value;

            // Validate name - no digits
            if (/\d/.test(name)) {
                e.preventDefault();
                alert('Name should not contain any digits');
                return false;
            }

            // Validate email - no uppercase
            if (email !== email.toLowerCase()) {
                e.preventDefault();
                alert('Email should be in lowercase only');
                return false;
            }

            // Validate phone - exactly 10 digits
            if (phone.length !== 10 || !/^\d{10}$/.test(phone)) {
                e.preventDefault();
                alert('Phone number must be exactly 10 digits');
                return false;
            }

            // Validate nagarikta number - only numbers
            if (!/^\d+$/.test(nagrita)) {
                e.preventDefault();
                alert('Nagarikta number should only contain numbers');
                return false;
            }
        });
    </script>
</x-guest-layout>
