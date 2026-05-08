<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 – Server Error</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800,900" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: #fff5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.18;
            pointer-events: none;
            z-index: 0;
        }

        body::before {
            width: 500px;
            height: 500px;
            background: #e74a3b;
            top: -120px;
            left: -120px;
        }

        body::after {
            width: 400px;
            height: 400px;
            background: #f6c23e;
            bottom: -100px;
            right: -100px;
        }

        .error-wrap {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 40px 24px;
            max-width: 520px;
            width: 100%;
        }

        .error-code {
            font-size: 9rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #e74a3b 0%, #f6c23e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -4px;
            user-select: none;
        }

        .error-code span {
            display: inline-block;
            animation: shake 2.4s ease-in-out infinite;
        }

        .error-code span:nth-child(1) {
            animation-delay: 0s;
        }

        .error-code span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .error-code span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            25% {
                transform: translateY(-10px) rotate(-3deg);
            }

            75% {
                transform: translateY(-10px) rotate(3deg);
            }
        }

        .error-divider {
            width: 60px;
            height: 4px;
            border-radius: 2px;
            background: linear-gradient(90deg, #e74a3b, #f6c23e);
            margin: 18px auto 22px;
        }

        .error-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .error-desc {
            font-size: 0.95rem;
            color: #718096;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .btn-group-error {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-error {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: linear-gradient(135deg, #e74a3b, #c0392b);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(231, 74, 59, 0.35);
        }

        .btn-primary-error:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 74, 59, 0.45);
            color: #fff;
            text-decoration: none;
        }

        .btn-outline-error {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: #fff;
            color: #e74a3b;
            border: 2px solid #e74a3b;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-outline-error:hover {
            transform: translateY(-2px);
            background: #fff5f5;
            color: #e74a3b;
            text-decoration: none;
        }

        .error-icon-wrap {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fde8e8, #fcc);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.2rem;
            color: #e74a3b;
            box-shadow: 0 8px 24px rgba(231, 74, 59, 0.15);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 8px 24px rgba(231, 74, 59, 0.15);
            }

            50% {
                box-shadow: 0 8px 32px rgba(231, 74, 59, 0.30);
            }
        }

        .error-badge {
            display: inline-block;
            background: #fde8e8;
            color: #c0392b;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        @media (max-width: 480px) {
            .error-code {
                font-size: 6rem;
            }

            .error-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>
    <div class="error-wrap">
        <div class="error-icon-wrap">
            <i class="fas fa-server"></i>
        </div>
        <span class="error-badge">Error 500</span>
        <div class="error-code"><span>5</span><span>0</span><span>0</span></div>
        <div class="error-divider"></div>
        <div class="error-title">Internal Server Error</div>
        <p class="error-desc">
            Something went wrong on our end. Our team has been notified.<br>
            Please try again in a few moments.
        </p>
        <div class="btn-group-error">
            <a href="javascript:history.back()" class="btn-primary-error">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
            <a href="<?= baseurl('/') ?>" class="btn-outline-error">
                <i class="fas fa-home"></i> Home
            </a>
        </div>
    </div>
</body>

</html>