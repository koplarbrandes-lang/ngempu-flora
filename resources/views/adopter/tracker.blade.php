<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $tree->tree_code }} — {{ $tree->species->name }} • Pemantauan Pertumbuhan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #F1F5F2;
            --surface: #FFFFFF;
            --ink: #14231B;
            --muted: #5B6B62;
            --line: #D8E2DB;
            --moss: #1F4D3A;
            --leaf: #4FA36B;
            --sprout: #B7E07A;
            --bark: #6E5644;
            --soft: #E3EEE6;
            --locked: #E8EEEA;
            box-sizing: border-box;
            padding-top: env(safe-area-inset-top, 0px);
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --bg: #0F1A14;
                --surface: #16261D;
                --ink: #EAF3EC;
                --muted: #9BB0A2;
                --line: #264034;
                --moss: #8FD3A4;
                --leaf: #5DBB7B;
                --sprout: #C6EB8B;
                --bark: #A58468;
                --soft: #1C3227;
                --locked: #1A2B21;
            }
        }
        * { box-sizing: border-box; }
        html, body { margin: 0; }
        body {
            background: var(--bg);
            color: var(--ink);
            font: 400 16px/1.55 "DM Sans", system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .wrap {
            max-width: 720px;
            margin: 0 auto;
            padding: 20px 18px 48px;
        }
        h1, h2, .num {
            font-family: "Bricolage Grotesque", "DM Sans", system-ui, sans-serif;
            letter-spacing: -.02em;
            margin: 0;
        }
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }
        .brand {
            font-weight: 700;
            color: var(--moss);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        select {
            font: inherit;
            color: var(--ink);
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 8px 12px;
            max-width: 60%;
        }
        .hero {
            background: var(--moss);
            color: #F1F8F3;
            border-radius: 24px;
            padding: 26px 22px 0;
            overflow: hidden;
            position: relative;
        }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) .hero {
                background: #1B3A2B;
                color: #EAF3EC;
            }
        }
        .hero h1 {
            font-size: clamp(28px, 7vw, 42px);
            line-height: 1.05;
            font-weight: 700;
        }
        .hero p {
            margin: 8px 0 0;
            opacity: .85;
            max-width: 38ch;
            font-size: 15px;
        }
        .hero .meta {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 14px;
            font-size: 13.5px;
            opacity: .92;
        }
        .hero .meta b {
            font-weight: 600;
            display: block;
            font-size: 14.5px;
        }
        .hero-media-wrap {
            position: relative;
            width: 100%;
            max-width: 480px;
            margin: 12px auto 0;
            min-height: 260px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .hero-real-photo {
            width: 100%;
            max-height: 320px;
            object-fit: cover;
            border-radius: 16px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
            display: block;
            margin-bottom: 12px;
        }
        .hero-photo-tag {
            position: absolute;
            bottom: 22px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 46, 34, 0.88);
            color: #fff;
            padding: 4px 14px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .02em;
            backdrop-filter: blur(4px);
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .scene .t {
            transform-box: fill-box;
            transform-origin: 50% 100%;
            transition: transform .7s cubic-bezier(.3, .8, .3, 1);
        }
        .scene .c {
            transform-box: fill-box;
            transform-origin: 50% 50%;
            transition: transform .6s cubic-bezier(.3, 1.3, .5, 1);
        }
        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 20px;
            margin-top: 16px;
        }
        .card h2 { font-size: 20px; font-weight: 700; }
        .sub { color: var(--muted); font-size: 14px; margin: 3px 0 0; }
        .bar {
            height: 12px;
            border-radius: 99px;
            background: var(--soft);
            overflow: hidden;
            margin-top: 14px;
        }
        .bar i {
            display: block;
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--leaf), var(--sprout));
            border-radius: 99px;
            transition: width .8s ease;
        }
        .barlab {
            display: flex;
            justify-content: space-between;
            font-size: 13.5px;
            color: var(--muted);
            margin-top: 8px;
        }
        .weeks {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 8px;
            margin-top: 16px;
        }
        @media (min-width: 520px) {
            .weeks { grid-template-columns: repeat(8, 1fr); }
        }
        .wk {
            font: 600 14px "DM Sans", sans-serif;
            border: 1px solid var(--line);
            background: var(--surface);
            color: var(--ink);
            border-radius: 12px;
            padding: 0;
            height: 52px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .wk span { position: relative; z-index: 1; padding-bottom: 6px; }
        .wk::before {
            content: "";
            position: absolute;
            left: 0; right: 0; bottom: 0;
            height: var(--g, 15%);
            background: var(--soft);
            transition: background .2s;
        }
        .wk:hover:not(:disabled)::before { background: var(--sprout); }
        .wk[aria-pressed="true"] { border: 2px solid var(--moss); }
        .wk[aria-pressed="true"]::before { background: var(--leaf); }
        .wk[aria-pressed="true"] span { color: #fff; }
        .wk:disabled {
            cursor: not-allowed;
            color: var(--muted);
            background: var(--locked);
            border-style: dashed;
        }
        .wk:disabled::before { display: none; }
        .detail {
            margin-top: 18px;
            border-top: 1px solid var(--line);
            padding-top: 18px;
        }
        .detail h3 {
            margin: 0;
            font: 700 22px "Bricolage Grotesque", sans-serif;
            letter-spacing: -.02em;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 14px 0;
        }
        .stat {
            background: var(--soft);
            border-radius: 14px;
            padding: 12px;
        }
        .stat .num {
            font-size: 24px;
            font-weight: 700;
            color: var(--moss);
            line-height: 1.1;
        }
        .stat small { color: var(--muted); font-size: 12px; }
        .photo-preview {
            margin-top: 14px;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--line);
        }
        .photo-preview img {
            width: 100%;
            height: auto;
            display: block;
            max-height: 380px;
            object-fit: cover;
        }
        .calc {
            margin: 14px 0 0;
            padding: 14px 16px;
            border-left: 4px solid var(--leaf);
            background: var(--soft);
            border-radius: 0 12px 12px 0;
            font-size: 14px;
        }
        .calc b { display: block; color: var(--moss); margin-bottom: 2px; }
        .chart { width: 100%; height: auto; margin-top: 8px; }
        .qr {
            display: flex;
            gap: 18px;
            align-items: center;
            flex-wrap: wrap;
        }
        #qrbox {
            background: #fff;
            padding: 10px;
            border-radius: 14px;
            border: 1px solid var(--line);
            min-width: 140px;
            min-height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn {
            font: 600 14px "DM Sans", sans-serif;
            background: var(--moss);
            color: #fff;
            border: 0;
            border-radius: 10px;
            padding: 10px 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .urlt {
            font-size: 13px;
            color: var(--muted);
            word-break: break-all;
            margin: 8px 0 0;
            max-width: 36ch;
        }
        footer {
            margin-top: 26px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="top">
        <a href="{{ url('/') }}" class="brand" title="Menuju Landing Page Utama Ngempu Flora">
            <img src="{{ asset('images/logo-ngempu-flora.jpg') }}" alt="Logo Ngempu Flora" style="width:34px; height:34px; border-radius:50%; object-fit:cover; box-shadow:0 2px 6px rgba(0,0,0,0.15);">
            <span>Ngempu Flora</span>
        </a>
        <div style="display:flex; align-items:center; gap:8px;">
            @if(count($allTrees) > 1)
                <select id="pick" aria-label="Pilih pohon adopsi Anda" onchange="window.location.href=this.value;">
                    @foreach ($allTrees as $t)
                        <option value="{{ route('tracker.show', ['codeAndToken' => $t->full_code_token]) }}" @selected($t->id === $tree->id)>
                            {{ $t->tree_code }} • {{ $t->species->name }}
                        </option>
                    @endforeach
                </select>
            @endif
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn" style="padding:7px 12px; font-size:13px;">
                        👨‍💼 Admin
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn" style="padding:7px 12px; font-size:13px;">
                        🌿 Dashboard Saya
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn" style="padding:7px 12px; font-size:13px; background:var(--soft); color:var(--moss);">
                        Keluar
                    </button>
                </form>
            @endauth
        </div>
    </div>

    <!-- HERO SECTION DENGAN SVG TUMBUH -->
    <section class="hero" aria-labelledby="tname">
        <h1 id="tname">{{ $tree->species->name }} {{ $tree->tree_code }}</h1>
        <p id="tsub">{{ $tree->species->display_name }} • Diadopsi oleh <b>{{ $tree->adopter_name }}</b></p>
        
        <div class="meta">
            <div>Ditanam: <b>{{ $tree->planted_date->translatedFormat('d F Y') }}</b></div>
            <div>Plot: <b>{{ $tree->plot ?? 'Semaya' }}</b></div>
            <div>Program: <b>{{ $totalWeeks }} Bulan</b></div>
        </div>

        <div class="hero-media-wrap">
            <svg id="hero-scene" class="scene" viewBox="0 0 360 300" role="img" aria-label="Ilustrasi pertumbuhan pohon" style="{{ $activeData['photo_url'] ? 'display:none;' : '' }}">
                <ellipse cx="180" cy="278" rx="150" ry="14" fill="#0F2E22" opacity=".45"/>
                <path d="M0 270 Q90 252 180 266 T360 262 V300 H0Z" fill="#2E6A4F"/>
                <g id="tree-svg"></g>
            </svg>
            <div id="hero-photo-wrap" style="{{ $activeData['photo_url'] ? '' : 'display:none;' }}; width:100%; position:relative;">
                <img id="hero-tree-photo" class="hero-real-photo" src="{{ $activeData['photo_url'] ?? '' }}" alt="Foto perkembangan asli pohon">
                <span class="hero-photo-tag" id="hero-photo-tag">📷 Foto Lapangan Bulan ke-{{ $activeWeek }}</span>
            </div>
        </div>
    </section>

    <!-- KARTU PROGRESS BULANAN -->
    <section class="card" aria-labelledby="ph">
        <h2 id="ph">Perkembangan Bulanan</h2>
        <p class="sub" id="psub">
            Bulan ke-{{ $activeWeek }} dari {{ $totalWeeks }} bulan program adopsi
        </p>

        <div class="bar">
            <i id="progress-bar" style="width: {{ round(($activeWeek / $totalWeeks) * 100) }}%;"></i>
        </div>
        <div class="barlab">
            <span>Bulan 1</span>
            <span>Target: Bulan {{ $totalWeeks }}</span>
        </div>

        <div class="weeks" id="weeks-container">
            @foreach ($weeklyData as $w => $data)
                <button type="button" 
                    class="wk" 
                    data-week="{{ $w }}"
                    @if(!$data['is_unlocked']) disabled @endif
                    @if($w === $activeWeek) aria-pressed="true" @endif
                    style="--g: {{ min(100, round(($w / $totalWeeks) * 100)) }}%;">
                    <span>{{ $w }}</span>
                </button>
            @endforeach
        </div>

        <!-- DETAIL BULAN TERPILIH -->
        <div class="detail">
            <h3 id="detail-title">Bulan ke-{{ $activeWeek }}</h3>
            
            <div class="stats">
                <div class="stat">
                    <div class="num" id="stat-height">{{ $activeData['measurement_cm'] }}</div>
                    <small id="stat-label">{{ $species->label }} (cm)</small>
                </div>
                <div class="stat">
                    <div class="num" id="stat-leaves">{{ $activeData['leaf_count'] ?? '—' }}</div>
                    <small>Jumlah Daun</small>
                </div>
                <div class="stat">
                    <div class="num" id="stat-co2">{{ $activeData['total_co2_formatted'] }}</div>
                    <small>CO₂ Terserap</small>
                </div>
            </div>

            <p id="week-note" style="margin:8px 0; color:var(--ink);">
                {{ $activeData['note'] }}
            </p>

            <div class="calc">
                <b>Metode Ilmiah Perhitungan Emisi Karbon</b>
                <span id="calc-text">
                    {{ $species->label }} {{ $activeData['measurement_cm'] }} cm menghasilkan perkiraan biomassa kering {{ $activeData['dry_biomass_kg'] }} kg (termasuk akar). Sesuai standar IPCC, biomassa tanaman mengandung 47% karbon organik yang dikonversikan dengan rasio molekul CO₂ (44/12 ≈ 3.67) menghasilkan serapan {{ $activeData['total_co2_formatted'] }}.
                </span>
            </div>
        </div>
    </section>

    <!-- GRAFIK PERTUMBUHAN SVG -->
    <section class="card">
        <h2>Grafik Pertumbuhan</h2>
        <p class="sub">Kurva tinggi / ukuran tanaman sepanjang minggu</p>
        <svg id="chart-svg" class="chart" viewBox="0 0 640 180"></svg>
    </section>

    <!-- BAGIKAN & QR CODE -->
    <section class="card qr">
        <div id="qrbox"></div>
        <div>
            <h2>Akses QR Code</h2>
            <p class="sub">Pindai kode ini untuk membuka perkembangan pohon di smartphone Anda.</p>
            <p class="urlt" id="url-text">{{ route('tracker.show', ['codeAndToken' => $tree->full_code_token]) }}</p>
            <button class="btn" style="margin-top:10px;" onclick="copyUrl()">Salin Tautan</button>
        </div>
    </section>

    <footer>
        <p>ADOPT TREE — YAYASAN KEDAI MASYARAKAT</p>
        <p style="margin-top:4px;"><a href="{{ route('admin.dashboard') }}" style="color:var(--muted); font-size:12px;">Masuk ke Panel Admin</a></p>
    </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const TOTAL_WEEKS = {{ $totalWeeks }};
    const treeSpeciesKey = "{{ $species->key }}";
    const weeklyData = @json($weeklyData);
    let currentActiveWeek = {{ $activeWeek }};

    function drawTreeShape(w) {
        const p = w / TOTAL_WEEKS;
        const th = 30 + p * 130;
        const top = 268 - th;
        const blobs = [[0,-6,26],[-34,14,22],[34,14,22],[-20,-30,20],[22,-32,20],[0,-52,18],[-52,34,16],[52,34,16]];
        const need = Math.max(1, Math.ceil(p * blobs.length));
        const scale = .6 + p * .9;
        
        let svg = `<g class="t" style="transform:scaleY(${(th / 160).toFixed(3)})">
            <path d="M172 268 L176 108 L184 108 L188 268Z" fill="var(--bark)" transform="translate(0,-8)"/>
            <path d="M180 200 Q205 185 218 160" stroke="var(--bark)" stroke-width="4" fill="none" stroke-linecap="round" opacity="${p > .4 ? 1 : 0}"/>
        </g>`;
        
        const cy = top + 10;
        const cols = ['#3E8F5C', '#4FA36B', '#66B97F', '#7FCB8F'];
        blobs.forEach((b, i) => {
            const on = i < need;
            svg += `<circle class="c" cx="${180 + b[0] * scale}" cy="${cy + b[1] * scale}" r="${b[2] * scale}" fill="${cols[i % 4]}" style="transform:scale(${on ? 1 : 0})"/>`;
        });

        if (w <= 2) {
            svg = `<path d="M180 268 V236" stroke="#B7E07A" stroke-width="4" stroke-linecap="round"/>
                   <ellipse cx="168" cy="232" rx="14" ry="8" fill="#7FCB8F" transform="rotate(-25 168 232)"/>
                   <ellipse cx="192" cy="228" rx="14" ry="8" fill="#4FA36B" transform="rotate(25 192 228)"/>`;
        }

        document.getElementById('tree-svg').innerHTML = svg;
    }

    function drawVine(w) {
        const p = w / TOTAL_WEEKS;
        const yT = 268 - (24 + p * 176);
        const n = Math.max(2, Math.round(p * 14));
        let s = '<path d="M118 270V66M242 270V66" stroke="#8B6F58" stroke-width="5" stroke-linecap="round"/><path d="M118 110H242M118 170H242M118 230H242" stroke="#8B6F58" stroke-width="2" opacity=".6"/>';
        s += `<path d="M180 268 Q150 ${(268 + yT) / 2} 180 ${yT}" stroke="#3E8F5C" stroke-width="4" fill="none" stroke-linecap="round"/>`;
        
        for (let i = 0; i < n; i++) {
            const ly = 260 - (i / n) * (260 - yT);
            const side = i % 2 === 0 ? -1 : 1;
            s += `<path d="M180 ${ly} Q${180 + side * 22} ${ly - 8} ${180 + side * 30} ${ly + 4} Q${180 + side * 15} ${ly + 14} 180 ${ly}" fill="#4FA36B"/>`;
        }

        document.getElementById('tree-svg').innerHTML = s;
    }

    function renderTree(w) {
        if (treeSpeciesKey === 'inca') {
            drawVine(w);
        } else {
            drawTreeShape(w);
        }
    }

    function selectWeek(w) {
        currentActiveWeek = w;
        const data = weeklyData[w];
        if (!data) return;

        // Update Buttons
        document.querySelectorAll('.wk').forEach(btn => {
            btn.setAttribute('aria-pressed', parseInt(btn.dataset.week) === w ? 'true' : 'false');
        });

        // Update Detail Texts
        document.getElementById('detail-title').textContent = `Bulan ke-${w}`;
        document.getElementById('stat-height').textContent = data.measurement_cm;
        document.getElementById('stat-leaves').textContent = data.leaf_count || '—';
        document.getElementById('stat-co2').textContent = data.total_co2_formatted;
        document.getElementById('week-note').textContent = data.note;

        // Photo handling in Hero banner
        const heroScene = document.getElementById('hero-scene');
        const heroPhotoWrap = document.getElementById('hero-photo-wrap');
        const heroTreePhoto = document.getElementById('hero-tree-photo');
        const heroPhotoTag = document.getElementById('hero-photo-tag');

        if (data.photo_url) {
            heroTreePhoto.src = data.photo_url;
            heroPhotoTag.textContent = `📷 Foto Lapangan Bulan ke-${w}`;
            heroPhotoWrap.style.display = 'block';
            heroScene.style.display = 'none';
        } else {
            heroPhotoWrap.style.display = 'none';
            heroScene.style.display = 'block';
        }

        // Calculation text
        document.getElementById('calc-text').textContent = 
            `Ukuran ${data.measurement_cm} cm menghasilkan estimasi biomassa kering ${data.dry_biomass_kg} kg (termasuk akar). Sesuai standar IPCC, biomassa mengandung 47% karbon organik yang dikonversikan dengan rasio molekul CO₂ (44/12 ≈ 3.67) menghasilkan serapan ${data.total_co2_formatted}.`;

        // Render Tree Illustration
        renderTree(w);
        drawChart();
    }

    function drawChart() {
        const W = 640, H = 180, px = 38, py = 20;
        const pts = [];
        let maxH = 0;

        for (let w = 1; w <= TOTAL_WEEKS; w++) {
            const h = weeklyData[w].measurement_cm;
            if (h > maxH) maxH = h;
            if (w <= {{ $tree->current_week }}) {
                pts.push([w, h]);
            }
        }
        maxH = Math.max(maxH, 100);

        const X = w => px + (w - 1) / (TOTAL_WEEKS - 1) * (W - px - 15);
        const Y = h => H - py - (h / maxH) * (H - py * 2);

        let s = '';
        [0, 0.5, 1].forEach(f => {
            const y = Y(maxH * f);
            s += `<line x1="${px}" x2="${W - 15}" y1="${y}" y2="${y}" stroke="var(--line)"/>
                  <text x="${px - 6}" y="${y + 4}" text-anchor="end" font-size="11" fill="var(--muted)">${Math.round(maxH * f)}</text>`;
        });

        s += `<polyline fill="none" stroke="var(--leaf)" stroke-width="3" stroke-linejoin="round" stroke-linecap="round" points="${pts.map(p => X(p[0]) + ',' + Y(p[1])).join(' ')}"/>`;
        s += `<circle cx="${X(currentActiveWeek)}" cy="${Y(weeklyData[currentActiveWeek].measurement_cm)}" r="6" fill="var(--moss)" stroke="var(--surface)" stroke-width="3"/>`;
        s += `<text x="${px}" y="${H - 2}" font-size="11" fill="var(--muted)">Bulan 1</text>
              <text x="${W - 15}" y="${H - 2}" text-anchor="end" font-size="11" fill="var(--muted)">Bulan ${TOTAL_WEEKS}</text>`;

        document.getElementById('chart-svg').innerHTML = s;
    }

    // Init Week Button Clicks
    document.querySelectorAll('.wk:not(:disabled)').forEach(b => {
        b.addEventListener('click', () => {
            selectWeek(parseInt(b.dataset.week));
        });
    });

    // Init QR Code
    const qrUrl = document.getElementById('url-text').textContent;
    new QRCode(document.getElementById('qrbox'), {
        text: qrUrl,
        width: 120,
        height: 120,
        colorDark: '#14231B',
        colorLight: '#FFFFFF',
        correctLevel: QRCode.CorrectLevel.M
    });

    function copyUrl() {
        navigator.clipboard.writeText(qrUrl).then(() => {
            alert('Tautan pohon berhasil disalin ke clipboard!');
        });
    }

    // Initial render
    renderTree(currentActiveWeek);
    drawChart();
</script>
</body>
</html>
