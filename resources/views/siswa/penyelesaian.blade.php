<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penyelesaian Magang & Pengumpulan Berkas - BPS Kota Malang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="text-gray-800 min-h-screen flex flex-col justify-between">

    <!-- Top Navbar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30 px-4 sm:px-8 py-3.5 flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-3">
            <img src="{{ asset('logo-bps-malang.png') }}" alt="BPS Kota Malang" class="h-9 w-auto object-contain">
            <div class="hidden sm:block border-l border-gray-200 pl-3">
                <span class="text-xs font-bold text-gray-900 block leading-tight">Portal Penyelesaian Magang</span>
                <span class="text-[11px] text-teal-700 font-medium">BPS Kota Malang</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right">
                <div class="text-xs font-bold text-gray-900 leading-tight">{{ $nama ?? ($siswaProfile->full_name ?? 'Peserta') }}</div>
                <div class="text-[10px] text-gray-500 font-medium">{{ $siswaProfile->division->nama_divisi ?? 'Peserta Magang' }}</div>
            </div>
            <div class="w-8 h-8 rounded-full bg-teal-700 text-white font-bold text-xs flex items-center justify-center shrink-0">
                {{ strtoupper(substr($nama ?? ($siswaProfile->full_name ?? 'P'), 0, 1)) }}
            </div>
            @if(isset($application) && $application && $application->status === 'approved')
                <button type="button" onclick="openModalUndurDiriPenyelesaian()" class="px-2.5 py-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-semibold transition flex items-center gap-1 shadow-2xs" title="Mengundurkan diri dari program magang">
                    <i class="fa-solid fa-user-xmark text-[10px]"></i>
                    <span class="hidden sm:inline">Undur Diri</span>
                </button>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" title="Keluar" class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 text-gray-500 flex items-center justify-center transition">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-5xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- Flash Alert -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl text-xs font-medium flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('error') || (isset($errors) && $errors->any()))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-xl text-xs font-medium flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <div>
                        @if(session('error')) <div>{{ session('error') }}</div> @endif
                        @if(isset($errors))
                            @foreach ($errors->all() as $err) <div>{{ $err }}</div> @endforeach
                        @endif
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Status Box: Masa Magang Selesai -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-base font-bold text-gray-900">Periode Magang Telah Selesai</h2>
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800">
                            Tahap Penyelesaian Berkas
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed max-w-2xl">
                        Masa kegiatan magang Anda telah berakhir pada <strong>{{ $siswaProfile->end_date ? \Carbon\Carbon::parse($siswaProfile->end_date)->translatedFormat('d F Y') : '-' }}</strong>. 
                        Halaman presensi harian sudah dinonaktifkan. Untuk menerbitkan <strong>Sertifikat Magang</strong> resmi, 
                        mohon pastikan seluruh <strong>Berkas Progres Bulanan</strong> telah lengkap dan disetujui (ACC) oleh ketua divisi/pegawai BPS.
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row md:flex-col items-start md:items-end gap-2 shrink-0 border-t md:border-t-0 pt-3 md:pt-0 border-gray-100">
                <div class="text-xs text-gray-500 text-left md:text-right">
                    Progress Berkas: <strong class="text-gray-900">{{ $progressData['total_approved'] ?? 0 }}/{{ $progressData['total_months'] ?? 0 }} Bulan Di-ACC</strong>
                </div>
                <div class="w-full md:w-36 h-2 bg-gray-100 rounded-full overflow-hidden">
                    @php
                        $pct = ($progressData['total_months'] ?? 0) > 0 ? round((($progressData['total_approved'] ?? 0) / $progressData['total_months']) * 100) : 0;
                    @endphp
                    <div class="h-full bg-teal-600 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>

        <!-- Tab Selection (Berkas Progres & Riwayat Logbook) -->
        <div class="flex items-center border-b border-gray-200 gap-2">
            <button type="button" onclick="switchPenyelesaianTab('progres')" id="tabBtnProgres"
                class="px-4 py-2.5 text-xs font-bold border-b-2 border-teal-600 text-teal-700 flex items-center gap-2 transition">
                <i class="fa-solid fa-file-circle-check"></i>
                <span>Berkas Progres Bulanan</span>
                <span class="px-1.5 py-0.2 rounded-full bg-teal-100 text-teal-800 text-[10px]">
                    {{ $progressData['total_approved'] ?? 0 }}/{{ $progressData['total_months'] ?? 0 }}
                </span>
            </button>
            <button type="button" onclick="switchPenyelesaianTab('logbook')" id="tabBtnLogbook"
                class="px-4 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-800 flex items-center gap-2 transition">
                <i class="fa-solid fa-book-journal-whills"></i>
                <span>Riwayat Logbook Kegiatan</span>
                <span class="px-1.5 py-0.2 rounded-full bg-gray-100 text-gray-600 text-[10px]">
                    {{ count($logbooks ?? []) }}
                </span>
            </button>
        </div>

        <!-- ================= TAB 1: BERKAS PROGRES BULANAN ================= -->
        <div id="sectionProgres" class="space-y-4">
            <div class="flex items-center justify-between gap-3 flex-wrap bg-white p-4 rounded-xl border border-gray-200">
                <div class="text-xs text-gray-600">
                    <span class="font-bold text-gray-900 block">Panduan Pengumpulan Berkas Logbook Bulanan:</span>
                    Pastikan logbook dicetak/diekspor dengan template resmi, ditandatangani oleh ketua divisi, lalu di-scan menjadi file PDF.
                </div>
                <a href="{{ route('siswa.logbook.export', ['date' => now()->toDateString()]) }}" target="_blank"
                    class="px-3.5 py-2 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-semibold flex items-center gap-1.5 transition shadow-xs">
                    <i class="fa-solid fa-file-pdf text-rose-400"></i>
                    <span>Download / Cetak Template Logbook</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($progressData['list'] ?? [] as $m)
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 space-y-4 shadow-xs">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Bulan Ke-{{ $m['index'] }}</span>
                                <h4 class="text-sm font-bold text-gray-900">{{ $m['month_name'] }}</h4>
                            </div>
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $m['status_badge'] }}">
                                {{ $m['status_label'] }}
                            </span>
                        </div>

                        <!-- Feedback dari Pegawai jika ada -->
                        @if($m['feedback'])
                            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-1">
                                <strong class="flex items-center gap-1 text-[11px] text-rose-900">
                                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i> Catatan Perbaikan Pegawai:
                                </strong>
                                <p class="text-[11px] leading-relaxed">{{ $m['feedback'] }}</p>
                            </div>
                        @endif

                        <!-- File Info -->
                        @if($m['has_uploaded'])
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2 truncate pr-2">
                                    <i class="fa-solid fa-file-pdf text-rose-500 text-base"></i>
                                    <span class="font-medium text-gray-700 truncate text-[11px]">{{ $m['file_name'] }}</span>
                                </div>
                                <a href="{{ route('siswa.progres.download', $m['progress_id']) }}" target="_blank"
                                    class="text-teal-700 hover:text-teal-900 font-bold text-xs shrink-0 flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-down"></i>
                                    <span>Buka PDF</span>
                                </a>
                            </div>
                        @else
                            <div class="p-3 rounded-xl bg-gray-50 border border-dashed border-gray-200 text-center text-xs text-gray-400">
                                Belum ada berkas yang diunggah untuk bulan ini.
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[10px] text-gray-400">
                                {{ $m['has_uploaded'] ? ($m['uploaded_at'] ? 'Diperbarui: ' . $m['uploaded_at'] : 'Sudah diunggah') : 'Wajib diisi' }}
                            </span>

                            @if($m['status'] === 'approved')
                                <span class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check"></i> Sudah Di-ACC
                                </span>
                            @else
                                <button type="button" onclick="openModalUploadProgres({{ $m['year'] }}, {{ $m['month'] }}, '{{ $m['month_name'] }}', {{ $m['has_uploaded'] ? 'true' : 'false' }})"
                                    class="px-3.5 py-1.5 rounded-lg {{ $m['has_uploaded'] ? 'border border-amber-300 bg-amber-50 text-amber-800 hover:bg-amber-100' : 'bg-teal-600 hover:bg-teal-700 text-white' }} text-xs font-bold transition flex items-center gap-1.5">
                                    <i class="fa-solid {{ $m['has_uploaded'] ? 'fa-pen-to-square' : 'fa-upload' }}"></i>
                                    <span>{{ $m['has_uploaded'] ? 'Unggah Ulang Berkas' : 'Unggah Berkas TTD' }}</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 p-8 text-center text-gray-400 bg-white rounded-2xl border border-gray-200">
                        Tidak ada data periode magang yang terdaftar.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ================= TAB 2: RIWAYAT LOGBOOK KEGIATAN ================= -->
        <div id="sectionLogbook" class="space-y-4 hidden">
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-xs">
                <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/50">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-book-journal-whills text-teal-600"></i>
                            <span>Catatan Logbook Harian yang Telah Diisi</span>
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar rekaman aktivitas kegiatan magang harian Anda di BPS Kota Malang.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('siswa.logbook.export', ['date' => now()->toDateString()]) }}" target="_blank"
                            class="px-3.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-print text-gray-500"></i>
                            <span>Cetak Riwayat Logbook</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left text-gray-600">
                        <thead class="text-[11px] text-gray-400 uppercase bg-gray-50/70 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-5 py-3">Tanggal</th>
                                <th scope="col" class="px-5 py-3">Kegiatan / Aktivitas</th>
                                <th scope="col" class="px-5 py-3">Kendala / Hambatan</th>
                                <th scope="col" class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($logbooks ?? [] as $lb)
                                <tr class="hover:bg-gray-50/60 transition align-top">
                                    <td class="px-5 py-3.5 font-semibold text-gray-900 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($lb->date)->translatedFormat('l, d F Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-800 leading-relaxed max-w-md break-words">
                                        {{ $lb->activity ?: '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-500 max-w-xs break-words">
                                        {{ $lb->obstacle ?: '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $lb->status_badge ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $lb->status === 'approved' ? 'Disetujui' : ($lb->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-gray-400">
                                        Belum ada rekaman logbook harian yang tersimpan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- Modal Upload Berkas Progres -->
    <div id="modalUploadProgresPenyelesaian" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-gray-100">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Unggah Berkas Progres Bulanan</h3>
                        <p class="text-[11px] text-gray-400" id="modalUploadSubtitle">Periode Bulan</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalUploadProgres()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-xs transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('siswa.progres.upload') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="year" id="uploadYearInput">
                <input type="hidden" name="month" id="uploadMonthInput">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Pilih File Berkas Logbook TTD (PDF / Foto) <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required
                        class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-200 rounded-xl p-1.5 focus:outline-none focus:border-teal-600">
                    <span class="text-[10px] text-gray-400 block mt-1">Maksimal ukuran file 10MB (disarankan PDF).</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Catatan untuk Pegawai / Reviewer (Opsional)
                    </label>
                    <textarea name="notes" rows="2" placeholder="Tuliskan catatan tambahan jika ada..."
                        class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-teal-600 resize-none leading-relaxed"></textarea>
                </div>

                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-[11px] text-amber-900 leading-relaxed">
                    <i class="fa-solid fa-circle-info text-amber-600 mr-1"></i>
                    Jika mengunggah ulang berkas, status verifikasi akan kembali menjadi <strong>Menunggu Verifikasi</strong> untuk dicek ulang oleh pegawai.
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeModalUploadProgres()"
                        class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Kirim Berkas</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4 px-4 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} Badan Pusat Statistik Kota Malang. Portal Resmi Magang & Praktik Kerja Lapangan.
    </footer>

    <script>
        function switchPenyelesaianTab(tab) {
            const secProgres = document.getElementById('sectionProgres');
            const secLogbook = document.getElementById('sectionLogbook');
            const btnProgres = document.getElementById('tabBtnProgres');
            const btnLogbook = document.getElementById('tabBtnLogbook');

            if (tab === 'progres') {
                secProgres.classList.remove('hidden');
                secLogbook.classList.add('hidden');
                btnProgres.className = 'px-4 py-2.5 text-xs font-bold border-b-2 border-teal-600 text-teal-700 flex items-center gap-2 transition';
                btnLogbook.className = 'px-4 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-800 flex items-center gap-2 transition';
            } else {
                secProgres.classList.add('hidden');
                secLogbook.classList.remove('hidden');
                btnLogbook.className = 'px-4 py-2.5 text-xs font-bold border-b-2 border-teal-600 text-teal-700 flex items-center gap-2 transition';
                btnProgres.className = 'px-4 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-800 flex items-center gap-2 transition';
            }
        }

        function openModalUploadProgres(year, month, monthName, isReupload) {
            document.getElementById('uploadYearInput').value = year;
            document.getElementById('uploadMonthInput').value = month;
            document.getElementById('modalUploadSubtitle').textContent = `Periode: ${monthName} ${isReupload ? '(Unggah Ulang)' : ''}`;
            document.getElementById('modalUploadProgresPenyelesaian').classList.remove('hidden');
        }

        function closeModalUploadProgres() {
            document.getElementById('modalUploadProgresPenyelesaian').classList.add('hidden');
        }

        function openModalUndurDiriPenyelesaian() {
            document.getElementById('modalUndurDiriPenyelesaian')?.classList.remove('hidden');
        }

        function closeModalUndurDiriPenyelesaian() {
            document.getElementById('modalUndurDiriPenyelesaian')?.classList.add('hidden');
        }
    </script>

    <!-- Modal Pengunduran Diri di Halaman Penyelesaian -->
    @if(isset($application) && $application)
        <div id="modalUndurDiriPenyelesaian" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 animate-scaleUp border border-gray-100">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-bold border border-rose-200">
                            <i class="fa-solid fa-user-xmark"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Pengajuan Pengunduran Diri</h3>
                            <p class="text-[11px] text-gray-400">Program Magang BPS Kota Malang</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModalUndurDiriPenyelesaian()" class="text-gray-400 hover:text-black text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('pendaftaran.undur_diri', $application->id) }}" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                    @csrf
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-[11px] leading-relaxed flex items-start gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 shrink-0"></i>
                        <span><strong>Peringatan:</strong> Pengunduran diri yang diajukan akan diverifikasi pihak BPS. Peserta yang mengundurkan diri <strong>tidak memperoleh sertifikat magang</strong> dan statusnya dicatat di riwayat.</span>
                    </div>

                    <div class="space-y-1">
                        <label class="font-bold text-gray-700 block text-[11px]">Alasan Pengunduran Diri <span class="text-rose-500">*</span></label>
                        <textarea name="alasan_undur_diri" rows="3" required placeholder="Jelaskan alasan pengunduran diri Anda..." class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900 leading-relaxed"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="font-bold text-gray-700 block text-[11px]">Berkas Bukti / Surat Resmi Permohonan <span class="text-rose-500">*</span></label>
                        <input type="file" name="bukti_undur_diri" accept=".pdf,.jpg,.jpeg,.png" required class="w-full p-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:outline-none focus:border-gray-900 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-black">
                        <span class="text-[10px] text-gray-400 block">Format: PDF, JPG, PNG (Maksimal 5MB)</span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" onclick="closeModalUndurDiriPenyelesaian()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs transition">
                            Batal
                        </button>
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengajukan pengunduran diri?');" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-xs">
                            <i class="fa-solid fa-paper-plane text-[11px]"></i>
                            <span>Kirim Pengajuan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</body>
</html>
