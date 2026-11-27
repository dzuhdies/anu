<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPS Magang - Portal Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }

        /* Container utama frame aplikasi */
        .app-container {
            width: 100vw;
            height: 100vh;
            background-color: #ffffff;
            display: flex;
            overflow: hidden;
        }

        .card {
            background: #ffffff;
            border: 1px solid #f0f2f5;
            border-radius: 0.875rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.03);
        }

        /* Modal transitions */
        .modal {
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }
        .modal.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .modal-content-box {
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s ease;
        }
        .modal.hidden .modal-content-box {
            transform: scale(0.96) translateY(8px);
            opacity: 0;
        }

        /* Sidebar nav active state */
        .sidebar-nav-link.active {
            background-color: #ffffff;
            color: #111827;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid #f3f4f6;
            font-weight: 600;
        }

        @media screen and (max-width: 768px) {
            .responsive-table thead {
                display: none;
            }
            .responsive-table tr {
                display: block;
                border: 1px solid #e5e7eb;
                margin-bottom: 0.75rem;
                background-color: #fff;
                border-radius: 0.75rem;
                padding: 0.875rem;
            }
            .responsive-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 0.75rem;
                padding: 0.5rem 0;
                border-bottom: 1px dashed #f3f4f6;
                text-align: right;
                word-break: break-word;
            }
            .responsive-table td:last-child {
                border-bottom: 0;
                padding-top: 0.75rem;
                margin-top: 0.25rem;
            }
            .responsive-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #9ca3af;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                text-align: left;
                flex-shrink: 0;
            }
            .responsive-table td.actions-cell {
                justify-content: space-between;
                border-bottom: 0;
            }
            .responsive-table td.actions-cell::before {
                content: "AKSI";
            }

            /* Custom Mobile Card Styling for Seleksi / Permohonan Magang (row-pendaftaran) */
            .responsive-table tr.row-pendaftaran {
                border: 1px solid #e2e8f0;
                margin-bottom: 1rem;
                background-color: #ffffff;
                border-radius: 1rem;
                padding: 1rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            }
            .responsive-table tr.row-pendaftaran td {
                padding: 0.5rem 0 !important;
                border-bottom: 1px dashed #f1f5f9;
                font-size: 0.75rem;
            }
            /* Header card: Pendaftar (Full width, No label, Left aligned) */
            .responsive-table tr.row-pendaftaran td[data-label="Pendaftar"] {
                display: block !important;
                width: 100%;
                text-align: left !important;
                padding: 0 0 0.75rem 0 !important;
                margin-bottom: 0.25rem;
                border-bottom: 1px solid #f1f5f9 !important;
            }
            .responsive-table tr.row-pendaftaran td[data-label="Pendaftar"]::before {
                display: none !important;
            }
            .responsive-table tr.row-pendaftaran td[data-label="Pendaftar"] > div {
                width: 100%;
                justify-content: flex-start !important;
                text-align: left !important;
            }
            /* Metadata items */
            .responsive-table tr.row-pendaftaran td[data-label="Divisi"],
            .responsive-table tr.row-pendaftaran td[data-label="Periode"],
            .responsive-table tr.row-pendaftaran td[data-label="Jumlah"],
            .responsive-table tr.row-pendaftaran td[data-label="Status"] {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                text-align: right !important;
            }
            /* Action button cell (Full width, No label) */
            .responsive-table tr.row-pendaftaran td.actions-cell {
                display: block !important;
                width: 100%;
                border-bottom: 0 !important;
                padding-top: 0.75rem !important;
                padding-bottom: 0 !important;
            }
            .responsive-table tr.row-pendaftaran td.actions-cell::before {
                display: none !important;
            }
            .responsive-table tr.row-pendaftaran td.actions-cell > div {
                width: 100%;
                justify-content: stretch !important;
            }
            .responsive-table tr.row-pendaftaran td.actions-cell button {
                width: 100%;
                justify-content: center;
                padding: 0.625rem 1rem;
                border-radius: 0.75rem;
                background-color: #111827;
                color: #ffffff;
                border-color: #111827;
                font-weight: 600;
                box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            }
            .responsive-table tr.row-pendaftaran td.actions-cell button i {
                color: #ffffff !important;
            }
            .responsive-table tr.row-pendaftaran td.actions-cell button:hover {
                background-color: #000000;
            }
        }
    </style>
    @include('partials.pwa')
</head>
<body class="bg-white h-screen overflow-hidden">

    <div class="app-container">

        <!-- ================= SIDEBAR (ADMIN STYLE) ================= -->
        <aside id="main-sidebar" class="w-[250px] bg-[#fafafa] border-r border-gray-100 flex flex-col shrink-0 fixed md:static inset-y-0 left-0 z-40 -translate-x-full md:translate-x-0 transition-transform duration-200">
            <!-- Brand / Logo BPS Kota Malang -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-gray-100 border-dashed mb-4 bg-white/70">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('logo-bps-malang.png') }}" alt="BPS Kota Malang" class="h-8 max-h-9 w-auto object-contain">
                </a>
                <button type="button" onclick="toggleSidebarMobile()" class="md:hidden text-gray-400 hover:text-black text-sm p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar px-4 space-y-6">
                <!-- Group: Main Menu -->
                <div>
                    <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Menu Utama</h3>
                    <ul class="space-y-1 text-sm font-medium">
                        <li>
                            <button onclick="showMainContent('pendaftaran'); closeSidebarMobile();" id="side-nav-pendaftaran"
                                class="sidebar-nav-link active w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-id-card-clip w-4 text-center"></i> Seleksi Pendaftaran</span>
                                @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-blue-500 text-white">{{ $pendingCount }}</span>
                                @endif
                            </button>
                        </li>
                        <li>
                            <button onclick="showMainContent('absensi'); closeSidebarMobile();" id="side-nav-absensi"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-clipboard-user w-4 text-center"></i> Rekap Presensi</span>

                            </button>
                        </li>
                        <li>
                            <button onclick="showMainContent('siswa'); closeSidebarMobile();" id="side-nav-siswa"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-user-graduate w-4 text-center"></i> Peserta Magang</span>

                            </button>
                        </li>
                        <li>
                            <button onclick="showMainContent('kalender'); closeSidebarMobile();" id="side-nav-kalender"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-calendar-days w-4 text-center"></i> Kalender Sisa Kuota</span>
                            </button>
                        </li>
                        <li>
                            <button onclick="showMainContent('logbook'); closeSidebarMobile();" id="side-nav-logbook"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-book-open w-4 text-center text-black-600"></i> Logbook Magang</span>
                                @if(($logbookPendingCount ?? 0) > 0)
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-amber-500 text-white">{{ $logbookPendingCount }}</span>
                                @endif
                            </button>
                        </li>
                        <li>
                            <button onclick="showMainContent('history'); closeSidebarMobile();" id="side-nav-history"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-clock-rotate-left w-4 text-center text-black-600"></i> History</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Group: Informasi Penugasan -->
                <div>
                    <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Penugasan Kerja</h3>
                    <div class="p-3 bg-white border border-gray-100 rounded-xl space-y-2.5 text-xs">
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Divisi Penugasan</span>
                            <span class="font-bold text-gray-800 flex items-center gap-1.5 mt-0.5">

                                {{ $divisiPegawai ? $divisiPegawai->nama_divisi : 'Semua Divisi' }}
                            </span>
                        </div>
                        <div class="pt-2 border-t border-gray-50">
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Lokasi Gedung</span>
                            <span class="font-medium text-gray-700 flex items-center gap-1.5 mt-0.5">

                                {{ $gedung->nama ?? 'BPS Kota Malang' }}
                            </span>
                        </div>
                        <div class="pt-2 border-t border-gray-50">
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Batas Jam Telat</span>
                            <span class="font-mono font-semibold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded text-[11px] inline-block mt-0.5">
                                {{ $jamTelat ?? '08:16' }} WIB
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Sidebar: Profile & Logout -->
            <div class="p-3 border-t border-gray-100 mt-auto bg-[#fafafa]">
                <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-gray-100 shadow-xs">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-gray-900 text-white font-bold flex items-center justify-center text-xs shrink-0">
                            {{ strtoupper(substr(Auth::user()->pegawaiProfile->nama_lengkap ?? Auth::user()->username, 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-semibold text-gray-900 truncate">{{ Auth::user()->pegawaiProfile->nama_lengkap ?? Auth::user()->username }}</p>
                            <p class="text-[10px] text-gray-400">Pegawai &bull; {{ $divisiPegawai ? $divisiPegawai->nama_divisi : 'Semua Divisi' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                        @csrf
                        <button type="submit" title="Logout"
                            class="w-7 h-7 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" onclick="toggleSidebarMobile()" class="fixed inset-0 bg-black/40 backdrop-blur-xs hidden md:hidden z-30 transition-opacity"></div>

        <!-- ================= MAIN CONTENT (ADMIN STYLE) ================= -->
        <main class="flex-1 flex flex-col bg-[#fbfcfd] overflow-hidden">

            <!-- Top Header -->
            <header class="h-16 flex items-center justify-between px-4 sm:px-8 border-b border-gray-100 border-dashed shrink-0 bg-white">
                <!-- Breadcrumbs & Mobile Trigger -->
                <div class="flex items-center gap-2 text-sm font-medium min-w-0">
                    <button onclick="toggleSidebarMobile()" class="md:hidden w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-500 mr-1 shrink-0 hover:text-gray-900 hover:border-gray-400 transition" title="Buka Menu Sidebar">
                        <i class="fa-solid fa-bars text-sm"></i>
                    </button>
                    <span class="text-gray-400 hidden sm:inline shrink-0">BPS Magang</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 hidden sm:inline shrink-0"></i>
                    <i class="fa-solid fa-chart-pie text-gray-400 shrink-0"></i>
                    <span id="breadcrumb-active-title" class="text-gray-800 font-semibold truncate max-w-[130px] sm:max-w-none">Seleksi Pendaftaran Magang</span>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">


                    <button onclick="showMainContent('pendaftaran')" title="Lihat Pendaftaran Menunggu Review"
                        class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-500 hover:text-black bg-white relative transition">
                        <i class="fa-regular fa-bell text-sm"></i>
                        @if(isset($pendingCount) && $pendingCount > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-blue-500 text-white rounded-full text-[9px] font-bold flex items-center justify-center animate-pulse">
                            {{ $pendingCount }}
                        </span>
                        @endif
                    </button>

                    <!-- Mobile Logout Quick Action -->
                    <form method="POST" action="{{ route('logout') }}" class="md:hidden" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                        @csrf
                        <button type="submit" title="Keluar / Logout"
                            class="w-8 h-8 rounded-lg border border-gray-200 text-gray-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                </div>
            </header>

            @php
                $selectedYear = request('bulan') ? \Carbon\Carbon::parse(request('bulan'))->year : ($tanggal ? \Carbon\Carbon::parse($tanggal)->year : now()->year);
                $selectedMonth = request('bulan') ? \Carbon\Carbon::parse(request('bulan'))->month : ($tanggal ? \Carbon\Carbon::parse($tanggal)->month : now()->month);
                $activeTanggal = request('tanggal', $tanggal);
                $isRangeMode = ($displayMode === 'range' || (isset($dari) && isset($ke) && $dari !== $ke) || $displayMode === 'monthly' || request()->filled('bulan'));
                $isMonthlyMode = ($displayMode === 'monthly' || request()->filled('bulan'));
            @endphp

            <!-- Scrollable Dashboard Content -->
            <div class="flex-1 overflow-y-auto custom-scrollbar px-3.5 sm:px-8 pt-4 sm:pt-5 pb-24 md:pb-6 space-y-5 sm:space-y-6">

                <!-- Alerts Notification -->
                @if (session('success'))
                <div class="card p-3.5 bg-emerald-50/70 border-emerald-100 flex items-center justify-between text-emerald-900 animate-fade-in shrink-0">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 p-1">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>
                @endif

                @php
                    $pegawaiModalFieldMap = [
                        'pendaftaranRejectModal' => ['catatan_admin'],
                        'rejectResignationModal' => ['catatan_admin', 'catatan'],
                        'pendaftaranRescheduleModal' => ['tanggal_mulai_saran', 'tanggal_selesai_saran', 'catatan_revisi_jadwal'],
                    ];
                    $activePegawaiModal = session('modal_error_target');
                    if (!$activePegawaiModal && isset($errors) && $errors->any()) {
                        foreach ($pegawaiModalFieldMap as $mId => $fields) {
                            if ($errors->hasAny($fields)) {
                                $activePegawaiModal = $mId;
                                break;
                            }
                        }
                    }
                @endphp

                @if (session('error') && !$activePegawaiModal)
                <div class="card p-3.5 bg-rose-50/70 border-rose-100 flex items-center justify-between text-rose-900 animate-fade-in shrink-0">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 p-1">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>
                @endif

                @if ($errors->any() && !$activePegawaiModal)
                <div class="card p-4 bg-rose-50/70 border-rose-100 text-rose-900 shrink-0">
                    <div class="flex items-center gap-2 mb-1.5 font-semibold text-xs sm:text-sm">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                        <span>Harap perbaiki beberapa input berikut:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs text-rose-700 space-y-0.5 ml-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (session('info'))
                <div class="card p-3.5 bg-blue-50/70 border-blue-100 flex items-center justify-between text-blue-900 animate-fade-in shrink-0">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm">
                        <i class="fa-solid fa-circle-info text-blue-600"></i>
                        <span class="font-medium">{{ session('info') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-800 p-1">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>
                @endif

                <!-- ================= VIEW 1: PENDAFTARAN & SELEKSI ================= -->
                <div id="main-content-pendaftaran" class="main-content-view space-y-6">

                    <!-- Stats Cards Pendaftaran -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="card p-4">
                            <span class="text-xs text-gray-400 block mb-1">Total Permohonan</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ $pendaftarans->count() }}</span>
                                <span class="text-xs text-gray-500">Berkas</span>
                            </div>
                        </div>
                        <div class="card p-4">
                            <span class="text-xs text-gray-400 block mb-1">Menunggu Review</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl sm:text-2xl font-bold text-amber-600">{{ $pendingCount }}</span>
                                <span class="text-xs text-gray-500">Pending</span>
                            </div>
                        </div>
                        <div class="card p-4">
                            <span class="text-xs text-gray-400 block mb-1">Revisi Jadwal</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl sm:text-2xl font-bold text-blue-600">{{ $pendaftarans->where('status', 'rescheduled')->count() }}</span>
                                <span class="text-xs text-gray-500">Usulan</span>
                            </div>
                        </div>
                        <div class="card p-4">
                            <span class="text-xs text-gray-400 block mb-1">Diterima / Aktif</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl sm:text-2xl font-bold text-emerald-600">{{ $pendaftarans->where('status', 'approved')->count() }}</span>
                                <span class="text-xs text-gray-500">Disetujui</span>
                            </div>
                        </div>
                    </div>

                    <!-- Table Card Pendaftaran -->
                    <div class="card overflow-hidden">
                        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Daftar Permohonan Magang</h3>
                                <p class="text-xs text-gray-400">
                                    @if($divisiPegawai)
                                        Menampilkan permohonan seleksi untuk <strong>Divisi {{ $divisiPegawai->nama_divisi }}</strong>
                                    @else
                                        Menampilkan seluruh permohonan magang untuk semua divisi
                                    @endif
                                </p>
                            </div>

                            <!-- Filter status tabs -->
                            <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-medium">
                                <button onclick="filterPendaftaranTable('all')" id="btn-filter-pendaftaran-all"
                                    class="filter-pendaftaran-btn px-3 py-1 rounded-md bg-gray-100 text-gray-900 font-semibold transition">
                                    Semua ({{ $pendaftarans->count() }})
                                </button>
                                <button onclick="filterPendaftaranTable('pending')" id="btn-filter-pendaftaran-pending"
                                    class="filter-pendaftaran-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition">
                                    Menunggu ({{ $pendingCount }})
                                </button>
                                <button onclick="filterPendaftaranTable('rescheduled')" id="btn-filter-pendaftaran-rescheduled"
                                    class="filter-pendaftaran-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition">
                                    Revisi Jadwal ({{ $pendaftarans->where('status', 'rescheduled')->count() }})
                                </button>
                                <button onclick="filterPendaftaranTable('approved')" id="btn-filter-pendaftaran-approved"
                                    class="filter-pendaftaran-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition">
                                    Disetujui ({{ $pendaftarans->where('status', 'approved')->count() }})
                                </button>
                                <button onclick="filterPendaftaranTable('rejected')" id="btn-filter-pendaftaran-rejected"
                                    class="filter-pendaftaran-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition">
                                    Ditolak ({{ $pendaftarans->where('status', 'rejected')->count() }})
                                </button>
                                <button onclick="filterPendaftaranTable('cancelled')" id="btn-filter-pendaftaran-cancelled"
                                    class="filter-pendaftaran-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition">
                                    Dibatalkan ({{ $pendaftarans->where('status', 'cancelled')->count() }})
                                </button>
                                <button onclick="filterPendaftaranTable('resignation_requested')" id="btn-filter-pendaftaran-resignation_requested"
                                    class="filter-pendaftaran-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition">
                                    Undur Diri ({{ $pendaftarans->where('status', 'resignation_requested')->count() }})
                                </button>
                                <button onclick="filterPendaftaranTable('resigned')" id="btn-filter-pendaftaran-resigned"
                                    class="filter-pendaftaran-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition">
                                    Mengundurkan Diri ({{ $pendaftarans->where('status', 'resigned')->count() }})
                                </button>
                            </div>
                        </div>

                        <!-- Table Content -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left text-gray-600 responsive-table">
                                <thead class="text-[11px] text-gray-400 uppercase bg-[#fafafa] font-semibold border-b border-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3.5">Calon Peserta / Kelompok</th>
                                        <th scope="col" class="px-6 py-3.5">Divisi Tujuan</th>
                                        <th scope="col" class="px-6 py-3.5">Periode Magang</th>
                                        <th scope="col" class="px-6 py-3.5 text-center">Jumlah</th>
                                        <th scope="col" class="px-6 py-3.5 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3.5 text-right">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($pendaftarans as $p)
                                    <tr class="hover:bg-gray-50/70 transition-colors row-pendaftaran search-row" data-status="{{ $p->status }}">
                                        <td data-label="Pendaftar" class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-lg {{ $p->tipe === 'kelompok' ? 'bg-purple-50 text-purple-600 border border-purple-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }} font-bold flex items-center justify-center text-xs shrink-0">
                                                    @if($p->tipe === 'kelompok')
                                                    <i class="fa-solid fa-users"></i>
                                                    @else
                                                    <i class="fa-solid fa-user"></i>
                                                    @endif
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="font-bold text-gray-900 text-sm">
                                                        {{ $p->tipe === 'kelompok' ? $p->nama_kelompok : ($p->anggotas->first()->nama_lengkap ?? $p->ketua->username) }}
                                                    </div>
                                                    <div class="text-[11px] text-gray-500">
                                                        Pendaftar: <strong>{{ $p->anggotas->first()->nama_lengkap ?? '-' }}</strong> ({{ '@' . $p->ketua->username }})
                                                    </div>
                                                    <div class="text-[10px] text-gray-400">
                                                        {{ $p->anggotas->first()->asal_univ ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td data-label="Divisi" class="px-6 py-4 font-semibold text-gray-800">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-teal-50 text-teal-800 text-xs border border-teal-100">
                                                <i class="fa-solid fa-sitemap text-teal-600 text-[10px]"></i>
                                                {{ $p->divisi->nama_divisi }}
                                            </span>
                                        </td>

                                        <td data-label="Periode" class="px-6 py-4">
                                            <div class="font-semibold text-gray-900">
                                                {{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}
                                            </div>
                                            <div class="text-[11px] text-blue-600 font-medium">
                                                {{ $p->durasi_hari }} Hari Kalender
                                            </div>
                                        </td>

                                        <td data-label="Jumlah" class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                {{ $p->anggotas->count() }} Orang
                                            </span>
                                        </td>

                                        <td data-label="Status" class="px-6 py-4 text-center">
                                            @if($p->status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                <span>Menunggu</span>
                                            </span>
                                            @elseif($p->status === 'rescheduled')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200" title="Usulan Jadwal Baru: {{ $p->tanggal_mulai_saran ? $p->tanggal_mulai_saran->format('d/m/Y') : '' }} - {{ $p->tanggal_selesai_saran ? $p->tanggal_selesai_saran->format('d/m/Y') : '' }}">
                                                <i class="fa-solid fa-clock-rotate-left text-blue-600 text-[10px]"></i>
                                                <span>Menunggu ACC Peserta</span>
                                            </span>
                                            @elseif($p->status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <i class="fa-solid fa-check text-emerald-600 text-[10px]"></i>
                                                <span>Disetujui</span>
                                            </span>
                                            @if($p->is_over_quota)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300 ml-1" title="Diterima Melebihi Kuota Resmi Divisi">
                                                <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                                                <span>Melebihi Kuota</span>
                                            </span>
                                            @endif
                                            @elseif($p->status === 'resignation_requested')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-800 border border-purple-200">
                                                <i class="fa-solid fa-file-signature text-purple-600 text-[10px]"></i>
                                                <span>Pengajuan Undur Diri</span>
                                            </span>
                                            @elseif($p->status === 'resigned')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                                <i class="fa-solid fa-user-xmark text-gray-500 text-[10px]"></i>
                                                <span>Mengundurkan Diri</span>
                                            </span>
                                            @elseif($p->status === 'cancelled')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200" title="{{ $p->catatan_admin ?: 'Dibatalkan oleh pemohon' }}">
                                                <i class="fa-solid fa-ban text-gray-500 text-[10px]"></i>
                                                <span>Dibatalkan</span>
                                            </span>
                                            @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200" title="{{ $p->alasan_penolakan_peserta ? 'Alasan Peserta: ' . $p->alasan_penolakan_peserta : $p->catatan_admin }}">
                                                <i class="fa-solid fa-xmark text-rose-600 text-[10px]"></i>
                                                <span>{{ $p->alasan_penolakan_peserta ? 'Ditolak Peserta' : 'Ditolak' }}</span>
                                            </span>
                                            @endif
                                        </td>

                                        <td data-label="Aksi" class="px-6 py-4 text-right actions-cell">
                                            <div class="flex items-center justify-end">
                                                <!-- Detail Button (Aksi Revisi, Tolak, dan Terima ada di dalam Detail) -->
                                                <button type="button" onclick="openDetailPendaftaran({{ $p->id }})"
                                                    class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white hover:bg-gray-50 text-gray-700 font-semibold text-xs transition flex items-center gap-1.5 shadow-xs hover:border-gray-300" title="Buka Detail & Tindakan Permohonan">
                                                    <i class="fa-solid fa-eye text-xs text-gray-500"></i>
                                                    <span>Detail</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <i class="fa-solid fa-inbox text-3xl text-gray-300"></i>
                                                <p class="font-medium text-xs">Tidak ada data pendaftaran magang pada kategori ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- ================= VIEW 2: REKAP PRESENSI & KALENDER ================= -->
                <div id="main-content-absensi" class="main-content-view hidden space-y-6">

                    <!-- Layout Presensi: 1/3 (Col-4) Mini Kalender & Filter, 2/3 (Col-8) Tabel Kehadiran -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- Left Column: Kalender Interaktif & Filter Form -->
                        <div class="lg:col-span-4 space-y-5">

                            <!-- Card Mini Calendar -->
                            <div class="card p-5">
                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-gray-900"></span>
                                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Kalender Presensi</h3>
                                    </div>
                                    <span class="text-[10px] font-semibold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Pilih Tanggal</span>
                                </div>

                                <!-- Month Navigation -->
                                <div class="flex items-center justify-between mb-3 px-1">
                                    <button type="button" onclick="changeCalMonth(-1)" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-500 flex items-center justify-center transition">
                                        <i class="fas fa-chevron-left text-xs"></i>
                                    </button>
                                    <span id="calMonthTitle" class="text-xs font-bold text-gray-800">
                                        {{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->translatedFormat('F Y') }}
                                    </span>
                                    <button type="button" onclick="changeCalMonth(1)" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-500 flex items-center justify-center transition">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </button>
                                </div>

                                <!-- Days Header -->
                                <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-gray-400 uppercase mb-2">
                                    <span>Sen</span>
                                    <span>Sel</span>
                                    <span>Rab</span>
                                    <span>Kam</span>
                                    <span>Jum</span>
                                    <span class="text-rose-500">Sab</span>
                                    <span class="text-rose-500">Min</span>
                                </div>

                                <!-- Calendar Matrix Days -->
                                <div id="calendarMatrixDays" class="grid grid-cols-7 gap-1 text-center text-xs">
                                    <!-- Diisi oleh JS renderCalendarMatrix -->
                                </div>

                            </div>

                            <!-- Card Filter Rentang Tanggal (dd/mm/yyyy) -->
                            <div class="card p-5">
                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="fa-solid fa-filter text-teal-600"></i>
                                        <span>Filter Periode Presensi</span>
                                    </h3>
                                    @if(request('dari') || request('ke') || request('tanggal') || request('bulan'))
                                        <a href="{{ route('pegawai.absensi.rekap') }}" class="text-[11px] font-medium text-rose-600 hover:underline">Reset</a>
                                    @endif
                                </div>

                                <form id="filterForm" action="{{ route('pegawai.absensi.rekap') }}" method="GET" class="space-y-3.5 text-xs">
                                    <input type="hidden" name="tab" value="absensi">

                                    <div class="space-y-2.5">
                                        <div>
                                            <label class="block font-semibold text-gray-700 mb-1">
                                                <i class="fa-regular fa-calendar text-gray-400 mr-1"></i> Dari Tanggal (dd/mm/yyyy)
                                            </label>
                                            <input type="date" name="dari" id="inputFilterDari" value="{{ $dari ?? $tanggal }}"
                                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-white font-medium text-gray-800 shadow-2xs">
                                        </div>

                                        <div>
                                            <label class="block font-semibold text-gray-700 mb-1">
                                                <i class="fa-regular fa-calendar-check text-gray-400 mr-1"></i> Sampai Tanggal / Ke (dd/mm/yyyy)
                                            </label>
                                            <input type="date" name="ke" id="inputFilterKe" value="{{ $ke ?? $tanggal }}"
                                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-white font-medium text-gray-800 shadow-2xs">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 pt-0.5">
                                        <button type="button" onclick="setPegawaiFilterPreset('today')" class="py-1.5 px-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-[11px] transition text-center">
                                            Hari Ini
                                        </button>
                                        <button type="button" onclick="setPegawaiFilterPreset('this_month')" class="py-1.5 px-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-[11px] transition text-center">
                                            Bulan Ini
                                        </button>
                                    </div>

                                    <button type="submit" class="w-full py-2.5 rounded-lg bg-gray-900 hover:bg-black text-white font-semibold text-xs transition shadow-xs flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                                        <span>Terapkan Filter</span>
                                    </button>
                                </form>
                            </div>

                        </div>

                        <!-- Right Column: Table Rekap Presensi -->
                        <div class="lg:col-span-8 card overflow-hidden">

                            <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-[#fafafa]">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">
                                        @if($displayMode === 'range' || (isset($dari) && isset($ke) && $dari !== $ke))
                                            Rekap Presensi Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} &mdash; {{ \Carbon\Carbon::parse($ke)->format('d/m/Y') }}
                                        @elseif($isMonthlyMode)
                                            Rekap Bulanan: {{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->translatedFormat('F Y') }}
                                        @elseif($activeTanggal)
                                            Presensi Harian: {{ \Carbon\Carbon::parse($activeTanggal)->translatedFormat('d F Y') }}
                                        @else
                                            Seluruh Catatan Presensi Siswa
                                        @endif
                                    </h3>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left text-gray-600 responsive-table">
                                    <thead class="text-[11px] text-gray-400 uppercase bg-[#fafafa] font-semibold border-b border-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3.5">Nama Siswa</th>
                                            @if($isRangeMode)
                                                <th scope="col" class="px-6 py-3.5">Tanggal</th>
                                            @endif
                                            <th scope="col" class="px-6 py-3.5">Jam Masuk</th>
                                            <th scope="col" class="px-6 py-3.5">Jam Pulang</th>
                                            <th scope="col" class="px-6 py-3.5 text-center">Status</th>
                                            <th scope="col" class="px-6 py-3.5 text-right">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @php $hasAnyMonthlyRecord = false; @endphp

                                        @if(!$isRangeMode)
                                            <!-- Mode Harian: 1 Baris per Siswa -->
                                            @forelse ($siswas as $siswa)
                                                @php
                                                    $targetDateStr = $dari ?: ($activeTanggal ?: now()->toDateString());
                                                    $absensi = $siswa->absensis->first(function ($att) use ($targetDateStr) {
                                                        $attDate = $att->date ? (is_string($att->date) ? substr($att->date, 0, 10) : $att->date->format('Y-m-d')) : null;
                                                        return $attDate === $targetDateStr;
                                                    });
                                                    $isHadir = (bool)$absensi;
                                                    $isIzin = $absensi && $absensi->izin;
                                                    $isTerlambat = $absensi && ($absensi->status_ketepatan === 'Terlambat');

                                                    $pProfile = $siswa->user?->pesertaProfile;
                                                    $cvUrl = $pProfile?->cv_path ? (str_starts_with($pProfile->cv_path, 'http') ? $pProfile->cv_path : asset('storage/' . $pProfile->cv_path)) : null;
                                                    $portoUrl = $pProfile?->portofolio_path ? (str_starts_with($pProfile->portofolio_path, 'http') ? $pProfile->portofolio_path : asset('storage/' . $pProfile->portofolio_path)) : ($pProfile?->portofolio_url ?? null);

                                                    $siswaMagangData = [
                                                        'id' => $siswa->id,
                                                        'nama' => $siswa->nama_lengkap,
                                                        'username' => $siswa->user?->username ?? '-',
                                                        'email' => $siswa->user?->email ?? '-',
                                                        'asal' => $siswa->asal_pkl ?? ($pProfile?->asal_univ ?? '-'),
                                                        'jurusan' => $siswa->major ?? ($pProfile?->jurusan ?? '-'),
                                                        'no_hp' => $pProfile?->no_hp ?? '-',
                                                        'divisi' => $siswa->divisi?->nama_divisi ?? '-',
                                                        'gedung' => $siswa->gedung?->nama ?? ($gedung->nama ?? 'Kantor BPS'),
                                                        'tanggal_mulai' => $siswa->tanggal_mulai ? \Carbon\Carbon::parse($siswa->tanggal_mulai)->format('d/m/Y') : '-',
                                                        'tanggal_selesai' => $siswa->tanggal_selesai ? \Carbon\Carbon::parse($siswa->tanggal_selesai)->format('d/m/Y') : '-',
                                                        'cv_url' => $cvUrl,
                                                        'porto_url' => $portoUrl,
                                                        'wfh_start_date' => $siswa->wfh_start_date ? \Carbon\Carbon::parse($siswa->wfh_start_date)->format('Y-m-d') : null,
                                                        'wfh_end_date' => $siswa->wfh_end_date ? \Carbon\Carbon::parse($siswa->wfh_end_date)->format('Y-m-d') : null,
                                                        'is_wfh_today' => (bool)$siswa->isWfhOn(),
                                                        'wfh_notes' => $siswa->wfh_notes ?? '',
                                                    ];

                                                    $absenData = $absensi ? [
                                                        'id' => $absensi->id,
                                                        'nama' => $siswa->nama_lengkap,
                                                        'asal' => $siswa->asal_pkl,
                                                        'divisi' => $siswa->divisi?->nama_divisi ?? '-',
                                                        'tanggal' => \Carbon\Carbon::parse($absensi->tanggal ?? ($activeTanggal ?? now()))->translatedFormat('l, d F Y'),
                                                        'jam_masuk' => $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : null,
                                                        'jam_pulang' => $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : null,
                                                        'durasi_kerja' => $absensi->durasi_kerja ?? '-',
                                                        'status_ketepatan' => $absensi->status_ketepatan ?? 'Tepat Waktu',
                                                        'status_kerja' => $absensi->status_kerja ?? 'WFO',
                                                        'lokasi_lat' => $absensi->lokasi_lat ?? $absensi->check_in_latitude ?? $absensi->lokasi_masuk_lat,
                                                        'lokasi_long' => $absensi->lokasi_long ?? $absensi->check_in_longitude ?? $absensi->lokasi_masuk_long,
                                                        'foto_masuk_url' => $absensi->foto_masuk_url ?? null,
                                                        'foto_keluar_url' => $absensi->foto_keluar_url ?? null,
                                                        'is_izin' => (bool)$absensi->izin,
                                                        'is_terlambat' => $isTerlambat,
                                                        'jenis_izin' => $absensi->jenisIzin->nama ?? null,
                                                        'keterangan_izin' => $absensi->keterangan_izin ?? null,
                                                        'file_izin_url' => $absensi->file_izin ? asset('storage/' . $absensi->file_izin) : null,
                                                        'siswa_info' => $siswaMagangData,
                                                    ] : null;

                                                    $b64Siswa = base64_encode(json_encode($siswaMagangData, JSON_UNESCAPED_UNICODE));
                                                    $b64Absen = $absenData ? base64_encode(json_encode($absenData, JSON_UNESCAPED_UNICODE)) : null;
                                                @endphp
                                                <tr class="hover:bg-gray-50/70 transition-colors search-row">
                                                    <td data-label="Siswa" class="px-6 py-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-lg bg-gray-900 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                                                {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <div class="font-bold text-gray-900">{{ $siswa->nama_lengkap }}</div>

                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td data-label="Masuk" class="px-6 py-4 font-mono font-medium text-gray-800">
                                                        @if($isHadir)
                                                            <div class="flex items-center gap-1.5">
                                                                <i class="fa-regular fa-clock text-emerald-600 text-[10px]"></i>
                                                                <span>{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '--:--' }}</span>
                                                            </div>
                                                        @else
                                                            <span class="text-gray-300">--:--</span>
                                                        @endif
                                                    </td>

                                                    <td data-label="Pulang" class="px-6 py-4 font-mono font-medium text-gray-800">
                                                        @if($isHadir && $absensi->jam_pulang)
                                                            <div class="flex items-center gap-1.5">
                                                                <i class="fa-regular fa-clock text-blue-600 text-[10px]"></i>
                                                                <span>{{ \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') }}</span>
                                                            </div>
                                                        @else
                                                            <span class="text-gray-300">--:--</span>
                                                        @endif
                                                    </td>

                                                    <td data-label="Status" class="px-6 py-4 text-center">
                                                        @if(!$isHadir)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                                Belum Hadir
                                                            </span>
                                                        @elseif($isIzin)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                                Izin: {{ $absensi->jenisIzin->nama ?? 'Resmi' }}
                                                            </span>
                                                        @elseif($isTerlambat)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                                                Terlambat
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                                Tepat Waktu
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td data-label="Aksi" class="px-6 py-4 text-right actions-cell">
                                                        <div class="flex items-center justify-end gap-1.5">
                                                            @if($isHadir)
                                                                <button type="button" onclick="openDetailPresensiModal(this)" data-absen="{{ $b64Absen }}"
                                                                    class="px-2.5 py-1.5 border border-gray-200 rounded-lg bg-white hover:bg-gray-50 text-gray-700 font-medium transition flex items-center gap-1 shadow-xs" title="Lihat Selfie & Lokasi Presensi">
                                                                    <i class="fa-solid fa-camera text-xs text-blue-600"></i>
                                                                    <span>Bukti</span>
                                                                </button>
                                                            @else
                                                                <span class="text-xs text-gray-400 italic">Belum Hadir</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                                        Belum ada siswa magang terdaftar di divisi/gedung ini.
                                                    </td>
                                                </tr>
                                            @endforelse

                                        @else
                                            <!-- Mode Bulanan: Menampilkan Riwayat Presensi Lengkap -->
                                            @foreach($siswas as $siswa)
                                                @foreach($siswa->absensis as $absensi)
                                                    @php
                                                        $hasAnyMonthlyRecord = true;
                                                        $isIzin = (bool)$absensi->izin;
                                                        $isTerlambat = ($absensi->status_ketepatan === 'Terlambat');
                                                        $tglCarbon = \Carbon\Carbon::parse($absensi->tanggal);

                                                        $pProfile = $siswa->user?->pesertaProfile;
                                                        $cvUrl = $pProfile?->cv_path ? (str_starts_with($pProfile->cv_path, 'http') ? $pProfile->cv_path : asset('storage/' . $pProfile->cv_path)) : null;
                                                        $portoUrl = $pProfile?->portofolio_path ? (str_starts_with($pProfile->portofolio_path, 'http') ? $pProfile->portofolio_path : asset('storage/' . $pProfile->portofolio_path)) : ($pProfile?->portofolio_url ?? null);

                                                        $siswaMagangData = [
                                                            'id' => $siswa->id,
                                                            'nama' => $siswa->nama_lengkap,
                                                            'username' => $siswa->user?->username ?? '-',
                                                            'email' => $siswa->user?->email ?? '-',
                                                            'asal' => $siswa->asal_pkl ?? ($pProfile?->asal_univ ?? '-'),
                                                            'jurusan' => $siswa->major ?? ($pProfile?->jurusan ?? '-'),
                                                            'no_hp' => $pProfile?->no_hp ?? '-',
                                                            'divisi' => $siswa->divisi?->nama_divisi ?? '-',
                                                            'gedung' => $siswa->gedung?->nama ?? ($gedung->nama ?? 'Kantor BPS'),
                                                            'tanggal_mulai' => $siswa->tanggal_mulai ? \Carbon\Carbon::parse($siswa->tanggal_mulai)->format('d/m/Y') : '-',
                                                            'tanggal_selesai' => $siswa->tanggal_selesai ? \Carbon\Carbon::parse($siswa->tanggal_selesai)->format('d/m/Y') : '-',
                                                            'cv_url' => $cvUrl,
                                                            'porto_url' => $portoUrl,
                                                            'wfh_start_date' => $siswa->wfh_start_date ? \Carbon\Carbon::parse($siswa->wfh_start_date)->format('Y-m-d') : null,
                                                            'wfh_end_date' => $siswa->wfh_end_date ? \Carbon\Carbon::parse($siswa->wfh_end_date)->format('Y-m-d') : null,
                                                            'is_wfh_today' => (bool)$siswa->isWfhOn(),
                                                            'wfh_notes' => $siswa->wfh_notes ?? '',
                                                        ];

                                                        $absenData = [
                                                            'id' => $absensi->id,
                                                            'nama' => $siswa->nama_lengkap,
                                                            'asal' => $siswa->asal_pkl,
                                                            'divisi' => $siswa->divisi?->nama_divisi ?? '-',
                                                            'tanggal' => $tglCarbon->translatedFormat('l, d F Y'),
                                                            'jam_masuk' => $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : null,
                                                            'jam_pulang' => $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : null,
                                                            'durasi_kerja' => $absensi->durasi_kerja ?? '-',
                                                            'status_ketepatan' => $absensi->status_ketepatan ?? 'Tepat Waktu',
                                                            'status_kerja' => $absensi->status_kerja ?? 'WFO',
                                                            'lokasi_lat' => $absensi->lokasi_lat ?? $absensi->check_in_latitude ?? $absensi->lokasi_masuk_lat,
                                                            'lokasi_long' => $absensi->lokasi_long ?? $absensi->check_in_longitude ?? $absensi->lokasi_masuk_long,
                                                            'foto_masuk_url' => $absensi->foto_masuk_url ?? null,
                                                            'foto_keluar_url' => $absensi->foto_keluar_url ?? null,
                                                            'is_izin' => $isIzin,
                                                            'is_terlambat' => $isTerlambat,
                                                            'jenis_izin' => $absensi->jenisIzin->nama ?? null,
                                                            'keterangan_izin' => $absensi->keterangan_izin ?? null,
                                                            'file_izin_url' => $absensi->file_izin ? asset('storage/' . $absensi->file_izin) : null,
                                                            'siswa_info' => $siswaMagangData,
                                                        ];

                                                        $b64Siswa = base64_encode(json_encode($siswaMagangData, JSON_UNESCAPED_UNICODE));
                                                        $b64Absen = base64_encode(json_encode($absenData, JSON_UNESCAPED_UNICODE));
                                                    @endphp
                                                    <tr class="hover:bg-gray-50/70 transition-colors search-row">
                                                        <td data-label="Siswa" class="px-6 py-4">
                                                            <div class="flex items-center gap-3">
                                                                <div class="w-8 h-8 rounded-lg bg-gray-900 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                                                    {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                                                                </div>
                                                                <div>
                                                                    <div class="font-bold text-gray-900">{{ $siswa->nama_lengkap }}</div>
                                                                    <div class="text-[11px] text-gray-400">{{ $siswa->asal_pkl ?? '-' }}</div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td data-label="Tanggal" class="px-6 py-4 font-medium text-gray-800">
                                                            {{ $tglCarbon->format('d/m/Y') }}
                                                        </td>

                                                        <td data-label="Masuk" class="px-6 py-4 font-mono font-medium text-gray-800">
                                                            {{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '--:--' }}
                                                        </td>

                                                        <td data-label="Pulang" class="px-6 py-4 font-mono font-medium text-gray-800">
                                                            {{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '--:--' }}
                                                        </td>

                                                        <td data-label="Status" class="px-6 py-4 text-center">
                                                            @if($isIzin)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                                    Izin: {{ $absensi->jenisIzin->nama ?? 'Resmi' }}
                                                                </span>
                                                            @elseif($isTerlambat)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                                                    Terlambat
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                                    Tepat Waktu
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td data-label="Aksi" class="px-6 py-4 text-right actions-cell">
                                                            <div class="flex items-center justify-end gap-1.5">
                                                                <button type="button" onclick="openDetailPresensiModal(this)" data-absen="{{ $b64Absen }}"
                                                                    class="px-2.5 py-1.5 border border-gray-200 rounded-lg bg-white hover:bg-gray-50 text-gray-700 font-medium transition flex items-center gap-1 shadow-xs" title="Lihat Bukti Presensi">
                                                                    <i class="fa-solid fa-camera text-xs text-blue-600"></i>
                                                                    <span>Bukti</span>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach

                                            @if(!$hasAnyMonthlyRecord)
                                                <tr>
                                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                                        <div class="flex flex-col items-center justify-center gap-2">
                                                            <i class="fa-regular fa-calendar-xmark text-3xl text-gray-300"></i>
                                                            <p class="font-medium text-xs">Tidak ada riwayat presensi yang tercatat untuk periode ini.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- ================= VIEW 3: DAFTAR SISWA MAGANG ================= -->
                <div id="main-content-siswa" class="main-content-view hidden space-y-6">

                    <div class="card overflow-hidden">
                        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-[#fafafa]">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Daftar Siswa Magang Aktif</h3>
                                <p class="text-xs text-gray-400">
                                    Siswa magang aktif di bawah naungan <strong>{{ $divisiPegawai ? 'Divisi ' . $divisiPegawai->nama_divisi : 'Seluruh Divisi BPS' }}</strong>
                                </p>
                            </div>
                            <span class="px-2.5 py-1 bg-gray-100 rounded-lg text-xs font-semibold text-gray-700">
                                Total: {{ $all_siswas->count() }} Orang
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left text-gray-600 responsive-table">
                                <thead class="text-[11px] text-gray-400 uppercase bg-[#fafafa] font-semibold border-b border-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3.5">Nama & Akun</th>
                                        <th scope="col" class="px-6 py-3.5">Asal Sekolah / Kampus</th>
                                        <th scope="col" class="px-6 py-3.5">Divisi</th>
                                        <th scope="col" class="px-6 py-3.5">Periode Magang</th>
                                        <th scope="col" class="px-6 py-3.5 text-right">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($all_siswas as $sw)
                                    @php
                                        $swProfile = $sw->user?->pesertaProfile;
                                        $swCvUrl = $swProfile?->cv_path ? (str_starts_with($swProfile->cv_path, 'http') ? $swProfile->cv_path : asset('storage/' . $swProfile->cv_path)) : null;
                                        $swPortoUrl = $swProfile?->portofolio_path ? (str_starts_with($swProfile->portofolio_path, 'http') ? $swProfile->portofolio_path : asset('storage/' . $swProfile->portofolio_path)) : ($swProfile?->portofolio_url ?? null);

                                        $swMagangData = [
                                            'id' => $sw->id,
                                            'nama' => $sw->nama_lengkap,
                                            'username' => $sw->user?->username ?? '-',
                                            'email' => $sw->user?->email ?? '-',
                                            'asal' => $sw->asal_pkl ?? ($swProfile?->asal_univ ?? '-'),
                                            'jurusan' => $sw->major ?? ($swProfile?->jurusan ?? '-'),
                                            'no_hp' => $swProfile?->no_hp ?? '-',
                                            'divisi' => $sw->divisi?->nama_divisi ?? '-',
                                            'gedung' => $sw->gedung?->nama ?? ($gedung->nama ?? 'Kantor BPS'),
                                            'tanggal_mulai' => $sw->tanggal_mulai ? \Carbon\Carbon::parse($sw->tanggal_mulai)->format('d/m/Y') : '-',
                                            'tanggal_selesai' => $sw->tanggal_selesai ? \Carbon\Carbon::parse($sw->tanggal_selesai)->format('d/m/Y') : '-',
                                            'cv_url' => $swCvUrl,
                                            'porto_url' => $swPortoUrl,
                                            'wfh_start_date' => $sw->wfh_start_date ? \Carbon\Carbon::parse($sw->wfh_start_date)->format('Y-m-d') : null,
                                            'wfh_end_date' => $sw->wfh_end_date ? \Carbon\Carbon::parse($sw->wfh_end_date)->format('Y-m-d') : null,
                                            'is_wfh_today' => (bool)$sw->isWfhOn(),
                                            'wfh_notes' => $sw->wfh_notes ?? '',
                                        ];

                                        $b64Sw = base64_encode(json_encode($swMagangData, JSON_UNESCAPED_UNICODE));
                                    @endphp
                                    <tr class="hover:bg-gray-50/70 transition-colors search-row">
                                        <td data-label="Nama" class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <button type="button" onclick="openDetailMagangModal(this)" data-siswa="{{ $b64Sw }}"
                                                    class="w-8 h-8 rounded-lg bg-gray-900 hover:bg-blue-600 text-white font-bold flex items-center justify-center text-xs shrink-0 transition shadow-2xs" title="Lihat Detail Magang Siswa">
                                                    {{ strtoupper(substr($sw->nama_lengkap, 0, 1)) }}
                                                </button>
                                                <div>
                                                    <button type="button" onclick="openDetailMagangModal(this)" data-siswa="{{ $b64Sw }}"
                                                        class="font-bold text-gray-900 hover:text-blue-600 transition flex items-center gap-1.5 text-left" title="Buka Rincian Magang Siswa">
                                                        <span>{{ $sw->nama_lengkap }}</span>
                                                        <i class="fa-solid fa-circle-info text-[10px] text-gray-300 hover:text-blue-500"></i>
                                                    </button>
                                                    <div class="text-[11px] text-gray-400">{{ '@' . ($sw->user->username ?? '-') }} &bull; {{ $sw->user->email ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td data-label="Asal" class="px-6 py-4 font-medium text-gray-800">
                                            {{ $sw->asal_pkl ?? '-' }}
                                        </td>

                                        <td data-label="Divisi" class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-teal-50 text-teal-800 text-xs border border-teal-100 font-semibold">
                                                {{ $sw->divisi->nama_divisi ?? '-' }}
                                            </span>
                                        </td>

                                        <td data-label="Periode" class="px-6 py-4 text-gray-600">
                                            @if($sw->tanggal_mulai && $sw->tanggal_selesai)
                                                <div class="font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($sw->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sw->tanggal_selesai)->format('d/m/Y') }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>

                                        <td data-label="Aksi" class="px-6 py-4 text-right actions-cell">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button" onclick="openDetailMagangModal(this)" data-siswa="{{ $b64Sw }}"
                                                    class="px-2.5 py-1.5 border border-gray-200 rounded-lg bg-white hover:bg-gray-50 text-gray-700 font-medium transition inline-flex items-center gap-1 shadow-xs" title="Lihat Detail Profil & Magang Siswa">
                                                    <i class="fa-solid fa-id-card-clip text-xs text-teal-600"></i>
                                                    <span>Detail Magang</span>
                                                </button>
                                                <a href="{{ route('pegawai.siswa.riwayat', $sw->id) }}"
                                                    class="px-2.5 py-1.5 border border-gray-200 rounded-lg bg-white hover:bg-gray-50 text-gray-700 font-medium transition inline-flex items-center gap-1 shadow-xs" title="Buka Halaman Riwayat Presensi Lengkap Siswa Ini">
                                                    <i class="fa-solid fa-clipboard-user text-xs text-blue-600"></i>
                                                    <span>Cek Riwayat Presensi</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                            Belum ada siswa magang aktif di divisi penugasan ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- ================= VIEW 4: KALENDER SISA KUOTA DIVISI ================= -->
                <div id="main-content-kalender" class="main-content-view hidden space-y-6">

                    <!-- Header & Division Selector -->
                    <div class="card p-5 bg-white space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-days text-teal-600"></i>
                                    <span>Kalender Sisa Kuota Divisi</span>
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Pantau ketersediaan kuota harian, jumlah peserta magang aktif, dan sisa kapasitas divisi secara real-time.
                                </p>
                            </div>

                            <!-- Selector Divisi -->
                            <div class="flex items-center gap-3">
                                @if($pegawai && $pegawai->divisi_id)
                                    <!-- Pegawai hanya bertugas di 1 divisi tertentu -->
                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-teal-50 text-teal-900 text-xs font-semibold shadow-2xs">

                                        <input type="hidden" id="pegawaiKalenderDivisiSelect" value="{{ $pegawai->divisi_id }}">
                                    </div>
                                @else
                                    <!-- Pegawai memegang banyak divisi / akses ke semua divisi -->
                                    <div class="flex items-center gap-2">
                                        <label for="pegawaiKalenderDivisiSelect" class="text-xs font-semibold text-gray-700 whitespace-nowrap">
                                            <i class="fa-solid fa-sitemap text-gray-400 mr-1"></i> Pilih Divisi:
                                        </label>
                                        <select id="pegawaiKalenderDivisiSelect" onchange="onPegawaiDivisiChange(this.value)"
                                            class="px-3.5 py-2 text-xs border border-gray-200 rounded-xl font-bold text-gray-800 bg-white shadow-xs outline-none focus:border-gray-900 transition cursor-pointer">
                                            @foreach($divisis as $d)
                                                <option value="{{ $d->id }}" {{ $loop->first ? 'selected' : '' }}>
                                                    {{ $d->nama_divisi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Division Info Bar & Legend -->
                        <div class="pt-4 border-t border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-4 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400 font-medium">Bulan Ditampilkan:</span>
                                    <span id="pegawaiKalenderInfoMonth" class="font-bold text-gray-900">-</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400 font-medium">Total Kuota Divisi:</span>
                                    <span id="pegawaiKalenderInfoKuota" class="px-2 py-0.5 rounded-md bg-gray-100 font-bold text-gray-900">-</span>
                                </div>
                            </div>

                            <!-- Legend Pills -->
                            <div class="flex items-center gap-2 flex-wrap text-[11px]">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 font-medium">
                                Kuota Tersedia
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 font-medium">
                                 Terbatas (&le;1)
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 font-medium">
                                 Kuota Penuh
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 border border-purple-200 text-purple-800 font-medium">
                                 Melebihi Kuota (Over)
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Kalender Grid Card -->
                    <div class="card p-6 bg-white space-y-4 shadow-xs">
                        <!-- Navigation Header Bulan -->
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <button type="button" onclick="navPegawaiKalender(-1)"
                                class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-bold transition shadow-2xs hover:border-gray-300">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </button>

                            <div class="text-center">
                                <h3 id="pegawaiKalenderMonthLabel" class="text-base font-bold text-gray-900 tracking-tight">-</h3>
                                <p id="pegawaiKalenderSubLabel" class="text-[11px] text-gray-400">Pilih salah satu tanggal untuk melihat detail rincian pengisi kuota</p>
                            </div>

                            <button type="button" onclick="navPegawaiKalender(1)"
                                class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-bold transition shadow-2xs hover:border-gray-300">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>
                        </div>

                        <!-- Days of Week Header -->
                        <div class="grid grid-cols-7 gap-1.5 sm:gap-2 text-center text-[11px] font-bold text-gray-400 uppercase tracking-wider py-1">
                            <div class="text-rose-500">Min</div>
                            <div>Sen</div>
                            <div>Sel</div>
                            <div>Rab</div>
                            <div>Kam</div>
                            <div>Jum</div>
                            <div class="text-blue-500">Sab</div>
                        </div>

                        <!-- Loading Indicator -->
                        <div id="pegawaiKalenderLoading" class="py-16 text-center text-gray-400">
                            <i class="fa-solid fa-circle-notch fa-spin text-2xl text-teal-600 mb-2"></i>
                            <p class="text-xs font-medium">Memuat data ketersediaan kuota...</p>
                        </div>

                        <!-- Days Grid -->
                        <div id="pegawaiKalenderDaysGrid" class="grid grid-cols-7 gap-1.5 sm:gap-2.5">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                </div>

                <div id="main-content-logbook" class="main-content-view hidden space-y-6">
                    @include('pegawai.partials.logbook-tab')
                </div>

                <div id="main-content-history" class="main-content-view hidden space-y-6">
                    @include('pegawai.partials.history-tab')
                </div>

            </div>

    </div>

    <!-- ================= FLOATING BOTTOM NAVIGATION BAR (MOBILE ONLY) ================= -->
    <div class="fixed bottom-4 inset-x-0 z-40 flex justify-center px-4 pointer-events-none md:hidden">
        <nav class="pointer-events-auto bg-white/95 backdrop-blur-xl border border-gray-200/90 shadow-[0_10px_30px_rgba(0,0,0,0.12)] rounded-full px-5 py-2 flex items-center gap-6 sm:gap-7 transition-all">
            <!-- TAB 1: SELEKSI -->
            <button type="button" onclick="showMainContent('pendaftaran')" id="pegawai-bottom-nav-pendaftaran"
                class="transition-all active:scale-90 text-[#ff6b00] flex flex-col items-center justify-center gap-0.5"
                title="Seleksi Pendaftaran">
                <i class="fa-solid fa-id-card-clip text-base"></i>
                <span class="text-[9px] font-semibold">Seleksi</span>
            </button>

            <!-- TAB 2: PRESENSI -->
            <button type="button" onclick="showMainContent('absensi')" id="pegawai-bottom-nav-absensi"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex flex-col items-center justify-center gap-0.5"
                title="Rekap Presensi">
                <i class="fa-solid fa-clipboard-user text-base"></i>
                <span class="text-[9px] font-semibold">Presensi</span>
            </button>

            <!-- TAB 3: SISWA -->
            <button type="button" onclick="showMainContent('siswa')" id="pegawai-bottom-nav-siswa"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex flex-col items-center justify-center gap-0.5"
                title="Peserta Magang">
                <i class="fa-solid fa-user-graduate text-base"></i>
                <span class="text-[9px] font-semibold">Siswa</span>
            </button>

            <!-- TAB 4: KALENDER -->
            <button type="button" onclick="showMainContent('kalender')" id="pegawai-bottom-nav-kalender"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex flex-col items-center justify-center gap-0.5"
                title="Kalender Kuota">
                <i class="fa-solid fa-calendar-days text-base"></i>
                <span class="text-[9px] font-semibold">Kalender</span>
            </button>

            <!-- TAB 5: LOGBOOK -->
            <button type="button" onclick="showMainContent('logbook')" id="pegawai-bottom-nav-logbook"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex flex-col items-center justify-center gap-0.5"
                title="Logbook">
                <i class="fa-solid fa-book-open text-base"></i>
                <span class="text-[9px] font-semibold">Logbook</span>
            </button>

            <!-- TAB 6: RIWAYAT ALUMNI -->
            <button type="button" onclick="showMainContent('history')" id="pegawai-bottom-nav-history"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex flex-col items-center justify-center gap-0.5"
                title="History">
                <i class="fa-solid fa-clock-rotate-left text-base"></i>
                <span class="text-[9px] font-semibold">Riwayat</span>
            </button>
        </nav>
    </div>

    <!-- ================= MODAL: DETAIL PENDAFTARAN (ADMIN STYLE) ================= -->
    <div id="pendaftaranDetailModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-2xl bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-id-card-clip"></i>
                    </div>
                    <div>
                        <h3 id="detailPendaftaranTitle" class="text-sm font-bold text-gray-900">Rincian Permohonan Magang</h3>
                        <p class="text-xs text-gray-400">Biodata lengkap, CV, dan portofolio calon peserta</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('pendaftaranDetailModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div id="detailPendaftaranBody" class="space-y-4">
                <!-- Konten dinamis diisi JS -->
            </div>

            <div id="detailPendaftaranFooter" class="flex items-center justify-between pt-4 border-t border-gray-100 mt-6">
                <button type="button" onclick="closeModal('pendaftaranDetailModal')"
                    class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 text-xs font-medium hover:bg-gray-50 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL: TOLAK PENDAFTARAN (ADMIN STYLE) ================= -->
    <div id="pendaftaranRejectModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-md bg-white">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Tolak Permohonan Magang</h3>
                        <p id="rejectModalSub" class="text-xs text-gray-400">Pendaftaran: -</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('pendaftaranRejectModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="formRejectPendaftaran" method="POST" class="space-y-4">
                @csrf
                <div class="modal-error-box {{ (isset($activePegawaiModal) && $activePegawaiModal === 'pendaftaranRejectModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activePegawaiModal) && $activePegawaiModal === 'pendaftaranRejectModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activePegawaiModal) && $activePegawaiModal === 'pendaftaranRejectModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Alasan Penolakan *</label>
                    <textarea name="catatan_admin" id="rejectCatatan" rows="3" required
                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-white"
                        placeholder="Misal: Kuota telah terpenuhi atau kualifikasi berkas belum sesuai..."></textarea>
                    <p class="text-[11px] text-gray-400 mt-1">Catatan ini akan tampil di dashboard calon peserta.</p>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('pendaftaranRejectModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-medium text-xs shadow-xs transition">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL: TOLAK PENGUNDURAN DIRI (PEGAWAI/ADMIN) ================= -->
    <div id="rejectResignationModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-md bg-white">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Tolak Pengunduran Diri</h3>
                        <p class="text-xs text-gray-400">Berikan catatan alasan penolakan undur diri</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('rejectResignationModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="rejectResignationForm" method="POST" action="" class="space-y-4">
                @csrf
                <div class="modal-error-box {{ (isset($activePegawaiModal) && $activePegawaiModal === 'rejectResignationModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activePegawaiModal) && $activePegawaiModal === 'rejectResignationModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activePegawaiModal) && $activePegawaiModal === 'rejectResignationModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Penolakan Pengunduran Diri</label>
                    <textarea name="catatan_admin" rows="3" required
                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900"
                        placeholder="Misal: Dokumen bukti tidak valid atau permohonan tidak disetujui..."></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('rejectResignationModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-medium text-xs shadow-xs transition">
                        Tolak Pengunduran Diri
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Memberhentikan Peserta Magang (Pegawai) -->
    <div id="modalTerminatePendaftaranPegawai" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="card bg-white w-full max-w-md overflow-hidden flex flex-col shadow-2xl rounded-2xl animate-fade-in">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between bg-[#fafafa]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-sm border border-rose-200">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Memberhentikan Peserta Magang</h3>
                        <p class="text-xs text-gray-400" id="terminatePendaftaranSub">Tindakan pembimbing / pegawai BPS</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalTerminatePendaftaranPegawai')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="terminatePendaftaranFormPegawai" method="POST" action="" class="space-y-4 p-5">
                @csrf
                <div class="p-3 bg-rose-50 rounded-xl border border-rose-200 text-rose-900 text-xs leading-relaxed space-y-1">
                    <strong class="block text-rose-950 font-bold">Peringatan Tindakan:</strong>
                    <p class="text-[11px] text-rose-800">
                        Peserta akan langsung diakhiri masa magangnya hari ini dan status akun dikembalikan ke calon peserta. Alasan pemberhentian akan tercatat resmi pada riwayat peserta tanpa penerbitan sertifikat.
                    </p>
                </div>

                <div class="space-y-1.5 text-xs">
                    <label class="font-bold text-gray-700 block">Alasan Pemberhentian <span class="text-rose-500">*</span></label>
                    <textarea name="alasan_pemberhentian" id="inputTerminateReasonPegawai" rows="3" required placeholder="Tuliskan catatan alasan pemberhentian peserta ini..."
                        class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-gray-900 leading-relaxed"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-gray-100">
                    <button type="button" onclick="closeModal('modalTerminatePendaftaranPegawai')"
                        class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MEMBERHENTIKAN peserta ini dari program magang? Alasan akan tercatat di riwayat.');"
                        class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs transition flex items-center gap-1 shadow-xs">
                        <i class="fa-solid fa-user-xmark text-xs"></i>
                        <span>Ya, Berhentikan Peserta</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL: AJUKAN REVISI JADWAL MAGANG DENGAN KALENDER KUOTA ================= -->
    <div id="pendaftaranRescheduleModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-2xl bg-white max-h-[92vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Ajukan Revisi Jadwal Masuk</h3>
                        <p id="rescheduleModalSub" class="text-xs text-gray-400">Pendaftaran: -</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('pendaftaranRescheduleModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="formReschedulePendaftaran" method="POST" class="space-y-4">
                @csrf
                <div class="modal-error-box {{ (isset($activePegawaiModal) && $activePegawaiModal === 'pendaftaranRescheduleModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activePegawaiModal) && $activePegawaiModal === 'pendaftaranRescheduleModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activePegawaiModal) && $activePegawaiModal === 'pendaftaranRescheduleModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="p-3 bg-amber-50/70 border border-amber-200/60 rounded-xl text-xs text-amber-900 leading-relaxed space-y-1">
                    <div class="flex items-center gap-1.5 font-bold text-amber-800">
                        <i class="fa-solid fa-circle-info text-amber-600"></i>
                        <span>Ketentuan Penyesuaian Jadwal</span>
                    </div>
                    <p>Jadwal awal pengajuan peserta: <strong id="rescheduleJadwalAwal" class="text-gray-900">-</strong></p>
                    <p class="text-gray-600 text-[11px]">Tentukan rentang tanggal baru menggunakan kalender sisa kuota di bawah ini. Jika peserta <strong>menyetujui (ACC)</strong> tawaran ini, status pendaftaran otomatis berubah menjadi <strong>Disetujui</strong>.</p>
                </div>

                <!-- KALENDER KUOTA DIVISI INTERAKTIF -->
                <div class="border border-gray-200 rounded-xl p-3 bg-gray-50/60 space-y-2.5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <div class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                <i class="fa-solid fa-calendar-check text-teal-600"></i>
                                <span id="calendarDivisiTitle">Kalender Sisa Kuota Divisi</span>
                            </div>
                            <p class="text-[11px] text-gray-500">Klik tanggal pada kalender untuk mengisi Tanggal Mulai & Selesai</p>
                        </div>
                        <div class="flex items-center gap-1 self-end sm:self-auto">
                            <button type="button" onclick="navigateCalendarMonth(-1)" class="w-7 h-7 rounded-lg border border-gray-200 bg-white hover:bg-gray-100 flex items-center justify-center text-xs text-gray-700 transition" title="Bulan Sebelumnya">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </button>
                            <span id="calendarMonthLabel" class="text-xs font-bold text-gray-800 px-2 min-w-28 text-center">-</span>
                            <button type="button" onclick="navigateCalendarMonth(1)" class="w-7 h-7 rounded-lg border border-gray-200 bg-white hover:bg-gray-100 flex items-center justify-center text-xs text-gray-700 transition" title="Bulan Berikutnya">
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Kalender Grid Container -->
                    <div id="calendarGridLoading" class="py-8 text-center text-xs text-gray-400">
                        <i class="fa-solid fa-circle-notch fa-spin text-teal-600 mr-1.5"></i> Memuat data sisa kuota per tanggal...
                    </div>

                    <div id="calendarGridWrapper" class="hidden space-y-2">
                        <!-- Header Hari -->
                        <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-gray-400 uppercase">
                            <span>Min</span>
                            <span>Sen</span>
                            <span>Sel</span>
                            <span>Rab</span>
                            <span>Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                        </div>
                        <!-- Grid Tanggal -->
                        <div id="calendarDaysGrid" class="grid grid-cols-7 gap-1">
                            <!-- Disuntikkan via JavaScript -->
                        </div>
                        <!-- Legend & Status -->
                        <div class="flex flex-wrap items-center justify-between gap-1 pt-1.5 border-t border-gray-200 text-[10px] text-gray-500">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tersedia</span>
                                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Terbatas</span>
                                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Penuh</span>
                                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-purple-500"></span> Over Kuota</span>
                            </div>
                            <div id="calendarRangeStatus" class="font-semibold text-gray-700"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Mulai Baru *</label>
                        <input type="date" name="tanggal_mulai_saran" id="rescheduleTanggalMulai" required onchange="onManualDateInputChange()"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Selesai Baru *</label>
                        <input type="date" name="tanggal_selesai_saran" id="rescheduleTanggalSelesai" required onchange="onManualDateInputChange()"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-white">
                    </div>
                </div>

                <!-- Warning Peringatan jika Melebihi Kuota Divisi -->
                <div id="rescheduleOverQuotaWarning" class="hidden p-3 bg-amber-50 border border-amber-300 rounded-xl text-xs text-amber-900 space-y-2">
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm mt-0.5"></i>
                        <div>
                            <div class="font-bold text-amber-900">Peringatan: Kuota Periode Melebihi Kapasitas</div>
                            <p id="rescheduleOverQuotaDesc" class="text-amber-800 text-[11px] mt-0.5 leading-relaxed">
                                Kapasitas kuota divisi pada rentang tanggal yang dipilih tidak mencukupi untuk jumlah anggota ini.
                            </p>
                        </div>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer pt-1.5 border-t border-amber-200">
                        <input type="checkbox" name="force_over_quota" value="1" id="rescheduleForceOverQuota" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                        <span class="text-xs font-semibold text-amber-900">
                            Ajukan usulan ini dengan status <u>Melebihi Kuota</u> (Kapasitas kuota resmi divisi tidak akan berubah).
                        </span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Penyesuaian Jadwal *</label>
                    <textarea name="catatan_revisi_jadwal" id="rescheduleCatatan" rows="3" required
                        placeholder="Contoh: Pada bulan tersebut kantor sedang ada agenda sensus lapangan penuh, kami sarankan magang dimulai pada tanggal..."
                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 resize-none"></textarea>
                    <p class="text-[11px] text-gray-400 mt-1">Alasan ini akan tampil secara jelas di dashboard calon peserta.</p>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('pendaftaranRescheduleModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Kirim Usulan Jadwal</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL: KONFIRMASI APPROVAL DENGAN CEK KUOTA & OVER-QUOTA ================= -->
    <div id="confirmApproveModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-md bg-white">
            <div class="flex items-center justify-between pb-3.5 mb-3.5 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div id="approveModalIcon" class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <h3 id="approveModalTitle" class="text-sm font-bold text-gray-900">Konfirmasi Persetujuan</h3>
                        <p id="approveModalSub" class="text-xs text-gray-400">Pendaftaran Magang</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('confirmApproveModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="formConfirmApprove" method="POST" class="space-y-3.5">
                @csrf
                <div class="modal-error-box {{ (isset($activePegawaiModal) && $activePegawaiModal === 'confirmApproveModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activePegawaiModal) && $activePegawaiModal === 'confirmApproveModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activePegawaiModal) && $activePegawaiModal === 'confirmApproveModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <input type="hidden" name="force_over_quota" id="approveInputForceOverQuota" value="0">

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl space-y-1.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Pemohon / Kelompok:</span>
                        <strong id="approveInfoNama" class="text-gray-900">-</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Divisi Penempatan:</span>
                        <strong id="approveInfoDivisi" class="text-gray-900">-</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jumlah Anggota:</span>
                        <strong id="approveInfoAnggota" class="text-gray-900">-</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Periode Magang:</span>
                        <strong id="approveInfoPeriode" class="text-gray-900">-</strong>
                    </div>
                </div>

                <!-- Status Ketersediaan Kuota Box (Dinamis via JS) -->
                <div id="approveQuotaStatusBox" class="p-3 rounded-xl text-xs space-y-1.5">
                    <!-- Loading / Hasil Cek Kuota -->
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('confirmApproveModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" id="btnApproveSubmit"
                        class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span id="btnApproveSubmitText">Setujui Pendaftaran</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL: DETAIL PRESENSI SISWA (ADMIN STYLE) ================= -->
    <div id="detailAbsenModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-lg bg-white max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div id="modalAvatar" class="w-9 h-9 rounded-lg bg-gray-900 text-white font-bold flex items-center justify-center text-xs">
                        S
                    </div>
                    <div>
                        <h3 id="modalNamaSiswa" class="text-sm font-bold text-gray-900 leading-tight">Nama Siswa</h3>
                        <p id="modalSubtext" class="text-xs text-gray-400">Asal PKL &bull; Tanggal</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('detailAbsenModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Modal Body Content -->
            <div class="space-y-4">
                <!-- Status Kehadiran Badge -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <div>
                        <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block">Status Presensi</span>
                        <span id="modalStatusBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold mt-1 bg-emerald-100 text-emerald-800">
                            Hadir
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block">Area Gedung</span>
                        <span id="modalModeKerja" class="text-xs font-bold text-gray-800 block mt-1">WFO</span>
                    </div>
                </div>

                <!-- 4 Stats Grid: Jam Masuk, Keluar, Durasi, Ketepatan -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-2.5">
                        <span class="text-[10px] font-semibold text-gray-400 uppercase block">Jam Masuk</span>
                        <p id="modalJamMasuk" class="font-bold text-gray-900 mt-0.5">--:--</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-2.5">
                        <span class="text-[10px] font-semibold text-gray-400 uppercase block">Jam Pulang</span>
                        <p id="modalJamPulang" class="font-bold text-gray-900 mt-0.5">--:--</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-2.5">
                        <span class="text-[10px] font-semibold text-gray-400 uppercase block">Durasi</span>
                        <p id="modalDurasi" class="font-bold text-gray-900 mt-0.5">-</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-2.5">
                        <span class="text-[10px] font-semibold text-gray-400 uppercase block">Ketepatan</span>
                        <p id="modalKetepatan" class="font-bold text-gray-900 mt-0.5">Tepat Waktu</p>
                    </div>
                </div>

                <!-- Foto Bukti Presensi (Selfie Masuk & Pulang) -->
                <div id="modalSectionPhotos">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-camera text-blue-500"></i>
                            <span>Bukti Foto Presensi</span>
                        </h4>
                        <span class="text-[10px] text-gray-400">Klik untuk memperbesar</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <!-- Foto Masuk -->
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-2 flex flex-col items-center">
                            <span class="text-[10px] font-medium text-gray-500 mb-1.5">Foto Masuk</span>
                            <div class="w-full aspect-[4/3] rounded-lg overflow-hidden bg-gray-200 relative group cursor-pointer" onclick="zoomModalImage('fotoMasuk')">
                                <img id="modalImgMasuk" src="" alt="Foto Masuk" class="w-full h-full object-cover"
                                    onerror="this.classList.add('hidden'); document.getElementById('modalImgMasukEmpty')?.classList.remove('hidden');">
                                <div id="modalImgMasukEmpty" class="hidden absolute inset-0 flex flex-col items-center justify-center text-gray-400 bg-gray-100 text-xs">
                                    <i class="fa-regular fa-image text-lg mb-1"></i>
                                    <span>Tidak Ada Foto</span>
                                </div>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-medium">
                                    <i class="fa-solid fa-magnifying-glass-plus mr-1"></i> Perbesar
                                </div>
                            </div>
                        </div>

                        <!-- Foto Keluar -->
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-2 flex flex-col items-center">
                            <span class="text-[10px] font-medium text-gray-500 mb-1.5">Foto Pulang</span>
                            <div class="w-full aspect-[4/3] rounded-lg overflow-hidden bg-gray-200 relative group cursor-pointer" onclick="zoomModalImage('fotoKeluar')">
                                <img id="modalImgKeluar" src="" alt="Foto Keluar" class="w-full h-full object-cover"
                                    onerror="this.classList.add('hidden'); document.getElementById('modalImgKeluarEmpty')?.classList.remove('hidden');">
                                <div id="modalImgKeluarEmpty" class="hidden absolute inset-0 flex flex-col items-center justify-center text-gray-400 bg-gray-100 text-xs">
                                    <i class="fa-regular fa-image text-lg mb-1"></i>
                                    <span>Belum Pulang</span>
                                </div>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-medium">
                                    <i class="fa-solid fa-magnifying-glass-plus mr-1"></i> Perbesar
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lokasi GPS Masuk -->
                <div id="modalSectionLocation" class="bg-gray-50 rounded-xl p-3 border border-gray-100 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-gray-400 uppercase block">Koordinat Presensi</span>
                            <p id="modalCoordText" class="font-mono font-medium text-gray-800 mt-0.5">Latitude, Longitude</p>
                        </div>
                    </div>
                    <a id="modalMapBtn" href="#" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-medium shadow-xs transition flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        <span>Buka Maps</span>
                    </a>
                </div>

                <!-- Info Khusus Izin -->
                <div id="modalSectionIzin" class="hidden bg-amber-50 rounded-xl p-3.5 border border-amber-200 space-y-2 text-xs">
                    <div class="flex items-center gap-2 text-amber-800 font-semibold">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Keterangan Izin: <strong id="modalJenisIzinNama">-</strong></span>
                    </div>
                    <p id="modalKeteranganIzin" class="text-amber-900 bg-white/80 p-2.5 rounded-lg border border-amber-100">
                        Alasan izin siswa...
                    </p>
                    <div id="modalFileIzinContainer" class="pt-1 hidden">
                        <a id="modalFileIzinLink" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs shadow-xs transition">
                            <i class="fa-solid fa-paperclip"></i>
                            <span>Buka Surat / Bukti Izin</span>
                        </a>
                    </div>
                </div>

                <div id="modalEmptyState" class="hidden py-8 text-center text-gray-400 text-xs">
                    <i class="fa-regular fa-face-frown text-3xl mb-2 text-gray-300 block"></i>
                    <span>Siswa ini belum melakukan presensi untuk tanggal terpilih.</span>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-5">
                <button type="button" onclick="showSiswaMagangDetailFromAbsen()" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 text-xs font-semibold flex items-center gap-1.5 transition shadow-2xs">
                    <i class="fa-solid fa-id-card-clip text-teal-600"></i>
                    <span>Lihat Biodata & Magang</span>
                </button>
                <button type="button" onclick="closeModal('detailAbsenModal')" class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg font-medium text-xs hover:bg-gray-50 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL: DETAIL LENGKAP MAGANG SISWA ================= -->
    <div id="detailMagangSiswaModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-lg bg-white max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div id="magangModalAvatar" class="w-10 h-10 rounded-xl bg-gray-900 text-white font-bold flex items-center justify-center text-sm shadow-xs">
                        S
                    </div>
                    <div>
                        <h3 id="magangModalNama" class="text-sm font-bold text-gray-900 leading-tight">Nama Peserta Magang</h3>
                        <p id="magangModalUsername" class="text-xs text-gray-400">@username &bull; email</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('detailMagangSiswaModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="space-y-4 text-xs">
                <!-- Status & Divisi Box -->
                <div class="grid grid-cols-2 gap-3 p-3.5 bg-gray-50 rounded-xl border border-gray-100">
                    <div>
                        <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block">Divisi Penempatan</span>
                        <p id="magangModalDivisi" class="font-bold text-gray-900 mt-0.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-sitemap text-teal-600 text-xs"></i>
                            <span>-</span>
                        </p>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block">Lokasi Gedung</span>
                        <p id="magangModalGedung" class="font-bold text-gray-900 mt-0.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-building text-blue-600 text-xs"></i>
                            <span>-</span>
                        </p>
                    </div>
                </div>

                <!-- Periode Magang -->
                <div class="p-3.5 bg-blue-50/70 border border-blue-100 rounded-xl">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-semibold text-blue-800 uppercase tracking-wider">Periode Masa Magang</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800" id="magangModalStatusText">Aktif</span>
                    </div>
                    <p id="magangModalPeriode" class="font-bold text-gray-900 text-sm mt-0.5 flex items-center gap-2">
                        <i class="fa-regular fa-calendar-days text-blue-600"></i>
                        <span>-</span>
                    </p>
                </div>

                <!-- Detail Pendidikan & Kontak -->
                <div class="space-y-2 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    <div class="flex justify-between items-center py-1 border-b border-gray-100">
                        <span class="text-gray-400">Asal Instansi / Univ:</span>
                        <span id="magangModalAsal" class="font-semibold text-gray-900 text-right">-</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-gray-100">
                        <span class="text-gray-400">Jurusan:</span>
                        <span id="magangModalJurusan" class="font-semibold text-gray-900 text-right">-</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-gray-100">
                        <span class="text-gray-400">Nomor WhatsApp / HP:</span>
                        <span id="magangModalNoHp" class="font-mono font-semibold text-gray-900">-</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-400">Alamat Email:</span>
                        <span id="magangModalEmail" class="font-semibold text-gray-900">-</span>
                    </div>
                </div>

                <!-- Dokumen CV / Portofolio -->
                <div id="magangModalDocsContainer" class="flex items-center gap-2 pt-1">
                    <a id="magangModalCvBtn" href="#" target="_blank" class="flex-1 px-3 py-2 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 text-gray-800 font-semibold text-xs transition flex items-center justify-center gap-2 shadow-xs">
                        <i class="fa-solid fa-file-pdf text-rose-500 text-sm"></i>
                        <span>Unduh / Buka CV</span>
                    </a>
                    <a id="magangModalPortoBtn" href="#" target="_blank" class="flex-1 px-3 py-2 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 text-gray-800 font-semibold text-xs transition flex items-center justify-center gap-2 shadow-xs">
                        <i class="fa-solid fa-arrow-up-right-from-square text-blue-600 text-xs"></i>
                        <span>Portofolio</span>
                    </a>
                </div>

                <!-- ================= PENGATURAN IZIN WFH SISWA ================= -->
                <div class="border border-indigo-100 bg-indigo-50/40 rounded-2xl p-4 space-y-3 mt-3">
                    <div class="flex items-center justify-between pb-2 border-b border-indigo-100">
                        <div class="flex items-center gap-2">x
                            <div>
                                <span class="text-xs font-bold text-gray-900">Pengaturan Izin WFH</span>
                                <p class="text-[10px] text-gray-500">Hanya siswa yang diizinkan WFH yang dapat presensi di luar kantor</p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Status Aktif WFH Saat Ini -->
                    <div id="magangWfhActiveInfo" class="hidden p-2.5 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                            <div>
                                <span class="font-bold block text-[11px] text-emerald-900">Izin WFH Aktif</span>
                                <span id="magangWfhActiveDates" class="text-[10px] text-emerald-700 font-mono">-</span>
                            </div>
                        </div>
                        <button type="button" onclick="submitWfhAction('cancel')" id="btnCancelWfh" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 font-semibold text-[10px] transition flex items-center gap-1 shadow-2xs">
                            <i class="fa-solid fa-xmark"></i>
                            <span>Cabut Izin WFH</span>
                        </button>
                    </div>

                    <!-- Pilihan 1: Set WFH Hari Ini -->
                    <div class="flex items-center justify-between gap-2 p-2.5 bg-white rounded-xl border border-indigo-100/80 shadow-2xs">
                        <div>
                            <div class="font-bold text-[11px] text-gray-800">Set WFH Hari Ini</div>
                            <div class="text-[10px] text-gray-400">Berlaku hanya untuk hari ini ({{ \Carbon\Carbon::today()->translatedFormat('d F Y') }})</div>
                        </div>
                        <button type="button" onclick="submitWfhAction('today')" id="btnSetWfhToday" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition shrink-0 flex items-center gap-1.5">
                            <i class="fa-solid fa-calendar-day text-[11px]"></i>
                            <span>Set Hari Ini</span>
                        </button>
                    </div>

                    <!-- Pilihan 2: Set WFH Rentang Tanggal -->
                    <div class="p-2.5 bg-white rounded-xl border border-indigo-100/80 shadow-2xs space-y-2">
                        <div class="font-bold text-[11px] text-gray-800 flex items-center gap-1.5">
                            <i class="fa-solid fa-calendar-week text-indigo-600 text-[11px]"></i>
                            <span>Set Rentang Tanggal WFH</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Tanggal Mulai</label>
                                <input type="date" id="wfhInputStart" class="w-full text-xs px-2.5 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-indigo-600 bg-gray-50/50">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Tanggal Selesai</label>
                                <input type="date" id="wfhInputEnd" class="w-full text-xs px-2.5 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-indigo-600 bg-gray-50/50">
                            </div>
                        </div>
                        <div class="flex justify-end pt-0.5">
                            <button type="button" onclick="submitWfhAction('range')" id="btnSetWfhRange" class="px-3 py-1.5 rounded-lg bg-gray-900 hover:bg-black text-white font-semibold text-xs transition flex items-center gap-1.5 shadow-xs">
                                <i class="fa-solid fa-floppy-disk text-[10px]"></i>
                                <span>Simpan Periode WFH</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-5 flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <a id="magangModalPresensiLink" href="#"
                        class="px-3 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-semibold flex items-center gap-1.5 shadow-xs transition">
                        <i class="fa-solid fa-clipboard-user text-xs"></i>
                        <span>Cek Riwayat Presensi</span>
                    </a>
                    <button type="button" id="magangModalUbahPeriodeBtn" onclick="openUbahPeriodeFromDetailMagang()"
                        class="px-3 py-2 rounded-lg border border-teal-300 bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-semibold flex items-center gap-1.5 transition shadow-2xs">
                        <i class="fa-solid fa-calendar-days text-teal-600 text-xs"></i>
                        <span>Ubah Tanggal Selesai</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL UBAH TANGGAL SELESAI MAGANG PEGAWAI ================= -->
    <div id="modalUbahPeriodePegawai" class="modal hidden fixed inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="card p-6 shadow-xl w-full max-w-md bg-white">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-calendar-days text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Ubah Tanggal Selesai Magang</h3>
                        <p class="text-[11px] text-gray-400">Percepat atau perpanjang masa magang siswa</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalUbahPeriodePegawai')" class="text-gray-400 hover:text-black p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="formUbahPeriodePegawai" method="POST" action="" class="space-y-4">
                @csrf
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs space-y-1">
                    <div id="ubahPeriodeNamaSiswa" class="font-bold text-gray-800">Nama Siswa</div>
                    <div id="ubahPeriodeDivisiSiswa" class="text-[11px] text-gray-500">Divisi: -</div>
                    <div id="ubahPeriodeTanggalMulaiSiswa" class="text-[11px] text-gray-500">Tanggal Mulai: -</div>
                    <div id="ubahPeriodeTanggalSelesaiSiswa" class="text-[11px] text-gray-500">Tanggal Selesai Saat Ini: -</div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Tanggal Selesai Baru <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="ubahPeriodeInputTanggalSelesai" name="tanggal_selesai" required
                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900">
                    <p class="text-[10px] text-gray-400 mt-1">Pilih tanggal lebih awal untuk mempercepat, atau lebih akhir untuk memperpanjang.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Catatan / Alasan Perubahan (Opsional)
                    </label>
                    <textarea name="alasan" rows="2" placeholder="Contoh: Perpanjangan pengerjaan laporan akhir atau percepatan jadwal skripsi..."
                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('modalUbahPeriodePegawai')"
                        class="px-3.5 py-2 border border-gray-200 text-gray-600 rounded-lg font-medium text-xs hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-900 hover:bg-black text-white rounded-lg font-semibold text-xs shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= IMAGE LIGHTBOX MODAL ================= -->
    <div id="imageLightboxModal" class="modal hidden fixed inset-0 bg-black/80 backdrop-blur-xs flex items-center justify-center p-4 z-60" onclick="closeImageLightbox()">
        <div class="relative max-w-md w-full" onclick="event.stopPropagation()">
            <button type="button" onclick="closeImageLightbox()" class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white text-gray-800 font-bold text-sm flex items-center justify-center shadow-lg hover:bg-gray-100 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <img id="lightboxImage" src="" alt="Preview Full" class="w-full h-auto max-h-[80vh] rounded-2xl object-contain shadow-2xl border-2 border-white/20">
        </div>
    </div>

    <!-- ================= MODAL: DETAIL KUOTA TANGGAL ================= -->
    <div id="pegawaiDateDetailModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50" onclick="if(event.target === this) closeModal('pegawaiDateDetailModal')">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-xl bg-white max-h-[85vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                    <div>
                        <h3 id="dateDetailTitle" class="text-sm font-bold text-gray-900">Rincian Kuota Tanggal</h3>
                        <p id="dateDetailSub" class="text-xs text-gray-400">-</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('pegawaiDateDetailModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div id="dateDetailBody" class="space-y-4">
                <!-- Dynamically populated via JS -->
            </div>

            <div class="flex justify-end pt-3 border-t border-gray-100 mt-4">
                <button type="button" onclick="closeModal('pegawaiDateDetailModal')"
                    class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 text-xs font-semibold hover:bg-gray-50 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ================= JAVASCRIPT ================= -->
    <script>
        const allPendaftaransData = @json($pendaftarans);
        let currentModalData = null;

        // Calendar & State Variables
        let currentCalYear = parseInt("{{ $selectedYear }}");
        let currentCalMonth = parseInt("{{ $selectedMonth }}");
        const activeDateStr = "{{ $activeTanggal }}";
        const isMonthlyActive = {{ $isMonthlyMode ? 'true' : 'false' }};

        const namaBulanIndo = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        // Global Date Formatting Helpers
        function formatDateDMY(val) {
            if (!val) return '-';
            if (typeof val === 'string') {
                if (/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(val)) return val;
                const isoMatch = val.match(/^(\d{4})-(\d{2})-(\d{2})/);
                if (isoMatch) {
                    return `${isoMatch[3]}/${isoMatch[2]}/${isoMatch[1]}`;
                }
            }
            const d = new Date(val);
            if (!isNaN(d.getTime())) {
                const day = String(d.getDate()).padStart(2, '0');
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const year = d.getFullYear();
                return `${day}/${month}/${year}`;
            }
            return val;
        }

        function formatDateYMD(val) {
            if (!val) return '';
            if (typeof val === 'string') {
                const isoMatch = val.match(/^(\d{4})-(\d{2})-(\d{2})/);
                if (isoMatch) return `${isoMatch[1]}-${isoMatch[2]}-${isoMatch[3]}`;
                const dmyMatch = val.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})/);
                if (dmyMatch) return `${dmyMatch[3]}-${dmyMatch[2].padStart(2, '0')}-${dmyMatch[1].padStart(2, '0')}`;
            }
            const d = new Date(val);
            if (!isNaN(d.getTime())) {
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${y}-${m}-${day}`;
            }
            return '';
        }

        // 1. Navigation / View Handlers (Karniva / Admin Style)
        function showMainContent(contentToShow) {
            document.querySelectorAll('.main-content-view').forEach(view => {
                view.classList.add('hidden');
            });
            const viewId = `main-content-${contentToShow}`;
            const targetView = document.getElementById(viewId);
            if (targetView) {
                targetView.classList.remove('hidden');
            }

            // Update Sidebar State
            document.querySelectorAll('.sidebar-nav-link').forEach(btn => btn.classList.remove('active'));
            const activeSideBtn = document.getElementById(`side-nav-${contentToShow}`);
            if (activeSideBtn) activeSideBtn.classList.add('active');

            // Update Mobile Bottom Nav State
            ['pendaftaran', 'absensi', 'siswa', 'kalender', 'logbook', 'history'].forEach(item => {
                const bBtn = document.getElementById(`pegawai-bottom-nav-${item}`);
                if (bBtn) {
                    if (item === contentToShow) {
                        bBtn.classList.add('text-[#ff6b00]');
                        bBtn.classList.remove('text-gray-400', 'hover:text-gray-600');
                    } else {
                        bBtn.classList.remove('text-[#ff6b00]');
                        bBtn.classList.add('text-gray-400', 'hover:text-gray-600');
                    }
                }
            });

            // Update Breadcrumb Title
            const breadcrumbEl = document.getElementById('breadcrumb-active-title');
            if (breadcrumbEl) {
                const titles = {
                    'pendaftaran': 'Seleksi Pendaftaran Magang',
                    'absensi': 'Rekap Presensi & Kehadiran Siswa',
                    'siswa': 'Daftar Siswa Magang Aktif',
                    'kalender': 'Kalender Sisa Kuota Divisi',
                    'logbook': 'Review Logbook & Berkas Bulanan',
                    'history': 'Riwayat Alumni Peserta Magang'
                };
                breadcrumbEl.textContent = titles[contentToShow] || 'Portal Pegawai';
            }

            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', contentToShow);
            window.history.replaceState({}, '', url);

            if (contentToShow === 'kalender') {
                initPegawaiKalenderView();
            }

            updateRecordCountBadge();
        }

        // 2. Mobile Sidebar Toggle
        function toggleSidebarMobile() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function closeSidebarMobile() {
            if (window.innerWidth < 768) {
                const sidebar = document.getElementById('main-sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // 3. Global Live Search Filter
        function filterTables(keyword) {
            const term = (keyword || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.search-row');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (!term || text.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            updateRecordCountBadge();
        }

        function updateRecordCountBadge() {
            const visibleAbsenRows = document.querySelectorAll('#main-content-absensi tbody tr.search-row:not([style*="display: none"])');
            const badge = document.getElementById('absensiCountBadge');
            if (badge) {
                badge.textContent = visibleAbsenRows.length;
            }
        }

        // 4. Modal Handlers (Clean Admin Style)
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                const errBox = modal.querySelector('.modal-error-box');
                if (errBox) errBox.classList.add('hidden');
            }
        }

        // ================= UNIVERSAL MODAL FORMS AJAX HANDLER =================
        function setupModalForms() {
            document.querySelectorAll('.modal form').forEach(form => {
                if (form.dataset.ajaxModalInitialized) return;
                form.dataset.ajaxModalInitialized = 'true';

                form.addEventListener('submit', async function (e) {
                    if (form.dataset.noAjax) return;
                    e.preventDefault();

                    const modalEl = form.closest('.modal');
                    let errorBox = form.querySelector('.modal-error-box') || (modalEl ? modalEl.querySelector('.modal-error-box') : null);
                    if (errorBox) {
                        errorBox.classList.add('hidden');
                        errorBox.innerHTML = '';
                    }

                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Memproses...';
                    }

                    try {
                        const formData = new FormData(form);
                        const response = await fetch(form.action, {
                            method: (form.method || 'POST').toUpperCase(),
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json().catch(() => ({}));

                        if (response.ok && data.success !== false) {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                window.location.reload();
                            }
                            return;
                        }

                        // Tangani kesalahan (422 validasi atau error controller)
                        let errorList = [];
                        if (data.errors) {
                            Object.values(data.errors).forEach(errArray => {
                                if (Array.isArray(errArray)) errorList.push(...errArray);
                                else errorList.push(errArray);
                            });
                        } else if (data.message) {
                            errorList.push(data.message);
                        } else if (data.error) {
                            errorList.push(data.error);
                        } else {
                            errorList.push('Terjadi kesalahan saat memproses data.');
                        }

                        if (!errorBox) {
                            errorBox = document.createElement('div');
                            errorBox.className = 'modal-error-box mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5';
                            form.insertBefore(errorBox, form.firstChild);
                        }

                        errorBox.classList.remove('hidden');
                        errorBox.innerHTML = `
                            <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                            <div class="space-y-1">
                                <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                                <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                    ${errorList.map(e => `<li>${e}</li>`).join('')}
                                </ul>
                            </div>
                        `;

                        // Modal dipastikan TETAP TERBUKA sampai ditutup sendiri oleh user
                        if (modalEl) modalEl.classList.remove('hidden');

                    } catch (err) {
                        console.error(err);
                        if (!errorBox) {
                            errorBox = document.createElement('div');
                            errorBox.className = 'modal-error-box mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5';
                            form.insertBefore(errorBox, form.firstChild);
                        }
                        errorBox.classList.remove('hidden');
                        errorBox.innerHTML = `
                            <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                            <div>Gagal terhubung ke server. Silakan periksa koneksi internet Anda.</div>
                        `;
                    } finally {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnHtml;
                        }
                    }
                });
            });
        }

        // 5. Pendaftaran Filter Tab
        function filterPendaftaranTable(status) {
            document.querySelectorAll('.filter-pendaftaran-btn').forEach(btn => {
                btn.className = 'filter-pendaftaran-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition';
            });
            const activeBtn = document.getElementById(`btn-filter-pendaftaran-${status}`);
            if (activeBtn) {
                activeBtn.className = 'filter-pendaftaran-btn px-3 py-1 rounded-md bg-gray-100 text-gray-900 font-semibold transition';
            }

            const rows = document.querySelectorAll('.row-pendaftaran');
            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                if (status === 'all' || rowStatus === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        let activeSelectedPendaftaran = null;

        // 6. Detail Pendaftaran Modal
        function openDetailPendaftaran(id) {
            const item = allPendaftaransData.find(p => p.id == id);
            if (!item) return;
            activeSelectedPendaftaran = item;

            const bodyEl = document.getElementById('detailPendaftaranBody');
            const members = item.anggotas || item.members || [];
            const division = item.divisi || item.division || {};
            const leader = item.ketua || item.leader || {};
            const isKelompok = (item.tipe || item.type) === 'kelompok';
            const groupName = item.nama_kelompok || item.group_name || '';
            const leaderName = members[0]?.nama_lengkap || members[0]?.full_name || leader?.username || 'Peserta';

            document.getElementById('detailPendaftaranTitle').textContent = `Permohonan: ${isKelompok ? groupName : leaderName}`;

            let anggotasHtml = '';
            members.forEach((anggota, idx) => {
                let cvUrl = null;
                if (anggota.cv_path) {
                    cvUrl = anggota.cv_path.startsWith('http')
                        ? anggota.cv_path
                        : (anggota.cv_path.startsWith('storage/') ? `{{ url('/') }}/${anggota.cv_path}` : `{{ asset('storage') }}/${anggota.cv_path}`);
                }

                let portoUrl = null;
                if (anggota.portofolio_path) {
                    portoUrl = anggota.portofolio_path.startsWith('http')
                        ? anggota.portofolio_path
                        : (anggota.portofolio_path.startsWith('storage/') ? `{{ url('/') }}/${anggota.portofolio_path}` : `{{ asset('storage') }}/${anggota.portofolio_path}`);
                }

                const memberName = anggota.nama_lengkap || anggota.full_name || '-';
                const memberUniv = anggota.asal_univ || anggota.institution || '-';
                const memberMajor = anggota.jurusan || anggota.major || '-';
                const memberPhone = anggota.no_hp || anggota.phone || '-';
                const memberUser = anggota.user || anggota.pengguna || (anggota.is_leader ? leader : null) || (idx === 0 ? leader : null) || {};
                const memberUsername = anggota.username || memberUser.username || leader.username || '-';
                const memberEmail = anggota.email || memberUser.email || leader.email || '-';

                anggotasHtml += `
                    <div class="p-3.5 rounded-xl border border-gray-100 bg-[#fbfcfd] space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-gray-900 text-white font-bold text-xs flex items-center justify-center">${idx + 1}</span>
                                <span class="font-bold text-gray-900 text-sm">${memberName}</span>
                                ${idx === 0 && isKelompok ? '<span class="text-[10px] bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded-full border border-blue-200">Pengaju Utama</span>' : ''}
                            </div>
                            <span class="text-[11px] font-mono text-gray-400">@${memberUsername}</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-600">
                            <div><span class="text-gray-400">Universitas:</span> <strong class="text-gray-800">${memberUniv}</strong></div>
                            <div><span class="text-gray-400">Jurusan:</span> <strong class="text-gray-800">${memberMajor}</strong></div>
                            <div><span class="text-gray-400">WhatsApp/HP:</span> <strong class="text-gray-800">${memberPhone}</strong></div>
                            <div><span class="text-gray-400">Email:</span> <strong class="text-gray-800">${memberEmail}</strong></div>
                        </div>
                        <div class="pt-2 border-t border-gray-100 flex items-center gap-2">
                            ${cvUrl ? `
                                <a href="${cvUrl}" target="_blank" class="px-3 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 text-xs font-medium transition flex items-center gap-1.5 shadow-xs">
                                    <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                    <span>Download / Buka CV</span>
                                </a>
                            ` : '<span class="text-xs text-gray-400">CV tidak dilampirkan</span>'}

                            ${portoUrl ? `
                                <a href="${portoUrl}" target="_blank" class="px-3 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 text-xs font-medium transition flex items-center gap-1.5 shadow-xs">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-blue-600 text-[10px]"></i>
                                    <span>Buka Portofolio</span>
                                </a>
                            ` : ''}
                        </div>
                    </div>
                `;
            });

            let rescheduleNotice = '';
            if (item.status === 'approved' && item.is_over_quota) {
                rescheduleNotice = `
                    <div class="p-3 bg-amber-50 border border-amber-300 rounded-xl text-xs text-amber-900 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-amber-800">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                            <span>Disetujui Melebihi Kuota Resmi Divisi</span>
                        </div>
                        <p class="text-[11px] text-amber-800">Permohonan ini telah disetujui dengan status khusus <strong>Melebihi Kuota</strong> atas kebijakan kantor.</p>
                    </div>
                `;
            } else if (item.status === 'rescheduled') {
                rescheduleNotice = `
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-blue-800">
                            <i class="fa-solid fa-clock-rotate-left text-blue-600"></i>
                            <span>Usulan Revisi Jadwal Sedang Menunggu Persetujuan Peserta</span>
                        </div>
                        <p>Jadwal Baru yang Disarankan: <strong>${formatDateDMY(item.tanggal_mulai_saran)} s.d. ${formatDateDMY(item.tanggal_selesai_saran)}</strong></p>
                        <p class="text-blue-800 italic">"Alasan: ${item.catatan_revisi_jadwal || '-'}"</p>
                    </div>
                `;
            } else if (item.status === 'resignation_requested') {
                let buktiBtn = '';
                if (item.bukti_undur_diri) {
                    let buktiUrl = item.bukti_undur_diri.startsWith('http')
                        ? item.bukti_undur_diri
                        : (item.bukti_undur_diri.startsWith('storage/') ? `{{ url('/') }}/${item.bukti_undur_diri}` : `{{ asset('storage') }}/${item.bukti_undur_diri}`);
                    buktiBtn = `
                        <div class="pt-2">
                            <a href="${buktiUrl}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-100 hover:bg-purple-200 text-purple-800 rounded-lg text-xs font-semibold transition">
                                <i class="fa-solid fa-paperclip"></i>
                                <span>Lihat / Download Dokumen Bukti Pengunduran Diri</span>
                            </a>
                        </div>
                    `;
                }
                rescheduleNotice = `
                    <div class="p-3.5 bg-purple-50 border border-purple-200 rounded-xl text-xs text-purple-950 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-purple-900">
                            <i class="fa-solid fa-file-signature text-purple-600"></i>
                            <span>Permohonan Pengunduran Diri oleh Peserta</span>
                        </div>
                        <p class="text-purple-800">Diajukan pada: <strong>${item.tgl_undur_diri ? formatDateDMY(item.tgl_undur_diri) : '-'}</strong></p>
                        <p class="text-purple-900 bg-white/80 p-2.5 rounded-lg border border-purple-100">
                            <strong>Alasan:</strong> "${item.alasan_undur_diri || '-'}"
                        </p>
                        ${buktiBtn}
                    </div>
                `;
            } else if (item.status === 'resigned') {
                rescheduleNotice = `
                    <div class="p-3 bg-gray-100 border border-gray-300 rounded-xl text-xs text-gray-800 space-y-1">
                        <div class="flex items-center gap-2 font-bold text-gray-900">
                            <i class="fa-solid fa-user-xmark text-gray-600"></i>
                            <span>Peserta Telah Mengundurkan Diri</span>
                        </div>
                        <p class="text-gray-600">Alasan: "${item.alasan_undur_diri || '-'}"</p>
                        ${item.catatan_undur_diri_admin ? `<p class="text-gray-700 italic">Catatan Admin/Pegawai: "${item.catatan_undur_diri_admin}"</p>` : ''}
                    </div>
                `;
            } else if (item.status === 'cancelled') {
                rescheduleNotice = `
                    <div class="p-3 bg-gray-100 border border-gray-200 rounded-xl text-xs text-gray-700 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-gray-800">
                            <i class="fa-solid fa-ban text-gray-500"></i>
                            <span>Pendaftaran Dibatalkan oleh Peserta</span>
                        </div>
                        <p class="text-gray-600">${item.catatan_admin || 'Permohonan ini telah dibatalkan oleh calon peserta.'}</p>
                    </div>
                `;
            } else if (item.alasan_penolakan_peserta) {
                rescheduleNotice = `
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-900 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-rose-700">
                            <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                            <span>Peserta Menolak Usulan Jadwal Baru</span>
                        </div>
                        <p class="text-rose-800">Alasan Peserta: <strong>${item.alasan_penolakan_peserta}</strong></p>
                    </div>
                `;
            } else if (item.catatan_admin) {
                rescheduleNotice = `
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-900">
                        <strong class="block mb-0.5 font-bold">Catatan Penolakan:</strong>
                        ${item.catatan_admin}
                    </div>
                `;
            }

            const tglMulai = item.tanggal_mulai || item.start_date;
            const tglSelesai = item.tanggal_selesai || item.end_date;
            const tglMulaiSaran = item.tanggal_mulai_saran || item.suggested_start_date;
            const tglSelesaiSaran = item.tanggal_selesai_saran || item.suggested_end_date;
            const catatanRevisi = item.catatan_revisi_jadwal || item.reschedule_reason || '';
            const divisiId = item.divisi_id || item.division_id;
            const divisiNama = division.nama_divisi || division.name || '-';
            const kuotaDivisi = division.kuota ?? division.quota ?? 0;
            const totalAnggota = members.length || 1;

            bodyEl.innerHTML = `
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3.5 bg-gray-50 rounded-xl border border-gray-100 text-xs">
                    <div>
                        <span class="text-gray-400 uppercase tracking-wider block mb-0.5 font-semibold text-[10px]">Divisi Tujuan:</span>
                        <strong class="text-gray-900 text-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-sitemap text-teal-600"></i>
                            ${divisiNama}
                        </strong>
                    </div>
                    <div>
                        <span class="text-gray-400 uppercase tracking-wider block mb-0.5 font-semibold text-[10px]">Periode Magang:</span>
                        <strong class="text-gray-900 text-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-calendar text-blue-600"></i>
                            ${formatDateDMY(tglMulai)} s.d. ${formatDateDMY(tglSelesai)}
                        </strong>
                    </div>
                </div>

                ${rescheduleNotice}

                <div class="p-3 bg-white border border-gray-100 rounded-xl text-xs text-gray-800 space-y-1">
                    <span class="font-bold text-gray-900">Sisa Kuota:</span>
                    <span class="font-bold ${item.min_remaining_quota > 0 ? 'text-emerald-600' : 'text-rose-600'}">
                        ${item.min_remaining_quota}
                    </span>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Data Calon Peserta (${totalAnggota} Orang)
                    </h4>
                    <div class="space-y-2.5">
                        ${anggotasHtml}
                    </div>
                </div>
            `;

            const footerEl = document.getElementById('detailPendaftaranFooter');
            if (footerEl) {
                if (['pending', 'rescheduled'].includes(item.status)) {
                    const titleText = isKelompok ? (groupName || 'Kelompok') : leaderName;
                    footerEl.innerHTML = `
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="closeModal('pendaftaranDetailModal'); openRejectModal(${item.id}, '${titleText.replace(/'/g, "\\'")}')"
                                class="px-3 py-1.5 border border-rose-200 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs transition flex items-center gap-1 shadow-xs">
                                <i class="fa-solid fa-xmark"></i>
                                <span>Tolak</span>
                            </button>
                            <button type="button" onclick="closeModal('pendaftaranDetailModal'); openRescheduleModal(${item.id}, '${titleText.replace(/'/g, "\\'")}', '${formatDateYMD(tglMulai)}', '${formatDateYMD(tglSelesai)}', '${formatDateYMD(tglMulaiSaran)}', '${formatDateYMD(tglSelesaiSaran)}', '${(catatanRevisi || '').replace(/'/g, "\\'")}', ${divisiId}, '${(divisiNama || '').replace(/'/g, "\\'")}', ${totalAnggota})"
                                class="px-3 py-1.5 border border-amber-200 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 font-semibold text-xs transition flex items-center gap-1 shadow-xs">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>Revisi Jadwal</span>
                            </button>
                            <button type="button" onclick="closeModal('pendaftaranDetailModal'); confirmApprovePendaftaran(${item.id}, '${titleText.replace(/'/g, "\\'")}', '${formatDateDMY(tglMulai)}', '${formatDateDMY(tglSelesai)}', ${totalAnggota}, ${divisiId}, '${(divisiNama || '').replace(/'/g, "\\'")}', ${kuotaDivisi})"
                                class="px-3 py-1.5 border border-emerald-200 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition flex items-center gap-1 shadow-xs">
                                <i class="fa-solid fa-check"></i>
                                <span>Terima (ACC)</span>
                            </button>
                        </div>
                        <button type="button" onclick="closeModal('pendaftaranDetailModal')"
                            class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 text-xs font-medium hover:bg-gray-50 transition">
                            Tutup
                        </button>
                    `;
                } else if (item.status === 'resignation_requested') {
                    footerEl.innerHTML = `
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="closeModal('pendaftaranDetailModal'); openRejectResignationModal(${item.id})"
                                class="px-3.5 py-1.5 border border-rose-200 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs transition flex items-center gap-1 shadow-xs">
                                <i class="fa-solid fa-xmark"></i>
                                <span>Tolak Undur Diri</span>
                            </button>
                            <button type="button" onclick="closeModal('pendaftaranDetailModal'); confirmApproveResignation(${item.id})"
                                class="px-3.5 py-1.5 border border-purple-300 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs transition flex items-center gap-1 shadow-xs">
                                <i class="fa-solid fa-check"></i>
                                <span>ACC Pengunduran Diri</span>
                            </button>
                        </div>
                        <button type="button" onclick="closeModal('pendaftaranDetailModal')"
                            class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 text-xs font-medium hover:bg-gray-50 transition">
                            Tutup
                        </button>
                    `;
                } else if (item.status === 'approved') {
                    footerEl.innerHTML = `
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="closeModal('pendaftaranDetailModal'); openTerminatePendaftaranModal(${item.id}, '${titleText.replace(/'/g, "\\'")}')"
                                class="px-3.5 py-1.5 border border-rose-300 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs transition flex items-center gap-1 shadow-xs active:scale-95 cursor-pointer">
                                <i class="fa-solid fa-user-xmark"></i>
                                <span>Berhentikan Peserta</span>
                            </button>
                        </div>
                        <button type="button" onclick="closeModal('pendaftaranDetailModal')"
                            class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 text-xs font-medium hover:bg-gray-50 transition">
                            Tutup
                        </button>
                    `;
                } else {
                    footerEl.innerHTML = `
                        <div></div>
                        <button type="button" onclick="closeModal('pendaftaranDetailModal')"
                            class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 text-xs font-medium hover:bg-gray-50 transition">
                            Tutup
                        </button>
                    `;
                }
            }

            showModal('pendaftaranDetailModal');
        }

        function openTerminatePendaftaranModal(id, titleText) {
            const form = document.getElementById('terminatePendaftaranFormPegawai');
            const sub = document.getElementById('terminatePendaftaranSub');
            const input = document.getElementById('inputTerminateReasonPegawai');
            if (form) form.action = `/pendaftaran/${id}/berhentikan`;
            if (sub) sub.textContent = titleText || 'Tindakan pembimbing / pegawai BPS';
            if (input) input.value = '';
            showModal('modalTerminatePendaftaranPegawai');
            input?.focus();
        }

        function confirmApproveResignation(id) {
            if (confirm('Apakah Anda yakin ingin menyetujui (ACC) pengunduran diri peserta ini? Kuota divisi akan otomatis bebas kembali.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pendaftaran/${id}/approve-undur-diri`;
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function openRejectResignationModal(id) {
            const form = document.getElementById('rejectResignationForm');
            if (form) {
                form.action = `/pendaftaran/${id}/reject-undur-diri`;
                const textarea = form.querySelector('textarea');
                if (textarea) textarea.value = '';
            }
            showModal('rejectResignationModal');
        }

        let currentCalendarDivisiId = null;
        let currentCalendarYear = null;
        let currentCalendarMonth = null;
        let currentExcludePendaftaranId = null;
        let currentTotalAnggota = 1;
        let currentCalendarDays = [];

        function openRescheduleModal(id, title, tglMulai, tglSelesai, saranMulai, saranSelesai, catatan, divisiId, divisiNama, totalAnggota) {
            const form = document.getElementById('formReschedulePendaftaran');
            form.action = `/pendaftaran/${id}/reschedule`;
            document.getElementById('rescheduleModalSub').textContent = `Pendaftaran: ${title}`;
            document.getElementById('rescheduleJadwalAwal').textContent = `${tglMulai} s.d. ${tglSelesai}`;
            document.getElementById('rescheduleTanggalMulai').value = saranMulai || tglMulai;
            document.getElementById('rescheduleTanggalSelesai').value = saranSelesai || tglSelesai;
            document.getElementById('rescheduleCatatan').value = catatan || '';

            const chk = document.getElementById('rescheduleForceOverQuota');
            if (chk) chk.checked = false;
            document.getElementById('rescheduleOverQuotaWarning').classList.add('hidden');

            currentExcludePendaftaranId = id;
            currentCalendarDivisiId = divisiId;
            currentTotalAnggota = totalAnggota || 1;
            if (divisiNama) {
                document.getElementById('calendarDivisiTitle').textContent = `Kalender Kuota Divisi: ${divisiNama}`;
            }

            const baseDateStr = saranMulai || tglMulai;
            const baseDate = baseDateStr ? new Date(baseDateStr) : new Date();
            currentCalendarYear = isNaN(baseDate.getFullYear()) ? (new Date()).getFullYear() : baseDate.getFullYear();
            currentCalendarMonth = isNaN(baseDate.getMonth()) ? ((new Date()).getMonth() + 1) : (baseDate.getMonth() + 1);

            showModal('pendaftaranRescheduleModal');
            loadQuotaCalendar(currentCalendarDivisiId, currentCalendarYear, currentCalendarMonth);
        }

        function navigateCalendarMonth(direction) {
            if (!currentCalendarDivisiId) return;
            currentCalendarMonth += direction;
            if (currentCalendarMonth > 12) {
                currentCalendarMonth = 1;
                currentCalendarYear += 1;
            } else if (currentCalendarMonth < 1) {
                currentCalendarMonth = 12;
                currentCalendarYear -= 1;
            }
            loadQuotaCalendar(currentCalendarDivisiId, currentCalendarYear, currentCalendarMonth);
        }

        async function loadQuotaCalendar(divisiId, year, month) {
            const loading = document.getElementById('calendarGridLoading');
            const wrapper = document.getElementById('calendarGridWrapper');
            const monthLabel = document.getElementById('calendarMonthLabel');
            const grid = document.getElementById('calendarDaysGrid');

            if (!divisiId) return;

            loading.classList.remove('hidden');
            wrapper.classList.add('hidden');

            try {
                const url = `/divisi/${divisiId}/quota-calendar?year=${year}&month=${month}&exclude_pendaftaran_id=${currentExcludePendaftaranId || ''}`;
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('Gagal memuat kalender kuota divisi');
                const data = await res.json();

                currentCalendarDays = data.days || [];
                monthLabel.textContent = data.month_name;
                grid.innerHTML = '';

                // Padding sel kosong sebelum hari pertama bulan
                for (let i = 0; i < data.first_day_of_week; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'h-14 rounded-lg bg-gray-50/50 border border-dashed border-gray-100';
                    grid.appendChild(emptyCell);
                }

                const tglMulaiVal = document.getElementById('rescheduleTanggalMulai').value;
                const tglSelesaiVal = document.getElementById('rescheduleTanggalSelesai').value;

                data.days.forEach(day => {
                    const dayCell = document.createElement('div');
                    dayCell.setAttribute('data-date', day.tanggal);

                    const isSelected = (tglMulaiVal && tglSelesaiVal && day.tanggal >= tglMulaiVal && day.tanggal <= tglSelesaiVal);
                    const isSingle = (tglMulaiVal && !tglSelesaiVal && day.tanggal === tglMulaiVal);

                    let statusClass = 'border-gray-200 bg-white hover:border-gray-400';
                    let badgeBg = 'bg-emerald-100 text-emerald-800';
                    let sisaText = `${day.sisa} sisa`;

                    if (day.status === 'over_quota') {
                        statusClass = 'border-purple-200 bg-purple-50/40';
                        badgeBg = 'bg-purple-100 text-purple-800';
                        sisaText = 'Over';
                    } else if (day.status === 'penuh') {
                        statusClass = 'border-rose-200 bg-rose-50/40';
                        badgeBg = 'bg-rose-100 text-rose-800';
                        sisaText = 'Penuh';
                    } else if (day.status === 'terbatas') {
                        statusClass = 'border-amber-200 bg-amber-50/40';
                        badgeBg = 'bg-amber-100 text-amber-800';
                        sisaText = `${day.sisa} sisa`;
                    }

                    if (isSelected || isSingle) {
                        statusClass = 'border-teal-500 bg-teal-50 ring-2 ring-teal-500';
                    }

                    dayCell.className = `h-14 p-1.5 rounded-lg border text-left cursor-pointer transition flex flex-col justify-between ${statusClass}`;
                    dayCell.innerHTML = `
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold ${day.is_today ? 'text-teal-600' : 'text-gray-800'}">${day.hari}</span>
                            ${day.is_today ? '<span class="w-1.5 h-1.5 rounded-full bg-teal-500" title="Hari Ini"></span>' : ''}
                        </div>
                        <div class="text-[9px] font-semibold px-1 py-0.5 rounded text-center truncate ${badgeBg}">
                            ${sisaText}
                        </div>
                    `;

                    dayCell.onclick = () => onCalendarDateClick(day.tanggal);
                    grid.appendChild(dayCell);
                });

                loading.classList.add('hidden');
                wrapper.classList.remove('hidden');

                evaluateRescheduleSelection();
            } catch (err) {
                loading.innerHTML = `<span class="text-rose-600 text-xs"><i class="fa-solid fa-triangle-exclamation mr-1"></i> ${err.message}</span>`;
            }
        }

        function onCalendarDateClick(dateStr) {
            const inputMulai = document.getElementById('rescheduleTanggalMulai');
            const inputSelesai = document.getElementById('rescheduleTanggalSelesai');

            if (!inputMulai.value || (inputMulai.value && inputSelesai.value)) {
                inputMulai.value = dateStr;
                inputSelesai.value = '';
            } else if (inputMulai.value && !inputSelesai.value) {
                if (dateStr < inputMulai.value) {
                    inputSelesai.value = inputMulai.value;
                    inputMulai.value = dateStr;
                } else {
                    inputSelesai.value = dateStr;
                }
            }

            refreshCalendarHighlight();
            evaluateRescheduleSelection();
        }

        function onManualDateInputChange() {
            refreshCalendarHighlight();
            evaluateRescheduleSelection();
        }

        function refreshCalendarHighlight() {
            const tglMulai = document.getElementById('rescheduleTanggalMulai').value;
            const tglSelesai = document.getElementById('rescheduleTanggalSelesai').value;
            const grid = document.getElementById('calendarDaysGrid');
            if (!grid) return;

            grid.querySelectorAll('[data-date]').forEach(cell => {
                const d = cell.getAttribute('data-date');
                const inRange = tglMulai && tglSelesai && d >= tglMulai && d <= tglSelesai;
                const isSingle = tglMulai && !tglSelesai && d === tglMulai;

                if (inRange || isSingle) {
                    cell.classList.add('ring-2', 'ring-teal-500', 'bg-teal-50');
                } else {
                    cell.classList.remove('ring-2', 'ring-teal-500', 'bg-teal-50');
                }
            });
        }

        function evaluateRescheduleSelection() {
            const tglMulai = document.getElementById('rescheduleTanggalMulai').value;
            const tglSelesai = document.getElementById('rescheduleTanggalSelesai').value;
            const statusLabel = document.getElementById('calendarRangeStatus');
            const warningBox = document.getElementById('rescheduleOverQuotaWarning');
            const warningDesc = document.getElementById('rescheduleOverQuotaDesc');

            if (!tglMulai || !tglSelesai) {
                if (statusLabel) statusLabel.textContent = '';
                if (warningBox) warningBox.classList.add('hidden');
                return;
            }

            let minSisa = 999;
            let foundDays = 0;
            let overQuotaCount = 0;

            currentCalendarDays.forEach(day => {
                if (day.tanggal >= tglMulai && day.tanggal <= tglSelesai) {
                    foundDays++;
                    if (day.sisa < minSisa) minSisa = day.sisa;
                    if (day.sisa < currentTotalAnggota) overQuotaCount++;
                }
            });

            if (foundDays > 0) {
                if (minSisa < currentTotalAnggota || overQuotaCount > 0) {
                    if (statusLabel) statusLabel.innerHTML = `<span class="text-rose-600 font-bold">⚠️ Sisa Terkecil: ${minSisa < 999 ? minSisa : 0} (Butuh ${currentTotalAnggota})</span>`;
                    if (warningBox) {
                        warningBox.classList.remove('hidden');
                        warningDesc.textContent = `Rentang tanggal yang dipilih memiliki sisa kuota terkecil ${minSisa < 999 ? minSisa : 0} kursi, sedangkan pendaftaran ini membutuhkan ${currentTotalAnggota} kursi.`;
                    }
                } else {
                    if (statusLabel) statusLabel.innerHTML = `<span class="text-emerald-600 font-bold">✅ Sisa Terkecil: ${minSisa} kursi (Cukup)</span>`;
                    if (warningBox) warningBox.classList.add('hidden');
                }
            }
        }

        async function confirmApprovePendaftaran(id, nama, tglMulaiStr, tglSelesaiStr, totalAnggota, divisiId, divisiNama, kuotaDivisi) {
            const form = document.getElementById('formConfirmApprove');
            form.action = `/pendaftaran/${id}/approve`;
            document.getElementById('approveModalSub').textContent = `ID #${id} • ${divisiNama}`;
            document.getElementById('approveInfoNama').textContent = nama;
            document.getElementById('approveInfoDivisi').textContent = `${divisiNama} (Kapasitas Resmi: ${kuotaDivisi} Orang)`;
            document.getElementById('approveInfoAnggota').textContent = `${totalAnggota} Orang`;
            document.getElementById('approveInfoPeriode').textContent = `${tglMulaiStr} s.d. ${tglSelesaiStr}`;

            const statusBox = document.getElementById('approveQuotaStatusBox');
            const inputForce = document.getElementById('approveInputForceOverQuota');
            const submitBtn = document.getElementById('btnApproveSubmit');
            const submitBtnText = document.getElementById('btnApproveSubmitText');
            const modalIcon = document.getElementById('approveModalIcon');

            statusBox.className = 'p-3 rounded-xl text-xs space-y-1 bg-gray-50 border border-gray-200 text-gray-600';
            statusBox.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-teal-600 mr-1.5"></i> Memeriksa ketersediaan kuota divisi...';
            inputForce.value = '0';

            showModal('confirmApproveModal');

            try {
                let sendTglMulai = tglMulaiStr;
                let sendTglSelesai = tglSelesaiStr;
                if (/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(tglMulaiStr)) {
                    const p = tglMulaiStr.split('/');
                    sendTglMulai = `${p[2]}-${p[1]}-${p[0]}`;
                }
                if (/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(tglSelesaiStr)) {
                    const p = tglSelesaiStr.split('/');
                    sendTglSelesai = `${p[2]}-${p[1]}-${p[0]}`;
                }

                const res = await fetch('/pendaftaran/check-quota', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        divisi_id: divisiId,
                        tanggal_mulai: sendTglMulai,
                        tanggal_selesai: sendTglSelesai,
                        jumlah_orang: totalAnggota,
                    })
                });
                const raw = await res.json();
                const ketersediaan = raw.data || raw;
                const isTersedia = (typeof raw.tersedia !== 'undefined') ? raw.tersedia : (ketersediaan.tersedia ?? false);
                const sisaKuota = (typeof raw.sisa_kuota_minimal !== 'undefined') ? raw.sisa_kuota_minimal : (ketersediaan.sisa_kuota_minimal ?? 0);

                if (isTersedia) {
                    statusBox.className = 'p-3 rounded-xl text-xs bg-emerald-50 border border-emerald-200 text-emerald-900 space-y-1';
                    statusBox.innerHTML = `
                        <div class="flex items-center gap-1.5 font-bold text-emerald-800">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                            <span>Kuota Divisi Tersedia (Memenuhi Kapasitas)</span>
                        </div>
                        <p class="text-[11px] text-emerald-700">Kapasitas resmi Divisi ${divisiNama} mencukupi untuk menerima ${totalAnggota} orang pada periode ini (Sisa minimal: ${sisaKuota} kursi).</p>
                    `;
                    inputForce.value = '0';
                    modalIcon.className = 'w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold';
                    submitBtn.className = 'px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition flex items-center gap-1.5';
                    submitBtnText.textContent = 'Setujui Pendaftaran';
                } else {
                    statusBox.className = 'p-3 rounded-xl text-xs bg-amber-50 border border-amber-300 text-amber-900 space-y-1.5';
                    statusBox.innerHTML = `
                        <div class="flex items-center gap-1.5 font-bold text-amber-800">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
                            <span>PERINGATAN: Kuota Divisi Melebihi Kapasitas!</span>
                        </div>
                        <p class="text-[11px] text-amber-800 leading-relaxed">
                            Kapasitas resmi Divisi <strong>${divisiNama}</strong> adalah <strong>${kuotaDivisi} orang</strong>, dan pada periode ini sisa kuota hanya <strong>${sisaKuota} orang</strong> (pendaftaran ini butuh <strong>${totalAnggota} orang</strong>).
                        </p>
                        <div class="p-2 bg-amber-100/70 border border-amber-300/70 rounded-lg text-[11px] text-amber-900 font-semibold flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info text-amber-700"></i>
                            <span>Pendaftaran ini akan disetujui dengan status bertanda <u>Melebihi Kuota</u>. Kapasitas kuota divisi resmi tidak akan berubah.</span>
                        </div>
                    `;
                    inputForce.value = '1';
                    modalIcon.className = 'w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold';
                    submitBtn.className = 'px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs shadow-xs transition flex items-center gap-1.5';
                    submitBtnText.textContent = 'Ya, Terima Melebihi Kuota';
                }
            } catch (err) {
                statusBox.className = 'p-3 rounded-xl text-xs bg-gray-50 border border-gray-200 text-gray-700';
                statusBox.innerHTML = `<span>Lanjutkan persetujuan pendaftaran ini.</span>`;
            }
        }

        function openRejectModal(id, title) {
            const form = document.getElementById('formRejectPendaftaran');
            form.action = `/pendaftaran/${id}/reject`;
            document.getElementById('rejectModalSub').textContent = `Pendaftaran: ${title}`;
            document.getElementById('rejectCatatan').value = '';
            showModal('pendaftaranRejectModal');
        }

        // 7. Detail Presensi Siswa Modal
        function openDetailPresensiModal(target) {
            try {
                let data = null;
                if (typeof target === 'string') {
                    try {
                        data = JSON.parse(decodeURIComponent(escape(atob(target))));
                    } catch (e1) {
                        try {
                            data = JSON.parse(target);
                        } catch (e2) {
                            const txt = document.createElement('textarea');
                            txt.innerHTML = target;
                            data = JSON.parse(txt.value);
                        }
                    }
                } else if (target && target.getAttribute) {
                    const b64 = target.getAttribute('data-absen');
                    if (b64) {
                        data = JSON.parse(decodeURIComponent(escape(atob(b64))));
                    }
                } else if (typeof target === 'object') {
                    data = target;
                }

                if (!data) return;
                currentSiswaMagangDetail = data;
                currentModalData = data;

                document.getElementById('modalNamaSiswa').textContent = data.nama;
                document.getElementById('modalSubtext').textContent = `${data.asal || 'PKL BPS'} • ${data.tanggal}`;
                document.getElementById('modalAvatar').textContent = (data.nama || 'S').charAt(0).toUpperCase();

                const badge = document.getElementById('modalStatusBadge');
                const modeKerja = document.getElementById('modalModeKerja');
                const secPhotos = document.getElementById('modalSectionPhotos');
                const secLocation = document.getElementById('modalSectionLocation');
                const secIzin = document.getElementById('modalSectionIzin');
                const emptyState = document.getElementById('modalEmptyState');

                if (!data.id) {
                    emptyState.classList.remove('hidden');
                    secPhotos.classList.add('hidden');
                    secLocation.classList.add('hidden');
                    secIzin.classList.add('hidden');

                    badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500';
                    badge.textContent = 'Belum Ada Data Presensi';
                    modeKerja.textContent = '-';
                    document.getElementById('modalJamMasuk').textContent = '--:--';
                    document.getElementById('modalJamPulang').textContent = '--:--';
                    document.getElementById('modalDurasi').textContent = '-';
                    document.getElementById('modalKetepatan').textContent = '-';
                } else {
                    emptyState.classList.add('hidden');
                    document.getElementById('modalJamMasuk').textContent = data.jam_masuk || '--:--';
                    document.getElementById('modalJamPulang').textContent = data.jam_pulang || '--:--';
                    document.getElementById('modalDurasi').textContent = data.durasi_kerja || '-';
                    document.getElementById('modalKetepatan').textContent = data.status_ketepatan || 'Tepat Waktu';

                    if (data.is_izin) {
                        secPhotos.classList.add('hidden');
                        secLocation.classList.add('hidden');
                        secIzin.classList.remove('hidden');

                        badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800';
                        badge.textContent = `Izin: ${data.jenis_izin || 'Pengajuan Izin'}`;
                        modeKerja.textContent = 'Izin Resmi';

                        document.getElementById('modalJenisIzinNama').textContent = data.jenis_izin || 'Izin';
                        document.getElementById('modalKeteranganIzin').textContent = data.keterangan_izin || 'Tidak ada catatan tambahan.';

                        const fileBox = document.getElementById('modalFileIzinContainer');
                        const fileLink = document.getElementById('modalFileIzinLink');
                        if (data.file_izin_url) {
                            fileBox.classList.remove('hidden');
                            fileLink.href = data.file_izin_url;
                        } else {
                            fileBox.classList.add('hidden');
                        }
                    } else {
                        secIzin.classList.add('hidden');
                        secPhotos.classList.remove('hidden');
                        secLocation.classList.remove('hidden');

                        if (data.is_terlambat) {
                            badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800';
                            badge.textContent = 'Hadir Terlambat';
                        } else {
                            badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800';
                            badge.textContent = 'Hadir Tepat Waktu';
                        }
                        modeKerja.textContent = data.status_kerja || 'WFO';

                        // Foto Masuk
                        const imgMasuk = document.getElementById('modalImgMasuk');
                        const emptyMasuk = document.getElementById('modalImgMasukEmpty');
                        if (data.foto_masuk_url) {
                            imgMasuk.src = data.foto_masuk_url;
                            imgMasuk.classList.remove('hidden');
                            emptyMasuk.classList.add('hidden');
                        } else {
                            imgMasuk.classList.add('hidden');
                            emptyMasuk.classList.remove('hidden');
                        }

                        // Foto Keluar
                        const imgKeluar = document.getElementById('modalImgKeluar');
                        const emptyKeluar = document.getElementById('modalImgKeluarEmpty');
                        if (data.foto_keluar_url) {
                            imgKeluar.src = data.foto_keluar_url;
                            imgKeluar.classList.remove('hidden');
                            emptyKeluar.classList.add('hidden');
                        } else {
                            imgKeluar.classList.add('hidden');
                            emptyKeluar.classList.remove('hidden');
                        }

                        // Lokasi GPS
                        const coordText = document.getElementById('modalCoordText');
                        const mapBtn = document.getElementById('modalMapBtn');
                        if (data.lokasi_lat && data.lokasi_long) {
                            coordText.textContent = `${Number(data.lokasi_lat).toFixed(5)}, ${Number(data.lokasi_long).toFixed(5)}`;
                            mapBtn.href = `https://maps.google.com/maps?q=${data.lokasi_lat},${data.lokasi_long}&hl=id&z=17`;
                            mapBtn.classList.remove('hidden');
                        } else {
                            coordText.textContent = 'Koordinat tidak tercatat';
                            mapBtn.classList.add('hidden');
                        }
                    }
                }

                showModal('detailAbsenModal');
            } catch (err) {
                console.error("Gagal membuka detail presensi:", err);
            }
        }

        function showSiswaMagangDetailFromAbsen() {
            if (currentModalData && currentModalData.siswa_info) {
                closeModal('detailAbsenModal');
                openDetailMagangModal(currentModalData.siswa_info);
            }
        }

        // 7b. Detail Magang Siswa Modal
        function openDetailMagangModal(target) {
            try {
                let data = null;
                if (typeof target === 'string') {
                    try {
                        data = JSON.parse(decodeURIComponent(escape(atob(target))));
                    } catch (e) {
                        data = JSON.parse(target);
                    }
                } else if (target && target.getAttribute) {
                    const b64 = target.getAttribute('data-siswa');
                    if (b64) {
                        data = JSON.parse(decodeURIComponent(escape(atob(b64))));
                    }
                } else if (typeof target === 'object') {
                    data = target;
                }

                if (!data) return;
                currentSiswaMagangDetail = data;

                document.getElementById('magangModalAvatar').textContent = (data.nama || 'S').charAt(0).toUpperCase();
                document.getElementById('magangModalNama').textContent = data.nama || '-';
                document.getElementById('magangModalUsername').textContent = `@${data.username || '-'} • ${data.email || '-'}`;
                document.getElementById('magangModalDivisi').innerHTML = `<i class="fa-solid fa-sitemap text-teal-600 text-xs mr-1"></i><span>${data.divisi || '-'}</span>`;
                document.getElementById('magangModalGedung').innerHTML = `<i class="fa-solid fa-building text-blue-600 text-xs mr-1"></i><span>${data.gedung || 'Kantor BPS'}</span>`;
                document.getElementById('magangModalPeriode').innerHTML = `<i class="fa-regular fa-calendar-days text-blue-600 mr-1.5"></i><span>${data.tanggal_mulai || '-'} s.d. ${data.tanggal_selesai || '-'}</span>`;
                document.getElementById('magangModalAsal').textContent = data.asal || '-';
                document.getElementById('magangModalJurusan').textContent = data.jurusan || '-';
                document.getElementById('magangModalNoHp').textContent = data.no_hp || '-';
                document.getElementById('magangModalEmail').textContent = data.email || '-';

                // CV button
                const cvBtn = document.getElementById('magangModalCvBtn');
                if (data.cv_url) {
                    cvBtn.href = data.cv_url;
                    cvBtn.classList.remove('hidden');
                } else {
                    cvBtn.classList.add('hidden');
                }

                // Porto button
                const portoBtn = document.getElementById('magangModalPortoBtn');
                if (data.porto_url) {
                    portoBtn.href = data.porto_url;
                    portoBtn.classList.remove('hidden');
                } else {
                    portoBtn.classList.add('hidden');
                }

                // Link to riwayat presensi siswa
                const link = document.getElementById('magangModalPresensiLink');
                if (data.id) {
                    link.href = `/pegawai/siswa/${data.id}/riwayat-absensi`;
                    link.classList.remove('hidden');
                } else {
                    link.classList.add('hidden');
                }

                // Update status WFH di modal
                updateWfhModalUI(data);

                showModal('detailMagangSiswaModal');
            } catch (err) {
                console.error("Gagal membuka detail magang siswa:", err);
            }
        }

        let currentSiswaMagangDetail = null;

        function updateWfhModalUI(data) {
            const badge = document.getElementById('magangWfhStatusBadge');
            const infoBox = document.getElementById('magangWfhActiveInfo');
            const datesText = document.getElementById('magangWfhActiveDates');
            const inputStart = document.getElementById('wfhInputStart');
            const inputEnd = document.getElementById('wfhInputEnd');

            if (inputStart) inputStart.value = data.wfh_start_date || '';
            if (inputEnd) inputEnd.value = data.wfh_end_date || '';

            const hasWfh = !!(data.wfh_start_date && data.wfh_end_date);
            if (hasWfh) {
                if (badge) {
                    badge.textContent = data.is_wfh_today ? 'WFH Aktif (Hari Ini)' : 'WFH Terjadwal';
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-50 text-teal-700 border border-teal-200';
                }
                if (infoBox) {
                    infoBox.classList.remove('hidden');
                }
                if (datesText) {
                    datesText.textContent = `${data.wfh_start_date} s.d. ${data.wfh_end_date}`;
                }
            } else {
                if (badge) {
                    badge.textContent = 'WFO (Normal)';
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200';
                }
                if (infoBox) {
                    infoBox.classList.add('hidden');
                }
            }
        }

        async function submitWfhAction(mode) {
            if (!currentSiswaMagangDetail || !currentSiswaMagangDetail.id) return;
            const siswaId = currentSiswaMagangDetail.id;

            let payload = {
                _token: '{{ csrf_token() }}',
                mode: mode
            };

            if (mode === 'range') {
                const start = document.getElementById('wfhInputStart')?.value;
                const end = document.getElementById('wfhInputEnd')?.value;
                if (!start || !end) {
                    alert('Silakan pilih Tanggal Mulai dan Tanggal Selesai untuk rentang WFH.');
                    return;
                }
                if (start > end) {
                    alert('Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai.');
                    return;
                }
                payload.wfh_start_date = start;
                payload.wfh_end_date = end;
            }

            const btnTarget = mode === 'today' ? document.getElementById('btnSetWfhToday') : (mode === 'range' ? document.getElementById('btnSetWfhRange') : document.getElementById('btnCancelWfh'));
            const origHtml = btnTarget ? btnTarget.innerHTML : '';
            if (btnTarget) {
                btnTarget.disabled = true;
                btnTarget.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Memproses...</span>';
            }

            try {
                const res = await fetch(`/pegawai/siswa/${siswaId}/set-wfh`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();
                if (btnTarget) {
                    btnTarget.disabled = false;
                    btnTarget.innerHTML = origHtml;
                }

                if (json.success) {
                    currentSiswaMagangDetail.wfh_start_date = json.data.wfh_start_date;
                    currentSiswaMagangDetail.wfh_end_date = json.data.wfh_end_date;
                    currentSiswaMagangDetail.is_wfh_today = json.data.is_wfh_today;
                    updateWfhModalUI(currentSiswaMagangDetail);

                    // Update data-siswa pada tombol di tabel agar tersinkronisasi jika dibuka ulang
                    const buttons = document.querySelectorAll(`button[data-siswa]`);
                    buttons.forEach(btn => {
                        try {
                            const b64 = btn.getAttribute('data-siswa');
                            if (b64) {
                                const parsed = JSON.parse(decodeURIComponent(escape(atob(b64))));
                                if (parsed.id === currentSiswaMagangDetail.id) {
                                    parsed.wfh_start_date = json.data.wfh_start_date;
                                    parsed.wfh_end_date = json.data.wfh_end_date;
                                    parsed.is_wfh_today = json.data.is_wfh_today;
                                    btn.setAttribute('data-siswa', btoa(unescape(encodeURIComponent(JSON.stringify(parsed)))));
                                }
                            }
                        } catch(e) {}
                    });

                    alert(json.message || 'Pengaturan WFH berhasil diperbarui.');
                } else {
                    alert(json.message || 'Gagal mengatur WFH.');
                }
            } catch (err) {
                if (btnTarget) {
                    btnTarget.disabled = false;
                    btnTarget.innerHTML = origHtml;
                }
                alert('Terjadi kesalahan saat menyimpan pengaturan WFH: ' + err.message);
            }
        }

        function openUbahPeriodeFromDetailMagang() {
            if (!currentSiswaMagangDetail || !currentSiswaMagangDetail.id) return;
            const data = currentSiswaMagangDetail;
            document.getElementById('formUbahPeriodePegawai').action = `/pegawai/siswa/${data.id}/update-periode`;
            document.getElementById('ubahPeriodeNamaSiswa').textContent = data.nama || '-';
            document.getElementById('ubahPeriodeDivisiSiswa').textContent = 'Divisi: ' + (data.divisi || '-');
            document.getElementById('ubahPeriodeTanggalMulaiSiswa').textContent = 'Tanggal Mulai: ' + (data.tanggal_mulai || '-');
            document.getElementById('ubahPeriodeTanggalSelesaiSiswa').textContent = 'Tanggal Selesai Saat Ini: ' + (data.tanggal_selesai || '-');

            const rawEnd = data.tanggal_selesai || '';
            let dateVal = '';
            if (rawEnd.includes('/')) {
                const parts = rawEnd.split('/');
                if (parts.length === 3) dateVal = `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
            } else if (rawEnd.includes('-')) {
                dateVal = rawEnd;
            }
            document.getElementById('ubahPeriodeInputTanggalSelesai').value = dateVal;

            closeModal('detailMagangSiswaModal');
            showModal('modalUbahPeriodePegawai');
        }

        // 8. Image Lightbox Zoom
        function zoomModalImage(type) {
            if (!currentModalData) return;
            const src = (type === 'fotoMasuk') ? currentModalData.foto_masuk_url : currentModalData.foto_keluar_url;
            if (!src) return;

            const lightbox = document.getElementById('imageLightboxModal');
            const img = document.getElementById('lightboxImage');
            img.src = src;
            lightbox.classList.remove('hidden');
        }

        function closeImageLightbox() {
            document.getElementById('imageLightboxModal')?.classList.add('hidden');
        }

        // 9. Calendar Functions
        function renderCalendarMatrix(year, month) {
            const container = document.getElementById('calendarMatrixDays');
            const titleEl = document.getElementById('calMonthTitle');
            if (!container) return;

            if (titleEl) {
                titleEl.textContent = `${namaBulanIndo[month - 1]} ${year}`;
            }

            container.innerHTML = '';

            const firstDayIndex = new Date(year, month - 1, 1).getDay(); // 0 = Min, 1 = Sen ...
            const adjustedFirstDay = (firstDayIndex === 0) ? 6 : firstDayIndex - 1; // 0 = Sen, 6 = Min

            const daysInMonth = new Date(year, month, 0).getDate();
            const daysInPrevMonth = new Date(year, month - 1, 0).getDate();

            // Prev Month spillover
            for (let i = adjustedFirstDay - 1; i >= 0; i--) {
                const dayNum = daysInPrevMonth - i;
                const cell = document.createElement('div');
                cell.className = 'py-2 text-gray-300 text-xs font-normal';
                cell.textContent = dayNum;
                container.appendChild(cell);
            }

            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

            // Current Month Days
            for (let day = 1; day <= daysInMonth; day++) {
                const dayStr = String(day).padStart(2, '0');
                const monthStr = String(month).padStart(2, '0');
                const fullDate = `${year}-${monthStr}-${dayStr}`;

                const dayOfWeek = new Date(year, month - 1, day).getDay();
                const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);
                const isToday = (fullDate === todayStr);
                const isSelected = (!isMonthlyActive && fullDate === activeDateStr);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `py-1.5 flex flex-col items-center justify-center rounded-lg transition font-medium text-xs relative cursor-pointer ${
                    isSelected
                        ? 'bg-gray-900 text-white font-bold shadow-xs'
                        : isToday
                        ? 'bg-blue-50 text-blue-700 font-bold border border-blue-200 hover:bg-blue-100'
                        : isWeekend
                        ? 'text-rose-500 hover:bg-gray-100'
                        : 'text-gray-700 hover:bg-gray-100'
                }`;

                btn.innerHTML = `<span>${day}</span>`;
                if (isToday && !isSelected) {
                    btn.innerHTML += `<span class="w-1 h-1 rounded-full bg-blue-600 absolute bottom-1"></span>`;
                }

                btn.onclick = () => filterByDate(fullDate);
                container.appendChild(btn);
            }
        }

        function changeCalMonth(direction) {
            currentCalMonth += direction;
            if (currentCalMonth > 12) {
                currentCalMonth = 1;
                currentCalYear++;
            } else if (currentCalMonth < 1) {
                currentCalMonth = 12;
                currentCalYear--;
            }
            renderCalendarMatrix(currentCalYear, currentCalMonth);
        }

        function filterByDate(dateStr) {
            window.location.href = `{{ route('pegawai.absensi.rekap') }}?tab=absensi&tanggal=${dateStr}&dari=${dateStr}&ke=${dateStr}`;
        }

        function jumpToday() {
            const today = new Date();
            const dateStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            filterByDate(dateStr);
        }

        function filterCurrentMonthFull() {
            const monthStr = String(currentCalMonth).padStart(2, '0');
            const lastDay = new Date(currentCalYear, currentCalMonth, 0).getDate();
            const dari = `${currentCalYear}-${monthStr}-01`;
            const ke = `${currentCalYear}-${monthStr}-${String(lastDay).padStart(2, '0')}`;
            window.location.href = `{{ route('pegawai.absensi.rekap') }}?tab=absensi&dari=${dari}&ke=${ke}`;
        }

        function setPegawaiFilterPreset(preset) {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');

            if (preset === 'today') {
                const todayStr = `${yyyy}-${mm}-${dd}`;
                const dariEl = document.getElementById('inputFilterDari');
                const keEl = document.getElementById('inputFilterKe');
                if (dariEl) dariEl.value = todayStr;
                if (keEl) keEl.value = todayStr;
            } else if (preset === 'this_month') {
                const lastDay = new Date(yyyy, today.getMonth() + 1, 0).getDate();
                const dariEl = document.getElementById('inputFilterDari');
                const keEl = document.getElementById('inputFilterKe');
                if (dariEl) dariEl.value = `${yyyy}-${mm}-01`;
                if (keEl) keEl.value = `${yyyy}-${mm}-${String(lastDay).padStart(2, '0')}`;
            }
        }

        // ================= 9b. KALENDER SISA KUOTA DIVISI PEGAWAI =================
        let pgCalDivisiId = null;
        let pgCalYear = (new Date()).getFullYear();
        let pgCalMonth = (new Date()).getMonth() + 1;
        let pgCalDaysData = [];

        function initPegawaiKalenderView() {
            const selectEl = document.getElementById('pegawaiKalenderDivisiSelect');
            if (selectEl) {
                pgCalDivisiId = selectEl.value;
            }
            if (pgCalDivisiId) {
                loadPegawaiKalenderKuota(pgCalDivisiId, pgCalYear, pgCalMonth);
            }
        }

        function onPegawaiDivisiChange(divisiId) {
            pgCalDivisiId = divisiId;
            loadPegawaiKalenderKuota(pgCalDivisiId, pgCalYear, pgCalMonth);
        }

        function navPegawaiKalender(direction) {
            if (!pgCalDivisiId) {
                const selectEl = document.getElementById('pegawaiKalenderDivisiSelect');
                if (selectEl) pgCalDivisiId = selectEl.value;
            }
            if (!pgCalDivisiId) return;

            pgCalMonth += direction;
            if (pgCalMonth > 12) {
                pgCalMonth = 1;
                pgCalYear += 1;
            } else if (pgCalMonth < 1) {
                pgCalMonth = 12;
                pgCalYear -= 1;
            }
            loadPegawaiKalenderKuota(pgCalDivisiId, pgCalYear, pgCalMonth);
        }

        async function loadPegawaiKalenderKuota(divisiId, year, month) {
            const loading = document.getElementById('pegawaiKalenderLoading');
            const grid = document.getElementById('pegawaiKalenderDaysGrid');
            const monthLabel = document.getElementById('pegawaiKalenderMonthLabel');
            const infoMonth = document.getElementById('pegawaiKalenderInfoMonth');
            const infoKuota = document.getElementById('pegawaiKalenderInfoKuota');

            if (!divisiId || !grid) return;

            loading.classList.remove('hidden');
            grid.innerHTML = '';

            try {
                const res = await fetch(`/divisi/${divisiId}/quota-calendar?year=${year}&month=${month}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('Gagal memuat kalender kuota divisi');
                const data = await res.json();

                pgCalDaysData = data.days || [];
                if (monthLabel) monthLabel.textContent = data.month_name;
                if (infoMonth) infoMonth.textContent = data.month_name;
                if (infoKuota) infoKuota.textContent = `${data.divisi?.kuota || 0} Orang`;

                loading.classList.add('hidden');
                grid.innerHTML = '';

                // Padding hari kosong sebelum tanggal 1
                for (let i = 0; i < data.first_day_of_week; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'min-h-[52px] sm:min-h-[90px] rounded-lg sm:rounded-xl bg-gray-50/50 border border-dashed border-gray-100';
                    grid.appendChild(emptyCell);
                }

                data.days.forEach(day => {
                    const dayCell = document.createElement('div');
                    let statusClass = 'border-gray-200 bg-white hover:border-gray-400 hover:shadow-xs';
                    let badgeClass = 'bg-emerald-100 text-emerald-800';
                    let badgeText = `${day.sisa} sisa`;

                    if (day.status === 'over_quota') {
                        statusClass = 'border-purple-200 bg-purple-50/40 hover:border-purple-300';
                        badgeClass = 'bg-purple-100 text-purple-800';
                        badgeText = 'Over';
                    } else if (day.status === 'penuh') {
                        statusClass = 'border-rose-200 bg-rose-50/40 hover:border-rose-300';
                        badgeClass = 'bg-rose-100 text-rose-800';
                        badgeText = 'Penuh';
                    } else if (day.status === 'terbatas') {
                        statusClass = 'border-amber-200 bg-amber-50/40 hover:border-amber-300';
                        badgeClass = 'bg-amber-100 text-amber-800';
                        badgeText = `${day.sisa} sisa`;
                    }

                    if (day.is_today) {
                        statusClass += ' ring-2 ring-gray-900 bg-gray-50/60';
                    }

                    dayCell.className = `min-h-[52px] sm:min-h-[90px] p-1 sm:p-2.5 rounded-lg sm:rounded-xl border flex flex-col justify-between cursor-pointer transition text-left overflow-hidden ${statusClass}`;
                    dayCell.onclick = () => openDateDetailModal(day, data.divisi);

                    // Day Number styling:
                    // If is_today: on mobile, black circle for the date number. On desktop, standard font with desktop "Hari Ini" badge.
                    let dayNumHtml = '';
                    if (day.is_today) {
                        dayNumHtml = `
                            <div class="flex items-center justify-between w-full">
                                <span class="w-5.5 h-5.5 rounded-full bg-gray-900 text-white flex items-center justify-center text-[10px] font-bold mx-auto sm:mx-0 sm:w-auto sm:h-auto sm:bg-transparent sm:text-gray-900 sm:text-sm">
                                    ${day.hari}
                                </span>
                                <span class="hidden sm:inline-block text-[9px] font-bold bg-gray-900 text-white px-1.5 py-0.5 rounded">Hari Ini</span>
                            </div>
                        `;
                    } else {
                        const dayColor = day.day_of_week === 0 ? 'text-rose-600' : (day.day_of_week === 6 ? 'text-blue-600' : 'text-gray-900');
                        dayNumHtml = `
                            <div class="flex items-center justify-between w-full">
                                <span class="text-xs sm:text-sm font-bold ${dayColor} mx-auto sm:mx-0">
                                    ${day.hari}
                                </span>
                            </div>
                        `;
                    }

                    // Bottom info (Mobile vs Desktop):
                    // On desktop: full text badge ("3 sisa", "Penuh", "Over")
                    // On mobile:
                    //   - If penuh: red dot / color only ("warna merahnya saja")
                    //   - If over: purple dot
                    //   - If sisa kuota available: only the number ("hanya angka saja")
                    let bottomMobileHtml = '';
                    if (day.status === 'penuh') {
                        bottomMobileHtml = `
                            <div class="flex items-center justify-center sm:hidden w-full py-0.5" title="Kuota Penuh">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 ring-2 ring-rose-200"></span>
                            </div>
                        `;
                    } else if (day.status === 'over_quota') {
                        bottomMobileHtml = `
                            <div class="flex items-center justify-center sm:hidden w-full py-0.5" title="Over Kuota">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-500 ring-2 ring-purple-200"></span>
                            </div>
                        `;
                    } else {
                        bottomMobileHtml = `
                            <div class="flex items-center justify-center sm:hidden w-full" title="Sisa Kuota: ${day.sisa}">
                                <span class="text-[10px] font-extrabold px-1.5 py-0.2 rounded ${badgeClass}">
                                    ${day.sisa}
                                </span>
                            </div>
                        `;
                    }

                    dayCell.innerHTML = `
                        ${dayNumHtml}
                        <div class="w-full">
                            <!-- Desktop view: Full badge -->
                            <div class="hidden sm:flex items-center justify-between">
                                <span class="text-[9px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded ${badgeClass}">
                                    ${badgeText}
                                </span>
                            </div>
                            <!-- Mobile view: Only number or red color -->
                            ${bottomMobileHtml}
                        </div>
                    `;

                    grid.appendChild(dayCell);
                });

            } catch (err) {
                console.error(err);
                loading.innerHTML = `
                    <div class="text-rose-500 py-6">
                        <i class="fa-solid fa-circle-exclamation text-xl mb-1"></i>
                        <p class="text-xs font-semibold">Gagal memuat kalender kuota divisi.</p>
                    </div>
                `;
            }
        }

        function openDateDetailModal(day, divisi) {
            document.getElementById('dateDetailTitle').textContent = `Rincian Tanggal: ${formatDateDMY(day.tanggal)}`;
            document.getElementById('dateDetailSub').textContent = `Divisi: ${divisi?.nama_divisi || '-'} (Kapasitas Resmi: ${divisi?.kuota || 0} org)`;

            const body = document.getElementById('dateDetailBody');
            let badgeClass = 'bg-emerald-100 text-emerald-800';
            let statusLabel = 'Kuota Masih Tersedia';
            if (day.status === 'over_quota') {
                badgeClass = 'bg-purple-100 text-purple-800';
                statusLabel = 'Melebihi Kuota Resmi';
            } else if (day.status === 'penuh') {
                badgeClass = 'bg-rose-100 text-rose-800';
                statusLabel = 'Kuota Penuh';
            } else if (day.status === 'terbatas') {
                badgeClass = 'bg-amber-100 text-amber-800';
                statusLabel = 'Kuota Terbatas';
            }

            let pesertasHtml = '';
            if (day.pesertas && day.pesertas.length > 0) {
                pesertasHtml = `
                    <div class="space-y-2 mt-4 pt-3 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                <i class="fa-solid fa-users text-teal-600"></i>
                                <span>Peserta Magang Aktif (${day.pesertas.length} Orang):</span>
                            </span>
                        </div>
                        <div class="space-y-2 max-h-56 overflow-y-auto custom-scrollbar pr-1">
                            ${day.pesertas.map((p) => `
                                <div class="p-3 bg-gray-50/80 hover:bg-gray-100/80 rounded-xl border border-gray-100 flex items-center justify-between gap-3 transition">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-gray-900 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                            ${p.nama ? p.nama.charAt(0).toUpperCase() : 'M'}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 text-xs truncate">${p.nama}</p>
                                            <p class="text-[11px] text-gray-500 truncate">${p.asal || '-'} &bull; ${p.jurusan || '-'}</p>
                                            ${p.kelompok ? `<span class="inline-block mt-0.5 text-[9px] font-semibold text-purple-700 bg-purple-50 px-1.5 py-0.2 rounded border border-purple-100">Kelompok: ${p.kelompok}</span>` : ''}
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-[10px] text-gray-400 font-medium block">Periode</span>
                                        <span class="text-[10px] font-bold text-gray-700">${p.periode}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else {
                pesertasHtml = `
                    <div class="p-4 rounded-xl border border-dashed border-gray-200 bg-gray-50/50 text-center text-gray-400 text-xs mt-3">
                        <i class="fa-solid fa-user-slash text-base mb-1 text-gray-300 block"></i>
                        <span>Belum ada peserta magang yang menempati kuota pada tanggal ini.</span>
                    </div>
                `;
            }

            body.innerHTML = `
                <div class="grid grid-cols-3 gap-2 p-3 bg-gray-50 rounded-xl border border-gray-100 text-center text-xs">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Kapasitas</span>
                        <strong class="text-gray-900 text-sm">${day.kuota} org</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Terisi</span>
                        <strong class="text-gray-900 text-sm">${day.terisi} org</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Sisa Kuota</span>
                        <strong class="text-gray-900 text-sm">${day.sisa} org</strong>
                    </div>
                </div>

                <div class="p-3 rounded-xl border flex items-center justify-between text-xs ${day.status === 'penuh' ? 'bg-rose-50 border-rose-200' : (day.status === 'over_quota' ? 'bg-purple-50 border-purple-200' : 'bg-emerald-50 border-emerald-200')}">
                    <span class="font-bold text-gray-900">Status Ketersediaan:</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full ${badgeClass}">${statusLabel}</span>
                </div>

                ${pesertasHtml}

            `;

            showModal('pegawaiDateDetailModal');
        }

        // 10. Initialization on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function() {
            setupModalForms();

            @if(isset($activePegawaiModal) && $activePegawaiModal)
                showModal('{{ $activePegawaiModal }}');
            @endif

            const urlParams = new URLSearchParams(window.location.search);
            const initialTab = urlParams.get('tab') || "{{ $activeTab }}";
            showMainContent(initialTab || 'pendaftaran');

            renderCalendarMatrix(currentCalYear, currentCalMonth);
            updateRecordCountBadge();
        });
    </script>
</body>
</html>
