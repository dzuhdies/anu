<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Calon Peserta Magang BPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9CA3AF;
        }
    </style>
    @include('partials.pwa')
</head>

<body class="bg-gradient-to-br from-orange-50/40 via-gray-50 to-orange-50/30 min-h-screen text-gray-800 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white p-7 sm:p-9 rounded-2xl shadow-xl border border-gray-100">
        <div class="mb-4">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-700 font-medium transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
        
        <div class="text-center mb-6">
            <img src="{{ asset('logo-bps-malang.png') }}" alt="Badan Pusat Statistik Kota Malang" class="h-12 sm:h-14 w-auto mx-auto mb-4 object-contain">
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Registrasi Calon Peserta</h1>
            <p class="text-xs text-gray-500 mt-1">Buat akun untuk mendaftar magang di BPS Kota Malang</p>
        </div>

        @if($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-4 py-3 rounded-xl text-xs font-medium mb-5 shadow-xs space-y-1">
            <div class="font-bold flex items-center gap-1.5">
                <i class="fas fa-triangle-exclamation"></i>
                <span>Periksa kembali data pendaftaran:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="registerForm" method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="username" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 text-xs">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                        class="block w-full pl-9 pr-9 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:bg-white transition"
                        placeholder="Masukkan username"
                        autocomplete="off"
                        required autofocus>
                    <span id="username-status-icon" class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs hidden">
                    </span>
                </div>
                <p id="username-status-msg" class="text-[11px] text-gray-400 mt-1">Digunakan untuk login ke sistem dan bergabung kelompok.</p>
            </div>

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Email Aktif</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 text-xs">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="block w-full pl-9 pr-4 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:bg-white transition"
                        placeholder="Example@gmail.com"
                        required>
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password"
                        class="block w-full px-4 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:bg-white transition"
                        placeholder="Minimal 6 karakter"
                        required>
                    <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password', this)"></i>
                </div>
                <p id="password-status-msg" class="text-[11px] mt-1 hidden"></p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Konfirmasi Password</label>
                <div class="password-container">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="block w-full px-4 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:bg-white transition"
                        placeholder="Ketik ulang password"
                        required>
                    <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password_confirmation', this)"></i>
                </div>
                <p id="confirm-status-msg" class="text-[11px] mt-1 hidden"></p>
            </div>

            <div class="pt-2">
                <button type="submit" id="submitBtn"
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl shadow-md shadow-orange-200 font-bold text-xs text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed">
                    <i class="fas fa-user-plus"></i>
                    <span>Daftar Akun Sekarang</span>
                </button>
            </div>

            <div class="pt-3 text-center text-xs text-gray-600 border-t border-gray-100">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-bold text-orange-600 hover:text-orange-700 hover:underline">
                    Masuk di sini
                </a>
            </div>
        </form>
    </div>

    <script>
        function togglePasswordVisibility(inputId, iconEl) {
            const input = document.getElementById(inputId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                iconEl.classList.remove('fa-eye');
                iconEl.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                iconEl.classList.remove('fa-eye-slash');
                iconEl.classList.add('fa-eye');
            }
        }

        // Real-time Validation States
        let isUsernameValid = false;
        let isUsernameChecking = false;
        let usernameDebounceTimer = null;

        const usernameInput = document.getElementById('username');
        const usernameIcon = document.getElementById('username-status-icon');
        const usernameMsg = document.getElementById('username-status-msg');

        const passwordInput = document.getElementById('password');
        const passwordMsg = document.getElementById('password-status-msg');

        const confirmInput = document.getElementById('password_confirmation');
        const confirmMsg = document.getElementById('confirm-status-msg');

        const defaultUsernameMsg = 'Digunakan untuk login ke sistem dan bergabung kelompok.';

        function setFieldState(inputEl, iconEl, msgEl, state, message) {
            // state: 'neutral', 'loading', 'valid', 'invalid', 'warning'
            inputEl.classList.remove(
                'border-rose-400', 'focus:ring-rose-500', 'focus:border-rose-500',
                'border-emerald-400', 'focus:ring-emerald-500', 'focus:border-emerald-500',
                'border-amber-400', 'focus:ring-amber-500', 'focus:border-amber-500',
                'border-gray-200'
            );

            if (state === 'neutral') {
                inputEl.classList.add('border-gray-200');
                if (iconEl) iconEl.classList.add('hidden');
                if (msgEl) {
                    msgEl.className = 'text-[11px] text-gray-400 mt-1';
                    msgEl.textContent = message || '';
                    if (!message) msgEl.classList.add('hidden');
                    else msgEl.classList.remove('hidden');
                }
            } else if (state === 'loading') {
                inputEl.classList.add('border-gray-200');
                if (iconEl) {
                    iconEl.classList.remove('hidden');
                    iconEl.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-orange-500"></i>';
                }
                if (msgEl) {
                    msgEl.className = 'text-[11px] text-gray-500 mt-1';
                    msgEl.textContent = message;
                    msgEl.classList.remove('hidden');
                }
            } else if (state === 'valid') {
                inputEl.classList.add('border-emerald-400', 'focus:ring-emerald-500', 'focus:border-emerald-500');
                if (iconEl) {
                    iconEl.classList.remove('hidden');
                    iconEl.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-500"></i>';
                }
                if (msgEl) {
                    msgEl.className = 'text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1';
                    msgEl.innerHTML = `<i class="fa-solid fa-check text-[10px]"></i> ${message}`;
                    msgEl.classList.remove('hidden');
                }
            } else if (state === 'invalid') {
                inputEl.classList.add('border-rose-400', 'focus:ring-rose-500', 'focus:border-rose-500');
                if (iconEl) {
                    iconEl.classList.remove('hidden');
                    iconEl.innerHTML = '<i class="fa-solid fa-circle-xmark text-rose-500"></i>';
                }
                if (msgEl) {
                    msgEl.className = 'text-[11px] text-rose-600 font-semibold mt-1 flex items-center gap-1';
                    msgEl.innerHTML = `<i class="fa-solid fa-xmark text-[10px]"></i> ${message}`;
                    msgEl.classList.remove('hidden');
                }
            } else if (state === 'warning') {
                inputEl.classList.add('border-amber-400', 'focus:ring-amber-500', 'focus:border-amber-500');
                if (iconEl) {
                    iconEl.classList.remove('hidden');
                    iconEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-amber-500"></i>';
                }
                if (msgEl) {
                    msgEl.className = 'text-[11px] text-amber-600 font-semibold mt-1 flex items-center gap-1';
                    msgEl.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-[10px]"></i> ${message}`;
                    msgEl.classList.remove('hidden');
                }
            }
        }

        // 1. Username Real-time Validation & Availability Check
        async function validateUsernameNow() {
            const raw = usernameInput.value;
            const val = raw.trim();

            if (!val) {
                isUsernameValid = false;
                setFieldState(usernameInput, usernameIcon, usernameMsg, 'neutral', defaultUsernameMsg);
                return;
            }

            if (val.length < 4) {
                isUsernameValid = false;
                setFieldState(usernameInput, usernameIcon, usernameMsg, 'invalid', 'Username minimal 4 karakter.');
                return;
            }

            if (val.length > 50) {
                isUsernameValid = false;
                setFieldState(usernameInput, usernameIcon, usernameMsg, 'invalid', 'Username maksimal 50 karakter.');
                return;
            }

            const alphaDashRegex = /^[A-Za-z0-9_-]+$/;
            if (!alphaDashRegex.test(val)) {
                isUsernameValid = false;
                setFieldState(usernameInput, usernameIcon, usernameMsg, 'invalid', 'Hanya boleh huruf, angka, tanda hubung (-), dan garis bawah (_).');
                return;
            }

            // Check availability with backend
            isUsernameChecking = true;
            setFieldState(usernameInput, usernameIcon, usernameMsg, 'loading', 'Memeriksa ketersediaan username...');

            try {
                const response = await fetch(`{{ route('register.check_username') }}?username=${encodeURIComponent(val)}`);
                const result = await response.json();

                // Pastikan input belum berubah saat response tiba
                if (usernameInput.value.trim() !== val) return;

                if (result.available) {
                    isUsernameValid = true;
                    setFieldState(usernameInput, usernameIcon, usernameMsg, 'valid', result.message || 'Username tersedia.');
                } else {
                    isUsernameValid = false;
                    setFieldState(usernameInput, usernameIcon, usernameMsg, 'invalid', result.message || 'Username sudah digunakan.');
                }
            } catch (err) {
                // If network fails, don't hard block
                isUsernameValid = true;
                setFieldState(usernameInput, usernameIcon, usernameMsg, 'neutral', defaultUsernameMsg);
            } finally {
                isUsernameChecking = false;
            }
        }

        usernameInput.addEventListener('input', function () {
            clearTimeout(usernameDebounceTimer);
            usernameDebounceTimer = setTimeout(validateUsernameNow, 350);
        });

        usernameInput.addEventListener('blur', function () {
            clearTimeout(usernameDebounceTimer);
            validateUsernameNow();
        });

        // 2. Password & Konfirmasi Password Real-time Check
        function validatePasswordPair() {
            const pwd = passwordInput.value;
            const conf = confirmInput.value;

            // Password check
            if (!pwd) {
                setFieldState(passwordInput, null, passwordMsg, 'neutral', '');
            } else if (pwd.length < 6) {
                setFieldState(passwordInput, null, passwordMsg, 'invalid', 'Password minimal 6 karakter.');
            } else {
                setFieldState(passwordInput, null, passwordMsg, 'valid', 'Password memenuhi syarat.');
            }

            // Confirmation check
            if (!conf) {
                setFieldState(confirmInput, null, confirmMsg, 'neutral', '');
                return;
            }

            if (conf !== pwd) {
                setFieldState(confirmInput, null, confirmMsg, 'invalid', 'Konfirmasi password tidak cocok.');
            } else {
                if (pwd.length < 6) {
                    setFieldState(confirmInput, null, confirmMsg, 'warning', 'Password cocok, tetapi masih kurang dari 6 karakter.');
                } else {
                    setFieldState(confirmInput, null, confirmMsg, 'valid', 'Konfirmasi password cocok.');
                }
            }
        }

        passwordInput.addEventListener('input', validatePasswordPair);
        confirmInput.addEventListener('input', validatePasswordPair);

        // 3. Form Submit Safeguard
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            const pwd = passwordInput.value;
            const conf = confirmInput.value;
            const userVal = usernameInput.value.trim();

            if (!userVal || userVal.length < 4 || !/^[A-Za-z0-9_-]+$/.test(userVal)) {
                e.preventDefault();
                validateUsernameNow();
                usernameInput.focus();
                return;
            }

            if (!isUsernameValid && !isUsernameChecking) {
                e.preventDefault();
                usernameInput.focus();
                return;
            }

            if (pwd.length < 6) {
                e.preventDefault();
                validatePasswordPair();
                passwordInput.focus();
                return;
            }

            if (conf !== pwd) {
                e.preventDefault();
                validatePasswordPair();
                confirmInput.focus();
                return;
            }
        });

        // Initial check if old values exist
        if (usernameInput.value.trim().length >= 4) {
            validateUsernameNow();
        }
        if (passwordInput.value || confirmInput.value) {
            validatePasswordPair();
        }
    </script>
</body>

</html>
