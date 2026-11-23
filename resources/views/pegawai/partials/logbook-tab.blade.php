<!-- ================= TAB LOGBOOK (embedded in pegawai absensi) ================= -->
@if (!($logbookReviewEnabled ?? true))
<div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl text-[11px] text-amber-800">
    <i class="fa-solid fa-circle-info mr-1"></i>
    Review logbook sedang dinonaktifkan. Logbook siswa otomatis disetujui tanpa review.
</div>
@endif

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

            <form action="{{ route('pegawai.absensi') }}?tab=logbook" method="GET" class="space-y-3.5 text-xs">
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

<script>
    const logbookData = @json($logbookPayload);

    let logbookCalYear = {{ \Carbon\Carbon::today()->year }};
    let logbookCalMonth = {{ \Carbon\Carbon::today()->month }};

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
            const isToday = dateStr === todayStr;
            const cls = isToday
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
        const form = document.querySelector('#main-content-logbook form');
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
        if (event.key === 'Escape') closeReview();
    });
</script>
