<!-- ================= TAB LOGBOOK (embedded in pegawai absensi) ================= -->
@if (!($logbookReviewEnabled ?? true))
<div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl text-[11px] text-amber-800">
    <i class="fa-solid fa-circle-info mr-1"></i>
    Review logbook sedang dinonaktifkan. Logbook siswa otomatis disetujui tanpa review.
</div>
@endif

<!-- Sub-tab Selector: Logbook Harian vs Berkas Progres Bulanan -->
<div class="flex items-center gap-2 mb-5 p-1 bg-gray-100 rounded-xl w-fit">
    <button type="button" onclick="switchPegawaiLogbookSubtab('harian')" id="btn-subtab-harian"
        class="px-4 py-2 rounded-lg text-xs font-bold transition shadow-xs bg-white text-gray-900">
        <i class="fa-solid fa-book-open mr-1.5 text-orange-500"></i>
        <span>Logbook Harian</span>
        @if(($logbookPendingCount ?? 0) > 0)
            <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-[10px] bg-amber-500 text-white font-bold">{{ $logbookPendingCount }}</span>
        @endif
    </button>
    <button type="button" onclick="switchPegawaiLogbookSubtab('progres')" id="btn-subtab-progres"
        class="px-4 py-2 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition">
        <i class="fa-solid fa-file-circle-check mr-1.5 text-teal-600"></i>
        <span>Berkas Progres Bulanan (TTD)</span>
        @if(($monthlyProgressPendingCount ?? 0) > 0)
            <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-[10px] bg-rose-500 text-white font-bold">{{ $monthlyProgressPendingCount }}</span>
        @endif
    </button>
</div>

<div id="pegawai-logbook-harian-wrapper">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

    <!-- Left Column: Kalender & Filter -->
    <div class="lg:col-span-4 space-y-5">
        <!-- Card Mini Calendar -->
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-900"></span>
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Kalender Logbook</h3>
                </div>
                <span class="text-[10px] font-semibold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Pilih Tanggal</span>
            </div>
            <div class="flex items-center justify-between mb-3 px-1">
                <button type="button" onclick="changeLogbookCalMonth(-1)" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-500 flex items-center justify-center transition">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <span id="logbookCalMonthTitle" class="text-xs font-bold text-gray-800">
                    {{ \Carbon\Carbon::today()->translatedFormat('F Y') }}
                </span>
                <button type="button" onclick="changeLogbookCalMonth(1)" class="w-7 h-7 rounded-lg hover:bg-gray-100 text-gray-500 flex items-center justify-center transition">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-gray-400 uppercase mb-2">
                <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span>
                <span class="text-rose-500">Sab</span><span class="text-rose-500">Min</span>
            </div>
            <div id="logbookCalMatrix" class="grid grid-cols-7 gap-1 text-center text-xs">
                <!-- filled by JS -->
            </div>
        </div>

        <!-- Card Filter -->
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-teal-600"></i>
                    <span>Filter Logbook Siswa</span>
                </h3>
                @if(request('status') || request('tanggal') || request('q'))
                    <a href="{{ route('pegawai.absensi') }}?tab=logbook" class="text-[11px] font-medium text-rose-600 hover:underline">Reset</a>
                @endif
            </div>

            <form id="logbookFilterForm" action="{{ route('pegawai.absensi') }}?tab=logbook" method="GET" class="space-y-3.5 text-xs">
                <input type="hidden" name="tab" value="logbook">

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 mr-1"></i> Cari Siswa
                    </label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama siswa / asal instansi..."
                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-white font-medium text-gray-800 shadow-2xs">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">
                        <i class="fa-solid fa-circle-check text-gray-400 mr-1"></i> Status
                    </label>
                    <select name="status" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-white font-medium text-gray-800 shadow-2xs">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="space-y-2.5">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">
                            <i class="fa-regular fa-calendar text-gray-400 mr-1"></i> Dari Tanggal
                        </label>
                        <input type="date" name="dari" id="inputLogbookDari" value="{{ request('dari', request('tanggal')) }}"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-white font-medium text-gray-800 shadow-2xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">
                            <i class="fa-regular fa-calendar-check text-gray-400 mr-1"></i> Sampai Tanggal
                        </label>
                        <input type="date" name="ke" id="inputLogbookKe" value="{{ request('ke', request('tanggal')) }}"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-white font-medium text-gray-800 shadow-2xs">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-0.5">
                    <button type="button" onclick="setLogbookFilterPreset('today')" class="py-1.5 px-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-[11px] transition text-center">
                        Hari Ini
                    </button>
                    <button type="button" onclick="setLogbookFilterPreset('this_month')" class="py-1.5 px-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-[11px] transition text-center">
                        Bulan Ini
                    </button>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-lg bg-gray-900 hover:bg-black text-white font-semibold text-xs transition shadow-xs flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                    <span>Terapkan Filter</span>
                </button>
            </form>

            @if(request('dari') || request('ke') || request('status') || request('q'))
                <div class="mt-3 text-center">
                    <a href="{{ route('pegawai.absensi') }}?tab=logbook" class="text-[11px] font-medium text-rose-600 hover:underline">Reset Filter</a>
                </div>
            @endif

            <!-- Ringkasan -->
            <!--<div class="card p-5 space-y-2">
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-2">Ringkasan</h3>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500">Total</span>
                    <span class="font-bold text-gray-900">{{ $logbookStats['total'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-amber-600 font-semibold">Menunggu</span>
                    <span class="font-bold text-amber-700">{{ $logbookStats['pending'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-emerald-600 font-semibold">Disetujui</span>
                    <span class="font-bold text-emerald-700">{{ $logbookStats['approved'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-rose-600 font-semibold">Ditolak</span>
                    <span class="font-bold text-rose-700">{{ $logbookStats['rejected'] ?? 0 }}</span>
                </div>
            </div>-->
        </div>
    </div>

    <!-- Right Column: Table -->
    <div class="lg:col-span-8 card overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-[#fafafa]">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Daftar Logbook Siswa</h3>
                <p class="text-[11px] text-gray-400">Setujui atau tolak logbook harian peserta magang.</p>
            </div>
            <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                {{ $logbooks->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-600 responsive-table">
                <thead class="text-[11px] text-gray-400 uppercase bg-[#fafafa] font-semibold border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">Siswa</th>
                        <th scope="col" class="px-6 py-3.5">Tanggal</th>
                        <th scope="col" class="px-6 py-3.5">Kegiatan</th>
                        <th scope="col" class="px-6 py-3.5 text-center">Status</th>
                        <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($logbooks as $logbook)
                        @php
                            $namaSiswa = $logbook->student->full_name ?? 'Peserta Magang';
                            $statusIcon = $logbook->status === \App\Models\Logbook::STATUS_APPROVED
                                ? 'fa-circle-check'
                                : ($logbook->status === \App\Models\Logbook::STATUS_REJECTED ? 'fa-circle-xmark' : 'fa-clock');
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors align-top">
                            <td data-label="Siswa" class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-orange-50 text-[#ff6b00] border border-orange-100 font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ strtoupper(substr($namaSiswa, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-900 text-sm">{{ $namaSiswa }}</div>
                                        <div class="text-[11px] text-gray-500">
                                            {{ $logbook->student->institution ?: '-' }}
                                            @if ($logbook->student->division)
                                                · {{ $logbook->student->division->nama_divisi }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Tanggal" class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-900">
                                    {{ $logbook->date->format('d/m/Y') }}
                                </div>
                                <div class="text-[11px] text-gray-500">
                                    {{ $logbook->date->locale('id')->translatedFormat('l') }}
                                </div>
                            </td>
                            <td data-label="Kegiatan" class="px-6 py-4">
                                <div class="text-gray-800 leading-snug max-w-xs">
                                    {{ \Illuminate\Support\Str::limit($logbook->activity, 120) }}
                                </div>
                                @if ($logbook->result)
                                    <div class="text-[10px] text-gray-400 mt-1 max-w-xs truncate">
                                        <i class="fa-solid fa-arrow-turn-up text-[8px] mr-0.5"></i>
                                        {{ \Illuminate\Support\Str::limit($logbook->result, 80) }}
                                    </div>
                                @endif
                                @if ($logbook->isRejected() && $logbook->review_note)
                                    <div class="text-[10px] text-rose-500 mt-1 max-w-xs truncate">
                                        <i class="fa-solid fa-circle-exclamation text-[8px] mr-0.5"></i>
                                        {{ \Illuminate\Support\Str::limit($logbook->review_note, 80) }}
                                    </div>
                                @endif
                            </td>
                            <td data-label="Status" class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border whitespace-nowrap {{ $logbook->status_badge }}">
                                    <i class="fa-solid {{ $statusIcon }} text-[10px]"></i>
                                    <span>{{ $logbook->status_label }}</span>
                                </span>
                            </td>
                            <td data-label="Aksi" class="px-6 py-4 text-right actions-cell">
                                <div class="flex items-center justify-end">
                                    <button type="button" onclick="openReview({{ $logbook->id }})"
                                        class="px-2.5 py-1.5 border border-gray-200 rounded-lg bg-white hover:bg-gray-50 text-gray-700 font-medium transition flex items-center gap-1 shadow-xs" title="Review Logbook">
                                        <i class="fa-solid fa-eye text-xs text-gray-500"></i>
                                        <span>{{ $logbook->isApproved() ? 'Lihat' : 'Review' }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-solid fa-inbox text-3xl text-gray-300"></i>
                                    <p class="font-medium text-xs">Tidak ada data logbook ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logbooks->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $logbooks->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Review Logbook -->
<div id="reviewModal" class="modal hidden fixed inset-0 bg-black/50 backdrop-blur-xs z-50 flex items-center justify-center p-4"
    onclick="if (event.target === this) closeReview()">
    <div class="modal-content-box bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl border border-gray-100">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-xs"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Review Logbook</h3>
                    <p class="text-[10px] text-gray-400">Periksa kegiatan siswa sebelum menyetujui</p>
                </div>
            </div>
            <button type="button" onclick="closeReview()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div id="reviewAvatar" class="w-10 h-10 rounded-xl bg-orange-50 text-[#ff6b00] border border-orange-100 font-bold flex items-center justify-center shrink-0">?</div>
                    <div class="min-w-0">
                        <p id="reviewStudent" class="text-sm font-bold text-gray-900 truncate">-</p>
                        <p id="reviewInstitution" class="text-[10px] text-gray-400 truncate">-</p>
                    </div>
                </div>
                <span id="reviewStatusBadge" class="text-[9px] font-bold px-2.5 py-1 rounded-full border bg-amber-50 text-amber-800 border-amber-200 shrink-0">-</span>
            </div>

            <div class="flex items-center justify-between p-2.5 bg-gray-50 border border-gray-100 rounded-xl text-[11px]">
                <span class="text-gray-500 flex items-center gap-1.5">
                    <i class="fa-solid fa-calendar-day text-gray-400"></i>
                    <strong id="reviewDate" class="text-gray-800 font-semibold">-</strong>
                </span>
                <span id="reviewDivision" class="text-gray-400 truncate max-w-[45%]">-</span>
            </div>

            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-1">Uraian Kegiatan</span>
                <div class="p-3 bg-white border border-gray-200 rounded-xl">
                    <p id="reviewActivity" class="text-xs text-gray-800 whitespace-pre-line leading-relaxed">-</p>
                </div>
            </div>

            <div id="reviewResultWrap">
                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-1">Hasil / Output</span>
                <div class="p-3 bg-gray-50 border border-gray-100 rounded-xl">
                    <p id="reviewResult" class="text-xs text-gray-700 whitespace-pre-line leading-relaxed">-</p>
                </div>
            </div>

            <div id="reviewNoteWrap" class="hidden p-3 bg-rose-50 border border-rose-200 rounded-xl text-[11px] text-rose-800 space-y-1">
                <strong class="flex items-center gap-1.5 font-bold">
                    <i class="fa-solid fa-circle-exclamation"></i> Catatan Review Sebelumnya
                </strong>
                <p id="reviewNoteText" class="text-rose-700 leading-relaxed">-</p>
                <p id="reviewMeta" class="text-[10px] text-rose-500">-</p>
            </div>
        </div>

        <div class="px-5 pb-5 pt-3 border-t border-gray-100 space-y-3">
            <div id="reviewActions" class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeReview()"
                    class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-semibold transition">
                    Tutup
                </button>
                @if ($logbookReviewEnabled ?? true)
                <button type="button" onclick="showRejectPanel()"
                    class="px-4 py-2 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                    Tolak
                </button>
                <form id="approveForm" method="POST" class="m-0"
                    onsubmit="return confirm('Setujui logbook ini? Keputusan akan langsung tersimpan.')">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm active:scale-95">
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                        Setujui Logbook
                    </button>
                </form>
                @else
                <span class="text-[11px] text-gray-400 italic">Review dinonaktifkan — logbook otomatis disetujui.</span>
                @endif
            </div>

            <div id="reviewApprovedInfo" class="hidden p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-[11px] text-emerald-800">
                <i class="fa-solid fa-circle-check mr-1"></i>
                <span>Logbook ini sudah disetujui dan tidak memerlukan tindakan lagi.</span>
            </div>

            <form id="rejectForm" method="POST" class="hidden p-3 bg-rose-50 border border-rose-200 rounded-xl space-y-2">
                @csrf
                <label for="reviewNoteInput" class="block text-[11px] font-bold text-rose-800">
                    Alasan Penolakan <span class="text-rose-500">*</span>
                </label>
                <textarea id="reviewNoteInput" name="review_note" rows="3" required maxlength="2000"
                    placeholder="Contoh: uraian kegiatan terlalu singkat, mohon perjelas pekerjaan yang dilakukan..."
                    class="w-full text-xs p-2.5 border border-rose-200 rounded-lg focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-white resize-y"></textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="hideRejectPanel()"
                        class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-100 text-[11px] font-semibold transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-[11px] font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-paper-plane text-[9px]"></i>
                        Kirim Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div> <!-- Close pegawai-logbook-harian-wrapper -->

<!-- ================= TAB 2: BERKAS PROGRES BULANAN (SPLIT SIDEBAR + DETAIL) ================= -->
<div id="pegawai-progres-bulanan-wrapper" class="hidden space-y-5">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- ================= SIDEBAR KIRI: DAFTAR ANAK MAGANG AKTIF ================= -->
        <div class="lg:col-span-4 space-y-4">
            <div class="card p-4 sm:p-5 bg-white border border-gray-100 space-y-3.5">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div>
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-users text-teal-600"></i>
                            <span>Peserta Magang Aktif</span>
                        </h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Pilih siswa untuk verifikasi berkas bulanan</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-50 text-teal-700 border border-teal-200" id="progresStudentCountBadge">
                        {{ count($progressStudentsPayload ?? []) }} Siswa
                    </span>
                </div>

                <!-- Input Pencarian Siswa -->
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="searchProgresStudentInput" oninput="filterProgresStudentList()"
                        placeholder="Cari nama atau instansi..."
                        class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg outline-none focus:border-gray-900 bg-gray-50/50 focus:bg-white text-gray-800 transition">
                </div>

                <!-- List Siswa Magang Aktif -->
                <div class="space-y-2 max-h-[550px] overflow-y-auto custom-scrollbar pr-1" id="progresStudentsListContainer">
                    @forelse($progressStudentsPayload ?? [] as $idx => $st)
                        <button type="button" onclick="selectProgresStudent({{ $st['id'] }})"
                            id="progres-student-btn-{{ $st['id'] }}"
                            class="progres-student-item w-full p-3 rounded-xl border text-left transition flex items-start gap-3 cursor-pointer {{ $idx === 0 ? 'border-teal-600 bg-teal-50/40 shadow-xs' : 'border-gray-100 hover:border-gray-300 bg-white' }}"
                            data-name="{{ strtolower($st['name']) }}"
                            data-inst="{{ strtolower($st['institution']) }}">
                            <div class="w-8 h-8 rounded-lg {{ $st['is_complete'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-700' }} font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                {{ strtoupper(substr($st['name'], 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <strong class="text-xs font-bold text-gray-900 truncate block">{{ $st['name'] }}</strong>
                                    @if($st['is_complete'])
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 shrink-0">
                                            Lengkap
                                        </span>
                                    @else
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-800 shrink-0">
                                            {{ $st['uploaded_count'] }}/{{ $st['total_months'] }} Bln
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-gray-500 truncate block mt-0.5">{{ $st['institution'] }}</span>
                                <div class="text-[9px] text-gray-400 mt-1 flex items-center justify-between">
                                    <span>{{ $st['division'] }}</span>
                                    <span>{{ $st['start_date'] }} - {{ $st['end_date'] }}</span>
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="p-6 text-center text-gray-400 text-xs">
                            <i class="fa-solid fa-user-xmark text-2xl mb-1 text-gray-300"></i>
                            <p>Tidak ada peserta magang aktif saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ================= PANEL KANAN: DETAIL BERKAS PROGRES BULANAN ================= -->
        <div class="lg:col-span-8 space-y-5" id="progresStudentDetailWrapper">
            @if(count($progressStudentsPayload ?? []) > 0)
                <!-- Siswa Info Banner -->
                <div class="card p-5 bg-white border border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-600 text-white font-bold flex items-center justify-center text-sm shadow-xs shrink-0" id="detailStAvatar">
                                -
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900" id="detailStName">-</h3>
                                <p class="text-xs text-gray-500 mt-0.5" id="detailStSub">-</p>
                            </div>
                        </div>
                        <div class="text-left sm:text-right flex flex-col sm:items-end gap-1.5">
                            <div>
                                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block">Status Berkas</span>
                                <span class="inline-flex items-center gap-1 text-xs font-bold mt-0.5" id="detailStProgressBadge">
                                    -
                                </span>
                            </div>
                            <button type="button" onclick="openTerminateStudentModal()" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-[11px] font-semibold transition active:scale-95 cursor-pointer" title="Memberhentikan peserta di tengah masa magang">
                                <i class="fa-solid fa-user-xmark text-[10px]"></i>
                                <span>Berhentikan Peserta</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-3 text-xs">
                        <div class="p-2.5 bg-gray-50 rounded-lg">
                            <span class="text-[10px] text-gray-400 block font-semibold">Divisi Penugasan</span>
                            <strong class="text-gray-900 text-xs truncate block" id="detailStDivision">-</strong>
                        </div>
                        <div class="p-2.5 bg-gray-50 rounded-lg">
                            <span class="text-[10px] text-gray-400 block font-semibold">Periode Magang</span>
                            <strong class="text-gray-900 text-xs block" id="detailStPeriod">-</strong>
                        </div>
                        <div class="p-2.5 bg-gray-50 rounded-lg">
                            <span class="text-[10px] text-gray-400 block font-semibold">Total Durasi</span>
                            <strong class="text-gray-900 text-xs block" id="detailStDuration">-</strong>
                        </div>
                        <div class="p-2.5 bg-gray-50 rounded-lg">
                            <span class="text-[10px] text-gray-400 block font-semibold">Kontak HP / WA</span>
                            <strong class="text-gray-900 text-xs truncate block" id="detailStPhone">-</strong>
                        </div>
                    </div>
                </div>

                <!-- List Berkas Progres Bulanan Siswa (Terkumpul maupun Belum) -->
                <div class="card overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between bg-[#fafafa]">
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-file-circle-check text-teal-600"></i>
                                <span>Daftar Berkas Logbook Bulanan per Periode</span>
                            </h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">
                                Verifikasi dan lakukan ACC atau minta perbaikan berkas yang telah dikumpulkan.
                            </p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-700" id="detailStMonthsSummary">
                            -
                        </span>
                    </div>

                    <div class="divide-y divide-gray-100" id="detailMonthsContainer">
                        <!-- Populated dynamically via JS selectProgresStudent -->
                    </div>
                </div>

                <!-- Bagian Pengisian Pesan Apresiasi / Akhir Magang untuk Siswa -->
                <div class="card p-5 bg-white border border-gray-100 space-y-3.5" id="finalMessageCard">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold shrink-0 border border-amber-200 mt-0.5">
                            <i class="fa-solid fa-ribbon"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Pesan Apresiasi / Catatan Akhir untuk Anak Magang</h4>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                Pesan ini akan otomatis tampil di bagian <strong>Riwayat Magang (History)</strong> pada akun anak magang saat masa magang selesai.
                            </p>
                        </div>
                        <span id="finalMessageCompleteBadge" class="hidden text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                            Berkas Lengkap
                        </span>
                    </div>

                    <form id="finalMessageForm" method="POST" action="" class="space-y-3">
                        @csrf
                        <textarea name="final_message" id="inputFinalMessage" rows="3" required
                            placeholder="Contoh: Terima kasih atas dedikasi dan kerja keras Anda di Divisi Statistik Sosial. Hasil kerja sangat rapi, disiplin, dan mampu bekerjasama dengan tim dengan sangat baik. Semoga sukses selalu!"
                            class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 bg-gray-50/50 focus:bg-white resize-y transition leading-relaxed"></textarea>

                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-gray-400 italic" id="finalMessageStatusText">
                                Masukkan pesan atau apresiasi untuk siswa ini.
                            </span>
                            <button type="submit"
                                class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs active:scale-95">
                                <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                                <span>Simpan Pesan untuk Anak Magang</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Bagian Kelulusan & Penerbitan Sertifikat Magang -->
                <div class="card p-5 bg-white border border-gray-100 space-y-3.5" id="certificateActionCard">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center text-sm font-bold shrink-0 border border-teal-200 mt-0.5">
                                <i class="fa-solid fa-certificate"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Penyelesaian Magang & Penerbitan Sertifikat</h4>
                                <p class="text-[11px] text-gray-500 mt-0.5" id="certificateCardSubtitle">
                                    Setelah seluruh berkas bulanan disetujui (ACC), pegawai dapat menerbitkan sertifikat resmi magang BPS untuk siswa ini.
                                </p>
                            </div>
                        </div>
                        <div id="certStatusBadgeWrapper"></div>
                    </div>

                    <div class="p-3.5 rounded-xl border border-gray-200 bg-gray-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                        <div class="space-y-0.5">
                            <span class="text-[10px] text-gray-400 font-semibold uppercase block">Status Sertifikat</span>
                            <strong class="text-gray-900 font-bold block" id="certStatusText">Belum Diterbitkan</strong>
                            <span class="text-[11px] text-gray-500" id="certNumberInfo">Nomor sertifikat akan digenerate otomatis</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="#" id="btnViewCertLink" target="_blank" class="hidden px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                                <i class="fa-solid fa-print text-[11px]"></i>
                                <span>Lihat / Cetak Sertifikat</span>
                            </a>
                            <button type="button" id="btnOpenModalCert" onclick="openIssueCertModal()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs active:scale-95">
                                <i class="fa-solid fa-award text-[11px]"></i>
                                <span>Terbitkan Sertifikat Magang</span>
                            </button>
                        </div>
                    </div>
                </div>

            @else
                <div class="card p-12 text-center text-gray-400">
                    <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-2"></i>
                    <h4 class="text-sm font-bold text-gray-700">Belum Ada Siswa Magang Aktif</h4>
                    <p class="text-xs text-gray-400 mt-1">Daftar siswa magang aktif akan tampil di sini saat ada siswa yang melaksanakan magang.</p>
                </div>
            @endif
        </div>

    </div>
</div>

<!-- Modal Minta Perbaikan Berkas Progres Pegawai -->
<div id="modalRejectProgresPegawai" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 animate-scaleUp border border-gray-100">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Minta Perbaikan Berkas</h3>
                    <p class="text-[11px] text-gray-400" id="rejectProgresSub">Catatan perbaikan untuk siswa</p>
                </div>
            </div>
            <button type="button" onclick="closeRejectProgresModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="formRejectProgresPegawai" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label for="rejectFeedbackInput" class="block text-xs font-semibold text-gray-700 mb-1">
                    Alasan / Catatan Perbaikan <span class="text-rose-500">*</span>
                </label>
                <textarea name="feedback" id="rejectFeedbackInput" rows="4" required minlength="3" maxlength="1000"
                    placeholder="Jelaskan alasan berkas perlu diperbaiki (contoh: tanda tangan belum ada, cap belum lengkap, lembar logbook buram)..."
                    class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 resize-none bg-white leading-relaxed"></textarea>
                <span class="text-[10px] text-gray-400 block mt-1">Catatan ini akan langsung terlihat oleh siswa di tab Progres.</span>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                <button type="button" onclick="closeRejectProgresModal()"
                    class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-medium transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md active:scale-95">
                    <i class="fa-solid fa-paper-plane text-[11px]"></i>
                    <span>Kirim Catatan Perbaikan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Terbitkan Sertifikat Magang Pegawai -->
<div id="modalIssueCertPegawai" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 animate-scaleUp border border-gray-100">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center text-sm font-bold border border-teal-200">
                    <i class="fa-solid fa-award"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Terbitkan Sertifikat Resmi Magang</h3>
                    <p class="text-[11px] text-gray-400" id="issueCertSub">Verifikasi & Terbitkan Sertifikat BPS Kota Malang</p>
                </div>
            </div>
            <button type="button" onclick="closeIssueCertModal()" class="text-gray-400 hover:text-black text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('pegawai.sertifikat.terbitkan') }}" id="formIssueCertPegawai" class="space-y-3.5 text-xs">
            @csrf
            <input type="hidden" name="student_id" id="certInputStudentId" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="font-bold text-gray-700 block text-[11px]">Nama Peserta Magang</label>
                    <input type="text" name="student_name" id="certInputStudentName" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900">
                </div>
                <div class="space-y-1">
                    <label class="font-bold text-gray-700 block text-[11px]">Asal Kampus / Sekolah</label>
                    <input type="text" name="institution" id="certInputInstitution" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="font-bold text-gray-700 block text-[11px]">Nomor Surat Sertifikat</label>
                    <input type="text" name="certificate_number" id="certInputCertNumber" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900">
                </div>
                <div class="space-y-1">
                    <label class="font-bold text-gray-700 block text-[11px]">Predikat Penilaian</label>
                    <select name="predicate" id="certInputPredicate" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900">
                        <option value="Sangat Baik">Sangat Baik (A)</option>
                        <option value="Baik" selected>Baik (B)</option>
                        <option value="Cukup">Cukup (C)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="font-bold text-gray-700 block text-[11px]">Tanggal Terbit</label>
                    <input type="date" name="issue_date" id="certInputIssueDate" value="{{ date('Y-m-d') }}" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900">
                </div>
                <div class="space-y-1">
                    <label class="font-bold text-gray-700 block text-[11px]">Jabatan Penandatangan</label>
                    <input type="text" name="signer_position" id="certInputSignerPos" value="Kepala BPS Kota Malang" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900">
                </div>
            </div>

            <div class="space-y-1">
                <label class="font-bold text-gray-700 block text-[11px]">Nama Penandatangan / Pembimbing</label>
                <input type="text" name="signer_name" id="certInputSignerName" value="{{ Auth::user()->name ?: 'Kepala BPS Kota Malang' }}" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900">
            </div>

            <div class="space-y-1">
                <label class="font-bold text-gray-700 block text-[11px]">Catatan / Keterangan Khusus (Opsional)</label>
                <textarea name="notes" id="certInputNotes" rows="2" placeholder="Catatan tambahan di sertifikat jika ada..." class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900"></textarea>
            </div>

            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-[11px] leading-relaxed flex items-start gap-2">
                <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                <span>Menerbitkan sertifikat ini akan menandai program magang siswa sebagai <strong>Selesai (Completed)</strong>. Siswa dapat mengunduh sertifikat resmi langsung dari akun mereka.</span>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeIssueCertModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-black text-white font-bold text-xs transition flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid fa-check text-[11px]"></i>
                    <span>Terbitkan Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Memberhentikan Peserta Magang (Pegawai) -->
<div id="modalTerminateStudentPegawai" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 animate-scaleUp border border-gray-100">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-bold border border-rose-200">
                    <i class="fa-solid fa-user-slash"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Memberhentikan Peserta Magang</h3>
                    <p class="text-[11px] text-gray-400">Tindakan Khusus Pembimbing / Pegawai BPS</p>
                </div>
            </div>
            <button type="button" onclick="closeTerminateStudentModal()" class="text-gray-400 hover:text-black text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form method="POST" action="" id="formTerminateStudentPegawai" class="space-y-3.5 text-xs">
            @csrf
            <div class="p-3 bg-rose-50 rounded-xl border border-rose-200 text-rose-900 text-[11px] leading-relaxed space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                    <span id="terminateStudentNameText">Peserta Magang</span>
                </div>
                <p id="terminateStudentDivText" class="text-rose-800 text-[10px]">-</p>
                <p class="text-[10px] text-rose-700 pt-1">
                    Peserta akan langsung diakhiri masa magangnya dan status akun dikembalikan. Alasan pemberhentian akan tercatat resmi pada riwayat peserta tanpa penerbitan sertifikat.
                </p>
            </div>

            <div class="space-y-1">
                <label class="font-bold text-gray-700 block text-[11px]">Alasan Pemberhentian <span class="text-rose-500">*</span></label>
                <textarea name="alasan_pemberhentian" id="terminateReasonInput" rows="3" required placeholder="Tuliskan alasan jelas pemberhentian peserta magang ini..." class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900 leading-relaxed"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                <button type="button" onclick="closeTerminateStudentModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs transition">
                    Batal
                </button>
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MEMBERHENTIKAN peserta ini dari program magang? Tindakan ini akan tercatat resmi di history.');" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid fa-user-xmark text-[11px]"></i>
                    <span>Ya, Berhentikan Peserta</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const logbookData = @json($logbookPayload);

    let logbookCalYear = {{ request('dari') ? \Carbon\Carbon::parse(request('dari'))->year : \Carbon\Carbon::today()->year }};
    let logbookCalMonth = {{ request('dari') ? \Carbon\Carbon::parse(request('dari'))->month : \Carbon\Carbon::today()->month }};
    const selectedLogbookDate = "{{ request('dari') ?: (request('tanggal') ?: '') }}";

    function changeLogbookCalMonth(delta) {
        logbookCalMonth += delta;
        if (logbookCalMonth > 12) { logbookCalMonth = 1; logbookCalYear++; }
        if (logbookCalMonth < 1) { logbookCalMonth = 12; logbookCalYear--; }
        renderLogbookCal();
    }

    function renderLogbookCal() {
        const titleEl = document.getElementById('logbookCalMonthTitle');
        const grid = document.getElementById('logbookCalMatrix');
        if (!titleEl || !grid) return;

        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        titleEl.textContent = monthNames[logbookCalMonth - 1] + ' ' + logbookCalYear;

        const firstDay = new Date(logbookCalYear, logbookCalMonth - 1, 1);
        const lastDay = new Date(logbookCalYear, logbookCalMonth, 0).getDate();
        const startWeekday = (firstDay.getDay() + 6) % 7; // Monday=0

        let html = '';
        // empty cells before first day
        for (let i = 0; i < startWeekday; i++) {
            html += '<div class="py-1.5 text-gray-300 text-xs font-normal"></div>';
        }
        const today = new Date();
        const todayStr = today.getFullYear() + '-' + String(today.getMonth()+1).padStart(2,'0') + '-' + String(today.getDate()).padStart(2,'0');
        for (let d = 1; d <= lastDay; d++) {
            const dateStr = logbookCalYear + '-' + String(logbookCalMonth).padStart(2,'0') + '-' + String(d).padStart(2,'0');
            const isSelected = (selectedLogbookDate && dateStr === selectedLogbookDate);
            const isToday = dateStr === todayStr;
            const cls = isSelected
                ? 'bg-gray-900 text-white font-bold shadow-xs'
                : isToday
                ? 'bg-blue-50 text-blue-700 font-bold border border-blue-200 hover:bg-blue-100'
                : 'text-gray-700 hover:bg-gray-100';
            html += `<button type="button" onclick="selectLogbookCalDate('${dateStr}')" class="py-1.5 flex flex-col items-center justify-center rounded-lg transition font-medium text-xs relative cursor-pointer ${cls}"><span>${d}</span></button>`;
        }
        grid.innerHTML = html;
    }

    function selectLogbookCalDate(dateStr) {
        const dariEl = document.getElementById('inputLogbookDari');
        const keEl = document.getElementById('inputLogbookKe');
        if (dariEl) dariEl.value = dateStr;
        if (keEl) keEl.value = dateStr;
        const form = document.getElementById('logbookFilterForm') || document.querySelector('#main-content-logbook form');
        if (form) form.submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        renderLogbookCal();
    });

    function setLogbookFilterPreset(preset) {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const todayStr = `${yyyy}-${mm}-${dd}`;

        const dariEl = document.getElementById('inputLogbookDari');
        const keEl = document.getElementById('inputLogbookKe');
        if (!dariEl || !keEl) return;

        if (preset === 'today') {
            dariEl.value = todayStr;
            keEl.value = todayStr;
        } else if (preset === 'this_month') {
            const firstDay = `${yyyy}-${mm}-01`;
            const lastDay = new Date(yyyy, today.getMonth() + 1, 0).getDate();
            const lastDayStr = `${yyyy}-${mm}-${String(lastDay).padStart(2, '0')}`;
            dariEl.value = firstDay;
            keEl.value = lastDayStr;
        }
    }

    function openReview(id) {
        const data = logbookData.find(item => item.id === id);
        if (!data) return;

        document.getElementById('reviewAvatar').textContent = (data.student || '?').charAt(0).toUpperCase();
        document.getElementById('reviewStudent').textContent = data.student;
        document.getElementById('reviewInstitution').textContent = data.institution || '-';

        const badge = document.getElementById('reviewStatusBadge');
        badge.textContent = data.status_label;
        badge.className = 'text-[9px] font-bold px-2.5 py-1 rounded-full border shrink-0 ' + data.status_badge;

        document.getElementById('reviewDate').textContent = data.date;
        document.getElementById('reviewDivision').textContent = data.division && data.division !== '-' ? data.division : 'Tanpa divisi';

        document.getElementById('reviewActivity').textContent = data.activity || '-';

        const resultWrap = document.getElementById('reviewResultWrap');
        if (data.result) {
            resultWrap.classList.remove('hidden');
            document.getElementById('reviewResult').textContent = data.result;
        } else {
            resultWrap.classList.add('hidden');
            document.getElementById('reviewResult').textContent = '';
        }

        const noteWrap = document.getElementById('reviewNoteWrap');
        if (data.review_note) {
            noteWrap.classList.remove('hidden');
            document.getElementById('reviewNoteText').textContent = data.review_note;
            document.getElementById('reviewMeta').textContent = [data.reviewer, data.reviewed_at].filter(Boolean).join(' · ');
        } else {
            noteWrap.classList.add('hidden');
        }

        const approveForm = document.getElementById('approveForm');
        const rejectForm = document.getElementById('rejectForm');
        if (approveForm) approveForm.action = data.approve_url;
        if (rejectForm) rejectForm.action = data.reject_url;
        const noteInput = document.getElementById('reviewNoteInput');
        if (noteInput) noteInput.value = '';

        const actions = document.getElementById('reviewActions');
        const approvedInfo = document.getElementById('reviewApprovedInfo');

        if (data.status === 'approved') {
            actions.classList.add('hidden');
            approvedInfo.classList.remove('hidden');
        } else {
            actions.classList.remove('hidden');
            approvedInfo.classList.add('hidden');
        }

        hideRejectPanel();
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function closeReview() {
        document.getElementById('reviewModal')?.classList.add('hidden');
        hideRejectPanel();
    }

    function showRejectPanel() {
        document.getElementById('rejectForm')?.classList.remove('hidden');
        document.getElementById('reviewNoteInput')?.focus();
    }

    function hideRejectPanel() {
        document.getElementById('rejectForm')?.classList.add('hidden');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeReview();
            closeRejectProgresModal();
        }
    });

    function switchPegawaiLogbookSubtab(subtab) {
        const harianWrapper = document.getElementById('pegawai-logbook-harian-wrapper');
        const progresWrapper = document.getElementById('pegawai-progres-bulanan-wrapper');
        const btnHarian = document.getElementById('btn-subtab-harian');
        const btnProgres = document.getElementById('btn-subtab-progres');

        if (subtab === 'progres') {
            harianWrapper?.classList.add('hidden');
            progresWrapper?.classList.remove('hidden');

            btnProgres?.classList.add('bg-white', 'text-gray-900', 'shadow-xs', 'font-bold');
            btnProgres?.classList.remove('text-gray-600');
            btnHarian?.classList.remove('bg-white', 'text-gray-900', 'shadow-xs', 'font-bold');
            btnHarian?.classList.add('text-gray-600');
        } else {
            progresWrapper?.classList.add('hidden');
            harianWrapper?.classList.remove('hidden');

            btnHarian?.classList.add('bg-white', 'text-gray-900', 'shadow-xs', 'font-bold');
            btnHarian?.classList.remove('text-gray-600');
            btnProgres?.classList.remove('bg-white', 'text-gray-900', 'shadow-xs', 'font-bold');
            btnProgres?.classList.add('text-gray-600');
        }
    }

    function openRejectProgresModal(id, studentName, monthName) {
        const modal = document.getElementById('modalRejectProgresPegawai');
        const form = document.getElementById('formRejectProgresPegawai');
        const sub = document.getElementById('rejectProgresSub');
        const input = document.getElementById('rejectFeedbackInput');

        if (form) form.action = `/pegawai/progres/${id}/reject`;
        if (sub) sub.textContent = `${studentName} · Periode ${monthName}`;
        if (input) input.value = '';

        modal?.classList.remove('hidden');
        input?.focus();
    }

    function closeRejectProgresModal() {
        document.getElementById('modalRejectProgresPegawai')?.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('subtab') === 'progres' || urlParams.get('progres_page')) {
            switchPegawaiLogbookSubtab('progres');
        }

        // Initialize first active student in Berkas Progres Bulanan
        if (typeof progressStudentsData !== 'undefined' && progressStudentsData.length > 0) {
            const preselectedId = parseInt(urlParams.get('student_id')) || progressStudentsData[0].id;
            selectProgresStudent(preselectedId);
        }
    });

    const progressStudentsData = @json($progressStudentsPayload ?? []);
    let selectedProgresStudentId = null;

    function selectProgresStudent(studentId) {
        selectedProgresStudentId = studentId;
        const student = progressStudentsData.find(s => s.id === studentId);
        if (!student) return;

        // Update active class in sidebar items
        document.querySelectorAll('.progres-student-item').forEach(btn => {
            btn.className = 'progres-student-item w-full p-3 rounded-xl border text-left transition flex items-start gap-3 cursor-pointer border-gray-100 hover:border-gray-300 bg-white';
        });
        const activeBtn = document.getElementById(`progres-student-btn-${studentId}`);
        if (activeBtn) {
            activeBtn.className = 'progres-student-item w-full p-3 rounded-xl border text-left transition flex items-start gap-3 cursor-pointer border-teal-600 bg-teal-50/40 shadow-xs';
        }

        // Header info
        const avatarEl = document.getElementById('detailStAvatar');
        if (avatarEl) avatarEl.textContent = (student.name || 'M').charAt(0).toUpperCase();
        const nameEl = document.getElementById('detailStName');
        if (nameEl) nameEl.textContent = student.name;
        const subEl = document.getElementById('detailStSub');
        if (subEl) subEl.textContent = `${student.institution} · ${student.major}`;
        const divEl = document.getElementById('detailStDivision');
        if (divEl) divEl.textContent = student.division;
        const periodEl = document.getElementById('detailStPeriod');
        if (periodEl) periodEl.textContent = `${student.start_date} s.d. ${student.end_date}`;
        const durationEl = document.getElementById('detailStDuration');
        if (durationEl) durationEl.textContent = `${student.total_months} Bulan (${student.uploaded_count} Terkumpul)`;
        const phoneEl = document.getElementById('detailStPhone');
        if (phoneEl) phoneEl.textContent = student.phone || '-';

        const progressBadge = document.getElementById('detailStProgressBadge');
        if (progressBadge) {
            if (student.is_complete) {
                progressBadge.innerHTML = `<span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200"><i class="fa-solid fa-circle-check text-[10px] mr-1"></i>Lengkap & Disetujui (${student.approved_count}/${student.total_months})</span>`;
            } else {
                progressBadge.innerHTML = `<span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200"><i class="fa-solid fa-clock text-[10px] mr-1"></i>${student.approved_count}/${student.total_months} Bulan Disetujui</span>`;
            }
        }

        const summaryEl = document.getElementById('detailStMonthsSummary');
        if (summaryEl) summaryEl.textContent = `${student.uploaded_count} dari ${student.total_months} bulan diunggah`;

        // Render Months List
        const monthsContainer = document.getElementById('detailMonthsContainer');
        if (!monthsContainer) return;
        monthsContainer.innerHTML = '';

        if (!student.months || student.months.length === 0) {
            monthsContainer.innerHTML = `<div class="p-8 text-center text-gray-400 text-xs">Periode magang siswa belum memiliki jadwal bulan aktif.</div>`;
        } else {
            student.months.forEach(m => {
                const row = document.createElement('div');
                row.className = 'p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50/60 transition';

                if (m.status !== 'unsubmitted') {
                    // Uploaded card
                    let statusBadgeHtml = '';
                    if (m.status === 'approved') {
                        statusBadgeHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-emerald-200 bg-emerald-50 text-emerald-700"><i class="fa-solid fa-circle-check text-[10px]"></i> Disetujui (ACC)</span>`;
                    } else if (m.status === 'rejected') {
                        statusBadgeHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-rose-200 bg-rose-50 text-rose-700"><i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Perlu Perbaikan</span>`;
                    } else {
                        statusBadgeHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-amber-200 bg-amber-50 text-amber-700"><i class="fa-solid fa-clock text-[10px]"></i> Menunggu Verifikasi</span>`;
                    }

                    let actionsHtml = '';
                    if (m.status !== 'approved') {
                        actionsHtml += `
                            <form method="POST" action="${m.approve_url}" class="inline-block m-0" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI (ACC) berkas bulan ${m.month_name} milik ${student.name.replace(/'/g, "\\'")}?');">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'}">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-xs active:scale-95">
                                    <i class="fa-solid fa-check text-[11px]"></i>
                                    <span>ACC</span>
                                </button>
                            </form>
                        `;
                    }
                    actionsHtml += `
                        <button type="button" onclick="openRejectProgresModal(${m.progress_id}, '${student.name.replace(/'/g, "\\'")}', '${m.month_name}')"
                            class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition flex items-center gap-1 active:scale-95" title="Minta Perbaikan / Revisi Berkas">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                            <span>Perbaiki</span>
                        </button>
                    `;

                    row.innerHTML = `
                        <div class="flex items-start gap-3.5 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center shrink-0 text-sm mt-0.5">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div class="min-w-0 space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <strong class="text-xs font-bold text-gray-900">${m.month_name}</strong>
                                    ${statusBadgeHtml}
                                </div>
                                <div>
                                    <a href="${m.download_url}" target="_blank"
                                        class="group inline-flex items-center gap-1.5 text-xs font-medium text-gray-800 hover:text-blue-600 transition"
                                        title="Klik untuk membuka dokumen PDF">
                                        <span class="truncate max-w-[200px] sm:max-w-xs group-hover:underline">${m.file_name}</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-gray-400 group-hover:text-blue-600"></i>
                                    </a>
                                </div>
                                <div class="text-[10px] text-gray-400">
                                    ${m.file_size_formatted || ''} · Diunggah pada ${m.uploaded_at || '-'}
                                </div>
                                ${m.notes ? `<p class="text-[11px] text-gray-500 italic mt-0.5">Catatan siswa: "${m.notes}"</p>` : ''}
                                ${m.feedback ? `<div class="p-2 bg-rose-50 border border-rose-100 rounded-lg text-rose-800 text-[11px] mt-1"><strong>Catatan revisi:</strong> ${m.feedback}</div>` : ''}
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 self-end sm:self-center shrink-0">
                            ${actionsHtml}
                        </div>
                    `;
                } else {
                    // Unsubmitted card
                    row.innerHTML = `
                        <div class="flex items-start gap-3.5 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-gray-100 text-gray-400 border border-gray-200 flex items-center justify-center shrink-0 text-sm mt-0.5">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <strong class="text-xs font-bold text-gray-700">${m.month_name}</strong>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                        <i class="fa-regular fa-circle text-[8px]"></i> Belum Dikumpulkan
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-400">
                                    Siswa belum mengunggah berkas logbook bertandatangan untuk periode bulan ini.
                                </p>
                            </div>
                        </div>
                        <div class="self-end sm:self-center shrink-0">
                            <span class="text-[11px] text-gray-400 italic">Menunggu unggahan</span>
                        </div>
                    `;
                }

                monthsContainer.appendChild(row);
            });
        }

        // Setup Final Message Form
        const messageForm = document.getElementById('finalMessageForm');
        const inputMessage = document.getElementById('inputFinalMessage');
        const completeBadge = document.getElementById('finalMessageCompleteBadge');
        const statusText = document.getElementById('finalMessageStatusText');

        if (messageForm) messageForm.action = student.save_message_url;
        if (inputMessage) inputMessage.value = student.final_message || '';

        if (statusText) {
            if (student.is_complete) {
                completeBadge?.classList.remove('hidden');
                statusText.textContent = student.final_message
                    ? 'Pesan telah tersimpan dan sudah tampil di akun siswa.'
                    : 'Seluruh berkas bulanan siswa ini sudah lengkap! Silakan tuliskan pesan apresiasi akhir.';
                statusText.className = 'text-[10px] text-emerald-700 font-semibold';
            } else {
                completeBadge?.classList.add('hidden');
                statusText.textContent = student.final_message
                    ? 'Pesan tersimpan (dapat diubah kapan saja).'
                    : `Progres saat ini: ${student.approved_count} dari ${student.total_months} bulan disetujui. Anda dapat mengisi pesan akhir sekarang atau setelah lengkap.`;
                statusText.className = 'text-[10px] text-gray-500 italic';
            }
        }

        // Setup Certificate Action Card
        const certBadgeWrapper = document.getElementById('certStatusBadgeWrapper');
        const certStatusText = document.getElementById('certStatusText');
        const certNumberInfo = document.getElementById('certNumberInfo');
        const btnViewCert = document.getElementById('btnViewCertLink');
        const btnOpenModalCert = document.getElementById('btnOpenModalCert');

        if (student.has_certificate) {
            if (certBadgeWrapper) certBadgeWrapper.innerHTML = `<span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-200"><i class="fa-solid fa-circle-check mr-1"></i>Sertifikat Terbit</span>`;
            if (certStatusText) certStatusText.textContent = `Nomor: ${student.certificate_number}`;
            if (certNumberInfo) certNumberInfo.textContent = 'Sertifikat resmi telah diterbitkan dan dapat diunduh oleh siswa.';
            if (btnViewCert) {
                btnViewCert.href = student.certificate_url;
                btnViewCert.classList.remove('hidden');
            }
            if (btnOpenModalCert) {
                btnOpenModalCert.innerHTML = '<i class="fa-solid fa-pen-to-square text-[11px]"></i><span>Perbarui Data Sertifikat</span>';
                btnOpenModalCert.className = 'px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer';
            }
        } else {
            if (certBadgeWrapper) {
                certBadgeWrapper.innerHTML = student.is_complete
                    ? `<span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold border border-blue-200"><i class="fa-solid fa-circle-check mr-1"></i>Siap Diterbitkan</span>`
                    : `<span class="px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[10px] font-bold border border-gray-200"><i class="fa-regular fa-clock mr-1"></i>Belum Lengkap</span>`;
            }
            if (certStatusText) {
                certStatusText.textContent = student.is_complete ? 'Berkas Lengkap — Siap Terbit' : 'Menunggu Kelengkapan Berkas Bulanan';
            }
            if (certNumberInfo) {
                certNumberInfo.textContent = student.is_complete
                    ? `Usulan Nomor: ${student.default_cert_number}`
                    : `Siswa baru mengumpulkan ${student.approved_count} dari ${student.total_months} bulan berkas disetujui.`;
            }
            if (btnViewCert) btnViewCert.classList.add('hidden');
            if (btnOpenModalCert) {
                if (student.is_complete) {
                    btnOpenModalCert.disabled = false;
                    btnOpenModalCert.innerHTML = '<i class="fa-solid fa-award text-[11px]"></i><span>Terbitkan Sertifikat Magang</span>';
                    btnOpenModalCert.className = 'px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs active:scale-95 cursor-pointer';
                    btnOpenModalCert.title = 'Terbitkan sertifikat resmi kelulusan';
                } else {
                    btnOpenModalCert.disabled = true;
                    btnOpenModalCert.innerHTML = '<i class="fa-solid fa-lock text-[11px]"></i><span>Sertifikat Terkunci (Berkas Belum Lengkap)</span>';
                    btnOpenModalCert.className = 'px-4 py-2 rounded-xl bg-gray-100 text-gray-400 border border-gray-200 text-xs font-semibold cursor-not-allowed flex items-center gap-1.5 shadow-none';
                    btnOpenModalCert.title = `Siswa belum melengkapi berkas bulanan (${student.approved_count}/${student.total_months} bulan disetujui). Sertifikat tidak dapat diberikan.`;
                }
            }
        }
    }

    function openIssueCertModal() {
        const student = progressStudentsData.find(s => s.id === selectedProgresStudentId);
        if (!student) return;

        if (!student.is_complete && !student.has_certificate) {
            alert(`Sertifikat belum dapat diberikan karena peserta belum melengkapi berkas logbook bulanan (${student.approved_count} dari ${student.total_months} bulan disetujui).`);
            return;
        }

        const inputId = document.getElementById('certInputStudentId');
        const inputName = document.getElementById('certInputStudentName');
        const inputInst = document.getElementById('certInputInstitution');
        const inputNumber = document.getElementById('certInputCertNumber');
        const sub = document.getElementById('issueCertSub');

        if (inputId) inputId.value = student.id;
        if (inputName) inputName.value = student.name;
        if (inputInst) inputInst.value = student.institution;
        if (inputNumber) inputNumber.value = student.certificate_number || student.default_cert_number;
        if (sub) sub.textContent = `${student.name} · ${student.division}`;

        document.getElementById('modalIssueCertPegawai')?.classList.remove('hidden');
    }

    function closeIssueCertModal() {
        document.getElementById('modalIssueCertPegawai')?.classList.add('hidden');
    }

    function openTerminateStudentModal() {
        const student = progressStudentsData.find(s => s.id === selectedProgresStudentId);
        if (!student) return;

        const modal = document.getElementById('modalTerminateStudentPegawai');
        const form = document.getElementById('formTerminateStudentPegawai');
        const nameEl = document.getElementById('terminateStudentNameText');
        const divEl = document.getElementById('terminateStudentDivText');
        const inputReason = document.getElementById('terminateReasonInput');

        if (form) form.action = `/pegawai/siswa/${student.id}/berhentikan`;
        if (nameEl) nameEl.textContent = student.name;
        if (divEl) divEl.textContent = `${student.institution} · Divisi ${student.division}`;
        if (inputReason) inputReason.value = '';

        modal?.classList.remove('hidden');
        inputReason?.focus();
    }

    function closeTerminateStudentModal() {
        document.getElementById('modalTerminateStudentPegawai')?.classList.add('hidden');
    }

    function filterProgresStudentList() {
        const q = (document.getElementById('searchProgresStudentInput')?.value || '').toLowerCase().trim();
        const items = document.querySelectorAll('.progres-student-item');
        let visible = 0;
        items.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const inst = item.getAttribute('data-inst') || '';
            if (name.includes(q) || inst.includes(q)) {
                item.style.display = '';
                visible++;
            } else {
                item.style.display = 'none';
            }
        });
        const badge = document.getElementById('progresStudentCountBadge');
        if (badge) badge.textContent = `${visible} Siswa`;
    }
</script>
