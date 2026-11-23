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

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="username" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 text-xs">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                        class="block w-full pl-9 pr-4 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:bg-white transition"
                        placeholder="Masukkan username"
                        required autofocus>
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Digunakan untuk login ke sistem dan bergabung kelompok.</p>
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
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl shadow-md shadow-orange-200 font-bold text-xs text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition active:scale-95">
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
    </script>
</body>

</html>
