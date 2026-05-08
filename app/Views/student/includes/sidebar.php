<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$userName    = $_SESSION['user']['full_name'] ?? $_SESSION['user']['name'] ?? 'Student';
$initials    = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $userName), 0, 2))));

function studentIsActive(string $path): string {
    global $currentPath;
    return str_contains($currentPath, $path) ? 'active' : '';
}
?>

<!-- ── MOBILE TOP HEADER ── -->
<div id="mobile-header">
  <div class="mh-brand">
    <img src="<?= asset('dist/assets/img/Rizal_logo.png') ?>" alt="Logo">
    <span>E-Module LMS</span>
  </div>
  <div class="mh-user">
    <span><?= htmlspecialchars($userName) ?></span>
    <div class="mh-avatar"><?= htmlspecialchars($initials) ?></div>
  </div>
</div>

<!-- ── DESKTOP SIDEBAR ── -->
<nav id="sidebar">
  <div class="sidebar-header">
    <img src="<?= asset('dist/assets/img/Rizal_logo.png') ?>" alt="Logo" class="school-logo">
    <div class="school-name">State University of Northern Negros</div>
    <div class="role-badge">Student</div>
    <div class="user-name">
      <i class="bi bi-person-circle mr-1"></i>
      <?= htmlspecialchars($userName) ?>
    </div>
  </div>

  <div class="sidebar-nav">
    <span class="nav-label">Main</span>
    <a href="<?= baseurl('/student/dashboard') ?>" class="<?= studentIsActive('/student/dashboard') ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <span class="nav-label">Learning</span>
    <a href="<?= baseurl('/student/modules') ?>" class="<?= studentIsActive('/student/modules') ?>">
      <i class="bi bi-journal-text"></i> My Modules
    </a>

    <span class="nav-label">Reports</span>
    <a href="<?= baseurl('/student/progress') ?>" class="<?= studentIsActive('/student/progress') ?>">
      <i class="bi bi-graph-up"></i> My Progress
    </a>

    <span class="nav-label">Account</span>
    <a href="<?= baseurl('/student/profile') ?>" class="<?= studentIsActive('/student/profile') ?>">
      <i class="bi bi-person-circle"></i> My Profile
    </a>
  </div>

  <div class="sidebar-logout">
    <a href="<?= baseurl('/auth/logout') ?>">
      <i class="bi bi-box-arrow-left"></i> Logout
    </a>
  </div>
</nav>

<!-- ── MOBILE BOTTOM NAV ── -->
<nav id="mobile-bottom-nav">
  <div class="mbn-items">
    <a href="<?= baseurl('/student/dashboard') ?>" class="mbn-item <?= studentIsActive('/student/dashboard') ?>">
      <i class="bi bi-speedometer2"></i>
      <span>Dashboard</span>
    </a>
    <a href="<?= baseurl('/student/modules') ?>" class="mbn-item <?= studentIsActive('/student/modules') ?>">
      <i class="bi bi-journal-text"></i>
      <span>Modules</span>
    </a>
    <a href="<?= baseurl('/student/progress') ?>" class="mbn-item <?= studentIsActive('/student/progress') ?>">
      <i class="bi bi-graph-up"></i>
      <span>Progress</span>
    </a>
    <a href="<?= baseurl('/student/profile') ?>" class="mbn-item <?= studentIsActive('/student/profile') ?>">
      <i class="bi bi-person-circle"></i>
      <span>Profile</span>
    </a>
  </div>
</nav>
