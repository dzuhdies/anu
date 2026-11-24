<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Magang & PKL - Badan Pusat Statistik Kota Malang</title>
    <meta name="description" content="Portal resmi pendaftaran, seleksi divisi, jadwal kegiatan, dan presensi digital Praktik Kerja Lapangan (PKL) / Magang di BPS Kota Malang.">

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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        button, a, input, select, textarea {
            touch-action: manipulation;
        }
    </style>

    @include('partials.pwa')
</head>

<body class="bg-slate-50 text-slate-800 antialiased selection:bg-bps-600 selection:text-white">

    <!-- ================= NAVBAR ================= -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">

                <!-- Logo & Brand -->
                <a href="#beranda" class="flex items-center gap-3">
                    <img src="{{ asset('logo-bps-malang.png') }}" alt="Logo BPS Kota Malang" class="h-8 sm:h-10 w-auto object-contain">
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-6 text-xs font-semibold text-slate-600">
                    <a href="#beranda" class="hover:text-bps-900 transition py-1">Beranda</a>
                    <a href="#tentang" class="hover:text-bps-900 transition py-1">Tentang</a>
                    <a href="#divisi" class="hover:text-bps-900 transition py-1">Divisi & Kuota</a>
                    <a href="#alur" class="hover:text-bps-900 transition py-1">Alur Pendaftaran</a>
                    <a href="#faq" class="hover:text-bps-900 transition py-1">FAQ</a>
                    <a href="#kontak" class="hover:text-bps-900 transition py-1">Kontak</a>
                </nav>

                <!-- Auth Action Buttons -->
                <div class="hidden sm:flex items-center gap-2.5">
                    @auth
                    <a href="{{ $dashboardUrl ?? route('login') }}" class="px-4 py-2 rounded-xl bg-bps-900 hover:bg-black text-white text-xs font-bold shadow-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-gauge text-xs"></i>
                        <span>Buka Portal ({{ Auth::user()->username }})</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                        @csrf
                        <button type="submit" title="Keluar Akun" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-700 hover:bg-slate-100 text-xs font-bold transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-bps-700 hover:bg-bps-800 text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-user-plus text-[11px]"></i>
                        <span>Daftar Magang</span>
                    </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex lg:hidden items-center gap-2">
                    @auth
                    <a href="{{ $dashboardUrl ?? route('login') }}" class="px-3 py-1.5 rounded-lg bg-bps-900 text-white text-[11px] font-bold">
                        Portal
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-[11px] font-bold">
                        Masuk
                    </a>
                    @endauth
                    <button type="button" id="mobileMenuBtn" onclick="toggleMobileMenu()" class="w-9 h-9 rounded-lg border border-slate-200 text-slate-700 flex items-center justify-center hover:bg-slate-100 transition" aria-label="Toggle Navigasi Menu">
                        <i id="mobileMenuIcon" class="fa-solid fa-bars text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div id="mobileMenu" class="hidden lg:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-5 space-y-3 shadow-lg">
            <nav class="flex flex-col space-y-2 text-xs font-semibold text-slate-700">
                <a href="#beranda" onclick="toggleMobileMenu()" class="py-2 px-3 rounded-lg hover:bg-slate-50">Beranda</a>
                <a href="#tentang" onclick="toggleMobileMenu()" class="py-2 px-3 rounded-lg hover:bg-slate-50">Tentang Program</a>
                <a href="#divisi" onclick="toggleMobileMenu()" class="py-2 px-3 rounded-lg hover:bg-slate-50">Divisi & Kuota</a>
                <a href="#alur" onclick="toggleMobileMenu()" class="py-2 px-3 rounded-lg hover:bg-slate-50">Alur Pendaftaran</a>
                <a href="#faq" onclick="toggleMobileMenu()" class="py-2 px-3 rounded-lg hover:bg-slate-50">Pertanyaan Umum (FAQ)</a>
                <a href="#kontak" onclick="toggleMobileMenu()" class="py-2 px-3 rounded-lg hover:bg-slate-50">Kontak & Lokasi</a>
            </nav>
            <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                @auth
                <a href="{{ $dashboardUrl ?? route('login') }}" class="w-full py-2.5 rounded-xl bg-bps-900 text-white text-xs font-bold text-center">
                    Buka Portal ({{ Auth::user()->username }})
                </a>
                @else
                <a href="{{ route('login') }}" class="w-1/2 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold text-center hover:bg-slate-50">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="w-1/2 py-2.5 rounded-xl bg-bps-700 text-white text-xs font-bold text-center shadow-xs hover:bg-bps-800">
                    Daftar Magang
                </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- ================= HERO SECTION ================= -->
    <section id="beranda" class="pt-24 sm:pt-32 pb-16 sm:pb-20 bg-gradient-to-b from-white via-slate-50 to-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">

                <!-- Hero Left: Text & Action -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-bps-50 border border-bps-200 text-bps-800 text-xs font-semibold">
                        <i class="fa-solid fa-building-columns text-[11px] text-bps-600"></i>
                        <span>Badan Pusat Statistik Kota Malang</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Praktik Kerja & Magang Statistik di BPS Kota Malang
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Portal resmi pendaftaran, seleksi divisi, jadwal kegiatan, dan presensi digital bagi siswa SMK dan mahasiswa untuk mengasah kompetensi analisis data dalam lingkungan kerja Badan Pusat Statistik.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 pt-2">
                        @auth
                        <a href="{{ $dashboardUrl ?? route('login') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-bps-900 hover:bg-black text-white text-xs sm:text-sm font-bold shadow-md transition flex items-center justify-center gap-2">
                            <span>Buka Portal Saya</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                        @else
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-bps-700 hover:bg-bps-800 text-white text-xs sm:text-sm font-bold shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-clipboard-check text-xs"></i>
                            <span>Daftar Magang Sekarang</span>
                        </a>
                        <a href="#divisi" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 hover:border-slate-400 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2">
                            <span>Lihat Divisi & Kuota</span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </a>
                        @endauth
                    </div>

                    <!-- Highlight Badges -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-4 border-t border-slate-200/80 text-left">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm shrink-0"></i>
                            <span class="text-xs text-slate-600 font-medium">100% Bebas Biaya</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-location-crosshairs text-bps-600 text-sm shrink-0"></i>
                            <span class="text-xs text-slate-600 font-medium">Presensi Digital PWA</span>
                        </div>
                        <div class="col-span-2 sm:col-span-1 flex items-center gap-2.5">
                            <i class="fa-solid fa-certificate text-amber-600 text-sm shrink-0"></i>
                            <span class="text-xs text-slate-600 font-medium">Sertifikat Resmi BPS</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Right: Authentic Portal Overview Card -->
                <div class="lg:col-span-5">
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-bps-100 text-bps-800 flex items-center justify-center font-bold text-xs">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-slate-900">Sistem Informasi Magang</h3>
                                    <p class="text-[10px] text-slate-400">BPS Kota Malang</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Pendaftaran Aktif
                            </span>
                        </div>

                        <!-- Info Grid -->
                        <div class="space-y-2.5 text-xs">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                <span class="text-slate-600 flex items-center gap-2">
                                    <i class="fa-solid fa-sitemap text-slate-400"></i>
                                    <span>Divisi Penempatan</span>
                                </span>
                                <strong class="text-slate-900 font-bold">{{ count($divisis) }} Divisi Tersedia</strong>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                <span class="text-slate-600 flex items-center gap-2">
                                    <i class="fa-solid fa-user-group text-slate-400"></i>
                                    <span>Tipe Pendaftaran</span>
                                </span>
                                <strong class="text-slate-900 font-bold">Individu & Kelompok</strong>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                <span class="text-slate-600 flex items-center gap-2">
                                    <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                                    <span>Pencatatan Harian</span>
                                </span>
                                <strong class="text-slate-900 font-bold">Logbook & Presensi GPS</strong>
                            </div>
                        </div>

                        <!-- Footer note -->
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                            <span>Kantor BPS Kota Malang</span>
                            <a href="#kontak" class="text-bps-600 hover:text-bps-800 font-semibold hover:underline">
                                Lihat Lokasi
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= TENTANG PROGRAM ================= -->
    <section id="tentang" class="py-16 sm:py-20 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center space-y-3 mb-12 sm:mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-bps-700 bg-bps-50 px-3 py-1 rounded-full border border-bps-200">
                    Tentang Program
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Pengalaman Praktik Kerja Statistik Pemerintahan
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Peserta magang di BPS Kota Malang dilibatkan secara langsung dalam alur kerja data statistik resmi, mulai dari pengumpulan data lapangan, entri, validasi, tabulasi, hingga penyusunan laporan visual.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Feature 1 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-bps-100 text-bps-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-table"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Pengolahan & Tabulasi Data</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Mempelajari verifikasi dan validasi data agregat, pembagian kode wilayah kerja statistik, dan penyusunan tabel statistik sektoral.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Sistem Informasi & Diseminasi</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Penerapan teknologi informasi, pengelolaan basis data rujukan, serta perancangan materi infografis publikasi statistik Kota Malang.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Pendampingan & Evaluasi</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Setiap peserta didampingi pembimbing lapangan dari pegawai BPS untuk memantau perkembangan logbook kegiatan harian dan laporan akhir.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= DIVISI & KUOTA MAGANG ================= -->
    <section id="divisi" class="py-16 sm:py-20 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center space-y-3 mb-12 sm:mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-bps-700 bg-bps-100 px-3 py-1 rounded-full">
                    Pilihan Divisi
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Divisi Penempatan & Kuota Penerimaan
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Pilih divisi sesuai dengan bidang studi dan minat keilmuan Anda. Setiap divisi memiliki kapasitas kuota untuk menjaga kualitas bimbingan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($divisis as $divisi)
                    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs hover:border-slate-300 transition flex flex-col justify-between space-y-3">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-sm font-bold text-slate-900">{{ $divisi->name }}</h3>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 shrink-0">
                                    Kuota: {{ $divisi->quota }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $divisi->description ?: 'Divisi teknis di lingkungan Badan Pusat Statistik Kota Malang.' }}
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-[11px] text-slate-400">Status: Aktif</span>
                            @auth
                            <a href="{{ route('pendaftaran.index') }}" class="text-bps-600 hover:text-bps-800 font-bold inline-flex items-center gap-1">
                                <span>Pilih Divisi</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                            @else
                            <a href="{{ route('register') }}" class="text-bps-600 hover:text-bps-800 font-bold inline-flex items-center gap-1">
                                <span>Daftar</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-slate-500 text-xs">
                        Data divisi sedang dalam pembaruan oleh administrator.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ================= ALUR PENDAFTARAN ================= -->
    <section id="alur" class="py-16 sm:py-20 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center space-y-3 mb-12 sm:mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-bps-700 bg-bps-50 px-3 py-1 rounded-full border border-bps-200">
                    Tata Cara
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Alur Pendaftaran Peserta Magang
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Proses pendaftaran dilaksanakan secara digital melalui portal, mulai dari pengisian biodata hingga persetujuan jadwal.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                <!-- Step 1 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center">
                        1
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Buat Akun</h3>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Daftarkan akun dengan username, email aktif, dan kata sandi pada halaman registrasi.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="w-8 h-8 rounded-lg bg-bps-700 text-white font-bold text-xs flex items-center justify-center">
                        2
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Lengkapi Berkas</h3>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Isi identitas diri, asal sekolah atau perguruan tinggi, jurusan, kontak, serta unggah CV dan portofolio.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="w-8 h-8 rounded-lg bg-teal-700 text-white font-bold text-xs flex items-center justify-center">
                        3
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Pilih Divisi & Jadwal</h3>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Tentukan divisi tujuan serta periode tanggal mulai hingga selesai (perorangan atau kelompok).
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-700 text-white font-bold text-xs flex items-center justify-center">
                        4
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Verifikasi Berkas</h3>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Tim BPS meninjau kelengkapan berkas dan kesesuaian kuota. Status konfirmasi dapat dipantau di portal.
                    </p>
                </div>

                <!-- Step 5 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-700 text-white font-bold text-xs flex items-center justify-center">
                        5
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Mulai Magang</h3>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Setelah disetujui, akun aktif untuk melakukan presensi kehadiran digital dan pengisian logbook.
                    </p>
                </div>

            </div>

            <!-- Button under steps -->
            <div class="mt-10 text-center">
                @auth
                <a href="{{ $dashboardUrl ?? route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-bps-900 hover:bg-black text-white font-bold text-xs shadow-sm transition">
                    <i class="fa-solid fa-gauge"></i>
                    <span>Masuk ke Dashboard Portal</span>
                </a>
                @else
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-bps-700 hover:bg-bps-800 text-white font-bold text-xs shadow-sm transition">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Mulai Pendaftaran Akun</span>
                </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ================= FAQ SECTION ================= -->
    <section id="faq" class="py-16 sm:py-20 bg-slate-50 border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-3 mb-12 sm:mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-bps-700 bg-bps-100 px-3 py-1 rounded-full">
                    Informasi
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-xs sm:text-sm text-slate-600">
                    Jawaban atas hal-hal yang sering ditanyakan seputar pelaksanaan magang di BPS Kota Malang.
                </p>
            </div>

            <!-- Accordion -->
            <div class="space-y-3" id="faqAccordion">

                <!-- Q1 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-2xs">
                    <button type="button" onclick="toggleFaq(1)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-slate-900 hover:bg-slate-50 transition" aria-expanded="false" id="faqBtn-1">
                        <span>Siapa saja yang dapat mendaftar magang / PKL?</span>
                        <i id="faqIcon-1" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                    </button>
                    <div id="faqContent-1" class="hidden px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 bg-white pt-3">
                        Program terbuka untuk <strong>Siswa SMK</strong> (jurusan Rekayasa Perangkat Lunak, Multimedia, Manajemen Perkantoran, Akuntansi, dll.) serta <strong>Mahasiswa D3/D4/S1</strong> (Statistika, Matematika, Ilmu Komputer, Sistem Informasi, Teknik Informatika, Ekonomi, Komunikasi, dan jurusan relevan lainnya).
                    </div>
                </div>

                <!-- Q2 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-2xs">
                    <button type="button" onclick="toggleFaq(2)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-slate-900 hover:bg-slate-50 transition" aria-expanded="false" id="faqBtn-2">
                        <span>Apakah pendaftaran magang ini dipungut biaya?</span>
                        <i id="faqIcon-2" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                    </button>
                    <div id="faqContent-2" class="hidden px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 bg-white pt-3">
                        <strong>Tidak ada biaya sama sekali (100% gratis).</strong> Seluruh tahapan pendaftaran, seleksi berkas, bimbingan, hingga penerbitan sertifikat selesai magang tidak memungut biaya apapun.
                    </div>
                </div>

                <!-- Q3 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-2xs">
                    <button type="button" onclick="toggleFaq(3)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-slate-900 hover:bg-slate-50 transition" aria-expanded="false" id="faqBtn-3">
                        <span>Bisakah mendaftar secara berkelompok dari kampus atau sekolah yang sama?</span>
                        <i id="faqIcon-3" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                    </button>
                    <div id="faqContent-3" class="hidden px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 bg-white pt-3">
                        <strong>Bisa.</strong> Ketua kelompok memilih opsi tipe pendaftaran <em>Kelompok</em>, kemudian memasukkan username anggota yang sudah terdaftar di sistem sehingga pengajuan diproses dalam satu berkas terpadu.
                    </div>
                </div>

                <!-- Q4 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-2xs">
                    <button type="button" onclick="toggleFaq(4)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-slate-900 hover:bg-slate-50 transition" aria-expanded="false" id="faqBtn-4">
                        <span>Berapa lama durasi magang yang diperbolehkan?</span>
                        <i id="faqIcon-4" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                    </button>
                    <div id="faqContent-4" class="hidden px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 bg-white pt-3">
                        Durasi pelaksanaan umumnya berkisar antara <strong>1 bulan hingga 6 bulan</strong>, menyesuaikan kalender akademik instansi pendidikan Anda serta ketersediaan kuota divisi.
                    </div>
                </div>

                <!-- Q5 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-2xs">
                    <button type="button" onclick="toggleFaq(5)" class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-xs sm:text-sm text-slate-900 hover:bg-slate-50 transition" aria-expanded="false" id="faqBtn-5">
                        <span>Bagaimana jika kuota divisi pada periode yang diinginkan sudah penuh?</span>
                        <i id="faqIcon-5" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                    </button>
                    <div id="faqContent-5" class="hidden px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 bg-white pt-3">
                        Anda dapat memilih divisi teknis lain yang masih memiliki kuota, atau menyesuaikan jadwal pelaksanaan ke tanggal lain saat kuota divisi kembali tersedia.
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= KONTAK & LOKASI ================= -->
    <section id="kontak" class="py-16 sm:py-20 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                <!-- Info Kontak -->
                <div class="space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-bps-700 bg-bps-50 px-3 py-1 rounded-full border border-bps-200">
                        Lokasi & Kontak
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Kantor BPS Kota Malang
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Kunjungi atau hubungi kantor kami pada hari dan jam kerja dinas untuk konfirmasi surat pengantar dan koordinasi pelaksanaan magang.
                    </p>

                    <div class="space-y-3 pt-2 text-xs">
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <i class="fa-solid fa-location-dot text-rose-600 text-sm mt-0.5 shrink-0"></i>
                            <div>
                                <strong class="text-slate-900 block font-bold">Alamat Kantor:</strong>
                                <span class="text-slate-600">Jl. Jenderal A. Yani No. 5, Blimbing, Kota Malang, Jawa Timur 65126</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <i class="fa-solid fa-clock text-bps-700 text-sm mt-0.5 shrink-0"></i>
                            <div>
                                <strong class="text-slate-900 block font-bold">Jam Kerja Dinas:</strong>
                                <span class="text-slate-600">Senin – Jumat • 08.00 – 16.00 WIB (Kecuali Hari Libur Nasional)</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <i class="fa-solid fa-envelope text-teal-700 text-sm mt-0.5 shrink-0"></i>
                            <div>
                                <strong class="text-slate-900 block font-bold">Email & Situs Resmi:</strong>
                                <span class="text-slate-600">bps3573@bps.go.id • <a href="https://malangkota.bps.go.id" target="_blank" rel="noopener noreferrer" class="text-bps-700 hover:underline">malangkota.bps.go.id</a></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Card Preview -->
                <div class="rounded-2xl overflow-hidden border border-slate-200 bg-white p-3 space-y-3 shadow-2xs">
                    <div class="h-64 sm:h-72 w-full rounded-xl overflow-hidden relative">
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
                        <span class="text-slate-500 text-[11px]">Kecamatan Blimbing, Kota Malang</span>
                        <a href="https://maps.app.goo.gl/w1T9RHU9skxLTGLPA" target="_blank" rel="noopener noreferrer" class="text-bps-700 hover:text-bps-900 font-bold inline-flex items-center gap-1">
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
                        <img src="{{ asset('logo-bps-malang.png') }}" alt="Logo BPS Kota Malang" class="h-8 w-auto object-contain brightness-0 invert">
                    </div>
                    <p class="text-[11px] text-slate-400 leading-relaxed max-w-sm">
                        Sistem informasi pendaftaran peserta, seleksi divisi, jadwal magang, rekap kehadiran digital, dan logbook kegiatan resmi Badan Pusat Statistik Kota Malang.
                    </p>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Tautan Halaman</h4>
                    <ul class="space-y-1.5 text-[11px]">
                        <li><a href="#beranda" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="#tentang" class="hover:text-white transition">Tentang Program</a></li>
                        <li><a href="#divisi" class="hover:text-white transition">Divisi & Kuota</a></li>
                        <li><a href="#alur" class="hover:text-white transition">Alur Pendaftaran</a></li>
                        <li><a href="#faq" class="hover:text-white transition">Pertanyaan Umum (FAQ)</a></li>
                        <li><a href="#kontak" class="hover:text-white transition">Kontak & Lokasi</a></li>
                    </ul>
                </div>

                <!-- Col 3: Akses Akun -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Akses Portal</h4>
                    <ul class="space-y-1.5 text-[11px]">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk ke Akun</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar Akun Baru</a></li>
                        <li><a href="https://malangkota.bps.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition">Situs Resmi BPS Kota Malang</a></li>
                    </ul>
                </div>

            </div>

            <div class="pt-6 border-t border-slate-800 text-[11px] text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-3">
                <span>&copy; {{ date('Y') }} Badan Pusat Statistik Kota Malang. Seluruh hak cipta dilindungi.</span>
                <span>Portal Magang & PKL Terpadu</span>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#beranda" id="backToTopBtn" class="fixed bottom-6 right-6 w-10 h-10 rounded-full bg-slate-900 text-white shadow-lg flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 hover:bg-bps-700 z-40" aria-label="Kembali ke atas">
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
            const btn = document.getElementById(`faqBtn-${id}`);
            const isOpen = !content.classList.contains('hidden');

            // Close all
            document.querySelectorAll('[id^="faqContent-"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="faqIcon-"]').forEach(el => el.style.transform = 'rotate(0deg)');
            document.querySelectorAll('[id^="faqBtn-"]').forEach(el => el.setAttribute('aria-expanded', 'false'));

            if (!isOpen) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                btn.setAttribute('aria-expanded', 'true');
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
