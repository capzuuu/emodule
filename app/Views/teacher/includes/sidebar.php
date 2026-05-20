<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$userName    = $_SESSION['user']['full_name'] ?? $_SESSION['user']['name'] ?? 'Teacher';
$initials    = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $userName), 0, 2))));

function teacherIsActive(string $path): string {
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
      <i class="bi bi-journal-text"></i> Modules
    </a>
    <a href="<?= baseurl('/teacher/tests') ?>" class="<?= teacherIsActive('/teacher/tests') ?>">
      <i class="bi bi-clipboard-check"></i> Tests
    </a>

    <span class="nav-label">Students</span>
    <a href="<?= baseurl('/teacher/students') ?>" class="<?= teacherIsActive('/teacher/students') ?>">
      <i class="bi bi-people-fill"></i> My Students
    </a>
    <a href="<?= baseurl('/teacher/grades') ?>" class="<?= teacherIsActive('/teacher/grades') ?>">
      <i class="bi bi-diagram-3-fill"></i> Grades & Sections
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
    <a href="<?= baseurl('/auth/logout') ?>" data-logout>
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
    <a href="<?= baseurl('/teacher/students') ?>" class="mbn-item <?= teacherIsActive('/teacher/students') ?>">
      <i class="bi bi-people-fill"></i>
      <span>Students</span>
    </a>
    <button class="mbn-item" id="mbn-more-btn" aria-expanded="false" aria-controls="mbn-drawer">
      <i class="bi bi-grid-3x3-gap-fill"></i>
      <span>More</span>
    </button>
  </div>
</nav>

<!-- ── MOBILE MORE DRAWER ── -->
<div id="mbn-drawer" aria-hidden="true">
  <div id="mbn-drawer-inner">
    <div class="mbn-drawer-header">
      <span>Menu</span>
      <button id="mbn-drawer-close" aria-label="Close"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="mbn-drawer-items">
      <a href="<?= baseurl('/teacher/tests') ?>" class="mbn-drawer-item <?= teacherIsActive('/teacher/tests') ?>">
        <i class="bi bi-clipboard-check"></i><span>Tests</span>
      </a>
      <a href="<?= baseurl('/teacher/grades') ?>" class="mbn-drawer-item <?= teacherIsActive('/teacher/grades') ?>">
        <i class="bi bi-diagram-3-fill"></i><span>Grades & Sections</span>
      </a>
      <a href="<?= baseurl('/teacher/progress') ?>" class="mbn-drawer-item <?= teacherIsActive('/teacher/progress') ?>">
        <i class="bi bi-graph-up"></i><span>Track Progress</span>
      </a>
      <a href="<?= baseurl('/teacher/profile') ?>" class="mbn-drawer-item <?= teacherIsActive('/teacher/profile') ?>">
        <i class="bi bi-person-circle"></i><span>My Profile</span>
      </a>
      <a href="<?= baseurl('/auth/logout') ?>" class="mbn-drawer-item mbn-drawer-logout" data-logout>
        <i class="bi bi-box-arrow-left"></i><span>Logout</span>
      </a>
    </div>
  </div>
</div>
<div id="mbn-overlay"></div>

<script nonce="<?= csp_nonce() ?>">
(function () {
  var btn     = document.getElementById('mbn-more-btn');
  var drawer  = document.getElementById('mbn-drawer');
  var overlay = document.getElementById('mbn-overlay');
  var close   = document.getElementById('mbn-drawer-close');

  function openDrawer()  { drawer.classList.add('open');  overlay.classList.add('open');  btn.setAttribute('aria-expanded','true');  }
  function closeDrawer() { drawer.classList.remove('open'); overlay.classList.remove('open'); btn.setAttribute('aria-expanded','false'); }

  btn.addEventListener('click', function () {
    drawer.classList.contains('open') ? closeDrawer() : openDrawer();
  });
  close.addEventListener('click', closeDrawer);
  overlay.addEventListener('click', closeDrawer);
})();
</script>
