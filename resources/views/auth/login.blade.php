<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CyberAudit — Login</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --red: #af101a;
            --red-d: #7a0b12;
            --red-l: #d32f2f;
            --cream: #f7f3ee;
            --dark: #0d0d0f;
            --dark2: #141418;
            --dark3: #1c1c22;
            --border: rgba(255, 255, 255, 0.07);
            --text: rgba(255, 255, 255, 0.85);
            --muted: rgba(255, 255, 255, 0.35);
        }

        html,
        body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--dark);
            color: var(--text);
            overflow: hidden;
        }

        /* ── BACKGROUND ── */
        .bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 70% 50%, rgba(175, 16, 26, 0.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 10% 80%, rgba(175, 16, 26, 0.10) 0%, transparent 55%),
                var(--dark);
        }

        /* Grid lines */
        .bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse at 70% 50%, black 20%, transparent 70%);
        }

        /* Noise grain */
        .bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            background-size: 200px 200px;
            pointer-events: none;
            opacity: 0.5;
        }

        /* ── LAYOUT ── */
        .page {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr 480px;
            height: 100vh;
        }

        /* ── LEFT PANEL ── */
        .left {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 56px 64px;
            border-right: 1px solid var(--border);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: var(--red);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 30px rgba(175, 16, 26, 0.5);
        }

        .brand-icon .material-symbols-outlined {
            color: white;
            font-size: 22px;
            font-variation-settings: 'FILL' 1;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: white;
        }

        .brand-sub {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-top: 2px;
        }

        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 0;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(175, 16, 26, 0.15);
            border: 1px solid rgba(175, 16, 26, 0.3);
            border-radius: 100px;
            padding: 5px 14px;
            font-size: 11px;
            font-weight: 500;
            color: #f87171;
            letter-spacing: 0.5px;
            margin-bottom: 28px;
            width: fit-content;
        }

        .hero-tag span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #f87171;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0.3
            }
        }

        .hero-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(36px, 4vw, 56px);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -2px;
            color: white;
            margin-bottom: 20px;
        }

        .hero-title em {
            font-style: normal;
            color: var(--red);
            position: relative;
        }

        .hero-desc {
            font-size: 15px;
            line-height: 1.7;
            color: var(--muted);
            max-width: 400px;
            font-weight: 300;
        }

        .stats {
            display: flex;
            gap: 40px;
            margin-top: 48px;
        }

        .stat-item {}

        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: white;
            line-height: 1;
        }

        .stat-label {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        .footer-text {
            font-size: 11px;
            color: var(--muted);
            letter-spacing: 0.3px;
        }

        /* ── RIGHT PANEL ── */
        .right {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 56px 52px;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-title {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            color: white;
            margin-bottom: 8px;
        }

        .form-sub {
            font-size: 14px;
            color: var(--muted);
        }

        /* Error alert */
        .alert-error {
            background: rgba(175, 16, 26, 0.1);
            border: 1px solid rgba(175, 16, 26, 0.3);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            color: #fca5a5;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Fields */
        .field {
            margin-bottom: 20px;
        }

        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 18px;
            font-variation-settings: 'FILL' 0;
            pointer-events: none;
            transition: color 0.2s;
        }

        .field-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px 14px 48px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: white;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }

        .field-input::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        .field-input:focus {
            border-color: var(--red);
            background: rgba(175, 16, 26, 0.08);
            box-shadow: 0 0 0 3px rgba(175, 16, 26, 0.15);
        }

        .field-input:focus+.field-icon,
        .field-wrap:focus-within .field-icon {
            color: var(--red);
        }

        .field-error {
            font-size: 12px;
            color: #fca5a5;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap-4px;
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            padding: 4px;
            font-size: 18px;
            transition: color 0.2s;
        }

        .pw-toggle:hover {
            color: white;
        }

        /* Row */
        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember input[type=checkbox] {
            width: 16px;
            height: 16px;
            accent-color: var(--red);
            cursor: pointer;
            border-radius: 4px;
        }

        .remember span {
            font-size: 13px;
            color: var(--muted);
        }

        .forgot {
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot:hover {
            color: white;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            background: var(--red);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 24px rgba(175, 16, 26, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        }

        .btn-login:hover {
            background: var(--red-l);
            transform: translateY(-1px);
            box-shadow: 0 8px 32px rgba(175, 16, 26, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider span {
            font-size: 11px;
            color: var(--muted);
            letter-spacing: 1px;
        }

        /* Security note */
        .security-note {
            display: flex;
            align-items: center;
            gap-8px;
            gap: 8px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 20px;
        }

        .security-note .material-symbols-outlined {
            font-size: 16px;
            color: var(--muted);
        }

        .security-note span {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.5;
        }

        /* Animate in */
        .right>* {
            animation: fadeUp 0.5s ease both;
        }

        .right>*:nth-child(1) {
            animation-delay: 0.1s;
        }

        .right>*:nth-child(2) {
            animation-delay: 0.2s;
        }

        .right>*:nth-child(3) {
            animation-delay: 0.25s;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile */
        @media (max-width: 768px) {
            .page {
                grid-template-columns: 1fr;
                overflow: hidden;
            }

            .left {
                display: none;
            }

            .right {
                padding: 40px 28px;
            }

            html,
            body {
                overflow: auto;
            }
        }
    </style>
</head>

<body>
    <div class="bg"></div>

    <div class="page">

        {{-- ═══ LEFT ═══ --}}
        <div class="left">
            <div class="brand">
                <div class="brand-icon">
                    <span class="material-symbols-outlined">security</span>
                </div>
                <div>
                    <div class="brand-name">CyberAudit</div>
                    <div class="brand-sub">Sistem Informasi Pra-Audit</div>
                </div>
            </div>

            <div class="hero">
                <div class="hero-tag">
                    <span></span>
                    Berbasis NIST CSF 2.0 & Standar Keamanan Siber Nasional
                </div>
                <h1 class="hero-title">
                    Keamanan Siber<br>
                    yang <em>Terukur</em><br>
                    & Terstruktur
                </h1>
                <p class="hero-desc">
                    Platform penilaian mandiri keamanan siber untuk perusahaan telekomunikasi, terintegrasi dengan
                    standar nasional dan internasional.
                </p>

                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-num">181+</div>
                        <div class="stat-label">Kriteria Penilaian</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">6</div>
                        <div class="stat-label">Fungsi NIST CSF</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">4</div>
                        <div class="stat-label">Level Tier</div>
                    </div>
                </div>
            </div>

            <div class="footer-text">
                © 2026 CyberAudit · Sistem Informasi Pra-Audit Keamanan Siber
            </div>
        </div>

        {{-- ═══ RIGHT ═══ --}}
        <div class="right">

            <div class="form-header">
                <h2 class="form-title">Selamat Datang</h2>
                <p class="form-sub">Masuk ke portal untuk melanjutkan assessment Anda</p>
            </div>

            {{-- Session Status --}}
            @if(session('status'))
                <div class="alert-error"
                    style="background:rgba(16,175,76,0.1); border-color:rgba(16,175,76,0.3); color:#6ee7b7;">
                    <span class="material-symbols-outlined" style="font-size:16px">check_circle</span>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Error --}}
            @if($errors->any())
                <div class="alert-error">
                    <span class="material-symbols-outlined" style="font-size:16px">error</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="field">
                    <label class="field-label" for="email">Email</label>
                    <div class="field-wrap">
                        <input id="email" type="email" name="email" class="field-input"
                            placeholder="nama@perusahaan.com" value="{{ old('email') }}" required autofocus
                            autocomplete="username">
                        <span class="material-symbols-outlined field-icon">mail</span>
                    </div>
                    @error('email')
                        <div class="field-error">
                            <span class="material-symbols-outlined" style="font-size:14px">warning</span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-wrap">
                        <input id="password" type="password" name="password" class="field-input"
                            placeholder="••••••••••" required autocomplete="current-password">
                        <span class="material-symbols-outlined field-icon">lock</span>
                        <button type="button" class="pw-toggle" onclick="togglePw()" id="pwToggle">
                            <span class="material-symbols-outlined" id="pwIcon">visibility_off</span>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">
                            <span class="material-symbols-outlined" style="font-size:14px">warning</span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="row">
                    <label class="remember">
                        <input type="checkbox" name="remember" id="remember_me">
                        <span>Ingat saya</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot">Lupa password?</a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login">
                    <span class="material-symbols-outlined" style="font-size:18px">login</span>
                    Masuk ke Portal
                </button>
            </form>

            <div class="security-note">
                <span class="material-symbols-outlined">shield</span>
                <span>Akses dibatasi berdasarkan peran. Hubungi Super Admin jika Anda tidak memiliki akun.</span>
            </div>

        </div>
    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('password');
            const icon = document.getElementById('pwIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }
    </script>
</body>

</html>