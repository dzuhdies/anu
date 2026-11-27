<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPS Magang - Dashboard</title>
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

        /* Progress bar styles */
        .progress-track { background-color: #f3f4f6; height: 6px; border-radius: 999px; width: 100%; }
        .progress-fill { height: 100%; border-radius: 999px; }

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
                padding: 0.5rem 0;
                border-bottom: 1px dashed #f3f4f6;
                text-align: right;
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
            }
            .responsive-table td.actions-cell {
                justify-content: flex-end;
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
            .responsive-table tr.row-pendaftaran td[data-label="Divisi"],
            .responsive-table tr.row-pendaftaran td[data-label="Periode"],
            .responsive-table tr.row-pendaftaran td[data-label="Jumlah"],
            .responsive-table tr.row-pendaftaran td[data-label="Status"] {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                text-align: right !important;
            }
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

        <!-- ================= SIDEBAR (KARNIVA STYLE) ================= -->
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
                    <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Main Menu</h3>
                    <ul class="space-y-1 text-sm font-medium">
                        <li>
                            <button onclick="showMainContent('pendaftaran'); closeSidebarMobile();" id="side-nav-pendaftaran"
                                class="sidebar-nav-link active w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-id-card-clip w-4 text-center"></i> Pendaftaran</span>
                                @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-blue-500 text-white">{{ $pendingCount }}</span>
                                @endif
                            </button>
                        </li>
                        <li>
                            <button onclick="showUserTable('siswa'); closeSidebarMobile();" id="side-nav-siswa"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-user-graduate w-4 text-center"></i> Siswa PKL</span>
                                <span class="text-xs text-gray-400">{{ $siswaUsers->count() }}</span>
                            </button>
                        </li>
                        <li>
                            <button onclick="showUserTable('pegawai'); closeSidebarMobile();" id="side-nav-pegawai"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-user-tie w-4 text-center"></i> Pegawai</span>
                                <span class="text-xs text-gray-400">{{ $pegawaiUsers->count() }}</span>
                            </button>
                        </li>
                        <li>
                            <button onclick="showUserTable('admin'); closeSidebarMobile();" id="side-nav-admin"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-user-shield w-4 text-center"></i> Administrator</span>
                                <span class="text-xs text-gray-400">{{ $adminUsers->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Group: Workspace & Wilayah -->
                <div>
                    <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Workspace & Divisi</h3>
                    <ul class="space-y-1 text-sm font-medium">
                        <li>
                            <button onclick="showMainContent('divisi'); closeSidebarMobile();" id="side-nav-divisi"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-sitemap w-4 text-center"></i> Divisi & Kuota</span>
                                <span class="text-xs text-gray-400">{{ $divisis->count() }}</span>
                            </button>
                        </li>
                        <li>
                            <button onclick="showMainContent('gedung'); closeSidebarMobile();" id="side-nav-gedung"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-building w-4 text-center"></i> Gedung Kantor</span>
                                <span class="text-xs text-gray-400">{{ $gedungs->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Group: Pengaturan Presensi -->
                <div>
                    <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Konfigurasi Presensi</h3>
                    <ul class="space-y-1 text-sm font-medium">
                        <li>
                            <button onclick="showModal('jamMasukModal'); closeSidebarMobile();"
                                class="w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-regular fa-clock w-4 text-center text-rose-500"></i> Batas Jam Telat</span>
                                <span class="text-[11px] font-mono font-semibold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">{{ $jamTelat ?? '08:16' }}</span>
                            </button>
                        </li>
                        <li>
                            <button onclick="showModal('jenisIzinModal'); closeSidebarMobile();"
                                class="w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-clipboard-check w-4 text-center text-teal-600"></i> Kategori Izin</span>
                                <span class="text-xs text-gray-400">{{ $jenisIzin->count() }}</span>
                            </button>
                        </li>
                        <li class="px-1 pt-1">
                            <form method="POST" action="{{ route('logbook.toggle') }}" class="flex items-center justify-between gap-2 px-2 py-1.5">
                                @csrf
                                <span class="text-xs text-gray-600 flex items-center gap-2">
                                    <i class="fa-solid fa-book-open text-amber-600 text-[11px]"></i> Review Logbook (Pegawai)
                                </span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="enabled" value="1" class="sr-only peer"
                                        {{ ($logbookReviewEnabled ?? true) ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-emerald-500 transition after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
                                </label>
                            </form>
                        </li>
                    </ul>
                </div>

                <!-- Group: Audit & Logs -->
                <div>
                    <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Monitoring</h3>
                    <ul class="space-y-1 text-sm font-medium">
                        <li>
                            <a href="{{ route('admin.logs') }}"
                                class="w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-shield-halved w-4 text-center"></i> Log Aktivitas</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-gray-400"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Sidebar: Profile & Logout -->
            <div class="p-3 border-t border-gray-100 mt-auto bg-[#fafafa]">
                <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-gray-100 shadow-xs">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-gray-900 text-white font-bold flex items-center justify-center text-xs shrink-0">
                            {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-semibold text-gray-900 truncate">{{ Auth::user()->username ?? 'Admin' }}</p>
                            <p class="text-[10px] text-gray-400">Administrator</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
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

        <!-- ================= MAIN CONTENT (KARNIVA STYLE) ================= -->
        <main class="flex-1 flex flex-col bg-[#fbfcfd] overflow-hidden">

            <!-- Top Header -->
            <header class="h-16 flex items-center justify-between px-4 sm:px-8 border-b border-gray-100 border-dashed shrink-0 bg-white">
                <!-- Breadcrumbs & Mobile Trigger -->
                <div class="flex items-center gap-2 text-sm font-medium">
                    <button onclick="toggleSidebarMobile()" class="md:hidden w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-500 mr-1">
                        <i class="fa-solid fa-bars text-sm"></i>
                    </button>
                    <span class="text-gray-400 hidden sm:inline">BPS Magang</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 hidden sm:inline"></i>
                    <i class="fa-solid fa-chart-pie text-gray-400"></i>
                    <span id="breadcrumb-active-title" class="text-gray-800 font-semibold truncate max-w-[200px] sm:max-w-none">Pendaftaran & Seleksi Magang</span>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <button onclick="showMainContent('pendaftaran')" title="Lihat Pendaftaran"
                        class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-500 hover:text-black bg-white relative transition">
                        <i class="fa-regular fa-bell text-sm"></i>
                        @if(isset($pendingCount) && $pendingCount > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-blue-500 text-white rounded-full text-[9px] font-bold flex items-center justify-center animate-pulse">
                            {{ $pendingCount }}
                        </span>
                        @endif
                    </button>
                </div>
            </header>

            <!-- Scrollable Dashboard Content -->
            <div class="flex-1 overflow-y-auto custom-scrollbar px-4 sm:px-8 py-5 space-y-6">

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
                    $adminModalFieldMap = [
                        'jamMasukModal' => ['jam_masuk', 'jam_pulang', 'toleransi_menit', 'jam_telat'],
                        'jenisIzinModal' => ['nama'],
                        'userModal' => ['username', 'password', 'role', 'email', 'nomor_induk', 'nama_lengkap', 'asal_pkl', 'gedung_id', 'divisi_id'],
                        'userEditModal' => ['username', 'password', 'role', 'email', 'nama_lengkap', 'asal_pkl', 'gedung_id', 'divisi_id'],
                        'gedungModal' => ['nama', 'latitude', 'longitude', 'radius_meter'],
                        'gedungEditModal' => ['nama', 'latitude', 'longitude', 'radius_meter'],
                        'divisiAddModal' => ['nama_divisi', 'kuota', 'deskripsi'],
                        'divisiEditModal' => ['nama_divisi', 'kuota', 'deskripsi'],
                        'pendaftaranRejectModal' => ['catatan_admin'],
                        'rejectResignationModal' => ['catatan_admin', 'catatan'],
                        'pendaftaranRescheduleModal' => ['tanggal_mulai_saran', 'tanggal_selesai_saran', 'catatan_revisi_jadwal'],
                    ];
                    $activeAdminModal = session('modal_error_target');
                    if (!$activeAdminModal && $errors->any()) {
                        foreach ($adminModalFieldMap as $mId => $fields) {
                            if ($errors->hasAny($fields)) {
                                $activeAdminModal = $mId;
                                break;
                            }
                        }
                    }
                @endphp

                @if (session('error') && !$activeAdminModal)
                <div class="card p-3.5 bg-rose-50/70 border-rose-100 flex items-center justify-between text-rose-900 shrink-0">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 p-1">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>
                @endif

                @if ($errors->any() && !$activeAdminModal)
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

                <!-- ================= 4 KPI CARDS (KARNIVA REFERENCE STYLE) ================= -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <!-- KPI Card 1: All Applications -->
                    <div onclick="showMainContent('pendaftaran')" class="card p-5 relative overflow-hidden cursor-pointer hover:border-gray-300 transition group">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pendaftar</h3>
                            <i class="fa-regular fa-circle-dot text-gray-300 group-hover:text-blue-500 transition"></i>
                        </div>
                        <div class="text-3xl font-extrabold text-gray-900 mb-1">{{ $pendaftarans->count() }}</div>
                        <div class="text-xs text-gray-500"><span class="text-blue-500 font-semibold">{{ $pendaftarans->where('status', 'approved')->count() }} disetujui</span> dari total pemohon</div>
                    </div>

                    <!-- KPI Card 2: Menunggu Review -->
                    <div onclick="showMainContent('pendaftaran'); filterPendaftaranTable('pending');" class="card p-5 relative overflow-hidden cursor-pointer hover:border-gray-300 transition group">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menunggu Review</h3>
                            <i class="fa-regular fa-clock text-gray-300 group-hover:text-amber-500 transition"></i>
                        </div>
                        <div class="text-3xl font-extrabold text-gray-900 mb-1">{{ $pendingCount }}</div>
                        <div class="text-xs text-gray-500">
                            @if($pendingCount > 0)
                            <span class="text-amber-500 font-semibold">Perlu tindakan</span> verifikasi admin
                            @else
                            <span class="text-emerald-500 font-semibold">Semua bersih</span> tidak ada antrean
                            @endif
                        </div>
                    </div>

                    <!-- KPI Card 3: Siswa PKL Aktif -->
                    <div onclick="showUserTable('siswa')" class="card p-5 relative overflow-hidden cursor-pointer hover:border-gray-300 transition group">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa PKL Aktif</h3>
                            <i class="fa-regular fa-circle-check text-gray-300 group-hover:text-emerald-500 transition"></i>
                        </div>
                        <div class="text-3xl font-extrabold text-gray-900 mb-1">{{ $siswaUsers->count() }}</div>
                        <div class="text-xs text-gray-500"><span class="text-emerald-500 font-semibold">Siap presensi</span> di gedung BPS</div>
                    </div>

                    <!-- KPI Card 4: Divisi & Kapasitas Kuota -->
                    <div onclick="showMainContent('divisi')" class="card p-5 relative overflow-hidden cursor-pointer hover:border-gray-300 transition group">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Divisi Terbuka</h3>
                            <i class="fa-solid fa-sitemap text-gray-300 group-hover:text-purple-500 transition"></i>
                        </div>
                        <div class="text-3xl font-extrabold text-gray-900 mb-1">{{ $divisis->count() }}</div>
                        <div class="text-xs text-gray-500"><span class="text-purple-500 font-semibold">{{ $divisis->sum('kuota') }} total kursi</span> kuota magang</div>
                    </div>
                </div>

                <!-- ================= VIEW 1: PENDAFTARAN & SELEKSI MAGANG ================= -->
                <div id="main-content-pendaftaran" class="main-content-view space-y-4">
                <!-- ================= DIVISION QUOTA CAROUSEL ================= -->
                <div id="quota-carousel-section" class="mb-6">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-teal-600"></i>
                            Kapasitas Kuota Divisi
                        </h3>
                        <div class="flex gap-1">
                            <button onclick="document.getElementById('quota-carousel').scrollBy({left: -200, behavior: 'smooth'})" class="w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:text-black shadow-sm">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </button>
                            <button onclick="document.getElementById('quota-carousel').scrollBy({left: 200, behavior: 'smooth'})" class="w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:text-black shadow-sm">
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                    <div id="quota-carousel" class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar snap-x snap-mandatory scroll-smooth">
                        @foreach($divisis as $div)
                        @php
                            $terisiCount = $div->terisi_saat_ini;
                            $kuotaMax = max(1, $div->kuota);
                            $percentage = min(100, round(($terisiCount / $kuotaMax) * 100));
                            $isFull = $terisiCount >= $div->kuota;
                        @endphp
                        <div class="flex-none w-[200px] sm:w-[240px] snap-start">
                            <div class="card p-4 bg-white border border-gray-100 hover:border-teal-200 transition-colors shadow-sm relative overflow-hidden">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase truncate pr-2">{{ $div->nama_divisi }}</span>
                                    <i class="fa-solid fa-circle text-[6px] {{ $isFull ? 'text-rose-500' : 'text-emerald-500' }}"></i>
                                </div>
                                <div class="flex items-end justify-between mb-2">
                                    <div class="text-xl font-black text-gray-900">
                                        {{ $terisiCount }}<span class="text-gray-300 text-sm font-medium">/{{ $div->kuota }}</span>
                                    </div>
                                    <div class="text-[10px] font-bold {{ $isFull ? 'text-rose-600' : 'text-teal-600' }}">
                                        {{ $percentage }}%
                                    </div>
                                </div>
                                <div class="progress-track bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="progress-fill h-full rounded-full {{ $isFull ? 'bg-rose-500' : ($percentage >= 80 ? 'bg-amber-500' : 'bg-teal-500') }}" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                    <div class="card overflow-hidden">
                        <!-- Card Header & Filter -->
                        <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white">
                            <div>
                                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                    <span>Pendaftaran & Seleksi Magang</span>
                                </h2>
                                <p class="text-xs text-gray-500 mt-0.5">Tinjau CV & portofolio, verifikasi ketersediaan kuota, dan tentukan persetujuan</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                                <!-- Filter Divisi Dropdown -->
                                <div class="flex items-center gap-1.5 bg-[#f3f4f6] px-2.5 py-1 rounded-lg text-xs font-medium border border-gray-200/60 shadow-2xs">
                                    <i class="fa-solid fa-sitemap text-teal-600 text-[11px]"></i>
                                    <label for="filterPendaftaranDivisi" class="text-gray-500 font-semibold text-[11px]">Divisi:</label>
                                    <select id="filterPendaftaranDivisi" onchange="applyPendaftaranFilters()"
                                        class="bg-transparent border-none text-xs font-bold text-gray-800 outline-none cursor-pointer py-0.5">
                                        <option value="semua">Semua Divisi</option>
                                        @foreach($divisis as $div)
                                            @php $divCount = $pendaftarans->where('divisi_id', $div->id)->count(); @endphp
                                            <option value="{{ $div->id }}">{{ $div->nama_divisi }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Status Pills -->
                                <div class="flex items-center gap-1.5 bg-[#f3f4f6] p-1 rounded-lg text-xs font-medium flex-wrap">
                                    <button onclick="filterPendaftaranTable('semua')" id="btn-filter-pendaftaran-semua"
                                        class="filter-pendaftaran-btn px-3 py-1 rounded-md bg-white text-gray-900 shadow-xs transition">
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
                        </div>

                        <!-- Table Pendaftaran -->
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
                                    <tr class="hover:bg-gray-50/70 transition-colors row-pendaftaran" data-status="{{ $p->status }}" data-divisi-id="{{ $p->divisi_id }}">
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
                                            <i class="fa-solid fa-id-card-clip text-3xl mb-2 text-gray-300 block"></i>
                                            <p class="font-semibold text-sm text-gray-600">Belum ada permohonan pendaftaran magang</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Pendaftaran yang dikirim calon peserta akan muncul di sini secara otomatis.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ================= VIEW 2: MASTER USER MANAGEMENT ================= -->
                <div id="main-content-user" class="main-content-view hidden space-y-4">
                    <div class="card overflow-hidden">
                        <!-- Toolbar -->
                        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white">
                            <!-- Role Tab Pills -->
                            <div class="flex items-center gap-1 bg-[#f3f4f6] p-1 rounded-lg text-xs font-medium">
                                <button onclick="showUserTable('siswa')" id="tab-pill-siswa"
                                    class="role-tab-btn px-3 py-1 rounded-md bg-white text-gray-900 shadow-xs transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-user-graduate text-xs"></i>
                                    <span>Siswa PKL</span>
                                    <span class="bg-gray-100 text-gray-600 px-1.5 py-0.2 rounded-full text-[10px]">{{ $siswaUsers->count() }}</span>
                                </button>
                                <button onclick="showUserTable('pegawai')" id="tab-pill-pegawai"
                                    class="role-tab-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-user-tie text-xs"></i>
                                    <span>Pegawai</span>
                                    <span class="bg-gray-100 text-gray-600 px-1.5 py-0.2 rounded-full text-[10px]">{{ $pegawaiUsers->count() }}</span>
                                </button>
                                <button onclick="showUserTable('admin')" id="tab-pill-admin"
                                    class="role-tab-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-user-shield text-xs"></i>
                                    <span>Admin</span>
                                    <span class="bg-gray-100 text-gray-600 px-1.5 py-0.2 rounded-full text-[10px]">{{ $adminUsers->count() }}</span>
                                </button>
                                <button onclick="showUserTable('semua')" id="tab-pill-semua"
                                    class="role-tab-btn px-3 py-1 rounded-md text-gray-600 hover:text-black transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-users text-xs"></i>
                                    <span>Semua</span>
                                </button>
                            </div>

                            <button onclick="showModal('userModal')"
                                class="text-xs font-semibold bg-gray-900 text-white hover:bg-black px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition">
                                <i class="fa-solid fa-plus text-[10px]"></i> User Baru
                            </button>
                        </div>

                        <!-- SUB-TABLE: SISWA -->
                        <div id="user-table-siswa" class="user-table">
                            <div class="px-6 py-3 bg-[#fafafa] border-b border-gray-100 flex items-center justify-between text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <span>Daftar Siswa PKL ({{ $siswaUsers->count() }})</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left text-gray-600 responsive-table">
                                    <thead class="text-[11px] text-gray-400 uppercase bg-white font-semibold border-b border-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Akun Siswa</th>
                                            <th scope="col" class="px-6 py-3">Asal PKL / Kampus</th>
                                            <th scope="col" class="px-6 py-3">Gedung Presensi</th>
                                            <th scope="col" class="px-6 py-3 text-right">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse ($siswaUsers as $user)
                                        <tr class="hover:bg-gray-50/70 transition-colors search-row">
                                            <td data-label="Akun Siswa" class="px-6 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-xs border border-blue-100">
                                                        {{ strtoupper(substr($user->siswaProfile->nama_lengkap ?? $user->username, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-gray-900 searchable-text">
                                                            {{ $user->siswaProfile->nama_lengkap ?? '-' }}
                                                        </div>
                                                        <div class="text-gray-400 text-[11px] font-mono searchable-text">
                                                            {{ '@' . $user->username }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="Asal PKL" class="px-6 py-3.5 font-medium text-gray-700 searchable-text">
                                                <div>{{ $user->siswaProfile->asal_pkl ?? '-' }}</div>
                                                @if(!empty($user->siswaProfile?->major))
                                                    <div class="text-[11px] text-gray-400 font-normal">{{ $user->siswaProfile->major }}</div>
                                                @endif
                                            </td>
                                            <td data-label="Gedung" class="px-6 py-3.5 searchable-text">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 text-[11px] border border-amber-200">
                                                    <i class="fa-solid fa-location-dot text-amber-600 text-[9px]"></i>
                                                    {{ $user->siswaProfile->gedung->nama ?? 'Belum Diatur' }}
                                                </span>
                                            </td>
                                            <td data-label="Aksi" class="px-6 py-3.5 text-right actions-cell">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    @php
                                                        $rawStart = $user->siswaProfile?->start_date ? \Carbon\Carbon::parse($user->siswaProfile->start_date)->format('Y-m-d') : '';
                                                        $rawEnd = $user->siswaProfile?->end_date ? \Carbon\Carbon::parse($user->siswaProfile->end_date)->format('Y-m-d') : '';
                                                    @endphp
                                                    <button onclick="openEditUser('{{ route('admin.users.update', $user->id) }}','{{ $user->username }}','{{ $user->role }}','{{ addslashes($user->siswaProfile->nama_lengkap ?? '') }}','{{ addslashes($user->siswaProfile->asal_pkl ?? '') }}','{{ $user->siswaProfile->gedung_id ?? '' }}','','{{ $rawStart }}','{{ $rawEnd }}','{{ addslashes($user->siswaProfile->major ?? '') }}')"
                                                        class="p-1.5 border border-gray-200 rounded-lg hover:bg-gray-100 text-gray-600 transition" title="Edit Siswa / Ubah Periode Magang">
                                                        <i class="fa-solid fa-pen text-xs"></i>
                                                    </button>
                                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user siswa: {{ $user->username }}?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 border border-rose-200 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Hapus User">
                                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-8 text-gray-400">Belum ada data siswa</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- SUB-TABLE: PEGAWAI -->
                        <div id="user-table-pegawai" class="user-table hidden">
                            <div class="px-6 py-3 bg-[#fafafa] border-b border-gray-100 flex items-center justify-between text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <span>Daftar Pegawai / Staff ({{ $pegawaiUsers->count() }})</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left text-gray-600 responsive-table">
                                    <thead class="text-[11px] text-gray-400 uppercase bg-white font-semibold border-b border-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Akun Pegawai</th>
                                            <th scope="col" class="px-6 py-3">Divisi Tugas</th>
                                            <th scope="col" class="px-6 py-3">Gedung Penempatan</th>
                                            <th scope="col" class="px-6 py-3 text-right">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse ($pegawaiUsers as $user)
                                        <tr class="hover:bg-gray-50/70 transition-colors search-row">
                                            <td data-label="Akun Pegawai" class="px-6 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center text-xs border border-emerald-100">
                                                        {{ strtoupper(substr($user->pegawaiProfile->nama_lengkap ?? $user->username, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-gray-900 searchable-text">
                                                            {{ $user->pegawaiProfile->nama_lengkap ?? '-' }}
                                                        </div>
                                                        <div class="text-gray-400 text-[11px] font-mono searchable-text">
                                                            {{ '@' . $user->username }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="Divisi Tugas" class="px-6 py-3.5 searchable-text">
                                                @if($user->pegawaiProfile && $user->pegawaiProfile->divisi)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-teal-50 text-teal-800 text-[11px] font-semibold border border-teal-200">
                                                        <i class="fa-solid fa-sitemap text-teal-600 text-[9px]"></i>
                                                        {{ $user->pegawaiProfile->divisi->nama_divisi }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-800 text-[11px] font-semibold border border-purple-200" title="Akses seluruh divisi">
                                                        <i class="fa-solid fa-globe text-purple-600 text-[9px]"></i>
                                                        Semua Divisi (Akses Penuh)
                                                    </span>
                                                @endif
                                            </td>
                                            <td data-label="Gedung" class="px-6 py-3.5 searchable-text">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 text-[11px] border border-amber-200">
                                                    <i class="fa-solid fa-location-dot text-amber-600 text-[9px]"></i>
                                                    {{ $user->pegawaiProfile->gedung->nama ?? 'Belum Diatur' }}
                                                </span>
                                            </td>
                                            <td data-label="Aksi" class="px-6 py-3.5 text-right actions-cell">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <button onclick="openEditUser('{{ route('admin.users.update', $user->id) }}','{{ $user->username }}','{{ $user->role }}','{{ $user->pegawaiProfile->nama_lengkap ?? '' }}','','{{ $user->pegawaiProfile->gedung_id ?? '' }}','{{ $user->pegawaiProfile->divisi_id ?? '' }}')"
                                                        class="p-1.5 border border-gray-200 rounded-lg hover:bg-gray-100 text-gray-600 transition" title="Edit Pegawai">
                                                        <i class="fa-solid fa-pen text-xs"></i>
                                                    </button>
                                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user pegawai {{ $user->username }}?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 border border-rose-200 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Hapus User">
                                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-8 text-gray-400">Belum ada data pegawai</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- SUB-TABLE: ADMIN -->
                        <div id="user-table-admin" class="user-table hidden">
                            <div class="px-6 py-3 bg-[#fafafa] border-b border-gray-100 flex items-center justify-between text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <span>Daftar Administrator ({{ $adminUsers->count() }})</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left text-gray-600 responsive-table">
                                    <thead class="text-[11px] text-gray-400 uppercase bg-white font-semibold border-b border-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Akun Admin</th>
                                            <th scope="col" class="px-6 py-3">Otoritas</th>
                                            <th scope="col" class="px-6 py-3 text-right">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse ($adminUsers as $user)
                                        <tr class="hover:bg-gray-50/70 transition-colors search-row">
                                            <td data-label="Akun Admin" class="px-6 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-gray-900 text-white font-bold flex items-center justify-center text-xs">
                                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-gray-900 searchable-text">
                                                            {{ $user->username }}
                                                        </div>
                                                        <div class="text-gray-400 text-[11px]">{{ $user->email ?? 'Superadmin BPS' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="Role" class="px-6 py-3.5">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-800 text-xs font-medium">
                                                    <i class="fa-solid fa-shield-halved text-gray-500 text-[10px]"></i>
                                                    Administrator
                                                </span>
                                            </td>
                                            <td data-label="Aksi" class="px-6 py-3.5 text-right actions-cell">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    @if(Auth::id() != $user->username && Auth::user()->id != $user->id)
                                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun admin {{ $user->username }}?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 border border-rose-200 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Hapus Admin">
                                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                                        </button>
                                                    </form>
                                                    @else
                                                    <span class="text-[11px] text-gray-400 italic">Akun Anda</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-8 text-gray-400">Belum ada data admin</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ================= VIEW 3: MASTER DIVISI & KUOTA (WITH PROGRESS BARS) ================= -->
                <div id="main-content-divisi" class="main-content-view hidden space-y-4">
                    <div class="card overflow-hidden">
                        <!-- Toolbar -->
                        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white">
                            <div>
                                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                    <span>Divisi & Kuota Magang</span>
                                </h2>
                                <p class="text-xs text-gray-500 mt-0.5">Kelola divisi penempatan dan batas kapasitas kuota maksimal bersamaan per divisi</p>
                            </div>

                            <button onclick="openAddDivisi()"
                                class="text-xs font-semibold bg-gray-900 text-white hover:bg-black px-3.5 py-1.5 rounded-lg flex items-center gap-1.5 shadow-sm transition">
                                <i class="fa-solid fa-plus text-[10px]"></i> Tambah Divisi Baru
                            </button>
                        </div>

                        <!-- Grid Cards Divisi dengan Progress Bar Karniva -->
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4 bg-[#fbfcfd]">
                            @forelse ($divisis as $div)
                            <div class="card p-5 bg-white border border-gray-100 space-y-3.5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-700 font-bold flex items-center justify-center text-xs shrink-0 border border-teal-100">
                                            <i class="fa-solid fa-network-wired"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-sm text-gray-900">{{ $div->nama_divisi }}</h3>
                                            <span class="text-[11px] {{ $div->is_active ? 'text-emerald-600' : 'text-gray-400' }} font-medium">
                                                ● {{ $div->is_active ? 'Aktif Menerima' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button onclick="openEditDivisi('{{ route('admin.divisi.update', $div->id) }}', '{{ addslashes($div->nama_divisi) }}', '{{ addslashes($div->deskripsi ?? '') }}', {{ $div->kuota }}, {{ $div->is_active ? 'true' : 'false' }}, {{ json_encode($div->aktivitas_list) }})"
                                            class="p-1.5 border border-gray-200 rounded-lg hover:bg-gray-100 text-gray-600 transition" title="Edit Divisi & Aktivitas">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        <form action="{{ route('admin.divisi.delete', $div->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi {{ $div->nama_divisi }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 border border-rose-200 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Hapus Divisi">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <p class="text-xs text-gray-500 leading-relaxed">{{ $div->deskripsi ?: 'Tidak ada deskripsi rincian tugas.' }}</p>

                                <!-- Daftar Aktivitas Divisi (Dapat Ditambah/Kurang oleh Admin) -->
                                <div class="pt-2 border-t border-gray-100 space-y-1.5">
                                    <div class="flex items-center justify-between text-[11px] font-bold text-gray-700">
                                        <span class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-list-check text-teal-600"></i>
                                            Aktivitas / Kerjaan ({{ count($div->aktivitas_list) }} Kegiatan):
                                        </span>
                                    </div>
                                    <ol class="list-decimal list-inside text-[11px] text-gray-600 space-y-1 bg-gray-50/70 p-2.5 rounded-lg border border-gray-100 max-h-36 overflow-y-auto">
                                        @forelse($div->aktivitas_list as $act)
                                            <li class="leading-relaxed">{{ $act }}</li>
                                        @empty
                                            <li class="text-gray-400 italic list-none">Belum ada aktivitas ditentukan. Klik edit untuk menambahkan.</li>
                                        @endforelse
                                    </ol>
                                </div>

                                <!-- Progress Kuota (Dihitung per anggota per kepala) -->
                                @php
                                    $terisiCount = $div->terisi_saat_ini;
                                    $kuotaMax = max(1, $div->kuota);
                                    $percentage = min(100, round(($terisiCount / $kuotaMax) * 100));
                                @endphp
                                <div class="pt-2 border-t border-gray-100 space-y-1.5">
                                    <div class="flex justify-between text-xs font-medium">
                                        <span class="text-gray-500">Kuota Terisi</span>
                                        <span class="text-gray-900 font-bold">{{ $terisiCount }} / {{ $div->kuota }} Orang (Sisa {{ $div->sisa_kuota }})</span>
                                    </div>
                                    <div class="progress-track">
                                        <div class="progress-fill {{ $percentage >= 100 ? 'bg-rose-500' : ($percentage >= 70 ? 'bg-amber-500' : 'bg-teal-500') }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-span-2 text-center py-10 text-gray-400">
                                Belum ada divisi terdaftar
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- ================= VIEW 4: MASTER GEDUNG & LOKASI ================= -->
                <div id="main-content-gedung" class="main-content-view hidden space-y-4">
                    <div class="card overflow-hidden">
                        <!-- Toolbar -->
                        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white">
                            <div>
                                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                    <span>Data Gedung & Lokasi Presensi</span>
                                </h2>
                                <p class="text-xs text-gray-500 mt-0.5">Kelola titik koordinat GPS kantor dan radius toleransi presensi</p>
                            </div>

                            <button onclick="showModal('gedungModal')"
                                class="text-xs font-semibold bg-gray-900 text-white hover:bg-black px-3.5 py-1.5 rounded-lg flex items-center gap-1.5 shadow-sm transition">
                                <i class="fa-solid fa-plus text-[10px]"></i> Tambah Gedung
                            </button>
                        </div>

                        <!-- Table Gedung -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left text-gray-600 responsive-table">
                                <thead class="text-[11px] text-gray-400 uppercase bg-[#fafafa] font-semibold border-b border-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3.5">Nama Gedung</th>
                                        <th scope="col" class="px-6 py-3.5">Titik Koordinat (GPS)</th>
                                        <th scope="col" class="px-6 py-3.5">Radius Toleransi</th>
                                        <th scope="col" class="px-6 py-3.5 text-right">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($gedungs as $g)
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td data-label="Nama Gedung" class="px-6 py-4 font-bold text-gray-900 text-sm">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-700 font-bold flex items-center justify-center text-xs shrink-0 border border-yellow-200">
                                                    <i class="fa-solid fa-landmark"></i>
                                                </div>
                                                <span>{{ $g->nama }}</span>
                                            </div>
                                        </td>
                                        <td data-label="Koordinat GPS" class="px-6 py-4 font-mono text-gray-600">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 text-xs font-medium">
                                                <i class="fa-solid fa-location-crosshairs text-blue-500"></i>
                                                {{ $g->latitude }}, {{ $g->longitude }}
                                            </span>
                                        </td>
                                        <td data-label="Radius" class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 font-semibold text-xs border border-emerald-200">
                                                <i class="fa-solid fa-circle-dot text-[9px]"></i>
                                                {{ $g->radius_meter }} Meter
                                            </span>
                                        </td>
                                        <td data-label="Aksi" class="px-6 py-4 text-right actions-cell">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button onclick="openEditGedung('{{ $g->id }}','{{ $g->nama }}','{{ $g->latitude }}','{{ $g->longitude }}','{{ $g->radius_meter }}','{{ route('admin.gedungs.update', $g->id) }}')"
                                                    class="p-1.5 border border-gray-200 rounded-lg hover:bg-gray-100 text-gray-600 transition" title="Edit Gedung">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </button>
                                                <form method="POST" action="{{ route('admin.gedungs.destroy', $g->id) }}" onsubmit="return confirm('Yakin ingin menghapus gedung {{ $g->nama }}?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 border border-rose-200 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Hapus Gedung">
                                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">Belum ada data gedung presensi</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ================= MODALS & POP-UPS (CLEAN KARNIVA MODALS) ================= -->

    <!-- 1. MODAL: JAM TELAT -->
    <div id="jamMasukModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-md bg-white">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Batas Jam Telat</h3>
                        <p class="text-xs text-gray-400">Tentukan batas toleransi presensi masuk</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('jamMasukModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.jam_telat.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'jamMasukModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'jamMasukModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'jamMasukModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Jam Masuk Dinyatakan Telat</label>
                    <p class="text-xs text-gray-400 mb-2">Presensi yang dikirim setelah jam ini otomatis berstatus <span class="text-rose-600 font-semibold">'Telat'</span>.</p>
                    <input type="time" name="jam_telat" value="{{ $jamTelat ?? '08:16' }}" required
                        class="w-full text-sm font-bold tracking-wider px-3.5 py-2.5 border border-gray-200 rounded-lg outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('jamMasukModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-medium shadow-sm transition">
                        Simpan Jam Telat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. MODAL: KATEGORI IZIN -->
    <div id="jenisIzinModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-2xl bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Kategori Jenis Izin</h3>
                        <p class="text-xs text-gray-400">Alasan izin yang dapat dipilih siswa</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('jenisIzinModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
                <!-- Form Tambah -->
                <div class="md:col-span-2 p-4 bg-gray-50/70 rounded-xl border border-gray-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700 mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-plus text-teal-600"></i> Tambah Kategori
                    </h4>
                    <form action="{{ route('admin.jenis_izin.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'jenisIzinModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                            <div class="space-y-1">
                                <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                                @if(isset($activeAdminModal) && $activeAdminModal === 'jenisIzinModal' && session('error'))
                                    <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                                @endif
                                @if(isset($activeAdminModal) && $activeAdminModal === 'jenisIzinModal' && $errors->any())
                                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                        @foreach($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label for="nama_izin" class="block text-xs font-medium text-gray-600 mb-1">Nama Izin</label>
                            <input type="text" name="nama" id="nama_izin" required
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none bg-white focus:border-gray-900"
                                placeholder="Misal: Sakit, Dispen, dsb">
                        </div>
                        <button type="submit"
                            class="w-full py-2 px-3 bg-gray-900 hover:bg-black text-white text-xs font-medium rounded-lg transition shadow-xs">
                            + Tambah Kategori
                        </button>
                    </form>
                </div>

                <!-- List Table -->
                <div class="md:col-span-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Kategori Aktif</h4>
                    <div class="border border-gray-100 rounded-xl overflow-hidden max-h-56 overflow-y-auto">
                        <table class="w-full text-xs text-left text-gray-600">
                            <thead class="text-[11px] text-gray-400 uppercase bg-[#fafafa] font-semibold sticky top-0 border-b border-gray-100">
                                <tr>
                                    <th scope="col" class="px-4 py-2">Nama</th>
                                    <th scope="col" class="px-4 py-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($jenisIzin as $izin)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2.5 font-medium text-gray-800">{{ $izin->nama }}</td>
                                    <td class="px-4 py-2.5 text-right">
                                        <form action="{{ route('admin.jenis_izin.destroy', $izin->id) }}" method="POST" onsubmit="return confirm('Hapus jenis izin {{ $izin->nama }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-gray-400 hover:text-rose-600 transition" title="Hapus">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-6 text-gray-400">Belum ada kategori izin.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. MODAL: TAMBAH USER -->
    <div id="userModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Tambah Akun Baru</h3>
                        <p class="text-xs text-gray-400">Buat akun untuk Siswa, Pegawai, atau Admin</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('userModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'userModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'userModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'userModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="username" class="block text-xs font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" value="{{ old('username') }}" placeholder="Contoh: siswa123" required>
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div>
                    <label for="role" class="block text-xs font-medium text-gray-700 mb-1">Role / Peran</label>
                    <select name="role" id="role" onchange="toggleForm(this.value)" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none bg-white focus:border-gray-900" required>
                        <option value="">-- Pilih Role Pengguna --</option>
                        <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa PKL</option>
                        <option value="pegawai" {{ old('role') == 'pegawai' ? 'selected' : '' }}>Pegawai / Staff</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <!-- Form Tambahan: Siswa -->
                <div id="formSiswa" class="hidden space-y-3 p-3.5 bg-gray-50/70 border border-gray-100 rounded-lg">
                    <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wider">Biodata Siswa PKL</h4>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('role') == 'siswa' ? old('nama_lengkap') : '' }}" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white" placeholder="Nama lengkap siswa" disabled>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Asal Sekolah / Kampus</label>
                        <input type="text" name="asal_pkl" value="{{ old('asal_pkl') }}" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white" placeholder="Contoh: SMKN 1 / Universitas" disabled>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Jurusan / Program Studi</label>
                        <input type="text" name="jurusan" value="{{ old('jurusan') }}" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white" placeholder="Contoh: Teknik Informatika" disabled>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Gedung Presensi</label>
                        <select name="gedung_id" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white" disabled>
                            @foreach ($gedungs as $gedung)
                            <option value="{{ $gedung->id }}" {{ (old('role') == 'siswa' && old('gedung_id') == $gedung->id) ? 'selected' : '' }}>{{ $gedung->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Form Tambahan: Pegawai -->
                <div id="formPegawai" class="hidden space-y-3 p-3.5 bg-gray-50/70 border border-gray-100 rounded-lg">
                    <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Biodata Pegawai / Staff</h4>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('role') == 'pegawai' ? old('nama_lengkap') : '' }}" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white" placeholder="Nama lengkap pegawai" disabled>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Divisi Penugasan Seleksi Magang</label>
                        <select name="divisi_id" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white" required disabled>
                            <option value="" disabled {{ (old('role') == 'pegawai' && old('divisi_id')) ? '' : 'selected' }}>-- Pilih Divisi Penugasan --</option>
                            @foreach ($divisis as $divisi)
                            <option value="{{ $divisi->id }}" {{ (old('role') == 'pegawai' && old('divisi_id') == $divisi->id) ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-0.5">Pegawai hanya dapat menyeleksi dan memantau peserta pada divisi penugasannya.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Gedung Penempatan</label>
                        <select name="gedung_id" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white" disabled>
                            @foreach ($gedungs as $gedung)
                            <option value="{{ $gedung->id }}" {{ (old('role') == 'pegawai' && old('gedung_id') == $gedung->id) ? 'selected' : '' }}>{{ $gedung->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('userModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-medium shadow-sm transition">
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. MODAL: EDIT USER -->
    <div id="userEditModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-gray-100 text-gray-800 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Edit Data Pengguna</h3>
                        <p class="text-xs text-gray-400">Perbarui profil akun pengguna</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('userEditModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="editUserForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'userEditModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'userEditModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'userEditModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="editUserUsername" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" placeholder="Kosongkan jika tidak diubah">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Role / Peran</label>
                    <select name="role" id="editUserRole" onchange="toggleEditForm(this.value)" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none bg-white focus:border-gray-900" required>
                        <option value="siswa">Siswa PKL</option>
                        <option value="pegawai">Pegawai / Staff</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <!-- Form Tambahan: Siswa -->
                <div id="editFormSiswa" class="hidden space-y-3 p-3.5 bg-gray-50/70 border border-gray-100 rounded-lg">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" id="editSiswaNama" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Asal Sekolah / Kampus</label>
                        <input type="text" name="asal_pkl" id="editSiswaAsal" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Jurusan / Program Studi</label>
                        <input type="text" name="jurusan" id="editSiswaJurusan" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai Magang</label>
                            <input type="date" name="tanggal_mulai" id="editSiswaTanggalMulai" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white outline-none focus:border-gray-900">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Selesai Magang</label>
                            <input type="date" name="tanggal_selesai" id="editSiswaTanggalSelesai" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white outline-none focus:border-gray-900">
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400">Ubah tanggal selesai untuk mempercepat atau memperpanjang masa magang siswa ini.</p>
                </div>

                <!-- Form Tambahan: Pegawai -->
                <div id="editFormPegawai" class="hidden space-y-3 p-3.5 bg-gray-50/70 border border-gray-100 rounded-lg">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" id="editPegawaiNama" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Divisi Penugasan Seleksi Magang</label>
                        <select name="divisi_id" id="editPegawaiDivisi" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white" required>
                            <option value="" disabled>-- Pilih Divisi Penugasan --</option>
                            @foreach ($divisis as $divisi)
                            <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-0.5">Pegawai hanya dapat menyeleksi dan memantau peserta pada divisi penugasannya.</p>
                    </div>
                </div>

                <!-- Pilihan Gedung -->
                <div id="editGedungSelection" class="hidden">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Gedung Presensi</label>
                    <select name="gedung_id" id="editUserGedung" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white">
                        @foreach ($gedungs as $gedung)
                        <option value="{{ $gedung->id }}">{{ $gedung->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('userEditModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-medium shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. MODAL: TAMBAH GEDUNG -->
    <div id="gedungModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-lg bg-white">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Tambah Gedung Kantor</h3>
                        <p class="text-xs text-gray-400">Daftarkan koordinat kantor dan radius presensi</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('gedungModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.gedungs.store') }}" class="space-y-4">
                @csrf
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'gedungModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'gedungModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'gedungModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Gedung / Kantor</label>
                    <input type="text" name="nama" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" placeholder="Contoh: Kantor BPS Kota" required>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Latitude</label>
                        <input type="text" name="latitude" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" placeholder="-6.1753924" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Longitude</label>
                        <input type="text" name="longitude" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" placeholder="106.8271528" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Radius Toleransi (Meter)</label>
                    <input type="number" name="radius_meter" value="100" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" required>
                </div>

                <button type="button" onclick="getGPSLocation('gedungModal')"
                    class="w-full py-2 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-location-crosshairs text-blue-500"></i> Ambil Koordinat GPS Saat Ini
                </button>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('gedungModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-medium shadow-sm transition">
                        Simpan Gedung
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 6. MODAL: EDIT GEDUNG -->
    <div id="gedungEditModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-lg bg-white">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Edit Data Gedung</h3>
                        <p class="text-xs text-gray-400">Perbarui koordinat atau radius presensi</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('gedungEditModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="editGedungForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'gedungEditModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'gedungEditModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'gedungEditModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Gedung</label>
                    <input type="text" name="nama" id="editGedungNama" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" required>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="editGedungLat" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="editGedungLong" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Radius Toleransi (Meter)</label>
                    <input type="number" name="radius_meter" id="editGedungRadius" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" required>
                </div>

                <button type="button" onclick="getGPSLocation('gedungEditModal')"
                    class="w-full py-2 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-location-crosshairs text-blue-500"></i> Ambil Koordinat GPS Saat Ini
                </button>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('gedungEditModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-medium shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 7. MODAL: DETAIL PENDAFTARAN -->
    <div id="pendaftaranDetailModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-2xl bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-id-card-clip"></i>
                    </div>
                    <div>
                        <h3 id="detailPendaftaranTitle" class="text-sm font-bold text-gray-900">Rincian Permohonan Magang</h3>
                        <p class="text-xs text-gray-400">Biodata lengkap, CV, dan portofolio peserta</p>
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

    <!-- 8. MODAL: TOLAK PENDAFTARAN -->
    <div id="pendaftaranRejectModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-md bg-white">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Tolak Permohonan Magang</h3>
                        <p id="rejectModalSub" class="text-xs text-gray-400">Berikan catatan alasan kepada calon peserta</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('pendaftaranRejectModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="formRejectPendaftaran" method="POST" class="space-y-4">
                @csrf
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'pendaftaranRejectModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'pendaftaranRejectModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'pendaftaranRejectModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="p-3.5 bg-rose-50/70 border border-rose-200 rounded-xl text-xs text-rose-900 leading-relaxed">
                    Apakah Anda yakin ingin menolak permohonan pendaftaran magang ini?
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

    <!-- 8b. MODAL: TOLAK PENGUNDURAN DIRI -->
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
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'rejectResignationModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'rejectResignationModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'rejectResignationModal' && $errors->any())
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
                        placeholder="Misal: Dokumen bukti tidak valid atau tidak memenuhi syarat..."></textarea>
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

    <!-- Modal Memberhentikan Peserta Magang (Admin) -->
    <div id="modalTerminatePendaftaranAdmin" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="card bg-white w-full max-w-md overflow-hidden flex flex-col shadow-2xl rounded-2xl animate-fade-in">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between bg-[#fafafa]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-sm border border-rose-200">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Memberhentikan Peserta Magang</h3>
                        <p class="text-xs text-gray-400" id="terminatePendaftaranSubAdmin">Tindakan Admin BPS</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalTerminatePendaftaranAdmin')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="terminatePendaftaranFormAdmin" method="POST" action="" class="space-y-4 p-5">
                @csrf
                <div class="p-3 bg-rose-50 rounded-xl border border-rose-200 text-rose-900 text-xs leading-relaxed space-y-1">
                    <strong class="block text-rose-950 font-bold">Peringatan Tindakan:</strong>
                    <p class="text-[11px] text-rose-800">
                        Peserta akan langsung diakhiri masa magangnya hari ini dan status akun dikembalikan ke calon peserta. Alasan pemberhentian akan tercatat resmi pada riwayat peserta tanpa penerbitan sertifikat.
                    </p>
                </div>

                <div class="space-y-1.5 text-xs">
                    <label class="font-bold text-gray-700 block">Alasan Pemberhentian <span class="text-rose-500">*</span></label>
                    <textarea name="alasan_pemberhentian" id="inputTerminateReasonAdmin" rows="3" required placeholder="Tuliskan catatan alasan pemberhentian peserta ini..."
                        class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-gray-900 leading-relaxed"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-gray-100">
                    <button type="button" onclick="closeModal('modalTerminatePendaftaranAdmin')"
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

    <!-- 9. MODAL: AJUKAN REVISI JADWAL MAGANG OLEH ADMIN DENGAN KALENDER KUOTA -->
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
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'pendaftaranRescheduleModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'pendaftaranRescheduleModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'pendaftaranRescheduleModal' && $errors->any())
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

    <!-- 10. MODAL: KONFIRMASI APPROVAL DENGAN CEK KUOTA & OVER-QUOTA -->
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
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'confirmApproveModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'confirmApproveModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'confirmApproveModal' && $errors->any())
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

    <!-- 9. MODAL: TAMBAH DIVISI -->
    <div id="divisiAddModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-lg bg-white">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Tambah Divisi BPS</h3>
                        <p class="text-xs text-gray-400">Atur kapasitas kuota kursi bersamaan</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('divisiAddModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.divisi.store') }}" class="space-y-4">
                @csrf
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'divisiAddModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'divisiAddModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'divisiAddModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Divisi</label>
                    <input type="text" name="nama_divisi" required class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" placeholder="Contoh: Neraca Wilayah & Analisis Statistik">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi Tugas</label>
                    <textarea name="deskripsi" rows="2" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900" placeholder="Uraian singkat bidang pekerjaan..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kapasitas Kuota Kursi (Bersamaan)</label>
                    <input type="number" name="kuota" value="4" min="1" max="50" required class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900">
                </div>
                <!-- Daftar Aktivitas Magang (Per Nomor) -->
                <div class="space-y-2 pt-2 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-xs font-semibold text-gray-800">Daftar Aktivitas & Kerjaan Magang</label>
                            <p class="text-[10px] text-gray-400">Atur per nomor tugas yang dijalankan siswa pada divisi ini</p>
                        </div>
                        <button type="button" onclick="addAktivitasRow('addDivisiAktivitasList')"
                            class="px-2.5 py-1 rounded-md bg-teal-50 hover:bg-teal-100 text-teal-800 text-[11px] font-bold border border-teal-200 transition flex items-center gap-1 shadow-2xs">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Tambah Nomor</span>
                        </button>
                    </div>
                    <div id="addDivisiAktivitasList" class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        <!-- Diisi via JS -->
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="divisiAddActive" value="1" checked class="w-4 h-4 rounded text-gray-900">
                    <label for="divisiAddActive" class="text-xs font-medium text-gray-800">Aktifkan pendaftaran untuk divisi ini</label>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('divisiAddModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-medium shadow-sm transition">
                        Simpan Divisi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 10. MODAL: EDIT DIVISI -->
    <div id="divisiEditModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-lg bg-white">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Edit Divisi & Kuota</h3>
                        <p class="text-xs text-gray-400">Perbarui kuota kapasitas atau deskripsi</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('divisiEditModal')" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="formEditDivisi" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="modal-error-box {{ (isset($activeAdminModal) && $activeAdminModal === 'divisiEditModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activeAdminModal) && $activeAdminModal === 'divisiEditModal' && session('error'))
                            <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activeAdminModal) && $activeAdminModal === 'divisiEditModal' && $errors->any())
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Divisi</label>
                    <input type="text" name="nama_divisi" id="editDivisiNama" required class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi Tugas</label>
                    <textarea name="deskripsi" id="editDivisiDeskripsi" rows="2" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kapasitas Kuota Kursi</label>
                    <input type="number" name="kuota" id="editDivisiKuota" min="1" max="50" required class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900">
                </div>
                <!-- Daftar Aktivitas Magang (Per Nomor) -->
                <div class="space-y-2 pt-2 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-xs font-semibold text-gray-800">Daftar Aktivitas & Kerjaan Magang</label>
                            <p class="text-[10px] text-gray-400">Ubah per nomor atau tambah/kurang nomor aktivitas</p>
                        </div>
                        <button type="button" onclick="addAktivitasRow('editDivisiAktivitasList')"
                            class="px-2.5 py-1 rounded-md bg-teal-50 hover:bg-teal-100 text-teal-800 text-[11px] font-bold border border-teal-200 transition flex items-center gap-1 shadow-2xs">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Tambah Nomor</span>
                        </button>
                    </div>
                    <div id="editDivisiAktivitasList" class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        <!-- Diisi via JS -->
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="editDivisiIsActive" value="1" class="w-4 h-4 rounded text-gray-900">
                    <label for="editDivisiIsActive" class="text-xs font-medium text-gray-800">Aktifkan pendaftaran untuk divisi ini</label>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('divisiEditModal')"
                        class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-medium shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= JAVASCRIPT ================= -->
    <script>
        // Update Active Nav States (Sidebar & Breadcrumb)
        function updateNavState(activeNav) {
            // Update Sidebar Nav Links
            document.querySelectorAll('.sidebar-nav-link').forEach(item => {
                item.classList.remove('active');
            });
            const activeSideItem = document.getElementById(`side-nav-${activeNav}`);
            if (activeSideItem) {
                activeSideItem.classList.add('active');
            }

            // Update Breadcrumb Title
            const breadcrumbTitles = {
                pendaftaran: 'Pendaftaran & Seleksi Magang',
                siswa: 'Master Data Siswa PKL',
                pegawai: 'Master Data Pegawai',
                admin: 'Master Data Administrator',
                divisi: 'Master Divisi & Kuota Magang',
                gedung: 'Data Gedung Kantor BPS'
            };
            const breadcrumbEl = document.getElementById('breadcrumb-active-title');
            if (breadcrumbEl && breadcrumbTitles[activeNav]) {
                breadcrumbEl.textContent = breadcrumbTitles[activeNav];
            }
        }

        function updateTabPillState(activeRole) {
            document.querySelectorAll('.role-tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-gray-900', 'shadow-xs');
                btn.classList.add('text-gray-600');
            });
            const activePill = document.getElementById(`tab-pill-${activeRole}`);
            if (activePill) {
                activePill.classList.add('bg-white', 'text-gray-900', 'shadow-xs');
                activePill.classList.remove('text-gray-600');
            }
        }

        function showMainContent(contentToShow) {
            document.querySelectorAll('.main-content-view').forEach(view => {
                view.classList.add('hidden');
            });
            const viewId = `main-content-${contentToShow}`;
            const viewToShow = document.getElementById(viewId);
            if (viewToShow) {
                viewToShow.classList.remove('hidden');
            }

            updateNavState(contentToShow);
            localStorage.setItem('dashboard_active_view', contentToShow);
            if (contentToShow !== 'user') {
                localStorage.removeItem('dashboard_active_table');
            }
        }

        function showUserTable(roleToShow) {
            showMainContent('user');
            document.querySelectorAll('.user-table').forEach(table => {
                table.classList.add('hidden');
            });

            if (roleToShow === 'semua') {
                document.querySelectorAll('.user-table').forEach(table => {
                    table.classList.remove('hidden');
                });
            } else {
                const tableId = `user-table-${roleToShow}`;
                const tableToShow = document.getElementById(tableId);
                if (tableToShow) {
                    tableToShow.classList.remove('hidden');
                }
            }

            updateNavState(roleToShow);
            updateTabPillState(roleToShow);
            localStorage.setItem('dashboard_active_table', roleToShow);
        }

        // Live Table Search Filter
        function filterTables(keyword) {
            const term = keyword.toLowerCase().trim();
            const rows = document.querySelectorAll('.search-row');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Modal Handlers
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                if (modalId === 'userModal') {
                    const roleSelect = document.getElementById('role');
                    toggleForm(roleSelect ? roleSelect.value : '');
                }
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                const errBox = modal.querySelector('.modal-error-box');
                if (errBox) errBox.classList.add('hidden');
                const form = modal.querySelector('form');
                if (form) {
                    form.reset();
                }
                if (modalId === 'userModal') {
                    toggleForm('');
                }
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

        // Mobile Sidebar Toggle
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

        function toggleForm(role) {
            const siswaForm = document.getElementById('formSiswa');
            const pegawaiForm = document.getElementById('formPegawai');

            [siswaForm, pegawaiForm].forEach(form => {
                if (form) {
                    form.classList.add('hidden');
                    form.querySelectorAll('input, select').forEach(el => el.disabled = true);
                }
            });

            if (role === 'siswa' && siswaForm) {
                siswaForm.classList.remove('hidden');
                siswaForm.querySelectorAll('input, select').forEach(el => el.disabled = false);
            } else if (role === 'pegawai' && pegawaiForm) {
                pegawaiForm.classList.remove('hidden');
                pegawaiForm.querySelectorAll('input, select').forEach(el => el.disabled = false);
            }
        }

        function toggleEditForm(role) {
            const editSiswaForm = document.getElementById('editFormSiswa');
            const editPegawaiForm = document.getElementById('editFormPegawai');
            const gedungSelection = document.getElementById('editGedungSelection');

            [editSiswaForm, editPegawaiForm, gedungSelection].forEach(form => {
                if (form) {
                    form.classList.add('hidden');
                    form.querySelectorAll('input, select').forEach(el => el.disabled = true);
                }
            });

            if (role === 'siswa') {
                editSiswaForm.classList.remove('hidden');
                gedungSelection.classList.remove('hidden');
                editSiswaForm.querySelectorAll('input').forEach(el => el.disabled = false);
                document.getElementById('editSiswaNama').name = 'nama_lengkap';
                document.getElementById('editUserGedung').disabled = false;
            } else if (role === 'pegawai') {
                editPegawaiForm.classList.remove('hidden');
                gedungSelection.classList.remove('hidden');
                const namaPegawaiInput = document.getElementById('editPegawaiNama');
                namaPegawaiInput.disabled = false;
                namaPegawaiInput.name = 'nama_lengkap';
                document.getElementById('editPegawaiDivisi').disabled = false;
                document.getElementById('editUserGedung').disabled = false;
            }
        }

        function openEditUser(actionUrl, username, role, nama_lengkap, asal_pkl, gedung_id, divisi_id = '', tanggal_mulai = '', tanggal_selesai = '', jurusan = '') {
            const modal = document.getElementById('userEditModal');
            modal.querySelector('#editUserForm').action = actionUrl;
            modal.querySelector('#editUserUsername').value = username;
            modal.querySelector('#editUserRole').value = role;
            toggleEditForm(role);

            if (role === 'siswa') {
                document.getElementById('editSiswaNama').value = nama_lengkap;
                document.getElementById('editSiswaAsal').value = asal_pkl;
                const jurusanEl = document.getElementById('editSiswaJurusan');
                if (jurusanEl) jurusanEl.value = jurusan || '';
                const tglMulaiEl = document.getElementById('editSiswaTanggalMulai');
                const tglSelesaiEl = document.getElementById('editSiswaTanggalSelesai');
                if (tglMulaiEl) tglMulaiEl.value = tanggal_mulai || '';
                if (tglSelesaiEl) tglSelesaiEl.value = tanggal_selesai || '';
            } else if (role === 'pegawai') {
                document.getElementById('editPegawaiNama').value = nama_lengkap;
                document.getElementById('editPegawaiDivisi').value = divisi_id || '';
            }

            if (role === 'siswa' || role === 'pegawai') {
                document.getElementById('editUserGedung').value = gedung_id;
            }

            showModal('userEditModal');
        }

        function openEditGedung(id, nama, lat, long, radius, actionUrl) {
            const modal = document.getElementById('gedungEditModal');
            modal.querySelector('#editGedungNama').value = nama;
            modal.querySelector('#editGedungLat').value = lat;
            modal.querySelector('#editGedungLong').value = long;
            modal.querySelector('#editGedungRadius').value = radius;
            modal.querySelector('#editGedungForm').action = actionUrl;
            showModal('gedungEditModal');
        }

        function getGPSLocation(modalId) {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung fitur Geolocation.');
                return;
            }
            const modal = document.getElementById(modalId);
            const btn = modal.querySelector('button[onclick*="getGPSLocation"]');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengambil koordinat GPS...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    modal.querySelector('input[name="latitude"]').value = position.coords.latitude;
                    modal.querySelector('input[name="longitude"]').value = position.coords.longitude;
                    btn.innerHTML = '<i class="fa-solid fa-check text-emerald-600"></i> Koordinat Berhasil Didapatkan!';
                    setTimeout(() => {
                        btn.innerHTML = originalContent;
                        btn.disabled = false;
                    }, 2000);
                },
                function(error) {
                    alert('Gagal mengambil lokasi: ' + error.message);
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }

        // Pendaftaran Client Logic
        const allPendaftaransData = @json($pendaftarans);
        let currentPendaftaranStatus = 'semua';

        function filterPendaftaranTable(status) {
            currentPendaftaranStatus = status;
            document.querySelectorAll('.filter-pendaftaran-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-gray-900', 'shadow-xs');
                btn.classList.add('text-gray-600');
            });
            const activeBtn = document.getElementById(`btn-filter-pendaftaran-${status}`);
            if (activeBtn) {
                activeBtn.classList.add('bg-white', 'text-gray-900', 'shadow-xs');
                activeBtn.classList.remove('text-gray-600');
            }

            applyPendaftaranFilters();
        }

        function applyPendaftaranFilters() {
            const divisiSelect = document.getElementById('filterPendaftaranDivisi');
            const selectedDivisi = divisiSelect ? divisiSelect.value : 'semua';

            document.querySelectorAll('.row-pendaftaran').forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const rowDivisi = row.getAttribute('data-divisi-id');

                const matchStatus = (currentPendaftaranStatus === 'semua' || rowStatus === currentPendaftaranStatus);
                const matchDivisi = (selectedDivisi === 'semua' || rowDivisi === selectedDivisi);

                if (matchStatus && matchDivisi) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        function openDetailPendaftaran(id) {
            const item = allPendaftaransData.find(p => p.id === id);
            if (!item) return;

            const modal = document.getElementById('pendaftaranDetailModal');
            const titleEl = document.getElementById('detailPendaftaranTitle');
            const bodyEl = document.getElementById('detailPendaftaranBody');

            const members = item.anggotas || item.members || [];
            const division = item.divisi || item.division || {};
            const leader = item.ketua || item.leader || {};
            const isKelompok = (item.tipe || item.type) === 'kelompok';
            const groupName = item.nama_kelompok || item.group_name || '';
            const leaderName = members[0]?.nama_lengkap || members[0]?.full_name || leader?.username || 'Peserta';

            titleEl.textContent = isKelompok
                ? `Rincian Pendaftaran: ${groupName || 'Kelompok'}`
                : `Rincian Pendaftaran: ${leaderName}`;

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
                    <div class="p-4 rounded-xl border border-gray-100 bg-[#fbfcfd] space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-gray-900 text-white font-bold text-xs flex items-center justify-center">${idx + 1}</span>
                                <span class="font-bold text-gray-900 text-sm">${memberName}</span>
                                ${idx === 0 && isKelompok ? '<span class="text-[10px] bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded-full border border-blue-200">Pengaju Permohonan</span>' : ''}
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
                                <a href="${cvUrl}" target="_blank" class="px-3 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 text-xs font-medium transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                    <span>Download / Lihat CV</span>
                                </a>
                            ` : '<span class="text-xs text-gray-400">CV tidak tersedia</span>'}

                            ${portoUrl ? `
                                <a href="${portoUrl}" target="_blank" class="px-3 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 text-xs font-medium transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-blue-600 text-[10px]"></i>
                                    <span>Lihat Portofolio</span>
                                </a>
                            ` : ''}
                        </div>
                    </div>
                `;
            });

            // Helper format dd/mm/yyyy
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

            const tglMulai = item.tanggal_mulai || item.start_date;
            const tglSelesai = item.tanggal_selesai || item.end_date;
            const tglMulaiSaran = item.tanggal_mulai_saran || item.suggested_start_date;
            const tglSelesaiSaran = item.tanggal_selesai_saran || item.suggested_end_date;
            const catatanRevisi = item.catatan_revisi_jadwal || item.reschedule_reason || '';
            const catatanAdmin = item.catatan_admin || item.admin_note || '';
            const alasanPenolakanPeserta = item.alasan_penolakan_peserta || item.applicant_rejection_reason || '';
            const alasanUndurDiri = item.alasan_undur_diri || item.resignation_reason || '';
            const buktiUndurDiri = item.bukti_undur_diri || item.resignation_proof || '';
            const tglUndurDiri = item.tgl_undur_diri || item.resignation_requested_at || '';
            const catatanUndurDiriAdmin = item.catatan_undur_diri_admin || item.resignation_admin_note || '';
            const divisiId = item.divisi_id || item.division_id;
            const divisiNama = division.nama_divisi || division.name || '-';
            const kuotaDivisi = division.kuota ?? division.quota ?? 0;
            const totalAnggota = members.length || 1;

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
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-amber-800">
                            <i class="fa-solid fa-clock-rotate-left text-amber-600"></i>
                            <span>Usulan Revisi Jadwal Dikirim ke Peserta</span>
                        </div>
                        <p>Jadwal Baru yang Disarankan: <strong>${formatDateDMY(tglMulaiSaran)} s.d. ${formatDateDMY(tglSelesaiSaran)}</strong></p>
                        <p class="text-amber-800 italic">"Alasan: ${catatanRevisi || '-'}"</p>
                        <span class="inline-block text-[10px] bg-amber-100 text-amber-800 px-2 py-0.5 rounded font-semibold">Menunggu persetujuan / konfirmasi dari calon peserta</span>
                    </div>
                `;
            } else if (item.status === 'resignation_requested') {
                let buktiBtn = '';
                if (buktiUndurDiri) {
                    let buktiUrl = buktiUndurDiri.startsWith('http')
                        ? buktiUndurDiri
                        : (buktiUndurDiri.startsWith('storage/') ? `{{ url('/') }}/${buktiUndurDiri}` : `{{ asset('storage') }}/${buktiUndurDiri}`);
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
                        <p class="text-purple-800">Diajukan pada: <strong>${tglUndurDiri ? formatDateDMY(tglUndurDiri) : '-'}</strong></p>
                        <p class="text-purple-900 bg-white/80 p-2.5 rounded-lg border border-purple-100">
                            <strong>Alasan:</strong> "${alasanUndurDiri || '-'}"
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
                        <p class="text-gray-600">Alasan: "${alasanUndurDiri || '-'}"</p>
                        ${catatanUndurDiriAdmin ? `<p class="text-gray-700 italic">Catatan Admin/Pegawai: "${catatanUndurDiriAdmin}"</p>` : ''}
                    </div>
                `;
            } else if (item.status === 'cancelled') {
                rescheduleNotice = `
                    <div class="p-3 bg-gray-100 border border-gray-200 rounded-xl text-xs text-gray-700 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-gray-800">
                            <i class="fa-solid fa-ban text-gray-500"></i>
                            <span>Pendaftaran Dibatalkan oleh Peserta</span>
                        </div>
                        <p class="text-gray-600">${catatanAdmin || 'Permohonan ini telah dibatalkan oleh calon peserta.'}</p>
                    </div>
                `;
            } else if (alasanPenolakanPeserta) {
                rescheduleNotice = `
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-900 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-rose-700">
                            <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                            <span>Peserta Menolak Usulan Jadwal Baru</span>
                        </div>
                        <p class="text-rose-800">Alasan dari Peserta: <strong>${alasanPenolakanPeserta}</strong></p>
                    </div>
                `;
            } else if (catatanAdmin) {
                rescheduleNotice = `
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-900">
                        <strong class="block mb-0.5 font-bold">Catatan Penolakan Admin:</strong>
                        ${catatanAdmin}
                    </div>
                `;
            }

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

                <div>
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Data Seluruh Peserta (${totalAnggota} Orang)
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
                                class="px-3 py-1.5 border border-rose-200 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs transition flex items-center gap-1">
                                <i class="fa-solid fa-xmark"></i>
                                <span>Tolak</span>
                            </button>
                            <button type="button" onclick="closeModal('pendaftaranDetailModal'); openRescheduleModal(${item.id}, '${titleText.replace(/'/g, "\\'")}', '${formatDateYMD(tglMulai)}', '${formatDateYMD(tglSelesai)}', '${formatDateYMD(tglMulaiSaran)}', '${formatDateYMD(tglSelesaiSaran)}', '${catatanRevisi.replace(/'/g, "\\'")}', ${divisiId}, '${divisiNama.replace(/'/g, "\\'")}', ${totalAnggota})"
                                class="px-3 py-1.5 border border-amber-200 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 font-semibold text-xs transition flex items-center gap-1">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>Revisi Jadwal</span>
                            </button>
                            <button type="button" onclick="closeModal('pendaftaranDetailModal'); confirmApprovePendaftaran(${item.id}, '${titleText.replace(/'/g, "\\'")}', '${formatDateDMY(tglMulai)}', '${formatDateDMY(tglSelesai)}', ${totalAnggota}, ${divisiId}, '${divisiNama.replace(/'/g, "\\'")}', ${kuotaDivisi})"
                                class="px-3 py-1.5 border border-emerald-200 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition flex items-center gap-1">
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
                            <button type="button" onclick="closeModal('pendaftaranDetailModal'); openTerminatePendaftaranAdminModal(${item.id}, '${titleText.replace(/'/g, "\\'")}')"
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

        function openTerminatePendaftaranAdminModal(id, titleText) {
            const form = document.getElementById('terminatePendaftaranFormAdmin');
            const sub = document.getElementById('terminatePendaftaranSubAdmin');
            const input = document.getElementById('inputTerminateReasonAdmin');
            if (form) form.action = `/pendaftaran/${id}/berhentikan`;
            if (sub) sub.textContent = titleText || 'Tindakan Admin BPS';
            if (input) input.value = '';
            showModal('modalTerminatePendaftaranAdmin');
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
                // Convert format jika dd/mm/yyyy
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
            const catatanEl = document.getElementById('rejectCatatan');
            if (catatanEl) catatanEl.value = '';
            showModal('pendaftaranRejectModal');
        }

        // ================= PENGELOLAAN AKTIVITAS DIVISI =================
        function renderAktivitasList(containerId, items) {
            const container = document.getElementById(containerId);
            if (!container) return;
            container.innerHTML = '';
            const list = (items && items.length > 0) ? items : [''];
            list.forEach(val => {
                addAktivitasRow(containerId, val);
            });
        }

        function addAktivitasRow(containerId, val = '') {
            const container = document.getElementById(containerId);
            if (!container) return;
            const count = container.querySelectorAll('.aktivitas-row').length + 1;
            const row = document.createElement('div');
            row.className = 'aktivitas-row flex items-center gap-2';
            const escapedVal = (val || '').replace(/"/g, '&quot;');
            row.innerHTML = `
                <span class="row-number w-5 h-5 rounded-full bg-gray-100 text-gray-700 font-bold text-[10px] flex items-center justify-center shrink-0 border border-gray-200">
                    ${count}
                </span>
                <input type="text" name="aktivitas[]" value="${escapedVal}" required
                    placeholder="Aktivitas / tugas ke-${count}..."
                    class="flex-1 px-3 py-1.5 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-white">
                <button type="button" onclick="removeAktivitasRow(this)"
                    class="w-7 h-7 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition shrink-0" title="Hapus nomor ini">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            `;
            container.appendChild(row);
            reindexAktivitasRows(containerId);
        }

        function removeAktivitasRow(btn) {
            const container = btn.closest('#addDivisiAktivitasList, #editDivisiAktivitasList');
            const row = btn.closest('.aktivitas-row');
            if (container && row) {
                if (container.querySelectorAll('.aktivitas-row').length <= 1) {
                    row.querySelector('input').value = '';
                    return;
                }
                row.remove();
                reindexAktivitasRows(container.id);
            }
        }

        function reindexAktivitasRows(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            container.querySelectorAll('.aktivitas-row').forEach((row, idx) => {
                const numSpan = row.querySelector('.row-number');
                if (numSpan) numSpan.textContent = idx + 1;
                const input = row.querySelector('input');
                if (input && !input.value) {
                    input.placeholder = `Aktivitas / tugas ke-${idx + 1}...`;
                }
            });
        }

        function openAddDivisi() {
            renderAktivitasList('addDivisiAktivitasList', ['']);
            showModal('divisiAddModal');
        }

        function openEditDivisi(actionUrl, nama_divisi, deskripsi, kuota, is_active, activitiesData) {
            const form = document.getElementById('formEditDivisi');
            form.action = actionUrl;
            document.getElementById('editDivisiNama').value = nama_divisi;
            document.getElementById('editDivisiDeskripsi').value = deskripsi || '';
            document.getElementById('editDivisiKuota').value = kuota;
            document.getElementById('editDivisiIsActive').checked = Boolean(is_active);

            let activities = [];
            if (Array.isArray(activitiesData)) {
                activities = activitiesData;
            } else if (typeof activitiesData === 'string') {
                try {
                    activities = JSON.parse(activitiesData);
                } catch(e) {
                    activities = [];
                }
            }
            renderAktivitasList('editDivisiAktivitasList', activities);
            showModal('divisiEditModal');
        }

        document.addEventListener('DOMContentLoaded', function() {
            setupModalForms();

            @if(isset($activeAdminModal) && $activeAdminModal)
                showModal('{{ $activeAdminModal }}');
                @if($activeAdminModal === 'userModal' && old('role'))
                    toggleForm("{{ old('role') }}");
                @endif
            @endif

            const urlParams = new URLSearchParams(window.location.search);
            const viewParam = urlParams.get('view');
            const tableParam = urlParams.get('table');

            if (viewParam) {
                showMainContent(viewParam);
                if (viewParam === 'user' && tableParam) {
                    showUserTable(tableParam);
                }
            } else {
                const savedView = localStorage.getItem('dashboard_active_view');
                const savedTable = localStorage.getItem('dashboard_active_table');

                if (savedView) {
                    showMainContent(savedView);
                    if (savedView === 'user' && savedTable) {
                        showUserTable(savedTable);
                    }
                } else {
                    showMainContent('pendaftaran');
                }
            }
        });
    </script>
</body>
</html>
