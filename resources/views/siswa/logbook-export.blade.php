<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logbook Kegiatan Magang - {{ $bulanNama }} {{ $tahun }} - {{ $studentName }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-bps-malang.png') }}">
    <style>
        :root {
            --ink: #111;
            --muted: #555;
            --border: #222;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f1f3f5;
            color: var(--ink);
            font-family: Arial, Helvetica, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .toolbar {
            max-width: 210mm;
            margin: 16px auto;
            display: flex;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
            padding: 0 8px;
        }

        .toolbar-info {
            font-size: 13px;
            color: #4b5563;
        }

        .toolbar-actions {
            display: flex;
            gap: 8px;
        }

        button {
            border: 0;
            border-radius: 6px;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            background: #0f172a;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background: #1e293b;
        }

        button.secondary {
            background: #fff;
            color: #111;
            border: 1px solid #d1d5db;
        }

        button.secondary:hover {
            background: #f9fafb;
        }

        .page {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 30px;
            padding: 16mm 18mm 16mm;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .kop {
            display: flex;
            align-items: center;
            gap: 16px;
            min-height: 25mm;
        }

        .logo-img {
            width: 24mm;
            height: 24mm;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-text {
            text-align: center;
            flex: 1;
        }

        .kop-text h2 {
            font-size: 15px;
            font-weight: 800;
            margin: 0 0 2px;
            letter-spacing: 0.5px;
            color: #0b2545;
        }

        .kop-text h3 {
            font-size: 13px;
            font-weight: 700;
            margin: 0 0 4px;
            text-transform: uppercase;
            color: #13315c;
        }

        .kop-text p {
            font-size: 10px;
            margin: 0;
            color: #4b5563;
            line-height: 1.4;
        }

        .kop-rule {
            border: 0;
            border-top: 3px solid var(--ink);
            border-bottom: 1px solid var(--ink);
            height: 5px;
            margin: 10px 0 16px;
        }

        .title {
            text-align: center;
            margin: 0 0 14px;
        }

        .title h1 {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin: 0 0 4px;
            text-transform: uppercase;
        }

        .title p {
            margin: 0;
            font-size: 12px;
            color: #374151;
            font-weight: 600;
        }

        .print-date {
            text-align: right;
            font-size: 10.5px;
            margin: 0 0 12px;
            color: #4b5563;
        }

        .identity {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 20px;
            font-size: 11.5px;
            margin-bottom: 14px;
            background: #fafafa;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }

        .identity-line {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .identity-line .label {
            width: 110px;
            flex-shrink: 0;
            font-weight: 600;
            color: #374151;
        }

        .identity-line .colon {
            flex-shrink: 0;
        }

        .identity-line .val {
            font-weight: 500;
            color: #111827;
            word-break: break-word;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            font-size: 11px;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid var(--border);
            padding: 8px 9px;
            vertical-align: top;
            line-height: 1.45;
        }

        th {
            text-align: center;
            font-weight: bold;
            background: #f1f5f9;
            color: #0f172a;
            font-size: 11px;
        }

        th:nth-child(1),
        td:nth-child(1) {
            width: 18%;
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 42%;
        }

        th:nth-child(3),
        td:nth-child(3) {
            width: 40%;
        }

        td.date-cell {
            text-align: center;
            font-weight: 600;
            font-size: 10.5px;
            color: #1e293b;
        }

        td.activity,
        td.result {
            white-space: pre-line;
            word-break: break-word;
        }

        .empty-row {
            text-align: center;
            padding: 30px 15px;
            color: #6b7280;
            font-style: italic;
        }

        .signature {
            display: flex;
            justify-content: flex-end;
            margin-top: 28px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .signature-block {
            width: 52%;
            text-align: center;
            font-size: 11.5px;
            color: #111;
        }

        .signature-block p {
            margin: 2px 0;
        }

        .signature-space {
            height: 70px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .signature-sub {
            font-size: 10.5px;
            color: #4b5563;
        }

        .footnote {
            margin-top: 24px;
            font-size: 9.5px;
            color: var(--muted);
            border-top: 1px dashed #d1d5db;
            padding-top: 6px;
            display: flex;
            justify-content: space-between;
        }

        @media print {
            body {
                background: white;
            }

            .toolbar {
                display: none;
            }

            .page {
                margin: 0;
                box-shadow: none;
                width: auto;
                min-height: auto;
                padding: 14mm 16mm 14mm;
            }

            th {
                background: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            tr {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .signature {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>

<body>
    <div class="toolbar">
        <div class="toolbar-info">
            <strong>Logbook Bulan:</strong> {{ $bulanNama }} {{ $tahun }} ({{ count($logbooks) }} catatan kegiatan terisi)
        </div>
        <div class="toolbar-actions">
            <button class="secondary" onclick="window.close()">Tutup Halaman</button>
            <button onclick="window.print()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <main class="page">
        <!-- ================= KOP BPS KOTA MALANG ================= -->
        <header class="kop">
            <img src="{{ asset('logo-bps-malang.png') }}" alt="Logo BPS" class="logo-img">
            <div class="kop-text">
                <h2>BADAN PUSAT STATISTIK KOTA MALANG</h2>
                <h3>{{ strtoupper($divisionName) }}</h3>
                <p>Jl. Jenderal A. Yani No. 12 Malang, Jawa Timur 65126<br>
                Telepon: (0341) 491325 · Email: bps3573@bps.go.id · Laman: malangkota.bps.go.id</p>
            </div>
        </header>

        <hr class="kop-rule">

        <!-- ================= JUDUL DOKUMEN ================= -->
        <section class="title">
            <h1>LOGBOOK KEGIATAN MAGANG</h1>
            <p>Periode Bulan: {{ strtoupper($bulanNama) }} {{ $tahun }}</p>
        </section>

        <p class="print-date">Tanggal dicetak: <strong>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</strong></p>

        <!-- ================= IDENTITAS PESERTA ================= -->
        <section class="identity">
            <div class="identity-line">
                <span class="label">Nama Peserta</span>
                <span class="colon">:</span>
                <span class="val">{{ $studentName }}</span>
            </div>
            <div class="identity-line">
                <span class="label">Divisi / Unit</span>
                <span class="colon">:</span>
                <span class="val">{{ $divisionName }}</span>
            </div>
            <div class="identity-line">
                <span class="label">Asal Instansi</span>
                <span class="colon">:</span>
                <span class="val">{{ $institution ?: '-' }}</span>
            </div>
            <div class="identity-line">
                <span class="label">Periode Magang</span>
                <span class="colon">:</span>
                <span class="val">{{ $periodeMagang }}</span>
            </div>
        </section>

        <!-- ================= TABEL CATATAN LOGBOOK BULAN ITU ================= -->
        <table>
            <thead>
                <tr>
                    <th>Hari, Tanggal</th>
                    <th>Yang Dilakukan Hari Ini</th>
                    <th>Hasil Kegiatan Hari Ini</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logbooks as $log)
                    <tr>
                        <td class="date-cell">
                            {{ \Carbon\Carbon::parse($log->date)->locale('id')->isoFormat('dddd') }}<br>
                            <span style="font-size: 9.5px; color: #475569;">
                                {{ \Carbon\Carbon::parse($log->date)->locale('id')->isoFormat('D MMMM Y') }}
                            </span>
                        </td>
                        <td class="activity">{{ trim($log->activity) }}</td>
                        <td class="result">{{ trim($log->result) ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty-row">
                            Belum ada catatan logbook yang diisi untuk bulan {{ $bulanNama }} {{ $tahun }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- ================= TANDA TANGAN ================= -->
        <section class="signature">
            <div class="signature-block">
                <p>Malang, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
                <p>Mengetahui,</p>
                <p style="font-weight: 600;">Ketua Divisi / Pembimbing Lapangan</p>
                <div class="signature-space"></div>
                <p class="signature-name">{{ $leaderName ?: '......................................................' }}</p>
                <p class="signature-sub">NIP. {{ $leaderNip ?: '......................................................' }}</p>
            </div>
        </section>

        <footer class="footnote">
            <span>Sistem Informasi Manajemen Magang - BPS Kota Malang</span>
            <span>Dokumen ini dihasilkan secara otomatis oleh sistem</span>
        </footer>
    </main>

    <script>
        // Auto focus for immediate printing if triggered with ?print=1
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.addEventListener('load', () => window.print());
        }
    </script>
</body>

</html>
