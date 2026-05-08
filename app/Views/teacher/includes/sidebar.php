<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$userName = $_SESSION['user']['full_name'] ?? 'Teacher';
$initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $userName), 0, 2))));

function teacherIsActive(string $path): string
{
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
    <div class="school-name">Sagay City Farm School</div>
    <div class="role-badge">Teacher</div>
    <div class="user-name">
      <i class="bi bi-person-circle mr-1"></i>
      <?= htmlspecialchars($userName) ?>
    </div>
  </div>

  <div class="sidebar-nav">
    <span class="nav-label">Main</span>
    <a href="<?= baseurl('/teacher/dashboard') ?>" class="<?= teacherIsActive('/teacher/dashboard') ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <span class="nav-label">Content</span>
    <a href="<?= baseurl('/teacher/modules') ?>" class="<?= teacherIsActive('/teacher/modules') ?>">
      <i class="bi bi-journal-text"></i> Module Management
    </a>
    <a href="<?= baseurl('/teacher/tests') ?>" class="<?= teacherIsActive('/teacher/tests') ?>">
      <i class="bi bi-patch-question"></i> Test Management
    </a>

    <span class="nav-label">Students</span>
    <a href="<?= baseurl('/teacher/students') ?>" class="<?= teacherIsActive('/teacher/students') ?>">
      <i class="bi bi-people-fill"></i> My Students
    </a>
    <a href="<?= baseurl('/teacher/grades') ?>" class="<?= teacherIsActive('/teacher/grades') ?>">
      <i class="bi bi-grid-3x3-gap"></i> Grades &amp; Sections
    </a>

    <span class="nav-label">Reports</span>
    <a href="<?= baseurl('/teacher/progress') ?>" class="<?= teacherIsActive('/teacher/progress') ?>">
      <i class="bi bi-graph-up"></i> Track Progress
    </a>

    <span class="nav-label">Account</span>
    <a href="<?= baseurl('/teacher/profile') ?>" class="<?= teacherIsActive('/teacher/profile') ?>">
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
    <a href="<?= baseurl('/teacher/dashboard') ?>" class="mbn-item <?= teacherIsActive('/teacher/dashboard') ?>">
      <i class="bi bi-speedometer2"></i>
      <span>Dashboard</span>
    </a>
    <a href="<?= baseurl('/teacher/modules') ?>" class="mbn-item <?= teacherIsActive('/teacher/modules') ?>">
      <i class="bi bi-journal-text"></i>
      <span>Modules</span>
    </a>
    <a href="<?= baseurl('/teacher/tests') ?>" class="mbn-item <?= teacherIsActive('/teacher/tests') ?>">
      <i class="bi bi-patch-question"></i>
      <span>Tests</span>
    </a>
    <a href="<?= baseurl('/teacher/students') ?>" class="mbn-item <?= teacherIsActive('/teacher/students') ?>">
      <i class="bi bi-people-fill"></i>
      <span>Students</span>
    </a>
    <a href="<?= baseurl('/teacher/progress') ?>" class="mbn-item <?= teacherIsActive('/teacher/progress') ?>">
      <i class="bi bi-graph-up"></i>
      <span>Progress</span>
    </a>
    <a href="<?= baseurl('/teacher/profile') ?>" class="mbn-item <?= teacherIsActive('/teacher/profile') ?>">
      <i class="bi bi-person-circle"></i>
      <span>Profile</span>
    </a>
  </div>
</nav>