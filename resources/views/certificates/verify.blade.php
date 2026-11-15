<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Sertifikat — {{ $tree->certificate_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #F1F5F2; --sf: #fff; --ink: #14231B; --mut: #5B6B62;
            --line: #D8E2DB; --moss: #1F4D3A; --leaf: #4FA36B; --soft: #E3EEE6;
        }
        * { box-sizing: border-box; margin: 0; }
        body {
            background: var(--bg);
            color: var(--ink);
            font: 400 15.5px/1.5 "DM Sans", system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .wrap { max-width: 560px; width: 100%; }
        .card {
            background: var(--sf);
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 32px 28px;
            text-align: center;
        }
        .seal { font-size: 54px; margin-bottom: 12px; }
        h1 {
            font-family: "Bricolage Grotesque", sans-serif;
            font-size: 22px;
            color: var(--moss);
            letter-spacing: -.02em;
            margin-bottom: 6px;
        }
        .valid-badge {
            display: inline-block;
            background: #E3EEE6;
            color: var(--moss);
            font-weight: 700;
            font-size: 14px;
            padding: 6px 20px;
            border-radius: 99px;
            border: 2px solid var(--leaf);
            margin: 12px 0 20px;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14.5px;
            margin-top: 16px;
        }
        .detail-table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
        }
        .detail-table td:first-child {
            color: var(--mut);
            font-weight: 600;
            width: 42%;
        }
        .detail-table td:last-child {
            font-weight: 600;
            color: var(--ink);
        }
        .cert-code {
            font-family: monospace;
            font-size: 13px;
            background: var(--soft);
            padding: 3px 8px;
            border-radius: 6px;
            color: var(--moss);
        }
        .btn {
            display: inline-block;
            margin-top: 22px;
            background: var(--moss);
            color: #fff;
            font: 600 14px "DM Sans", sans-serif;
            padding: 10px 22px;
            border-radius: 10px;
            text-decoration: none;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: var(--mut);
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div style="margin-bottom:12px;">
            <img src="{{ asset('images/logo-ngempu-flora.jpg') }}" alt="Logo Ngempu Flora" style="width:58px; height:58px; border-radius:50%; object-fit:cover; box-shadow:0 2px 8px rgba(0,0,0,0.12);">
        </div>
        <div class="seal">✅</div>
        <h1>Sertifikat Valid & Terverifikasi</h1>
        <p style="color:var(--mut); font-size:14px;">
            Sertifikat adopsi pohon berikut telah terverifikasi dalam sistem resmi <b>Ngempu Flora</b> (Yayasan Kedai Masyarakat).
        </p>

        <div class="valid-badge">🌱 SERTIFIKAT RESMI DIAKUI</div>

        <table class="detail-table">
            <tr>
                <td>No. Sertifikat</td>
                <td><span class="cert-code">{{ $tree->certificate_number }}</span></td>
            </tr>
            <tr>
                <td>Kode Pohon</td>
                <td>{{ $tree->tree_code }}</td>
            </tr>
            <tr>
                <td>Nama Adopter</td>
                <td>{{ $tree->adopter_name }}</td>
            </tr>
            <tr>
                <td>Spesies Pohon</td>
                <td>{{ $species->name }} <span style="color:var(--mut); font-weight:400;"><i>{{ $species->display_name }}</i></span></td>
            </tr>
            <tr>
                <td>Tanggal Tanam</td>
                <td>{{ $tree->planted_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Plot / Lokasi</td>
                <td>{{ $tree->plot ?? 'Semaya' }}</td>
            </tr>
            <tr>
                <td>Perkembangan</td>
                <td>Bulan {{ $tree->current_week }} / {{ $tree->total_weeks }}</td>
            </tr>
            <tr>
                <td>CO₂ Diserap</td>
                <td>{{ $totalCO2Formatted }}</td>
            </tr>
            @if ($latest)
            <tr>
                <td>Pengukuran Terakhir</td>
                <td>
                    {{ $latest->measurement_cm }} cm
                    @if ($latest->logged_at)
                        <span style="color:var(--mut); font-weight:400;">• {{ $latest->logged_at->format('d M Y') }}</span>
                    @endif
                </td>
            </tr>
            @endif
        </table>

        <a href="{{ route('tracker.show', ['codeAndToken' => $tree->full_code_token]) }}" class="btn">
            Pantau Perkembangan Pohon ↗
        </a>

        <div class="footer">
            Verifikasi dilakukan secara real-time dari database resmi Yayasan Kedai Masyarakat.<br>
            Diverifikasi: {{ now()->format('d F Y, H:i') }} WIB
        </div>
    </div>
</div>
</body>
</html>
