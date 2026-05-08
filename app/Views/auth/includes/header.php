<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($GLOBALS['pageTitle'] ?? 'E-Module LMS') ?></title>
  <style nonce="<?= csp_nonce() ?>">
    @font-face {
      font-family: 'Nunito';
      src: url('<?= asset("dist/assets/fonts/Nunito-Regular.woff2") ?>') format('woff2'),
        url('<?= asset("dist/assets/fonts/Nunito-Regular.woff") ?>') format('woff');
      font-weight: 400;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Nunito';
      src: url('<?= asset("dist/assets/fonts/Nunito-SemiBold.woff2") ?>') format('woff2'),
        url('<?= asset("dist/assets/fonts/Nunito-SemiBold.woff") ?>') format('woff');
      font-weight: 600;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Nunito';
      src: url('<?= asset("dist/assets/fonts/Nunito-Bold.woff2") ?>') format('woff2'),
        url('<?= asset("dist/assets/fonts/Nunito-Bold.woff") ?>') format('woff');
      font-weight: 700;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Nunito';
      src: url('<?= asset("dist/assets/fonts/Nunito-ExtraBold.woff2") ?>') format('woff2'),
        url('<?= asset("dist/assets/fonts/Nunito-ExtraBold.woff") ?>') format('woff');
      font-weight: 800;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Nunito';
      src: url('<?= asset("dist/assets/fonts/Nunito-Black.woff2") ?>') format('woff2'),
        url('<?= asset("dist/assets/fonts/Nunito-Black.woff") ?>') format('woff');
      font-weight: 900;
      font-style: normal;
      font-display: swap;
    }
  </style>
  <link href="<?= asset('dist/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= asset('dist/assets/icons/bootstrap-icons.css') ?>" rel="stylesheet">
  <link href="<?= asset('dist/assets/css/notyf.min.css') ?>" rel="stylesheet">
  <link rel="icon" type="image/png" href="<?= asset('dist/assets/img/Rizal_logo.png') ?>">
</head>