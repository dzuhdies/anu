{{-- ================= TAB 3: LOGBOOK HARIAN ================= --}}
<div id="view-logbook" class="absen-view-tab space-y-5 animate-fadeIn" style="display: none;">
    <div class="w-full max-w-2xl mx-auto space-y-5">

        <!-- ================= JUDUL & PEMILIH TANGGAL ================= -->
        <div class="card p-4 bg-white space-y-3">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="text-base font-bold text-gray-900">Logbook Harian Magang</h1>
                    <p class="text-[11px] text-gray-500 mt-0.5 leading-relaxed">
                        Catat kegiatan magang Anda setiap hari. Logbook hanya dapat diisi pada hari-H dan H+1.
                    </p>
                </div>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200 shrink-0">
                    {{ $stats['total'] }} catatan
                </span>
            </div>

            <!-- Pemilih Tanggal Gaya Access by KAI -->
            <div class="flex items-center gap-2">
                <button type="button" onclick="scrollLogbookDates(-1)"
                    class="w-8 h-8 shrink-0 rounded-full border border-gray-200 bg-white text-gray-400 hover:text-gray-900 hover:border-gray-300 flex items-center justify-center transition active:scale-95"
                    title="Tanggal sebelumnya">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </button>

                <div id="logbookDateRail" class="flex-1 flex items-stretch gap-2 overflow-x-auto custom-scrollbar px-0.5 py-0.5 snap-x snap-mandatory">
                    @foreach ($availableDates as $dateOption)
                        @php
                            $key = $dateOption->toDateString();
                            $isActive = $dateOption->isSameDay($selectedDate);
                            $isTodayOption = $dateOption->isToday();
                            $optionLogbook = $logbookPanels[$key]['logbook'] ?? null;
                            $optionStatusColor = $optionLogbook
                                ? ($optionLogbook->status === \App\Models\Logbook::STATUS_APPROVED
                                    ? 'bg-emerald-400'
                                    : ($optionLogbook->status === \App\Models\Logbook::STATUS_REJECTED ? 'bg-rose-400' : 'bg-amber-400'))
                                : 'bg-gray-300';
                        @endphp
                        <button type="button" data-logbook-date="{{ $key }}" data-active="{{ $isActive ? 'true' : 'false' }}"
                            onclick="selectLogbookDate('{{ $key }}')"
                            class="logbook-date-card snap-start shrink-0 flex-1 min-w-[140px] rounded-2xl border px-3 py-2.5 text-left transition">
                            <span class="logbook-date-label flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-wider">
                                <i class="fa-solid {{ $isTodayOption ? 'fa-calendar-day' : 'fa-clock-rotate-left' }} text-[8px]"></i>
                                {{ $isTodayOption ? 'Hari Ini' : 'Kemarin' }}
                                <span class="ml-auto opacity-70">{{ $dateOption->locale('id')->translatedFormat('D') }}</span>
                            </span>
                            <span class="flex items-end gap-1.5 mt-1">
                                <span class="logbook-date-number text-2xl font-extrabold leading-none font-mono">{{ $dateOption->format('d') }}</span>
                                <span class="logbook-date-month text-[10px] font-semibold leading-tight pb-0.5">
                                    {{ $dateOption->locale('id')->translatedFormat('M') }}<br>
                                    <span class="opacity-70">{{ $dateOption->format('Y') }}</span>
                                </span>
                            </span>
                            <span class="logbook-date-meta flex items-center justify-between gap-1 mt-1.5 text-[9px] font-medium">
                                <span class="flex items-center gap-1 truncate">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $optionStatusColor }} shrink-0"></span>
                                    {{ $optionLogbook->status_label ?? 'Belum diisi' }}
                                </span>
                                <span class="truncate opacity-70">Batas {{ $dateOption->copy()->addDay()->locale('id')->translatedFormat('d M') }}</span>
                            </span>
                        </button>
                    @endforeach
                </div>

                <button type="button" onclick="scrollLogbookDates(1)"
                    class="w-8 h-8 shrink-0 rounded-full border border-gray-200 bg-white text-gray-400 hover:text-gray-900 hover:border-gray-300 flex items-center justify-center transition active:scale-95"
                    title="Tanggal berikutnya">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>

            <p class="text-[10px] text-gray-500 flex items-start gap-1.5 leading-relaxed bg-amber-50 border border-amber-100 rounded-lg p-2.5">
                <i class="fa-solid fa-circle-info text-amber-500 mt-0.5"></i>
                <span>
                    Pilih tanggal pada kartu di atas. Logbook masih dapat diubah sampai <strong>H+1</strong>
                    dan otomatis terkunci mulai <strong>H+2</strong>.
                </span>
            </p>
        </div>

        <!-- ================= RINGKASAN STATUS ================= -->
        <div class="grid grid-cols-3 gap-2">
            <div class="card p-3 bg-white">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Menunggu</span>
                    <span class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-clock text-[10px]"></i>
                    </span>
                </div>
                <strong class="block text-lg font-bold text-gray-900 mt-1">{{ $stats['pending'] }}</strong>
            </div>
            <div class="card p-3 bg-white">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Disetujui</span>
                    <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                    </span>
                </div>
                <strong class="block text-lg font-bold text-gray-900 mt-1">{{ $stats['approved'] }}</strong>
            </div>
            <div class="card p-3 bg-white">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600">Ditolak</span>
                    <span class="w-6 h-6 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                    </span>
                </div>
                <strong class="block text-lg font-bold text-gray-900 mt-1">{{ $stats['rejected'] }}</strong>
            </div>
        </div>

        <!-- ================= FORM / DETAIL PER TANGGAL ================= -->
        @foreach ($availableDates as $dateOption)
            @php
                $key = $dateOption->toDateString();
                $panel = $logbookPanels[$key];
                $panelLogbook = $panel['logbook'];
                $panelCanEdit = $panel['canEdit'];
                $isActivePanel = $dateOption->isSameDay($selectedDate);
                $oldDateMatches = old('date') === $key;
                $activityValue = $oldDateMatches ? old('activity', $panelLogbook->activity ?? '') : ($panelLogbook->activity ?? '');
                $resultValue = $oldDateMatches ? old('result', $panelLogbook->result ?? '') : ($panelLogbook->result ?? '');
                $statusIcon = $panelLogbook
                    ? ($panelLogbook->status === \App\Models\Logbook::STATUS_APPROVED
                        ? 'fa-circle-check'
                        : ($panelLogbook->status === \App\Models\Logbook::STATUS_REJECTED ? 'fa-circle-xmark' : 'fa-clock'))
                    : 'fa-circle';
            @endphp

            <div class="logbook-date-panel" data-logbook-panel="{{ $key }}" @if (!$isActivePanel) style="display: none;" @endif>
                <div class="card p-5 bg-white space-y-4">
                    <div class="flex items-center justify-between gap-3 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-gray-900 text-white flex flex-col items-center justify-center shrink-0">
                                <span class="text-sm font-bold leading-none">{{ $dateOption->format('d') }}</span>
                                <span class="text-[9px] uppercase leading-none mt-0.5">{{ $dateOption->locale('id')->translatedFormat('M') }}</span>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-sm font-bold text-gray-900 truncate">
                                    {{ $dateOption->locale('id')->translatedFormat('l, d F Y') }}
                                </h2>
                                <p class="text-[11px] text-gray-400">
                                    {{ $dateOption->isToday() ? 'Logbook hari ini' : 'Masa tenggang H+1 (kemarin)' }}
                                </p>
                            </div>
                        </div>

                        @if ($panelLogbook)
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border shrink-0 {{ $panelLogbook->status_badge }}">
                                <i class="fa-solid {{ $statusIcon }} text-[8px] mr-0.5"></i>
                                {{ $panelLogbook->status_label }}
                            </span>
                        @else
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border border-gray-200 bg-gray-100 text-gray-500 shrink-0">
                                Belum Diisi
                            </span>
                        @endif
                    </div>

                    @if ($panelLogbook && $panelLogbook->isRejected())
                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 space-y-1">
                            <strong class="flex items-center gap-1.5 font-bold">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                Logbook perlu diperbaiki
                            </strong>
                            <p class="text-rose-700 leading-relaxed">{{ $panelLogbook->review_note ?: 'Admin menolak logbook ini tanpa catatan.' }}</p>
                        </div>
                    @elseif ($panelLogbook && $panelLogbook->isApproved())
                        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-start gap-2">
                            <i class="fa-solid fa-circle-check mt-0.5"></i>
                            <div>
                                <strong class="font-bold block">Logbook telah disetujui</strong>
                                <span class="text-[11px] text-emerald-700">
                                    {{ $panelLogbook->reviewer->username ?? 'Admin' }}
                                    @if ($panelLogbook->reviewed_at)
                                        · {{ $panelLogbook->reviewed_at->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif

                    @if ($panelCanEdit)
                        <form method="POST" action="{{ route('siswa.logbook.store', [], false) }}" class="space-y-3.5"
                            onsubmit="showLoading('Menyimpan Logbook...', 'Mohon tunggu sebentar...')">
                            @csrf
                            <input type="hidden" name="date" value="{{ $key }}">

                            <div>
                                <label for="logbook-activity-{{ $key }}" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Uraian Kegiatan <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="activity" id="logbook-activity-{{ $key }}" rows="5" required maxlength="2000"
                                    placeholder="Tuliskan pekerjaan / kegiatan yang Anda kerjakan pada tanggal ini secara jelas..."
                                    class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 resize-y leading-relaxed">{{ $activityValue }}</textarea>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-[10px] text-gray-400">Minimal 3 karakter.</span>
                                    <span id="logbook-activity-counter-{{ $key }}" data-logbook-counter="logbook-activity-{{ $key }}" class="text-[10px] text-gray-400 font-mono">0/2000</span>
                                </div>
                            </div>

                            <div>
                                <label for="logbook-result-{{ $key }}" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Hasil / Output <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <textarea name="result" id="logbook-result-{{ $key }}" rows="3" maxlength="2000"
                                    placeholder="Contoh: laporan selesai, data sudah diinput, dokumen sudah direkap..."
                                    class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 resize-y leading-relaxed">{{ $resultValue }}</textarea>
                                <div class="flex items-center justify-end mt-1">
                                    <span id="logbook-result-counter-{{ $key }}" data-logbook-counter="logbook-result-{{ $key }}" class="text-[10px] text-gray-400 font-mono">0/2000</span>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pt-3 border-t border-gray-100">
                                <p class="text-[10px] text-gray-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-lock"></i>
                                    Terkunci otomatis pada H+2 ({{ $dateOption->copy()->addDays(2)->locale('id')->translatedFormat('d M Y') }}).
                                </p>
                                <button type="submit"
                                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-md active:scale-95">
                                    <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                                    <span>{{ $panelLogbook ? 'Perbarui Logbook' : 'Simpan Logbook' }}</span>
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="space-y-3 text-xs">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-[10px] uppercase font-semibold text-gray-400 block">Uraian Kegiatan</span>
                                <p class="text-gray-800 whitespace-pre-line mt-1 leading-relaxed">
                                    {{ $panelLogbook->activity ?? 'Belum ada catatan untuk tanggal ini.' }}
                                </p>
                            </div>

                            @if ($panelLogbook && $panelLogbook->result)
                                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <span class="text-[10px] uppercase font-semibold text-gray-400 block">Hasil / Output</span>
                                    <p class="text-gray-800 whitespace-pre-line mt-1 leading-relaxed">{{ $panelLogbook->result }}</p>
                                </div>
                            @endif

                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-[11px] text-amber-800 flex items-start gap-2">
                                <i class="fa-solid fa-lock mt-0.5"></i>
                                <span>
                                    @if ($panelLogbook && $panelLogbook->isApproved())
                                        Logbook ini sudah disetujui admin dan tidak dapat diubah lagi.
                                    @else
                                        Logbook tanggal ini sudah melewati batas pengisian (H+2) sehingga terkunci.
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- ================= RIWAYAT LOGBOOK ================= -->
        <div class="card p-5 bg-white space-y-3">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Riwayat Logbook</h3>
                    <p class="text-[11px] text-gray-400">Klik catatan yang masih dapat diedit untuk memperbaruinya.</p>
                </div>
                <button type="button" onclick="window.location.reload()"
                    class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-500 hover:text-black flex items-center justify-center transition"
                    title="Muat Ulang">
                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                </button>
            </div>

            <div class="space-y-2">
                @forelse ($logbooks as $item)
                    @php
                        $isSelected = $item->date->isSameDay($selectedDate);
                        $isEditableItem = $item->isEditableByStudent($today);
                        $itemKey = $item->date->toDateString();
                        $isPanelDate = isset($logbookPanels[$itemKey]);
                        $canOpenItem = $isEditableItem && $isPanelDate;
                        $statusIcon = $item->status === \App\Models\Logbook::STATUS_APPROVED
                            ? 'fa-circle-check'
                            : ($item->status === \App\Models\Logbook::STATUS_REJECTED ? 'fa-circle-xmark' : 'fa-clock');
                    @endphp

                    @if ($canOpenItem)
                        <button type="button" onclick="selectLogbookDate('{{ $itemKey }}')"
                            class="w-full text-left flex items-start gap-3 p-3 rounded-xl border transition {{ $isSelected ? 'border-gray-900 bg-gray-50' : 'border-gray-100 bg-white hover:bg-gray-50' }}">
                    @else
                        <div class="flex items-start gap-3 p-3 rounded-xl border border-gray-100 bg-white">
                    @endif

                        <div class="w-10 h-10 rounded-lg bg-gray-900 text-white flex flex-col items-center justify-center shrink-0">
                            <span class="text-xs font-bold leading-none">{{ $item->date->format('d') }}</span>
                            <span class="text-[8px] uppercase leading-none mt-0.5">{{ $item->date->locale('id')->translatedFormat('M') }}</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] font-semibold text-gray-500">
                                    {{ $item->date->locale('id')->translatedFormat('l') }}
                                </span>
                                @if ($isSelected)
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-gray-900 text-white">DIPILIH</span>
                                @endif
                            </div>
                            <p class="text-xs font-semibold text-gray-900 mt-0.5 leading-snug">
                                {{ \Illuminate\Support\Str::limit($item->activity, 110) }}
                            </p>

                            @if ($item->isRejected() && $item->review_note)
                                <p class="text-[10px] text-rose-600 mt-1 leading-snug">
                                    <i class="fa-solid fa-circle-exclamation text-[9px]"></i>
                                    {{ \Illuminate\Support\Str::limit($item->review_note, 90) }}
                                </p>
                            @endif

                            @if (!$isEditableItem)
                                <p class="text-[9px] text-gray-400 mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-lock text-[8px]"></i>
                                    Terkunci (H+2)
                                </p>
                            @elseif ($item->status !== \App\Models\Logbook::STATUS_APPROVED)
                                <p class="text-[9px] text-gray-400 mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-pen-to-square text-[8px]"></i>
                                    Masih dapat diedit
                                </p>
                            @endif
                        </div>

                        <div class="flex flex-col items-end gap-1.5 shrink-0">
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full border {{ $item->status_badge }}">
                                <i class="fa-solid {{ $statusIcon }} text-[8px] mr-0.5"></i>
                                {{ $item->status_label }}
                            </span>
                            @if ($canOpenItem)
                                <i class="fa-solid fa-chevron-right text-[9px] text-gray-300"></i>
                            @endif
                        </div>

                    @if ($canOpenItem)
                        </button>
                    @else
                        </div>
                    @endif
                @empty
                    <div class="py-8 text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto text-lg">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 mt-2">Belum ada logbook</p>
                        <p class="text-[11px] text-gray-400">Isi logbook pertama Anda pada form di atas.</p>
                    </div>
                @endforelse
            </div>

            @if ($logbooks->count() > 0)
                <p class="text-[10px] text-gray-400 text-center pt-1">
                    Menampilkan {{ $logbooks->count() }} catatan terbaru.
                </p>
            @endif
        </div>

    </div>
</div>
