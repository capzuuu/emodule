<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Under Maintenance – SUNN Sign</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800,900" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Nunito', sans-serif;
            background: #fffbf0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.18;
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            width: 500px; height: 500px;
            background: #f6c23e;
            top: -120px; left: -120px;
        }
        body::after {
            width: 400px; height: 400px;
            background: #fd7e14;
            bottom: -100px; right: -100px;
        }

        .error-wrap {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 40px 24px;
            max-width: 520px;
            width: 100%;
        }

        /* Gear icon with spin */
        .error-icon-wrap {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.6rem;
            color: #f0a500;
            box-shadow: 0 8px 24px rgba(240,165,0,0.2);
        }

        .error-icon-wrap .fa-cog {
            animation: spin 4s linear infinite;
        }
        .error-icon-wrap .fa-cog-sm {
            font-size: 1.4rem;
            animation: spin-reverse 3s linear infinite;
            margin-left: -8px;
            margin-bottom: -10px;
            vertical-align: bottom;
        }

        @keyframes spin         { from { transform: rotate(0deg); }   to { transform: rotate(360deg); } }
        @keyframes spin-reverse { from { transform: rotate(0deg); }   to { transform: rotate(-360deg); } }

        .error-badge {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .error-divider {
            width: 60px;
            height: 4px;
            border-radius: 2px;
            background: linear-gradient(90deg, #f6c23e, #fd7e14);
            margin: 18px auto 22px;
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 900;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .error-desc {
            font-size: 0.95rem;
            color: #718096;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        /* Progress bar */
        .maintenance-progress {
            background: #fde8a0;
            border-radius: 20px;
            height: 8px;
            overflow: hidden;
            margin: 0 auto 32px;
            max-width: 300px;
        }
        .maintenance-progress-bar {
            height: 100%;
            border-radius: 20px;
            background: linear-gradient(90deg, #f6c23e, #fd7e14);
            animation: progress 2.5s ease-in-out infinite alternate;
        }
        @keyframes progress {
            from { width: 30%; }
            to   { width: 85%; }
        }

        .btn-primary-error {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 28px;
            background: linear-gradient(135deg, #f6c23e, #e0a800);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(246,194,62,0.4);
        }
        .btn-primary-error:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(246,194,62,0.5);
            color: #fff;
            text-decoration: none;
        }

        /* ETA chip */
        .eta-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff;
            border: 1px solid #fde8a0;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 700;
            color: #856404;
            margin-bottom: 28px;
        }

        @media (max-width: 480px) {
            .error-title { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="error-wrap">
        <div class="error-icon-wrap">
            <i class="fas fa-cog"></i><i class="fas fa-cog fa-cog-sm"></i>
        </div>
        <span class="error-badge">Scheduled Maintenance</span>
        <div class="error-title">We'll Be Right Back</div>
        <div class="error-divider"></div>
        <p class="error-desc">
            SUNN Sign is currently undergoing scheduled maintenance<br>
            to improve your experience. Please check back shortly.
        </p>
        <div class="eta-chip">
            <i class="fas fa-clock"></i> Estimated downtime: a few minutes
        </div>
        <div class="maintenance-progress">
            <div class="maintenance-progress-bar"></div>
        </div>
        <a href="<?= baseurl('/') ?>" class="btn-primary-error">
            <i class="fas fa-redo"></i> Try Again
        </a>
    </div>
</body>
</html>
