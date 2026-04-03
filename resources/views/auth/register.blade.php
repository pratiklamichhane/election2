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
                    <input id="nagrita_number" class="form-control" type="text" name="nagrita_number" value="{{ old('nagrita_number') }}" required placeholder="Enter your citizenship number" pattern="[0-9०-९\-/\s]+" title="Citizenship number should contain only digits, slash, or hyphen">
                </div>
                <x-input-error :messages="$errors->get('nagrita_number')" class="error-message" />
                <small class="form-text text-muted" style="font-size: 0.8rem; color: #64748b;">Digits, slash, and hyphen allowed</small>
            </div>

            <!-- Date of Birth -->
            <div class="form-group">
                <label for="date_of_birth" class="form-label">Date of Birth</label>
                <div class="input-with-icon">
                    <i class="bi bi-calendar-date"></i>
                    <input id="date_of_birth" class="form-control" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required max="{{ now()->toDateString() }}">
                </div>
                <x-input-error :messages="$errors->get('date_of_birth')" class="error-message" />
            </div>

            <!-- District -->
            <div class="form-group">
                <label for="district" class="form-label">District</label>
                <div class="input-with-icon">
                    <i class="bi bi-geo-alt"></i>
                    <input id="district" class="form-control" type="text" name="district" value="{{ old('district') }}" required placeholder="Enter your district">
                </div>
                <x-input-error :messages="$errors->get('district')" class="error-message" />
            </div>

            <!-- Ward Number -->
            <div class="form-group">
                <label for="ward_no" class="form-label">Ward Number</label>
                <div class="input-with-icon">
                    <i class="bi bi-123"></i>
                    <input id="ward_no" class="form-control" type="number" name="ward_no" value="{{ old('ward_no') }}" required min="1" max="35" placeholder="1 - 35">
                </div>
                <x-input-error :messages="$errors->get('ward_no')" class="error-message" />
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
            this.value = this.value.replace(/[^0-9०-९\-/\s]/g, '');
        });

        // District validation - letters and spaces only
        document.getElementById('district').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^A-Za-z\s]/g, '');
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
            const wardNo = document.getElementById('ward_no').value;
            const dob = document.getElementById('date_of_birth').value;

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

            // Validate nagarikta number
            if (!/^[0-9०-९\-/\s]+$/.test(nagrita)) {
                e.preventDefault();
                alert('Nagarikta number should only contain digits, slash, or hyphen');
                return false;
            }

            // Validate ward number
            if (!wardNo || Number(wardNo) < 1 || Number(wardNo) > 35) {
                e.preventDefault();
                alert('Ward number must be between 1 and 35');
                return false;
            }

            // Validate age is at least 18
            const birthDate = new Date(dob);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            if (!dob || age < 18) {
                e.preventDefault();
                alert('You must be at least 18 years old to register for voting');
                return false;
            }
        });
    </script>
</x-guest-layout>
