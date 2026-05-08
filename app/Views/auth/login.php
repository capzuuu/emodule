<!DOCTYPE html>
<html lang="en">

<?php auth_view_layout(['header']); ?>

<head>
  <style nonce="<?= csp_nonce() ?>">
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
      margin: 0;
      font-family: 'Nunito', sans-serif;
      background: #f8f9fc;
    }

    .login-split {
      display: flex;
      min-height: 100vh;
    }

    /* ── LEFT PANEL ── */
    .login-left {
      flex: 0 0 52%;
      background: linear-gradient(150deg, #0f2410 0%, #1a5e10 50%, #258517 100%);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 52px 56px 36px;
      position: relative;
      overflow: hidden;
    }

    .login-left::before {
      content: '';
      position: absolute;
      top: -100px;
      right: -100px;
      width: 380px;
      height: 380px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.04);
      pointer-events: none;
    }

    .login-left::after {
      content: '';
      position: absolute;
      bottom: -80px;
      left: -80px;
      width: 280px;
      height: 280px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.03);
      pointer-events: none;
    }

    .login-left-grid {
      position: absolute;
      inset: 0;
      background-image: radial-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px);
      background-size: 28px 28px;
      pointer-events: none;
    }

    .login-left-inner {
      position: relative;
      z-index: 1;
    }

    .login-left-logo {
      margin-bottom: 28px;
    }

    .login-left-logo img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: contain;
      background: rgba(255, 255, 255, 0.15);
      padding: 8px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .login-left-title {
      font-size: 2.4rem;
      font-weight: 900;
      color: #fff;
      margin: 0 0 6px;
      letter-spacing: -0.03em;
      line-height: 1.1;
    }

    .login-left-title span {
      color: rgba(255, 255, 255, 0.5);
    }

    .login-left-sub {
      font-size: 0.7rem;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.45);
      letter-spacing: 0.1em;
      text-transform: uppercase;
      margin: 0 0 28px;
    }

    .login-left-tagline {
      font-size: 0.95rem;
      color: rgba(255, 255, 255, 0.75);
      line-height: 1.75;
      max-width: 340px;
      margin-bottom: 36px;
    }

    .login-left-features {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .login-feature-item {
      display: flex;
      align-items: center;
      gap: 14px;
      color: rgba(255, 255, 255, 0.85);
      font-size: 0.875rem;
      font-weight: 600;
    }

    .login-feature-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.12);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      flex-shrink: 0;
    }

    .login-left-footer {
      position: relative;
      z-index: 1;
      font-size: 0.68rem;
      color: rgba(255, 255, 255, 0.3);
      letter-spacing: 0.04em;
    }

    /* ── RIGHT PANEL ── */
    .login-right {
      flex: 0 0 48%;
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
      box-shadow: 0 4px 32px rgba(15, 36, 16, 0.1);
    }

    .login-pill {
      display: inline-block;
      background: #e8f5e9;
      border: 1px solid #c8e6c9;
      color: #258517;
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
    }

    .login-subheading {
      font-size: 0.82rem;
      color: #8a94a6;
      margin: 0;
      line-height: 1.6;
    }

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

    .input-wrap {
      position: relative;
    }

    .input-wrap .input-icon {
      position: absolute;
      top: 50%;
      left: 14px;
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
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input-wrap .form-control:focus {
      border-color: #258517;
      box-shadow: 0 0 0 3px rgba(37, 133, 23, 0.12);
      background: #fff;
      outline: none;
    }

    #togglePassword {
      position: absolute;
      top: 50%;
      right: 14px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #b7b9cc;
      font-size: 13px;
      transition: color .15s;
    }

    #togglePassword:hover {
      color: #258517;
    }

    .btn-login {
      width: 100%;
      height: 46px;
      border-radius: 10px;
      font-weight: 700;
      font-size: 0.875rem;
      letter-spacing: 0.04em;
      background: linear-gradient(135deg, #258517 0%, #1a5e10 100%);
      border: none;
      color: #fff;
      cursor: pointer;
      transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 16px rgba(37, 133, 23, 0.35);
      margin-top: 6px;
    }

    .btn-login:hover:not(:disabled) {
      opacity: 0.93;
      transform: translateY(-1px);
      box-shadow: 0 6px 22px rgba(37, 133, 23, 0.42);
    }

    .btn-login:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    @media (max-width: 900px) {
      .login-split {
        flex-direction: column;
      }

      .login-left {
        flex: none;
        width: 100%;
        padding: 20px 24px 18px;
        justify-content: flex-start;
      }

      .login-left-tagline,
      .login-left-features,
      .login-left-footer {
        display: none;
      }

      .login-left-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 6px;
      }

      .login-left-logo {
        margin-bottom: 4px;
      }

      .login-left-logo img {
        width: 42px;
        height: 42px;
      }

      .login-left-title {
        font-size: 1.45rem;
        margin: 0;
      }

      .login-left-sub {
        font-size: 0.65rem;
        margin: 0;
      }

      .login-right {
        flex: 1;
        padding: 32px 20px 40px;
      }

      .login-form-wrap {
        max-width: 480px;
        padding: 32px 28px 28px;
      }
    }

    @media (max-width: 480px) {
      .login-right {
        padding: 24px 16px 36px;
        align-items: flex-start;
      }

      .login-form-wrap {
        border-radius: 16px;
        padding: 28px 20px 24px;
      }

      .login-heading {
        font-size: 1.4rem;
      }
    }
  </style>
</head>

<body>
  <div class="login-split">

    <!-- LEFT PANEL -->
    <div class="login-left">
      <div class="login-left-grid"></div>
      <div class="login-left-inner">

        <div class="login-left-logo">
          <img src="<?= asset('dist/assets/img/Rizal_logo.png') ?>" alt="School Logo">
        </div>

        <h1 class="login-left-title">E-Module<span>LMS</span></h1>
        <p class="login-left-sub">Sagay City Farm School</p>

        <p class="login-left-tagline">
          Your digital learning platform for modules, quizzes, and progress tracking.
        </p>

        <div class="login-left-features">
          <div class="login-feature-item">
            <div class="login-feature-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
            <span>Interactive Learning Modules</span>
          </div>
          <div class="login-feature-item">
            <div class="login-feature-icon"><i class="bi bi-patch-check-fill"></i></div>
            <span>Quiz & Progress Tracking</span>
          </div>
          <div class="login-feature-item">
            <div class="login-feature-icon"><i class="bi bi-people-fill"></i></div>
            <span>Student & Teacher Management</span>
          </div>
        </div>

      </div>

      <div class="login-left-footer">
        &copy; <?= date('Y') ?> Sagay City Farm School — E-Module LMS
      </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="login-right">
      <div class="login-form-wrap">

        <div style="margin-bottom:28px;">
          <div class="login-pill">Welcome back</div>
          <h2 class="login-heading">Log in to continue</h2>
          <p class="login-subheading">E-Module Learning Management System</p>
        </div>

        <!-- Flash Alerts -->
        <?php if (!empty($flashError)): ?>
          <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert"
            style="font-size:.82rem;border-radius:10px;">
            <i class="bi bi-exclamation-triangle-fill mr-1"></i>
            <?= htmlspecialchars($flashError, ENT_COMPAT, 'UTF-8') ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
          </div>
        <?php endif; ?>

        <?php if (!empty($flashSuccess)): ?>
          <div class="alert alert-success alert-dismissible fade show mb-3" role="alert"
            style="font-size:.82rem;border-radius:10px;">
            <i class="bi bi-check-circle-fill mr-1"></i>
            <?= htmlspecialchars($flashSuccess, ENT_COMPAT, 'UTF-8') ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
          </div>
        <?php endif; ?>

        <!-- LOGIN FORM -->
        <form id="loginForm" action="<?= baseurl('/auth/login/authenticate') ?>" method="POST">
          <input type="hidden" name="_csrf" value="<?= $this->generateCSRF() ?>">

          <div class="login-field">
            <label class="login-label">Email Address</label>
            <div class="input-wrap">
              <i class="bi bi-envelope input-icon"></i>
              <input type="email" name="email" class="form-control" placeholder="you@school.edu.ph" required autofocus
                autocomplete="off">
            </div>
          </div>

          <div class="login-field">
            <label class="login-label">Password</label>
            <div class="input-wrap">
              <i class="bi bi-lock input-icon"></i>
              <input type="password" id="password" name="password" class="form-control"
                placeholder="Enter your password" required autocomplete="current-password">
              <i class="bi bi-eye" id="togglePassword"></i>
            </div>
          </div>

          <button type="submit" class="btn-login" id="loginBtn">
            <span id="loginBtnText">Sign In</span>
            <span class="spinner-border spinner-border-sm d-none ml-1" id="loginSpinner"></span>
          </button>
        </form>

      </div>
    </div>

  </div>

  <script nonce="<?= csp_nonce() ?>">
    document.addEventListener('DOMContentLoaded', function () {
      const pwd = document.getElementById('password');
      const toggle = document.getElementById('togglePassword');

      toggle.addEventListener('click', function () {
        const show = pwd.type === 'password';
        pwd.type = show ? 'text' : 'password';
        this.classList.toggle('bi-eye', !show);
        this.classList.toggle('bi-eye-slash', show);
      });

      document.getElementById('loginForm').addEventListener('submit', function () {
        document.getElementById('loginBtnText').textContent = 'Signing in...';
        document.getElementById('loginSpinner').classList.remove('d-none');
        document.getElementById('loginBtn').disabled = true;
      });
    });
  </script>

  <?php auth_view_layout(['script']); ?>

</body>

</html>