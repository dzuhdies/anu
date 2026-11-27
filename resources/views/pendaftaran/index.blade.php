<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pendaftaran Magang - BPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        html,
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            height: 100%;
            height: 100dvh;
            overflow: hidden;
            width: 100%;
            max-width: 100vw;
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

        .app-container {
            width: 100%;
            max-width: 100vw;
            height: 100%;
            height: 100dvh;
            background-color: #ffffff;
            display: flex;
            overflow: hidden;
        }

        .card {
            background: #ffffff;
            border: 1px solid #f0f2f5;
            border-radius: 0.875rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .progress-track {
            background-color: #f3f4f6;
            height: 6px;
            border-radius: 999px;
            width: 100%;
        }

        .progress-fill {
            height: 100%;
            border-radius: 999px;
        }
    </style>
    @include('partials.pwa')
</head>

<body class="bg-white h-screen h-[100dvh] overflow-hidden w-full max-w-full">

    <div class="app-container">

        <!-- ================= SIDEBAR KARNIVA (DESKTOP & TABLET) ================= -->
        <aside id="mainSidebar" class="hidden md:flex w-[250px] bg-[#fafafa] border-r border-gray-100 flex-col shrink-0 static">
            <!-- Brand / Logo BPS Kota Malang -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-gray-100 border-dashed bg-white/70">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('logo-bps-malang.png') }}" alt="BPS Kota Malang" class="h-8 max-h-9 w-auto object-contain">
                </a>
            </div>

            <!-- Sidebar Navigation Links -->
            <div class="flex-1 overflow-y-auto custom-scrollbar px-3.5 py-4 space-y-6">
                <!-- Nav Group: Pendaftaran -->
                <div>
                    <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Menu Utama</h3>
                    <ul class="space-y-1 text-xs font-medium">
                        <li>
                            <button type="button" onclick="switchTab('pendaftaran')" id="nav-pendaftaran"
                                class="sidebar-btn w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                                <span class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-clipboard-list w-4 text-center"></i>
                                    Formulir Magang
                                </span>
                            </button>
                        </li>
                        <li>
                            <button type="button" onclick="switchTab('status')" id="nav-status"
                                class="sidebar-btn w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                                <span class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i>
                                    Status Pengajuan
                                </span>
                            </button>
                        </li>
                        <li>
                            <button type="button" onclick="switchTab('profile')" id="nav-profile"
                                class="sidebar-btn w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                                <span class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-id-card w-4 text-center"></i>
                                    Profil Biodata Saya
                                </span>
                                @if(!$pesertaProfile)
                                <span class="text-[9px] font-bold bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded-full">Wajib</span>
                                @else
                                <span class="text-[9px] font-bold bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded-full">Siap</span>
                                @endif
                            </button>
                        </li>
                        <li>
                            <button type="button" onclick="switchTab('divisi')" id="nav-divisi"
                                class="sidebar-btn w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                                <span class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-sitemap w-4 text-center"></i>
                                    Daftar Divisi
                                </span>
                            </button>
                        </li>
                        <li>
                            <button type="button" onclick="switchTab('history')" id="nav-history"
                                class="sidebar-btn w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                                <span class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-graduation-cap w-4 text-center"></i>
                                    Riwayat Magang
                                </span>
                                @if(isset($historyMagang) && $historyMagang->count() > 0)
                                @endif
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Info Box Card -->
                <div class="p-3 bg-white rounded-xl border border-gray-100 shadow-xs space-y-2">
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-900">
                        <i class="fa-solid fa-circle-info text-blue-500"></i>
                        <span>Petunjuk Magang</span>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed">
                        Peserta wajib melengkapi profil biodata dan berkas CV. Untuk pendaftaran berkelompok, rekan cukup dicari melalui Username atau Email akun mereka.
                    </p>
                </div>
            </div>

            @if(isset($pendaftaran) && $pendaftaran && $pendaftaran->status === 'approved')
            <div class="px-3 pt-2">
                <button type="button" onclick="openModalUndurDiri()" class="w-full py-2 px-2.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-[11px] font-semibold transition flex items-center justify-between cursor-pointer active:scale-95">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-user-xmark text-rose-600 text-[10px]"></i>
                        <span>Pengunduran Diri</span>
                    </span>
                    <i class="fa-solid fa-chevron-right text-[9px] text-rose-400"></i>
                </button>
            </div>
            @endif

            <!-- Profile Footer -->
            <div class="p-3 border-t border-gray-100 flex items-center justify-between bg-white">
                <button type="button" onclick="switchTab('profile')" class="flex items-center gap-2.5 text-left flex-1 min-w-0 pr-2 hover:opacity-80 transition">
                    <div class="w-8 h-8 rounded-full bg-gray-900 text-white font-bold text-xs flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($pesertaProfile?->nama_lengkap ?: $user->username, 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <span class="text-xs font-bold text-gray-900 block truncate leading-tight">
                            {{ $pesertaProfile?->nama_lengkap ?: $user->username }}
                        </span>
                        <span class="text-[10px] text-gray-400 block truncate">
                            {{ '@' . $user->username }}
                        </span>
                    </div>
                </button>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" title="Keluar dari akun" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ================= MAIN CONTENT AREA ================= -->
        <div class="flex-1 flex flex-col h-full overflow-hidden bg-white">

            <!-- Top Header (h-16) -->
            <header class="h-16 border-b border-gray-100 px-4 sm:px-6 flex items-center justify-between shrink-0 bg-white z-10">
                <div class="flex items-center gap-2.5 min-w-0">
                    <!-- Logo BPS (Tampil di Mobile dan Desktop) -->
                    <a href="{{ url('/') }}" class="flex items-center shrink-0">
                        <img src="{{ asset('logo-bps-malang.png') }}" alt="BPS Kota Malang" class="h-7 w-auto object-contain">
                    </a>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <!-- Status Badge (Hanya di Layar Desktop & Tablet, di HP Ditiadakan) -->
                    <div class="hidden md:flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                        {{ $pendaftaran ? ($pendaftaran->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($pendaftaran->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($pendaftaran->status === 'rescheduled' ? 'bg-blue-50 text-blue-700 border border-blue-200' : ($pendaftaran->status === 'resignation_requested' ? 'bg-purple-50 text-purple-700 border border-purple-200' : ($pendaftaran->status === 'cancelled' || $pendaftaran->status === 'resigned' ? 'bg-gray-100 text-gray-700 border border-gray-200' : 'bg-amber-50 text-amber-700 border border-amber-200'))))) : 'bg-gray-100 text-gray-600' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $pendaftaran ? ($pendaftaran->status === 'approved' ? 'bg-emerald-500' : ($pendaftaran->status === 'rejected' ? 'bg-rose-500' : ($pendaftaran->status === 'rescheduled' ? 'bg-blue-500 animate-pulse' : ($pendaftaran->status === 'resignation_requested' ? 'bg-purple-500 animate-pulse' : ($pendaftaran->status === 'cancelled' || $pendaftaran->status === 'resigned' ? 'bg-gray-400' : 'bg-amber-500 animate-pulse'))))) : 'bg-gray-400' }}"></span>
                        <span class="truncate max-w-[150px]">{{ $pendaftaran ? ($pendaftaran->status === 'rescheduled' ? 'Revisi Jadwal' : ($pendaftaran->status === 'resignation_requested' ? 'Undur Diri Diproses' : ($pendaftaran->status === 'resigned' ? 'Mengundurkan Diri' : ($pendaftaran->status === 'cancelled' ? 'Dibatalkan' : ucfirst($pendaftaran->status))))) : 'Belum Ada' }}</span>
                    </div>

                    <!-- Tombol Keluar (Di HP dan Desktop) -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari akun?')">
                        @csrf
                        <button type="submit" title="Keluar Akun" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Scrollable Tab Content Container -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-3.5 sm:p-6 pb-36 md:pb-8 bg-[#fbfcfd]">

                <!-- Flash Messages -->
                @if(session('success'))
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

                @php
                $pendaftaranModalErrors = ['alasan_undur_diri', 'bukti_undur_diri', 'alasan_penolakan'];
                $activePendaftaranModal = session('modal_error_target');
                if (!$activePendaftaranModal && $errors->any()) {
                if ($errors->hasAny(['alasan_undur_diri', 'bukti_undur_diri'])) {
                $activePendaftaranModal = 'undurDiriModal';
                } elseif ($errors->has('alasan_penolakan')) {
                $activePendaftaranModal = 'rejectRescheduleModal';
                }
                }
                @endphp

                @if(session('error') && !$activePendaftaranModal)
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @endif

                @if($errors->any() && !$activePendaftaranModal)
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium shadow-xs">
                    <div class="font-bold mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                        <span>Terdapat beberapa kesalahan pengisian:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 text-rose-700">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- ================= TAB 1: FORMULIR MAGANG ================= -->
                <div id="tabContent-pendaftaran" class="tab-pane hidden max-w-4xl mx-auto space-y-6 pb-12 md:pb-0">

                    @if(!$pesertaProfile || empty($pesertaProfile->cv_path))
                    <!-- Alert Wajib Mengisi Biodata Dulu -->
                    <div class="card p-6 bg-gradient-to-r from-amber-50 to-orange-50 border-amber-200">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm text-xl">
                                <i class="fa-solid fa-user-clock"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-900 mb-1">Lengkapi Profil Biodata Anda Terlebih Dahulu</h3>
                                <p class="text-xs text-gray-600 leading-relaxed mb-4">
                                    Seluruh calon pendaftar magang wajib memasukkan biodata (Nama Lengkap, Asal Kampus/Sekolah, Jurusan, Nomor WhatsApp, dan Unggah CV) sebagai formulir utama setelah membuat akun. Klik tombol di bawah untuk melengkapi biodata Anda.
                                </p>
                                <button type="button" onclick="switchTab('profile')"
                                    class="px-4 py-2.5 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-black transition shadow-xs flex items-center gap-2">
                                    <i class="fa-solid fa-id-card"></i>
                                    <span>Isi Formulir Biodata Sekarang</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    @elseif($pendaftaran && $pendaftaran->status === 'rescheduled')
                    <!-- Status Pendaftaran Rescheduled -->
                    <div class="card p-6 bg-white space-y-4 border-2 border-amber-300">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 font-bold text-lg">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold text-gray-900">Pemberitahuan Penyesuaian Jadwal Magang</h3>
                                    <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full border border-amber-200">Perlu Tanggapan Anda</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                                    Pihak BPS menyarankan pemindahan jadwal pelaksanaan magang ke tanggal <strong>{{ $pendaftaran->tanggal_mulai_saran ? $pendaftaran->tanggal_mulai_saran->format('d/m/Y') : '-' }} s.d. {{ $pendaftaran->tanggal_selesai_saran ? $pendaftaran->tanggal_selesai_saran->format('d/m/Y') : '-' }}</strong>. Buka halaman status untuk menerima atau menolak tawaran tersebut.
                                </p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-3 flex-wrap">
                            <button type="button" onclick="switchTab('status')" class="px-4 py-2 rounded-lg bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition flex items-center gap-2 shadow-xs">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Tinjau & Tanggapi Usulan Jadwal</span>
                            </button>
                            <form method="POST" action="{{ route('pendaftaran.cancel', $pendaftaran->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran ini?')">
                                @csrf
                                <button type="submit" class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 text-xs font-medium transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-ban text-xs"></i>
                                    <span>Batalkan Pendaftaran</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    @elseif($pendaftaran && $pendaftaran->status === 'approved')
                    <!-- Status Pendaftaran Disetujui -->
                    <div class="card p-6 bg-white space-y-4">
                        @if($pendaftaran->resignation_admin_note)
                        <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-900 space-y-1">
                            <div class="flex items-center gap-2 font-bold text-rose-800">
                                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                                <span>Permohonan Pengunduran Diri Ditolak oleh Pihak BPS</span>
                            </div>
                            <p class="text-[11px] text-rose-700 leading-relaxed">
                                <strong>Alasan Penolakan:</strong> "{{ $pendaftaran->resignation_admin_note }}"
                            </p>
                        </div>
                        @endif
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 font-bold text-lg">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Pengajuan Magang Anda Telah Disetujui!</h3>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                                    Selamat! Permohonan Anda pada divisi <strong>{{ $pendaftaran->divisi->nama_divisi }}</strong> telah disetujui. Periode magang Anda dijadwalkan pada <strong>{{ $pendaftaran->tanggal_mulai ? $pendaftaran->tanggal_mulai->translatedFormat('d F Y') : '-' }}</strong> s.d. <strong>{{ $pendaftaran->tanggal_selesai ? $pendaftaran->tanggal_selesai->translatedFormat('d F Y') : '-' }}</strong>{{ isset($hariMenujuMulai) && $hariMenujuMulai > 0 ? " ({$hariMenujuMulai} hari lagi)" : "" }}.
                                </p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-2 flex-wrap">
                                <button type="button" onclick="switchTab('status')" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-black transition">
                                    Buka Halaman Status & Jadwal Magang
                                </button>
                                @if(isset($magangSudahMulai) && $magangSudahMulai)
                                <a href="{{ route('siswa.absen.form') }}" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-fingerprint text-xs"></i>
                                    <span>Buka Halaman Presensi</span>
                                </a>
                                @endif
                            </div>
                            <button type="button" onclick="openModalUndurDiri()" class="px-3.5 py-2 rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 text-xs font-semibold transition flex items-center gap-1.5">
                                <i class="fa-solid fa-user-xmark text-xs"></i>
                                <span>Ajukan Pengunduran Diri</span>
                            </button>
                        </div>
                    </div>

                    @elseif($pendaftaran && $pendaftaran->status === 'resignation_requested')
                    <!-- Status Pengajuan Pengunduran Diri -->
                    <div class="card p-6 bg-white space-y-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 font-bold text-lg">
                                <i class="fa-solid fa-file-signature"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold text-gray-900">Permohonan Pengunduran Diri Sedang Diverifikasi</h3>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                    Pengajuan pengunduran diri Anda pada divisi <strong>{{ $pendaftaran->divisi->nama_divisi }}</strong> telah terkirim ke pihak BPS beserta berkas alasan. Mohon menunggu keputusan persetujuan.
                                </p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-3 flex-wrap">
                            <button type="button" onclick="switchTab('status')" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-black transition">
                                Lihat Detail Pengajuan
                            </button>
                            <button type="button" onclick="openModalBatalUndurDiri()" class="px-3.5 py-2 rounded-lg border border-purple-200 text-purple-700 bg-purple-50 hover:bg-purple-100 text-xs font-semibold transition flex items-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-rotate-left text-xs"></i>
                                <span>Batalkan Pengunduran Diri</span>
                            </button>
                        </div>
                    </div>

                    @elseif($pendaftaran && $pendaftaran->status === 'pending')
                    <!-- Status Pendaftaran Sedang Pending -->
                    <div class="card p-6 bg-white space-y-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 font-bold">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Permohonan Magang Sedang Dalam Proses Verifikasi</h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Anda telah mengajukan pendaftaran magang pada divisi <strong>{{ $pendaftaran->divisi->nama_divisi }}</strong> untuk periode <strong>{{ $pendaftaran->tanggal_mulai->format('d/m/Y') }}</strong> s.d. <strong>{{ $pendaftaran->tanggal_selesai->format('d/m/Y') }}</strong>.
                                </p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-3 flex-wrap">
                            <button type="button" onclick="switchTab('status')" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-black transition">
                                Lihat Rincian Pengajuan
                            </button>
                            <form method="POST" action="{{ route('pendaftaran.cancel', $pendaftaran->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan pendaftaran ini?')">
                                @csrf
                                <button type="submit" class="px-3.5 py-2 rounded-lg border border-gray-200 text-gray-600 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 text-xs font-medium transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-ban text-xs"></i>
                                    <span>Batalkan Pendaftaran</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    @else
                    @if(isset($historyMagang) && $historyMagang->contains(fn($h) => $h->status === 'completed' || ($h->status === 'approved' && $h->end_date && $h->end_date->lt(now()->startOfDay()))))
                    <!-- Banner Alumni Magang BPS -->
                    <div class="card p-4 bg-gradient-to-r from-teal-50 via-emerald-50 to-teal-50 border border-teal-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-base shrink-0 shadow-xs">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <strong class="text-sm font-bold text-teal-950">Alumni Magang BPS Kota Malang</strong>
                                </div>
                                <p class="text-xs text-teal-800 mt-0.5">
                                    Anda telah menyelesaikan masa magang sebelumnya. Ingin mengajukan magang periode baru? Silakan lengkapi formulir di bawah ini.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @php
                    $divisionTaskTemplates = [
                    'ipds' => [
                    'icon' => 'fa-laptop-code',
                    'deskripsi' => 'Pengembangan teknologi informasi, integrasi pengolahan data sensus/survei, visualisasi dashboard analitik, dan diseminasi publikasi statistik kepada masyarakat.',
                    'tasks' => [
                    'Pengembangan dan pemeliharaan website/sistem informasi internal BPS.',
                    'Perancangan visualisasi data interaktif, dashboard analitik, dan infografis publikasi.',
                    'Pengelolaan basis data hasil survei dan integrasi data sensus statistik.',
                    'Pelayanan konsultasi data statistik di Pelayanan Statistik Terpadu (PST) dan Pojok Statistik.',
                    'Dukungan jaringan, pemeliharaan perangkat keras, dan keamanan sistem informasi kantor.'
                    ]
                    ],
                    'sosial' => [
                    'icon' => 'fa-users',
                    'deskripsi' => 'Pengumpulan, pengolahan, dan analisis statistik kependudukan, kemiskinan, ketenagakerjaan, pendidikan, dan kesejahteraan masyarakat Kota Malang.',
                    'tasks' => [
                    'Validasi, konsistensi, dan entri dokumen survei sosial ekonomi (Susenas dan Sakernas).',
                    'Penyusunan tabulasi data kemiskinan, ketimpangan pengeluaran (Gini Ratio), dan IPM.',
                    'Pengolahan indikator ketenagakerjaan, upah buruh, serta statistik gender dan pendidikan.',
                    'Penyusunan draft publikasi profil statistik kependudukan dan sosial Kota Malang.',
                    'Pemeriksaan kelengkapan berkas kuesioner pendataan lapangan.'
                    ]
                    ],
                    'produksi' => [
                    'icon' => 'fa-industry',
                    'deskripsi' => 'Pengelolaan survei dan sensus sektor riil komoditas, meliputi industri manufaktur, pertanian, tanaman pangan, peternakan, energi, dan konstruksi.',
                    'tasks' => [
                    'Pemutakhiran direktori perusahaan industri manufaktur skala besar, sedang, dan mikro/kecil.',
                    'Pengolahan data survei statistik pertanian tanaman pangan, hortikultura, dan peternakan.',
                    'Pencatatan dan verifikasi data survei perusahaan konstruksi dan bahan galian.',
                    'Penyusunan laporan indeks produksi industri manufaktur dan profil komoditas daerah.',
                    'Rekapitulasi dan pelaporan berkas bulanan data produksi unggulan daerah.'
                    ]
                    ],
                    'distribusi' => [
                    'icon' => 'fa-truck-fast',
                    'deskripsi' => 'Pemantauan indikator peredaran barang/jasa, harga konsumen (inflasi), pariwisata, perhotelan, perdagangan, dan transportasi di Kota Malang.',
                    'tasks' => [
                    'Pencatatan dan pengolahan Survei Harga Konsumen (SHK) untuk perhitungan inflasi Kota Malang.',
                    'Pendataan statistik pariwisata dan Tingkat Penghunian Kamar (TPK) hotel.',
                    'Verifikasi data survei perdagangan besar, eceran, logistik, dan jasa transportasi.',
                    'Penyusunan materi rilis Berita Resmi Statistik (BRS) inflasi dan pariwisata.',
                    'Analisis pergerakan harga komoditas strategis di pasar rakyat dan pasar modern.'
                    ]
                    ],
                    'nerwilis' => [
                    'icon' => 'fa-chart-pie',
                    'deskripsi' => 'Penyusunan indikator makroekonomi regional, Produk Domestik Regional Bruto (PDRB), tabel Input-Output, dan analisis neraca pembangunan ekonomi.',
                    'tasks' => [
                    'Penyusunan dan penghitungan PDRB Kota Malang menurut lapangan usaha dan pengeluaran.',
                    'Analisis pertumbuhan ekonomi, struktur ekonomi daerah, dan PDRB per kapita.',
                    'Kompilasi dan rekonsiliasi data sekunder dari dinas dan instansi terkait.',
                    'Penyusunan draft publikasi Analisis Indikator Makroekonomi Kota Malang.',
                    'Pembuatan model analisis keterkaitan antar sektor ekonomi daerah.'
                    ]
                    ],
                    'umum' => [
                    'icon' => 'fa-building-columns',
                    'deskripsi' => 'Manajemen perkantoran BPS, tata naskah persuratan dinas, administrasi kepegawaian aparatur, Barang Milik Negara (BMN), dan operasional kantor.',
                    'tasks' => [
                    'Tata kelola administrasi persuratan dinas, registrasi naskah masuk/keluar, dan arsip digital.',
                    'Dukungan administrasi kepegawaian dan absensi/presensi aparatur BPS.',
                    'Inventarisasi dan pengelolaan aset Barang Milik Negara (BMN).',
                    'Keprotokolan rapat/acara resmi, dokumentasi kegiatan, dan kehumasan kantor.',
                    'Penyusunan kelengkapan administrasi keuangan dan SPJ kegiatan dinas.'
                    ]
                    ],
                    ];

                    $divisiDataMap = [];
                    foreach($divisis as $div) {
                    $lowerName = strtolower($div->nama_divisi);
                    $matchedKey = null;
                    if (str_contains($lowerName, 'ipds') || str_contains($lowerName, 'integrasi')) {
                    $matchedKey = 'ipds';
                    } elseif (str_contains($lowerName, 'sosial')) {
                    $matchedKey = 'sosial';
                    } elseif (str_contains($lowerName, 'produksi')) {
                    $matchedKey = 'produksi';
                    } elseif (str_contains($lowerName, 'distribusi')) {
                    $matchedKey = 'distribusi';
                    } elseif (str_contains($lowerName, 'nerwilis') || str_contains($lowerName, 'neraca')) {
                    $matchedKey = 'nerwilis';
                    } elseif (str_contains($lowerName, 'umum') || str_contains($lowerName, 'tata usaha') || str_contains($lowerName, 'tu')) {
                    $matchedKey = 'umum';
                    }

                    $template = $matchedKey ? $divisionTaskTemplates[$matchedKey] : [
                    'icon' => 'fa-sitemap',
                    'deskripsi' => 'Pelayanan dan pengelolaan teknis operasional Badan Pusat Statistik Kota Malang.',
                    'tasks' => [
                    'Pendataan dan verifikasi dokumen teknis operasional statistik.',
                    'Pengolahan dan entri data menggunakan sistem aplikasi BPS.',
                    'Penyusunan tabulasi berkas dan rekapitulasi data kegiatan.',
                    'Dukungan administrasi dan laporan progres kerja harian.'
                    ]
                    ];

                    $deskripsiTampil = !empty(trim($div->deskripsi ?? '')) ? $div->deskripsi : $template['deskripsi'];

                    $divisiDataMap[$div->id] = [
                    'id' => $div->id,
                    'nama' => $div->nama_divisi,
                    'deskripsi' => $deskripsiTampil,
                    'icon' => $template['icon'],
                    'tasks' => $div->aktivitas_list,
                    ];
                    }
                    @endphp

                    <!-- LANGKAH 1: List Divisi (Hanya Nama & Deskripsi) -->
                    <div id="stepPilihDivisi" class="space-y-4">
                        <div class="p-5 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <div class="flex items-center gap-2.5">
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900">Pilih Divisi Magang</h4>
                                        <p class="text-[11px] text-gray-400">Silakan klik divisi yang Anda minati untuk melihat deskripsi dan rincian kerjaan yang dilakukan.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Grid Kartu Divisi (Hanya Nama & Deskripsi) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($divisis as $div)
                                @php
                                $dInfo = $divisiDataMap[$div->id] ?? null;
                                @endphp
                                <div onclick="openDetailDivisi({{ $div->id }})"
                                    id="divisiCard-{{ $div->id }}"
                                    class="p-4 rounded-xl border border-gray-200 bg-[#fafafa] hover:border-gray-900 hover:bg-white hover:shadow-sm transition cursor-pointer group flex items-center justify-between gap-3">

                                    <h5 class="text-xs font-bold text-gray-900 group-hover:text-black transition">
                                        {{ $div->nama_divisi }}
                                    </h5>

                                    <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:text-gray-900 group-hover:translate-x-0.5 transition-all"></i>

                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- LANGKAH 2: Halaman Rincian Divisi (Deskripsi & Kerjaan) -->
                    <div id="stepDetailDivisi" class="hidden space-y-4">
                        <div class="flex items-center justify-between">
                            <button type="button" onclick="backToPilihDivisi()"
                                class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:text-black hover:bg-gray-50 text-xs font-medium transition flex items-center gap-1.5">
                                <i class="fa-solid fa-arrow-left text-xs"></i>
                                <span>Kembali ke Daftar Divisi</span>
                            </button>
                            <span class="text-[11px] font-bold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-lg border border-teal-200">
                                Langkah 2: Informasi Divisi
                            </span>
                        </div>

                        <div class="card p-6 bg-white space-y-5">
                            <!-- Header Divisi -->
                            <div class="flex items-start gap-4 pb-4 border-b border-gray-100">
                                <div class="flex-1 min-w-0">
                                    <h3 id="detailDivisiNama" class="text-sm md:text-base font-bold text-gray-900">Nama Divisi</h3>
                                    <p id="detailDivisiDeskripsi" class="text-xs text-gray-600 mt-1 leading-relaxed">
                                        <!-- Deskripsi via JS -->
                                    </p>
                                </div>
                            </div>

                            <!-- Kerjaan yang Dilakukan -->
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">
                                        Kerjaan & Aktivitas yang Dilakukan
                                    </h4>
                                </div>
                                <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100">
                                    <ul id="detailDivisiTasksList" class="space-y-3 pl-1">
                                        <!-- Task items via JS -->
                                    </ul>
                                </div>
                            </div>

                            <!-- Tombol Aksi di Bagian Bawah -->
                            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3 flex-wrap">
                                <button type="button" onclick="proceedToFormulir()"
                                    class="px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-bold transition flex items-center gap-2 shadow-xs">
                                    <span>Daftar Divisi Ini</span>
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- LANGKAH 3: Formulir Isi Tanggal & Kelompok / Individu -->
                    <div id="stepFormulir" class="hidden space-y-5">
                        <div class="flex items-center justify-between">
                            <button type="button" onclick="backToDetailDivisi()"
                                class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:text-black hover:bg-gray-50 text-xs font-medium transition flex items-center gap-1.5">
                                <i class="fa-solid fa-arrow-left text-xs"></i>
                                <span>Kembali ke Detail Divisi</span>
                            </button>
                            <span class="text-[11px] font-bold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-lg border border-teal-200">
                                Langkah 3: Lengkapi Formulir
                            </span>
                        </div>

                        <form method="POST" action="{{ route('pendaftaran.store') }}" id="formPendaftaran" class="space-y-6">
                            @csrf

                            <input type="hidden" name="divisi_id" id="inputDivisi" value="{{ old('divisi_id') }}" required>


                            <!-- Periode Magang & Pengecekan Kuota -->
                            <div class="card p-5 bg-white space-y-4">
                                <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100">
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900">Tentukan Periode Magang</h4>
                                        <p class="text-[11px] text-gray-400">Pilih rentang tanggal mulai dan tanggal selesai magang</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-end">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                                            Tanggal Mulai <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="date" name="tanggal_mulai" id="inputTglMulai" required
                                            min="{{ date('Y-m-d') }}" value="{{ old('tanggal_mulai') }}" onchange="updateFormState()"
                                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition bg-white">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                                            Tanggal Selesai <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="date" name="tanggal_selesai" id="inputTglSelesai" required
                                            min="{{ date('Y-m-d') }}" value="{{ old('tanggal_selesai') }}" onchange="updateFormState()"
                                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition bg-white">
                                    </div>
                                </div>
                            </div>

                            <!-- Anggota Kelompok Tambahan (Opsional) -->
                            <div id="stepKelompokContainer" class="card p-5 bg-white space-y-4">
                                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                    <div class="flex items-center gap-2.5">
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-900">Daftarkan Rekan (Opsional)</h4>
                                            <p class="text-[11px] text-gray-400">Kosongkan jika mendaftar mandiri (1 orang). Tambahkan rekan untuk mendaftar 1 kelompok.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lock Notice jika Kuota/Tanggal Belum Diisi -->
                                <div id="kelompokLockedNotice" class="p-4 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-500 flex items-center gap-3">
                                    <i class="fa-solid fa-lock text-gray-400 text-base"></i>
                                    <span>Tentukan rentang tanggal magang untuk mengaktifkan opsi kelompok dan pengiriman formulir.</span>
                                </div>

                                <!-- Form Kelompok (Tampil setelah tanggal terisi) -->
                                <div id="kelompokActiveSection" class="hidden space-y-4">
                                    <!-- Banner Informasi Verifikasi -->
                                    <div id="kelompokCapacityBanner" class="p-3 bg-teal-50 border border-teal-200 rounded-xl text-xs text-teal-900 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-circle-info text-teal-600"></i>
                                            <span>Pendaftaran Anda akan diproses dan diverifikasi oleh Tim BPS.</span>
                                        </div>
                                        <span id="textStatusKapasitasKelompok" class="text-[10px] font-bold bg-teal-100 text-teal-800 px-2 py-0.5 rounded-full">1 Peserta</span>
                                    </div>

                                    <!-- Form Pencarian Anggota Tambahan -->
                                    <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-100 space-y-3">
                                        <label class="block text-xs font-semibold text-gray-700">
                                            Cari Akun Rekan Kelompok (Username atau Email)
                                        </label>
                                        <div class="flex gap-2">
                                            <div class="relative flex-1">
                                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                                                <input type="text" id="searchAnggotaInput" placeholder="Masukkan username atau email rekan..."
                                                    class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition bg-white"
                                                    onkeydown="if(event.key === 'Enter'){ event.preventDefault(); searchAnggota(); }">
                                            </div>
                                            <button type="button" onclick="searchAnggota()" id="btnSearchAnggota"
                                                class="px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-black transition flex items-center gap-1.5 shrink-0">
                                                <i class="fa-solid fa-user-plus text-xs"></i>
                                                <span>Cari & Tambahkan</span>
                                            </button>
                                        </div>
                                        <p class="text-[10px] text-gray-400">
                                            * Rekan harus sudah membuat akun di sistem dan telah melengkapi profil biodatanya.
                                        </p>

                                        <!-- Hasil Pencarian Anggota Rekan -->
                                        <div id="searchResultBox" class="hidden p-3 bg-white rounded-lg border border-gray-200">
                                            <!-- Diisi via JS -->
                                        </div>
                                    </div>

                                    <!-- Input Nama Kelompok (Muncul jika ada anggota tambahan) -->
                                    <div id="groupNameContainer" class="hidden space-y-1.5 pt-1">
                                        <label class="block text-xs font-semibold text-gray-700">
                                            Nama Kelompok (Opsional)
                                        </label>
                                        <input type="text" name="nama_kelompok" id="inputNamaKelompok" placeholder="Contoh: Tim Statistika Kampus BPS"
                                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition bg-white">
                                    </div>

                                    <!-- Daftar Anggota Tambahan yang Ditambahkan -->
                                    <div class="space-y-2">
                                        <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Daftar Rekan Kelompok Yang Ditambahkan:
                                        </h5>
                                        <div id="anggotaListContainer" class="space-y-2">
                                            <div id="emptyAnggotaNotice" class="text-xs text-gray-400 italic py-2">
                                                Belum ada anggota tambahan yang dipilih. Pendaftaran ini berstatus perorangan (1 orang).
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="submit" id="btnSubmitPendaftaran" disabled
                                    class="px-5 py-2.5 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-black transition flex items-center gap-2 shadow-xs opacity-50 cursor-not-allowed">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span>Kirimkan Permohonan Magang</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                </div>

                <!-- ================= TAB 2: STATUS PENGAJUAN ================= -->
                <div id="tabContent-status" class="tab-pane hidden max-w-4xl mx-auto space-y-6 pb-12 md:pb-0">
                    @if(!$pendaftaran)
                    <!-- Empty State: Belum ada pengajuan aktif -->
                    <div class="card p-10 text-center bg-white space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 text-gray-300 mx-auto flex items-center justify-center text-2xl">
                            <i class="fa-regular fa-folder-open"></i>
                        </div>
                        <div class="max-w-md mx-auto">
                            <h3 class="text-sm font-bold text-gray-900">Tidak Ada Permohonan Aktif</h3>
                            <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                @if(isset($historyMagang) && $historyMagang->contains(fn($h) => $h->status === 'completed' || ($h->status === 'approved' && $h->end_date && $h->end_date->lt(now()->startOfDay()))))
                                Anda telah menyelesaikan program magang di BPS Kota Malang. Anda dapat membuka tab <strong>Riwayat Magang (History)</strong> untuk melihat arsip kegiatan Anda, atau membuat permohonan magang baru melalui formulir.
                                @else
                                Anda belum mengirimkan permohonan pendaftaran magang yang sedang aktif. Silakan lengkapi formulir pendaftaran untuk mengajukan jadwal dan divisi magang di BPS.
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center justify-center gap-2.5 flex-wrap">
                            <button type="button" onclick="switchTab('pendaftaran')" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-black transition">
                                Buka Formulir Magang
                            </button>
                            @if(isset($historyMagang) && $historyMagang->contains(fn($h) => $h->status === 'completed' || ($h->status === 'approved' && $h->end_date && $h->end_date->lt(now()->startOfDay()))))
                            <button type="button" onclick="switchTab('history')" class="px-4 py-2 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold hover:bg-emerald-100 transition flex items-center gap-1.5">
                                <span>Buka Riwayat Magang</span>
                            </button>
                            @endif
                        </div>
                    </div>

                    @else
                    <!-- Rincian Status Pengajuan -->
                    <div class="card p-6 bg-white space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-bold text-gray-900">
                                        {{ $pendaftaran->tipe === 'kelompok' ? ($pendaftaran->nama_kelompok ?: 'Pendaftaran Kelompok') : 'Pendaftaran Mandiri' }}
                                    </h3>
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Diajukan pada {{ $pendaftaran->created_at->format('d F Y, H:i') }} WIB
                                </p>
                            </div>

                            @if($pendaftaran->status === 'rejected' || $pendaftaran->status === 'cancelled' || $pendaftaran->status === 'resigned')
                            <button type="button" onclick="switchTab('pendaftaran')" class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-black transition flex items-center gap-1.5 shadow-xs">
                                <i class="fa-solid fa-plus"></i>
                                <span>Buat Pengajuan Baru</span>
                            </button>
                            @elseif(in_array($pendaftaran->status, ['pending', 'rescheduled']))
                            <form method="POST" action="{{ route('pendaftaran.cancel', $pendaftaran->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan pendaftaran magang ini? Data berkas profil Anda tetap aman dan Anda dapat mengajukan pendaftaran baru.')">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-semibold transition flex items-center gap-1.5 shadow-xs">
                                    <i class="fa-solid fa-ban text-xs"></i>
                                    <span>Batalkan Pendaftaran</span>
                                </button>
                            </form>
                            @endif
                        </div>

                        <!-- Alert Box Sesuai Status -->
                        @if($pendaftaran->status === 'cancelled')
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 space-y-2">
                            <div class="font-bold flex items-center gap-2 text-gray-900">
                                <i class="fa-solid fa-ban text-gray-500"></i>
                                <span>Permohonan Pendaftaran Telah Dibatalkan</span>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $pendaftaran->catatan_admin ?: 'Permohonan pendaftaran magang ini telah dibatalkan atas permintaan Anda.' }} Seluruh biodata dan berkas CV Anda tetap tersimpan dengan aman di sistem. Anda dapat mengajukan permohonan magang baru kapan saja.
                            </p>
                            <div class="pt-2">
                                <button type="button" onclick="switchTab('pendaftaran')" class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white font-semibold text-xs transition flex items-center gap-1.5 shadow-xs">
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Buat Pengajuan Magang Baru</span>
                                </button>
                            </div>
                        </div>
                        @elseif($pendaftaran->status === 'rescheduled')
                        <div class="p-5 bg-gradient-to-r from-amber-50 via-orange-50 to-amber-50 border-2 border-amber-300 rounded-2xl text-xs text-amber-950 space-y-4 shadow-sm">
                            <div class="flex items-start gap-3.5">
                                <div class="w-11 h-11 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-xs">
                                    <i class="fa-solid fa-calendar-days text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-sm font-bold text-amber-950">Usulan Penyesuaian Jadwal Magang</h4>
                                        <span class="text-[10px] font-bold bg-amber-200 text-amber-900 px-2.5 py-0.5 rounded-full border border-amber-300">
                                            Menunggu Persetujuan Anda
                                        </span>
                                    </div>
                                    <p class="text-xs text-amber-900 mt-1 leading-relaxed">
                                        Karena kendala agenda operasional kantor pada periode yang diajukan, pihak BPS menyarankan penyesuaian jadwal pelaksanaan magang ke periode baru berikut:
                                    </p>
                                </div>
                            </div>

                            <!-- Perbandingan Jadwal -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="p-3.5 bg-white/80 rounded-xl border border-amber-200">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Jadwal Awal Pilihan Anda:</span>
                                    <div class="font-bold text-gray-700 text-sm flex items-center gap-2">
                                        <i class="fa-regular fa-calendar text-gray-400"></i>
                                        <span>{{ $pendaftaran->tanggal_mulai->format('d/m/Y') }} - {{ $pendaftaran->tanggal_selesai->format('d/m/Y') }}</span>
                                    </div>
                                    <span class="text-[11px] text-gray-500 block mt-0.5">Durasi: {{ $pendaftaran->durasi_hari }} Hari</span>
                                </div>

                                <div class="p-3.5 bg-white rounded-xl border-2 border-amber-400 shadow-2xs">
                                    <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block mb-1">Usulan Jadwal Baru (Disarankan):</span>
                                    <div class="font-bold text-amber-900 text-sm flex items-center gap-2">
                                        <i class="fa-solid fa-calendar-check text-amber-600"></i>
                                        <span>{{ $pendaftaran->tanggal_mulai_saran ? $pendaftaran->tanggal_mulai_saran->format('d/m/Y') : '-' }} - {{ $pendaftaran->tanggal_selesai_saran ? $pendaftaran->tanggal_selesai_saran->format('d/m/Y') : '-' }}</span>
                                    </div>
                                    <span class="text-[11px] text-amber-700 font-semibold block mt-0.5">
                                        Durasi: {{ $pendaftaran->tanggal_mulai_saran && $pendaftaran->tanggal_selesai_saran ? $pendaftaran->tanggal_mulai_saran->diffInDays($pendaftaran->tanggal_selesai_saran) + 1 : 0 }} Hari
                                    </span>
                                </div>
                            </div>

                            <!-- Alasan Revisi -->
                            <div class="p-3 bg-white/90 rounded-xl border border-amber-200 text-xs">
                                <strong class="font-bold text-amber-900 flex items-center gap-1.5 mb-1">
                                    <i class="fa-solid fa-comment-dots text-amber-600"></i>
                                    <span>Catatan & Keterangan Penyesuaian:</span>
                                </strong>
                                <p class="text-amber-800 italic">"{{ $pendaftaran->catatan_revisi_jadwal ?: 'Penyesuaian jadwal magang dengan agenda operasional kantor BPS.' }}"</p>
                            </div>

                            <!-- Tombol Aksi Peserta: ACC atau Tolak -->
                            <div class="pt-2 border-t border-amber-200/60 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2.5">
                                <!-- Tombol Tolak Jadwal (Membuka Modal Tolak) -->
                                <button type="button" onclick="openRejectRescheduleModal()"
                                    class="px-4 py-2 rounded-xl border border-rose-300 bg-white hover:bg-rose-50 text-rose-700 font-semibold text-xs transition flex items-center justify-center gap-2 shadow-xs">
                                    <i class="fa-solid fa-xmark"></i>
                                    <span>Tolak Jadwal Baru</span>
                                </button>

                                <!-- Form ACC / Terima Jadwal Baru -->
                                <form method="POST" action="{{ route('pendaftaran.accept_reschedule', $pendaftaran->id) }}"
                                    onsubmit="return confirm('Apakah Anda menyetujui jadwal baru ini? Status pendaftaran magang Anda akan LANGSUNG DISETUJUI.')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full sm:w-auto px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center justify-center gap-2 shadow-sm">
                                        <i class="fa-solid fa-check-double"></i>
                                        <span>Terima Jadwal & Setujui (ACC)</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @elseif($pendaftaran->status === 'pending')
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 space-y-1">
                            <div class="font-bold flex items-center gap-2">
                                <i class="fa-solid fa-hourglass-start text-amber-600"></i>
                                <span>Menunggu Verifikasi & Persetujuan</span>
                            </div>
                            <p class="text-amber-800">
                                Berkas dan formulir seluruh anggota telah diterima sistem. Pihak BPS sedang melakukan verifikasi kelengkapan berkas dan ketersediaan kuota divisi yang dituju.
                            </p>
                        </div>
                        @elseif($pendaftaran->status === 'rejected')
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-900 space-y-1">
                            <div class="font-bold flex items-center gap-2">
                                <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                                <span>Permohonan Pendaftaran Ditolak</span>
                            </div>
                            <p class="text-rose-800">
                                <strong>Keterangan:</strong> {{ $pendaftaran->catatan_admin ?: 'Mohon maaf, kuota penuh atau berkas belum memenuhi kriteria.' }}
                            </p>
                        </div>
                        @elseif($pendaftaran->status === 'approved')
                        @if($pendaftaran->resignation_admin_note)
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-950 space-y-2 shadow-2xs">
                            <div class="flex items-center gap-2 font-bold text-rose-900 text-sm">
                                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                                <span>Pemberitahuan: Permohonan Pengunduran Diri Ditolak</span>
                            </div>
                            <p class="text-xs text-rose-800 leading-relaxed">
                                Permohonan pengunduran diri yang Anda ajukan sebelumnya <strong>tidak disetujui / ditolak</strong> oleh pembimbing/pihak BPS. Anda tetap berstatus aktif sebagai peserta magang sesuai jadwal.
                            </p>
                            <div class="p-3 bg-white/90 rounded-xl border border-rose-100 space-y-1">
                                <strong class="text-rose-900 text-[11px] block">Alasan Penolakan dari Pegawai / Pembimbing:</strong>
                                <p class="text-rose-800 italic">"{{ $pendaftaran->resignation_admin_note }}"</p>
                            </div>
                        </div>
                        @endif
                        @if(isset($magangBelumMulai) && $magangBelumMulai)
                        <div class="p-5 bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-950 space-y-3 shadow-2xs">
                            <div class="flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-xs">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-sm font-bold text-emerald-950">Pengajuan Magang Anda Telah Disetujui!</h4>
                                    </div>
                                    <p class="text-xs text-emerald-800 mt-1 leading-relaxed">
                                        Selamat! Permohonan pendaftaran magang Anda di Badan Pusat Statistik telah <strong>resmi disetujui</strong>. Periode magang Anda dijadwalkan pada tanggal <strong>{{ $pendaftaran->tanggal_mulai->translatedFormat('d F Y') }}</strong> s.d. <strong>{{ $pendaftaran->tanggal_selesai->translatedFormat('d F Y') }}</strong>.
                                    </p>
                                </div>
                            </div>

                            <div class="p-3.5 bg-white/90 rounded-xl border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-calendar-check text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-emerald-950 block">Halaman Absensi Aktif Otomatis Saat Periode Magang Dimulai</span>
                                        <span class="text-[11px] text-emerald-700">
                                            Waktu menuju hari pertama magang: <strong>{{ $hariMenujuMulai }} Hari Lagi</strong> (mulai {{ $pendaftaran->tanggal_mulai->translatedFormat('d F Y') }}).
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 flex-wrap">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-100/70 border border-emerald-200 text-emerald-800 text-[11px] font-semibold shrink-0 cursor-default">
                                        <i class="fa-solid fa-lock text-[10px]"></i>
                                        <span>Presensi Dibuka {{ $pendaftaran->tanggal_mulai->format('d/m/Y') }}</span>
                                    </div>

                                    <button type="button" onclick="openModalUndurDiri()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-rose-300 bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold transition shadow-2xs">
                                        <i class="fa-solid fa-user-xmark text-[10px]"></i>
                                        <span>Ajukan Pengunduran Diri</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-900 space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                                    <i class="fa-solid fa-fingerprint text-base"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-emerald-950">Periode Magang Sedang Berjalan</h4>
                                    <p class="text-xs text-emerald-700">Akun Anda aktif untuk melakukan presensi magang harian di BPS.</p>
                                </div>
                            </div>
                            <div class="pt-1 flex items-center gap-2 flex-wrap">
                                <a href="{{ route('siswa.absen.form') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition shadow-xs text-xs">
                                    <i class="fa-solid fa-fingerprint"></i>
                                    <span>Buka Halaman Presensi / Absensi</span>
                                </a>
                                <button type="button" onclick="openModalUndurDiri()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg border border-rose-300 bg-white hover:bg-rose-50 text-rose-700 text-xs font-semibold transition shadow-2xs">
                                    <i class="fa-solid fa-user-xmark text-xs"></i>
                                    <span>Ajukan Pengunduran Diri</span>
                                </button>
                            </div>
                        </div>
                        @endif
                        @elseif($pendaftaran->status === 'resignation_requested')
                        <div class="p-5 bg-purple-50 border border-purple-200 rounded-2xl text-xs text-purple-950 space-y-3 shadow-2xs">
                            <div class="flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-xs">
                                    <i class="fa-solid fa-file-signature"></i>
                                </div>
                                <div class="space-y-1.5 flex-1">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <h4 class="text-sm font-bold text-purple-950">Pengajuan Pengunduran Diri Sedang Diverifikasi</h4>
                                        <button type="button" onclick="openModalBatalUndurDiri()" class="px-3 py-1.5 rounded-lg border border-purple-300 text-purple-700 bg-white hover:bg-purple-100 text-xs font-semibold transition flex items-center gap-1.5 shadow-2xs">
                                            <i class="fa-solid fa-rotate-left text-xs"></i>
                                            <span>Batalkan Pengunduran Diri</span>
                                        </button>
                                    </div>
                                    <p class="text-xs text-purple-800 leading-relaxed">
                                        Anda telah mengajukan permohonan pengunduran diri pada <strong>{{ $pendaftaran->tgl_undur_diri ? $pendaftaran->tgl_undur_diri->translatedFormat('d F Y, H:i') : '-' }} WIB</strong>. Mohon menunggu proses konfirmasi dari pihak BPS.
                                    </p>
                                    <div class="mt-2 p-3 bg-white/90 rounded-xl border border-purple-100 text-xs space-y-2">
                                        <p class="text-gray-700"><strong>Alasan:</strong> {{ $pendaftaran->alasan_undur_diri }}</p>
                                        @if($pendaftaran->bukti_undur_diri)
                                        <div>
                                            <a href="{{ asset('storage/' . $pendaftaran->bukti_undur_diri) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-100 hover:bg-purple-200 text-purple-800 rounded-lg text-xs font-semibold transition">
                                                <i class="fa-solid fa-paperclip"></i>
                                                <span>Lihat Dokumen / Bukti Pengunduran Diri</span>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($pendaftaran->status === 'resigned')
                        <div class="p-5 bg-gray-50 border border-gray-300 rounded-2xl text-xs text-gray-800 space-y-3">
                            <div class="flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-gray-600 text-white flex items-center justify-center font-bold text-lg shrink-0">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </div>
                                <div class="space-y-1.5 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-sm font-bold text-gray-900">Pengunduran Diri Telah Disetujui (ACC)</h4>
                                        <span class="text-[10px] font-bold bg-gray-200 text-gray-700 px-2.5 py-0.5 rounded-full">
                                            Resigned
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 leading-relaxed">
                                        Permohonan pengunduran diri Anda telah disetujui oleh pihak BPS pada <strong>{{ $pendaftaran->undur_diri_approved_at ? $pendaftaran->undur_diri_approved_at->translatedFormat('d F Y') : '-' }}</strong>. Kuota Anda pada divisi <strong>{{ $pendaftaran->divisi->nama_divisi }}</strong> telah dibebaskan.
                                    </p>
                                    @if($pendaftaran->catatan_undur_diri_admin)
                                    <div class="mt-2 p-3 bg-white rounded-xl border border-gray-200 text-xs text-gray-700">
                                        <strong>Catatan Admin / Pegawai:</strong> {{ $pendaftaran->catatan_undur_diri_admin }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Detail Jadwal & Divisi -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-gray-400 text-[10px] uppercase font-semibold block">Divisi Magang</span>
                                <strong class="text-gray-900 text-sm flex items-center gap-1.5 mt-0.5">

                                    {{ $pendaftaran->divisi->nama_divisi }}
                                </strong>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-gray-400 text-[10px] uppercase font-semibold block">Periode Pelaksanaan</span>
                                <strong class="text-gray-900 text-sm flex items-center gap-1.5 mt-0.5">

                                    {{ $pendaftaran->tanggal_mulai->format('d M Y') }} - {{ $pendaftaran->tanggal_selesai->format('d M Y') }}
                                </strong>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-gray-400 text-[10px] uppercase font-semibold block">Total Anggota</span>
                                <strong class="text-gray-900 text-sm flex items-center gap-1.5 mt-0.5">

                                    {{ $pendaftaran->anggotas->count() }} Orang
                                </strong>
                            </div>
                        </div>

                        <!-- Daftar Seluruh Anggota & Berkas -->
                        <div class="space-y-3 pt-2">
                            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Data Biodata Seluruh Anggota ({{ $pendaftaran->anggotas->count() }} Orang):
                            </h4>

                            <div class="space-y-2.5">
                                @foreach($pendaftaran->anggotas as $idx => $anggota)
                                <div class="p-4 rounded-xl border border-gray-100 bg-white space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-full bg-gray-900 text-white font-bold text-xs flex items-center justify-center">{{ $idx + 1 }}</span>
                                            <span class="font-bold text-gray-900 text-sm">{{ $anggota->nama_lengkap }}</span>
                                            @if($idx === 0 && $pendaftaran->tipe === 'kelompok')
                                            <span class="text-[10px] bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded-full border border-blue-200">Pengaju Form</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] font-mono text-gray-400">{{ '@' . $anggota->user->username }}</span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 text-xs text-gray-600">
                                        <div><span class="text-gray-400">Instansi:</span> <strong class="text-gray-800">{{ $anggota->asal_univ }}</strong></div>
                                        <div><span class="text-gray-400">Jurusan:</span> <strong class="text-gray-800">{{ $anggota->jurusan }}</strong></div>
                                        <div><span class="text-gray-400">WhatsApp:</span> <strong class="text-gray-800">{{ $anggota->no_hp ?: '-' }}</strong></div>
                                    </div>

                                    <div class="pt-2 border-t border-gray-100 flex items-center gap-2">
                                        @if($anggota->cv_path)
                                        <a href="{{ asset('storage/' . $anggota->cv_path) }}" target="_blank"
                                            class="px-2.5 py-1 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 text-xs font-medium transition flex items-center gap-1.5">
                                            <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                            <span>Lihat CV</span>
                                        </a>
                                        @endif
                                        @if($anggota->portofolio_path)
                                        <a href="{{ str_starts_with($anggota->portofolio_path, 'http') ? $anggota->portofolio_path : asset('storage/' . $anggota->portofolio_path) }}" target="_blank"
                                            class="px-2.5 py-1 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 text-xs font-medium transition flex items-center gap-1.5">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-blue-600 text-[10px]"></i>
                                            <span>Lihat Portofolio</span>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- ================= TAB 3: PROFIL BIODATA SAYA ================= -->
                <div id="tabContent-profile" class="tab-pane hidden max-w-3xl mx-auto space-y-6 pb-12 md:pb-0">
                    <div class="card p-4 sm:p-6 bg-white space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-900 text-white flex items-center justify-center font-bold text-sm shadow-xs shrink-0">
                                    <i class="fa-solid fa-user-gear"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-bold text-gray-900 truncate">Formulir Profil Biodata Peserta</h3>
                                    <p class="text-xs text-gray-400">Data diri wajib calon peserta magang Badan Pusat Statistik</p>
                                </div>
                            </div>

                            @if($pesertaProfile && !empty($pesertaProfile->cv_path))
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200 flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
                                <i class="fa-solid fa-circle-check"></i> Biodata Lengkap
                            </span>
                            @else
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full border border-amber-200 flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
                                <i class="fa-solid fa-triangle-exclamation"></i> Wajib Dilengkapi
                            </span>
                            @endif
                        </div>

                        <!-- Form Update Profil Biodata -->
                        <form method="POST" action="{{ route('pendaftaran.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        Username Akun
                                    </label>
                                    <input type="text" value="{{ '@' . $user->username }}" disabled
                                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-gray-50 text-gray-500 font-mono">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        Email Akun
                                    </label>
                                    <input type="text" value="{{ $user->email ?: '-' }}" disabled
                                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    Nama Lengkap <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="nama_lengkap" required
                                    value="{{ old('nama_lengkap', $pesertaProfile?->nama_lengkap) }}"
                                    placeholder="Masukkan nama lengkap"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div id="univInputWrapper" class="relative">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        Asal Universitas / Sekolah / Instansi <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="asal_univ" id="inputAsalUniv" required
                                            autocomplete="off"
                                            value="{{ old('asal_univ', $pesertaProfile?->asal_univ) }}"
                                            placeholder="Ketik & pilih Universitas/Sekolah/Instansi "
                                            class="w-full pl-3 pr-8 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition">
                                        <div class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center pointer-events-none text-gray-400">
                                            <i id="univSearchSpinner" class="fa-solid fa-circle-notch fa-spin text-teal-600 hidden"></i>
                                        </div>
                                    </div>

                                    <!-- Peringatan jika tidak memilih dari hasil pencarian -->
                                    <p id="univValidationWarning" class="hidden text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
                                        <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                        <span>Wajib memilih salah satu dari daftar rekomendasi</span>
                                    </p>

                                    <!-- Dropdown Hasil Pencarian Universitas (Maks. 5 Hasil) -->
                                    <div id="univDropdownMenu" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden text-xs">
                                        <div id="univDropdownList" class="max-h-60 overflow-y-auto divide-y divide-gray-50">
                                            <!-- Diisi secara dinamis via JavaScript -->
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        Program Studi / Jurusan <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="jurusan" required
                                        value="{{ old('jurusan', $pesertaProfile?->jurusan) }}"
                                        placeholder="Contoh: Statistika / Ilmu Komputer"
                                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    Nomor WhatsApp / HP Aktif <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="no_hp" required
                                    value="{{ old('no_hp', $pesertaProfile?->no_hp) }}"
                                    placeholder="Contoh: 081234567890"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition">
                            </div>

                            <div class="pt-2 border-t border-gray-100 space-y-2">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
                                    <label class="block text-xs font-semibold text-gray-700">
                                        Curriculum Vitae (CV) <span class="text-rose-500">*</span>
                                        <span class="text-[11px] font-normal text-gray-400">(Format PDF, Maks. 3MB)</span>
                                    </label>
                                    @if($pesertaProfile?->cv_path)
                                    <a href="{{ asset('storage/' . $pesertaProfile->cv_path) }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-semibold transition self-start sm:self-auto shrink-0 shadow-2xs">
                                        <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                        <span>Lihat CV Tersimpan</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-gray-400"></i>
                                    </a>
                                    @endif
                                </div>
                                <div class="w-full">
                                    <input type="file" name="cv" accept=".pdf" {{ $pesertaProfile?->cv_path ? '' : 'required' }}
                                        class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                                    @if($pesertaProfile?->cv_path)
                                    <p class="text-[11px] text-gray-400 mt-1">
                                        * File CV sudah tersimpan di sistem. Unggah file baru hanya jika ingin menggantinya.
                                    </p>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-semibold text-gray-700">
                                        Berkas Portofolio (Opsional)
                                        <span class="text-[10px] font-normal text-gray-400">(PDF, Maks. 10MB)</span>
                                    </label>
                                    <input type="file" name="portofolio_file" accept=".pdf"
                                        class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                                    @if($pesertaProfile?->portofolio_path)
                                    <div class="mt-1">
                                        <a href="{{ asset('storage/' . $pesertaProfile->portofolio_path) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded border border-gray-200 bg-gray-50 text-[11px] text-blue-600 hover:text-blue-800 font-medium">
                                            <i class="fa-solid fa-file-pdf text-rose-500 text-[10px]"></i>
                                            <span>Lihat Portofolio Tersimpan</span>
                                        </a>
                                    </div>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        Tautan Portofolio URL (Opsional)
                                    </label>
                                    <input type="url" name="portofolio_url"
                                        value="{{ old('portofolio_url', $pesertaProfile?->portofolio_url) }}"
                                        placeholder="https://github.com/username atau Google Drive"
                                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition bg-white">
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                <button type="submit" class="px-5 py-2.5 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-black transition flex items-center gap-2 shadow-xs">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    <span>Simpan Biodata Profil</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ================= TAB 4: DAFTAR DIVISI ================= -->
                <div id="tabContent-divisi" class="tab-pane hidden max-w-4xl mx-auto space-y-5 pb-12 md:pb-0">
                    <div class="card p-5 bg-white">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Daftar Divisi Penempatan Magang</h3>
                                <p class="text-xs text-gray-400">Informasi divisi dan bidang kegiatan di Badan Pusat Statistik Kota Malang</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            @foreach($divisis as $div)
                            <div class="p-4 rounded-xl border border-gray-100 bg-[#fbfcfd] space-y-3 hover:border-gray-200 transition">
                                <!-- Nama Divisi -->
                                <div class="flex items-center gap-2 pt-0.5">
                                    <h4 class="font-bold text-gray-900 text-xs">{{ $div->nama_divisi }}</h4>
                                </div>

                                <!-- Deskripsi Divisi -->
                                <p class="text-xs text-gray-500 leading-relaxed">
                                    {{ $div->deskripsi ?: 'Divisi operasional dan teknis Badan Pusat Statistik.' }}
                                </p>

                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 5: RIWAYAT MAGANG (HISTORY) ================= -->
                <div id="tabContent-history" class="tab-pane hidden max-w-4xl mx-auto space-y-6 pb-12 md:pb-0">

                    <!-- Header Banner -->
                    <div class="card p-6 bg-gradient-to-r from-[#0f172a] via-[#1e293b] to-[#0f172a] text-white rounded-2xl shadow-sm relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 text-white/5 text-9xl pointer-events-none font-bold">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3.5">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-base font-bold text-white tracking-tight">Riwayat Kegiatan Magang</h3>
                                    </div>
                                    <p class="text-xs text-gray-300 leading-relaxed max-w-xl">
                                        Catatan resmi keikutsertaan Anda dalam program Praktik Kerja Lapangan (PKL) / Magang di Badan Pusat Statistik Kota Malang.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    @if(!isset($historyMagang) || $historyMagang->isEmpty())
                    <!-- Empty State History -->
                    <div class="card p-10 text-center bg-white space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 mx-auto flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="max-w-md mx-auto">
                            <h3 class="text-sm font-bold text-gray-900">Belum Ada Riwayat Kegiatan Magang</h3>
                            <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                Seluruh riwayat pengajuan dan kegiatan magang Anda (pendaftaran ditolak, pengunduran diri, pemberhentian, hingga selesai magang) akan tercatat di halaman ini.
                            </p>
                        </div>
                        <div class="flex items-center justify-center gap-2.5">
                            <button type="button" onclick="switchTab('pendaftaran')" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-black transition">
                                Ajukan Pendaftaran Magang
                            </button>
                        </div>
                    </div>
                    @else
                    <!-- List Riwayat Pengajuan & Kegiatan Magang -->
                    <div class="space-y-4">
                        @foreach($historyMagang as $hIndex => $hist)
                        <div class="card p-6 bg-white space-y-5 border border-gray-100 hover:border-gray-200 transition shadow-2xs">
                            <!-- Card Header -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-base shrink-0 border border-teal-100">
                                        <i class="fa-solid fa-sitemap"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="text-sm font-bold text-gray-900">{{ $hist->divisi?->nama_divisi ?? 'Divisi BPS' }}</h4>
                                            @if($hist->status === 'rejected')
                                            <span class="text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                                <i class="fa-solid fa-circle-xmark text-[10px]"></i> Pendaftaran Ditolak
                                            </span>
                                            @elseif($hist->status === 'terminated')
                                            <span class="text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                                <i class="fa-solid fa-user-xmark text-[10px]"></i> Diberhentikan
                                            </span>
                                            @elseif($hist->status === 'resigned')
                                            <span class="text-[10px] font-bold bg-gray-100 text-gray-700 border border-gray-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                                <i class="fa-solid fa-person-walking-dashed-line-arrow-right text-[10px]"></i> Mengundurkan Diri
                                            </span>
                                            @elseif($hist->status === 'approved' && $hist->tanggal_selesai && $hist->tanggal_selesai->gte(now()->startOfDay()))
                                            <span class="text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                                <i class="fa-solid fa-spinner text-[10px]"></i> Aktif Magang
                                            </span>
                                            @else
                                            <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                                <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai Magang
                                            </span>
                                            @endif
                                            <span class="text-[10px] font-bold text-black-700">
                                                {{ $hist->tipe === 'kelompok' ? ($hist->nama_kelompok ?: 'Kelompok') : 'Mandiri' }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-gray-400 mt-0.5">
                                            Badan Pusat Statistik Kota Malang &bull;
                                            @if($hist->status === 'rejected')
                                                Diajukan pada {{ $hist->created_at ? $hist->created_at->translatedFormat('d F Y') : '-' }}
                                            @elseif($hist->status === 'terminated')
                                                Diberhentikan pada {{ $hist->updated_at ? $hist->updated_at->translatedFormat('d F Y') : '-' }}
                                            @elseif($hist->status === 'resigned')
                                                Mengundurkan diri pada {{ $hist->updated_at ? $hist->updated_at->translatedFormat('d F Y') : '-' }}
                                            @elseif($hist->status === 'approved' && $hist->tanggal_selesai && $hist->tanggal_selesai->gte(now()->startOfDay()))
                                                Periode {{ $hist->tanggal_mulai ? $hist->tanggal_mulai->translatedFormat('d F Y') : '-' }} s.d. {{ $hist->tanggal_selesai ? $hist->tanggal_selesai->translatedFormat('d F Y') : '-' }}
                                            @else
                                                Diselesaikan pada {{ $hist->tanggal_selesai ? $hist->tanggal_selesai->translatedFormat('d F Y') : '-' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="text-left sm:text-right">
                                    <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block">Durasi Pengajuan</span>
                                    <strong class="text-xs text-gray-800 font-bold block">{{ $hist->durasi_hari }} Hari Kalender</strong>
                                </div>
                            </div>

                            @if($hist->status === 'rejected')
                            <!-- Notifikasi Penolakan Pendaftaran -->
                            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl space-y-1.5 text-xs text-rose-950">
                                <div class="flex items-center gap-2 font-bold text-rose-900">
                                    <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                                    <span>Permohonan Pendaftaran Magang Ditolak</span>
                                </div>
                                <p class="text-[11px] text-rose-800 leading-relaxed">
                                    Mohon maaf, pengajuan pendaftaran magang Anda pada divisi <strong>{{ $hist->divisi?->nama_divisi ?? 'Divisi BPS' }}</strong> untuk periode <strong>{{ $hist->tanggal_mulai ? $hist->tanggal_mulai->translatedFormat('d F Y') : '-' }} - {{ $hist->tanggal_selesai ? $hist->tanggal_selesai->translatedFormat('d F Y') : '-' }}</strong> ini belum dapat disetujui, Anda dapat mengajukan pendaftaran baru melalui <strong>Formulir Magang</strong>.
                                </p>
                            </div>
                            @endif

                            @if($hist->status === 'terminated')
                            <!-- Notifikasi Pemberhentian oleh Pegawai/Admin -->
                            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl space-y-1.5 text-xs text-rose-950">
                                <div class="flex items-center gap-2 font-bold text-rose-900">
                                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                                    <span>Peserta Diberhentikan dari Program Magang</span>
                                </div>
                                <p class="text-[11px] text-rose-800 leading-relaxed">
                                    Masa magang telah diakhiri secara resmi oleh pihak BPS. Peserta tidak memperoleh sertifikat kelulusan.
                                </p>
                                @if($hist->resignation_admin_note)
                                <div class="p-2.5 bg-white/80 rounded-xl border border-rose-100">
                                    <strong class="text-[11px] text-rose-900 block font-semibold">Alasan Pemberhentian:</strong>
                                    <p class="text-[11px] text-rose-800 italic">"{{ $hist->resignation_admin_note }}"</p>
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($hist->certificate && !in_array($hist->status, ['resigned', 'terminated', 'rejected']) && ($hist->status === 'completed' || ($hist->tanggal_selesai && $hist->tanggal_selesai->lt(now()->startOfDay()))))
                            <!-- Sertifikat Resmi Kelulusan Magang -->
                            <div class="p-4 bg-gradient-to-r from-teal-50 via-emerald-50 to-teal-50 border border-teal-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs shadow-2xs">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-base shrink-0 shadow-xs">
                                        <i class="fa-solid fa-award"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <strong class="text-sm font-bold text-teal-950">Sertifikat Resmi Magang BPS Kota Malang</strong>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800 border border-teal-200">
                                                Predikat: {{ $hist->certificate->predicate }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-teal-800 font-mono">
                                            No: {{ $hist->certificate->certificate_number }}
                                        </p>
                                        <span class="text-[10px] text-teal-600 block">
                                            Diterbitkan pada {{ $hist->certificate->issue_date ? \Carbon\Carbon::parse($hist->certificate->issue_date)->translatedFormat('d F Y') : '-' }} oleh {{ $hist->certificate->signer_name }} ({{ $hist->certificate->signer_position }})
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('sertifikat.show', $hist->certificate->id) }}" target="_blank"
                                    class="px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-xs shrink-0 self-start sm:self-auto active:scale-95">
                                    <i class="fa-solid fa-print text-xs"></i>
                                    <span>Buka & Cetak Sertifikat</span>
                                </a>
                            </div>
                            @endif

                            @if(!empty($hist->resignation_history) || $hist->alasan_undur_diri)
                            <!-- Catatan Riwayat Pengunduran Diri -->
                            <div class="p-4 bg-gray-50/80 border border-gray-200 rounded-2xl space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 font-bold text-gray-800">
                                        <i class="fa-solid fa-user-xmark text-gray-500"></i>
                                        <span>Catatan Riwayat Pengunduran Diri</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    @if(!empty($hist->resignation_history))
                                    @foreach($hist->resignation_history as $rEvent)
                                    <div class="p-3 bg-white rounded-xl border border-gray-100 space-y-1">
                                        <div class="flex items-center justify-between flex-wrap gap-1">
                                            <strong class="text-xs text-gray-900">{{ $rEvent['status_label'] ?? ucfirst($rEvent['action'] ?? 'Catatan') }}</strong>
                                            <span class="text-[10px] text-gray-400">{{ $rEvent['created_at'] ?? '-' }}</span>
                                        </div>
                                        @if(!empty($rEvent['reason']))
                                        <p class="text-[11px] text-gray-600"><strong>Alasan Pengajuan:</strong> "{{ $rEvent['reason'] }}"</p>
                                        @endif
                                        @if(!empty($rEvent['note']))
                                        <div class="p-2 bg-rose-50/80 rounded-lg border border-rose-100 text-[11px] text-rose-900">
                                            <strong>Balasan Pegawai / Admin:</strong> "{{ $rEvent['note'] }}"
                                        </div>
                                        @endif
                                        @if(!empty($rEvent['proof']))
                                        <div class="pt-0.5">
                                            <a href="{{ $rEvent['proof'] }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-blue-600 hover:underline">
                                                <i class="fa-solid fa-paperclip"></i>
                                                <span>Lihat Berkas Bukti</span>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="p-3 bg-white rounded-xl border border-gray-100 space-y-1">
                                        <p class="text-[11px] text-gray-700"><strong>Alasan:</strong> {{ $hist->alasan_undur_diri }}</p>
                                        @if($hist->resignation_admin_note || $hist->catatan_undur_diri_admin)
                                        <div class="p-2 bg-rose-50/80 rounded-lg border border-rose-100 text-[11px] text-rose-900">
                                            <strong>Balasan Pegawai:</strong> {{ $hist->resignation_admin_note ?: $hist->catatan_undur_diri_admin }}
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($hist->status !== 'rejected')
                            <!-- Grid Rincian -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <span class="text-gray-400 text-[10px] uppercase font-semibold block">Periode Pelaksanaan</span>
                                    <strong class="text-gray-900 text-xs flex items-center gap-1.5 mt-0.5">
                                        {{ $hist->tanggal_mulai ? $hist->tanggal_mulai->format('d/m/Y') : '-' }} - {{ $hist->tanggal_selesai ? $hist->tanggal_selesai->format('d/m/Y') : '-' }}
                                    </strong>
                                </div>
                            </div>
                            @endif

                            <!-- Statistik Presensi & Riwayat Kehadiran (Jika Ada) -->
                            @if($hist->status !== 'rejected' && isset($historyAbsensiStats) && $historyAbsensiStats['total_hari'] > 0)
                            <div class="pt-3 border-t border-gray-100 space-y-3">
                                <div class="flex items-center justify-between">
                                    <h5 class="text-[11px] font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="fa-solid fa-chart-pie text-teal-600"></i>
                                        <span>Rekap Presensi & Kehadiran Anda</span>
                                    </h5>
                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                        Kehadiran: {{ $historyAbsensiStats['persentase_kehadiran'] }}%
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
                                    <div class="p-2.5 bg-gray-50 border border-gray-100 rounded-xl">
                                        <span class="text-[10px] text-gray-400 block font-semibold">Total Hari Presensi</span>
                                        <strong class="text-sm font-bold text-gray-900">{{ $historyAbsensiStats['total_hari'] }} Hari</strong>
                                    </div>
                                    <div class="p-2.5 bg-emerald-50 border border-emerald-100 rounded-xl">
                                        <span class="text-[10px] text-emerald-700 block font-semibold">Total Hadir</span>
                                        <strong class="text-sm font-bold text-emerald-800">{{ $historyAbsensiStats['total_hadir'] }} Hari</strong>
                                    </div>
                                    <div class="p-2.5 bg-amber-50 border border-amber-100 rounded-xl">
                                        <span class="text-[10px] text-amber-700 block font-semibold">Terlambat</span>
                                        <strong class="text-sm font-bold text-amber-800">{{ $historyAbsensiStats['total_telat'] }} Hari</strong>
                                    </div>
                                    <div class="p-2.5 bg-blue-50 border border-blue-100 rounded-xl">
                                        <span class="text-[10px] text-blue-700 block font-semibold">Izin</span>
                                        <strong class="text-sm font-bold text-blue-800">{{ $historyAbsensiStats['total_izin'] }} Hari</strong>
                                    </div>
                                </div>

                                <!-- Tombol Toggle Detail Presensi -->
                                <div>
                                    <button type="button" onclick="toggleHistoryAbsenDetail({{ $hist->id }})"
                                        class="text-xs font-semibold text-teal-700 hover:text-teal-800 flex items-center gap-1.5 py-1 transition cursor-pointer">
                                        <i class="fa-solid fa-list-check"></i>
                                        <span id="btnTextHistoryAbsen-{{ $hist->id }}">Lihat Rincian Harian Presensi</span>
                                        <i id="btnIconHistoryAbsen-{{ $hist->id }}" class="fa-solid fa-chevron-down text-[10px]"></i>
                                    </button>

                                    <div id="historyAbsenDetailBox-{{ $hist->id }}" class="hidden mt-3 max-h-60 overflow-y-auto custom-scrollbar border border-gray-100 rounded-xl divide-y divide-gray-100 bg-[#fafafa]">
                                        @foreach($historyAbsensis as $absen)
                                        <div class="p-2.5 flex items-center justify-between text-xs hover:bg-white transition">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full {{ $absen->jam_masuk ? ($absen->status_kehadiran === 'Terlambat' ? 'bg-amber-500' : 'bg-emerald-500') : 'bg-blue-500' }}"></span>
                                                <div>
                                                    <strong class="text-gray-900">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y') }}</strong>
                                                    <span class="text-[10px] text-gray-500 block">
                                                        @if($absen->jam_masuk)
                                                        Masuk: {{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') }} | Pulang: {{ $absen->jam_pulang ? \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') : '--:--' }}
                                                        @elseif($absen->jenisIzin)
                                                        Izin: {{ $absen->jenisIzin->nama }}
                                                        @else
                                                        Tidak Hadir
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $absen->jam_masuk ? ($absen->status_kehadiran === 'Terlambat' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') : 'bg-blue-100 text-blue-800' }}">
                                                {{ $absen->status_kehadiran ?: ($absen->jam_masuk ? 'Hadir' : 'Izin') }}
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @php
                                $isCompletedMagang = $hist->status === 'completed' || ($hist->status === 'approved' && $hist->tanggal_selesai && $hist->tanggal_selesai->lt(now()->startOfDay()));
                                $pesanApresiasi = $studentProfile?->final_message ?? Auth::user()->studentProfile?->final_message;
                            @endphp
                            @if($isCompletedMagang && !empty(trim((string) $pesanApresiasi)))
                            <!-- Pesan Apresiasi Resmi BPS (Hanya jika selesai magang & diisi pegawai) -->
                            <div class="p-4 bg-gradient-to-r from-teal-50/60 via-emerald-50/40 to-teal-50/60 border border-teal-100 rounded-2xl flex items-start gap-3 text-xs">
                                <div class="w-8 h-8 rounded-lg bg-teal-600 text-white flex items-center justify-center font-bold shrink-0 mt-0.5 shadow-2xs">
                                    <i class="fa-solid fa-ribbon text-xs"></i>
                                </div>
                                <div>
                                    <strong class="text-teal-950 font-bold block mb-0.5">Apresiasi dari BPS Kota Malang</strong>
                                    <p class="text-teal-900 text-[11px] leading-relaxed">
                                        {{ $pesanApresiasi }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                </div>

            </div>
        </div>
    </div>

    <!-- ================= FLOATING BOTTOM NAVIGATION BAR (KHUSUS HP / MOBILE) ================= -->
    <div class="fixed bottom-4 inset-x-0 z-40 flex justify-center px-4 pointer-events-none md:hidden">
        <nav class="pointer-events-auto bg-white/95 backdrop-blur-xl border border-gray-200/90 shadow-[0_10px_30px_rgba(0,0,0,0.12)] rounded-full px-4 sm:px-6 py-2 flex items-center gap-4 sm:gap-7 transition-all">
            <!-- TAB 1: FORMULIR -->
            <button type="button" onclick="switchTab('pendaftaran')" id="bottom-nav-pendaftaran"
                class="transition-all active:scale-90 text-[#ff6b00] flex flex-col items-center justify-center gap-0.5"
                title="Formulir Magang">
                <i class="fa-solid fa-clipboard-list text-base"></i>
                <span class="text-[9px] font-semibold">Formulir</span>
            </button>

            <!-- TAB 2: STATUS -->
            <button type="button" onclick="switchTab('status')" id="bottom-nav-status"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex flex-col items-center justify-center gap-0.5"
                title="Status Pengajuan">
                <i class="fa-solid fa-clock-rotate-left text-base"></i>
                <span class="text-[9px] font-semibold">Status</span>
            </button>

            <!-- TAB 3: PROFIL -->
            <button type="button" onclick="switchTab('profile')" id="bottom-nav-profile"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex flex-col items-center justify-center gap-0.5"
                title="Profil Biodata">
                <i class="fa-solid fa-id-card text-base"></i>
                <span class="text-[9px] font-semibold">Profil</span>
            </button>

            <!-- TAB 4: DAFTAR DIVISI -->
            <button type="button" onclick="switchTab('divisi')" id="bottom-nav-divisi"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex flex-col items-center justify-center gap-0.5"
                title="Daftar Divisi">
                <i class="fa-solid fa-sitemap text-base"></i>
                <span class="text-[9px] font-semibold">Divisi</span>
            </button>

            <!-- TAB 5: HISTORY -->
            <button type="button" onclick="switchTab('history')" id="bottom-nav-history"
                class="transition-all active:scale-90 text-gray-400 hover:text-gray-600 flex flex-col items-center justify-center gap-0.5 relative"
                title="Riwayat Magang">
                <i class="fa-solid fa-graduation-cap text-base"></i>
                <span class="text-[9px] font-semibold">History</span>
                @if(isset($historyMagang) && $historyMagang->count() > 0)
                <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-emerald-500"></span>
                @endif
            </button>
        </nav>
    </div>

    <!-- JavaScript Interactivity -->
    <script>
        // State Tab Aktif
        let currentTab = "{{ $activeTab }}";

        // State Anggota Kelompok Tambahan
        let addedMembers = []; // List of { id, username, email, nama_lengkap, asal_univ, jurusan, no_hp, cv_ready }

        // State Hasil Pengecekan Kuota
        let currentQuotaCheck = null;

        function toggleMobileSidebar() {
            // Sidebar mobile telah ditiadakan dan diganti dengan Floating Bottom Navigation Bar
        }

        function switchTab(tabName) {
            currentTab = tabName;

            // Sembunyikan semua tab content
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));

            // Tampilkan tab yang dipilih
            const targetContent = document.getElementById(`tabContent-${tabName}`);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }

            // Update Sidebar Link Styles (Desktop)
            document.querySelectorAll('.sidebar-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow-xs', 'border', 'border-gray-100', 'text-gray-900');
                btn.classList.add('text-gray-600');
            });
            const activeNavBtn = document.getElementById(`nav-${tabName}`);
            if (activeNavBtn) {
                activeNavBtn.classList.remove('text-gray-600');
                activeNavBtn.classList.add('bg-white', 'shadow-xs', 'border', 'border-gray-100', 'text-gray-900');
            }

            // Update Mobile Bottom Nav Links
            const mobileTabs = ['pendaftaran', 'status', 'profile', 'divisi', 'history'];
            mobileTabs.forEach(t => {
                const bBtn = document.getElementById(`bottom-nav-${t}`);
                if (bBtn) {
                    if (t === tabName) {
                        bBtn.classList.add('text-[#ff6b00]');
                        bBtn.classList.remove('text-gray-400');
                    } else {
                        bBtn.classList.remove('text-[#ff6b00]');
                        bBtn.classList.add('text-gray-400');
                    }
                }
            });

            // Update Breadcrumb Text
            const breadcrumbMap = {
                'pendaftaran': 'Formulir Magang & Pilihan Divisi',
                'status': 'Status Pengajuan Magang',
                'profile': 'Profil Biodata Saya',
                'divisi': 'Daftar Divisi Penempatan Magang',
                'history': 'Riwayat Magang di BPS Kota Malang'
            };
            const breadcrumbEl = document.getElementById('breadcrumbCurrent');
            if (breadcrumbEl && breadcrumbMap[tabName]) {
                breadcrumbEl.textContent = breadcrumbMap[tabName];
            }

            // Scroll container ke atas
            const scrollContainer = document.querySelector('.custom-scrollbar.overflow-y-auto');
            if (scrollContainer) scrollContainer.scrollTop = 0;
        }

        // ================= TOGGLE DETAIL ABSENSI RIWAYAT =================
        function toggleHistoryAbsenDetail(id) {
            const box = document.getElementById(`historyAbsenDetailBox-${id}`);
            const text = document.getElementById(`btnTextHistoryAbsen-${id}`);
            const icon = document.getElementById(`btnIconHistoryAbsen-${id}`);
            if (box) {
                if (box.classList.contains('hidden')) {
                    box.classList.remove('hidden');
                    if (text) text.textContent = 'Sembunyikan Rincian Harian Presensi';
                    if (icon) icon.className = 'fa-solid fa-chevron-up text-[10px]';
                } else {
                    box.classList.add('hidden');
                    if (text) text.textContent = 'Lihat Rincian Harian Presensi';
                    if (icon) icon.className = 'fa-solid fa-chevron-down text-[10px]';
                }
            }
        }

        // ================= NAVIGASI LANGKAH PENDAFTARAN =================
        const divisiDataMap = @json($divisiDataMap ?? []);
        let currentSelectedDivisiId = null;

        document.addEventListener('DOMContentLoaded', () => {
            switchTab(currentTab);

            // Jika ada old input divisi_id (misal kembali karena validasi error)
            const oldDivisi = document.getElementById('inputDivisi')?.value;
            if (oldDivisi && divisiDataMap[oldDivisi]) {
                proceedToFormulir(oldDivisi);
            }
        });

        function openDetailDivisi(id) {
            const data = divisiDataMap[id];
            if (!data) return;
            currentSelectedDivisiId = id;

            // Update isi Step 2
            const iconEl = document.getElementById('detailDivisiIcon');
            if (iconEl) iconEl.className = 'fa-solid ' + (data.icon || 'fa-sitemap');

            const namaEl = document.getElementById('detailDivisiNama');
            if (namaEl) namaEl.textContent = data.nama;

            const deskripsiEl = document.getElementById('detailDivisiDeskripsi');
            if (deskripsiEl) deskripsiEl.textContent = data.deskripsi;

            const tasksListEl = document.getElementById('detailDivisiTasksList');
            if (tasksListEl) {
                tasksListEl.innerHTML = '';
                (data.tasks || []).forEach(task => {
                    const li = document.createElement('li');
                    li.className = 'flex items-start gap-2.5 text-xs text-gray-700 leading-relaxed';
                    li.innerHTML = '<i class="fa-solid fa-circle-check text-teal-600 text-xs mt-0.5 shrink-0"></i> <span>' + task + '</span>';
                    tasksListEl.appendChild(li);
                });
            }

            // Tampilkan Step 2, sembunyikan Step 1 dan Step 3
            document.getElementById('stepPilihDivisi')?.classList.add('hidden');
            document.getElementById('stepDetailDivisi')?.classList.remove('hidden');
            document.getElementById('stepFormulir')?.classList.add('hidden');

            const container = document.querySelector('.custom-scrollbar.overflow-y-auto') || window;
            container.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function backToPilihDivisi() {
            document.getElementById('stepPilihDivisi')?.classList.remove('hidden');
            document.getElementById('stepDetailDivisi')?.classList.add('hidden');
            document.getElementById('stepFormulir')?.classList.add('hidden');

            const container = document.querySelector('.custom-scrollbar.overflow-y-auto') || window;
            container.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function backToDetailDivisi() {
            if (currentSelectedDivisiId) {
                openDetailDivisi(currentSelectedDivisiId);
            } else {
                backToPilihDivisi();
            }
        }

        function proceedToFormulir(id) {
            const targetId = id || currentSelectedDivisiId;
            if (!targetId) {
                backToPilihDivisi();
                return;
            }
            currentSelectedDivisiId = targetId;

            const inputDiv = document.getElementById('inputDivisi');
            if (inputDiv) inputDiv.value = targetId;

            const data = divisiDataMap[targetId];
            if (data) {
                const iconEl = document.getElementById('formSelectedDivisiIcon');
                if (iconEl) iconEl.className = 'fa-solid ' + (data.icon || 'fa-sitemap');

                const namaEl = document.getElementById('formSelectedDivisiNama');
                if (namaEl) namaEl.textContent = data.nama;

                const descEl = document.getElementById('formSelectedDivisiDesc');
                if (descEl) {
                    descEl.textContent = data.deskripsi || 'Divisi penempatan magang BPS Kota Malang';
                }
            }

            // Tampilkan Step 3, sembunyikan Step 1 dan Step 2
            document.getElementById('stepPilihDivisi')?.classList.add('hidden');
            document.getElementById('stepDetailDivisi')?.classList.add('hidden');
            document.getElementById('stepFormulir')?.classList.remove('hidden');

            updateFormState();

            const container = document.querySelector('.custom-scrollbar.overflow-y-auto') || window;
            container.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function selectDivisiCard(id, nama, sisa, kuota) {
            openDetailDivisi(id);
        }

        function resetQuotaCheckedStatus() {
            currentQuotaCheck = null;
        }

        function updateFormState() {
            const divisiId = document.getElementById('inputDivisi')?.value;
            const tglMulai = document.getElementById('inputTglMulai')?.value;
            const tglSelesai = document.getElementById('inputTglSelesai')?.value;

            if (divisiId && tglMulai && tglSelesai) {
                document.getElementById('kelompokActiveSection')?.classList.remove('hidden');
                document.getElementById('kelompokLockedNotice')?.classList.add('hidden');

                const submitBtn = document.getElementById('btnSubmitPendaftaran');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            } else {
                document.getElementById('kelompokActiveSection')?.classList.add('hidden');
                document.getElementById('kelompokLockedNotice')?.classList.remove('hidden');

                const submitBtn = document.getElementById('btnSubmitPendaftaran');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        // ================= VALIDASI KUOTA ANGGOTA KELOMPOK =================
        function validateKelompokQuota() {
            const statusKapasitas = document.getElementById('textStatusKapasitasKelompok');
            const totalAnggota = 1 + addedMembers.length;

            if (statusKapasitas) {
                statusKapasitas.textContent = `${totalAnggota} Peserta`;
                statusKapasitas.className = 'text-[10px] font-bold bg-teal-100 text-teal-800 px-2 py-0.5 rounded-full';
            }
        }

        // ================= PENCARIAN & TAMBAH ANGGOTA KELOMPOK =================
        function searchAnggota() {
            const input = document.getElementById('searchAnggotaInput');
            const btn = document.getElementById('btnSearchAnggota');
            const resultBox = document.getElementById('searchResultBox');
            const identifier = input.value.trim();

            if (!identifier) {
                alert('Silakan masukkan username atau email rekan Anda.');
                input.focus();
                return;
            }

            // Cek apakah sudah ada di list yang ditambahkan
            const alreadyAdded = addedMembers.some(m => m.username.toLowerCase() === identifier.toLowerCase() || (m.email && m.email.toLowerCase() === identifier.toLowerCase()));
            if (alreadyAdded) {
                alert('Rekan ini sudah ditambahkan ke dalam daftar anggota kelompok.');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Mencari...</span>';
            resultBox.classList.add('hidden');

            fetch("{{ route('pendaftaran.check_user', [], false) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        identifier
                    })
                })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-user-plus text-xs"></i> <span>Cari & Tambahkan</span>';

                    if (!data.success) {
                        resultBox.innerHTML = `
                        <div class="text-xs text-rose-600 flex items-start gap-2">
                            <i class="fa-solid fa-circle-xmark mt-0.5"></i>
                            <div>${data.message || 'Pengguna tidak dapat ditambahkan.'}</div>
                        </div>
                    `;
                        resultBox.classList.remove('hidden');
                        return;
                    }

                    const user = data.data;

                    // Tampilkan Hasil Sukses dengan Tombol "+ Tambahkan"
                    resultBox.innerHTML = `
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900 text-xs">${user.nama_lengkap}</span>
                                <span class="text-[11px] font-mono text-gray-400">@${user.username}</span>
                                <span class="text-[10px] bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded-full font-semibold">Biodata & CV Siap</span>
                            </div>
                            <div class="text-[11px] text-gray-500">
                                <span>${user.asal_univ || '-'}</span> • <span>${user.jurusan || '-'}</span>
                            </div>
                        </div>
                        <button type="button" onclick="confirmAddAnggota(${JSON.stringify(user).replace(/"/g, '&quot;')})"
                            class="px-3 py-1.5 rounded-lg bg-teal-600 text-white text-xs font-semibold hover:bg-teal-700 transition flex items-center gap-1.5 shrink-0">
                            <i class="fa-solid fa-plus"></i>
                            <span>Tambahkan ke Kelompok</span>
                        </button>
                    </div>
                `;
                    resultBox.classList.remove('hidden');
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-user-plus text-xs"></i> <span>Cari & Tambahkan</span>';
                    resultBox.innerHTML = `
                    <div class="text-xs text-rose-600 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Terjadi kesalahan saat memverifikasi akun. Pastikan server terhubung.</span>
                    </div>
                `;
                    resultBox.classList.remove('hidden');
                });
        }

        function confirmAddAnggota(userData) {
            if (addedMembers.some(m => m.id === userData.id)) {
                alert('Rekan ini sudah ditambahkan.');
                return;
            }

            addedMembers.push(userData);

            document.getElementById('searchAnggotaInput').value = '';
            document.getElementById('searchResultBox').classList.add('hidden');

            renderAnggotaList();
            validateKelompokQuota();
        }

        function removeAnggota(id) {
            addedMembers = addedMembers.filter(m => m.id !== id);
            renderAnggotaList();
            validateKelompokQuota();
        }

        function renderAnggotaList() {
            const container = document.getElementById('anggotaListContainer');
            const groupNameContainer = document.getElementById('groupNameContainer');
            const badgeTotal = document.getElementById('badgeTotalPeserta');

            const total = 1 + addedMembers.length;

            if (addedMembers.length === 0) {
                container.innerHTML = `
                    <div id="emptyAnggotaNotice" class="text-xs text-gray-400 italic py-2">
                        Belum ada anggota tambahan yang dipilih. Pendaftaran ini berstatus perorangan (1 orang).
                    </div>
                `;
                groupNameContainer.classList.add('hidden');
                badgeTotal.textContent = '1 Peserta (Mandiri)';
                badgeTotal.className = 'px-2.5 py-1 bg-gray-100 text-gray-800 text-[11px] font-bold rounded-lg border border-gray-200';
                return;
            }

            groupNameContainer.classList.remove('hidden');
            badgeTotal.textContent = `${total} Peserta (Kelompok)`;
            badgeTotal.className = 'px-2.5 py-1 bg-blue-50 text-blue-800 text-[11px] font-bold rounded-lg border border-blue-200';

            let html = '';
            addedMembers.forEach((m, idx) => {
                html += `
                    <div class="p-3 rounded-lg border border-gray-100 bg-white flex items-center justify-between gap-3 shadow-2xs">
                        <input type="hidden" name="anggota_user_id[]" value="${m.id}">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-5 h-5 rounded-full bg-gray-900 text-white font-bold text-[10px] flex items-center justify-center shrink-0">
                                ${idx + 2}
                            </span>
                            <div class="truncate">
                                <span class="font-bold text-gray-900 text-xs block truncate">${m.nama_lengkap}</span>
                                <span class="text-[10px] text-gray-400 block truncate">@${m.username} • ${m.asal_univ || '-'}</span>
                            </div>
                        </div>
                        <button type="button" onclick="removeAnggota(${m.id})" class="text-gray-400 hover:text-rose-600 p-1 text-xs" title="Hapus dari kelompok">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function resetFormPendaftaranFull() {
            addedMembers = [];
            currentQuotaCheck = null;
            currentSelectedDivisiId = null;
            const input = document.getElementById('inputDivisi');
            if (input) input.value = '';
            renderAnggotaList();
            updateFormState();
            backToPilihDivisi();
        }

        function openRejectRescheduleModal() {
            const modal = document.getElementById('rejectRescheduleModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeRejectRescheduleModal() {
            const modal = document.getElementById('rejectRescheduleModal');
            if (modal) {
                modal.classList.add('hidden');
                const errBox = modal.querySelector('.modal-error-box');
                if (errBox) errBox.classList.add('hidden');
            }
        }

        function openModalUndurDiri() {
            const modal = document.getElementById('undurDiriModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                const errBox = modal.querySelector('.modal-error-box');
                if (errBox) errBox.classList.add('hidden');
                const fileInput = document.getElementById('bukti_undur_diri_input');
                if (fileInput && !fileInput.files.length) {
                    resetBuktiUndurDiriUI();
                }
            }
        }

        function closeModalUndurDiri() {
            const modal = document.getElementById('undurDiriModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                const errBox = modal.querySelector('.modal-error-box');
                if (errBox) errBox.classList.add('hidden');
            }
        }

        function handleBuktiUndurDiriChange(input) {
            const emptyBox = document.getElementById('preview_bukti_empty');
            const selectedBox = document.getElementById('preview_bukti_selected');
            const nameEl = document.getElementById('preview_bukti_filename');
            const sizeEl = document.getElementById('preview_bukti_filesize');
            if (input && input.files && input.files[0]) {
                const file = input.files[0];
                if (emptyBox) emptyBox.classList.add('hidden');
                if (selectedBox) selectedBox.classList.remove('hidden');
                if (nameEl) nameEl.textContent = file.name;
                const sizeKb = (file.size / 1024).toFixed(1);
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                if (sizeEl) sizeEl.textContent = file.size >= 1024 * 1024 ? `${sizeMb} MB` : `${sizeKb} KB`;
            } else {
                resetBuktiUndurDiriUI();
            }
        }

        function resetBuktiUndurDiriUI() {
            const input = document.getElementById('bukti_undur_diri_input');
            if (input) input.value = '';
            const emptyBox = document.getElementById('preview_bukti_empty');
            const selectedBox = document.getElementById('preview_bukti_selected');
            if (emptyBox) emptyBox.classList.remove('hidden');
            if (selectedBox) selectedBox.classList.add('hidden');
        }

        function openModalBatalUndurDiri() {
            const modal = document.getElementById('batalUndurDiriModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModalBatalUndurDiri() {
            const modal = document.getElementById('batalUndurDiriModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // ================= UNIVERSAL MODAL FORM AJAX HANDLER =================
        function setupModalForms() {
            document.querySelectorAll('#undurDiriModal form, #rejectRescheduleModal form, #batalUndurDiriModal form').forEach(form => {
                if (form.dataset.ajaxModalInitialized) return;
                form.dataset.ajaxModalInitialized = 'true';

                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const modalEl = form.closest('#undurDiriModal') || form.closest('#rejectRescheduleModal') || form.closest('#batalUndurDiriModal');
                    let errorBox = form.querySelector('.modal-error-box');
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
                                setTimeout(() => window.location.reload(), 150);
                            } else {
                                window.location.reload();
                            }
                            return;
                        }

                        // Tangani kesalahan (422 atau JSON error)
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
                            errorList.push('Terjadi kesalahan saat memproses formulir.');
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

        // ================= 10. AUTOCOMPLETE ASAL UNIVERSITAS (API UNIV) =================
        let univDebounceTimer = null;
        let univAbortController = null;

        function initUniversitasAutocomplete() {
            const input = document.getElementById('inputAsalUniv');
            const menu = document.getElementById('univDropdownMenu');
            const list = document.getElementById('univDropdownList');
            const spinner = document.getElementById('univSearchSpinner');
            const icon = document.getElementById('univSearchIcon');
            const warning = document.getElementById('univValidationWarning');

            if (!input || !menu || !list) return;

            let currentConfirmedUniv = input.value.trim();
            let isUnivConfirmed = currentConfirmedUniv.length > 0;
            let hasValidationError = false;

            function showWarning(msg) {
                hasValidationError = true;
                if (warning) {
                    if (msg) {
                        const span = warning.querySelector('span');
                        if (span) span.textContent = msg;
                    }
                    warning.classList.remove('hidden');
                }
                input.classList.add('border-rose-400', 'bg-rose-50/20');
            }

            function clearWarning() {
                hasValidationError = false;
                if (warning) {
                    warning.classList.add('hidden');
                }
                input.classList.remove('border-rose-400', 'bg-rose-50/20');
            }

            function validateUnivSelection() {
                const currentVal = input.value.trim();
                // 1. Jika user mengetik tetapi tidak mengklik hasil dropdown
                if (currentVal.length > 0 && (!isUnivConfirmed || currentVal !== currentConfirmedUniv)) {
                    input.value = '';
                    currentConfirmedUniv = '';
                    isUnivConfirmed = false;
                    showWarning('Wajib memilih salah satu dari daftar rekomendasi');
                }
                // 2. Jika input kosong dan sedang ada error validasi, jangan hilangkan alertnya!
                else if (currentVal.length === 0 && hasValidationError) {
                    showWarning('Wajib memilih salah satu dari daftar rekomendasi');
                }
                // 3. Hanya bersihkan warning jika benar-benar sudah dikonfirmasi dari dropdown
                else if (isUnivConfirmed && currentVal === currentConfirmedUniv && currentVal.length > 0) {
                    clearWarning();
                }
                menu.classList.add('hidden');
            }

            // Cegah blur premature saat mousedown pada menu dropdown
            menu.addEventListener('mousedown', (e) => {
                e.preventDefault();
            });

            // Tunggu 1 kalimat / kata dulu baru search (debounce 500ms)
            input.addEventListener('input', (e) => {
                const query = e.target.value.trim();
                clearTimeout(univDebounceTimer);

                if (query !== currentConfirmedUniv) {
                    isUnivConfirmed = false;
                }

                if (!query || query.length < 3) {
                    menu.classList.add('hidden');
                    return;
                }

                // Debounce 500ms agar menunggu selesai mengetik kata/kalimat
                univDebounceTimer = setTimeout(() => {
                    executeSearchUniversitas(query);
                }, 500);
            });

            // Saat input kehilangan fokus (blur), hapus jika tidak dipilih dari hasil
            input.addEventListener('blur', () => {
                setTimeout(() => {
                    validateUnivSelection();
                }, 150);
            });

            // Enter key langsung search atau pilih opsi teratas
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    const firstBtn = list.querySelector('button[data-univ]');
                    if (!menu.classList.contains('hidden') && firstBtn) {
                        e.preventDefault();
                        firstBtn.click();
                        return;
                    }
                    const query = input.value.trim();
                    if (query.length >= 3) {
                        e.preventDefault();
                        clearTimeout(univDebounceTimer);
                        executeSearchUniversitas(query);
                    }
                } else if (e.key === 'Escape') {
                    validateUnivSelection();
                }
            });

            // Klik item hasil pencarian
            list.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-univ]');
                if (btn) {
                    const univName = btn.getAttribute('data-univ');
                    input.value = univName;
                    currentConfirmedUniv = univName;
                    isUnivConfirmed = true;
                    clearWarning();
                    menu.classList.add('hidden');
                }
            });

            // Tutup dropdown dan validasi saat klik di luar
            document.addEventListener('click', (e) => {
                if (!e.target.closest('#univInputWrapper')) {
                    validateUnivSelection();
                }
            });

            // Validasi pada saat submit form profil
            const profileForm = input.closest('form');
            if (profileForm) {
                profileForm.addEventListener('submit', (e) => {
                    const currentVal = input.value.trim();
                    if (!isUnivConfirmed || currentVal !== currentConfirmedUniv || currentVal.length === 0) {
                        e.preventDefault();
                        input.value = '';
                        currentConfirmedUniv = '';
                        isUnivConfirmed = false;
                        showWarning('Wajib memilih salah satu dari daftar rekomendasi');
                        input.focus();
                    }
                });
            }

            async function executeSearchUniversitas(q) {
                if (!q || q.length < 3) {
                    menu.classList.add('hidden');
                    return;
                }

                if (univAbortController) {
                    univAbortController.abort();
                }
                univAbortController = new AbortController();

                spinner?.classList.remove('hidden');
                icon?.classList.add('hidden');

                list.innerHTML = `
                    <div class="p-3 text-center text-gray-400 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-notch fa-spin text-teal-600"></i>
                        <span>Mencari universitas...</span>
                    </div>
                `;
                menu.classList.remove('hidden');

                try {
                    const url = `https://univ.annova.my.id/names?q=${encodeURIComponent(q)}&limit=5`;
                    const res = await fetch(url, {
                        signal: univAbortController.signal,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!res.ok) throw new Error('Status respon tidak berhasil: ' + res.status);
                    const data = await res.json();

                    // Batasi maksimal 5 hasil
                    const results = (data.names || []).slice(0, 5);

                    if (results.length === 0) {
                        list.innerHTML = `
                            <div class="p-3 text-center text-gray-500">
                                <span>Tidak ditemukan Universitas / Sekolah / Instansi dengan kata kunci tersebut.</span>
                            </div>
                        `;
                    } else {
                        let html = '';
                        results.forEach(name => {
                            const safeName = name.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                            html += `
                                <button type="button" data-univ="${safeName}" class="w-full text-left px-3 py-2.5 hover:bg-teal-50 hover:text-teal-900 flex items-center gap-2.5 transition text-gray-700 cursor-pointer">
                                    <i class="fa-solid fa-building-columns text-teal-600 shrink-0 text-xs"></i>
                                    <span class="font-medium truncate">${safeName}</span>
                                </button>
                            `;
                        });
                        list.innerHTML = html;
                    }
                } catch (err) {
                    if (err.name === 'AbortError') return;
                    console.error('Univ search error:', err);
                    list.innerHTML = `
                        <div class="p-3 text-center text-gray-400">
                            <p class="text-xs">Gagal memuat rekomendasi universitas.</p>
                            <p class="text-[10px] text-gray-400">Pastikan perangkat Anda terhubung ke internet.</p>
                        </div>
                    `;
                } finally {
                    spinner?.classList.add('hidden');
                    icon?.classList.remove('hidden');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            setupModalForms();
            initUniversitasAutocomplete();

            @if(isset($activePendaftaranModal) && $activePendaftaranModal)
            const targetModal = document.getElementById('{{ $activePendaftaranModal }}');
            if (targetModal) targetModal.classList.remove('hidden');
            @endif
        });
    </script>

    @if($pendaftaran && $pendaftaran->status === 'approved')
    <!-- Modal Pengunduran Diri oleh Peserta -->
    <div id="undurDiriModal" class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="card p-6 shadow-xl w-full max-w-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-user-xmark text-rose-600"></i>
                    <span>Pengajuan Pengunduran Diri Magang</span>
                </h3>
                <button type="button" onclick="closeModalUndurDiri()" class="text-gray-400 hover:text-black p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('pendaftaran.undur_diri', $pendaftaran->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Container Alert Kesalahan di Dalam Pop-up -->
                <div class="modal-error-box {{ (isset($activePendaftaranModal) && $activePendaftaranModal === 'undurDiriModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activePendaftaranModal) && $activePendaftaranModal === 'undurDiriModal' && session('error'))
                        <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activePendaftaranModal) && $activePendaftaranModal === 'undurDiriModal' && $errors->any())
                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>

                <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 leading-relaxed">
                    <strong class="flex items-center gap-1.5 font-bold mb-1">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                        Ketentuan Pengunduran Diri:
                    </strong>
                    Permohonan pengunduran diri wajib menyertakan alasan yang jelas dan bukti pendukung resmi (surat pengunduran diri / keterangan). Pengajuan memerlukan verifikasi dan persetujuan (ACC) dari pihak pegawai / admin BPS sebelum kuota dan status akun dilepaskan.
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Pengunduran Diri <span class="text-rose-500">*</span></label>
                    <textarea name="alasan_undur_diri" rows="3" required placeholder="Tuliskan alasan jelas mengapa Anda mengundurkan diri (minimal 10 karakter)..."
                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 resize-none">{{ old('alasan_undur_diri') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Dokumen / Foto Bukti Pendukung <span class="text-rose-500">*</span>
                    </label>
                    <p class="text-[11px] text-gray-500 mb-2">Unggah surat keterangan kampus, surat keterangan sakit/dokter, atau dokumen resmi lainnya (Format: PDF, JPG, PNG, maks 5MB).</p>

                    <div id="dropzone_undur_diri" onclick="document.getElementById('bukti_undur_diri_input').click()"
                        class="border-2 border-dashed border-gray-200 hover:border-gray-900 rounded-xl p-4 text-center cursor-pointer transition bg-gray-50/50 hover:bg-gray-50 group">
                        <input type="file" id="bukti_undur_diri_input" name="bukti_undur_diri" accept=".pdf,.jpg,.jpeg,.png" required class="hidden" onchange="handleBuktiUndurDiriChange(this)">
                        <div id="preview_bukti_empty" class="flex flex-col items-center justify-center gap-1.5 text-gray-500">
                            <div class="w-10 h-10 rounded-full bg-white shadow-xs border border-gray-200 flex items-center justify-center text-gray-600 group-hover:scale-105 transition">
                                <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-800">Klik untuk memilih file bukti</span>
                            <span class="text-[10px] text-gray-400">PDF, JPG, JPEG, atau PNG (Maks 5MB)</span>
                        </div>
                        <div id="preview_bukti_selected" class="hidden flex items-center justify-between p-2.5 bg-white rounded-lg border border-gray-200 text-left">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-file text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p id="preview_bukti_filename" class="text-xs font-bold text-gray-800 truncate">file.pdf</p>
                                    <p id="preview_bukti_filesize" class="text-[10px] text-gray-400">0 KB</p>
                                </div>
                            </div>
                            <button type="button" onclick="event.stopPropagation(); resetBuktiUndurDiriUI();" class="text-xs font-semibold text-rose-600 hover:text-rose-800 px-2 py-1 hover:bg-rose-50 rounded">
                                Ganti
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModalUndurDiri()" class="px-3.5 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Kirim Pengajuan Undur Diri</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($pendaftaran && $pendaftaran->status === 'rescheduled')
    <!-- Modal Penolakan Jadwal oleh Peserta -->
    <div id="rejectRescheduleModal" class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="card p-6 shadow-xl w-full max-w-md bg-white">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-xmark text-rose-600"></i>
                    <span>Tolak Usulan Jadwal Baru</span>
                </h3>
                <button type="button" onclick="closeRejectRescheduleModal()" class="text-gray-400 hover:text-black p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('pendaftaran.reject_reschedule', $pendaftaran->id) }}" class="space-y-4">
                @csrf

                <!-- Container Alert Kesalahan di Dalam Pop-up -->
                <div class="modal-error-box {{ (isset($activePendaftaranModal) && $activePendaftaranModal === 'rejectRescheduleModal' && ($errors->any() || session('error'))) ? '' : 'hidden' }} p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="space-y-1">
                        <div class="font-bold">Harap perbaiki kesalahan berikut:</div>
                        @if(isset($activePendaftaranModal) && $activePendaftaranModal === 'rejectRescheduleModal' && session('error'))
                        <p class="text-[11px] text-rose-700">{{ session('error') }}</p>
                        @endif
                        @if(isset($activePendaftaranModal) && $activePendaftaranModal === 'rejectRescheduleModal' && $errors->any())
                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>

                <p class="text-xs text-gray-600 leading-relaxed">
                    Jika usulan jadwal yang disarankan tidak memungkinkan (misalnya bentrok dengan kalender akademik kampus), silakan sampaikan alasan penolakan Anda di bawah ini:
                </p>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Penolakan Jadwal *</label>
                    <textarea name="alasan_penolakan" rows="3" required placeholder="Contoh: Jadwal tersebut bentrok dengan pelaksanaan UTS/UAS kampus..."
                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 resize-none">{{ old('alasan_penolakan') }}</textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeRejectRescheduleModal()" class="px-3.5 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-xs transition">
                        Kirim Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($pendaftaran && $pendaftaran->status === 'resignation_requested')
    <!-- Modal Batalkan Pengunduran Diri oleh Peserta -->
    <div id="batalUndurDiriModal" class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden" onclick="if(event.target === this) closeModalBatalUndurDiri()">
        <div class="card p-6 shadow-xl w-full max-w-md bg-white">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left text-purple-600"></i>
                    <span>Batalkan Pengunduran Diri</span>
                </h3>
                <button type="button" onclick="closeModalBatalUndurDiri()" class="text-gray-400 hover:text-black p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('pendaftaran.batal_undur_diri', $pendaftaran->id) }}" class="space-y-4">
                @csrf

                <!-- Container Alert Kesalahan di Dalam Pop-up -->
                <div class="modal-error-box hidden p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="error-msg-content text-[11px] text-rose-700"></div>
                </div>

                <div class="p-3.5 bg-purple-50 border border-purple-200 rounded-xl text-xs text-purple-900 leading-relaxed space-y-2">
                    <div class="flex items-center gap-2 font-bold text-purple-950">
                        <i class="fa-solid fa-circle-question text-purple-600 text-sm"></i>
                        <span>Konfirmasi Pembatalan:</span>
                    </div>
                    <p class="text-[11px] text-purple-800 leading-relaxed">
                        Apakah Anda yakin ingin membatalkan permohonan pengunduran diri pada divisi <strong>{{ $pendaftaran->divisi->nama_divisi }}</strong>?
                    </p>
                    <p class="text-[11px] text-purple-800 leading-relaxed">
                        Status pendaftaran Anda akan langsung dikembalikan menjadi <strong>Disetujui (Approved)</strong> dan Anda tetap terdaftar untuk melaksanakan magang di BPS Kota Malang.
                    </p>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModalBatalUndurDiri()" class="px-3.5 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition">
                        Tutup
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>Ya, Batalkan Pengunduran Diri</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</body>

</html>