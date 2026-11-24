{{-- resources/views/pegawai/partials/history-tab.blade.php --}}
<div class="space-y-6">

    <!-- Header & Filter -->
    <div class="card p-5 bg-white border border-gray-100 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <span>Riwayat Peserta Magang</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Daftar siswa/mahasiswa yang pernah magang di BPS Kota Malang. Periksa detail pendaftaran, riwayat presensi harian, dan logbook kegiatan.
                </p>
            </div>
            
        </div>

        <!-- Filter Controls -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 pt-2 border-t border-gray-100">
            <!-- Search Input -->
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" id="searchHistoryInput" oninput="filterHistoryCards()"
                    placeholder="Cari nama, kampus, jurusan, nomor HP, email..."
                    class="w-full pl-9 pr-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-gray-50/50 focus:bg-white text-gray-800 transition">
            </div>

        
        </div>
    </div>

    <!-- Table / List Alumni -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-600 responsive-table">
                <thead class="text-[11px] text-gray-400 uppercase bg-[#fafafa] font-semibold border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-5 py-3.5">Nama</th>
                        <th scope="col" class="px-5 py-3.5">Asal Instansi & Jurusan</th>
                        <th scope="col" class="px-5 py-3.5">Periode & Divisi</th>
                        <th scope="col" class="px-5 py-3.5 text-center">Status</th>
                        <th scope="col" class="px-5 py-3.5 text-right">Rincian & Riwayat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="historyTableBody">
                    @forelse($historyStudents ?? [] as $hist)
                        @php
                            $phoneClean = preg_replace('/[^0-9]/', '', $hist['phone'] ?? '');
                            if (str_starts_with($phoneClean, '0')) {
                                $phoneClean = '62' . substr($phoneClean, 1);
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors history-item-row"
                            data-name="{{ strtolower($hist['name']) }}"
                            data-inst="{{ strtolower($hist['institution']) }}"
                            data-major="{{ strtolower($hist['major']) }}"
                            data-email="{{ strtolower($hist['email']) }}"
                            data-phone="{{ strtolower($hist['phone']) }}"
                            data-status="{{ $hist['is_ended'] ? 'ended' : 'active' }}">
                            
                            <!-- Nama & Kontak -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg {{ $hist['is_ended'] ? 'bg-amber-50 text-black-700 border border-black-200' : 'bg-black-50 text-teal-700 border border-black-200' }} font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ strtoupper(substr($hist['name'], 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                                            <span>{{ $hist['name'] }}</span>
                                        </div>
                                
                                    </div>
                                </div>
                            </td>

                            <!-- Asal Instansi & Jurusan -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-gray-900 text-xs">{{ $hist['institution'] }}</div>
                            </td>

                            <!-- Periode & Divisi -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-bold text-teal-800 text-xs">{{ $hist['division'] }}</div>
                                <div class="text-[10px] text-gray-500 mt-0.5 flex items-center gap-1">
                                    <i class="fa-regular fa-calendar text-gray-400"></i>
                                    <span>{{ $hist['start_date'] }} s.d. {{ $hist['end_date'] }}</span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if(isset($hist['status']) && $hist['status'] === 'terminated')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-user-xmark text-[10px]"></i> Diberhentikan
                                    </span>
                                @elseif(isset($hist['status']) && $hist['status'] === 'resigned')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                        <i class="fa-solid fa-person-walking-dashed-line-arrow-right text-[10px]"></i> Mengundurkan Diri
                                    </span>
                                @elseif($hist['is_ended'])
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-graduation-cap text-[10px]"></i> Selesai Magang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Sedang Berjalan
                                    </span>
                                @endif
                            </td>
                            

                            <!-- Tindakan / Rincian -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- 1. Detail Pendaftaran -->
                                    <button type="button" onclick="openHistoryPendaftaranModal({{ $hist['id'] }})"
                                        class="px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-100 text-gray-700 text-xs font-semibold transition flex items-center gap-1.5 shadow-2xs"
                                        title="Lihat Detail Pendaftaran Siswa (Kontak, CV, Portofolio)">
                                        <i class="fa-solid fa-id-card text-gray-500"></i>
                                        <span>Pendaftaran</span>
                                    </button>

                                    <!-- 2. Riwayat Presensi -->
                                    <button type="button" onclick="openHistoryPresensiModal({{ $hist['id'] }})"
                                        class="px-2.5 py-1.5 rounded-lg border border-teal-200 bg-teal-50/60 hover:bg-teal-100 text-teal-800 text-xs font-semibold transition flex items-center gap-1.5 shadow-2xs"
                                        title="Lihat Riwayat Presensi Lengkap">
                                        <i class="fa-solid fa-calendar-check text-teal-600"></i>
                                        <span>Presensi</span>
                                    </button>

                                    <!-- 3. Riwayat Logbook -->
                                    <button type="button" onclick="openHistoryLogbookModal({{ $hist['id'] }})"
                                        class="px-2.5 py-1.5 rounded-lg border border-orange-200 bg-orange-50/60 hover:bg-orange-100 text-orange-800 text-xs font-semibold transition flex items-center gap-1.5 shadow-2xs"
                                        title="Lihat Riwayat Pengisian Logbook & Berkas TTD">
                                        <i class="fa-solid fa-book-open text-orange-600"></i>
                                        <span>Logbook</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-user-clock text-3xl mb-2 text-gray-300"></i>
                                <p class="text-xs">Belum ada riwayat siswa magang yang tercatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ================= MODAL 1: DETAIL PENDAFTARAN ALUMNI ================= -->
<div id="historyPendaftaranModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
    <div class="card bg-white w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl rounded-2xl animate-fade-in">
        <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between bg-[#fafafa]">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-sm border border-teal-200">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900" id="histPendName">Detail Pendaftaran Magang</h3>
                    <p class="text-[11px] text-gray-400" id="histPendSub">Biodata, kontak, CV, dan portofolio</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('historyPendaftaranModal')" class="w-8 h-8 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <div class="p-5 overflow-y-auto custom-scrollbar space-y-4 text-xs">
            <!-- Grid Kontak & Data Pribadi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-1">
                    <span class="text-[10px] text-gray-400 uppercase font-semibold block">Nama Lengkap</span>
                    <strong class="text-gray-900 text-xs block" id="histPendFullName">-</strong>
                    <span class="text-[11px] text-gray-500 block" id="histPendUsername">Username: -</span>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-1">
                    <span class="text-[10px] text-gray-400 uppercase font-semibold block">Email Terdaftar</span>
                    <strong class="text-gray-900 text-xs block" id="histPendEmail">-</strong>
                    <a href="#" id="histPendEmailLink" class="text-[11px] text-blue-600 hover:underline">Kirim Email</a>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-1">
                    <span class="text-[10px] text-gray-400 uppercase font-semibold block">Nomor WhatsApp / HP</span>
                    <strong class="text-gray-900 text-xs block" id="histPendPhone">-</strong>
                    <a href="#" id="histPendWaLink" target="_blank" class="text-[11px] text-emerald-600 hover:underline inline-flex items-center gap-1 font-semibold">
                        <i class="fa-brands fa-whatsapp"></i> Chat WhatsApp
                    </a>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-1">
                    <span class="text-[10px] text-gray-400 uppercase font-semibold block">Asal Kampus / Sekolah</span>
                    <strong class="text-gray-900 text-xs block" id="histPendInst">-</strong>
                    <span class="text-[11px] text-gray-500 block" id="histPendMajor">-</span>
                </div>
            </div>

            <!-- Periode & Penempatan -->
            <div class="p-3.5 bg-teal-50/50 rounded-xl border border-teal-100 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-teal-900 uppercase tracking-wider">Penempatan Divisi & Gedung</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800" id="histPendStatusBadge">-</span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-gray-500 text-[11px] block">Divisi Magang:</span>
                        <strong class="text-gray-900 font-semibold" id="histPendDivision">-</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 text-[11px] block">Periode Kegiatan:</span>
                        <strong class="text-gray-900 font-semibold" id="histPendPeriod">-</strong>
                    </div>
                </div>
            </div>

            <!-- Berkas Pendaftaran (CV & Portofolio) -->
            <div class="space-y-2">
                <span class="text-[10px] text-gray-400 uppercase font-semibold block">Dokumen Berkas Pendaftaran</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div id="histPendCvBox" class="p-3 rounded-xl border border-gray-200 bg-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-rose-500 text-base"></i>
                            <div>
                                <span class="font-semibold text-gray-800 text-xs block">Curriculum Vitae (CV)</span>
                                <span class="text-[10px] text-gray-400">Berkas Utama</span>
                            </div>
                        </div>
                        <a href="#" id="histPendCvLink" target="_blank"
                            class="px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition">
                            Buka
                        </a>
                    </div>
                    <div id="histPendPortoBox" class="p-3 rounded-xl border border-gray-200 bg-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-teal-600 text-base"></i>
                            <div>
                                <span class="font-semibold text-gray-800 text-xs block">Portofolio</span>
                                <span class="text-[10px] text-gray-400">Karya & Sertifikat</span>
                            </div>
                        </div>
                        <a href="#" id="histPendPortoLink" target="_blank"
                            class="px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition">
                            Buka
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pesan Apresiasi Akhir Magang -->
            <div class="p-3.5 bg-amber-50/60 rounded-xl border border-amber-200 space-y-1">
                <span class="text-[10px] font-bold text-amber-900 uppercase tracking-wider block">Pesan Akhir / Apresiasi dari Pegawai BPS</span>
                <p class="text-[11px] text-amber-950 italic leading-relaxed" id="histPendFinalMessage">
                    Belum ada pesan apresiasi khusus yang disimpan untuk siswa ini.
                </p>
            </div>

            <!-- Informasi Sertifikat Magang Alumni -->
            <div id="histPendCertBox" class="p-3.5 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-emerald-900 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-award text-emerald-600"></i>
                        <span>Sertifikat Resmi Magang BPS</span>
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800" id="histPendCertBadge">Diterbitkan</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <strong class="text-xs text-gray-900 font-mono block" id="histPendCertNumber">-</strong>
                        <span class="text-[11px] text-gray-500" id="histPendCertPredicate">Predikat: -</span>
                    </div>
                    <a href="#" id="histPendCertLink" target="_blank" class="px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs transition flex items-center gap-1.5 self-start sm:self-auto shadow-2xs">
                        <i class="fa-solid fa-print text-[11px]"></i>
                        <span>Buka Sertifikat</span>
                    </a>
                </div>
            </div>

            <!-- Riwayat Pengunduran Diri (Jika Pernah Mengajukan) -->
            <div id="histPendResignationBox" class="hidden p-3.5 bg-rose-50/70 rounded-xl border border-rose-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-rose-900 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-user-xmark text-rose-600"></i>
                        <span>Catatan Riwayat Pengunduran Diri</span>
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-800" id="histPendResignBadge">Tercatat</span>
                </div>
                <div id="histPendResignList" class="space-y-2 text-xs">
                    <!-- Populated via JS -->
                </div>
            </div>
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeModal('historyPendaftaranModal')"
                class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-bold transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ================= MODAL 2: RIWAYAT PRESENSI ALUMNI ================= -->
<div id="historyPresensiModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
    <div class="card bg-white w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl rounded-2xl animate-fade-in">
        <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between bg-[#fafafa]">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-sm border border-teal-200">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900" id="histAbsenName">Riwayat Presensi Siswa</h3>
                    <p class="text-[11px] text-gray-400" id="histAbsenSub">Catatan jam masuk, pulang, dan kehadiran</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('historyPresensiModal')" class="w-8 h-8 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <div class="p-5 overflow-y-auto custom-scrollbar space-y-4 text-xs">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-center">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 uppercase font-semibold block">Total Presensi</span>
                    <strong class="text-sm font-bold text-gray-900" id="histAbsenTotalHari">0 Hari</strong>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                    <span class="text-[10px] text-emerald-700 uppercase font-semibold block">Hadir Tepat</span>
                    <strong class="text-sm font-bold text-emerald-800" id="histAbsenTotalHadir">0 Hari</strong>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                    <span class="text-[10px] text-amber-700 uppercase font-semibold block">Terlambat</span>
                    <strong class="text-sm font-bold text-amber-800" id="histAbsenTotalTelat">0 Hari</strong>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                    <span class="text-[10px] text-blue-700 uppercase font-semibold block">Izin</span>
                    <strong class="text-sm font-bold text-blue-800" id="histAbsenTotalIzin">0 Hari</strong>
                </div>
            </div>

            <!-- Tabel Riwayat Harian Presensi -->
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <div class="max-h-64 overflow-y-auto custom-scrollbar">
                    <table class="w-full text-xs text-left text-gray-600">
                        <thead class="text-[10px] text-gray-400 uppercase bg-gray-50 sticky top-0 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-2.5">Tanggal</th>
                                <th class="px-4 py-2.5">Jam Masuk</th>
                                <th class="px-4 py-2.5">Jam Pulang</th>
                                <th class="px-4 py-2.5 text-center">Status</th>
                                <th class="px-4 py-2.5">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" id="histAbsenTableBody">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <a href="#" id="histAbsenFullLink"
                class="text-xs text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                <span>Buka Rekap Lengkap Presensi Siswa</span>
            </a>
            <button type="button" onclick="closeModal('historyPresensiModal')"
                class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-bold transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ================= MODAL 3: RIWAYAT LOGBOOK ALUMNI ================= -->
<div id="historyLogbookModal" class="modal hidden fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
    <div class="card bg-white w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl rounded-2xl animate-fade-in">
        <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between bg-[#fafafa]">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-700 flex items-center justify-center font-bold text-sm border border-orange-200">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900" id="histLogbookName">Riwayat Logbook Siswa</h3>
                    <p class="text-[11px] text-gray-400" id="histLogbookSub">Aktivitas harian dan berkas progres bulanan</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('historyLogbookModal')" class="w-8 h-8 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-black flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <div class="p-5 overflow-y-auto custom-scrollbar space-y-4 text-xs">
            <!-- Berkas Bulanan Bertandatangan -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-file-circle-check text-teal-600"></i>
                    <span>Berkas Logbook Bulanan (TTD Ketua Divisi)</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2" id="histMonthlyProgressList">
                    <!-- Populated via JS -->
                </div>
            </div>

            <!-- Daftar Aktivitas Harian Logbook -->
            <div class="space-y-2 pt-2 border-t border-gray-100">
                <h4 class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check text-orange-600"></i>
                    <span>Catatan Harian Logbook Siswa</span>
                </h4>
                <div class="border border-gray-100 rounded-xl overflow-hidden">
                    <div class="max-h-64 overflow-y-auto custom-scrollbar">
                        <table class="w-full text-xs text-left text-gray-600">
                            <thead class="text-[10px] text-gray-400 uppercase bg-gray-50 sticky top-0 border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-2.5">Tanggal</th>
                                    <th class="px-4 py-2.5">Aktivitas / Pekerjaan</th>
                                    <th class="px-4 py-2.5">Kendala</th>
                                    <th class="px-4 py-2.5 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="histLogbookTableBody">
                                <!-- Populated via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeModal('historyLogbookModal')"
                class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-bold transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    const historyStudentsData = @json($historyStudents ?? []);

    function setHistoryFilter(status) {
        document.querySelectorAll('.hist-filter-btn').forEach(b => {
            b.className = 'hist-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600 hover:text-gray-900 transition';
        });
        const activeBtn = document.getElementById(`btn-hist-${status}`);
        if (activeBtn) {
            activeBtn.className = 'hist-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-900 text-white transition shadow-xs';
        }

        const rows = document.querySelectorAll('.history-item-row');
        let visibleCount = 0;
        rows.forEach(r => {
            const itemStatus = r.getAttribute('data-status');
            const matchesStatus = (status === 'all') || (itemStatus === status);
            const matchesSearch = checkRowSearchMatch(r);

            if (matchesStatus && matchesSearch) {
                r.style.display = '';
                visibleCount++;
            } else {
                r.style.display = 'none';
            }
        });

        const badge = document.getElementById('historyTotalCountBadge');
        if (badge) badge.textContent = visibleCount;
    }

    function filterHistoryCards() {
        const rows = document.querySelectorAll('.history-item-row');
        let visibleCount = 0;
        rows.forEach(r => {
            const matchesSearch = checkRowSearchMatch(r);
            if (matchesSearch) {
                r.style.display = '';
                visibleCount++;
            } else {
                r.style.display = 'none';
            }
        });

        const badge = document.getElementById('historyTotalCountBadge');
        if (badge) badge.textContent = visibleCount;
    }

    function checkRowSearchMatch(row) {
        const query = (document.getElementById('searchHistoryInput')?.value || '').toLowerCase().trim();
        if (!query) return true;

        const name = row.getAttribute('data-name') || '';
        const inst = row.getAttribute('data-inst') || '';
        const major = row.getAttribute('data-major') || '';
        const email = row.getAttribute('data-email') || '';
        const phone = row.getAttribute('data-phone') || '';

        return name.includes(query) || inst.includes(query) || major.includes(query) || email.includes(query) || phone.includes(query);
    }

    function openHistoryPendaftaranModal(studentId) {
        const student = historyStudentsData.find(s => s.id === studentId);
        if (!student) return;

        document.getElementById('histPendName').textContent = student.name;
        document.getElementById('histPendSub').textContent = `${student.institution} · ${student.major}`;
        document.getElementById('histPendFullName').textContent = student.name;
        document.getElementById('histPendUsername').textContent = `Username: ${student.username}`;
        document.getElementById('histPendEmail').textContent = student.email;
        document.getElementById('histPendEmailLink').href = `mailto:${student.email}`;

        const phoneClean = (student.phone || '').replace(/[^0-9]/g, '');
        const waNumber = phoneClean.startsWith('0') ? '62' + phoneClean.substring(1) : phoneClean;
        document.getElementById('histPendPhone').textContent = student.phone || '-';
        const waLink = document.getElementById('histPendWaLink');
        if (student.phone && student.phone !== '-') {
            waLink.href = `https://wa.me/${waNumber}`;
            waLink.style.display = 'inline-flex';
        } else {
            waLink.style.display = 'none';
        }

        document.getElementById('histPendInst').textContent = student.institution;
        document.getElementById('histPendMajor').textContent = student.major;
        document.getElementById('histPendDivision').textContent = student.division;
        document.getElementById('histPendPeriod').textContent = `${student.start_date} s.d. ${student.end_date}`;

        const statusBadge = document.getElementById('histPendStatusBadge');
        if (student.is_ended) {
            statusBadge.textContent = 'Alumni / Selesai';
            statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800';
        } else {
            statusBadge.textContent = 'Aktif Magang';
            statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
        }

        // CV
        const cvLink = document.getElementById('histPendCvLink');
        const cvBox = document.getElementById('histPendCvBox');
        if (student.cv_url) {
            cvLink.href = student.cv_url;
            cvBox.classList.remove('opacity-50');
            cvLink.classList.remove('pointer-events-none');
            cvLink.textContent = 'Buka CV';
        } else {
            cvBox.classList.add('opacity-50');
            cvLink.classList.add('pointer-events-none');
            cvLink.textContent = 'Tidak Ada';
        }

        // Portofolio
        const portoLink = document.getElementById('histPendPortoLink');
        const portoBox = document.getElementById('histPendPortoBox');
        if (student.porto_url) {
            portoLink.href = student.porto_url;
            portoBox.classList.remove('opacity-50');
            portoLink.classList.remove('pointer-events-none');
            portoLink.textContent = 'Buka Portofolio';
        } else {
            portoBox.classList.add('opacity-50');
            portoLink.classList.add('pointer-events-none');
            portoLink.textContent = 'Tidak Ada';
        }

        // Pesan Apresiasi
        const msgEl = document.getElementById('histPendFinalMessage');
        if (student.final_message) {
            msgEl.textContent = `"${student.final_message}"`;
            msgEl.classList.remove('italic', 'text-gray-400');
            msgEl.classList.add('text-amber-950');
        } else {
            msgEl.textContent = 'Belum ada pesan khusus yang dituliskan pegawai untuk siswa ini.';
            msgEl.classList.add('italic', 'text-gray-400');
            msgEl.classList.remove('text-amber-950');
        }

        // Sertifikat Alumni
        const certBox = document.getElementById('histPendCertBox');
        const certNumber = document.getElementById('histPendCertNumber');
        const certPredicate = document.getElementById('histPendCertPredicate');
        const certBadge = document.getElementById('histPendCertBadge');
        const certLink = document.getElementById('histPendCertLink');

        if (student.has_certificate) {
            certBox?.classList.remove('hidden');
            if (certNumber) certNumber.textContent = `No: ${student.certificate_number}`;
            if (certPredicate) certPredicate.textContent = `Predikat: ${student.certificate_predicate || 'Baik'}`;
            if (certBadge) certBadge.textContent = 'Resmi Diterbitkan';
            if (certLink) {
                certLink.href = student.certificate_url;
                certLink.classList.remove('pointer-events-none', 'opacity-50');
            }
        } else {
            if (certNumber) certNumber.textContent = 'Belum diterbitkan';
            if (certPredicate) certPredicate.textContent = 'Siswa belum memiliki sertifikat resmi.';
            if (certBadge) certBadge.textContent = 'Belum Terbit';
            if (certLink) {
                certLink.href = '#';
                certLink.classList.add('pointer-events-none', 'opacity-50');
            }
        }

        // Riwayat Pengunduran Diri
        const resignBox = document.getElementById('histPendResignationBox');
        const resignList = document.getElementById('histPendResignList');
        const resignBadge = document.getElementById('histPendResignBadge');

        if (student.resignation_history && student.resignation_history.length > 0) {
            resignBox?.classList.remove('hidden');
            resignList.innerHTML = '';
            student.resignation_history.forEach(ev => {
                const item = document.createElement('div');
                item.className = 'p-3 bg-white rounded-xl border border-rose-100 space-y-1.5 shadow-2xs';
                
                const isRejected = ev.action === 'rejected';
                const isApproved = ev.action === 'approved';
                const badgeCls = isApproved ? 'bg-emerald-100 text-emerald-800' : (isRejected ? 'bg-rose-100 text-rose-800' : 'bg-purple-100 text-purple-800');
                
                item.innerHTML = `
                    <div class="flex items-center justify-between flex-wrap gap-1">
                        <span class="font-bold text-gray-900 text-xs">${ev.status_label || (isApproved ? 'Pengunduran Diri Disetujui' : (isRejected ? 'Pengunduran Diri Ditolak Pegawai' : 'Permohonan Pengunduran Diri'))}</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full ${badgeCls}">${ev.created_at || '-'}</span>
                    </div>
                    ${ev.reason ? `<p class="text-[11px] text-gray-700"><strong>Alasan Peserta:</strong> "${ev.reason}"</p>` : ''}
                    ${ev.note ? `<div class="p-2 bg-rose-50/80 rounded-lg border border-rose-100 text-[11px] text-rose-900"><strong>Catatan Balasan Pegawai / Admin:</strong> "${ev.note}"</div>` : ''}
                    ${ev.proof ? `<div class="pt-0.5"><a href="${ev.proof}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-blue-600 hover:underline font-medium"><i class="fa-solid fa-paperclip"></i> Lihat Berkas Bukti</a></div>` : ''}
                `;
                resignList.appendChild(item);
            });
            if (resignBadge) {
                const latest = student.resignation_history[student.resignation_history.length - 1];
                resignBadge.textContent = latest.action === 'approved' ? 'Disetujui' : (latest.action === 'rejected' ? 'Ditolak' : 'Diajukan');
            }
        } else {
            resignBox?.classList.add('hidden');
        }

        if (typeof showModal === 'function') {
            showModal('historyPendaftaranModal');
        } else {
            document.getElementById('historyPendaftaranModal')?.classList.remove('hidden');
        }
    }

    function openHistoryPresensiModal(studentId) {
        const student = historyStudentsData.find(s => s.id === studentId);
        if (!student) return;

        document.getElementById('histAbsenName').textContent = `Riwayat Presensi: ${student.name}`;
        document.getElementById('histAbsenSub').textContent = `${student.division} · Periode ${student.start_date} s.d. ${student.end_date}`;

        document.getElementById('histAbsenTotalHari').textContent = `${student.stats.total_hari} Hari`;
        document.getElementById('histAbsenTotalHadir').textContent = `${student.stats.total_hadir} Hari`;
        document.getElementById('histAbsenTotalTelat').textContent = `${student.stats.total_telat} Hari`;
        document.getElementById('histAbsenTotalIzin').textContent = `${student.stats.total_izin} Hari`;

        const fullLink = document.getElementById('histAbsenFullLink');
        fullLink.href = `/pegawai/siswa/${student.id}/riwayat-absensi`;

        const tbody = document.getElementById('histAbsenTableBody');
        tbody.innerHTML = '';

        if (!student.attendances || student.attendances.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data presensi tercatat untuk siswa ini.</td></tr>`;
        } else {
            student.attendances.forEach(att => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50/70 transition';
                tr.innerHTML = `
                    <td class="px-4 py-2.5 font-medium text-gray-900">${att.tanggal}</td>
                    <td class="px-4 py-2.5 text-gray-600">${att.jam_masuk || '-'}</td>
                    <td class="px-4 py-2.5 text-gray-600">${att.jam_pulang || '-'}</td>
                    <td class="px-4 py-2.5 text-center">
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold ${att.status_kehadiran === 'Terlambat' ? 'bg-amber-100 text-amber-800' : (att.status_kehadiran === 'Hadir' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800')}">
                            ${att.status_kehadiran}
                        </span>
                    </td>
                    <td class="px-4 py-2.5 text-gray-500">${att.keterangan || '-'}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        if (typeof showModal === 'function') {
            showModal('historyPresensiModal');
        } else {
            document.getElementById('historyPresensiModal')?.classList.remove('hidden');
        }
    }

    function openHistoryLogbookModal(studentId) {
        const student = historyStudentsData.find(s => s.id === studentId);
        if (!student) return;

        document.getElementById('histLogbookName').textContent = `Riwayat Logbook: ${student.name}`;
        document.getElementById('histLogbookSub').textContent = `${student.division} · Total ${student.stats.total_logbook} hari logbook terisi`;

        // Monthly Progress List
        const progressList = document.getElementById('histMonthlyProgressList');
        progressList.innerHTML = '';
        if (!student.monthly_progresses || student.monthly_progresses.length === 0) {
            progressList.innerHTML = `<div class="col-span-2 p-3 bg-gray-50 rounded-xl text-center text-gray-400 text-xs">Belum ada berkas bulanan yang diunggah.</div>`;
        } else {
            student.monthly_progresses.forEach(mp => {
                const card = document.createElement('div');
                card.className = 'p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between text-xs';
                card.innerHTML = `
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf text-rose-500 text-base"></i>
                        <div>
                            <span class="font-bold text-gray-900 block">${mp.month_name}</span>
                            <span class="text-[10px] text-gray-400 truncate max-w-[150px] block">${mp.file_name}</span>
                        </div>
                    </div>
                    <a href="${mp.download_url}" target="_blank"
                        class="px-2.5 py-1 rounded-lg bg-white border border-gray-200 text-blue-600 hover:text-blue-800 text-[11px] font-semibold transition">
                        Buka PDF
                    </a>
                `;
                progressList.appendChild(card);
            });
        }

        // Daily Logbooks
        const tbody = document.getElementById('histLogbookTableBody');
        tbody.innerHTML = '';
        if (!student.logbooks || student.logbooks.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada catatan logbook harian tercatat.</td></tr>`;
        } else {
            student.logbooks.forEach(lb => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50/70 transition align-top';
                tr.innerHTML = `
                    <td class="px-4 py-2.5 font-medium text-gray-900 whitespace-nowrap">${lb.tanggal}</td>
                    <td class="px-4 py-2.5 text-gray-800 max-w-xs break-words">${lb.activity || '-'}</td>
                    <td class="px-4 py-2.5 text-gray-500 max-w-xs break-words">${lb.obstacle || '-'}</td>
                    <td class="px-4 py-2.5 text-center whitespace-nowrap">
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold ${lb.status === 'approved' ? 'bg-emerald-100 text-emerald-800' : (lb.status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800')}">
                            ${lb.status === 'approved' ? 'Disetujui' : (lb.status === 'rejected' ? 'Ditolak' : 'Menunggu')}
                        </span>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        if (typeof showModal === 'function') {
            showModal('historyLogbookModal');
        } else {
            document.getElementById('historyLogbookModal')?.classList.remove('hidden');
        }
    }
</script>
