<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPS Magang - Log Aktivitas Sistem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
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

        /* Sidebar nav active state */
        .sidebar-nav-link.active {
            background-color: #ffffff;
            color: #111827;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid #f3f4f6;
            font-weight: 600;
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
    </style>
    @include('partials.pwa')
</head>
<body class="bg-white h-screen overflow-hidden">

    <div class="app-container">
        
        <!-- ================= SIDEBAR (IDENTIK DENGAN ADMIN DASHBOARD) ================= -->
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
                            <a href="{{ route('admin.dashboard') }}?view=pendaftaran"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-id-card-clip w-4 text-center"></i> Pendaftaran</span>
                                @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-blue-500 text-white">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.dashboard') }}?view=user&table=siswa"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-user-graduate w-4 text-center"></i> Siswa PKL</span>
                                <span class="text-xs text-gray-400">{{ $siswaCount }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.dashboard') }}?view=user&table=pegawai"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-user-tie w-4 text-center"></i> Pegawai</span>
                                <span class="text-xs text-gray-400">{{ $pegawaiCount }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.dashboard') }}?view=user&table=admin"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-user-shield w-4 text-center"></i> Administrator</span>
                                <span class="text-xs text-gray-400">{{ $adminCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Group: Workspace & Wilayah -->
                <div>
                    <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Workspace & Divisi</h3>
                    <ul class="space-y-1 text-sm font-medium">
                        <li>
                            <a href="{{ route('admin.dashboard') }}?view=divisi"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-sitemap w-4 text-center"></i> Divisi & Kuota</span>
                                <span class="text-xs text-gray-400">{{ $divisiCount }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.dashboard') }}?view=gedung"
                                class="sidebar-nav-link w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-building w-4 text-center"></i> Gedung Kantor</span>
                                <span class="text-xs text-gray-400">{{ $gedungCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Group: Pengaturan Presensi -->
                <div>
                    <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Konfigurasi Presensi</h3>
                    <ul class="space-y-1 text-sm font-medium">
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                                class="w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-regular fa-clock w-4 text-center text-rose-500"></i> Batas Jam Telat</span>
                                <span class="text-[11px] font-mono font-semibold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">{{ $jamTelat ?? '08:16' }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                                class="w-full flex items-center justify-between px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-clipboard-check w-4 text-center text-teal-600"></i> Kategori Izin</span>
                                <span class="text-xs text-gray-400">{{ $jenisIzinCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Group: Audit & Logs -->
                <div>
                    <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Monitoring</h3>
                    <ul class="space-y-1 text-sm font-medium">
                        <li>
                            <a href="{{ route('admin.logs') }}"
                                class="sidebar-nav-link active w-full flex items-center justify-between px-3 py-2 text-gray-900 rounded-lg transition">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-shield-halved w-4 text-center text-blue-600"></i> Log Aktivitas</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ $stats['total'] }}</span>
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
                    <span class="text-gray-800 font-semibold truncate max-w-[200px] sm:max-w-none">Log Aktivitas</span>
                </div>

            </header>


            <!-- Scrollable Content Area -->
            <div class="flex-1 overflow-y-auto custom-scrollbar px-4 sm:px-8 py-5 space-y-6">

                <!-- Debugging & Activity KPI Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
                    <!-- Total Log -->
                    <div class="card p-3.5 bg-white flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Log</p>
                            <p class="text-lg font-bold text-gray-900 mt-0.5">{{ number_format($stats['total'] ?? 0) }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 shrink-0">
                            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                        </div>
                    </div>

                    <!-- Berhasil -->
                    <a href="{{ route('admin.logs', ['status' => 'berhasil']) }}" class="card p-3.5 bg-white hover:border-emerald-200 flex items-center justify-between transition group">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Berhasil</p>
                            <p class="text-lg font-bold text-emerald-700 mt-0.5">{{ number_format($stats['berhasil'] ?? 0) }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                            <i class="fa-solid fa-circle-check text-sm"></i>
                        </div>
                    </a>

                    <!-- Gagal -->
                    <a href="{{ route('admin.logs', ['status' => 'gagal']) }}" class="card p-3.5 bg-white hover:border-rose-200 flex items-center justify-between transition group">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-rose-600">Gagal / Ditolak</p>
                            <p class="text-lg font-bold text-rose-700 mt-0.5">{{ number_format($stats['gagal'] ?? 0) }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                            <i class="fa-solid fa-circle-xmark text-sm"></i>
                        </div>
                    </a>

                    <!-- Salah Isi -->
                    <a href="{{ route('admin.logs', ['status' => 'salah_isi']) }}" class="card p-3.5 bg-white hover:border-amber-200 flex items-center justify-between transition group">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Salah Isi</p>
                            <p class="text-lg font-bold text-amber-700 mt-0.5">{{ number_format($stats['salah_isi'] ?? 0) }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                            <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                        </div>
                    </a>

                    <!-- Hari Ini -->
                    <div class="card p-3.5 bg-white flex items-center justify-between col-span-2 sm:col-span-1">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-500">Hari Ini</p>
                            <p class="text-lg font-bold text-blue-700 mt-0.5">{{ number_format($stats['today'] ?? 0) }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-calendar-day text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Filter Bar (Karniva Card Form) -->
                <div class="card p-5 bg-white space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fa-solid fa-filter text-gray-400"></i>
                            <span>Filter & Debugging Log Aktivitas</span>
                        </div>
                        @if(request()->anyFilled(['q', 'tanggal', 'status', 'role', 'user_id', 'per_page']))
                        <a href="{{ route('admin.logs') }}" class="text-xs text-rose-600 hover:text-rose-700 font-medium inline-flex items-center gap-1">
                            <i class="fa-solid fa-rotate-left"></i> Reset Filter
                        </a>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('admin.logs') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5 items-end">
                        
                        <!-- Search Keyword -->
                        <div class="lg:col-span-3">
                            <label for="q" class="block text-xs font-medium text-gray-600 mb-1">Cari Keyword</label>
                            <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Aksi, user, keterangan, IP..."
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg outline-none bg-white focus:border-gray-400 transition">
                        </div>

                        <!-- Date Picker -->
                        <div class="lg:col-span-2">
                            <label for="tanggal" class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ request('tanggal') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg outline-none bg-white focus:border-gray-400 transition text-gray-700">
                        </div>

                        <!-- Status Debugging Selector -->
                        <div class="lg:col-span-2">
                            <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status Debugging</label>
                            <select id="status" name="status"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg outline-none bg-white focus:border-gray-400 transition text-gray-700">
                                <option value="">Semua Status</option>
                                <option value="berhasil" {{ request('status') == 'berhasil' ? 'selected' : '' }}>✓ Berhasil</option>
                                <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>✗ Gagal / Error</option>
                                <option value="salah_isi" {{ request('status') == 'salah_isi' ? 'selected' : '' }}>⚠ Salah Isi / Validasi</option>
                            </select>
                        </div>

                        <!-- Role Selector -->
                        <div class="lg:col-span-2">
                            <label for="role" class="block text-xs font-medium text-gray-600 mb-1">Role Pengguna</label>
                            <select id="role" name="role"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg outline-none bg-white focus:border-gray-400 transition text-gray-700">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="pegawai" {{ request('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>
                        </div>

                        <!-- Specific User -->
                        <div class="lg:col-span-2">
                            <label for="user_id" class="block text-xs font-medium text-gray-600 mb-1">Akun User</label>
                            <select id="user_id" name="user_id"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg outline-none bg-white focus:border-gray-400 transition text-gray-700">
                                <option value="">Semua User</option>
                                @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->username }} ({{ ucfirst($u->role) }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="lg:col-span-1 flex gap-1.5">
                            <button type="submit"
                                class="flex-1 text-xs font-medium bg-gray-900 text-white hover:bg-black py-2 px-2.5 rounded-lg flex items-center justify-center gap-1 shadow-sm transition" title="Terapkan Filter">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </button>
                            <a href="{{ route('admin.logs') }}" title="Reset Filter"
                                class="w-8 h-8.5 border border-gray-200 hover:bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center transition">
                                <i class="fa-solid fa-rotate-left text-xs"></i>
                            </a>
                        </div>

                    </form>
                </div>

                <!-- Main Table Card (Karniva Reference Style) -->
                <div class="card overflow-hidden bg-white">
                    
                    <!-- Table Header Bar -->
                    <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-blue-600 text-sm"></i>
                                <span>Daftar Riwayat Audit Aktivitas</span>
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ number_format($logs->total()) }} entri tercatat
                            </p>
                        </div>

                        <!-- Per Page Selector -->
                        <form method="GET" action="{{ route('admin.logs') }}" class="flex items-center gap-2">
                            @foreach(request()->except('per_page') as $key => $val)
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endforeach
                            <label for="per_page" class="text-xs text-gray-400 font-medium">Baris:</label>
                            <select id="per_page" name="per_page" onchange="this.form.submit()"
                                class="text-xs border border-gray-200 rounded-lg px-2.5 py-1 bg-white focus:border-gray-400 text-gray-700 outline-none">
                                <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-gray-100 bg-[#fafafa] text-gray-400 text-[10px] uppercase tracking-wider font-semibold">
                                    <th class="py-3 px-5">Waktu Kejadian</th>
                                    <th class="py-3 px-5">Pengguna / Role</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                    <th class="py-3 px-5">Aksi / Event</th>
                                    <th class="py-3 px-5">Keterangan & Kasus</th>
                                    <th class="py-3 px-5">IP Address (IPv4)</th>
                                    <th class="py-3 px-5">Perangkat & OS</th>
                                    <th class="py-3 px-4 text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 font-medium">
                                @forelse($logs as $log)
                                @php
                                    // Parse User-Agent
                                    $ua = $log->user_agent ?? '';
                                    $os = 'Unknown';
                                    $osIcon = 'fa-solid fa-desktop text-gray-400';
                                    if (stripos($ua, 'Windows') !== false) { $os = 'Windows'; $osIcon = 'fa-brands fa-windows text-blue-500'; }
                                    elseif (stripos($ua, 'Android') !== false) { $os = 'Android'; $osIcon = 'fa-brands fa-android text-emerald-500'; }
                                    elseif (stripos($ua, 'iPhone') !== false || stripos($ua, 'iPad') !== false) { $os = 'iOS'; $osIcon = 'fa-brands fa-apple text-gray-700'; }
                                    elseif (stripos($ua, 'Macintosh') !== false || stripos($ua, 'Mac OS') !== false) { $os = 'macOS'; $osIcon = 'fa-brands fa-apple text-gray-700'; }
                                    elseif (stripos($ua, 'Linux') !== false) { $os = 'Linux'; $osIcon = 'fa-brands fa-linux text-amber-600'; }

                                    $browser = 'Browser';
                                    $browserIcon = 'fa-solid fa-globe text-gray-400';
                                    if (stripos($ua, 'Edg') !== false) { $browser = 'Edge'; $browserIcon = 'fa-brands fa-edge text-blue-600'; }
                                    elseif (stripos($ua, 'Chrome') !== false) { $browser = 'Chrome'; $browserIcon = 'fa-brands fa-chrome text-amber-500'; }
                                    elseif (stripos($ua, 'Firefox') !== false) { $browser = 'Firefox'; $browserIcon = 'fa-brands fa-firefox-browser text-orange-500'; }
                                    elseif (stripos($ua, 'Safari') !== false) { $browser = 'Safari'; $browserIcon = 'fa-brands fa-safari text-sky-500'; }
                                    elseif (stripos($ua, 'Postman') !== false) { $browser = 'Postman'; $browserIcon = 'fa-solid fa-paper-plane text-orange-600'; }

                                    // Role Styling
                                    $roleColors = [
                                        'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'pegawai' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'siswa' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'guru' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    ];
                                    $roleClass = $roleColors[strtolower($log->role ?? '')] ?? 'bg-gray-100 text-gray-700 border-gray-200';

                                    // Event Category Badges
                                    $aksiLower = strtolower($log->aksi ?? '');
                                    $aksiBadge = 'bg-gray-50 text-gray-700 border-gray-200';
                                    $aksiIcon = 'fa-solid fa-circle-info text-gray-400';
                                    
                                    if (str_contains($aksiLower, 'absen masuk') || str_contains($aksiLower, 'terima') || str_contains($aksiLower, 'approve')) {
                                        $aksiBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        $aksiIcon = 'fa-solid fa-circle-check text-emerald-600';
                                    } elseif (str_contains($aksiLower, 'absen keluar') || str_contains($aksiLower, 'logout')) {
                                        $aksiBadge = 'bg-gray-100 text-gray-700 border-gray-200';
                                        $aksiIcon = 'fa-solid fa-right-from-bracket text-gray-500';
                                    } elseif (str_contains($aksiLower, 'login') || str_contains($aksiLower, 'registrasi')) {
                                        $aksiBadge = 'bg-blue-50 text-blue-700 border-blue-200';
                                        $aksiIcon = 'fa-solid fa-right-to-bracket text-blue-600';
                                    } elseif (str_contains($aksiLower, 'tolak') || str_contains($aksiLower, 'reject') || str_contains($aksiLower, 'batal') || str_contains($aksiLower, 'hapus') || str_contains($aksiLower, 'gagal')) {
                                        $aksiBadge = 'bg-rose-50 text-rose-700 border-rose-200';
                                        $aksiIcon = 'fa-solid fa-circle-xmark text-rose-600';
                                    } elseif (str_contains($aksiLower, 'revisi')) {
                                        $aksiBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                                        $aksiIcon = 'fa-solid fa-clock-rotate-left text-amber-600';
                                    } elseif (str_contains($aksiLower, 'tambah') || str_contains($aksiLower, 'update') || str_contains($aksiLower, 'pengajuan')) {
                                        $aksiBadge = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                        $aksiIcon = 'fa-solid fa-pen-to-square text-indigo-600';
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition group">
                                    
                                    <!-- Waktu -->
                                    <td class="py-3 px-5 whitespace-nowrap">
                                        <p class="font-mono text-gray-900 font-semibold text-[11px]">
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}
                                        </p>
                                        <p class="font-mono text-gray-400 text-[10px]">
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB
                                        </p>
                                    </td>

                                    <!-- Pengguna / Role -->
                                    <td class="py-3 px-5 whitespace-nowrap">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-gray-900 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                                {{ strtoupper(substr($log->user ?? 'A', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 leading-tight">
                                                    {{ $log->user ?? 'Anonim' }}
                                                </p>
                                                @if($log->role)
                                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider border {{ $roleClass }} mt-0.5">
                                                    {{ $log->role }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Status Debug -->
                                    <td class="py-3 px-4 whitespace-nowrap text-center">
                                        @if($log->status === 'berhasil')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i> Berhasil
                                            </span>
                                        @elseif($log->status === 'salah_isi')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-[10px]"></i> Salah Isi
                                            </span>
                                        @elseif($log->status === 'gagal')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="fa-solid fa-circle-xmark text-rose-600 text-[10px]"></i> Gagal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                                {{ ucfirst($log->status ?? 'Berhasil') }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi / Event -->
                                    <td class="py-3 px-5 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium border {{ $aksiBadge }}">
                                            <i class="{{ $aksiIcon }} text-[11px]"></i>
                                            <span>{{ $log->aksi }}</span>
                                        </span>
                                    </td>

                                    <!-- Keterangan -->
                                    <td class="py-3 px-5 text-gray-600 max-w-sm">
                                        <p class="line-clamp-2 text-xs leading-relaxed" title="{{ $log->keterangan }}">
                                            {{ $log->keterangan ?? '-' }}
                                        </p>
                                    </td>

                                    <!-- IP Address (IPv4) -->
                                    <td class="py-3 px-5 whitespace-nowrap font-mono text-[11px]">
                                        <button type="button"
                                            onclick="navigator.clipboard.writeText('{{ $log->ip_address }}'); showToast('IP tersalin: {{ $log->ip_address }}')"
                                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 transition"
                                            title="Klik untuk menyalin IP">
                                            <i class="fa-solid fa-network-wired text-[10px] text-gray-400"></i>
                                            <span class="font-semibold">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                                            <span class="text-[9px] font-sans font-bold px-1 rounded bg-gray-200 text-gray-600">v4</span>
                                        </button>
                                    </td>

                                    <!-- Perangkat & OS -->
                                    <td class="py-3 px-5 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5 text-[11px] text-gray-600" title="{{ $log->user_agent }}">
                                            <i class="{{ $osIcon }} text-xs"></i>
                                            <span>{{ $os }}</span>
                                            <span class="text-gray-300">•</span>
                                            <i class="{{ $browserIcon }} text-xs"></i>
                                            <span>{{ $browser }}</span>
                                        </div>
                                    </td>

                                    <!-- Detail Action -->
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <button type="button"
                                            onclick="openLogDetail({{ json_encode([
                                                'id' => $log->id,
                                                'waktu' => \Carbon\Carbon::parse($log->created_at)->format('d F Y, H:i:s \W\I\B'),
                                                'user' => $log->user ?? 'Anonim',
                                                'role' => strtoupper($log->role ?? '-'),
                                                'status' => $log->status ?? 'berhasil',
                                                'aksi' => $log->aksi,
                                                'keterangan' => $log->keterangan ?? '-',
                                                'ip' => $log->ip_address,
                                                'os' => $os,
                                                'browser' => $browser,
                                                'user_agent' => $log->user_agent ?? '-',
                                            ]) }})"
                                            class="w-7 h-7 rounded-lg text-gray-400 hover:text-black hover:bg-gray-100 inline-flex items-center justify-center transition"
                                            title="Lihat Detail Log">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </button>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-16 text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 text-lg">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                            <p class="font-semibold text-sm text-gray-700">Tidak ada log aktivitas ditemukan</p>
                                            <p class="text-xs text-gray-400">Coba ubah filter pencarian untuk melihat data aktivitas lainnya.</p>
                                            <a href="{{ route('admin.logs') }}"
                                                class="mt-2 text-xs font-medium text-gray-900 border border-gray-200 bg-white hover:bg-gray-100 px-3 py-1.5 rounded-lg transition">
                                                Reset Filter
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($logs->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-[#fafafa]">
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
                    @endif

                </div>

            </div>

        </main>

    </div>

    <!-- Modal Detail Log (Karniva Reference Style) -->
    <div id="logDetailModal" class="modal fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs hidden">
        <div class="modal-content-box bg-white rounded-2xl border border-gray-100 shadow-xl max-w-lg w-full overflow-hidden">
            
            <!-- Modal Header -->
            <div class="h-14 px-6 border-b border-gray-100 flex items-center justify-between bg-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-gray-900 text-white flex items-center justify-center text-xs font-bold">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Detail Audit Log</h3>
                        <p class="text-[10px] text-gray-400" id="modalLogId">ID Log #</p>
                    </div>
                </div>
                <button type="button" onclick="closeLogDetail()" class="text-gray-400 hover:text-black w-7 h-7 rounded-lg hover:bg-gray-100 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4 text-xs">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Waktu Kejadian</p>
                        <p class="font-semibold text-gray-800 font-mono" id="modalWaktu">-</p>
                    </div>
                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Pelaku / Role</p>
                        <p class="font-bold text-gray-900" id="modalUser">-</p>
                    </div>
                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Status Debug</p>
                        <div id="modalStatusBadge" class="mt-0.5">-</div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Aksi / Event</p>
                    <p class="font-semibold text-blue-600" id="modalAksi">-</p>
                </div>

                <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Keterangan / Detail Debugging</p>
                    <p class="text-gray-700 leading-relaxed break-words whitespace-pre-wrap font-mono text-[11px]" id="modalKeterangan">-</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">IP Address (IPv4)</p>
                            <span class="text-[9px] font-bold px-1 rounded bg-gray-200 text-gray-600">v4</span>
                        </div>
                        <p class="font-mono font-bold text-gray-800" id="modalIp">-</p>
                    </div>
                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">OS / Browser</p>
                        <p class="font-semibold text-gray-800" id="modalDevice">-</p>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">User Agent Lengkap</p>
                    <p class="font-mono text-[10px] text-gray-500 break-all leading-normal" id="modalUserAgent">-</p>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-gray-100 bg-[#fafafa] flex justify-end">
                <button type="button" onclick="closeLogDetail()"
                    class="px-4 py-1.5 bg-gray-900 hover:bg-black text-white rounded-lg text-xs font-medium transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-12 opacity-0 transition-all duration-200 pointer-events-none">
        <div class="bg-gray-900 text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-400"></i>
            <span id="toastMsg">Tersalin ke clipboard</span>
        </div>
    </div>

    <script>
        function toggleSidebarMobile() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function openLogDetail(data) {
            document.getElementById('modalLogId').innerText = 'ID Log #' + data.id;
            document.getElementById('modalWaktu').innerText = data.waktu;
            document.getElementById('modalUser').innerText = data.user + ' (' + data.role + ')';
            document.getElementById('modalAksi').innerText = data.aksi;
            document.getElementById('modalKeterangan').innerText = data.keterangan;
            document.getElementById('modalIp').innerText = data.ip;
            document.getElementById('modalDevice').innerText = data.os + ' • ' + data.browser;
            document.getElementById('modalUserAgent').innerText = data.user_agent;

            const statusBadge = document.getElementById('modalStatusBadge');
            if (data.status === 'berhasil') {
                statusBadge.innerHTML = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"><i class="fa-solid fa-circle-check text-emerald-600"></i> Berhasil</span>';
            } else if (data.status === 'salah_isi') {
                statusBadge.innerHTML = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200"><i class="fa-solid fa-triangle-exclamation text-amber-600"></i> Salah Isi</span>';
            } else if (data.status === 'gagal') {
                statusBadge.innerHTML = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200"><i class="fa-solid fa-circle-xmark text-rose-600"></i> Gagal / Error</span>';
            } else {
                statusBadge.innerHTML = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 border border-gray-200">' + (data.status || 'Info') + '</span>';
            }

            const modal = document.getElementById('logDetailModal');
            modal.classList.remove('hidden');
        }

        function closeLogDetail() {
            const modal = document.getElementById('logDetailModal');
            modal.classList.add('hidden');
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLogDetail();
        });

        // Close when clicking modal backdrop
        document.getElementById('logDetailModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeLogDetail();
        });

        function showToast(msg) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMsg');
            toastMsg.innerText = msg;
            toast.classList.remove('translate-y-12', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-12', 'opacity-0');
            }, 2500);
        }
    </script>

</body>
</html>
