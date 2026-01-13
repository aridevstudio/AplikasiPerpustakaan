 // Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        // Form Validation
        const loginForm = document.getElementById('loginForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('usernameError');
        const passwordError = document.getElementById('passwordError');
        const loginBtn = document.getElementById('loginBtn');

        // Real-time validation
        usernameInput.addEventListener('input', function() {
            validateUsername();
        });

        usernameInput.addEventListener('blur', function() {
            validateUsername();
        });

        passwordInput.addEventListener('input', function() {
            validatePassword();
        });

        passwordInput.addEventListener('blur', function() {
            validatePassword();
        });

        function validateUsername() {
            const value = usernameInput.value.trim();
            
            if (value === '') {
                usernameInput.classList.add('is-invalid');
                usernameInput.classList.remove('is-valid');
                usernameError.classList.add('show');
                usernameError.querySelector('span').textContent = 'Username wajib diisi';
                return false;
            } else if (value.length < 3) {
                usernameInput.classList.add('is-invalid');
                usernameInput.classList.remove('is-valid');
                usernameError.classList.add('show');
                usernameError.querySelector('span').textContent = 'Username minimal 3 karakter';
                return false;
            } else {
                usernameInput.classList.remove('is-invalid');
                usernameInput.classList.add('is-valid');
                usernameError.classList.remove('show');
                return true;
            }
        }

        function validatePassword() {
            const value = passwordInput.value.trim();
            
            if (value === '') {
                passwordInput.classList.add('is-invalid');
                passwordInput.classList.remove('is-valid');
                passwordError.classList.add('show');
                passwordError.querySelector('span').textContent = 'Password wajib diisi';
                return false;
            } else if (value.length < 6) {
                passwordInput.classList.add('is-invalid');
                passwordInput.classList.remove('is-valid');
                passwordError.classList.add('show');
                passwordError.querySelector('span').textContent = 'Password minimal 6 karakter';
                return false;
            } else {
                passwordInput.classList.remove('is-invalid');
                passwordInput.classList.add('is-valid');
                passwordError.classList.remove('show');
                return true;
            }
        }

        // Form Submit
        loginForm.addEventListener('submit', function(e) {
            const isUsernameValid = validateUsername();
            const isPasswordValid = validatePassword();

            if (!isUsernameValid || !isPasswordValid) {
                e.preventDefault();
                
                // Focus on first invalid field
                if (!isUsernameValid) {
                    usernameInput.focus();
                } else if (!isPasswordValid) {
                    passwordInput.focus();
                }
            } else {
                // Add loading state
                loginBtn.classList.add('loading');
                loginBtn.disabled = true;
            }
        });

        // Prevent multiple submissions
        let isSubmitting = false;
        loginForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            
            if (validateUsername() && validatePassword()) {
                isSubmitting = true;
            }
        });