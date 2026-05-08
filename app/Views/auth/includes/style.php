    <style>
        *,
        *::before,
        *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background: #f8f9fc;
        }

        /* ═══════════════════════════════════════
           SPLIT LAYOUT
        ═══════════════════════════════════════ */
        .login-split {
            display: flex;
            min-height: 100vh;
        }

        /* ═══════════════════════════════════════
           LEFT PANEL
        ═══════════════════════════════════════ */
        .login-left {
            flex: 0 0 55%;
            background: linear-gradient(150deg, #1a3a8f 0%, #2f5be0 50%, #1e40af 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 52px 56px 36px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        .login-left::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 380px; height: 380px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            pointer-events: none;
        }
        .login-left::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            pointer-events: none;
        }

        /* Dot grid overlay */
        .login-left-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .login-left-inner {
            position: relative;
            z-index: 1;
        }

        .login-left-logo {
            margin-bottom: 32px;
        }

        .login-left-logo img {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            object-fit: contain;
            background: rgba(255,255,255,0.15);
            padding: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        }

        .login-left-title {
            font-size: 2.6rem;
            font-weight: 900;
            color: #fff;
            margin: 0 0 6px;
            letter-spacing: -0.03em;
            line-height: 1.1;
        }

        .login-left-title span {
            color: rgba(255,255,255,0.55);
        }

        .login-left-sub {
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin: 0 0 32px;
        }

        .login-left-tagline {
            font-size: 1rem;
            color: rgba(255,255,255,0.8);
            line-height: 1.75;
            max-width: 340px;
            margin-bottom: 40px;
        }

        .login-left-features {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .login-feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(255,255,255,0.88);
            font-size: 0.875rem;
            font-weight: 600;
        }

        .login-feature-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .login-left-footer {
            position: relative;
            z-index: 1;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.35);
            letter-spacing: 0.04em;
        }

        /* ═══════════════════════════════════════
           RIGHT PANEL
        ═══════════════════════════════════════ */
        .login-right {
            flex: 0 0 45%;
            background: #f4f6fb;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
        }

        .login-form-wrap {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 20px;
            padding: 40px 36px 36px;
            box-shadow: 0 4px 32px rgba(30,40,100,0.08);
        }

        .login-form-header {
            margin-bottom: 28px;
        }

        .login-pill {
            display: inline-block;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            color: #4e73df;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .login-heading {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a1f36;
            margin: 0 0 5px;
            line-height: 1.2;
        }

        .login-subheading {
            font-size: 0.82rem;
            color: #8a94a6;
            margin: 0;
            line-height: 1.6;
        }

        /* ── Fields ── */
        .login-field {
            margin-bottom: 16px;
        }

        .login-label {
            font-size: 0.76rem;
            font-weight: 700;
            color: #4a5568;
            margin-bottom: 6px;
            display: block;
        }

        /* ── Inputs ── */
        .input-wrap {
            position: relative;
        }

        .input-wrap .input-icon {
            position: absolute;
            top: 50%; left: 14px;
            transform: translateY(-50%);
            color: #b7b9cc;
            font-size: 13px;
            pointer-events: none;
        }

        .input-wrap .form-control {
            padding-left: 2.6rem;
            padding-right: 2.6rem;
            height: 46px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.875rem;
            background: #f8fafc;
            color: #1a1f36;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .input-wrap .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 3px rgba(78,115,223,0.12);
            background: #fff;
            outline: none;
        }

        #togglePassword {
            position: absolute;
            top: 50%; right: 14px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #b7b9cc;
            font-size: 13px;
            transition: color .15s;
        }

        #togglePassword:hover { color: #4e73df; }

        /* ── Login button ── */
        .btn-login {
            width: 100%;
            height: 46px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.875rem;
            letter-spacing: 0.04em;
            background: linear-gradient(135deg, #4e73df 0%, #1e40af 100%);
            border: none;
            color: #fff;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(78,115,223,0.35);
            margin-top: 6px;
        }

        .btn-login:hover:not(:disabled) {
            opacity: 0.93;
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(78,115,223,0.42);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ── Divider ── */
        .login-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0 14px;
            color: #c4c9d8;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e8ecf4;
        }

        /* ── Google button ── */
        .btn-google {
            width: 100%;
            height: 46px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #3c4043;
            cursor: pointer;
            transition: background 0.18s, border-color 0.18s, box-shadow 0.18s;
        }

        .btn-google:hover {
            background: #f8f9fc;
            border-color: #c5c8d6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }

        .google-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════
           DARK MODE
        ═══════════════════════════════════════ */
        html.dark-mode,
        html.dark-mode body { background: #0f1117 !important; color: #e2e8f0; }

        html.dark-mode .login-right { background: #0f1117; }

        html.dark-mode .login-form-wrap {
            background: #1a1d2e;
            box-shadow: 0 4px 32px rgba(0,0,0,0.4);
        }

        html.dark-mode .login-heading { color: #e2e8f0; }
        html.dark-mode .login-subheading { color: #64748b; }

        html.dark-mode .login-pill {
            background: rgba(125,164,245,0.1);
            border-color: rgba(125,164,245,0.25);
            color: #7da4f5;
        }

        html.dark-mode .login-label { color: #94a3b8 !important; }

        html.dark-mode .input-wrap .form-control {
            background: #252840 !important;
            border-color: #2e3254 !important;
            color: #e2e8f0 !important;
        }

        html.dark-mode .input-wrap .form-control::placeholder { color: #4a5568 !important; }

        html.dark-mode .input-wrap .form-control:focus {
            border-color: #4e73df !important;
            box-shadow: 0 0 0 3px rgba(78,115,223,0.18) !important;
            background: #2a2d48 !important;
        }

        html.dark-mode .input-wrap .input-icon,
        html.dark-mode #togglePassword { color: #4a5568; }

        html.dark-mode .login-divider { color: #2e3254; }
        html.dark-mode .login-divider::before,
        html.dark-mode .login-divider::after { background: #2e3254; }

        html.dark-mode .btn-google {
            background: #252840;
            border-color: #2e3254;
            color: #e2e8f0;
        }

        html.dark-mode .btn-google:hover {
            background: #2e3254;
            border-color: #3a3f6a;
        }

        /* ═══════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════ */
        @media (max-width: 900px) {
            .login-split { flex-direction: column; min-height: 100vh; }

            .login-left {
                flex: none;
                width: 100%;
                padding: 20px 24px 18px;
                justify-content: flex-start;
            }

            .login-left-tagline,
            .login-left-features,
            .login-left-footer { display: none; }

            .login-left-inner {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 6px;
            }

            .login-left-logo { margin-bottom: 4px; flex-shrink: 0; }
            .login-left-logo img { width: 42px; height: 42px; border-radius: 10px; }
            .login-left-title { font-size: 1.45rem; margin: 0; }
            .login-left-sub { font-size: 0.65rem; margin: 0; }

            .login-right {
                flex: 1;
                padding: 32px 20px 40px;
                background: #f4f6fb;
            }

            .login-form-wrap {
                max-width: 480px;
                padding: 32px 28px 28px;
            }

            html.dark-mode .login-right { background: #0f1117; }
        }

        @media (max-width: 480px) {
            .login-left { padding: 20px 20px 16px; }
            .login-left-logo img { width: 36px; height: 36px; }
            .login-left-title { font-size: 1.2rem; }

            .login-right { padding: 24px 16px 36px; align-items: flex-start; }

            .login-form-wrap {
                border-radius: 16px;
                padding: 28px 20px 24px;
            }

            .login-heading { font-size: 1.4rem; }
        }
    </style>
