<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bangun Karir Data Anda - Magang BPS Kota Malang</title>
    <meta name="description" content="Bergabunglah dengan program magang eksklusif di BPS Kota Malang. Dapatkan pengalaman nyata mengolah data statistik nasional dan bangun portofolio profesional Anda.">

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); }
        .hero-bg { background: radial-gradient(circle at top right, #f8fafc, #ffffff); }
    </style>
    @include('partials.pwa')
</head>

<body class="bg-slate-50 text-slate-800 antialiased selection:bg-bps-600 selection:text-white">

    <!-- ================= NAVBAR ================= -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <a href="#beranda" class="flex items-center gap-3">
                    <img src="{{ asset('logo-bps-malang.png') }}" alt="Logo BPS Kota Malang" class="h-8 sm:h-10 w-auto object-contain">
                </a>

                <nav class="hidden lg:flex items-center gap-8 text-xs font-bold text-slate-600 tracking-wide uppercase">
                    <a href="#beranda" class="hover:text-bps-900 transition underline-offset-8 hover:underline">Beranda</a>
                    <a href="#keunggulan" class="hover:text-bps-900 transition underline-offset-8 hover:underline">Mengapa BPS?</a>
                    <a href="#divisi" class="hover:text-bps-900 transition underline-offset-8 hover:underline">Divisi</a>
                    <a href="#alur" class="hover:text-bps-900 transition underline-offset-8 hover:underline">Alur Daftar</a>
                    <a href="#testimoni" class="hover:text-bps-900 transition underline-offset-8 hover:underline">Kesan Magang</a>
                </nav>

                <div class="hidden sm:flex items-center gap-3">
                    @auth
                    <a href="{{ $dashboardUrl ?? route('login') }}" class="px-6 py-3 rounded-2xl bg-bps-900 hover:bg-black text-white text-sm font-bold shadow-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Buka Dashboard</span>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-bps-900 text-sm font-bold px-4">Masuk</a>
                    <a href="{{ route('register') }}" class="px-6 py-3 rounded-2xl bg-bps-700 hover:bg-bps-800 text-white text-sm font-bold shadow-xl shadow-bps-700/20 transition active:scale-95">
                        Daftar Sekarang
                    </a>
                    @endauth
                </div>

                <button onclick="toggleMobileMenu()" class="lg:hidden w-12 h-12 flex items-center justify-center rounded-2xl border border-slate-200 text-lg">
                    <i id="mobileMenuIcon" class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
        <div id="mobileMenu" class="hidden lg:hidden border-t border-slate-200 bg-white px-6 py-8 space-y-6">
            <nav class="flex flex-col gap-6 text-base font-bold text-slate-600 uppercase">
                <a href="#beranda" onclick="toggleMobileMenu()">Beranda</a>
                <a href="#keunggulan" onclick="toggleMobileMenu()">Mengapa BPS?</a>
                <a href="#divisi" onclick="toggleMobileMenu()">Pilihan Divisi</a>
                <a href="#alur" onclick="toggleMobileMenu()">Cara Daftar</a>
            </nav>
            <div class="pt-6 border-t border-slate-100">
                <a href="{{ route('register') }}" class="block w-full py-4 rounded-2xl bg-bps-700 text-white text-center font-black text-base shadow-lg">Daftar Magang Baru</a>
            </div>
        </div>
    </header>

    <!-- ================= HERO SECTION ================= -->
    <section id="beranda" class="pt-28 sm:pt-40 pb-20 hero-bg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-bps-50/40 -skew-x-12 translate-x-1/4 -z-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-8 text-center lg:text-left order-2 lg:order-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-bps-100 text-bps-800 text-xs font-extrabold uppercase tracking-widest animate-pulse">
                        🎯 Pendaftaran Batch 2024 Dibuka
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black text-slate-900 leading-[1.1] tracking-tight">
                        Wujudkan Karir <span class="text-bps-600">Berbasis Data</span> Disini.
                    </h1>
                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0 font-medium">
                        Dapatkan pengalaman profesional di Badan Pusat Statistik Kota Malang. Belajar dari para ahli, kerjakan proyek data riil, dan siapkan dirimu menjadi talenta masa depan.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-2xl bg-bps-700 hover:bg-bps-800 text-white font-black text-sm shadow-2xl shadow-bps-700/40 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                            Mulai Magang Sekarang
                            <i class="fa-solid fa-rocket"></i>
                        </a>
                        <a href="#keunggulan" class="px-8 py-4 rounded-2xl bg-white border-2 border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-50 transition flex items-center justify-center">
                            Pelajari Keuntungan
                        </a>
                    </div>
                    <div class="flex items-center justify-center lg:justify-start gap-4 pt-6">
                        <div class="flex -space-x-3">
                            <img class="w-12 h-12 rounded-full border-4 border-white shadow-md object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=200" alt="Intern 1">
                            <img class="w-12 h-12 rounded-full border-4 border-white shadow-md object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=200" alt="Intern 2">
                            <img class="w-12 h-12 rounded-full border-4 border-white shadow-md object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=200" alt="Intern 3">
                        </div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Bergabunglah dengan<br><span class="text-slate-900">Alumni Magang Sukses Kami</span></p>
                    </div>
                </div>
                <div class="lg:col-span-6 relative order-1 lg:order-2">
                    <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl transition-transform duration-700 hover:scale-[1.02]">
                        <img src="{{ asset('fotogedung.webp') }}" alt="Kolaborasi Magang" class="w-full h-64 sm:h-80 lg:h-[450px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-bps-900/40 via-transparent to-transparent"></div>
                        <div class="absolute bottom-8 left-8 right-8">
                            <!--<div class="glass-card p-6 rounded-3xl border border-white/50 shadow-2xl">
                                <p class="text-xs font-black text-bps-700 uppercase mb-1">Bangunan Kantor</p>
                                <p class="text-lg font-bold text-slate-900">BPS Kota Malang</p>
                            </div>-->
                        </div>
                    </div>
                    <!-- Decorative element -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-bps-100 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= MENGAPA HARUS DI BPS? ================= -->
    <section id="keunggulan" class="py-24 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="hidden lg:block order-2 lg:order-1 relative">
                    <img src="https://plus.unsplash.com/premium_photo-1673967831980-1d377baaded2?q=80&w=1287&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D" class="rounded-[2.5rem] shadow-2xl z-10 relative" alt="Mentoring Magang">
                    <div class="absolute -bottom-10 -right-10 bg-emerald-500 p-8 rounded-3xl text-white shadow-2xl z-20 hidden md:block">
                        <i class="fa-solid fa-quote-left text-3xl opacity-30 mb-4 block"></i>
                        <p class="text-lg font-bold leading-tight">"Mentornya sangat suportif dan profesional."</p>
                        <p class="text-sm font-medium mt-2 opacity-80">Rina, Alumni Batch 2023</p>
                    </div>
                </div>
                <div class="order-1 lg:order-2 space-y-8">
                    <div class="space-y-4">
                        <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">Pengalaman Kerja <br><span class="text-bps-600">Bukan Sekadar Admin.</span></h2>
                        <p class="text-slate-600 text-lg leading-relaxed font-medium">Kami tidak memberikan tugas remeh. Disini, Anda akan menjadi bagian dari tim profesional yang menangani data krusial Kota Malang.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 transition hover:bg-white hover:shadow-xl hover:border-bps-200 group">
                            <div class="w-12 h-12 rounded-2xl bg-bps-100 text-bps-700 flex items-center justify-center mb-4 group-hover:bg-bps-700 group-hover:text-white transition">
                                <i class="fa-solid fa-chart-simple text-xl"></i>
                            </div>
                            <h4 class="font-bold text-slate-900 mb-2">Portfolio Data Real</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Gunakan data asli nasional untuk proyek akhir atau laporan magangmu.</p>
                        </div>
                        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 transition hover:bg-white hover:shadow-xl hover:border-bps-200 group">
                            <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center mb-4 group-hover:bg-teal-700 group-hover:text-white transition">
                                <i class="fa-solid fa-user-group text-xl"></i>
                            </div>
                            <h4 class="font-bold text-slate-900 mb-2">Networking Kuat</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Berinteraksi dengan para ahli statistik, developer, dan pimpinan instansi.</p>
                        </div>
                        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 transition hover:bg-white hover:shadow-xl hover:border-bps-200 group">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4 group-hover:bg-amber-700 group-hover:text-white transition">
                                <i class="fa-solid fa-laptop-file text-xl"></i>
                            </div>
                            <h4 class="font-bold text-slate-900 mb-2">Tools Modern</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Akses ke perangkat lunak statistik dan sistem informasi terbaru.</p>
                        </div>
                        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 transition hover:bg-white hover:shadow-xl hover:border-bps-200 group">
                            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center mb-4 group-hover:bg-rose-700 group-hover:text-white transition">
                                <i class="fa-solid fa-certificate text-xl"></i>
                            </div>
                            <h4 class="font-bold text-slate-900 mb-2">Sertifikat Resmi</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Validasi resmi dari instansi pemerintah untuk CV masa depanmu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= DIVISI SECTION ================= -->
    <section id="divisi" class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight">Temukan Area Fokusmu.</h2>
                <p class="text-slate-400 text-lg">Pilih penempatan divisi yang paling sesuai dengan passion dan jurusan studimu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($divisis as $divisi)
                <div class="group p-10 rounded-[2.5rem] bg-white/5 border border-white/10 backdrop-blur-sm hover:bg-white hover:shadow-[0_20px_50px_rgba(8,_112,_184,_0.2)] transition-all duration-500">
                    <div class="w-16 h-16 rounded-2xl bg-bps-700/20 text-bps-500 flex items-center justify-center mb-8 group-hover:bg-bps-700 group-hover:text-white transition-all duration-500 group-hover:rotate-6">
                        <i class="fa-solid fa-database text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-white group-hover:text-slate-900 mb-4 transition-colors">{{ $divisi->name }}</h3>
                    <p class="text-sm text-slate-400 group-hover:text-slate-600 leading-relaxed mb-8 transition-colors">{{ $divisi->description ?: 'Bentuk pengalaman teknis dan analisis data mendalam di pusat data statistik Kota Malang.' }}</p>
                    <div class="flex items-center justify-between pt-8 border-t border-white/10 group-hover:border-slate-100 transition-colors">
                        <span class="text-[10px] font-black uppercase tracking-widest text-bps-500">Kuota: {{ $divisi->quota }}</span>
                        <a href="{{ route('register') }}" class="text-white group-hover:text-bps-700 font-bold text-sm flex items-center gap-2">
                            Pilih <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ================= TESTIMONI ================= -->
    <section id="testimoni" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16 items-center">
                <div class="lg:col-span-1 space-y-6 text-center lg:text-left">
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight tracking-tight">Kesan Alumni <br>Magang Kami.</h2>
                    <p class="text-slate-500 font-medium">Bukan hanya kata kami, tapi kata mereka yang sudah membuktikannya.</p>
                    <div class="pt-4 flex justify-center lg:justify-start">
                        <div class="bg-bps-50 p-6 rounded-3xl border border-bps-100 inline-block text-center">
                            <p class="text-3xl font-black text-bps-700">4.9/5</p>
                            <p class="text-[10px] font-black uppercase text-bps-500 tracking-widest">Kepuasan Peserta</p>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl transition relative">
                            <i class="fa-solid fa-quote-right absolute top-8 right-8 text-4xl text-slate-200"></i>
                            <div class="flex text-amber-400 gap-1 mb-4"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                            <p class="text-sm font-bold text-slate-700 leading-relaxed mb-6">"Saya belajar bagaimana data mentah dari survei diolah menjadi kebijakan publik. Sangat membuka wawasan saya tentang dunia statistik pemerintah."</p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-slate-300 overflow-hidden"><img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&q=80&w=100" class="object-cover" alt="User"></div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Adinda Putri</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase">UB Malang</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 rounded-3xl bg-bps-900 text-white hover:shadow-xl transition relative">
                            <i class="fa-solid fa-quote-right absolute top-8 right-8 text-4xl text-bps-800"></i>
                            <div class="flex text-amber-400 gap-1 mb-4"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                            <p class="text-sm font-bold text-slate-100 leading-relaxed mb-6">"Dashboard portalnya keren, presensi digital memudahkan banget. Kita bisa fokus ke kerjaan tanpa ribet administrasi. Sangat modern untuk kantor pemerintah!"</p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-bps-800 overflow-hidden"><img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=100" class="object-cover" alt="User"></div>
                                <div>
                                    <p class="text-sm font-bold text-white">Fajar Sidiq</p>
                                    <p class="text-[10px] text-bps-300 font-bold uppercase">SMKN 4 Malang</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CTA SECTION ================= -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-[4rem] bg-bps-700 py-16 px-8 sm:px-24 text-center text-white relative overflow-hidden shadow-2xl">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/circuit-board.png')] opacity-10"></div>
                <div class="relative z-10 max-w-3xl mx-auto space-y-10">
                    <h2 class="text-4xl sm:text-6xl font-black tracking-tight leading-tight">Kursi Terbatas. <br>Amankan Posisimu Sekarang!</h2>
                    <p class="text-bps-100 text-lg sm:text-xl font-medium">Jangan lewatkan kesempatan emas untuk membangun karirmu di BPS Kota Malang batch ini.</p>
                    <div class="flex flex-col sm:flex-row gap-6 justify-center">
                        <a href="{{ route('register') }}" class="px-12 py-5 rounded-[2rem] bg-white text-bps-900 font-black text-sm hover:scale-105 active:scale-95 transition shadow-[0_15px_30px_rgba(255,255,255,0.3)]">Daftar Magang Baru</a>
                        <a href="#alur" class="px-12 py-5 rounded-[2rem] border-2 border-white/40 text-white font-bold text-sm hover:bg-white/10 transition">Lihat Alur Pendaftaran</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-white border-t border-slate-200 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-16 mb-20">
                <div class="md:col-span-6 space-y-8">
                    <img src="{{ asset('logo-bps-malang.png') }}" alt="Logo BPS" class="h-12">
                    <p class="text-sm text-slate-500 max-w-md leading-relaxed font-medium">BPS Kota Malang berkomitmen melahirkan talenta data berkualitas yang mampu bersaing di era industri 4.0 melalui program bimbingan magang profesional.</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-bps-600 hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-bps-600 hover:text-white transition"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-bps-600 hover:text-white transition"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
                <div class="md:col-span-3 space-y-6">
                    <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Navigasi Utama</h4>
                    <ul class="space-y-4 text-sm font-bold text-slate-700">
                        <li><a href="#beranda" class="hover:text-bps-600 transition">Beranda</a></li>
                        <li><a href="#keunggulan" class="hover:text-bps-600 transition">Mengapa Memilih Kami?</a></li>
                        <li><a href="#divisi" class="hover:text-bps-600 transition">Pilihan Divisi</a></li>
                        <li><a href="#testimoni" class="hover:text-bps-600 transition">Kesan Peserta</a></li>
                    </ul>
                </div>
                <div class="md:col-span-3 space-y-6">
                    <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Hubungi Kami</h4>
                    <div class="space-y-4">
                        <p class="text-sm text-slate-600 leading-relaxed">Jl. Jend. A. Yani No. 5, Blimbing, Kota Malang</p>
                        <p class="text-sm font-black text-bps-700">bps3573@bps.go.id</p>
                        <p class="text-sm font-black text-slate-900">+62 341 491325</p>
                    </div>
                </div>
            </div>
            <div class="pt-10 border-t border-slate-100 text-center">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">&copy; {{ date('Y') }} Badan Pusat Statistik Kota Malang. Terus Melayani dengan Data.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('mobileMenuIcon');
            menu.classList.toggle('hidden');
            icon.className = menu.classList.contains('hidden') ? 'fa-solid fa-bars' : 'fa-solid fa-xmark';
        }
    </script>
</body>
</html>
