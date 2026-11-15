<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Ngempu Flora — Solusi Iklim Nyata dengan Adopsi Moringa & Kacang Inka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #FAFAF7;
            --surface: #FFFFFF;
            --ink: #111A14;
            --muted: #5A6D61;
            --line: #E3E9E4;
            --forest: #143823;
            --forest-dark: #0A2013;
            --leaf: #2E7D47;
            --leaf-bright: #3E9B5B;
            --sprout: #A3D866;
            --soft: #F0F5F1;
            --gold: #D97706;
            --gold-bg: #FEF3C7;
            --border-radius: 24px;
        }

        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --bg: #0B140F;
                --surface: #132219;
                --ink: #EDF5EF;
                --muted: #91A698;
                --line: #1F3628;
                --forest: #6EC48B;
                --forest-dark: #08120D;
                --leaf: #44A262;
                --leaf-bright: #56B774;
                --sprout: #BDE486;
                --soft: #172A1F;
                --gold: #FBBF24;
                --gold-bg: #352608;
            }
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font: 400 16px/1.65 "Plus Jakarta Sans", system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .brand-font {
            font-family: "Bricolage Grotesque", system-ui, sans-serif;
            letter-spacing: -.03em;
            margin: 0;
        }

        .container {
            max-width: 1160px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* NAVBAR */
        nav.navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(250, 250, 247, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--line);
            padding: 16px 0;
            transition: all .2s;
        }
        @media (prefers-color-scheme: dark) {
            nav.navbar {
                background: rgba(11, 20, 15, 0.88);
            }
        }
        .nav-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }
        .brand {
            font-family: "Bricolage Grotesque", sans-serif;
            font-size: 21px;
            font-weight: 800;
            color: var(--forest);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -.02em;
            transition: transform .15s ease;
        }
        .brand:hover {
            transform: translateY(-1px);
        }
        .brand-logo-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(20, 56, 35, 0.18);
            display: block;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        @media(max-width: 890px) {
            .nav-links { display: none; }
        }
        .nav-links a {
            color: var(--muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 14.5px;
            transition: color .2s;
        }
        .nav-links a:hover { color: var(--forest); }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* BUTTONS */
        .btn {
            font: 600 14px "Plus Jakarta Sans", sans-serif;
            background: #143823;
            color: #FFFFFF;
            border: 0;
            border-radius: 999px;
            padding: 10px 22px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all .2s cubic-bezier(.16, 1, .3, 1);
            box-shadow: 0 4px 12px rgba(20, 56, 35, 0.16);
        }
        .btn:hover {
            background: #0A2013;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(20, 56, 35, 0.22);
        }
        .btn:active {
            transform: translateY(0);
        }
        .btn-alt {
            background: #FFFFFF;
            color: #111A14;
            border: 1px solid var(--line);
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }
        .btn-alt:hover {
            background: var(--soft);
            border-color: #C7D4CA;
            color: var(--forest);
        }
        .btn-lg {
            padding: 13px 28px;
            font-size: 15.5px;
            border-radius: 999px;
        }

        /* HERO SECTION WITH CLIMATE SPLIT BACKGROUND */
        .hero {
            padding: 90px 0 60px;
            text-align: center;
            position: relative;
            background: linear-gradient(180deg, rgba(10, 24, 16, 0.72) 0%, rgba(13, 30, 20, 0.82) 40%, rgba(10, 24, 16, 0.94) 100%),
                        url("{{ asset('images/hero-forest-climate-bg.jpg') }}") center center / cover no-repeat;
            color: #FFFFFF;
        }
        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: #E2F4E6;
            font-size: 13px;
            font-weight: 600;
            padding: 7px 20px;
            border-radius: 999px;
            margin-bottom: 26px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }
        .hero h1 {
            font-size: clamp(38px, 6vw, 68px);
            line-height: 1.08;
            font-weight: 800;
            color: #FFFFFF;
            max-width: 20ch;
            margin: 0 auto 20px;
            letter-spacing: -.035em;
            text-shadow: 0 4px 24px rgba(0, 0, 0, 0.45);
        }
        .hero h1 span.gradient {
            background: linear-gradient(135deg, #A8E6A3 0%, #D8F7B5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p.lead {
            font-size: clamp(16px, 2.2vw, 19px);
            color: #E0ECE3;
            max-width: 50ch;
            margin: 0 auto 36px;
            line-height: 1.68;
            font-weight: 400;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.4);
        }
        .hero-cta {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 50px;
        }
        .hero .btn-hero-primary {
            background: #2E7D47;
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 24px rgba(46, 125, 71, 0.45);
        }
        .hero .btn-hero-primary:hover {
            background: #236538;
            box-shadow: 0 10px 28px rgba(46, 125, 71, 0.6);
        }
        .hero .btn-hero-alt {
            background: rgba(255, 255, 255, 0.95);
            color: #111A14;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }
        .hero .btn-hero-alt:hover {
            background: #FFFFFF;
            color: #143823;
        }

        /* TRUST BAR INSIDE HERO */
        .trust-section {
            text-align: center;
            margin-bottom: 50px;
            padding: 0 20px;
        }
        .trust-label {
            font-size: 13.5px;
            color: #C7D8CD;
            letter-spacing: .01em;
            margin-bottom: 18px;
            font-weight: 500;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        }
        .trust-logos {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px 28px;
            flex-wrap: wrap;
        }
        .trust-badge {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            color: #E2ECE5;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            padding: 6px 16px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* STATS BAR */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--border-radius);
            padding: 28px 30px;
            box-shadow: 0 12px 36px rgba(0,0,0,0.03);
            text-align: left;
        }
        @media(max-width: 800px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media(max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
        .stat-item .num {
            font-family: "Bricolage Grotesque", sans-serif;
            font-size: 34px;
            font-weight: 800;
            color: var(--forest);
            line-height: 1.1;
            letter-spacing: -.02em;
        }
        .stat-item .label {
            font-size: 13.5px;
            color: var(--muted);
            margin-top: 5px;
            font-weight: 500;
        }

        /* SECTION STYLING */
        section {
            padding: 80px 0;
        }
        .section-header {
            text-align: center;
            max-width: 660px;
            margin: 0 auto 50px;
        }
        .section-tag {
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: .09em;
            color: var(--leaf);
            font-weight: 700;
            margin-bottom: 10px;
            display: inline-block;
            background: var(--soft);
            padding: 4px 14px;
            border-radius: 999px;
        }
        .section-header h2 {
            font-size: clamp(30px, 4.2vw, 44px);
            line-height: 1.14;
            color: var(--ink);
            font-weight: 800;
        }
        .section-header p {
            color: var(--muted);
            font-size: 16.5px;
            margin-top: 14px;
            line-height: 1.6;
        }

        /* KRISIS IKLIM SECTION */
        .climate-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 40px;
            align-items: center;
        }
        @media(max-width: 860px) {
            .climate-grid { grid-template-columns: 1fr; }
        }
        .climate-fact {
            display: grid;
            gap: 16px;
        }
        .fact-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-left: 4px solid var(--leaf);
            border-radius: 16px;
            padding: 20px 22px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.02);
            transition: transform .2s ease;
        }
        .fact-card:hover {
            transform: translateY(-2px);
        }
        .fact-card h4 {
            font-size: 17.5px;
            color: var(--forest);
            margin-bottom: 5px;
            font-weight: 700;
        }
        .fact-card p {
            margin: 0;
            color: var(--muted);
            font-size: 14.5px;
            line-height: 1.6;
        }

        /* SPECIES COMPARISON */
        .species-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 26px;
        }
        @media(max-width: 800px) {
            .species-grid { grid-template-columns: 1fr; }
        }
        .species-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--border-radius);
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            transition: transform .25s, box-shadow .25s;
        }
        .species-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 44px rgba(20, 56, 35, 0.08);
        }
        .species-card-img-wrap {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: #E8EFEA;
        }
        .species-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s cubic-bezier(.16, 1, .3, 1);
            display: block;
        }
        .species-card:hover .species-card-img {
            transform: scale(1.05);
        }
        .species-card-img-badge {
            position: absolute;
            bottom: 12px;
            left: 16px;
            background: rgba(17, 26, 20, 0.75);
            backdrop-filter: blur(8px);
            color: #FFFFFF;
            font-size: 11.5px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 999px;
            letter-spacing: .02em;
        }
        .species-card-body {
            padding: 28px 28px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex: 1;
        }
        .species-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            z-index: 2;
        }
        .species-card.moringa::before { background: linear-gradient(90deg, #143823, #2E7D47); }
        .species-card.inca::before { background: linear-gradient(90deg, #D97706, #A3D866); }
        .species-badge {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 16px;
        }
        .badge-moringa { background: var(--soft); color: var(--forest); }
        .badge-inca { background: var(--gold-bg); color: var(--gold); }
        .species-card h3 {
            font-size: 28px;
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 4px;
        }
        .species-card .scientific {
            font-style: italic;
            color: var(--muted);
            font-size: 14.5px;
            margin-bottom: 16px;
            display: block;
        }
        .spec-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            display: grid;
            gap: 12px;
        }
        .spec-list li {
            display: flex;
            gap: 10px;
            font-size: 14.5px;
            color: var(--ink);
            line-height: 1.55;
        }
        .spec-list li b {
            color: var(--forest);
            min-width: 140px;
        }

        /* HOW IT WORKS STEPS */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        @media(max-width: 860px) {
            .steps-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media(max-width: 500px) {
            .steps-grid { grid-template-columns: 1fr; }
        }
        .step-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--border-radius);
            padding: 28px 24px;
            position: relative;
            box-shadow: 0 4px 18px rgba(0,0,0,0.02);
            transition: transform .2s ease;
        }
        .step-card:hover {
            transform: translateY(-3px);
        }
        .step-num {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #143823;
            color: #A3D866;
            font-family: "Bricolage Grotesque", sans-serif;
            font-size: 20px;
            font-weight: 800;
            display: grid;
            place-items: center;
            margin-bottom: 18px;
        }
        .step-card h4 {
            font-size: 18px;
            margin-bottom: 6px;
            color: var(--ink);
            font-weight: 700;
        }
        .step-card p {
            color: var(--muted);
            font-size: 14px;
            margin: 0;
            line-height: 1.55;
        }

        /* LIVE ADOPTED TREES SHOWCASE */
        .showcase-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }
        .showcase-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 18px rgba(0,0,0,0.02);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .showcase-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(20, 56, 35, 0.08);
        }
        .showcase-card h4 {
            font-size: 18px;
            color: var(--forest);
            font-weight: 700;
        }
        .tree-month-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #E8F5EC;
            color: #143823;
            font-weight: 700;
            font-size: 11.5px;
            padding: 4px 12px;
            border-radius: 999px;
            border: 1px solid #BFE3C7;
            letter-spacing: .02em;
        }
        .showcase-meta {
            font-size: 13.5px;
            color: var(--muted);
            margin: 10px 0 18px;
            line-height: 1.6;
        }

        /* IPCC FORMULA BOX */
        .ipcc-box {
            background: #143823;
            color: #F1F8F3;
            border-radius: var(--border-radius);
            padding: 44px 38px;
            margin-top: 44px;
            box-shadow: 0 16px 40px rgba(20, 56, 35, 0.2);
        }
        .ipcc-box h3 {
            font-size: 28px;
            color: #FFFFFF;
            margin-bottom: 10px;
            font-weight: 800;
        }
        .ipcc-box p {
            opacity: .9;
            font-size: 15.5px;
            max-width: 65ch;
            line-height: 1.65;
        }
        .formula-chips {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
        }
        .f-chip {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.18);
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 14px;
            backdrop-filter: blur(4px);
        }

        /* CTA BANNER */
        .cta-banner {
            background: linear-gradient(135deg, #0A2013 0%, #143823 60%, #1C4D32 100%);
            color: #fff;
            border-radius: 32px;
            padding: 68px 36px;
            text-align: center;
            margin: 60px 0;
            box-shadow: 0 20px 50px rgba(10, 32, 19, 0.25);
            position: relative;
            overflow: hidden;
        }
        .cta-banner::before {
            content: "";
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(163, 216, 102, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-banner h2 {
            font-size: clamp(30px, 4.8vw, 46px);
            margin-bottom: 14px;
            color: #fff;
            font-weight: 800;
        }
        .cta-banner p {
            font-size: 17.5px;
            opacity: .9;
            max-width: 50ch;
            margin: 0 auto 34px;
            line-height: 1.6;
        }

        /* FOOTER */
        footer {
            border-top: 1px solid var(--line);
            padding: 60px 0 34px;
            color: var(--muted);
            font-size: 14px;
            background: var(--surface);
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 36px;
            margin-bottom: 44px;
        }
        @media(max-width: 700px) {
            .footer-grid { grid-template-columns: 1fr; }
        }
        .footer-col h5 {
            color: var(--ink);
            font-size: 16px;
            margin-bottom: 16px;
            font-family: "Bricolage Grotesque", sans-serif;
            font-weight: 700;
        }
        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }
        .footer-col a {
            color: var(--muted);
            text-decoration: none;
            transition: color .15s;
        }
        .footer-col a:hover { color: var(--forest); }
        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding-top: 24px;
            border-top: 1px solid var(--line);
            font-size: 13.5px;
        }
    </style>
</head>
<body>

    <!-- TOP NAVIGATION -->
    <nav class="navbar">
        <div class="container nav-inner">
            <!-- Brand Logo mengarah ke landing page utama -->
            <a href="{{ url('/') }}" class="brand" title="Ngempu Flora - Beranda">
                <img src="{{ asset('images/logo-ngempu-flora.jpg') }}" alt="Logo Ngempu Flora" class="brand-logo-img">
                <span>Ngempu Flora</span>
            </a>

            <ul class="nav-links">
                <li><a href="#krisis">Krisis Iklim</a></li>
                <li><a href="#spesies">Moringa & Kacang Inka</a></li>
                <li><a href="#sains">Standar Karbon (IPCC)</a></li>
                <li><a href="#cara-kerja">Cara Kerja</a></li>
                <li><a href="#pohon">Pohon Teradopsi</a></li>
            </ul>

            <div class="nav-actions">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn">
                            👨‍💼 Panel Admin
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn">
                            🌿 Dashboard Saya
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn">
                        Get started / Masuk
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="hero">
        <div class="container">
            <div class="hero-pill">
                <span>🌱</span> Aksi Iklim Terukur &middot; Pemantauan Bulanan Terverifikasi
            </div>

            <h1>
                World-class platform for <br>
                <span class="gradient">sustainable climate solutions.</span>
            </h1>

            <p class="lead">
                Kami menyediakan solusi adopsi pohon terukur yang disesuaikan untuk menyerap emisi CO<sub>2</sub> atmosfer secara nyata, dipantau bulanan dengan sains allometrik berstandar global.
            </p>

            <div class="hero-cta">
                @auth
                    <a href="{{ route('user.dashboard') }}" class="btn btn-lg btn-hero-primary">
                        🌿 Buka Dashboard Adopter
                    </a>
                    <a href="#pohon" class="btn btn-lg btn-hero-alt">
                        Lihat Pohon Aktif
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-lg btn-hero-primary">
                        Mulai Adopsi / Masuk
                    </a>
                    <a href="#cara-kerja" class="btn btn-lg btn-hero-alt">
                        Pelajari Cara Kerja
                    </a>
                @endauth
            </div>

            <!-- TRUST BADGES SECTION -->
            <div class="trust-section">
                <div class="trust-label">
                    Dipercaya oleh mitra konservasi, akademisi, dan adopter dari berbagai penjuru
                </div>
                <div class="trust-logos">
                    <span class="trust-badge">🌿 IPCC Framework</span>
                    <span class="trust-badge">🌱 Yayasan Kedai Masyarakat</span>
                    <span class="trust-badge">🌾 Kebun Konservasi Bali</span>
                    <span class="trust-badge">🧪 Allometric Carbon Index</span>
                    <span class="trust-badge">📊 Verified Metric Tracking</span>
                </div>
            </div>

            <!-- LIVE IMPACT STATS BAR -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="num">{{ $totalTrees }}</div>
                    <div class="label">Pohon Aktif Ditanam</div>
                </div>
                <div class="stat-item">
                    <div class="num">{{ $totalCO2Formatted }}</div>
                    <div class="label">Total CO₂ Berhasil Diserap</div>
                </div>
                <div class="stat-item">
                    <div class="num">12 Bulan</div>
                    <div class="label">Program Perawatan Intensif</div>
                </div>
                <div class="stat-item">
                    <div class="num">100%</div>
                    <div class="label">Transparansi Laporan Lapangan</div>
                </div>
            </div>
        </div>
    </header>

    <!-- SECTION 1: KRISIS IKLIM & URGENSI ADOPSI POHON -->
    <section id="krisis">
        <div class="container">
            <div class="climate-grid">
                <div>
                    <span class="section-tag">Urgensi Planet Kita</span>
                    <h2>Mengapa Menjaga Iklim Menjadi Tanggung Jawab Bersama?</h2>
                    <p style="color:var(--muted); font-size:16.5px; margin-top:14px; line-height:1.7;">
                        Setiap tahun, miliaran ton emisi karbon dioksida (CO<sub>2</sub>) dilepaskan ke atmosfer, memicu kenaikan suhu bumi, kekeringan berkepanjangan, dan cuaca ekstrem. 
                        <b>Fotosintesis alami adalah teknologi penangkap karbon terhebat yang pernah ada</b>—pohon mengikat karbon bebas dari udara dan menguncinya menjadi batang kayu, akar, serta biomassa tanah.
                    </p>
                    <p style="color:var(--muted); font-size:16.5px; line-height:1.7;">
                        Namun, masalah program tanam pohon konvensional adalah <b>kurangnya pemantauan lanjutan</b>. Jutaan pohon ditanam lalu mati karena tidak dirawat. Melalui program adopsi ini, setiap pohon dijamin kelangsungan hidupnya dengan pemantauan fisik berkala setiap bulan.
                    </p>
                </div>

                <div class="climate-fact">
                    <div class="fact-card">
                        <h4>🔥 Krisis Pemanasan Global</h4>
                        <p>Kenaikan suhu di atas 1.5°C mengancam ketahanan pangan dan kestabilan ekosistem pulau tropis Indonesia.</p>
                    </div>
                    <div class="fact-card">
                        <h4>🌳 Penyerapan Karbon Permanen</h4>
                        <p>Pohon menyerap karbon selama puluhan tahun. Biomassa akar menyimpan karbon di bawah tanah secara stabil.</p>
                    </div>
                    <div class="fact-card">
                        <h4>📱 Transparansi Data Bulanan</h4>
                        <p>Setiap adopter dapat melihat pertumbuhan milimeter demi milimeter melalui foto dan metrik yang diunggah staf ahli kami.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: DUA SPESIES JUARA (MORINGA & INCA NUT) -->
    <section id="spesies" style="background:var(--soft);">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Spesies Pilihan Kebun</span>
                <h2>Mengapa Memilih Pohon Kelor (Moringa) & Kacang Inka?</h2>
                <p>Kedua tanaman ini dipilih secara ilmiah karena kombinasi laju pertumbuhan yang sangat cepat, daya tahan terhadap iklim tropis, serta manfaat ekologis dan sosial yang luar biasa.</p>
            </div>

            <div class="species-grid">
                <!-- CARD MORINGA -->
                <div class="species-card moringa">
                    <div class="species-card-img-wrap">
                        <img src="{{ asset('images/species-moringa.jpg') }}" alt="Pohon Kelor (Moringa oleifera)" class="species-card-img" onerror="this.src='{{ asset('images/logo-ngempu-flora.jpg') }}'">
                        <span class="species-card-img-badge">🌿 Moringa oleifera</span>
                    </div>

                    <div class="species-card-body">
                        <div>
                            <span class="species-badge badge-moringa">Pohon Pertumbuhan Cepat & Biomassa</span>
                            <h3>Pohon Kelor (Moringa)</h3>
                            <span class="scientific">Moringa oleifera</span>

                            <p style="color:var(--muted); font-size:15px; line-height:1.6;">
                                Dikenal di seluruh dunia sebagai <i>"Miracle Tree"</i> (Pohon Keajaiban). Moringa tumbuh sangat cepat (bisa mencapai 2-3 meter dalam 6 bulan), menjadikannya mesin fotosintesis yang sangat efektif dalam menangkap CO<sub>2</sub> atmosfer secara instan.
                            </p>

                            <ul class="spec-list">
                                <li><b>Laju Serapan Karbon:</b> Hingga 20–50x lebih tinggi dibanding vegetasi rata-rata tropis per hektar.</li>
                                <li><b>Sistem Perakaran:</b> Akar tunggang dalam yang menahan air tanah dan mencegah erosi lereng.</li>
                                <li><b>Manfaat Pangkasan:</b> Ranting dan daun yang dipangkas (*pruning*) diolah menjadi pupuk hijau berkarbon tinggi.</li>
                                <li><b>Karakteristik Tanam:</b> Tahan kekeringan dan mampu merevitalisasi lahan kritis tandus.</li>
                            </ul>
                        </div>

                        <div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--line);">
                            <small style="color:var(--muted); display:block; font-size:13px;">Parameter Allometrik: A = 0.00004, B = 1.9, Akar +25%</small>
                        </div>
                    </div>
                </div>

                <!-- CARD INCA NUT -->
                <div class="species-card inca">
                    <div class="species-card-img-wrap">
                        <img src="{{ asset('images/species-inca.jpg') }}" alt="Kacang Inka (Sacha Inchi)" class="species-card-img" onerror="this.src='{{ asset('images/logo-ngempu-flora.jpg') }}'">
                        <span class="species-card-img-badge">⭐ Plukenetia volubilis</span>
                    </div>

                    <div class="species-card-body">
                        <div>
                            <span class="species-badge badge-inca">Sulur Kanopi & Superfood Lestari</span>
                            <h3>Kacang Inka (Inca Nut)</h3>
                            <span class="scientific">Plukenetia volubilis (Sacha Inchi)</span>

                            <p style="color:var(--muted); font-size:15px; line-height:1.6;">
                                Tanaman merambat abadi asal hutan Amazon yang sangat produktif di iklim tropis Indonesia. Menghasilkan biji berbentuk bintang dengan kandungan Omega 3-6-9 tertinggi di dunia nabati.
                            </p>

                            <ul class="spec-list">
                                <li><b>Kanopi Hijau Rimbun:</b> Daun menutupi teralis dengan cepat, memperluas permukaan fotosintesis.</li>
                                <li><b>Siklus Produksi Panjang:</b> Mampu berbuah produktif selama 10–15 tahun tanpa perlu menebang pohon induk.</li>
                                <li><b>Dampak Sosial Ekonomi:</b> Biji diolah menjadi minyak omega berkualitas tinggi untuk petani lokal.</li>
                                <li><b>Konservasi Tanah:</b> Tajuk tanaman melindungi tanah dari panas langsung dan menjaga kelembaban mikroklimat.</li>
                            </ul>
                        </div>

                        <div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--line);">
                            <small style="color:var(--muted); display:block; font-size:13px;">Parameter Allometrik: A = 0.00002, B = 1.7, Akar +20%</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IPCC FORMULA BOX -->
            <div id="sains" class="ipcc-box">
                <span style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; opacity:.85;">Transparansi Ilmiah</span>
                <h3>Bagaimana Kami Menghitung Serapan Karbon Pohon Anda?</h3>
                <p>
                    Kami tidak menebak angka serapan karbon. Setiap bulan, angka tinggi (cm) dan berat biomassa pohon dihitung menggunakan rumus <b>Allometrik Kehutanan Standar IPCC (<i>Intergovernmental Panel on Climate Change</i>)</b>:
                </p>

                <div class="formula-chips">
                    <div class="f-chip">1. Biomassa Kering = A &times; (cm)<sup>B</sup> &times; (1 + Akar)</div>
                    <div class="f-chip">2. Kandungan Karbon = Biomassa Kering &times; 47%</div>
                    <div class="f-chip">3. Ekuivalen CO<sub>2</sub> Diserap = Karbon &times; 3.67 (Rasio molekul 44/12)</div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: CARA KERJA PROGRAM ADOPSI -->
    <section id="cara-kerja">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Alur Program</span>
                <h2>4 Langkah Mudah Menjadi Penjaga Bumi</h2>
                <p>Bergabunglah bersama komunitas peduli lingkungan dan nikmati pengalaman memantau pohon adopsi Anda tumbuh setiap bulannya.</p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <h4>Pilih Pohon</h4>
                    <p>Pilih spesies pohon (Moringa atau Kacang Inka) dan lokasikan adopsi Anda di lahan konservasi kebun kami.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <h4>Penanaman & Tag QR</h4>
                    <p>Tim kebun menanam bibit unggul, mencatat titik plot tanam, dan memasang plat label QR code fisik di pohon.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <h4>Inspeksi Bulanan</h4>
                    <p>Staf lapangan mengukur ukuran fisik, menghitung daun, mengecek hama, dan memotret pohon setiap bulan.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">4</div>
                    <h4>Pantau di Dashboard</h4>
                    <p>Buka dashboard adopter kapan saja lewat ponsel Anda untuk melihat foto bulanan, kurva tumbuh, dan sertifikat emisi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: SHOWCASE POHON ADOPSI SAAT INI -->
    <section id="pohon" style="background:var(--soft);">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Koleksi Terverifikasi</span>
                <h2>Pohon yang Sedang Tumbuh di Kebun Saat Ini</h2>
                <p>Lihat contoh pohon adopsi yang saat ini sedang aktif dirawat dan dipantau di kebun konservasi Semaya dan Teba.</p>
            </div>

            <div class="showcase-grid">
                @foreach ($sampleTrees as $t)
                    <div class="showcase-card">
                        <div>
                            <div style="display:flex; justify-content:space-between; align-items:start;">
                                <span style="font-weight:700; color:var(--forest); font-family:'Bricolage Grotesque',sans-serif; font-size:19px;">
                                    {{ $t->tree_code }}
                                </span>
                                <span class="tree-month-badge">
                                    Bulan ke-{{ $t->current_month ?? $t->current_week }}
                                </span>
                            </div>

                            <h4 style="margin:6px 0 2px;">{{ $t->species->name }}</h4>
                            <small style="color:var(--muted); font-style:italic;">{{ $t->species->display_name }}</small>

                            <div class="showcase-meta">
                                <div>👤 Adopter: <b>{{ explode(',', $t->adopter_name)[0] }}</b></div>
                                <div>📍 Plot: <b>{{ $t->plot ?? 'Semaya' }}</b></div>
                                <div>📅 Ditanam: <b>{{ $t->planted_date->translatedFormat('d M Y') }}</b></div>
                            </div>
                        </div>

                        @auth
                            <a href="{{ route('tracker.show', ['codeAndToken' => $t->full_code_token]) }}" class="btn btn-alt" style="width:100%; font-size:13px; border-radius:12px;">
                                Buka Pemantauan Pohon Ini ↗
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-alt" style="width:100%; font-size:13px; border-radius:12px;">
                                🔒 Login untuk Memantau ↗
                            </a>
                        @endauth
                    </div>
                @endforeach
            </div>

            <div style="text-align:center; margin-top:36px;">
                @auth
                    <a href="{{ route('user.dashboard') }}" class="btn btn-lg">
                        Buka Dashboard Seluruh Pohon Anda 🌿
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-lg">
                        Masuk untuk Melihat Pohon Anda 🌿
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- SECTION 5: CTA BANNER -->
    <section>
        <div class="container">
            <div class="cta-banner">
                <h2>Siap Memberi Dampak Nyata bagi Iklim?</h2>
                <p>
                    Setiap helai daun yang tumbuh adalah jejak kebaikan untuk generasi mendatang. Masuk ke dashboard untuk memantau perkembangan bulanan pohon adopsi Anda.
                </p>
                <div style="display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
                    <a href="{{ route('login') }}" class="btn btn-lg" style="background:#fff; color:#143823; box-shadow:0 8px 24px rgba(0,0,0,0.15);">
                        Masuk ke Dashboard Adopter
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="{{ url('/') }}" class="brand" style="font-size:22px; margin-bottom:14px;" title="Ngempu Flora - Beranda">
                        <span class="brand-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22v-9"/>
                                <path d="M9 10a4 4 0 0 1 6 0 4 4 0 0 0 3-1 4 4 0 0 0-1-3 4 4 0 0 0-5-2 4 4 0 0 0-5 2 4 4 0 0 0-1 3 4 4 0 0 0 3 1z"/>
                                <path d="M7 16l5-3 5 3"/>
                            </svg>
                        </span>
                        <span>Ngempu Flora</span>
                    </a>
                    <p style="color:var(--muted); max-width:36ch; font-size:14px; margin:0 0 14px; line-height:1.6;">
                        Inisiatif pemulihan iklim dan pemantauan serapan karbon berbasis pohon Moringa & Kacang Inka, dikelola secara profesional oleh <b>Yayasan Kedai Masyarakat</b>.
                    </p>
                    <small>Lokasi Konservasi: Kebun Semaya & Teba, Bali, Indonesia.</small>
                </div>

                <div class="footer-col">
                    <h5>Navigasi Cepat</h5>
                    <ul>
                        <li><a href="#krisis">Krisis Iklim</a></li>
                        <li><a href="#spesies">Moringa & Kacang Inka</a></li>
                        <li><a href="#sains">Perhitungan Karbon</a></li>
                        <li><a href="#cara-kerja">Cara Kerja</a></li>
                        <li><a href="{{ route('tracker.index') }}">Pemantau Pohon (QR)</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h5>Akses Portal</h5>
                    <ul>
                        <li><a href="{{ route('login') }}">Masuk Akun Adopter</a></li>
                        <li><a href="{{ route('login') }}">Login Staf Lapangan / Admin</a></li>
                        <li><a href="{{ route('admin.dashboard') }}">Panel Dashboard Admin</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div>&copy; {{ date('Y') }} Ngempu Flora — Yayasan Kedai Masyarakat. Hak Cipta Dilindungi.</div>
                <div>Metodologi Allometrik Karbon Berstandar IPCC.</div>
            </div>
        </div>
    </footer>

</body>
</html>
