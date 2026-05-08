<?php
$name     = $_SESSION['user']['full_name'] ?? $_SESSION['user']['name'] ?? 'Student';
$initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $name), 0, 2))));
$title    = $pageTitle ?? '';
$path     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = array_filter(explode('/', trim($path, '/')));
$crumbs   = [];
foreach ($segments as $seg) {
    if (in_array($seg, ['student', 'emodule2', 'public'])) continue;
    $crumbs[] = ucwords(str_replace(['-', '_'], ' ', $seg));
}
?>
<div class="topbar">
  <div>
    <div class="topbar-title"><?= htmlspecialchars($title) ?></div>
    <nav class="topbar-breadcrumb">
      <a href="<?= baseurl('/student/dashboard') ?>">Home</a>
      <?php foreach ($crumbs as $crumb): ?>
        <span class="topbar-breadcrumb-sep">/</span>
        <span><?= htmlspecialchars($crumb) ?></span>
      <?php endforeach; ?>
    </nav>
  </div>
  <div class="topbar-right">
    <div class="topbar-date">
      <i class="bi bi-calendar3 mr-1"></i>
      <span id="topbar-date-text"></span>
    </div>
    <div class="topbar-divider"></div>
    <div class="topbar-user">
      <div class="topbar-avatar"><?= htmlspecialchars($initials) ?></div>
      <div class="topbar-user-info">
        <div class="topbar-user-name"><?= htmlspecialchars($name) ?></div>
        <div class="topbar-user-role">Student</div>
      </div>
    </div>
    <div class="topbar-divider"></div>
    <a href="<?= baseurl('/auth/logout') ?>" class="topbar-logout" title="Logout">
      <i class="bi bi-box-arrow-right"></i>
    </a>
  </div>
</div>
<script nonce="<?= csp_nonce() ?>">
  (function(){ var el=document.getElementById('topbar-date-text'); if(el) el.textContent=new Date().toLocaleDateString('en-US',{weekday:'short',month:'short',day:'numeric',year:'numeric'}); })();
</script>
