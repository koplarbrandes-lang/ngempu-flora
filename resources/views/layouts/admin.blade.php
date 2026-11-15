<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — Ngempu Flora</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #F1F5F2;
            --sf: #FFFFFF;
            --ink: #14231B;
            --mut: #5B6B62;
            --line: #D8E2DB;
            --moss: #1F4D3A;
            --leaf: #4FA36B;
            --soft: #E3EEE6;
            --warn: #B45309;
            --warnbg: #FEF3C7;
            --bad: #B42318;
            --badbg: #FEE4E2;
            --on: #FFFFFF;
            box-sizing: border-box;
            padding-top: env(safe-area-inset-top, 0px);
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --bg: #0F1A14;
                --sf: #16261D;
                --ink: #EAF3EC;
                --mut: #9BB0A2;
                --line: #264034;
                --moss: #8FD3A4;
                --leaf: #5DBB7B;
                --soft: #1C3227;
                --warn: #FCD34D;
                --warnbg: #3A2E0B;
                --bad: #FDA29B;
                --badbg: #3B1512;
                --on: #0F1A14;
            }
        }
        * { box-sizing: border-box; }
        html, body { margin: 0; }
        body {
            background: var(--bg);
            color: var(--ink);
            font: 400 15.5px/1.5 "DM Sans", system-ui, sans-serif;
        }
        h1, h2, .n {
            font-family: "Bricolage Grotesque", "DM Sans", sans-serif;
            letter-spacing: -.02em;
            margin: 0;
        }
        .wrap {
            max-width: 980px;
            margin: 0 auto;
            padding: 16px 16px 60px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .brand {
            font-weight: 700;
            font-size: 18px;
            color: var(--moss);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .pill {
            font-size: 12px;
            background: var(--soft);
            color: var(--moss);
            padding: 4px 12px;
            border-radius: 99px;
            font-weight: 500;
        }
        nav {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding-bottom: 6px;
            margin-bottom: 18px;
            border-bottom: 1px solid var(--line);
        }
        nav a {
            font: 600 14px inherit;
            white-space: nowrap;
            background: none;
            border: 0;
            color: var(--mut);
            padding: 9px 15px;
            border-radius: 10px;
            text-decoration: none;
            transition: all .2s;
        }
        nav a:hover {
            color: var(--moss);
            background: var(--soft);
        }
        nav a[aria-current="page"] {
            background: var(--moss);
            color: var(--on);
        }
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14.5px;
            font-weight: 500;
        }
        .alert-success {
            background: var(--soft);
            color: var(--moss);
            border: 1px solid var(--leaf);
        }
        .card {
            background: var(--sf);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px;
            margin-top: 14px;
        }
        .grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(2, 1fr);
        }
        @media (min-width: 720px) {
            .grid.g4 { grid-template-columns: repeat(4, 1fr); }
        }
        .stat .n {
            font-size: 28px;
            font-weight: 700;
            color: var(--moss);
            line-height: 1.1;
        }
        .stat small { color: var(--mut); }
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 12px 0;
            border-top: 1px solid var(--line);
        }
        .row:first-of-type { border-top: 0; }
        .btn {
            font: 600 14px "DM Sans", sans-serif;
            background: var(--moss);
            color: var(--on);
            border: 0;
            border-radius: 10px;
            padding: 9px 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn.alt {
            background: var(--soft);
            color: var(--moss);
        }
        .btn:hover { opacity: .9; }
        .tag {
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 99px;
            background: var(--soft);
            color: var(--moss);
            white-space: nowrap;
            font-weight: 600;
        }
        .tag.late { background: var(--badbg); color: var(--bad); }
        .tag.due { background: var(--warnbg); color: var(--warn); }
        .tw { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th {
            text-align: left;
            color: var(--mut);
            font-weight: 600;
            padding: 10px 8px;
            border-bottom: 1px solid var(--line);
            white-space: nowrap;
        }
        td {
            padding: 11px 8px;
            border-bottom: 1px solid var(--line);
            white-space: nowrap;
        }
        label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            margin: 12px 0 5px;
        }
        input, select, textarea {
            font: inherit;
            color: var(--ink);
            background: var(--sf);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px;
            width: 100%;
        }
        input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 3px solid var(--leaf);
            outline-offset: 1px;
        }
        .fine { font-size: 13px; color: var(--mut); }

        /* MODAL POPUP KONFIRMASI MODERN */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(14, 26, 18, 0.65);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeInModal .15s ease-out;
        }
        @keyframes fadeInModal {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-box {
            background: var(--sf);
            border: 1px solid var(--line);
            border-radius: 20px;
            max-width: 440px;
            width: 100%;
            padding: 28px 24px 22px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.25);
            text-align: center;
            transform: scale(0.96);
            animation: popInModal .18s cubic-bezier(.16, 1, .3, 1) forwards;
        }
        @keyframes popInModal {
            to { transform: scale(1); }
        }
        .modal-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }
        .modal-icon.danger { background: var(--badbg); color: var(--bad); }
        .modal-icon.info { background: var(--soft); color: var(--moss); }
        .modal-icon.warn { background: var(--warnbg); color: var(--warn); }
        .modal-title {
            font-family: "Bricolage Grotesque", sans-serif;
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 8px;
            color: var(--ink);
        }
        .modal-message {
            font-size: 14px;
            color: var(--mut);
            line-height: 1.55;
            margin: 0 0 22px;
            white-space: pre-line;
        }
        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .modal-actions button, .modal-actions a {
            flex: 1;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="wrap">
    <header>
        <a href="{{ url('/') }}" class="brand" title="Menuju Landing Page Utama Ngempu Flora">
            <img src="{{ asset('images/logo-ngempu-flora.jpg') }}" alt="Logo Ngempu Flora" style="width:34px; height:34px; border-radius:50%; object-fit:cover; box-shadow:0 2px 6px rgba(0,0,0,0.15);">
            <span>Ngempu Flora — Admin</span>
        </a>
        <div style="display:flex; align-items:center; gap:8px;">
            <a href="{{ route('tracker.index') }}" target="_blank" class="pill" style="text-decoration:none;">
                Lihat Web Adopter ↗
            </a>
            @auth
                <span class="pill">👤 {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn alt" style="padding:4px 10px; font-size:12.5px;">
                        Keluar
                    </button>
                </form>
            @endauth
        </div>
    </header>

    <nav aria-label="Admin sections">
        <a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>Dashboard</a>
        <a href="{{ route('admin.trees') }}" @if(request()->routeIs('admin.trees*')) aria-current="page" @endif>Daftar Pohon</a>
        <a href="{{ route('admin.adopters.index') }}" @if(request()->routeIs('admin.adopters*')) aria-current="page" @endif>Daftar Adopter</a>
        <a href="{{ route('admin.update') }}" @if(request()->routeIs('admin.update')) aria-current="page" @endif>Input Bulanan</a>
        <a href="{{ route('admin.certificates') }}" @if(request()->routeIs('admin.certificates*')) aria-current="page" @endif>📜 Sertifikat Barcode</a>
        <a href="{{ route('admin.qr_codes') }}" @if(request()->routeIs('admin.qr_codes')) aria-current="page" @endif>Cetak QR</a>
    </nav>

    @if (session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @yield('content')
</div>

{{-- MODAL DIALOG POPUP GLOBAL UNTUK SEMUA AKSI ADMIN --}}
<div id="admin-confirm-modal" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="modal-confirm-title">
    <div class="modal-box">
        <div id="modal-confirm-icon" class="modal-icon danger">⚠️</div>
        <h3 id="modal-confirm-title" class="modal-title">Konfirmasi Aksi</h3>
        <p id="modal-confirm-msg" class="modal-message">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
        <div class="modal-actions">
            <button type="button" id="modal-confirm-cancel" class="btn alt">Batal</button>
            <button type="button" id="modal-confirm-ok" class="btn" style="background:var(--bad);">Lanjutkan</button>
        </div>
    </div>
</div>

<script>
    // Universal confirmation modal helper for admin actions
    function showAdminConfirm(options = {}) {
        return new Promise((resolve) => {
            const modal = document.getElementById('admin-confirm-modal');
            const iconEl = document.getElementById('modal-confirm-icon');
            const titleEl = document.getElementById('modal-confirm-title');
            const msgEl = document.getElementById('modal-confirm-msg');
            const okBtn = document.getElementById('modal-confirm-ok');
            const cancelBtn = document.getElementById('modal-confirm-cancel');

            if (!modal) {
                // Fallback to native confirm if modal markup is missing
                resolve(confirm(options.message || 'Lanjutkan aksi ini?'));
                return;
            }

            const type = options.type || 'danger'; // 'danger', 'info', 'warn'
            iconEl.className = 'modal-icon ' + type;
            iconEl.textContent = options.icon || (type === 'danger' ? '🗑️' : (type === 'warn' ? '⚠️' : 'ℹ️'));
            titleEl.textContent = options.title || 'Konfirmasi Tindakan';
            msgEl.textContent = options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            okBtn.textContent = options.confirmText || 'Ya, Lanjutkan';
            okBtn.style.background = options.confirmColor || (type === 'danger' ? 'var(--bad)' : 'var(--moss)');

            modal.style.display = 'flex';

            const cleanup = () => {
                modal.style.display = 'none';
                okBtn.onclick = null;
                cancelBtn.onclick = null;
                modal.onclick = null;
            };

            okBtn.onclick = () => {
                cleanup();
                resolve(true);
            };

            cancelBtn.onclick = () => {
                cleanup();
                resolve(false);
            };

            modal.onclick = (e) => {
                if (e.target === modal) {
                    cleanup();
                    resolve(false);
                }
            };
        });
    }
</script>

@stack('scripts')
</body>
</html>
