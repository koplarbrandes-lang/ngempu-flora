<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat Adopsi Pohon — {{ $tree->tree_code }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;900&family=EB+Garamond:ital,wght@0,400;0,500;0,700;1,400;1,600&family=DM+Sans:wght@400;500;600&display=swap');

        :root {
            --moss:  #1F4D3A;
            --gold:  #8B6914;
            --goldb: #C9A227;
            --cream: #FAF5E8;
            --line:  #D4BC7A;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #2a2a2a;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 30px 16px 60px;
            font-family: "DM Sans", sans-serif;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .toolbar a, .toolbar button {
            font: 600 14px "DM Sans", sans-serif;
            padding: 8px 18px;
            border-radius: 8px;
            cursor: pointer;
            border: 0;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .toolbar .btn-print { background: #1F4D3A; color: #fff; }
        .toolbar .btn-back  { background: #444; color: #fff; }

        /* CERTIFICATE PAPER */
        .cert {
            width: 297mm;
            max-width: 100%;
            min-height: 210mm;
            background: var(--cream);
            position: relative;
            padding: 20mm 22mm 18mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 8px 40px rgba(0,0,0,0.5);
        }

        /* OUTER ORNATE BORDER */
        .cert::before {
            content: '';
            position: absolute;
            inset: 10mm;
            border: 3px solid var(--goldb);
            border-radius: 4px;
            pointer-events: none;
        }
        .cert::after {
            content: '';
            position: absolute;
            inset: 12mm;
            border: 1px solid var(--goldb);
            border-radius: 2px;
            pointer-events: none;
        }

        /* CORNER ORNAMENTS */
        .corner {
            position: absolute;
            width: 28mm;
            height: 28mm;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--goldb);
            opacity: 0.6;
        }
        .corner.tl { top: 6mm; left: 6mm; }
        .corner.tr { top: 6mm; right: 6mm; }
        .corner.bl { bottom: 6mm; left: 6mm; }
        .corner.br { bottom: 6mm; right: 6mm; }

        /* HEADER */
        .cert-header {
            text-align: center;
            margin-bottom: 6mm;
            border-bottom: 1.5px solid var(--goldb);
            padding-bottom: 5mm;
            width: 100%;
        }

        .org-name {
            font-family: "Cinzel", serif;
            font-size: 12pt;
            letter-spacing: 0.22em;
            color: var(--moss);
            text-transform: uppercase;
        }
        .org-sub {
            font-size: 9pt;
            color: var(--gold);
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .cert-title {
            font-family: "Cinzel", serif;
            font-size: 20pt;
            font-weight: 900;
            color: var(--moss);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin: 3mm 0 1mm;
            line-height: 1.1;
        }
        .cert-subtitle {
            font-family: "EB Garamond", serif;
            font-style: italic;
            font-size: 12pt;
            color: var(--gold);
        }

        /* DIVIDER */
        .divider {
            width: 80%;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--goldb), transparent);
            margin: 4mm auto;
        }
        .divider.thin {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--line), transparent);
            margin: 2mm auto;
        }

        /* BODY */
        .cert-body {
            text-align: center;
            font-family: "EB Garamond", serif;
            font-size: 12pt;
            color: #2a1e00;
            line-height: 1.7;
            flex: 1;
            width: 100%;
        }

        .cert-body .granted-to {
            font-size: 10pt;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1mm;
        }

        .adopter-name {
            font-family: "Cinzel", serif;
            font-size: 22pt;
            font-weight: 700;
            color: var(--moss);
            letter-spacing: 0.04em;
            line-height: 1.1;
            margin-bottom: 4mm;
        }

        .cert-body p {
            font-size: 11.5pt;
            color: #3a2e00;
            max-width: 80%;
            margin: 0 auto 2mm;
        }

        /* DETAILS GRID */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0 4mm;
            margin: 4mm 0 5mm;
            width: 100%;
        }

        .detail-box {
            background: rgba(31,77,58,0.07);
            border: 1px solid var(--line);
            border-radius: 4px;
            padding: 3mm 4mm;
            text-align: center;
        }
        .detail-box .dl {
            font-family: "DM Sans", sans-serif;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gold);
            font-weight: 600;
        }
        .detail-box .dv {
            font-family: "Cinzel", serif;
            font-size: 9.5pt;
            font-weight: 700;
            color: var(--moss);
            margin-top: 1mm;
            line-height: 1.2;
        }

        /* BARCODE & QR SECTION */
        .verify-section {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 6mm;
            align-items: end;
            margin-top: 4mm;
            padding-top: 4mm;
            border-top: 1px solid var(--line);
        }

        .barcode-area {
            display: flex;
            flex-direction: column;
            gap: 2mm;
        }
        .barcode-label {
            font-family: "DM Sans", sans-serif;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gold);
            font-weight: 600;
        }
        .cert-num {
            font-family: "DM Sans", monospace;
            font-size: 8pt;
            color: var(--moss);
            letter-spacing: 0.12em;
            font-weight: 700;
            margin-top: 1mm;
        }

        .qr-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5mm;
        }
        .qr-area img, .qr-area canvas {
            width: 32mm;
            height: 32mm;
            border: 1px solid var(--line);
            border-radius: 4px;
            padding: 1mm;
            background: #fff;
        }
        .qr-area .qr-label {
            font-size: 7pt;
            color: var(--gold);
            text-align: center;
            font-family: "DM Sans", sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* FOOTER SIGNATURES */
        .cert-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4mm;
            width: 100%;
            margin-top: 5mm;
            padding-top: 3mm;
            border-top: 1.5px solid var(--goldb);
        }
        .signature {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .sig-line {
            width: 55mm;
            height: 1px;
            background: var(--moss);
            margin-bottom: 2mm;
        }
        .sig-name {
            font-family: "Cinzel", serif;
            font-size: 9pt;
            color: var(--moss);
            font-weight: 700;
        }
        .sig-title {
            font-size: 7.5pt;
            color: var(--gold);
            margin-top: 0.5mm;
            letter-spacing: 0.06em;
        }

        /* WATERMARK */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-family: "Cinzel", serif;
            font-size: 52pt;
            font-weight: 900;
            color: rgba(31,77,58,0.045);
            white-space: nowrap;
            pointer-events: none;
            letter-spacing: 0.1em;
            z-index: 0;
        }

        .cert > * { position: relative; z-index: 1; }

        /* QR Code (auto rendered) */
        #qr-canvas { display: none; }

        /* ============ PRINT STYLES ============ */
        @media print {
            @page { size: A4 landscape; margin: 0; }
            body { background: none; padding: 0; }
            .toolbar { display: none !important; }
            .cert {
                width: 297mm;
                min-height: 210mm;
                box-shadow: none;
                padding: 18mm 20mm 16mm;
            }
        }
    </style>
</head>
<body>

{{-- TOOLBAR (hidden when printing) --}}
<div class="toolbar">
    <a href="{{ url()->previous() }}" class="btn-back">← Kembali</a>
    <button onclick="window.print()" class="btn-print">🖨️ Cetak Sertifikat (A4 Landscape)</button>
    <a href="{{ route('certificate.verify', ['codeAndToken' => $tree->full_code_token]) }}" target="_blank" class="btn-back" style="background:#1F4D3A;">
        🔗 Halaman Verifikasi
    </a>
</div>

{{-- CERTIFICATE PAPER --}}
<div class="cert" role="main" aria-label="Sertifikat Adopsi Pohon {{ $tree->tree_code }}">

    {{-- Corner ornaments --}}
    <div class="corner tl">❧</div>
    <div class="corner tr">❧</div>
    <div class="corner bl">❧</div>
    <div class="corner br">❧</div>

    {{-- Watermark --}}
    <div class="watermark">YAYASAN KEDAI MASYARAKAT</div>

    {{-- Header --}}
    <div class="cert-header">
        <div style="margin-bottom:8px;">
            <img src="{{ asset('images/logo-ngempu-flora.jpg') }}" alt="Logo Ngempu Flora" style="width:64px; height:64px; border-radius:50%; object-fit:cover; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
        </div>
        <div class="org-name">Ngempu Flora</div>
        <div class="org-sub">Program Adopsi Pohon Moringga & Inka — Indonesia (Yayasan Kedai Masyarakat)</div>
        <div class="cert-title">Sertifikat Adopsi Pohon</div>
        <div class="cert-subtitle">Certificate of Tree Adoption</div>
    </div>

    {{-- Body --}}
    <div class="cert-body">
        <div class="granted-to">Dengan bangga diberikan kepada</div>
        <div class="adopter-name">{{ strtoupper($tree->adopter_name) }}</div>

        <div class="divider thin"></div>

        <p>
            Atas kepedulian dan kontribusi nyata dalam program adopsi pohon guna menjaga
            kelestarian lingkungan dan mitigasi perubahan iklim melalui penyerapan emisi karbon.
        </p>
        <p style="margin-top:1mm;">
            Pohon <em>{{ $species->name }}</em> (<em>{{ $species->display_name }}</em>) dengan kode
            <strong>{{ $tree->tree_code }}</strong> telah berhasil menyerap
            <strong>{{ $totalCO2Formatted }}</strong> CO₂ dalam program pemantauan <strong>{{ $tree->total_weeks }} bulan</strong>.
        </p>
    </div>

    {{-- Detail grid --}}
    <div class="detail-grid">
        <div class="detail-box">
            <div class="dl">Kode Pohon</div>
            <div class="dv">{{ $tree->tree_code }}</div>
        </div>
        <div class="detail-box">
            <div class="dl">Spesies</div>
            <div class="dv">{{ $species->name }}</div>
        </div>
        <div class="detail-box">
            <div class="dl">Ditanam</div>
            <div class="dv">{{ $tree->planted_date->format('d M Y') }}</div>
        </div>
        <div class="detail-box">
            <div class="dl">CO₂ Diserap</div>
            <div class="dv">{{ $totalCO2Formatted }}</div>
        </div>
    </div>

    {{-- Divider --}}
    <div class="divider"></div>

    {{-- Barcode + QR --}}
    <div class="verify-section">
        <div class="barcode-area">
            <div class="barcode-label">🔍 Kode Barcode Verifikasi Autentisitas</div>
            {!! $barcodeSvg !!}
            <div class="cert-num">No. Sertifikat: {{ $tree->certificate_number }}</div>
        </div>

        <div class="qr-area">
            <div class="qr-label">Scan QR<br>untuk Verifikasi</div>
            <canvas id="qr-canvas" width="128" height="128"></canvas>
            <img id="qr-img" src="" alt="QR Code Verifikasi" style="display:none;">
        </div>
    </div>

    {{-- Footer Signatures --}}
    <div class="cert-footer">
        <div class="signature">
            <div style="height:14mm;"></div>
            <div class="sig-line"></div>
            <div class="sig-name">Direktur Program</div>
            <div class="sig-title">Yayasan Kedai Masyarakat</div>
        </div>
        <div class="signature">
            <div style="height:14mm;"></div>
            <div class="sig-line"></div>
            <div class="sig-name">Koordinator Lapangan</div>
            <div class="sig-title">Pengawas Pohon Adopsi</div>
        </div>
    </div>

</div>

{{-- Inline QR Code Generator (pure JS, no CDN needed) --}}
<script>
// Simple QR via Google Charts or inline rendering
// We'll use a tiny inline QR image via qr-server API-compatible data URI
(function () {
    const verifyUrl = {{ Js::from($verificationUrl) }};
    const img = document.getElementById('qr-img');
    const canvas = document.getElementById('qr-canvas');

    // Attempt to use qrcodejs inline, fallback to a data URL placeholder
    if (img && verifyUrl) {
        // Use a public QR API (works when internet is available, Laragon usually has net)
        img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&ecc=M&margin=6&data=' + encodeURIComponent(verifyUrl);
        img.style.display = 'block';
        img.onerror = function() {
            // Fallback: draw placeholder with verification text
            if (canvas) {
                canvas.style.display = 'block';
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#fff';
                ctx.fillRect(0, 0, 128, 128);
                ctx.fillStyle = '#1F4D3A';
                ctx.font = 'bold 10px monospace';
                ctx.textAlign = 'center';
                ctx.fillText('SCAN TO VERIFY', 64, 20);
                ctx.font = '8px monospace';
                const words = verifyUrl.split('/verify/')[1]?.substring(0, 20) || 'CERT';
                ctx.fillText(words, 64, 64);
                ctx.strokeStyle = '#1F4D3A';
                ctx.lineWidth = 4;
                ctx.strokeRect(2, 2, 124, 124);
            }
        };
    }
})();
</script>
</body>
</html>
