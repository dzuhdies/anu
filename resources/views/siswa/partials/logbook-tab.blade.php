{{-- ================= TAB 3: LOGBOOK HARIAN ================= --}}
<div id="view-logbook" class="absen-view-tab space-y-5 animate-fadeIn" style="display: none;">
    <div class="w-full max-w-2xl mx-auto space-y-5">

        <!-- ================= KARTU 1: KALENDER LOGBOOK BULANAN (SAMA SEPERTI RIWAYAT) ================= -->
        <div class="card p-5 bg-white space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 flex-wrap gap-2">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Riwayat Logbook Kegiatan</h3>
                    <p class="text-xs text-gray-400">Klik tanggal pada kalender untuk melihat logbook</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="exportCurrentLogbookMonth()"
                        class="px-3 py-1.5 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-semibold flex items-center gap-1.5 transition shadow-xs active:scale-95"
                        title="Ekspor Seluruh Logbook Bulan Ini ke Template Cetak / Simpan PDF">
                        <i class="fa-solid fa-print text-xs"></i>
                        <span>Ekspor Bulan Ini</span>
                    </button>
                    <button type="button" onclick="refreshLogbookCalendar()"
                        class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-500 hover:text-black flex items-center justify-center transition"
                        title="Muat Ulang Kalender">
                        <i class="fa-solid fa-arrows-rotate text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Selector Bulan & Tahun -->
            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-xl border border-gray-100">
                <button type="button" onclick="prevLogbookMonth()"
                    class="p-1.5 px-2.5 rounded-lg hover:bg-white text-gray-500 hover:text-gray-900 transition text-xs"
                    title="Bulan Sebelumnya">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <span id="labelLogbookBulanTahun" class="font-bold text-gray-900 text-xs tracking-tight">Memuat Bulan...</span>
                <button type="button" onclick="nextLogbookMonth()"
                    class="p-1.5 px-2.5 rounded-lg hover:bg-white text-gray-500 hover:text-gray-900 transition text-xs"
                    title="Bulan Berikutnya">
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
                <div id="logbookCalendarMatrix" class="grid grid-cols-7 gap-y-2 gap-x-1 text-center text-xs font-medium min-h-[180px]">
                    <!-- Diisi secara dinamis via JS -->
                </div>
            </div>

            <!-- Legend Status Logbook -->
            <div class="flex items-center justify-center gap-4 pt-3 border-t border-gray-100 text-[10px] text-gray-500 flex-wrap">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Disetujui
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Menunggu Review
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Perlu Perbaikan
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-gray-300"></span> Belum Diisi
                </span>
            </div>
        </div>

        <!-- ================= KARTU 2: RINCIAN & PENGISIAN LOGBOOK TANGGAL TERPILIH ================= -->
        <div id="logbookDetailCard" class="card p-5 bg-white space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h4 class="text-xs font-bold text-gray-900">Rincian Logbook Tanggal Terpilih</h4>
                    <p id="logbookDetailDateFormatted" class="text-[11px] text-gray-400 mt-0.5">-</p>
                </div>
                <span id="logbookDetailStatusBadge" class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600">
                    Pilih Tanggal
                </span>
            </div>

            <!-- State Loading Saat Mengambil Detail dari Backend -->
            <div id="logbookDetailLoading" class="hidden py-8 flex flex-col items-center justify-center space-y-2 text-gray-400">
                <div class="w-6 h-6 border-2 border-gray-300 border-t-gray-900 rounded-full animate-spin"></div>
                <span class="text-xs font-medium">Memuat rincian logbook dari server...</span>
            </div>

            <!-- Kontainer Konten Logbook (Tampil setelah data berhasil dimuat) -->
            <div id="logbookDetailBody" class="space-y-4">

                <!-- Alert Penolakan / Catatan Review (Jika Ditolak) -->
                <div id="logbookAlertRejected" class="hidden p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 space-y-1">
                    <strong class="flex items-center gap-1.5 font-bold">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>Logbook Perlu Diperbaiki</span>
                    </strong>
                    <p id="logbookRejectedNote" class="text-rose-700 text-[11px] leading-relaxed">-</p>
                </div>

                <!-- Alert Persetujuan (Jika Disetujui) -->
                <div id="logbookAlertApproved" class="hidden p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-start gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-sm"></i>
                    <div>
                        <strong class="font-bold block">Logbook Telah Disetujui</strong>
                        <span id="logbookApprovedMeta" class="text-[11px] text-emerald-700 block mt-0.5">-</span>
                    </div>
                </div>

                <!-- Alert Penguncian / Informasi (Jika Terkunci / Belum Tersedia / Luar Periode) -->
                <div id="logbookAlertLocked" class="hidden p-3 bg-amber-50 border border-amber-200 rounded-xl text-[11px] text-amber-800 flex items-start gap-2">
                    <i class="fa-solid fa-lock text-amber-600 mt-0.5"></i>
                    <span id="logbookLockedReason">-</span>
                </div>

                <!-- Bagian 1: Tampilan Baca (Readonly Mode) -->
                <div id="logbookReadonlySection" class="hidden space-y-3">
                    <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-[10px] uppercase font-semibold text-gray-400 block tracking-wider">Uraian Kegiatan</span>
                        <p id="logbookReadonlyActivity" class="text-xs text-gray-900 whitespace-pre-line mt-1.5 leading-relaxed font-medium">-</p>
                    </div>

                    <div id="logbookReadonlyResultWrapper" class="p-3.5 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-[10px] uppercase font-semibold text-gray-400 block tracking-wider">Hasil / Output</span>
                        <p id="logbookReadonlyResult" class="text-xs text-gray-900 whitespace-pre-line mt-1.5 leading-relaxed font-medium">-</p>
                    </div>

                    <div id="logbookEditActionWrapper" class="hidden pt-1 flex justify-end">
                        <button type="button" onclick="showLogbookFormMode()"
                            class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs active:scale-95">
                            <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                            <span>Ubah Catatan Logbook</span>
                        </button>
                    </div>
                </div>

                <!-- Bagian 2: Form Pengisian / Pengeditan Logbook -->
                <div id="logbookFormSection" class="hidden">
                    <form id="formSubmitLogbook" method="POST" action="{{ route('siswa.logbook.store', [], false) }}" class="space-y-3.5">
                        @csrf
                        <input type="hidden" name="date" id="logbookFormDateInput" value="">

                        <div>
                            <label for="logbookInputActivity" class="block text-xs font-semibold text-gray-700 mb-1">
                                Uraian Kegiatan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="activity" id="logbookInputActivity" rows="5" required maxlength="2000"
                                placeholder="Tuliskan pekerjaan / kegiatan yang Anda kerjakan pada tanggal ini secara jelas..."
                                class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 resize-y leading-relaxed bg-white"></textarea>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-[10px] text-gray-400">Minimal 3 karakter.</span>
                                <span id="logbookActivityCounter" class="text-[10px] text-gray-400 font-mono">0/2000</span>
                            </div>
                        </div>

                        <div>
                            <label for="logbookInputResult" class="block text-xs font-semibold text-gray-700 mb-1">
                                Hasil / Output <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <textarea name="result" id="logbookInputResult" rows="3" maxlength="2000"
                                placeholder="Contoh: laporan selesai, data sudah diinput, dokumen sudah direkap..."
                                class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 resize-y leading-relaxed bg-white"></textarea>
                            <div class="flex items-center justify-end mt-1">
                                <span id="logbookResultCounter" class="text-[10px] text-gray-400 font-mono">0/2000</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pt-3 border-t border-gray-100">
                            <p class="text-[10px] text-gray-400 flex items-center gap-1.5">
                                <i class="fa-solid fa-lock text-[9px]"></i>
                                <span>Logbook dapat diisi pada hari-H dan H+1</span>
                            </p>
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" id="btnCancelLogbookEdit" onclick="cancelLogbookFormMode()"
                                    class="hidden px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-medium transition">
                                    Batal
                                </button>
                                <button type="submit" id="btnSubmitLogbookForm"
                                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-bold transition flex items-center justify-center gap-2 sh         ow-md active:scale-95">
                                    <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                                    <span id="btnSubmitLogbookLabel">Simpan Logbook</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Bagian 3: Tampilan Kosong Jika Belum Ada Data & Terkunci -->
                <div id="logbookEmptyLockedSection" class="hidden py-8 text-center space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto text-lg">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h5 class="text-xs font-bold text-gray-700">Tidak Ada Catatan Logbook</h5>
                    <p id="logbookEmptyLockedReason" class="text-[11px] text-gray-400 max-w-sm mx-auto leading-relaxed">
                        Belum ada catatan logbook yang diisi untuk tanggal ini.
                    </p>
                </div>

            </div>
        </div>

    </div>
</div>
