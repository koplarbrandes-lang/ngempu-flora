<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Masuk — Ngempu Flora</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Newsreader:ital,opsz,wght@0,6..72,400;1,6..72,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #FAFAF8;
            --sf: #FFFFFF;
            --ink: #111A14;
            --mut: #5C6E63;
            --line: #E5EBE6;
            --forest: #143823;
            --forest-dark: #0D2617;
            --leaf: #2E7D47;
            --soft: #F0F5F1;
            --bad: #B42318;
            --badbg: #FEE4E2;
            --on: #FFFFFF;
        }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --bg: #0C1510;
                --sf: #14221A;
                --ink: #EDF5EF;
                --mut: #91A698;
                --line: #203629;
                --forest: #68BF84;
                --forest-dark: #09130D;
                --leaf: #44A262;
                --soft: #182C21;
                --bad: #FDA29B;
                --badbg: #3B1512;
                --on: #0C1510;
            }
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font: 400 15px/1.55 "Plus Jakarta Sans", system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle organic background accent */
        body::before {
            content: "";
            position: absolute;
            top: -150px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 450px;
            background: radial-gradient(circle, rgba(46, 125, 71, 0.08) 0%, rgba(20, 56, 35, 0.02) 60%, transparent 80%);
            pointer-events: none;
            z-index: 0;
        }

        .login-card {
            position: relative;
            z-index: 1;
            background: var(--sf);
            border: 1px solid var(--line);
            border-radius: 24px;
            padding: 40px 34px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 48px -12px rgba(20, 56, 35, 0.08), 0 2px 6px rgba(0,0,0,0.02);
        }

        .brand-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            width: 100%;
            margin-bottom: 8px;
            transition: transform .2s ease;
        }
        .brand-link:hover {
            transform: translateY(-1px);
        }
        .brand-logo-img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(20, 56, 35, 0.2);
            display: block;
        }
        .brand-name {
            font-family: "Bricolage Grotesque", sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--forest);
            letter-spacing: -.03em;
        }
        .sub {
            text-align: center;
            color: var(--mut);
            font-size: 14px;
            margin: 0 0 26px;
        }

        .form-group {
            margin-bottom: 18px;
        }
        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 7px;
        }
        label {
            display: block;
            font-weight: 600;
            font-size: 13.5px;
            color: var(--ink);
        }

        .input-wrap {
            position: relative;
            width: 100%;
        }
        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--sf);
            color: var(--ink);
            font: inherit;
            font-size: 14.5px;
            transition: border-color .15s, box-shadow .15s;
        }
        .input-wrap input {
            padding-right: 44px;
        }
        input:focus-visible {
            outline: none;
            border-color: var(--leaf);
            box-shadow: 0 0 0 3px rgba(46, 125, 71, 0.18);
        }

        .eye-toggle-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--mut);
            border-radius: 8px;
            transition: color .15s, background-color .15s;
        }
        .eye-toggle-btn:hover {
            color: var(--ink);
            background: var(--soft);
        }
        .eye-toggle-btn svg {
            width: 19px;
            height: 19px;
            display: block;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 18px 0 22px;
            font-size: 13.5px;
            color: var(--mut);
            cursor: pointer;
            user-select: none;
        }
        .remember-row input[type="checkbox"] {
            accent-color: var(--leaf);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 12px;
            background: #143823;
            color: #FFFFFF;
            font: 600 15px "Plus Jakarta Sans", sans-serif;
            cursor: pointer;
            transition: transform .15s ease, background-color .15s ease, box-shadow .15s ease;
            box-shadow: 0 4px 14px rgba(20, 56, 35, 0.22);
        }
        .btn:hover {
            background: #0D2617;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(20, 56, 35, 0.28);
        }
        .btn:active {
            transform: translateY(0);
        }

        .alert-error {
            background: var(--badbg);
            color: var(--bad);
            border: 1px solid var(--bad);
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 13.5px;
            margin-bottom: 18px;
        }
        .alert-success {
            background: var(--soft);
            color: var(--forest);
            border: 1px solid var(--leaf);
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 13.5px;
            margin-bottom: 18px;
        }

        .demo-box {
            margin-top: 26px;
            padding: 16px;
            border-radius: 14px;
            background: var(--soft);
            border: 1px solid var(--line);
            font-size: 13px;
        }
        .demo-box b {
            display: block;
            margin-bottom: 8px;
            color: var(--forest);
            font-size: 13px;
        }
        .demo-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--sf);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 12.5px;
            font-weight: 500;
            cursor: pointer;
            color: var(--ink);
            margin-top: 4px;
            margin-right: 6px;
            transition: border-color .15s, transform .1s;
        }
        .demo-btn:hover {
            border-color: var(--leaf);
            transform: translateY(-1px);
        }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-align: center;
            margin-top: 22px;
            color: var(--mut);
            text-decoration: none;
            font-size: 13.5px;
            transition: color .15s;
        }
        .back-link:hover { color: var(--forest); }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Logo Ngempu Flora yang mengarah ke landing page utama -->
        <a href="{{ url('/') }}" class="brand-link" title="Menuju Landing Page Utama Ngempu Flora">
            <img src="{{ asset('images/logo-ngempu-flora.jpg') }}" alt="Logo Ngempu Flora" style="width:48px; height:48px; border-radius:50%; object-fit:cover; box-shadow:0 3px 10px rgba(20,56,35,0.2);">
            <span class="brand-name">Ngempu Flora</span>
        </a>
        <p class="sub">Masuk untuk mengakses dashboard Anda</p>

        @if (session('success'))
            <div class="alert-success">✓ {{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert-error">⚠️ {{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
            </div>

            <div class="form-group">
                <div class="label-row">
                    <label for="password">Password</label>
                </div>
                <div class="input-wrap">
                    <input id="password" name="password" type="password" placeholder="••••••••" required>
                    <button type="button" class="eye-toggle-btn" id="togglePasswordBtn" aria-label="Lihat password" title="Tampilkan/Sembunyikan Password">
                        <!-- Icon Eye (Default) -->
                        <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <!-- Icon Eye Off (Hidden by default) -->
                        <svg id="eyeOffIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <line x1="2" y1="2" x2="22" y2="22"/>
                        </svg>
                    </button>
                </div>
            </div>

            <label class="remember-row">
                <input type="checkbox" name="remember" value="1">
                <span>Ingat saya di perangkat ini</span>
            </label>

            <button type="submit" class="btn">Masuk ke Akun</button>
        </form>

        <!-- KOTAK AKUN DEMO SIAP PAKAI -->
        <div class="demo-box">
            <b>🔑 Akun Uji Coba (Klik untuk Mengisi):</b>
            <div>
                <button type="button" class="demo-btn" onclick="fillDemo('admin@adopsi.test', 'admin123')">
                    👨‍💼 Admin Kebun
                </button>
                <button type="button" class="demo-btn" onclick="fillDemo('ayuwati@example.com', 'user123')">
                    🌿 Adopter (Ayu Wati)
                </button>
            </div>
        </div>

        <a href="{{ url('/') }}" class="back-link">
            ← Kembali ke Halaman Depan
        </a>
    </div>

    <script>
        function fillDemo(email, pass) {
            document.getElementById('email').value = email;
            const passInput = document.getElementById('password');
            passInput.value = pass;
        }

        // Toggle Password Visibility
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                if (isPassword) {
                    passwordInput.setAttribute('type', 'text');
                    eyeIcon.style.display = 'none';
                    eyeOffIcon.style.display = 'block';
                    toggleBtn.setAttribute('aria-label', 'Sembunyikan password');
                } else {
                    passwordInput.setAttribute('type', 'password');
                    eyeIcon.style.display = 'block';
                    eyeOffIcon.style.display = 'none';
                    toggleBtn.setAttribute('aria-label', 'Lihat password');
                }
                passwordInput.focus();
            });
        }
    </script>
</body>
</html>
