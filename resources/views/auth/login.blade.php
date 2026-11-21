<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Magang BPS Kota Malang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
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
        
        <!-- Header Logo BPS Kota Malang -->
        <div class="text-center mb-7">
            <img src="{{ asset('logo-bps-malang.png') }}" alt="Badan Pusat Statistik Kota Malang" class="h-12 sm:h-14 w-auto mx-auto mb-4 object-contain">
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Portal Magang BPS</h1>
            <p class="text-xs text-gray-500 mt-1">Silakan masukkan akun Anda untuk melanjutkan</p>
        </div>

        @if($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-4 py-3 rounded-xl text-xs font-medium mb-6 shadow-xs flex items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label for="username" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Username atau Email
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 text-xs">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                        class="block w-full pl-9 pr-4 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:bg-white transition"
                        placeholder="Masukkan username atau email"
                        required autofocus>
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Password
                </label>
                <div class="password-container">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 text-xs">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password"
                        class="block w-full pl-9 pr-10 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:bg-white transition"
                        placeholder="Masukkan password"
                        required>
                    <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <label for="remember" class="flex cursor-pointer items-center gap-2 text-xs text-gray-600">
                    <input type="checkbox" id="remember" name="remember" value="1"
                        class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                        {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit"
                class="w-full py-3 px-4 rounded-xl shadow-md shadow-orange-200 font-bold text-xs text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition active:scale-95 flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-to-bracket text-xs"></i>
                <span>Masuk ke Akun</span>
            </button>

            <div class="pt-4 border-t border-gray-100 text-center text-xs text-gray-600">
                Belum memiliki akun calon peserta? 
                <a href="{{ route('register') }}" class="font-bold text-orange-600 hover:text-orange-700 hover:underline">
                    Daftar Akun Baru
                </a>
            </div>
        </form>

        <!-- Tombol Pasang Aplikasi PWA -->
        <div id="pwaInstallArea" class="hidden mt-5 pt-4 border-t border-gray-100 text-center">
            <button type="button" id="pwaInstallButton"
                class="w-full py-2.5 px-4 bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 rounded-xl font-semibold text-xs flex items-center justify-center gap-2 transition active:scale-95 shadow-xs">
                <i class="fa-solid fa-download text-xs"></i>
                <span>Pasang Aplikasi (Install App)</span>
            </button>
            <p class="text-[10px] text-gray-400 mt-1.5">Instal untuk akses cepat di layar utama HP</p>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
    </script>

</body>
</html>
