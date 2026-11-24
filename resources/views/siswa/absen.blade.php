<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Presensi Siswa Magang - BPS</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .hidden, [hidden] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        .card {
            background: #ffffff;
            border: 1px solid #f0f2f5;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        /* Pulse ring animation */
        @keyframes pulse-ring {
            0% {
                transform: scale(0.97);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.45);
            }
            70% {
                transform: scale(1.02);
                box-shadow: 0 0 0 16px rgba(16, 185, 129, 0);
            }
            100% {
                transform: scale(0.97);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        @keyframes pulse-ring-rose {
            0% {
                transform: scale(0.97);
                box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.45);
            }
            70% {
                transform: scale(1.02);
                box-shadow: 0 0 0 16px rgba(244, 63, 94, 0);
            }
            100% {
                transform: scale(0.97);
                box-shadow: 0 0 0 0 rgba(244, 63, 94, 0);
            }
        }

        .btn-pulse-emerald {
            animation: pulse-ring 2.4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .btn-pulse-rose {
            animation: pulse-ring-rose 2.4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
    @include('partials.pwa')
</head>

<body class="text-gray-800 min-h-screen p-0 lg:p-4 flex flex-col justify-center items-center">

    <!-- Container Utama Gaya Karniva (Responsive Mobile & Desktop) -->
    <div class="w-full max-w-6xl min-h-screen lg:min-h-[92vh] bg-white rounded-none lg:rounded-2xl border-0 lg:border border-gray-100 shadow-xl flex flex-col overflow-hidden">

        <!-- ================= TOP NAVBAR ================= -->
        <header class="h-16 border-b border-gray-100 px-4 sm:px-6 flex items-center justify-between shrink-0 bg-white z-20">
            <!-- Brand & Info Peserta -->
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('logo-bps-malang.png') }}" alt="BPS Kota Malang" class="h-8 max-h-9 w-auto object-contain">
                </a>
            </div>

            <!-- User Pill & Actions (Klik Profil untuk Membuka Detail Profil) -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Tombol Buka Profil Saya -->
                <button type="button" onclick="showProfileModal()"
                    class="flex items-center gap-2.5 p-1 sm:px-2.5 sm:py-1 rounded-xl hover:bg-gray-100 transition group text-left cursor-pointer border border-transparent hover:border-gray-200"
                    title="Klik untuk membuka profil saya">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-xs font-bold text-gray-900 group-hover:text-[#ff6b00] transition leading-tight">{{ $nama }}</span>
                        <span class="text-[10px] text-teal-600 font-semibold flex items-center gap-1 justify-end">
                            <i class="fa-solid fa-sitemap text-[9px]"></i>
                            {{ $siswaProfile->divisi->nama_divisi ?? ($siswaProfile->asal_pkl ?: 'Peserta Magang') }}
                        </span>
                    </div>

                    <div class="w-8 h-8 rounded-full bg-gray-900 group-hover:bg-[#ff6b00] text-white font-bold text-xs flex items-center justify-center shadow-xs transition">
                        {{ strtoupper(substr($nama, 0, 1)) }}
                    </div>
                </button>

                <!-- Tombol Logout -->
                <form method="POST" action="{{ route('logout') }}" class="m-0" onsubmit="showLoading('Keluar Akun...', 'Sedang memproses logout...', 'generic')">
                    @csrf
                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Keluar">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- ================= MAIN BODY CONTENT ================= -->
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 pb-28 sm:pb-32 bg-[#fbfcfd]">

            <!-- Flash Toast Alerts -->
            @if (session('success'))
                <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if (session('error') || $errors->any())
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                        <div>
                            @if(session('error')) <div>{{ session('error') }}</div> @endif
                            @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                        </div>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            <!-- Tab Container (Responsive Centered Layout) -->
            <div class="w-full max-w-2xl mx-auto">

                <!-- ================= TAB 1: HALAMAN PRESENSI HARI INI ================= -->
                <div id="view-absen" class="absen-view-tab space-y-5 animate-fadeIn">

                    <!-- Kartu Jam Presensi -->
                    <div class="text-center">
    <div
        id="liveClock"
        class="text-4xl sm:text-6xl font-bold text-gray-900 tracking-tight tabular-nums"
        style="font-family: 'JetBrains Mono';"
    >
        --:--:--
    </div>
</div>

                    <!-- Status Banner Izin WFH Aktif -->
                    @if($isWfhToday)
                        <div class="p-3.5 bg-teal-50 border border-teal-200 text-teal-900 rounded-2xl text-xs font-medium flex items-center gap-3 shadow-2xs">
                            <div class="w-9 h-9 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center shrink-0 text-sm">
                                <i class="fa-solid fa-house-laptop"></i>
                            </div>
                            <div class="text-left">
                                <span class="font-bold block text-teal-950 text-xs">Izin Work From Home Aktif</span>
                                <span class="text-[11px] text-teal-700 leading-tight block mt-0.5">Anda dapat melakukan presensi di luar area kantor BPS.</span>
                            </div>
                        </div>
                    @endif

                    <!-- Banner Warning Luar Radius & Belum Ada Izin WFH (Dikontrol dinamis via JS) -->
                    <div id="outsideRadiusWarningBanner" class="hidden p-3.5 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl text-xs font-medium flex items-start gap-3 shadow-2xs">
                        <div class="text-left">
                            <span class="font-bold block text-rose-950 text-xs">Presensi Dinonaktifkan</span>
                            <span id="outsideRadiusWarningText" class="text-[11px] text-rose-700 leading-relaxed block mt-0.5">
                                Anda berada di luar radius kantor BPS dan tidak memiliki izin WFH untuk hari ini. Tombol presensi tidak dapat digunakan.
                            </span>
                        </div> 
                    </div>

                    <!-- Kartu Aksi Utama Presensi (Tombol Absen Masuk / Pulang / Izin) -->
                    <div class="flex flex-col items-center justify-center text-center space-y-4">
                        @php
                            $sudahMasuk = $absensi && $absensi->jam_masuk;
                            $sudahPulang = $absensi && $absensi->jam_pulang;
                            $isIzin = $absensi && $absensi->izin;
                        @endphp

                        @if(!$sudahMasuk && !$isIzin)
                            <!-- Tombol Absen Masuk -->
                            <button type="button" id="btnClockInAction"
                                class="w-44 h-44 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 shadow-xl shadow-emerald-200 text-white flex flex-col items-center justify-center transform active:scale-95 transition-all duration-300 cursor-pointer group btn-pulse-emerald border-4 border-white">
                                <i class="fa-solid fa-fingerprint text-4xl mb-2 group-hover:scale-110 transition"></i>
                                <span class="text-base font-black tracking-wider">ABSEN MASUK</span>
                                <span class="text-[10px] text-emerald-100 mt-0.5 font-medium">Buka Kamera Selfie</span>
                            </button>
                            <p class="text-xs text-gray-500">
                                Pastikan Anda berada di area kantor dan mengaktifkan GPS
                            </p>

                        @elseif($sudahMasuk && !$sudahPulang)
                            <!-- Tombol Absen Pulang -->
                            <button type="button" id="btnClockOutAction"
                                class="w-44 h-44 rounded-full bg-gradient-to-tr from-rose-600 to-pink-500 shadow-xl shadow-rose-200 text-white flex flex-col items-center justify-center transform active:scale-95 transition-all duration-300 cursor-pointer group btn-pulse-rose border-4 border-white">
                                <i class="fa-solid fa-arrow-right-from-bracket text-4xl mb-2 group-hover:scale-110 transition"></i>
                                <span class="text-base font-black tracking-wider">ABSEN PULANG</span>
                                <span class="text-[10px] text-rose-100 mt-0.5 font-medium">Ambil Selfie Pulang</span>
                            </button>

                        @elseif($isIzin)
                            <!-- Status Sedang Izin -->
                            <div class="w-44 h-44 rounded-full bg-gradient-to-tr from-amber-400 to-yellow-500 shadow-xl shadow-amber-200 text-white flex flex-col items-center justify-center border-4 border-white p-4">
                                <i class="fa-solid fa-file-signature text-3xl mb-1"></i>
                                <span class="text-sm font-black tracking-wider">SEDANG IZIN</span>
                                <span class="text-[11px] text-amber-100 mt-1 line-clamp-2">{{ $absensi->jenisIzin->nama ?? 'Izin Tercatat' }}</span>
                            </div>
                            <p class="text-xs text-gray-500">
                                Permohonan izin Anda hari ini telah tersimpan dalam sistem.
                            </p>

                        @else
                            <!-- Presensi Selesai -->
                            <div class="w-44 h-44 rounded-full bg-gradient-to-tr from-gray-900 to-gray-800 shadow-xl text-white flex flex-col items-center justify-center border-4 border-white p-4">
                                <i class="fa-solid fa-circle-check text-4xl mb-1 text-emerald-400"></i>
                                <span class="text-sm font-black tracking-wider">PRESENSI LENGKAP</span>
                            </div>
                        @endif
                    </div>

                    <!-- Geofence & Jarak Lokasi GPS -->
                    <div class="card p-4 bg-white space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-gray-100 text-xs">
                            <span class="text-gray-400 font-semibold uppercase tracking-wider text-[10px]">Lokasi Gedung & GPS</span>
                            <span id="statusGedung" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                Memeriksa GPS...
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="truncate">
                                    <h4 class="text-xs font-bold text-gray-900 truncate">{{ $gedung->nama ?? 'Kantor BPS' }}</h4>
                                    <p class="text-[11px] text-gray-500">
                                        Jarak: <strong id="distance" class="text-gray-800">Mencari sinyal GPS...</strong>
                                    </p>
                                </div>
                            </div>
                            <button type="button" id="btnBukaPeta" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 text-xs font-medium hover:bg-gray-50 transition flex items-center gap-1.5 shrink-0" title="Buka Titik Peta">
                                <i class="fa-solid fa-map-location-dot text-blue-600"></i>
                                <span>Peta</span>
                            </button>
                        </div>
                    </div>

                    <!-- Ringkasan Status Presensi Hari Ini -->
                    <div class="card p-4 bg-white space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-gray-100 text-xs">
                            <span class="text-gray-400 font-semibold uppercase tracking-wider text-[10px]">Ringkasan Hari Ini</span>
                            @if($absensi)
                                @if($absensi->izin)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200">Izin</span>
                                @elseif($absensi->status_ketepatan == 'Terlambat')
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-800 border border-rose-200">Terlambat</span>
                                @else
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">Tepat Waktu</span>
                                @endif
                            @else
                                <span class="text-[10px] font-medium text-gray-400">Belum Presensi</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-center">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-[10px] text-gray-400 font-semibold uppercase block">Masuk</span>
                                <strong class="text-sm font-bold text-gray-900 mt-0.5 block font-mono">
                                    {{ ($absensi && $absensi->jam_masuk) ? substr($absensi->jam_masuk, 0, 5) : '--:--' }}
                                </strong>
                                <span class="text-[9px] text-gray-400">WIB</span>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-[10px] text-gray-400 font-semibold uppercase block">Pulang</span>
                                <strong class="text-sm font-bold text-gray-900 mt-0.5 block font-mono">
                                    {{ ($absensi && $absensi->jam_pulang) ? substr($absensi->jam_pulang, 0, 5) : '--:--' }}
                                </strong>
                                <span class="text-[9px] text-gray-400">WIB</span>
                            </div>
                        </div>

                        <!-- Pratinjau Foto Selfie Jika Sudah Absen -->
                        @if($absensi && ($absensi->foto_masuk_url || $absensi->foto_keluar_url))
                            <div class="pt-2 border-t border-gray-100">
                                <span class="text-[10px] text-gray-400 uppercase font-semibold block mb-2">Bukti Verifikasi Kamera</span>
                                <div class="grid grid-cols-2 gap-2">
                                    @if($absensi->foto_masuk_url)
                                        <div class="p-2 rounded-lg border border-gray-100 bg-gray-50 flex items-center gap-2 cursor-pointer hover:bg-gray-100 transition" onclick="showImageModal('{{ $absensi->foto_masuk_url }}')">
                                            <img src="{{ $absensi->foto_masuk_url }}" alt="Selfie Masuk" class="w-9 h-9 rounded-md object-cover bg-gray-200 shrink-0">
                                            <div class="truncate">
                                                <span class="text-[11px] font-bold text-gray-800 block truncate">Foto Masuk</span>
                                                <span class="text-[10px] text-emerald-600 font-semibold">Terkonfirmasi</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if($absensi->foto_keluar_url)
                                        <div class="p-2 rounded-lg border border-gray-100 bg-gray-50 flex items-center gap-2 cursor-pointer hover:bg-gray-100 transition" onclick="showImageModal('{{ $absensi->foto_keluar_url }}')">
                                            <img src="{{ $absensi->foto_keluar_url }}" alt="Selfie Pulang" class="w-9 h-9 rounded-md object-cover bg-gray-200 shrink-0">
                                            <div class="truncate">
                                                <span class="text-[11px] font-bold text-gray-800 block truncate">Foto Pulang</span>
                                                <span class="text-[10px] text-blue-600 font-semibold">Terkonfirmasi</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Tombol Ajukan Izin Jika Belum Masuk -->
                        @if(!$absensi || (!$absensi->jam_masuk && !$absensi->izin))
                            <div class="pt-2">
                                <button type="button" onclick="showIzinModal()"
                                    class="w-full py-2.5 px-4 rounded-xl border border-amber-200 bg-amber-50/60 hover:bg-amber-100 text-amber-800 text-xs font-semibold transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-circle-question text-amber-600"></i>
                                    <span>Tidak Masuk Hari Ini? Ajukan Izin / Sakit</span>
                                </button>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- ================= TAB 2: RIWAYAT PRESENSI & KALENDER ================= -->
                <div id="view-riwayat" class="absen-view-tab space-y-5 animate-fadeIn" style="display: none;">

                    <!-- Kartu Riwayat Kalender Bulanan -->
                    <div class="card p-5 bg-white space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">Riwayat Kehadiran Bulanan</h3>
                                    <p class="text-xs text-gray-400">Klik tanggal pada kalender untuk melihat detail jam dan bukti selfie</p>
                                </div>
                            </div>
                            <button type="button" onclick="refreshRiwayatData()" class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-500 hover:text-black flex items-center justify-center transition" title="Muat Ulang">
                                <i class="fa-solid fa-arrows-rotate text-xs"></i>
                            </button>
                        </div>

                        <!-- Selector Bulan Sebelumnya / Selanjutnya -->
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-xl border border-gray-100">
                            <button type="button" onclick="prevMonth()" class="p-1.5 px-2.5 rounded-lg hover:bg-white text-gray-500 hover:text-gray-900 transition text-xs">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </button>
                            <span id="labelBulanTahun" class="font-bold text-gray-900 text-xs tracking-tight">Memuat Bulan...</span>
                            <button type="button" onclick="nextMonth()" class="p-1.5 px-2.5 rounded-lg hover:bg-white text-gray-500 hover:text-gray-900 transition text-xs">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>
                        </div>

                        <!-- Kalender Grid -->
                        <div class="space-y-2">
                            <!-- Header Hari (Sen - Min) -->
                            <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <span>Sen</span>
                                <span>Sel</span>
                                <span>Rab</span>
                                <span>Kam</span>
                                <span>Jum</span>
                                <span class="text-rose-400">Sab</span>
                                <span class="text-rose-400">Min</span>
                            </div>

                            <!-- Matriks Tanggal (Diisi via JavaScript) -->
                            <div id="calendarMatrix" class="grid grid-cols-7 gap-y-2 gap-x-1 text-center text-xs font-medium min-h-[180px]">
                                <!-- Diisi via JS -->
                            </div>
                        </div>

                        <!-- Legend Status Kehadiran -->
                        <div class="flex items-center justify-center gap-4 pt-3 border-t border-gray-100 text-[10px] text-gray-500 flex-wrap">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tepat Waktu
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Terlambat
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Izin / Sakit
                            </span>
                        </div>
                    </div>

                    <!-- Kartu Detail Presensi Harian (Saat Tanggal Diklik) -->
                    <div id="dailyDetailCard" class="card p-5 bg-white space-y-3">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <div>
                                <h4 class="text-xs font-bold text-gray-900">Rincian Presensi Tanggal Terpilih</h4>
                                <p id="detailTanggalFormatted" class="text-[11px] text-gray-400 mt-0.5">-</p>
                            </div>
                            <span id="detailStatusBadge" class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                Pilih Tanggal
                            </span>
                        </div>

                        <!-- Detail Jam Masuk & Pulang -->
                        <div class="grid grid-cols-2 gap-2 text-center">
                            <div class="p-2.5 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-[10px] text-gray-400 uppercase font-semibold block">Masuk</span>
                                <p id="detailJamMasuk" class="text-xs font-bold text-gray-900 mt-0.5 font-mono">--:--</p>
                            </div>
                            <div class="p-2.5 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-[10px] text-gray-400 uppercase font-semibold block">Pulang</span>
                                <p id="detailJamPulang" class="text-xs font-bold text-gray-900 mt-0.5 font-mono">--:--</p>
                            </div>
                        </div>

                        <!-- Geofence Status -->
                        <div id="detailLocationBox" class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-2.5 text-xs">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-location-dot text-xs"></i>
                            </div>
                            <div class="truncate">
                                <p id="detailLocationText" class="font-bold text-gray-900 text-xs truncate">Lokasi Presensi</p>
                                <p id="detailGeofenceStatus" class="text-[10px] text-emerald-600 font-semibold">Terverifikasi Geofence</p>
                            </div>
                        </div>

                        <!-- Foto Selfie Detail -->
                        <div id="detailPhotosSection" class="hidden pt-2 border-t border-gray-100">
                            <span class="text-[10px] text-gray-400 uppercase font-semibold block mb-2">Foto Selfie Presensi</span>
                            <div class="grid grid-cols-2 gap-2">
                                <div id="detailPhotoMasukCard" class="p-2 bg-gray-50 rounded-lg border border-gray-100 flex items-center gap-2 cursor-pointer hover:bg-gray-100 transition" onclick="openDetailImageModal('masuk')">
                                    <img id="detailImgMasuk" src="" alt="Masuk" class="w-8 h-8 rounded-md object-cover bg-gray-200 shrink-0">
                                    <div class="truncate">
                                        <span class="text-[11px] font-bold text-gray-900 block truncate">Masuk</span>
                                        <span id="detailFotoMasukTime" class="text-[9px] text-gray-400 block font-mono">--:--</span>
                                    </div>
                                </div>
                                <div id="detailPhotoKeluarCard" class="p-2 bg-gray-50 rounded-lg border border-gray-100 flex items-center gap-2 cursor-pointer hover:bg-gray-100 transition" onclick="openDetailImageModal('keluar')">
                                    <img id="detailImgKeluar" src="" alt="Pulang" class="w-8 h-8 rounded-md object-cover bg-gray-200 shrink-0">
                                    <div class="truncate">
                                        <span class="text-[11px] font-bold text-gray-900 block truncate">Pulang</span>
                                        <span id="detailFotoKeluarTime" class="text-[9px] text-gray-400 block font-mono">--:--</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Keterangan Izin -->
                        <div id="detailIzinBox" class="hidden p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 space-y-1">
                            <strong class="block font-bold">Keterangan Izin:</strong>
                            <p id="detailIzinText" class="text-amber-800">-</p>
                            <div id="detailIzinFileContainer" class="hidden pt-1">
                                <a id="detailIzinFileLink" href="#" target="_blank" class="text-blue-600 font-semibold hover:underline inline-flex items-center gap-1">
                                    <i class="fa-solid fa-paperclip"></i>
                                    <span>Lihat Berkas Lampiran Izin</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                @include('siswa.partials.logbook-tab')

                @include('siswa.partials.progres-tab')

            </div>
        </main>
    </div>

    <!-- ================= FLOATING BOTTOM NAVIGATION BAR ================= -->
    <div class="fixed bottom-5 inset-x-0 z-40 flex justify-center px-4 pointer-events-none">
        <nav class="pointer-events-auto bg-white/95 backdrop-blur-xl border border-gray-200/90 shadow-[0_12px_36px_rgba(0,0,0,0.12)] rounded-full px-6 sm:px-7 py-3 flex items-center gap-7 sm:gap-9 transition-all">
            <!-- TAB 1: ABSEN (PRESENSI) -->
            <button type="button" onclick="switchAbsenTab('absen')" id="tap-btn-absen"
                class="transition-all active:scale-90 text-[#ff6b00] hover:opacity-90 flex items-center justify-center"
                title="Presensi">
                <i class="fa-solid fa-clock text-xl"></i>
            </button>

            <!-- TAB 2: RIWAYAT (KALENDER) -->
            <button type="button" onclick="switchAbsenTab('riwayat')" id="tap-btn-riwayat"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex items-center justify-center"
                title="Riwayat Kehadiran">
                <i class="fa-solid fa-calendar-days text-xl"></i>
            </button>

            <!-- TAB 3: LOGBOOK HARIAN -->
            <button type="button" onclick="switchAbsenTab('logbook')" id="tap-btn-logbook"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex items-center justify-center"
                title="Logbook Harian">
                <i class="fa-solid fa-book-open text-xl"></i>
            </button>

            <!-- TAB 4: PROGRES BERKAS BULANAN -->
            <button type="button" onclick="switchAbsenTab('progres')" id="tap-btn-progres"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex items-center justify-center"
                title="Progres Berkas Magang">
                <i class="fa-solid fa-list-check text-xl"></i>
            </button>
        </nav>
    </div>

    <!-- ================= MODAL PROFIL SISWA ================= -->
    <div id="profileModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl space-y-4 animate-scaleUp border border-gray-100">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-id-badge text-orange-500 text-sm"></i>
                    <h3 class="font-bold text-gray-900 text-sm">Profil Peserta Magang</h3>
                </div>
                <button type="button" onclick="closeProfileModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-xs transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="text-center space-y-2">
                <div class="w-16 h-16 rounded-full mx-auto bg-orange-100 border-2 border-orange-200 text-[#ff6b00] flex items-center justify-center font-bold text-xl shadow-xs">
                    {{ strtoupper(substr($nama, 0, 1)) }}
                </div>
                <div>
                    <h4 class="font-bold text-sm text-gray-900">{{ $nama }}</h4>
                    <p class="text-[11px] text-gray-500">{{ $siswaProfile->asal_pkl ?: 'Peserta Magang BPS' }}</p>
                </div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-semibold border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Aktif Magang BPS
                </div>
            </div>

            <div class="space-y-2 bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs">
                <div class="flex justify-between items-center text-gray-600">
                    <span class="text-gray-400">Divisi Penempatan:</span>
                    <span class="font-semibold text-gray-800 text-right">{{ $siswaProfile->divisi->nama_divisi ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span class="text-gray-400">Gedung / Lokasi:</span>
                    <span class="font-semibold text-gray-800 text-right">{{ $gedung->nama_gedung ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span class="text-gray-400">Nomor Telepon:</span>
                    <span class="font-semibold text-gray-800">{{ $siswaProfile->telepon ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span class="text-gray-400">Periode Magang:</span>
                    <span class="font-semibold text-gray-800 text-right">
                        {{ $siswaProfile->tanggal_mulai ? \Carbon\Carbon::parse($siswaProfile->tanggal_mulai)->isoFormat('D MMM Y') : '-' }} s/d 
                        {{ $siswaProfile->tanggal_selesai ? \Carbon\Carbon::parse($siswaProfile->tanggal_selesai)->isoFormat('D MMM Y') : '-' }}
                    </span>
                </div>
            </div>

            <div class="pt-1">
                <form method="POST" action="{{ route('logout') }}" onsubmit="showLoading('Keluar Akun...', 'Sedang memproses logout...', 'generic')">
                    @csrf
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 text-xs font-bold transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Keluar Akun (Logout)</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= HIDDEN FORMS UNTUK ABSENSI ================= -->
    <form method="POST" action="{{ route('siswa.absen.masuk', [], false) }}" id="formMasuk" class="hidden">
        @csrf
        <input type="hidden" name="latitude" id="lat">
        <input type="hidden" name="longitude" id="long">
        <input type="hidden" name="foto" id="fotoMasuk">
    </form>

    <form method="POST" action="{{ route('siswa.absen.keluar', [], false) }}" id="formKeluar" class="hidden">
        @csrf
        <input type="hidden" name="latitude" id="latKeluar">
        <input type="hidden" name="longitude" id="longKeluar">
        <input type="hidden" name="foto" id="fotoKeluar">
    </form>

    <!-- ================= MODAL PERINGATAN GPS NONAKTIF (UI BPS) ================= -->
    <div id="gpsAlertModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 animate-scaleUp border border-gray-100 text-center">
            <!-- Icon Circle -->
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center mx-auto text-2xl shadow-xs">
                <i class="fa-solid fa-location-crosshairs"></i>
            </div>
            <div>
                <h3 class="font-bold text-base text-gray-900">Akses Lokasi / GPS Diperlukan</h3>
                <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                    Sistem presensi BPS mewajibkan koordinat GPS Anda. Anda <strong>tidak dapat melakukan presensi</strong> sebelum GPS diaktifkan.
                </p>
            </div>
            
            <!-- Steps Box -->
            <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100 text-left text-xs space-y-2 text-gray-600">
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">1</span>
                    <span>Nyalakan <strong>Lokasi / GPS</strong> pada pengaturan perangkat HP atau laptop Anda.</span>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">2</span>
                    <span>Izinkan <strong>Akses Lokasi</strong> pada browser untuk situs ini.</span>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">3</span>
                    <span>Klik tombol <strong>Aktifkan & Coba Lagi</strong> di bawah.</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2 pt-1">
                <button type="button" onclick="retryGpsLocation()" id="btnRetryGps"
                    class="w-full py-2.5 px-4 rounded-xl bg-gray-900 hover:bg-black text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2 active:scale-95">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>Aktifkan & Coba Lagi</span>
                </button>
                <button type="button" onclick="closeGpsAlertModal()"
                    class="w-full py-2 px-4 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-semibold text-xs transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL KAMERA SELFIE (CLEAN & RESPONSIF) ================= -->
    <div id="cameraModal" class="hidden fixed inset-0 bg-black/90 backdrop-blur-md z-50 flex flex-col justify-between p-4 sm:p-6 transition-all">
        <!-- Header Atas Kamera -->
        <div class="flex justify-between items-center text-white px-2 pt-1 z-10">
            <button type="button" id="closeCameraBtn" class="w-9 h-9 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition active:scale-90" title="Tutup">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
            <div class="text-center">
                <span id="cameraModalTitle" class="text-xs sm:text-sm font-bold tracking-wide">Ambil Foto Presensi</span>
            </div>
            <div class="w-9"></div>
        </div>

        <!-- Viewfinder Kamera -->
        <div class="flex-1 flex items-center justify-center my-auto w-full py-2">
            <div class="w-full max-w-xs sm:max-w-sm aspect-[3/4] rounded-2xl overflow-hidden relative shadow-2xl bg-black border border-white/20 flex items-center justify-center">
                <div id="cameraFlash" class="absolute inset-0 bg-white opacity-0 pointer-events-none transition-opacity duration-150 z-20"></div>
                <video id="videoPreview" autoplay muted playsinline class="w-full h-full object-cover transform -scale-x-100"></video>
                <canvas id="canvas" class="hidden"></canvas>
            </div>
        </div>

        <!-- Kontrol Shutter & Kamera Switch -->
        <div class="w-full max-w-xs sm:max-w-sm mx-auto pb-4 pt-2">
            <div class="flex items-center justify-around">
                <div class="w-12"></div>

                <!-- Tombol Shutter -->
                <div class="flex justify-center">
                    <button type="button" id="captureBtn" class="w-16 h-16 rounded-full border-4 border-white/80 p-1 flex items-center justify-center active:scale-90 transition-transform cursor-pointer bg-white/20 hover:bg-white/30" title="Ambil Foto">
                        <div class="w-full h-full rounded-full bg-white transition-all shadow-inner"></div>
                    </button>
                </div>

                <!-- Switch Kamera Depan/Belakang -->
                <div class="w-12 flex justify-center">
                    <button type="button" id="switchCameraBtn" class="w-10 h-10 rounded-full bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition active:scale-90" title="Ganti Kamera">
                        <i class="fa-solid fa-camera-rotate text-sm"></i>
                    </button>
                </div>
            </div>
            <p class="text-center text-[11px] text-gray-400 mt-3">Pastikan wajah terlihat jelas pada kamera</p>
        </div>
    </div>

    <!-- ================= MODAL FORM IZIN ================= -->
    <div id="izinModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="card p-6 bg-white w-full max-w-md shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Formulir Pengajuan Izin</h3>
                        <p class="text-[11px] text-gray-400">Laporkan ketidakhadiran magang dengan jelas</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('izinModal')" class="text-gray-400 hover:text-black">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('siswa.absen.izin', [], false) }}" enctype="multipart/form-data" id="formIzinSiswa" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Izin <span class="text-rose-500">*</span></label>
                    <select name="jenis_izin_id" required class="w-full text-xs p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 bg-white">
                        @foreach($jenisIzin as $izin)
                            <option value="{{ $izin->id }}">{{ $izin->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan / Keperluan <span class="text-rose-500">*</span></label>
                    <textarea name="keperluan" rows="3" required placeholder="Tulis alasan izin secara singkat dan jelas..." class="w-full text-xs p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Berkas / Surat Bukti (Opsional)</label>
                    <input type="file" name="file_izin" accept=".pdf,.jpg,.jpeg,.png" class="text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('izinModal')" class="px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition">Batal</button>
                    <button type="submit" id="btnSubmitIzin" class="px-4 py-2 text-xs font-semibold text-white bg-gray-900 hover:bg-black rounded-lg transition flex items-center gap-1.5 shadow-xs">
                        <i class="fa-solid fa-paper-plane text-[10px]"></i>
                        <span>Kirim Permohonan Izin</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL PREVIEW GAMBAR ================= -->
    <div id="imageModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
        <div class="relative bg-white p-2 rounded-2xl shadow-2xl max-w-sm w-full" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold shadow-md hover:bg-black">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <img id="modalImage" src="" alt="Preview" class="w-full h-auto max-h-[75vh] rounded-xl object-contain bg-gray-50">
        </div>
    </div>

    <!-- ================= MODAL PETA LOKASI ================= -->
    <div id="mapModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="card p-5 bg-white w-full max-w-md shadow-2xl space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <h4 class="text-xs font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-blue-600"></i>
                    <span>Titik Lokasi GPS Presensi</span>
                </h4>
                <button type="button" onclick="closeModal('mapModal')" class="text-gray-400 hover:text-black">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <div id="map" class="w-full h-64 rounded-xl overflow-hidden border border-gray-200">
                <p class="p-4 text-xs text-center text-gray-400">Memuat peta lokasi...</p>
            </div>
        </div>
    </div>

    <!-- ================= LOADING OVERLAY ================= -->
    <div id="loadingOverlay" class="fixed inset-0 z-[100] flex flex-col items-center justify-center p-4 bg-black/60 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-300">
        <div id="loadingModalCard" class="bg-white rounded-2xl p-6 max-w-xs w-full shadow-2xl flex flex-col items-center text-center transform scale-95 transition-all duration-300 space-y-3">
            <div class="w-12 h-12 rounded-full border-3 border-gray-200 border-t-gray-900 animate-spin"></div>
            <div>
                <h4 id="loadingTitle" class="text-xs font-bold text-gray-900">Memproses...</h4>
                <p id="loadingSubtitle" class="text-[11px] text-gray-400 mt-0.5">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>

    <!-- JavaScript Interactivity Presensi Siswa -->
    <script>
        function showLoading(title = 'Memproses...', subtitle = 'Mohon tunggu sebentar...', icon = 'camera') {
            const overlay = document.getElementById('loadingOverlay');
            const card = document.getElementById('loadingModalCard');
            const t = document.getElementById('loadingTitle');
            const s = document.getElementById('loadingSubtitle');

            if (t) t.textContent = title;
            if (s) s.textContent = subtitle;

            if (overlay) {
                overlay.classList.remove('pointer-events-none', 'opacity-0');
                overlay.classList.add('opacity-100');
            }
            if (card) {
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }
        }

        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            const card = document.getElementById('loadingModalCard');
            if (overlay) {
                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0', 'pointer-events-none');
            }
            if (card) {
                card.classList.remove('scale-100');
                card.classList.add('scale-95');
            }
        }

        const gedLat = parseFloat("{{ $gedung->latitude ?? 0 }}");
        const gedLong = parseFloat("{{ $gedung->longitude ?? 0 }}");
        const gedRadius = parseFloat("{{ $gedung->radius_meter ?? 10 }}");
        const isWfhToday = {{ $isWfhToday ? 'true' : 'false' }};

        // Riwayat State
        let currentRiwayatDate = new Date();
        let riwayatCacheData = [];
        let selectedDayRiwayat = null;

        const namaBulanIndo = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        // Live Clock
        function updateLiveClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            const clockEl = document.getElementById('liveClock');
            if (clockEl) clockEl.textContent = timeStr;
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // Modal Helpers
        function closeModal(id) {
            document.getElementById(id)?.classList.add('hidden');
        }

        let currentActiveTab = 'absen';

        const absenTabButtons = {
            absen: 'tap-btn-absen',
            riwayat: 'tap-btn-riwayat',
            logbook: 'tap-btn-logbook',
            progres: 'tap-btn-progres',
        };

        const absenTabViews = {
            absen: 'view-absen',
            riwayat: 'view-riwayat',
            logbook: 'view-logbook',
            progres: 'view-progres',
        };

        function syncStudentTabUrl({ tab = null, date = null } = {}) {
            if (!window.history || !window.history.replaceState) return;

            const url = new URL(window.location.href);

            if (tab) {
                if (tab === 'absen') {
                    url.searchParams.delete('tab');
                } else {
                    url.searchParams.set('tab', tab);
                }
            }

            if (date) {
                url.searchParams.set('tab', 'logbook');
                url.searchParams.set('date', date);
            }

            window.history.replaceState({}, '', url);
        }

        function switchAbsenTab(tab) {
            if (!absenTabViews[tab]) tab = 'absen';
            currentActiveTab = tab;

            Object.entries(absenTabViews).forEach(([name, viewId]) => {
                const view = document.getElementById(viewId);
                if (!view) return;

                if (name === tab) {
                    view.style.display = 'block';
                    view.classList.remove('hidden');
                } else {
                    view.style.display = 'none';
                    view.classList.add('hidden');
                }
            });

            Object.entries(absenTabButtons).forEach(([name, buttonId]) => {
                const button = document.getElementById(buttonId);
                if (!button) return;

                if (name === tab) {
                    button.classList.add('text-[#ff6b00]');
                    button.classList.remove('text-gray-400');
                } else {
                    button.classList.remove('text-[#ff6b00]');
                    button.classList.add('text-gray-400');
                }
            });

            const scrollArea = document.querySelector('main');
            if (scrollArea) {
                scrollArea.scrollTo({ top: 0, behavior: 'smooth' });
            }

            syncStudentTabUrl({ tab });

            if (tab === 'logbook' && !logbookCalendarInitialized) {
                initLogbookTab();
            } else if (tab === 'progres') {
                loadProgresData();
            }
        }

        // ================= LOGBOOK BULANAN & RINCIAN HARIAN =================
        let currentLogbookDate = new Date();
        let logbookMonthCache = {};
        let selectedLogbookDateStr = null;
        let currentLoadedLogbookData = null;
        let logbookCalendarInitialized = false;

        function prevLogbookMonth() {
            currentLogbookDate.setMonth(currentLogbookDate.getMonth() - 1);
            fetchLogbookMonthlyData(currentLogbookDate.getFullYear(), currentLogbookDate.getMonth() + 1);
        }

        function nextLogbookMonth() {
            currentLogbookDate.setMonth(currentLogbookDate.getMonth() + 1);
            fetchLogbookMonthlyData(currentLogbookDate.getFullYear(), currentLogbookDate.getMonth() + 1);
        }

        function refreshLogbookCalendar() {
            fetchLogbookMonthlyData(currentLogbookDate.getFullYear(), currentLogbookDate.getMonth() + 1, selectedLogbookDateStr);
        }

        function exportCurrentLogbookMonth() {
            const year = currentLogbookDate.getFullYear();
            const month = currentLogbookDate.getMonth() + 1;
            const url = `{{ route('siswa.logbook.export') }}?bulan=${month}&tahun=${year}`;
            window.open(url, '_blank');
        }

        function initLogbookTab(targetDate = null) {
            logbookCalendarInitialized = true;
            bindLogbookTextareaCounters();
            bindLogbookFormSubmit();

            let dateToSelect = targetDate;
            if (!dateToSelect) {
                const urlParams = new URLSearchParams(window.location.search);
                dateToSelect = urlParams.get('date');
            }

            if (dateToSelect) {
                const [y, m] = dateToSelect.split('-').map(Number);
                if (y && m) {
                    currentLogbookDate = new Date(y, m - 1, 1);
                }
            }

            fetchLogbookMonthlyData(
                currentLogbookDate.getFullYear(),
                currentLogbookDate.getMonth() + 1,
                dateToSelect
            );
        }

        async function fetchLogbookMonthlyData(year, month, targetSelectDate = null) {
            const labelEl = document.getElementById('labelLogbookBulanTahun');
            if (labelEl) labelEl.textContent = `${namaBulanIndo[month - 1]} ${year}`;

            const calEl = document.getElementById('logbookCalendarMatrix');
            if (calEl) {
                calEl.innerHTML = `
                    <div class="col-span-7 py-8 flex flex-col items-center justify-center space-y-2 text-gray-400">
                        <div class="w-6 h-6 border-2 border-gray-300 border-t-gray-900 rounded-full animate-spin"></div>
                        <span class="text-xs">Memuat kalender logbook...</span>
                    </div>
                `;
            }

            try {
                const res = await fetch(`{{ route('siswa.logbook.bulan', [], false) }}?bulan=${month}&tahun=${year}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();

                logbookMonthCache = {};
                (data || []).forEach(item => {
                    const dateStr = (item.date || '').split(/[T ]/)[0];
                    if (dateStr) logbookMonthCache[dateStr] = item.status;
                });

                renderLogbookCalendar(year, month);

                const now = new Date();
                const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;

                let dateToSelect = targetSelectDate;
                if (!dateToSelect) {
                    if (now.getFullYear() === year && (now.getMonth() + 1) === month) {
                        dateToSelect = todayStr;
                    } else {
                        dateToSelect = `${year}-${String(month).padStart(2, '0')}-01`;
                    }
                }

                fetchLogbookDetail(dateToSelect);
            } catch (err) {
                console.error('Logbook calendar error:', err);
                if (calEl) {
                    calEl.innerHTML = `
                        <div class="col-span-7 py-6 text-center space-y-2">
                            <div class="text-xs font-semibold text-rose-500">Gagal memuat kalender logbook.</div>
                            <button type="button" onclick="fetchLogbookMonthlyData(${year}, ${month})" class="text-[11px] font-bold text-gray-900 hover:underline">
                                Coba Lagi
                            </button>
                        </div>
                    `;
                }
            }
        }

        function renderLogbookCalendar(year, month) {
            const calEl = document.getElementById('logbookCalendarMatrix');
            if (!calEl) return;
            calEl.innerHTML = '';

            const firstDayIndex = new Date(year, month - 1, 1).getDay();
            const daysInMonth = new Date(year, month, 0).getDate();
            const daysInPrevMonth = new Date(year, month - 1, 0).getDate();
            const adjustedFirstDay = (firstDayIndex === 0) ? 6 : firstDayIndex - 1;

            // Prev Month spillover
            for (let i = adjustedFirstDay - 1; i >= 0; i--) {
                const dayNum = daysInPrevMonth - i;
                const cell = document.createElement('div');
                cell.className = 'py-2 text-gray-300 text-xs';
                cell.textContent = dayNum;
                calEl.appendChild(cell);
            }

            const now = new Date();
            const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;

            for (let day = 1; day <= daysInMonth; day++) {
                const dayStr = String(day).padStart(2, '0');
                const monthStr = String(month).padStart(2, '0');
                const fullDate = `${year}-${monthStr}-${dayStr}`;
                const status = logbookMonthCache[fullDate];

                const dayOfWeek = new Date(year, month - 1, day).getDay();
                const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);

                const cell = document.createElement('div');
                cell.className = 'py-1 flex flex-col items-center justify-center cursor-pointer rounded-lg transition hover:bg-gray-100';
                cell.id = `cal-logbook-${fullDate}`;

                let dotHtml = '';
                if (status === 'approved') {
                    dotHtml = `<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-0.5"></span>`;
                } else if (status === 'rejected') {
                    dotHtml = `<span class="w-1.5 h-1.5 rounded-full bg-rose-500 mt-0.5"></span>`;
                } else if (status === 'pending') {
                    dotHtml = `<span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-0.5"></span>`;
                } else {
                    dotHtml = `<span class="w-1.5 h-1.5 rounded-full bg-gray-200 mt-0.5"></span>`;
                }

                const isToday = (fullDate === todayStr);
                const todayRing = isToday ? ' ring-1 ring-gray-900 font-bold' : '';
                const textClass = isWeekend ? 'text-rose-400' : 'text-gray-700';

                cell.innerHTML = `
                    <span class="text-xs font-semibold ${textClass}${todayRing}">${day}</span>
                    ${dotHtml}
                `;

                cell.onclick = () => {
                    fetchLogbookDetail(fullDate);
                };

                calEl.appendChild(cell);
            }
        }

        function highlightLogbookCalendarCell(dateStr) {
            document.querySelectorAll('[id^="cal-logbook-"]').forEach(el => {
                el.classList.remove('bg-gray-900', 'text-white', 'shadow-xs');
                const span = el.querySelector('span:first-child');
                if (span) span.classList.remove('text-white');
            });

            const activeCell = document.getElementById(`cal-logbook-${dateStr}`);
            if (activeCell) {
                activeCell.classList.add('bg-gray-900', 'shadow-xs');
                const span = activeCell.querySelector('span:first-child');
                if (span) {
                    span.classList.remove('text-gray-700', 'text-rose-400');
                    span.classList.add('text-white');
                }
            }
        }

        async function fetchLogbookDetail(dateStr) {
            selectedLogbookDateStr = dateStr;
            highlightLogbookCalendarCell(dateStr);
            syncStudentTabUrl({ tab: 'logbook', date: dateStr });

            const loadingEl = document.getElementById('logbookDetailLoading');
            const bodyEl = document.getElementById('logbookDetailBody');
            const dateFormattedEl = document.getElementById('logbookDetailDateFormatted');
            const statusBadgeEl = document.getElementById('logbookDetailStatusBadge');

            if (loadingEl) loadingEl.classList.remove('hidden');
            if (bodyEl) bodyEl.classList.add('hidden');
            if (statusBadgeEl) {
                statusBadgeEl.className = 'text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500';
                statusBadgeEl.textContent = 'Memuat...';
            }

            try {
                const res = await fetch(`{{ route('siswa.logbook.detail', [], false) }}?date=${dateStr}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                currentLoadedLogbookData = data;

                if (loadingEl) loadingEl.classList.add('hidden');
                if (bodyEl) bodyEl.classList.remove('hidden');

                renderLogbookDetail(data);
            } catch (err) {
                console.error('Error fetching logbook detail:', err);
                if (loadingEl) loadingEl.classList.add('hidden');
                if (bodyEl) bodyEl.classList.remove('hidden');
                if (statusBadgeEl) {
                    statusBadgeEl.textContent = 'Gagal Memuat';
                    statusBadgeEl.className = 'text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200';
                }
            }
        }

        function renderLogbookDetail(data) {
            const dateFormattedEl = document.getElementById('logbookDetailDateFormatted');
            const statusBadgeEl = document.getElementById('logbookDetailStatusBadge');
            const alertRejected = document.getElementById('logbookAlertRejected');
            const noteRejected = document.getElementById('logbookRejectedNote');
            const alertApproved = document.getElementById('logbookAlertApproved');
            const metaApproved = document.getElementById('logbookApprovedMeta');
            const alertLocked = document.getElementById('logbookAlertLocked');
            const lockReasonText = document.getElementById('logbookLockedReason');

            const readonlySection = document.getElementById('logbookReadonlySection');
            const readonlyActivity = document.getElementById('logbookReadonlyActivity');
            const readonlyResult = document.getElementById('logbookReadonlyResult');
            const readonlyResultWrapper = document.getElementById('logbookReadonlyResultWrapper');
            const editActionWrapper = document.getElementById('logbookEditActionWrapper');

            const formSection = document.getElementById('logbookFormSection');
            const formDateInput = document.getElementById('logbookFormDateInput');
            const inputActivity = document.getElementById('logbookInputActivity');
            const inputResult = document.getElementById('logbookInputResult');
            const btnCancelEdit = document.getElementById('btnCancelLogbookEdit');
            const btnSubmitLabel = document.getElementById('btnSubmitLogbookLabel');

            const emptyLockedSection = document.getElementById('logbookEmptyLockedSection');
            const emptyLockedReason = document.getElementById('logbookEmptyLockedReason');

            if (dateFormattedEl) dateFormattedEl.textContent = data.date_formatted;

            // Reset visibility
            alertRejected?.classList.add('hidden');
            alertApproved?.classList.add('hidden');
            alertLocked?.classList.add('hidden');
            readonlySection?.classList.add('hidden');
            formSection?.classList.add('hidden');
            emptyLockedSection?.classList.add('hidden');

            const hasLogbook = data.exists && data.logbook;

            // Status Badge
            if (hasLogbook) {
                statusBadgeEl.textContent = data.logbook.status_label;
                statusBadgeEl.className = `text-[10px] font-bold px-2.5 py-0.5 rounded-full border ${data.logbook.status_badge}`;

                if (data.logbook.status === 'rejected') {
                    alertRejected?.classList.remove('hidden');
                    if (noteRejected) noteRejected.textContent = data.logbook.review_note || 'logbook ditolak tanpa catatan.';
                } else if (data.logbook.status === 'approved') {
                    alertApproved?.classList.remove('hidden');
                    if (metaApproved) {
                        metaApproved.textContent = `Disetujui pada ${data.logbook.reviewed_at || ''}`;
                    }
                }
            } else {
                statusBadgeEl.textContent = 'Belum Diisi';
                statusBadgeEl.className = 'text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200';
            }

            // Set Form Data
            if (formDateInput) formDateInput.value = data.date;
            if (inputActivity) inputActivity.value = hasLogbook ? (data.logbook.activity || '') : '';
            if (inputResult) inputResult.value = hasLogbook ? (data.logbook.result || '') : '';
            updateLogbookCounter('logbookInputActivity', 'logbookActivityCounter', 2000);
            updateLogbookCounter('logbookInputResult', 'logbookResultCounter', 2000);

            // Lock notification
            if (data.lock_reason && (!hasLogbook || data.logbook.status !== 'approved')) {
                alertLocked?.classList.remove('hidden');
                if (lockReasonText) lockReasonText.textContent = data.lock_reason;
            }

            if (hasLogbook) {
                readonlySection?.classList.remove('hidden');
                if (readonlyActivity) readonlyActivity.textContent = data.logbook.activity || '-';
                if (readonlyResult) readonlyResult.textContent = data.logbook.result || '-';
                if (readonlyResultWrapper) {
                    readonlyResultWrapper.classList.toggle('hidden', !data.logbook.result);
                }

                if (data.can_edit) {
                    editActionWrapper?.classList.remove('hidden');
                    if (btnSubmitLabel) btnSubmitLabel.textContent = 'Perbarui Logbook';
                    btnCancelEdit?.classList.remove('hidden');
                } else {
                    editActionWrapper?.classList.add('hidden');
                }
            } else {
                if (data.can_edit) {
                    formSection?.classList.remove('hidden');
                    btnCancelEdit?.classList.add('hidden');
                    if (btnSubmitLabel) btnSubmitLabel.textContent = 'Simpan Logbook';
                } else {
                    emptyLockedSection?.classList.remove('hidden');
                    if (emptyLockedReason) emptyLockedReason.textContent = data.lock_reason || 'Belum ada catatan logbook yang diisi untuk tanggal ini.';
                }
            }
        }

        function showLogbookFormMode() {
            document.getElementById('logbookReadonlySection')?.classList.add('hidden');
            document.getElementById('logbookFormSection')?.classList.remove('hidden');
        }

        function cancelLogbookFormMode() {
            document.getElementById('logbookFormSection')?.classList.add('hidden');
            document.getElementById('logbookReadonlySection')?.classList.remove('hidden');
        }

        function updateLogbookCounter(inputId, counterId, max = 2000) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            if (input && counter) {
                counter.textContent = `${input.value.length}/${max}`;
            }
        }

        function bindLogbookTextareaCounters() {
            ['logbookInputActivity', 'logbookInputResult'].forEach(id => {
                const input = document.getElementById(id);
                if (!input) return;
                const counterId = id === 'logbookInputActivity' ? 'logbookActivityCounter' : 'logbookResultCounter';
                input.addEventListener('input', () => updateLogbookCounter(id, counterId, 2000));
            });
        }

        function bindLogbookFormSubmit() {
            const form = document.getElementById('formSubmitLogbook');
            if (!form || form.dataset.bound === 'true') return;
            form.dataset.bound = 'true';

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const submitBtn = document.getElementById('btnSubmitLogbookForm');
                const origHtml = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Menyimpan...</span>';
                }

                showLoading('Menyimpan Logbook...', 'Sedang memproses catatan kegiatan Anda...', 'upload');

                try {
                    const formData = new FormData(form);
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const json = await res.json();
                    hideLoading();

                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = origHtml;
                    }

                    if (json.success) {
                        if (json.date && json.logbook) {
                            logbookMonthCache[json.date] = json.logbook.status;
                            renderLogbookCalendar(currentLogbookDate.getFullYear(), currentLogbookDate.getMonth() + 1);
                        }
                        await fetchLogbookDetail(json.date || selectedLogbookDateStr);
                        alert(json.message || 'Logbook berhasil disimpan!');
                    } else {
                        alert(json.message || 'Gagal menyimpan logbook. Periksa kembali isian Anda.');
                    }
                } catch (err) {
                    hideLoading();
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = origHtml;
                    }
                    console.error('Error submitting logbook:', err);
                    form.submit();
                }
            });
        }

        // ================= LOGIKA PROGRES BERKAS BULANAN =================
        function openUploadProgresModal(year, month, monthName, isReupload = false, currentStatus = '') {
            const inputYear = document.getElementById('progresInputYear');
            const inputMonth = document.getElementById('progresInputMonth');
            const title = document.getElementById('modalProgresTitle');
            const subtitle = document.getElementById('modalProgresSubtitle');
            const submitLabel = document.getElementById('btnSubmitProgresLabel');
            const alertBox = document.getElementById('modalProgresReuploadAlert');
            const fileInput = document.getElementById('progresFileInput');
            const notesInput = document.getElementById('progresNotesInput');
            const modal = document.getElementById('modalUploadProgres');

            if (inputYear) inputYear.value = year;
            if (inputMonth) inputMonth.value = month;
            if (title) {
                title.textContent = isReupload
                    ? `Upload Ulang Berkas - ${monthName}`
                    : `Upload Berkas Logbook - ${monthName}`;
            }
            if (subtitle) subtitle.textContent = `Periode Bulan: ${monthName}`;
            if (submitLabel) {
                submitLabel.textContent = isReupload
                    ? 'Perbarui & Simpan Berkas'
                    : 'Unggah Berkas';
            }

            if (alertBox) {
                if (isReupload) {
                    alertBox.classList.remove('hidden');
                } else {
                    alertBox.classList.add('hidden');
                }
            }

            if (fileInput) fileInput.value = '';
            if (notesInput) notesInput.value = '';

            modal?.classList.remove('hidden');
        }

        function closeUploadProgresModal() {
            const modal = document.getElementById('modalUploadProgres');
            modal?.classList.add('hidden');
        }

        async function loadProgresData() {
            const container = document.getElementById('progresListContainer');
            if (!container) return;

            try {
                const response = await fetch(`{{ route('siswa.progres.data') }}`);
                if (!response.ok) throw new Error('Gagal memuat data progres');
                const data = await response.json();
                renderProgresList(data);
            } catch (err) {
                console.error('Error fetching progress data:', err);
            }
        }

        function renderProgresList(data) {
            const subtitle = document.getElementById('progresSummarySubtitle');
            if (subtitle) {
                subtitle.textContent = `${data.total_approved} dari ${data.total_months} berkas telah disetujui (ACC)`;
            }
            const ratio = document.getElementById('progresSummaryRatio');
            if (ratio) {
                ratio.textContent = data.total_months > 0 && data.total_approved === data.total_months ? 'Lengkap (ACC)' : 'Dalam Proses';
            }

            const container = document.getElementById('progresListContainer');
            if (!container) return;

            if (!data.months || data.months.length === 0) {
                container.innerHTML = `
                    <div class="py-10 text-center text-gray-400 space-y-2">
                        <i class="fa-solid fa-calendar-xmark text-3xl text-gray-300"></i>
                        <h5 class="text-xs font-bold text-gray-600">Periode Magang Belum Ditetapkan</h5>
                        <p class="text-[11px] text-gray-400 max-w-sm mx-auto">
                            Tanggal mulai dan selesai magang belum diatur
                        </p>
                    </div>`;
                return;
            }

            container.innerHTML = data.months.map(m => {
                const noteHtml = m.notes ? `<p class="text-[11px] text-gray-500 italic px-1">"${escapeHtml(m.notes)}"</p>` : '';
                const feedbackHtml = (m.status === 'rejected' && m.feedback) ? `
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800 space-y-1">
                        <strong class="flex items-center gap-1.5 font-bold">
                            <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                            <span>Catatan Perbaikan Pegawai:</span>
                        </strong>
                        <p class="text-rose-700 text-[11px] leading-relaxed">${escapeHtml(m.feedback)}</p>
                    </div>` : '';

                const fileInfoHtml = m.has_uploaded ? `
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-between text-xs gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <i class="fa-solid fa-file-pdf text-rose-500 text-base shrink-0"></i>
                            <div class="truncate">
                                <span class="font-semibold text-gray-800 block truncate text-xs">${escapeHtml(m.file_name)}</span>
                                <span class="text-[10px] text-gray-400 block">${m.file_size_formatted} · Diunggah: ${m.uploaded_at}</span>
                            </div>
                        </div>
                        <a href="${m.file_url}" target="_blank"
                            class="px-2.5 py-1 rounded-lg bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-[11px] font-semibold transition shrink-0 flex items-center gap-1 shadow-2xs">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            <span>Lihat</span>
                        </a>
                    </div>` : '';

                let statusHelpText = '';
                if (m.status === 'approved') statusHelpText = '<i class="fa-solid fa-circle-check text-emerald-500"></i> Berkas telah diverifikasi & disetujui.';
                else if (m.status === 'pending') statusHelpText = '<i class="fa-solid fa-clock text-amber-500"></i> Menunggu pengecekan oleh pegawai.';
                else if (m.status === 'rejected') statusHelpText = '<i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Perlu perbaikan berkas.';
                else statusHelpText = '<i class="fa-solid fa-circle-info text-gray-400"></i> Silakan upload berkas bertandatangan.';

                const btnHtml = !m.has_uploaded
                    ? `<button type="button" onclick="openUploadProgresModal(${m.year}, ${m.month}, '${escapeHtml(m.month_name)}', false)"
                        class="px-3.5 py-1.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs active:scale-95">
                        <i class="fa-solid fa-cloud-arrow-up text-[11px]"></i>
                        <span>Upload Berkas</span>
                    </button>`
                    : `<button type="button" onclick="openUploadProgresModal(${m.year}, ${m.month}, '${escapeHtml(m.month_name)}', true, '${m.status}')"
                        class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold transition flex items-center gap-1.5 active:scale-95">
                        <i class="fa-solid fa-arrow-rotate-right text-[11px]"></i>
                        <span>Upload Ulang</span>
                    </button>`;

                return `
                    <div class="p-4 rounded-xl border border-gray-100 hover:border-gray-200 transition bg-white shadow-xs space-y-3" id="progres-card-${m.year}-${m.month}">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-700 font-bold text-[11px] flex items-center justify-center shrink-0">
                                    ${m.index}
                                </span>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">${escapeHtml(m.month_name)}</h4>
                                    <span class="text-[10px] text-gray-400">Bulan ke-${m.index} Masa Magang</span>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border ${m.status_badge}">
                                ${m.status_label}
                            </span>
                        </div>
                        ${feedbackHtml}
                        ${fileInfoHtml}
                        ${noteHtml}
                        <div class="flex items-center justify-between pt-1 border-t border-gray-50">
                            <span class="text-[10px] text-gray-400">${statusHelpText}</span>
                            <div>${btnHtml}</div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function showProfileModal() {
            document.getElementById('profileModal')?.classList.remove('hidden');
        }

        function closeProfileModal() {
            document.getElementById('profileModal')?.classList.add('hidden');
        }

        function showIzinModal() {
            document.getElementById('izinModal')?.classList.remove('hidden');
        }

        function showImageModal(src) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');
            if (modal && img) {
                img.src = src;
                modal.classList.remove('hidden');
            }
        }

        function closeImageModal() {
            document.getElementById('imageModal')?.classList.add('hidden');
        }

        function openDetailImageModal(type) {
            if (!selectedDayRiwayat) return;
            const src = type === 'masuk' ? selectedDayRiwayat.foto_masuk_url : selectedDayRiwayat.foto_keluar_url;
            if (src) showImageModal(src);
        }

        function getDistanceFromLatLonInMeters(lat1, lon1, lat2, lon2) {
            const R = 6371e3;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        // Camera Logic
        let stream = null;
        let currentFacingMode = 'user';
        let currentFormToSubmit = null;

        async function startCamera(formType) {
            currentFormToSubmit = formType === 'masuk' ? document.getElementById('formMasuk') : document.getElementById('formKeluar');
            const modalTitle = document.getElementById('cameraModalTitle');

            if (modalTitle) {
                modalTitle.textContent = formType === 'masuk' ? 'Foto Presensi Masuk' : 'Foto Presensi Pulang';
            }

            showLoading('Membuka Kamera...', 'Sedang menyiapkan modul kamera...', 'camera');

            try {
                if (stream) stream.getTracks().forEach(track => track.stop());
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: currentFacingMode } },
                    audio: false
                });
                const video = document.getElementById('videoPreview');
                if (video) {
                    video.srcObject = stream;
                    video.classList.toggle('-scale-x-100', currentFacingMode === 'user');
                    await video.play();
                }
                document.getElementById('cameraModal').classList.remove('hidden');
                hideLoading();
            } catch (err) {
                hideLoading();
                alert('Gagal mengakses kamera: ' + err.message + '\nPastikan izin akses kamera telah diizinkan pada browser Anda.');
            }
        }

        function stopCamera() {
            if (stream) stream.getTracks().forEach(track => track.stop());
            stream = null;
            const video = document.getElementById('videoPreview');
            if (video) video.srcObject = null;
            document.getElementById('cameraModal')?.classList.add('hidden');
        }

        function base64ImageSize(dataUrl) {
            const base64 = dataUrl.split(',')[1] || '';
            const padding = (base64.match(/=*$/) || [''])[0].length;
            return Math.floor((base64.length * 3) / 4) - padding;
        }

        function compressCameraPhoto() {
            const video = document.getElementById('videoPreview');
            const canvas = document.getElementById('canvas');
            const maxBytes = 200 * 1024;
            const maxDimension = 1280;
            const scale = Math.min(1, maxDimension / Math.max(video.videoWidth, video.videoHeight));

            canvas.width = Math.max(1, Math.round(video.videoWidth * scale));
            canvas.height = Math.max(1, Math.round(video.videoHeight * scale));

            const ctx = canvas.getContext('2d');
            if (currentFacingMode === 'user') {
                ctx.save();
                ctx.scale(-1, 1);
                ctx.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
                ctx.restore();
            } else {
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            }

            let workingCanvas = canvas;
            for (let resizeAttempt = 0; resizeAttempt < 10; resizeAttempt++) {
                for (let quality = 0.85; quality >= 0.35; quality -= 0.05) {
                    const base64 = workingCanvas.toDataURL('image/jpeg', quality);
                    if (base64ImageSize(base64) <= maxBytes) return base64;
                }
                const smallerCanvas = document.createElement('canvas');
                smallerCanvas.width = Math.max(1, Math.round(workingCanvas.width * 0.85));
                smallerCanvas.height = Math.max(1, Math.round(workingCanvas.height * 0.85));
                smallerCanvas.getContext('2d').drawImage(workingCanvas, 0, 0, smallerCanvas.width, smallerCanvas.height);
                workingCanvas = smallerCanvas;
            }
            return workingCanvas.toDataURL('image/jpeg', 0.5);
        }

        // ================= RIWAYAT PRESENSI & KALENDER =================
        function prevMonth() {
            currentRiwayatDate.setMonth(currentRiwayatDate.getMonth() - 1);
            fetchRiwayatData(currentRiwayatDate.getFullYear(), currentRiwayatDate.getMonth() + 1);
        }

        function nextMonth() {
            currentRiwayatDate.setMonth(currentRiwayatDate.getMonth() + 1);
            fetchRiwayatData(currentRiwayatDate.getFullYear(), currentRiwayatDate.getMonth() + 1);
        }

        function refreshRiwayatData() {
            fetchRiwayatData(currentRiwayatDate.getFullYear(), currentRiwayatDate.getMonth() + 1);
        }

        async function fetchRiwayatData(year, month) {
            const labelEl = document.getElementById('labelBulanTahun');
            if (labelEl) labelEl.textContent = `${namaBulanIndo[month - 1]} ${year}`;

            const calEl = document.getElementById('calendarMatrix');
            calEl.innerHTML = `
                <div class="col-span-7 py-8 flex flex-col items-center justify-center space-y-2 text-gray-400">
                    <div class="w-6 h-6 border-2 border-gray-300 border-t-gray-900 rounded-full animate-spin"></div>
                    <span class="text-xs">Memuat riwayat kehadiran...</span>
                </div>
            `;

            try {
                const res = await fetch(`{{ route('siswa.riwayat', [], false) }}?bulan=${month}&tahun=${year}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) throw new Error('HTTP error ' + res.status);
                const rawData = await res.json();
                riwayatCacheData = (rawData || []).map(item => {
                    const rawDate = item.tanggal || item.date || '';
                    item.tanggal = String(rawDate).split(/[T ]/)[0];
                    item.jam_masuk = item.jam_masuk || item.check_in_time || null;
                    item.jam_pulang = item.jam_pulang || item.check_out_time || null;
                    item.status_ketepatan = item.status_ketepatan || item.punctuality_status || 'Tepat Waktu';
                    item.status_kerja = (item.status_kerja !== undefined) ? Boolean(item.status_kerja) : Boolean(item.work_type);
                    item.izin = (item.izin !== undefined) ? Boolean(item.izin) : Boolean(item.is_permission);
                    item.keterangan_izin = item.keterangan_izin || item.leave_description || null;
                    item.file_izin = item.file_izin || item.leave_file || null;
                    item.foto_masuk_url = item.foto_masuk_url || null;
                    item.foto_keluar_url = item.foto_keluar_url || null;
                    return item;
                });
                renderCalendar(year, month, riwayatCacheData);

                const now = new Date();
                const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
                const matchToday = riwayatCacheData.find(d => d.tanggal === todayStr);
                if (matchToday) {
                    selectCalendarDay(matchToday);
                } else if (riwayatCacheData.length > 0) {
                    selectCalendarDay(riwayatCacheData[riwayatCacheData.length - 1]);
                } else {
                    resetDetailCard(year, month);
                }
            } catch (err) {
                console.error('Riwayat error:', err);
                calEl.innerHTML = `
                    <div class="col-span-7 py-6 text-center space-y-2">
                        <div class="text-xs font-semibold text-rose-500">Gagal memuat riwayat.</div>
                        <button type="button" onclick="fetchRiwayatData(${year}, ${month})" class="text-[11px] font-bold text-gray-900 hover:underline">
                            Coba Lagi
                        </button>
                    </div>
                `;
            }
        }

        function renderCalendar(year, month, data) {
            const calEl = document.getElementById('calendarMatrix');
            calEl.innerHTML = '';

            const firstDayIndex = new Date(year, month - 1, 1).getDay();
            const daysInMonth = new Date(year, month, 0).getDate();
            const daysInPrevMonth = new Date(year, month - 1, 0).getDate();
            const adjustedFirstDay = (firstDayIndex === 0) ? 6 : firstDayIndex - 1;

            const dataMap = {};
            data.forEach(item => {
                const dateKey = (item.tanggal || '').split(/[T ]/)[0];
                if (dateKey) dataMap[dateKey] = item;
            });

            // Prev Month spillover
            for (let i = adjustedFirstDay - 1; i >= 0; i--) {
                const dayNum = daysInPrevMonth - i;
                const cell = document.createElement('div');
                cell.className = 'py-2 text-gray-300 text-xs';
                cell.textContent = dayNum;
                calEl.appendChild(cell);
            }

            const now = new Date();
            const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;

            for (let day = 1; day <= daysInMonth; day++) {
                const dayStr = String(day).padStart(2, '0');
                const monthStr = String(month).padStart(2, '0');
                const fullDate = `${year}-${monthStr}-${dayStr}`;
                const item = dataMap[fullDate];

                const dayOfWeek = new Date(year, month - 1, day).getDay();
                const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);

                const cell = document.createElement('div');
                cell.className = 'py-1 flex flex-col items-center justify-center cursor-pointer rounded-lg transition hover:bg-gray-100';
                cell.id = `cal-day-${fullDate}`;

                let dotHtml = '';
                if (item) {
                    if (item.jam_masuk) {
                        const dotColor = item.status_ketepatan === 'Terlambat' ? 'bg-rose-500' : 'bg-emerald-500';
                        dotHtml = `<span class="w-1.5 h-1.5 rounded-full ${dotColor} mt-0.5"></span>`;
                    } else if (item.izin === true || Number(item.izin) === 1) {
                        dotHtml = `<span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-0.5"></span>`;
                    } else {
                        dotHtml = `<span class="w-1.5 h-1.5 rounded-full bg-gray-400 mt-0.5"></span>`;
                    }
                } else {
                    dotHtml = `<span class="w-1.5 h-1.5 rounded-full bg-transparent mt-0.5"></span>`;
                }

                const isToday = (fullDate === todayStr);
                const todayRing = isToday ? ' ring-1 ring-gray-900 font-bold' : '';
                const textClass = isWeekend ? 'text-rose-400' : 'text-gray-700';

                cell.innerHTML = `
                    <span class="text-xs font-semibold ${textClass}${todayRing}">${day}</span>
                    ${dotHtml}
                `;

                cell.onclick = () => {
                    if (item) {
                        selectCalendarDay(item);
                    } else {
                        selectEmptyDay(fullDate, day, isWeekend);
                    }
                };

                calEl.appendChild(cell);
            }
        }

        function highlightCalendarCell(dateStr) {
            document.querySelectorAll('[id^="cal-day-"]').forEach(el => {
                el.classList.remove('bg-gray-900', 'text-white', 'shadow-xs');
                const span = el.querySelector('span:first-child');
                if (span) span.classList.remove('text-white');
            });

            const activeCell = document.getElementById(`cal-day-${dateStr}`);
            if (activeCell) {
                activeCell.classList.add('bg-gray-900', 'shadow-xs');
                const span = activeCell.querySelector('span:first-child');
                if (span) {
                    span.classList.remove('text-gray-700', 'text-rose-400');
                    span.classList.add('text-white');
                }
            }
        }

        function selectCalendarDay(item) {
            selectedDayRiwayat = item;
            const dateStr = (item.tanggal || '').split(/[T ]/)[0];
            highlightCalendarCell(dateStr);

            let tglFormatted = dateStr;
            if (dateStr) {
                const [y, m, d] = dateStr.split('-').map(Number);
                const tgl = new Date(y, m - 1, d);
                tglFormatted = tgl.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }

            document.getElementById('detailTanggalFormatted').textContent = tglFormatted;

            const badge = document.getElementById('detailStatusBadge');
            const masukEl = document.getElementById('detailJamMasuk');
            const pulangEl = document.getElementById('detailJamPulang');
            const durasiEl = document.getElementById('detailDurasi');
            const locationBox = document.getElementById('detailLocationBox');
            const locationText = document.getElementById('detailLocationText');
            const geofenceStatus = document.getElementById('detailGeofenceStatus');
            const photoSec = document.getElementById('detailPhotosSection');
            const photoMasukCard = document.getElementById('detailPhotoMasukCard');
            const photoKeluarCard = document.getElementById('detailPhotoKeluarCard');
            const imgMasuk = document.getElementById('detailImgMasuk');
            const imgKeluar = document.getElementById('detailImgKeluar');
            const timeMasuk = document.getElementById('detailFotoMasukTime');
            const timeKeluar = document.getElementById('detailFotoKeluarTime');
            const izinBox = document.getElementById('detailIzinBox');

            if (item.jam_masuk) {
                izinBox.classList.add('hidden');
                photoSec.classList.remove('hidden');
                if (locationBox) locationBox.classList.remove('hidden');

                if (item.status_ketepatan === 'Terlambat') {
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-800 border border-rose-200';
                    badge.textContent = 'Terlambat (' + (item.status_kerja ? 'WFO' : 'WFH') + ')';
                } else {
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200';
                    badge.textContent = 'Hadir ' + (item.status_kerja ? 'WFO' : 'WFH');
                }

                if (locationText && geofenceStatus) {
                    if (item.status_kerja) {
                        locationText.textContent = 'Presensi Dalam Gedung BPS';
                        geofenceStatus.textContent = 'Terverifikasi Geofence (WFO)';
                        geofenceStatus.className = 'text-[10px] text-emerald-600 font-semibold';
                    } else {
                        locationText.textContent = 'Presensi Di Luar Gedung BPS';
                        geofenceStatus.textContent = 'Di Luar Radius (WFH)';
                        geofenceStatus.className = 'text-[10px] text-amber-600 font-semibold';
                    }
                }

                masukEl.textContent = (item.jam_masuk || '--:--').substring(0, 5) + ' WIB';
                pulangEl.textContent = item.jam_pulang ? item.jam_pulang.substring(0, 5) + ' WIB' : '--:--';
                if (durasiEl) durasiEl.textContent = item.durasi_kerja || (item.jam_pulang ? '-' : 'Berjalan');

                if (item.foto_masuk_url) {
                    photoMasukCard.classList.remove('hidden');
                    imgMasuk.src = item.foto_masuk_url;
                    timeMasuk.textContent = (item.jam_masuk || '').substring(0, 5) + ' WIB';
                } else {
                    photoMasukCard.classList.add('hidden');
                }

                if (item.foto_keluar_url) {
                    photoKeluarCard.classList.remove('hidden');
                    imgKeluar.src = item.foto_keluar_url;
                    timeKeluar.textContent = (item.jam_pulang ? item.jam_pulang.substring(0, 5) : '-') + ' WIB';
                } else {
                    photoKeluarCard.classList.add('hidden');
                }
            } else if (item.izin === true || Number(item.izin) === 1) {
                badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200';
                badge.textContent = item.jenis_izin ? item.jenis_izin.nama : 'Izin';

                masukEl.textContent = '--:--';
                pulangEl.textContent = '--:--';
                if (durasiEl) durasiEl.textContent = '-';
                photoSec.classList.add('hidden');
                if (locationBox) locationBox.classList.add('hidden');

                izinBox.classList.remove('hidden');
                const izinKeterangan = item.keterangan_izin || (item.jenis_izin ? item.jenis_izin.nama : 'Pengajuan Izin');
                document.getElementById('detailIzinText').textContent = izinKeterangan;

                const fileContainer = document.getElementById('detailIzinFileContainer');
                const fileLink = document.getElementById('detailIzinFileLink');
                if (item.file_izin) {
                    fileContainer.classList.remove('hidden');
                    const cleanPath = item.file_izin.replace(/^\/?storage\//, '');
                    fileLink.href = '{{ url("/storage") }}/' + cleanPath;
                } else {
                    fileContainer.classList.add('hidden');
                }
            } else {
                badge.className = 'text-[10px] font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500';
                badge.textContent = item.status_kehadiran ? 'Hadir' : 'Tidak Hadir';
                masukEl.textContent = '--:--';
                pulangEl.textContent = '--:--';
                durasiEl.textContent = '-';
                photoSec.classList.add('hidden');
                if (locationBox) locationBox.classList.add('hidden');
                izinBox.classList.add('hidden');
            }
        }

        function selectEmptyDay(fullDate, day, isWeekend) {
            selectedDayRiwayat = null;
            highlightCalendarCell(fullDate);

            let tglFormatted = fullDate;
            if (fullDate) {
                const [y, m, d] = fullDate.split('-').map(Number);
                const tgl = new Date(y, m - 1, d);
                tglFormatted = tgl.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }

            document.getElementById('detailTanggalFormatted').textContent = tglFormatted;
            const badge = document.getElementById('detailStatusBadge');
            badge.className = 'text-[10px] font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500';
            badge.textContent = isWeekend ? 'Akhir Pekan' : 'Tidak Ada Data';

            document.getElementById('detailJamMasuk').textContent = '--:--';
            document.getElementById('detailJamPulang').textContent = '--:--';
            const durasiEl = document.getElementById('detailDurasi');
            if (durasiEl) durasiEl.textContent = '-';
            document.getElementById('detailPhotosSection').classList.add('hidden');
            document.getElementById('detailIzinBox').classList.add('hidden');
            const locationBox = document.getElementById('detailLocationBox');
            if (locationBox) locationBox.classList.add('hidden');
        }

        function resetDetailCard(year, month) {
            document.getElementById('detailTanggalFormatted').textContent = `Periode ${namaBulanIndo[month - 1]} ${year}`;
            const badge = document.getElementById('detailStatusBadge');
            badge.textContent = 'Pilih Hari';
            badge.className = 'text-[10px] font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500';
            document.getElementById('detailJamMasuk').textContent = '--:--';
            document.getElementById('detailJamPulang').textContent = '--:--';
            const durasiEl = document.getElementById('detailDurasi');
            if (durasiEl) durasiEl.textContent = '-';
            document.getElementById('detailPhotosSection').classList.add('hidden');
            document.getElementById('detailIzinBox').classList.add('hidden');
            const locationBox = document.getElementById('detailLocationBox');
            if (locationBox) locationBox.classList.add('hidden');
        }

        let userGpsLat = null;
        let userGpsLong = null;
        let pendingFormAction = null;

        function showGpsAlertModal() {
            document.getElementById('gpsAlertModal')?.classList.remove('hidden');
        }

        function closeGpsAlertModal() {
            document.getElementById('gpsAlertModal')?.classList.add('hidden');
        }

        function fetchUserGps(onSuccess, onError) {
            if (!navigator.geolocation) {
                if (onError) onError('Perangkat tidak mendukung geolokasi GPS.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    userGpsLat = pos.coords.latitude;
                    userGpsLong = pos.coords.longitude;

                    const latInput = document.getElementById('lat');
                    const longInput = document.getElementById('long');
                    const latKeluar = document.getElementById('latKeluar');
                    const longKeluar = document.getElementById('longKeluar');
                    if (latInput) latInput.value = userGpsLat;
                    if (longInput) longInput.value = userGpsLong;
                    if (latKeluar) latKeluar.value = userGpsLat;
                    if (longKeluar) longKeluar.value = userGpsLong;

                    const mapEl = document.getElementById('map');
                    if (mapEl) {
                        mapEl.innerHTML = `<iframe class="w-full h-full" src="https://maps.google.com/maps?q=${userGpsLat},${userGpsLong}&hl=id&z=17&output=embed" frameborder="0"></iframe>`;
                    }

                    const distanceEl = document.getElementById('distance');
                    if (gedLat && gedLong && gedRadius) {
                        const jarak = getDistanceFromLatLonInMeters(userGpsLat, userGpsLong, gedLat, gedLong);
                        if (distanceEl) distanceEl.textContent = jarak.toFixed(0) + ' meter';
                        applyAttendanceRadiusPolicy(jarak);
                    }

                    if (onSuccess) onSuccess(pos);
                },
                (err) => {
                    const distanceEl = document.getElementById('distance');
                    const statusGedungEl = document.getElementById('statusGedung');
                    if (distanceEl) distanceEl.textContent = 'GPS Nonaktif';
                    if (statusGedungEl) {
                        statusGedungEl.textContent = 'GPS Diperlukan';
                        statusGedungEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200';
                    }
                    if (onError) onError(err);
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        }

        function applyAttendanceRadiusPolicy(jarak) {
            const statusGedungEl = document.getElementById('statusGedung');
            const btnIn = document.getElementById('btnClockInAction');
            const btnOut = document.getElementById('btnClockOutAction');
            const warningBanner = document.getElementById('outsideRadiusWarningBanner');
            const warningText = document.getElementById('outsideRadiusWarningText');
            
            const isInside = jarak <= gedRadius;

            if (isInside) {
                // Di dalam area gedung: Hadir (WFO)
                if (statusGedungEl) {
                    statusGedungEl.textContent = 'Dalam Area';
                    statusGedungEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200';
                }
                if (warningBanner) warningBanner.classList.add('hidden');
                
                enableAttendanceButton(btnIn, 'emerald');
                enableAttendanceButton(btnOut, 'rose');
            } else {
                // Di luar radius gedung
                if (isWfhToday) {
                    // Diizinkan WFH oleh pegawai
                    if (statusGedungEl) {
                        statusGedungEl.textContent = 'Luar Area';
                        statusGedungEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200';
                    }
                    if (warningBanner) warningBanner.classList.add('hidden');

                    enableAttendanceButton(btnIn, 'emerald');
                    enableAttendanceButton(btnOut, 'rose');
                } else {
                    // TIDAK BISA ABSEN (Button tidak bisa dipencet)
                    if (statusGedungEl) {
                        statusGedungEl.textContent = 'Luar Area';
                        statusGedungEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200';
                    }
                    if (warningBanner) {
                        warningBanner.classList.remove('hidden');
                        if (warningText) {
                            warningText.innerHTML = `Anda berada <strong>${jarak.toFixed(0)} meter</strong> di luar jangkauan Gedung BPS. Anda hanya dapat melakukan presensi di lokasi kantor BPS.`;
                        }
                    }

                    disableAttendanceButton(btnIn, 'emerald');
                    disableAttendanceButton(btnOut, 'rose');
                }
            }
        }

        function enableAttendanceButton(btn, color) {
            if (!btn) return;
            btn.disabled = false;
            btn.classList.remove('opacity-40', 'grayscale', 'cursor-not-allowed', 'pointer-events-none');
            btn.classList.add('cursor-pointer', `btn-pulse-${color}`);
            btn.removeAttribute('aria-disabled');
        }

        function disableAttendanceButton(btn, color) {
            if (!btn) return;
            btn.disabled = true;
            btn.classList.add('opacity-40', 'grayscale', 'cursor-not-allowed');
            btn.classList.remove('cursor-pointer', `btn-pulse-${color}`);
            btn.setAttribute('aria-disabled', 'true');
        }

        function validateAndStartAbsen(formType) {
            pendingFormAction = formType;

            // Jika GPS sudah tersimpan dan valid
            if (userGpsLat && userGpsLong) {
                if (gedLat && gedLong && gedRadius) {
                    const jarak = getDistanceFromLatLonInMeters(userGpsLat, userGpsLong, gedLat, gedLong);
                    if (jarak > gedRadius && !isWfhToday) {
                        alert(`Presensi Tidak Dapat Dilakukan:\nAnda berada ${jarak.toFixed(0)} meter di luar radius kantor BPS dan tidak memiliki izin WFH dari pegawai. Anda hanya dapat melakukan presensi di lokasi kantor.`);
                        return;
                    }
                }

                const latInput = document.getElementById('lat');
                const longInput = document.getElementById('long');
                const latKeluar = document.getElementById('latKeluar');
                const longKeluar = document.getElementById('longKeluar');
                if (latInput) latInput.value = userGpsLat;
                if (longInput) longInput.value = userGpsLong;
                if (latKeluar) latKeluar.value = userGpsLat;
                if (longKeluar) longKeluar.value = userGpsLong;

                startCamera(formType);
                return;
            }

            // Jika belum ada, coba minta GPS dengan loading
            showLoading('Mendeteksi Lokasi GPS...', 'Sistem sedang memverifikasi titik koordinat GPS perangkat Anda...', 'generic');
            fetchUserGps(
                () => {
                    hideLoading();
                    if (gedLat && gedLong && gedRadius) {
                        const jarak = getDistanceFromLatLonInMeters(userGpsLat, userGpsLong, gedLat, gedLong);
                        if (jarak > gedRadius && !isWfhToday) {
                            alert(`Presensi Tidak Dapat Dilakukan:\nAnda berada ${jarak.toFixed(0)} meter di luar radius kantor BPS dan tidak memiliki izin WFH dari pegawai. Anda hanya dapat melakukan presensi di lokasi kantor.`);
                            return;
                        }
                    }
                    startCamera(formType);
                },
                (err) => {
                    hideLoading();
                    showGpsAlertModal();
                }
            );
        }

        function retryGpsLocation() {
            const btn = document.getElementById('btnRetryGps');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Memeriksa GPS...</span>';
            }

            showLoading('Mendeteksi Lokasi GPS...', 'Sedang meminta akses koordinat GPS...', 'generic');
            fetchUserGps(
                () => {
                    hideLoading();
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-arrows-rotate"></i><span>Aktifkan & Coba Lagi</span>';
                    }
                    closeGpsAlertModal();
                    if (pendingFormAction) {
                        startCamera(pendingFormAction);
                    }
                },
                (err) => {
                    hideLoading();
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-arrows-rotate"></i><span>Aktifkan & Coba Lagi</span>';
                    }
                }
            );
        }

        // ================= DOM INITIALIZATION =================
        document.addEventListener('DOMContentLoaded', () => {
            const btnClockIn = document.getElementById('btnClockInAction');
            const btnClockOut = document.getElementById('btnClockOutAction');
            const captureBtn = document.getElementById('captureBtn');
            const switchCamBtn = document.getElementById('switchCameraBtn');
            const closeCamBtn = document.getElementById('closeCameraBtn');
            const btnBukaPeta = document.getElementById('btnBukaPeta');

            // Load Riwayat Bulan Ini
            fetchRiwayatData(currentRiwayatDate.getFullYear(), currentRiwayatDate.getMonth() + 1);

            // Tab awal: presensi, riwayat, atau logbook (mis. setelah menyimpan logbook)
            const initTabName = @json($initialTab ?? 'absen');
            switchAbsenTab(initTabName);
            if (initTabName === 'logbook') {
                initLogbookTab(@json(request()->query('date')));
            } else if (initTabName === 'progres') {
                loadProgresData();
            }

            // Fetch Realtime Geolocation GPS awal
            fetchUserGps();

            // Trigger Buttons Kamera dengan Validasi GPS
            if (btnClockIn) {
                btnClockIn.addEventListener('click', () => {
                    validateAndStartAbsen('masuk');
                });
            }

            if (btnClockOut) {
                btnClockOut.addEventListener('click', () => {
                    validateAndStartAbsen('keluar');
                });
            }

            if (captureBtn) {
                captureBtn.addEventListener('click', () => {
                    if (!currentFormToSubmit || captureBtn.disabled) return;

                    // Validasi ketat bahwa koordinat GPS wajib ada sebelum submit
                    if (!userGpsLat || !userGpsLong) {
                        stopCamera();
                        captureBtn.disabled = false;
                        showGpsAlertModal();
                        return;
                    }

                    captureBtn.disabled = true;

                    const flash = document.getElementById('cameraFlash');
                    if (flash) {
                        flash.classList.remove('opacity-0');
                        flash.classList.add('opacity-100');
                        setTimeout(() => {
                            flash.classList.remove('opacity-100');
                            flash.classList.add('opacity-0');
                        }, 120);
                    }

                    showLoading('Mengambil Foto...', 'Sedang mengompresi dan menyiapkan foto...', 'camera');

                    setTimeout(() => {
                        try {
                            const base64 = compressCameraPhoto();
                            if (currentFormToSubmit === document.getElementById('formMasuk')) {
                                document.getElementById('fotoMasuk').value = base64;
                                document.getElementById('lat').value = userGpsLat;
                                document.getElementById('long').value = userGpsLong;
                            } else {
                                document.getElementById('fotoKeluar').value = base64;
                                document.getElementById('latKeluar').value = userGpsLat;
                                document.getElementById('longKeluar').value = userGpsLong;
                            }

                            showLoading('Mengirim Data Presensi...', 'Sedang mengunggah foto ke server. Mohon tunggu...', 'upload');
                            stopCamera();
                            currentFormToSubmit.submit();
                        } catch (error) {
                            hideLoading();
                            alert(error.message || 'Gagal memproses foto.');
                            captureBtn.disabled = false;
                        }
                    }, 60);
                });
            }

            const formIzinSiswa = document.getElementById('formIzinSiswa');
            if (formIzinSiswa) {
                formIzinSiswa.addEventListener('submit', () => {
                    const submitBtn = document.getElementById('btnSubmitIzin');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span>Mengirim...</span>';
                    }
                    closeModal('izinModal');
                    showLoading('Mengirim Permohonan Izin...', 'Sedang mengunggah berkas dan data izin...', 'upload');
                });
            }

            if (switchCamBtn) {
                switchCamBtn.addEventListener('click', async () => {
                    currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
                    await startCamera(currentFormToSubmit === document.getElementById('formMasuk') ? 'masuk' : 'keluar');
                });
            }

            if (closeCamBtn) {
                closeCamBtn.addEventListener('click', stopCamera);
            }

            if (btnBukaPeta) {
                btnBukaPeta.addEventListener('click', () => {
                    document.getElementById('mapModal')?.classList.remove('hidden');
                });
            }
        });
    </script>
</body>

</html>
