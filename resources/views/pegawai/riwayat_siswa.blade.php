<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Presensi - {{ $siswa->nama_lengkap }} - BPS Magang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        
        .card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.03);
        }

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

        @media screen and (max-width: 768px) {
            .responsive-table thead {
                display: none;
            }
            .responsive-table tr {
                display: block;
                border: 1px solid #e2e8f0;
                margin-bottom: 0.875rem;
                background-color: #fff;
                border-radius: 0.875rem;
                padding: 0.875rem;
                box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            }
            .responsive-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 0.75rem;
                padding: 0.5rem 0;
                border-bottom: 1px dashed #f1f5f9;
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
                color: #94a3b8;
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
        }
    </style>
    @include('partials.pwa')
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- Top Header Navigation -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <!-- Left: Back Button & Breadcrumbs -->
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('pegawai.absensi.rekap', ['tab' => 'siswa']) }}"
                    class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 text-slate-700 font-semibold text-xs transition flex items-center gap-2 shadow-2xs shrink-0"
                    title="Kembali ke Daftar Siswa Magang">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                
                <div class="h-4 w-px bg-slate-200 hidden sm:block shrink-0"></div>

                <div class="flex items-center gap-2 text-xs sm:text-sm font-medium min-w-0 truncate">
                    <span class="text-slate-400 hidden sm:inline shrink-0">Portal Pegawai</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 hidden sm:inline shrink-0"></i>
                    <span class="text-slate-400 hidden md:inline shrink-0">Daftar Siswa</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 hidden md:inline shrink-0"></i>
                    <span class="font-bold text-slate-900 truncate">Riwayat Presensi: {{ $siswa->nama_lengkap }}</span>
                </div>
            </div>

            <!-- Right: Pegawai Identity & Logout -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="hidden sm:flex items-center gap-2 text-right">
                    <div>
                        <p class="text-xs font-bold text-slate-900 leading-tight">{{ $pegawai?->nama_lengkap ?? Auth::user()->username }}</p>
                        <p class="text-[10px] text-slate-500">Pegawai &bull; {{ $siswa->divisi?->nama_divisi ?? 'Akses Global' }}</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold flex items-center justify-center text-xs">
                        {{ strtoupper(substr($pegawai?->nama_lengkap ?? Auth::user()->username, 0, 1)) }}
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                    @csrf
                    <button type="submit" title="Logout"
                        class="w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        @php
            $profile = $siswa->user?->pesertaProfile;
            $cvUrl = $profile?->cv_path ? (str_starts_with($profile->cv_path, 'http') ? $profile->cv_path : asset('storage/' . $profile->cv_path)) : null;
            $portoUrl = $profile?->portofolio_path ? (str_starts_with($profile->portofolio_path, 'http') ? $profile->portofolio_path : asset('storage/' . $profile->portofolio_path)) : ($profile?->portofolio_url ?? null);
        @endphp

        <!-- 1. Header Profile Peserta Magang -->
        <div class="card p-5 sm:p-6 bg-white border border-slate-200/90 shadow-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                <!-- Info Siswa -->
                <div class="flex items-start sm:items-center gap-4">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-700 text-white font-bold flex items-center justify-center text-xl sm:text-2xl shadow-sm shrink-0 border border-slate-700">
                        {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-lg sm:text-xl font-bold text-slate-900 leading-tight">
                                {{ $siswa->nama_lengkap }}
                            </h1>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-800 border border-teal-200 flex items-center gap-1">
                                <i class="fa-solid fa-sitemap text-[9px]"></i>
                                {{ $siswa->divisi?->nama_divisi ?? 'Belum Ditentukan' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                            <span><i class="fa-solid fa-at text-slate-400 mr-1"></i>{{ $siswa->user->username ?? '-' }}</span>
                            <span>&bull;</span>
                            <span><i class="fa-regular fa-envelope text-slate-400 mr-1"></i>{{ $siswa->user->email ?? '-' }}</span>
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-graduation-cap text-slate-400 mr-1"></i>{{ $profile?->asal_univ ?? ($siswa->asal_pkl ?? '-') }}</span>
                        </p>
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            Jurusan: <strong class="text-slate-600">{{ $profile?->jurusan ?? '-' }}</strong> &bull; No HP: <strong class="text-slate-600 font-mono">{{ $profile?->no_hp ?? '-' }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Periode & Dokumen -->
                <div class="flex flex-col sm:flex-row md:flex-col items-start sm:items-center md:items-end justify-between gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                    <div class="text-left md:text-right">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Masa Periode Magang</span>
                        <div class="font-bold text-slate-900 text-xs sm:text-sm flex items-center md:justify-end gap-1.5 mt-0.5">
                            <i class="fa-regular fa-calendar-days text-teal-600 text-xs"></i>
                            <span>{{ $siswa->tanggal_mulai ? \Carbon\Carbon::parse($siswa->tanggal_mulai)->format('d/m/Y') : '-' }}</span>
                            <span class="text-slate-300">&mdash;</span>
                            <span>{{ $siswa->tanggal_selesai ? \Carbon\Carbon::parse($siswa->tanggal_selesai)->format('d/m/Y') : '-' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap md:justify-end">
                        <button type="button" onclick="openModalUbahPeriode()" 
                            class="px-3 py-1.5 rounded-lg border border-teal-300 bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-semibold flex items-center gap-1.5 transition shadow-2xs">
                            <i class="fa-solid fa-calendar-days text-teal-600 text-xs"></i>
                            <span>Ubah Tanggal Selesai</span>
                        </button>
                        @if($cvUrl)
                        <a href="{{ $cvUrl }}" target="_blank"
                            class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition shadow-2xs">
                            <i class="fa-solid fa-file-pdf text-rose-500"></i>
                            <span>Lihat CV</span>
                        </a>
                        @endif
                        @if($portoUrl)
                        <a href="{{ $portoUrl }}" target="_blank"
                            class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition shadow-2xs">
                            <i class="fa-solid fa-arrow-up-right-from-square text-blue-500 text-[10px]"></i>
                            <span>Portofolio</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Metric Cards Ringkasan Siswa Ini -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <div class="card p-4 border-slate-200/80">
                <span class="text-xs text-slate-400 block mb-1">Total Kehadiran</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-xl sm:text-2xl font-bold text-emerald-600">{{ $totalHadir }}</span>
                    <span class="text-xs text-slate-500 font-medium">Hari</span>
                </div>
                <span class="text-[10px] text-slate-400 mt-1 block">Presensi tercatat</span>
            </div>

            <div class="card p-4 border-slate-200/80">
                <span class="text-xs text-slate-400 block mb-1">Tepat Waktu</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-xl sm:text-2xl font-bold text-slate-900">{{ $totalTepatWaktu }}</span>
                    <span class="text-xs text-slate-500 font-medium">Hari</span>
                </div>
                <span class="text-[10px] text-emerald-600 font-medium mt-1 block">&le; {{ substr($jamTelat, 0, 5) }} WIB</span>
            </div>

            <div class="card p-4 border-slate-200/80">
                <span class="text-xs text-slate-400 block mb-1">Terlambat</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-xl sm:text-2xl font-bold text-rose-600">{{ $totalTerlambat }}</span>
                    <span class="text-xs text-slate-500 font-medium">Hari</span>
                </div>
                <span class="text-[10px] text-rose-500 font-medium mt-1 block">&gt; {{ substr($jamTelat, 0, 5) }} WIB</span>
            </div>

            <div class="card p-4 border-slate-200/80">
                <span class="text-xs text-slate-400 block mb-1">Izin Resmi</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-xl sm:text-2xl font-bold text-amber-600">{{ $totalIzin }}</span>
                    <span class="text-xs text-slate-500 font-medium">Hari</span>
                </div>
                <span class="text-[10px] text-amber-600 font-medium mt-1 block">Pengajuan surat/bukti</span>
            </div>
        </div>

        <!-- 3. Form Filter Rentang Tanggal (dd/mm/yyyy) -->
        <div class="card p-4 sm:p-5 bg-white border border-slate-200/80 shadow-xs">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-filter text-teal-600 text-[11px]"></i>
                        <span>Filter Rentang Tanggal Riwayat</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Default menampilkan dari awal masuk (<strong>{{ \Carbon\Carbon::parse($defaultDari)->format('d/m/Y') }}</strong>) hingga masa magang selesai (<strong>{{ \Carbon\Carbon::parse($defaultKe)->format('d/m/Y') }}</strong>).
                    </p>
                </div>

                <!-- Form Filter -->
                <form action="{{ route('pegawai.siswa.riwayat', $siswa->id) }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="flex-1 sm:flex-initial">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-0.5">Dari Tanggal</label>
                            <input type="date" name="dari" id="filterRiwayatDari" value="{{ $dari }}"
                                class="px-3 py-1.5 text-xs border border-slate-200 rounded-lg outline-none focus:border-slate-900 bg-white font-medium text-slate-800 shadow-2xs">
                        </div>

                        <span class="text-slate-400 text-xs self-end pb-2">&mdash;</span>

                        <div class="flex-1 sm:flex-initial">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-0.5">Sampai Tanggal (Ke)</label>
                            <input type="date" name="ke" id="filterRiwayatKe" value="{{ $ke }}"
                                class="px-3 py-1.5 text-xs border border-slate-200 rounded-lg outline-none focus:border-slate-900 bg-white font-medium text-slate-800 shadow-2xs">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end w-full sm:w-auto pt-1 sm:pt-0">
                        <button type="submit"
                            class="flex-1 sm:flex-initial px-3.5 py-2 rounded-lg bg-slate-900 hover:bg-black text-white text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-2xs">
                            <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                            <span>Terapkan</span>
                        </button>

                        <button type="button" onclick="resetToMagangPeriod()"
                            class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition" title="Kembalikan ke Periode Magang Default">
                            <span>Masa Magang</span>
                        </button>

                        @if(request('dari') || request('ke'))
                        <a href="{{ route('pegawai.siswa.riwayat', $siswa->id) }}"
                            class="px-2.5 py-2 rounded-lg text-rose-600 hover:bg-rose-50 text-xs font-medium transition" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- 4. Tabel Riwayat Presensi Lengkap -->
        <div class="card overflow-hidden bg-white border border-slate-200/90 shadow-xs">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">
                        Catatan Kehadiran Siswa
                    </h3>
                    <p class="text-xs text-slate-400">
                        Periode: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }}</strong> s/d <strong class="text-slate-700">{{ \Carbon\Carbon::parse($ke)->format('d/m/Y') }}</strong> &bull; Total: <strong class="text-slate-900">{{ $absensis->count() }}</strong> catatan
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-600 responsive-table">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50 font-semibold border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 w-12 text-center">No</th>
                            <th scope="col" class="px-6 py-3.5">Hari & Tanggal</th>
                            <th scope="col" class="px-6 py-3.5">Jam Masuk</th>
                            <th scope="col" class="px-6 py-3.5">Jam Pulang</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Durasi Kerja</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($absensis as $index => $absen)
                        @php
                            $isIzin = (bool)$absen->izin;
                            $isTerlambat = ($absen->status_ketepatan === 'Terlambat');
                            $tglCarbon = \Carbon\Carbon::parse($absen->tanggal);

                            $absenData = [
                                'id' => $absen->id,
                                'nama' => $siswa->nama_lengkap,
                                'asal' => $profile?->asal_univ ?? ($siswa->asal_pkl ?? '-'),
                                'divisi' => $siswa->divisi?->nama_divisi ?? '-',
                                'tanggal' => $tglCarbon->translatedFormat('l, d F Y'),
                                'jam_masuk' => $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') : null,
                                'jam_pulang' => $absen->jam_pulang ? \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') : null,
                                'durasi_kerja' => $absen->durasi_kerja ?? '-',
                                'status_ketepatan' => $absen->status_ketepatan ?? 'Tepat Waktu',
                                'status_kerja' => $absen->status_kerja ?? 'WFO',
                                'lokasi_lat' => $absen->lokasi_lat ?? $absen->check_in_latitude ?? $absen->lokasi_masuk_lat,
                                'lokasi_long' => $absen->lokasi_long ?? $absen->check_in_longitude ?? $absen->lokasi_masuk_long,
                                'foto_masuk_url' => $absen->foto_masuk_url ?? null,
                                'foto_keluar_url' => $absen->foto_keluar_url ?? null,
                                'is_izin' => $isIzin,
                                'is_terlambat' => $isTerlambat,
                                'jenis_izin' => $absen->jenisIzin->nama ?? null,
                                'keterangan_izin' => $absen->keterangan_izin ?? null,
                                'file_izin_url' => $absen->file_izin ? asset('storage/' . $absen->file_izin) : null,
                            ];

                            $b64Absen = base64_encode(json_encode($absenData, JSON_UNESCAPED_UNICODE));
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td data-label="No" class="px-6 py-4 text-center font-mono text-slate-400">
                                {{ $index + 1 }}
                            </td>

                            <td data-label="Tanggal" class="px-6 py-4">
                                <div class="font-bold text-slate-900">
                                    {{ $tglCarbon->translatedFormat('l, d F Y') }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono">
                                    {{ $tglCarbon->format('d/m/Y') }}
                                </div>
                            </td>

                            <td data-label="Masuk" class="px-6 py-4 font-mono font-medium text-slate-800">
                                @if($absen->jam_masuk)
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-regular fa-clock text-emerald-600 text-[10px]"></i>
                                        <span>{{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') }} WIB</span>
                                    </div>
                                @else
                                    <span class="text-slate-300">--:--</span>
                                @endif
                            </td>

                            <td data-label="Pulang" class="px-6 py-4 font-mono font-medium text-slate-800">
                                @if($absen->jam_pulang)
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-regular fa-clock text-blue-600 text-[10px]"></i>
                                        <span>{{ \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') }} WIB</span>
                                    </div>
                                @else
                                    <span class="text-slate-300">--:--</span>
                                @endif
                            </td>

                            <td data-label="Durasi" class="px-6 py-4 text-center">
                                @if($absen->durasi_kerja && $absen->durasi_kerja !== '-')
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono text-[11px] font-semibold">
                                        {{ $absen->durasi_kerja }}
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>

                            <td data-label="Status" class="px-6 py-4 text-center">
                                @if($isIzin)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-file-signature text-amber-600 text-[10px]"></i>
                                        <span>Izin: {{ $absen->jenisIzin->nama ?? 'Resmi' }}</span>
                                    </span>
                                @elseif($isTerlambat)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                        <i class="fa-solid fa-clock-rotate-left text-rose-600 text-[10px]"></i>
                                        <span>Terlambat</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <i class="fa-solid fa-check text-emerald-600 text-[10px]"></i>
                                        <span>Tepat Waktu</span>
                                    </span>
                                @endif
                            </td>

                            <td data-label="Aksi" class="px-6 py-4 text-right actions-cell">
                                <button type="button" onclick="openDetailPresensiModal(this)" data-absen="{{ $b64Absen }}"
                                    class="px-2.5 py-1.5 border border-slate-200 rounded-lg bg-white hover:bg-slate-50 text-slate-700 font-medium transition inline-flex items-center gap-1.5 shadow-2xs hover:border-slate-300" title="Buka Detail Bukti Presensi">
                                    <i class="fa-solid fa-camera text-xs text-blue-600"></i>
                                    <span>Bukti</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-regular fa-calendar-xmark text-3xl text-slate-300"></i>
                                    <p class="font-medium text-xs">Tidak ada data riwayat presensi siswa pada rentang tanggal ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- ================= MODAL: DETAIL PRESENSI (BUKTI & GPS) ================= -->
    <div id="detailPresensiModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="modal-content-box card p-6 shadow-xl w-full max-w-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-clipboard-user"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Rincian Presensi</h3>
                        <p id="modalPresensiTanggal" class="text-xs text-slate-400">-</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('detailPresensiModal')" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-black flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="space-y-4 text-xs">
                <!-- Status Badges -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Status Ketepatan</span>
                        <span id="modalPresensiStatus" class="font-bold text-slate-900 text-xs">-</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Durasi Kerja</span>
                        <span id="modalPresensiDurasi" class="font-mono font-bold text-slate-900 text-xs">-</span>
                    </div>
                </div>

                <!-- Section Izin (Jika jenisnya izin) -->
                <div id="modalPresensiIzinBox" class="hidden p-3.5 bg-amber-50/70 border border-amber-200 rounded-xl space-y-2 text-amber-900">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-file-signature text-amber-600"></i>
                            <span id="modalPresensiJenisIzin">Pengajuan Izin</span>
                        </span>
                    </div>
                    <p id="modalPresensiKeteranganIzin" class="text-xs text-slate-700 italic bg-white p-2.5 rounded-lg border border-amber-100">
                        -
                    </p>
                    <a id="modalPresensiFileIzinBtn" href="#" target="_blank" class="hidden px-3 py-1.5 bg-white border border-amber-200 rounded-lg text-amber-900 font-semibold text-xs transition flex items-center justify-center gap-1.5 shadow-2xs">
                        <i class="fa-solid fa-file-arrow-down text-amber-600"></i>
                        <span>Lihat Lampiran Surat Izin</span>
                    </a>
                </div>

                <!-- Section Foto Masuk & Pulang (WFO) -->
                <div id="modalPresensiFotoBox" class="space-y-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Foto Bukti Kehadiran</span>
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Foto Masuk -->
                        <div class="border border-slate-200 rounded-xl p-2.5 text-center bg-slate-50 space-y-1.5">
                            <span class="text-[10px] font-bold text-slate-500 uppercase block">Masuk (<span id="modalPresensiJamMasuk">--:--</span>)</span>
                            <div class="h-28 rounded-lg bg-slate-200 overflow-hidden flex items-center justify-center relative group">
                                <img id="modalFotoMasuk" src="" alt="Foto Masuk" class="w-full h-full object-cover hidden">
                                <span id="modalFotoMasukEmpty" class="text-slate-400 text-[10px] font-medium">Tidak ada foto</span>
                                <button type="button" id="modalFotoMasukZoom" onclick="openLightbox(this)" class="hidden absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold">
                                    <i class="fa-solid fa-magnifying-glass-plus text-base"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Foto Pulang -->
                        <div class="border border-slate-200 rounded-xl p-2.5 text-center bg-slate-50 space-y-1.5">
                            <span class="text-[10px] font-bold text-slate-500 uppercase block">Pulang (<span id="modalPresensiJamPulang">--:--</span>)</span>
                            <div class="h-28 rounded-lg bg-slate-200 overflow-hidden flex items-center justify-center relative group">
                                <img id="modalFotoKeluar" src="" alt="Foto Pulang" class="w-full h-full object-cover hidden">
                                <span id="modalFotoKeluarEmpty" class="text-slate-400 text-[10px] font-medium">Belum absen</span>
                                <button type="button" id="modalFotoKeluarZoom" onclick="openLightbox(this)" class="hidden absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold">
                                    <i class="fa-solid fa-magnifying-glass-plus text-base"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section GPS Lokasi -->
                <div id="modalPresensiGpsBox" class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-rose-500 text-sm"></i>
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase font-semibold block">Titik Koordinat GPS</span>
                            <span id="modalPresensiGpsCoords" class="font-mono text-slate-700 text-[11px]">-</span>
                        </div>
                    </div>
                    <a id="modalPresensiGpsLink" href="#" target="_blank"
                        class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-700 hover:bg-slate-100 text-xs font-semibold flex items-center gap-1 shadow-2xs">
                        <i class="fa-solid fa-map-location-dot text-blue-600 text-[10px]"></i>
                        <span>Maps</span>
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-slate-100 mt-5">
                <button type="button" onclick="closeModal('detailPresensiModal')" class="px-4 py-2 border border-slate-200 text-slate-700 rounded-lg font-medium text-xs hover:bg-slate-50 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ================= IMAGE LIGHTBOX ================= -->
    <div id="imageLightboxModal" class="modal hidden fixed inset-0 bg-black/80 backdrop-blur-xs flex items-center justify-center p-4 z-60" onclick="closeImageLightbox()">
        <div class="relative max-w-md w-full" onclick="event.stopPropagation()">
            <button type="button" onclick="closeImageLightbox()" class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white text-slate-800 font-bold text-sm flex items-center justify-center shadow-lg hover:bg-slate-100 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <img id="lightboxImage" src="" alt="Preview Full" class="w-full h-auto max-h-[80vh] rounded-2xl object-contain shadow-2xl border-2 border-white/20">
        </div>
    </div>

    <!-- ================= MODAL UBAH TANGGAL SELESAI MAGANG ================= -->
    <div id="modalUbahPeriode" class="modal hidden fixed inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="card p-6 shadow-xl w-full max-w-md bg-white">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-calendar-days text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Ubah Tanggal Selesai Magang</h3>
                        <p class="text-[11px] text-slate-400">Percepat atau perpanjang masa magang siswa</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalUbahPeriode')" class="text-slate-400 hover:text-slate-800 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('pegawai.siswa.update_periode', $siswa->id) }}" class="space-y-4">
                @csrf
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs space-y-1">
                    <div class="font-bold text-slate-800">{{ $siswa->nama_lengkap }}</div>
                    <div class="text-[11px] text-slate-500">Divisi: {{ $siswa->divisi?->nama_divisi ?? '-' }}</div>
                    <div class="text-[11px] text-slate-500">
                        Tanggal Mulai: <strong>{{ $siswa->tanggal_mulai ? \Carbon\Carbon::parse($siswa->tanggal_mulai)->format('d/m/Y') : '-' }}</strong>
                    </div>
                    <div class="text-[11px] text-slate-500">
                        Tanggal Selesai Saat Ini: <strong class="text-teal-700">{{ $siswa->tanggal_selesai ? \Carbon\Carbon::parse($siswa->tanggal_selesai)->format('d/m/Y') : '-' }}</strong>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Tanggal Selesai Baru <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal_selesai" required
                        min="{{ $siswa->tanggal_mulai ? \Carbon\Carbon::parse($siswa->tanggal_mulai)->format('Y-m-d') : '' }}"
                        value="{{ $siswa->tanggal_selesai ? \Carbon\Carbon::parse($siswa->tanggal_selesai)->format('Y-m-d') : '' }}"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                    <p class="text-[10px] text-slate-400 mt-1">Pilih tanggal lebih awal untuk mempercepat, atau tanggal lebih akhir untuk memperpanjang.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Catatan / Alasan Perubahan (Opsional)
                    </label>
                    <textarea name="alasan" rows="2" placeholder="Contoh: Perpanjangan pengerjaan laporan akhir atau percepatan ujian sidang..."
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:border-slate-900 resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modalUbahPeriode')" 
                        class="px-3.5 py-2 border border-slate-200 text-slate-600 rounded-lg font-medium text-xs hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-slate-900 hover:bg-black text-white rounded-lg font-semibold text-xs shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function resetToMagangPeriod() {
            document.getElementById('filterRiwayatDari').value = "{{ $defaultDari }}";
            document.getElementById('filterRiwayatKe').value = "{{ $defaultKe }}";
        }

        function openModalUbahPeriode() {
            showModal('modalUbahPeriode');
        }

        function showModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('hidden');
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        }

        function openDetailPresensiModal(btn) {
            const raw = btn.getAttribute('data-absen');
            if (!raw) return;

            try {
                const data = JSON.parse(atob(raw));
                document.getElementById('modalPresensiTanggal').textContent = data.tanggal || '-';
                document.getElementById('modalPresensiStatus').textContent = data.status_ketepatan || 'Tepat Waktu';
                document.getElementById('modalPresensiDurasi').textContent = data.durasi_kerja || '-';

                // Izin section
                const izinBox = document.getElementById('modalPresensiIzinBox');
                const fotoBox = document.getElementById('modalPresensiFotoBox');
                const gpsBox = document.getElementById('modalPresensiGpsBox');

                if (data.is_izin) {
                    izinBox.classList.remove('hidden');
                    document.getElementById('modalPresensiJenisIzin').textContent = `Izin: ${data.jenis_izin || 'Resmi'}`;
                    document.getElementById('modalPresensiKeteranganIzin').textContent = data.keterangan_izin || 'Tidak ada keterangan tertulis.';
                    
                    const fileBtn = document.getElementById('modalPresensiFileIzinBtn');
                    if (data.file_izin_url) {
                        fileBtn.href = data.file_izin_url;
                        fileBtn.classList.remove('hidden');
                    } else {
                        fileBtn.classList.add('hidden');
                    }
                } else {
                    izinBox.classList.add('hidden');
                }

                // Foto section
                document.getElementById('modalPresensiJamMasuk').textContent = data.jam_masuk || '--:--';
                document.getElementById('modalPresensiJamPulang').textContent = data.jam_pulang || '--:--';

                const imgMasuk = document.getElementById('modalFotoMasuk');
                const emptyMasuk = document.getElementById('modalFotoMasukEmpty');
                const zoomMasuk = document.getElementById('modalFotoMasukZoom');
                if (data.foto_masuk_url) {
                    imgMasuk.src = data.foto_masuk_url;
                    imgMasuk.classList.remove('hidden');
                    emptyMasuk.classList.add('hidden');
                    zoomMasuk.classList.remove('hidden');
                    zoomMasuk.setAttribute('data-src', data.foto_masuk_url);
                } else {
                    imgMasuk.classList.add('hidden');
                    emptyMasuk.classList.remove('hidden');
                    zoomMasuk.classList.add('hidden');
                }

                const imgKeluar = document.getElementById('modalFotoKeluar');
                const emptyKeluar = document.getElementById('modalFotoKeluarEmpty');
                const zoomKeluar = document.getElementById('modalFotoKeluarZoom');
                if (data.foto_keluar_url) {
                    imgKeluar.src = data.foto_keluar_url;
                    imgKeluar.classList.remove('hidden');
                    emptyKeluar.classList.add('hidden');
                    zoomKeluar.classList.remove('hidden');
                    zoomKeluar.setAttribute('data-src', data.foto_keluar_url);
                } else {
                    imgKeluar.classList.add('hidden');
                    emptyKeluar.classList.remove('hidden');
                    zoomKeluar.classList.add('hidden');
                }

                // GPS Section
                if (data.lokasi_lat && data.lokasi_long) {
                    gpsBox.classList.remove('hidden');
                    document.getElementById('modalPresensiGpsCoords').textContent = `${data.lokasi_lat}, ${data.lokasi_long}`;
                    document.getElementById('modalPresensiGpsLink').href = `https://www.google.com/maps?q=${data.lokasi_lat},${data.lokasi_long}`;
                } else {
                    gpsBox.classList.add('hidden');
                }

                showModal('detailPresensiModal');
            } catch (e) {
                console.error("Gagal membuka detail presensi:", e);
            }
        }

        function openLightbox(btn) {
            const src = btn.getAttribute('data-src') || btn.querySelector('img')?.src;
            if (src) {
                document.getElementById('lightboxImage').src = src;
                showModal('imageLightboxModal');
            }
        }

        function closeImageLightbox() {
            closeModal('imageLightboxModal');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('detailPresensiModal');
                closeImageLightbox();
            }
        });
    </script>
</body>
</html>
