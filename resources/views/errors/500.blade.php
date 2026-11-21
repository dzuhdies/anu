<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server | BPS Kota Malang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
    @include('partials.pwa')
</head>
<body class="bg-[#fafafa] min-h-screen flex flex-col justify-between items-center p-4 sm:p-6 text-gray-800 antialiased">

    <!-- Top Bar -->
    <header class="w-full max-w-lg flex items-center justify-center pt-4">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 transition hover:opacity-90">
            <img src="{{ asset('logo-bps-malang.png') }}" alt="BPS Kota Malang" class="h-9 sm:h-10 w-auto object-contain">
        </a>
    </header>

    <!-- Main Card -->
    <main class="w-full max-w-md my-auto py-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 text-center space-y-5">
            <!-- Icon Badge -->
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center mx-auto text-2xl shadow-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <!-- Error Code & Title -->
            <div class="space-y-1">
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">Error 500</span>
                <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">Kesalahan Server</h1>
                <p class="text-xs sm:text-sm text-gray-500 leading-relaxed pt-2">
                    {{ $exception && $exception->getMessage() ? $exception->getMessage() : 'Maaf, terjadi kendala saat memproses permintaan di server. Tim administrator telah diberitahu dan sedang menanganinya.' }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="pt-2 space-y-2.5">
                <button type="button" onclick="window.location.reload()" class="w-full inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white text-xs font-semibold py-2.5 px-4 rounded-xl shadow-sm transition active:scale-[0.98]">
                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                    <span>Coba Muat Ulang</span>
                </button>

                <button type="button" onclick="goBack()" class="w-full inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold py-2.5 px-4 rounded-xl transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali ke Halaman Sebelumnya</span>
                </button>

                <a href="{{ url('/') }}" class="w-full inline-flex items-center justify-center gap-2 text-xs text-gray-400 hover:text-gray-700 font-medium py-1 transition">
                    <i class="fa-solid fa-house"></i>
                    <span>Ke Halaman Utama</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-lg text-center pb-4 text-[11px] text-gray-400">
        <p>&copy; {{ date('Y') }} Badan Pusat Statistik Kota Malang</p>
    </footer>

    <script>
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = "{{ url('/') }}";
            }
        }
    </script>
</body>
</html>
