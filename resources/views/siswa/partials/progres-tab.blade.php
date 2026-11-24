{{-- ================= TAB 4: PROGRES BERKAS BULANAN ================= --}}
<div id="view-progres" class="absen-view-tab space-y-5 animate-fadeIn" style="display: none;">
    <div class="w-full max-w-2xl mx-auto space-y-5">

        <!-- ================= KARTU UTAMA: DAFTAR PROGRES BERKAS BULANAN ================= -->
        <div class="card p-5 bg-white space-y-4">
            <div class="flex items-start justify-between pb-3 border-b border-gray-100 gap-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-orange-500"></i>
                        <span>Progres Berkas Magang Bulanan</span>
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Unggah berkas logbook yang telah ditandatangani Ketua Divisi setiap bulannya untuk memenuhi syarat magang.
                    </p>
                </div>
                <button type="button" onclick="loadProgresData()"
                    class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-500 hover:text-black flex items-center justify-center transition shrink-0"
                    title="Segarkan Data Progres">
                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                </button>
            </div>

            <!-- Ringkasan Progres -->
            <div class="p-3.5 bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl border border-orange-100 flex items-center justify-between text-xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-orange-500 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <div>
                        <span class="font-bold text-gray-900 block" id="progresSummaryTitle">Ringkasan Berkas Bulanan</span>
                        <span class="text-[11px] text-gray-500" id="progresSummarySubtitle">
                            {{ $progressData['total_approved'] ?? 0 }} dari {{ $progressData['total_months'] ?? 0 }} berkas telah disetujui (ACC)
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase font-bold text-orange-600 block tracking-wider">Status Berkas</span>
                    <span class="text-xs font-bold text-gray-800" id="progresSummaryRatio">
                        {{ ($progressData['total_months'] ?? 0) > 0 && ($progressData['total_approved'] ?? 0) === ($progressData['total_months'] ?? 0) ? 'Lengkap (ACC)' : 'Dalam Proses' }}
                    </span>
                </div>
            </div>

            <!-- Daftar List Bulan (Rendered via Blade / Refreshed via JS) -->
            <div id="progresListContainer" class="space-y-3 pt-1">
                @if(isset($progressData['months']) && count($progressData['months']) > 0)
                    @foreach($progressData['months'] as $m)
                        <div class="p-4 rounded-xl border border-gray-100 hover:border-gray-200 transition bg-white shadow-xs space-y-3" id="progres-card-{{ $m['year'] }}-{{ $m['month'] }}">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-700 font-bold text-[11px] flex items-center justify-center shrink-0">
                                        {{ $m['index'] }}
                                    </span>
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900">{{ $m['month_name'] }}</h4>
                                        <span class="text-[10px] text-gray-400">Bulan ke-{{ $m['index'] }} Masa Magang</span>
                                    </div>
                                </div>

                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border {{ $m['status_badge'] }}">
                                    {{ $m['status_label'] }}
                                </span>
                            </div>

                            {{-- Alert Catatan Revisi dari Pegawai (Jika ditolak/perlu perbaikan) --}}
                            @if($m['status'] === 'rejected' && !empty($m['feedback']))
                                <div class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800 space-y-1">
                                    <strong class="flex items-center gap-1.5 font-bold">
                                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                                        <span>Catatan Perbaikan Pegawai:</span>
                                    </strong>
                                    <p class="text-rose-700 text-[11px] leading-relaxed">{{ $m['feedback'] }}</p>
                                </div>
                            @endif

                            {{-- Informasi Berkas yang Telah Diunggah --}}
                            @if($m['has_uploaded'])
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-between text-xs gap-3">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <i class="fa-solid fa-file-pdf text-rose-500 text-base shrink-0"></i>
                                        <div class="truncate">
                                            <span class="font-semibold text-gray-800 block truncate text-xs">{{ $m['file_name'] }}</span>
                                            <span class="text-[10px] text-gray-400 block">{{ $m['file_size_formatted'] }} · Diunggah: {{ $m['uploaded_at'] }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ $m['file_url'] }}" target="_blank"
                                        class="px-2.5 py-1 rounded-lg bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-[11px] font-semibold transition shrink-0 flex items-center gap-1 shadow-2xs">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        <span>Lihat</span>
                                    </a>
                                </div>

                                @if(!empty($m['notes']))
                                    <p class="text-[11px] text-gray-500 italic px-1">
                                        "{{ $m['notes'] }}"
                                    </p>
                                @endif
                            @endif

                            {{-- Tombol Aksi (Upload Baru / Upload Ulang) --}}
                            <div class="flex items-center justify-between pt-1 border-t border-gray-50">
                                <span class="text-[10px] text-gray-400">
                                    @if($m['status'] === 'approved')
                                        <i class="fa-solid fa-circle-check text-emerald-500"></i> Berkas telah diverifikasi & disetujui.
                                    @elseif($m['status'] === 'pending')
                                        <i class="fa-solid fa-clock text-amber-500"></i> Menunggu pengecekan oleh pegawai.
                                    @elseif($m['status'] === 'rejected')
                                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Perlu perbaikan berkas.
                                    @else
                                        <i class="fa-solid fa-circle-info text-gray-400"></i> Silakan upload berkas bertandatangan.
                                    @endif
                                </span>

                                <div>
                                    @if(!$m['has_uploaded'])
                                        <button type="button"
                                            onclick="openUploadProgresModal({{ $m['year'] }}, {{ $m['month'] }}, '{{ addslashes($m['month_name']) }}', false)"
                                            class="px-3.5 py-1.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs active:scale-95">
                                            <i class="fa-solid fa-cloud-arrow-up text-[11px]"></i>
                                            <span>Upload Berkas</span>
                                        </button>
                                    @else
                                        <button type="button"
                                            onclick="openUploadProgresModal({{ $m['year'] }}, {{ $m['month'] }}, '{{ addslashes($m['month_name']) }}', true, '{{ $m['status'] }}')"
                                            class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold transition flex items-center gap-1.5 active:scale-95">
                                            <i class="fa-solid fa-arrow-rotate-right text-[11px]"></i>
                                            <span>Upload Ulang</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="py-10 text-center text-gray-400 space-y-2">
                        <i class="fa-solid fa-calendar-xmark text-3xl text-gray-300"></i>
                        <h5 class="text-xs font-bold text-gray-600">Periode Magang Belum Ditetapkan</h5>
                        <p class="text-[11px] text-gray-400 max-w-sm mx-auto">
                            Tanggal mulai dan selesai magang belum diatur oleh admin/pegawai sehingga daftar bulan progres belum dapat ditampilkan.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Petunjuk Pengunggahan Berkas -->
            <div class="p-3 bg-blue-50/60 border border-blue-100 rounded-xl text-xs text-blue-900 space-y-1.5">
                <span class="font-bold flex items-center gap-1.5 text-blue-800">
                    <i class="fa-solid fa-circle-info text-blue-600"></i>
                    Petunjuk Pengunggahan Berkas Magang
                </span>
                <ol class="list-decimal list-inside text-[11px] text-blue-800/90 space-y-0.5 leading-relaxed">
                    <li>Gunakan tombol <strong>Ekspor Bulan Ini</strong> di tab Logbook untuk mengunduh catatan kegiatan.</li>
                    <li>Cetak dokumen dan mintakan tanda tangan serta persetujuan dari <strong>Ketua Divisi</strong> atau pembimbing Anda.</li>
                    <li>Pindai (scan) atau foto dokumen yang sudah ditandatangani dalam format <strong>PDF / Dokumen / Gambar</strong> (maks 10 MB).</li>
                    <li>Unggah berkas pada daftar bulan yang bersangkutan di halaman ini.</li>
                    <li>Jika Anda mengedit / mengunggah ulang berkas, status ACC sebelumnya akan direset dan menunggu verifikasi ulang oleh pegawai.</li>
                </ol>
            </div>

        </div>

    </div>
</div>

<!-- ================= POP-UP MODAL UPLOAD BERKAS PROGRES ================= -->
<div id="modalUploadProgres" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 animate-scaleUp border border-gray-100">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-file-arrow-up"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm" id="modalProgresTitle">Upload Berkas Logbook</h3>
                    <p class="text-[11px] text-gray-400" id="modalProgresSubtitle">Pilih berkas bertandatangan</p>
                </div>
            </div>
            <button type="button" onclick="closeUploadProgresModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="formUploadProgres" method="POST" action="{{ route('siswa.progres.upload') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="year" id="progresInputYear" value="">
            <input type="hidden" name="month" id="progresInputMonth" value="">

            {{-- Peringatan Reset ACC jika upload ulang --}}
            <div id="modalProgresReuploadAlert" class="hidden p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-start gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 shrink-0"></i>
                <div class="text-[11px] leading-relaxed">
                    <strong class="font-bold block">Pemberitahuan Upload Ulang:</strong>
                    Mengunggah ulang berkas baru akan menggantikan file sebelumnya dan <strong>mereset status persetujuan (ACC)</strong> menjadi menunggu verifikasi pegawai kembali.
                </div>
            </div>

            <div>
                <label for="progresFileInput" class="block text-xs font-semibold text-gray-700 mb-1">
                    File Berkas Logbook (TTD Ketua Divisi) <span class="text-rose-500">*</span>
                </label>
                <input type="file" name="file" id="progresFileInput" required
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                    class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-black file:cursor-pointer border border-gray-200 rounded-xl p-2 bg-gray-50/50">
                <span class="text-[10px] text-gray-400 block mt-1">Format: PDF, DOC, DOCX, JPG, PNG (Maksimal 10 MB)</span>
            </div>

            <div>
                <label for="progresNotesInput" class="block text-xs font-semibold text-gray-700 mb-1">
                    Catatan Tambahan <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea name="notes" id="progresNotesInput" rows="2" maxlength="1000"
                    placeholder="Tuliskan catatan tambahan jika ada..."
                    class="w-full text-xs p-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 resize-none bg-white"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                <button type="button" onclick="closeUploadProgresModal()"
                    class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-medium transition">
                    Batal
                </button>
                <button type="submit" id="btnSubmitUploadProgres"
                    class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-md active:scale-95">
                    <i class="fa-solid fa-cloud-arrow-up text-[11px]"></i>
                    <span id="btnSubmitProgresLabel">Unggah Berkas</span>
                </button>
            </div>
        </form>
    </div>
</div>
