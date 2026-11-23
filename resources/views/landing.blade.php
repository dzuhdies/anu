<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badan Pusat Statistik Kota Malang</title>
    <meta name="description" content="Portal Resmi Pendaftaran, Seleksi Divisi, dan Presensi Magang / PKL Badan Pusat Statistik (BPS) Kota Malang.">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bps: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            200: '#bae0fd',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                            800: '#0c4a6e',
                            900: '#0b2f5b',
                            dark: '#081f3d'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#e2e8f0 0.8px, transparent 0.8px), radial-gradient(#e2e8f0 0.8px, #ffffff 0.8px);
            background-size: 32px 32px;
            background-position: 0 0, 16px 16px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #0b2f5b 0%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .floating-element {
            animation: floating 4s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }
        }
    </style>

    @include('partials.pwa')
</head>

<body class="bg-slate-50 text-slate-800 antialiased selection:bg-bps-500 selection:text-white">

    <!-- ================= NAVBAR ================= -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">

                <!-- Logo & Brand -->
                <a href="#beranda" class="flex items-center gap-3 group">
                    <img src="{{ asset('logo-bps-malang.png') }}" alt="Logo BPS Kota Malang" class="h-9 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105">
                </a>


                <!-- Auth Action Buttons -->
                <div class="hidden sm:flex items-center gap-3">
                    @auth
                    <a href="{{ $dashboardUrl ?? route('login') }}" class="px-4 py-2 rounded-xl bg-bps-900 hover:bg-black text-white text-xs font-bold shadow-sm transition flex items-center gap-2">
                        <i class="fa-solid fa-gauge text-xs"></i>
                        <span>Buka Portal ({{ Auth::user()->username }})</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" title="Keluar Akun" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-bold transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-bps-600 hover:bg-bps-700 text-white text-xs font-bold shadow-sm transition flex items-center gap-1.5 shadow-bps-500/20">
                        <i class="fa-solid fa-user-plus text-[11px]"></i>
                        <span>Daftar Magang</span>
                    </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex sm:hidden items-center gap-2">
                    @auth
                    <a href="{{ $dashboardUrl ?? route('login') }}" class="px-3 py-1.5 rounded-lg bg-bps-900 text-white text-[11px] font-bold">
                        Portal
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-[11px] font-bold">
                        Masuk
                    </a>
                    @endauth
                    <button type="button" id="mobileMenuBtn" onclick="toggleMobileMenu()" class="w-9 h-9 rounded-lg border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-slate-100 transition" aria-label="Toggle Menu">
                        <i id="mobileMenuIcon" class="fa-solid fa-bars text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div id="mobileMenu" class="hidden sm:hidden border-t border-slate-200 bg-white/95 backdrop-blur-md px-4 pt-3 pb-5 space-y-3 shadow-lg">
            <div class="pt-2 border-t border-slate-100 flex items-center gap-2">
                @auth
                <a href="{{ $dashboardUrl ?? route('login') }}" class="w-full py-2.5 rounded-xl bg-bps-900 text-white text-xs font-bold text-center">
                    Buka Portal ({{ Auth::user()->username }})
                </a>
                @else
                <a href="{{ route('login') }}" class="w-1/2 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold text-center">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="w-1/2 py-2.5 rounded-xl bg-bps-600 text-white text-xs font-bold text-center shadow-sm">
                    Daftar Magang
                </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- ================= HERO SECTION ================= -->
    <section id="beranda" class="hero-pattern pt-28 sm:pt-36 pb-16 sm:pb-24 border-b border-slate-200/80 relative overflow-hidden">
        <!-- Background Ambient Glow -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-tr from-bps-200/40 via-sky-100/30 to-transparent rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Hero Left: Text & Action -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">


                    <!-- Main Headline -->
                    <h1 class="mt-6 max-w-xl font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-gray-900 sm:text-5xl">
                        Mulai Karirmu dari
                        <span class="relative inline-block whitespace-nowrap text-orange-500">
                            Magang Terbaik
                            <svg class="absolute -bottom-2 left-0 w-full text-orange-300" viewBox="0 0 200 12" preserveAspectRatio="none" fill="none" aria-hidden="true">
                                <path d="M2 9C40 3 92 2 198 6" stroke="currentColor" stroke-width="5" stroke-linecap="round" />
                            </svg>
                        </span>
                        di sini.
                    </h1>


                    <!-- Subtitle -->
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Bergabunglah bersama <strong>Badan Pusat Statistik (BPS) Kota Malang</strong> untuk mengasah keterampilan pengolahan data, sensus, survei sektoral, dan visualisasi data dalam ekosistem profesional berbasis digital.
                    </p>

                </div>

                <!-- Hero Right: Interactive Visual Card Mockup -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md">

                        <!-- Floating Highlight Card 1: GPS Presensi -->
                        <div class="floating-element absolute -top-4 -left-4 sm:-left-6 z-20 glass-card border border-white/80 rounded-2xl p-3.5 shadow-xl flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-base shadow-sm">
                                <i class="fa-solid fa-laptop"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Skill Upgrade</p>
                                <p class="text-xs font-bold text-slate-900">Siap kerja hari pertama</p>
                            </div>
                        </div>

                        <!-- Floating Highlight Card 2: Kalender Kuota -->
                        <div class="floating-element absolute -bottom-6 -right-4 sm:-right-6 z-20 glass-card border border-white/80 rounded-2xl p-3.5 shadow-xl flex items-center gap-3" style="animation-delay: 2s;">
                            <div class="w-10 h-10 rounded-xl bg-bps-600 text-white flex items-center justify-center text-base shadow-sm">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Portofolio</p>
                                <p class="text-xs font-bold text-slate-900">Hasil karya di portofolio</p>
                            </div>
                        </div>

                        <div class="w-full h-85 sm:h-85 rounded-2xl overflow-hidden shadow-lg">
                            <img src="{{ asset('logo-bps-malang.png') }}"
                                alt="Foto Magang BPS Kota Malang"
                                class="w-full h-full object-cover">
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= TENTANG PROGRAM ================= -->
    <section id="tentang" class="py-16 sm:py-24 bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center space-y-3 mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                    Mengenal Praktik Kerja di BPS Kota Malang
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Badan Pusat Statistik (BPS) Kota Malang membuka kesempatan bagi siswa SMK/SMA dan mahasiswa perguruan tinggi untuk mengimplementasikan ilmu akademik ke dalam dunia kerja nyata bidang statistik pemerintahan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2    sm:gap-10">

                <!-- Feature 1 -->
                <div class="p-6 rounded-2xl bg-slate-50/70 border border-slate-200/80 hover:border-bps-300 hover:shadow-md transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-bps-100 text-bps-700 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-2">Pengolahan & Analisis Data</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Terlibat langsung dalam verifikasi data, tabulasi statistik, validasi angka agregat, dan visualisasi indikator sosial ekonomi Kota Malang.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 rounded-2xl bg-slate-50/70 border border-slate-200/80 hover:border-bps-300 hover:shadow-md transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-2">Teknologi & Diseminasi Informasi</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Penerapan teknologi informasi, sistem basis data, pembuatan infografis publik, serta pengelolaan publikasi statistik resmi BPS.
                    </p>
                </div>


            </div>
        </div>
    </section>


    <!-- ================= ALUR PENDAFTARAN ================= -->
    <section id="alur" class="py-16 sm:py-24 bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center space-y-3 mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                    5 Langkah Mudah Untuk Memulai
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Alur pendaftaran transparan, serba digital, dan dapat diakses dari perangkat ponsel maupun desktop tanpa dokumen fisik yang rumit.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 relative">

                <!-- Step 1 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 relative group hover:border-slate-400 transition">
                    <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-black flex items-center justify-center text-sm shadow-xs">
                        1
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Registrasi Akun</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Buat akun menggunakan username dan email aktif pada halaman pendaftaran.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 relative group hover:border-slate-400 transition">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-black flex items-center justify-center text-sm shadow-xs">
                        2
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Lengkapi Profil & Berkas</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Isi nama lengkap, institusi/sekolah, jurusan, no HP, serta unggah CV & Portofolio.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 relative group hover:border-slate-400 transition">
                    <div class="w-10 h-10 rounded-xl bg-teal-600 text-white font-black flex items-center justify-center text-sm shadow-xs">
                        3
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Pilih Divisi & Tanggal</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Pilih divisi peminatan dan tentukan periode magang (perorangan maupun kelompok).
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 relative group hover:border-slate-400 transition">
                    <div class="w-10 h-10 rounded-xl bg-amber-600 text-white font-black flex items-center justify-center text-sm shadow-xs">
                        4
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Verifikasi & Konfirmasi</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Tim pembina BPS menyeleksi berkas. Status persetujuan / usulan revisi jadwal tampil di tab Status.
                    </p>
                </div>

                <!-- Step 5 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 relative group hover:border-slate-400 transition">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-black flex items-center justify-center text-sm shadow-xs">
                        5
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Pelaksanaan & Presensi</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Setelah disetujui, akun otomatis aktif sebagai siswa dan dapat langsung melakukan absen digital PWA.
                    </p>
                </div>

            </div>

            <!-- Button under steps -->
            <div class="mt-12 text-center">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-bps-600 hover:bg-bps-700 text-white font-bold text-xs shadow-md transition">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Mulai Pendaftaran Anda Sekarang</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ================= FAQ SECTION ================= -->
    <section id="faq" class="py-16 sm:py-24 bg-white border-b border-slate-200/80">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-3 mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-xs sm:text-sm text-slate-500">
                    Temukan jawaban atas pertanyaan umum terkait pendaftaran dan pelaksanaan magang di BPS Kota Malang.
                </p>
            </div>

            <!-- Accordion -->
            <div class="space-y-3" id="faqAccordion">

                <!-- Q1 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-50/50">
                    <button type="button" onclick="toggleFaq(1)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-slate-900 hover:bg-slate-50 transition">
                        <span>Siapa saja yang diperbolehkan mendaftar magang / PKL?</span>
                        <i id="faqIcon-1" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                    </button>
                    <div id="faqContent-1" class="hidden px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 bg-white pt-3">
                        Program terbuka untuk <strong>Siswa SMK</strong> (jurusan Rekayasa Perangkat Lunak, Multimedia, Administrasi Perkantoran, Akuntansi, dll.) serta <strong>Mahasiswa D3/D4/S1</strong> (Statistika, Matematika, Ilmu Komputer, Teknik Informatika, Sistem Informasi, Ekonomi, Ilmu Komunikasi, Manajemen, dll.).
                    </div>
                </div>

                <!-- Q2 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-50/50">
                    <button type="button" onclick="toggleFaq(2)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-slate-900 hover:bg-slate-50 transition">
                        <span>Apakah pendaftaran magang ini dipungut biaya?</span>
                        <i id="faqIcon-2" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                    </button>
                    <div id="faqContent-2" class="hidden px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 bg-white pt-3">
                        <strong>Tidak ada biaya sama sekali (100% Gratis).</strong> Seluruh proses pendaftaran, seleksi, pembinaan, hingga penerbitan sertifikat resmi BPS Kota Malang tidak memungut biaya apapun.
                    </div>
                </div>

                <!-- Q3 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-50/50">
                    <button type="button" onclick="toggleFaq(3)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-slate-900 hover:bg-slate-50 transition">
                        <span>Bisakah mendaftar secara berkelompok?</span>
                        <i id="faqIcon-3" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                    </button>
                    <div id="faqContent-3" class="hidden px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 bg-white pt-3">
                        <strong>Bisa!</strong> Satu orang ketua kelompok dapat memilih tipe pendaftaran <em>"Kelompok"</em> dan mendaftarkan rekan-rekannya hanya dengan memasukkan username akun mereka yang telah terdaftar pada sistem.
                    </div>
                </div>

                <!-- Q4 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-50/50">
                    <button type="button" onclick="toggleFaq(4)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-slate-900 hover:bg-slate-50 transition">
                        <span>Berapa lama durasi magang yang diizinkan?</span>
                        <i id="faqIcon-4" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                    </button>
                    <div id="faqContent-4" class="hidden px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 bg-white pt-3">
                        Durasi pelaksanaan umumnya berkisar antara <strong>1 bulan hingga 6 bulan</strong>, disesuaikan dengan kurikulum kampus / sekolah Anda serta ketersediaan kuota pada divisi yang dipilih.
                    </div>
                </div>

                <!-- Q5 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-50/50">
                    <button type="button" onclick="toggleFaq(5)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-slate-900 hover:bg-slate-50 transition">
                        <span>Bagaimana jika kuota pada divisi yang saya inginkan penuh?</span>
                        <i id="faqIcon-5" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                    </button>
                    <div id="faqContent-5" class="hidden px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 bg-white pt-3">
                        Anda dapat memilih divisi lain yang masih memiliki sisa kuota, atau menggeser tanggal pelaksanaan ke periode lain saat kuota divisi kembali tersedia.
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= CTA BANNER ================= -->
    <section class="py-16 sm:py-20 bg-gradient-to-br from-bps-900 via-slate-900 to-bps-dark text-white relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight">
                Siap Memulai Perjalanan Karir Statistik Anda?
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Segera daftarkan diri Anda sebelum kuota divisi terisi penuh. BPS Kota Malang siap menyambut kehadiran Anda untuk berkolaborasi dan belajar bersama.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                @auth
                <a href="{{ $dashboardUrl ?? route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-bps-500 hover:bg-bps-600 text-white font-bold text-xs sm:text-sm shadow-lg transition">
                    Buka Dashboard Saya
                </a>
                @else
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-bps-500 hover:bg-bps-600 text-white font-bold text-xs sm:text-sm shadow-lg shadow-bps-500/25 transition">
                    Daftar Magang Sekarang
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl border border-white/20 hover:bg-white/10 text-white font-bold text-xs sm:text-sm transition">
                    Masuk ke Akun
                </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ================= KONTAK & LOKASI ================= -->
    <section id="kontak" class="py-16 sm:py-20 bg-slate-50 border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

                <!-- Info Kontak -->
                <div class="space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-bps-700 bg-bps-100 px-3 py-1 rounded-full">
                        Lokasi & Kontak
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Badan Pusat Statistik Kota Malang
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Silakan hubungi atau kunjungi kantor kami pada hari dan jam operasional dinas untuk informasi lebih lanjut mengenai kegiatan magang.
                    </p>

                    <div class="space-y-3 pt-2 text-xs">
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-slate-200">
                            <i class="fa-solid fa-location-dot text-rose-600 text-sm mt-0.5 shrink-0"></i>
                            <div>
                                <strong class="text-slate-900 block font-bold">Alamat Kantor:</strong>
                                <span class="text-slate-600">Jl. Jend. A. Yani No. 5, Blimbing, Kec. Blimbing, Kota Malang, Jawa Timur 65126</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-slate-200">
                            <i class="fa-solid fa-clock text-blue-600 text-sm mt-0.5 shrink-0"></i>
                            <div>
                                <strong class="text-slate-900 block font-bold">Jam Layanan / Kerja:</strong>
                                <span class="text-slate-600">Senin &ndash; Jumat &bull; 08:00 &ndash; 16:00 WIB (Kecuali Hari Libur Nasional)</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-slate-200">
                            <i class="fa-solid fa-envelope text-teal-600 text-sm mt-0.5 shrink-0"></i>
                            <div>
                                <strong class="text-slate-900 block font-bold">Email & Website Resmi:</strong>
                                <span class="text-slate-600">bps3573@bps.go.id &bull; https://malangkota.bps.go.id</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Card Preview -->
                <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-md bg-white p-3 space-y-3">
                    <div class="h-64 sm:h-72 w-full rounded-2xl overflow-hidden relative">
                        <iframe
                            src="https://www.google.com/maps?q=Badan+Pusat+Statistik+Kota+Malang,+Jl.+Jend.+A.+Yani+No.+5,+Blimbing,+Kota+Malang&output=embed"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Peta Lokasi Kantor BPS Kota Malang">
                        </iframe>
                    </div>
                    <div class="flex items-center justify-between px-2 pt-1 text-xs">
                        <a href="https://maps.app.goo.gl/w1T9RHU9skxLTGLPA" target="_blank" class="text-bps-600 hover:text-bps-800 font-bold text-xs inline-flex items-center gap-1">
                            <span>Buka di Google Maps</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-slate-900 text-slate-400 text-xs py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                <!-- Col 1: Brand -->
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('logo-bps-malang.png') }}" alt="Logo BPS Kota Malang" class="h-9 w-auto object-contain brightness-0 invert">
                    </div>
                    <p class="text-[11px] text-slate-400 leading-relaxed max-w-sm">
                        Sistem informasi terintegrasi untuk pendaftaran peserta, seleksi divisi, jadwal magang, hingga presensi berbasis digital bagi peserta magang di BPS Kota Malang.
                    </p>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Tautan Pintas</h4>
                    <ul class="space-y-1.5 text-[11px]">
                        <li><a href="#beranda" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="#divisi" class="hover:text-white transition">Divisi & Kuota</a></li>
                        <li><a href="#alur" class="hover:text-white transition">Alur Pendaftaran</a></li>
                        <li><a href="#faq" class="hover:text-white transition">Pertanyaan Umum</a></li>
                    </ul>
                </div>

                <!-- Col 3: Akses Akun -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Portal Akses</h4>
                    <ul class="space-y-1.5 text-[11px]">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk ke Akun</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Registrasi Akun Baru</a></li>
                        <li><a href="https://malangkota.bps.go.id" target="_blank" class="hover:text-white transition">Website Resmi BPS Kota Malang</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#beranda" id="backToTopBtn" class="fixed bottom-6 right-6 w-10 h-10 rounded-full bg-slate-900 text-white shadow-lg flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 hover:bg-bps-600 z-40" aria-label="Kembali ke atas">
        <i class="fa-solid fa-arrow-up text-xs"></i>
    </a>

    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('mobileMenuIcon');
            menu.classList.toggle('hidden');
            if (menu.classList.contains('hidden')) {
                icon.className = 'fa-solid fa-bars text-sm';
            } else {
                icon.className = 'fa-solid fa-xmark text-sm';
            }
        }

        function toggleFaq(id) {
            const content = document.getElementById(`faqContent-${id}`);
            const icon = document.getElementById(`faqIcon-${id}`);
            const isOpen = !content.classList.contains('hidden');

            // Close all
            document.querySelectorAll('[id^="faqContent-"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="faqIcon-"]').forEach(el => el.style.transform = 'rotate(0deg)');

            if (!isOpen) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // Back to top observer
        window.addEventListener('scroll', () => {
            const btn = document.getElementById('backToTopBtn');
            if (window.scrollY > 400) {
                btn.classList.remove('opacity-0', 'pointer-events-none');
                btn.classList.add('opacity-100');
            } else {
                btn.classList.add('opacity-0', 'pointer-events-none');
                btn.classList.remove('opacity-100');
            }
        });
    </script>
</body>

</html>