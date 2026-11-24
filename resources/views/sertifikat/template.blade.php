<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Magang - {{ $certificate->student_name }} - BPS Kota Malang</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #e2e8f0;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 16px;
        }

        /* Top Action Toolbar */
        .toolbar {
            width: 100%;
            max-width: 297mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: #0f172a;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #000000;
        }

        .btn-secondary {
            background: #ffffff;
            color: #334155;
            border-color: #cbd5e1;
        }

        .btn-secondary:hover {
            background: #f8fafc;
        }

        /* Certificate A4 Landscape Frame */
        .cert-container {
            width: 297mm;
            height: 210mm;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            position: relative;
            padding: 14mm 16mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        /* Outer & Inner Decorative Borders */
        .cert-border-outer {
            position: absolute;
            inset: 8mm;
            border: 3px double #0f766e;
            pointer-events: none;
        }

        .cert-border-inner {
            position: absolute;
            inset: 10mm;
            border: 1px solid #d97706;
            pointer-events: none;
        }

        /* Corner Ornaments */
        .cert-corner {
            position: absolute;
            width: 24px;
            height: 24px;
            border-color: #0f766e;
            border-style: solid;
        }
        .cert-corner-tl { top: 7mm; left: 7mm; border-width: 3px 0 0 3px; }
        .cert-corner-tr { top: 7mm; right: 7mm; border-width: 3px 3px 0 0; }
        .cert-corner-bl { bottom: 7mm; left: 7mm; border-width: 0 0 3px 3px; }
        .cert-corner-br { bottom: 7mm; right: 7mm; border-width: 0 3px 3px 0; }

        /* Header / Kop */
        .cert-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            text-align: center;
            position: relative;
            z-index: 2;
            padding-bottom: 8px;
            border-bottom: 2px solid #0f766e;
        }

        .cert-logo {
            width: 75px;
            height: auto;
            object-contain: fit;
        }

        .cert-inst-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f766e;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .cert-main-title {
            font-family: 'Cinzel', serif;
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 3px;
            margin: 2px 0;
            text-transform: uppercase;
        }

        .cert-num {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
            letter-spacing: 1px;
        }

        /* Certificate Body */
        .cert-body {
            text-align: center;
            position: relative;
            z-index: 2;
            padding: 10px 40px;
        }

        .cert-given-to {
            font-size: 13px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .cert-student-name {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 1px;
            margin: 4px 0;
        }

        .cert-inst-name {
            font-size: 14px;
            font-weight: 600;
            color: #0f766e;
            margin-bottom: 14px;
        }

        .cert-statement {
            font-size: 12.5px;
            line-height: 1.7;
            color: #334155;
            max-width: 780px;
            margin: 0 auto 14px;
        }

        .cert-predicate-box {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 22px;
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 30px;
            font-weight: 800;
            font-size: 13px;
            color: #92400e;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* Footer & Signatures */
        .cert-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            position: relative;
            z-index: 2;
            padding-top: 10px;
        }

        .cert-verification-box {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 10px;
            color: #64748b;
            text-align: left;
        }

        .cert-qr-mock {
            width: 58px;
            height: 58px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f766e;
            font-size: 26px;
        }

        .cert-signature-box {
            text-align: center;
            min-width: 250px;
        }

        .cert-sign-date {
            font-size: 11px;
            color: #475569;
            margin-bottom: 4px;
        }

        .cert-sign-pos {
            font-size: 11.5px;
            font-weight: 600;
            color: #1e293b;
        }

        .cert-sign-space {
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cert-stamp-badge {
            font-family: 'Cinzel', serif;
            font-size: 9px;
            color: #0f766e;
            border: 1.5px dashed #0f766e;
            padding: 3px 10px;
            border-radius: 4px;
            letter-spacing: 1px;
            text-transform: uppercase;
            opacity: 0.85;
        }

        .cert-sign-name {
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
            border-bottom: 1px solid #0f172a;
            display: inline-block;
            padding-bottom: 2px;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .toolbar {
                display: none !important;
            }

            .cert-container {
                box-shadow: none !important;
                margin: 0 !important;
                width: 297mm !important;
                height: 210mm !important;
                page-break-after: avoid !important;
            }

            @page {
                size: A4 landscape;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Action Toolbar (Hidden in Print) -->
    <div class="toolbar">
        <a href="javascript:history.back()" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <div style="display: flex; gap: 8px;">
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Unduh PDF</span>
            </button>
        </div>
    </div>

    <!-- Official Certificate Frame -->
    <div class="cert-container">
        <!-- Decorative Frames -->
        <div class="cert-border-outer"></div>
        <div class="cert-border-inner"></div>
        <div class="cert-corner cert-corner-tl"></div>
        <div class="cert-corner cert-corner-tr"></div>
        <div class="cert-corner cert-corner-bl"></div>
        <div class="cert-corner cert-corner-br"></div>

        <!-- Header -->
        <div class="cert-header">
            <img src="{{ asset('logo-bps-malang.png') }}" alt="Logo BPS" class="cert-logo">
            <div>
                <div class="cert-inst-title">Badan Pusat Statistik Kota Malang</div>
                <h1 class="cert-main-title">Sertifikat Magang</h1>
                <div class="cert-num">Nomor: {{ $certificate->certificate_number }}</div>
            </div>
        </div>

        <!-- Body Content -->
        <div class="cert-body">
            <div class="cert-given-to">Diberikan Kepada :</div>
            <h2 class="cert-student-name">{{ $certificate->student_name }}</h2>
            <div class="cert-inst-name">{{ $certificate->institution }}</div>

            <p class="cert-statement">
                Telah berhasil melaksanakan dan menyelesaikan program <strong>Praktik Kerja Lapangan (PKL) / Magang</strong> 
                pada <strong>Badan Pusat Statistik Kota Malang</strong>, bertempat pada <strong>{{ $certificate->division_name }}</strong> 
                terhitung sejak tanggal 
                <strong>{{ $certificate->start_date ? \Carbon\Carbon::parse($certificate->start_date)->translatedFormat('d F Y') : '-' }}</strong> 
                sampai dengan 
                <strong>{{ $certificate->end_date ? \Carbon\Carbon::parse($certificate->end_date)->translatedFormat('d F Y') : '-' }}</strong> 
                dengan hasil predikat kelulusan :
            </p>

            <div>
                <div class="cert-predicate-box">
                    <i class="fa-solid fa-medal"></i>
                    <span>Predikat: {{ $certificate->predicate }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="cert-footer">
            <!-- QR / Security Validation Box -->
            <div class="cert-verification-box">
                <div class="cert-qr-mock">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div>
                    <strong style="color: #0f172a; display: block;">Dokumen Resmi Terverifikasi</strong>
                    <span>BPS Kota Malang</span><br>
                    <span>ID Sertifikat: #{{ str_pad($certificate->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <!-- Signature Box -->
            <div class="cert-signature-box">
                <div class="cert-sign-date">Malang, {{ $certificate->issue_date ? \Carbon\Carbon::parse($certificate->issue_date)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</div>
                <div class="cert-sign-pos">{{ $certificate->signer_position }}</div>
                
                <div class="cert-sign-space">
                    <span class="cert-stamp-badge">
                        <i class="fa-solid fa-stamp"></i> TTD & CAP RESMI
                    </span>
                </div>

                <div class="cert-sign-name">{{ $certificate->signer_name }}</div>
            </div>
        </div>
    </div>

</body>
</html>
