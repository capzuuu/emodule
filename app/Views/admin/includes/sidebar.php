<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function isActive(string $path): string
{
  global $currentPath;
  return str_contains($currentPath, $path) ? 'active' : '';
}

$userName = $_SESSION['user']['full_name'] ?? 'Admin';
$initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $userName), 0, 2))));
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
    <div class="role-badge">Admin</div>
    <div class="user-name">
      <i class="bi bi-person-circle mr-1"></i>
      <?= htmlspecialchars($userName) ?>
    </div>
  </div>

  <div class="sidebar-nav">
    <span class="nav-label">Main</span>
    <a href="<?= baseurl('/admin/dashboard') ?>" class="<?= isActive('/admin/dashboard') ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <span class="nav-label">Users</span>
    <a href="<?= baseurl('/admin/userAccounts') ?>" class="<?= isActive('/admin/userAccounts') ?>">
      <i class="bi bi-people-fill"></i> User Management
    </a>

    <span class="nav-label">Content</span>
    <a href="<?= baseurl('/admin/modules') ?>" class="<?= isActive('/admin/modules') ?>">
      <i class="bi bi-journal-bookmark-fill"></i> Modules
    </a>

    <span class="nav-label">Reports</span>
    <a href="<?= baseurl('/admin/progress') ?>" class="<?= isActive('/admin/progress') ?>">
      <i class="bi bi-graph-up"></i> Student Progress
    </a>

    <span class="nav-label">Account</span>
    <a href="<?= baseurl('/admin/profile') ?>" class="<?= isActive('/admin/profile') ?>">
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

    <a href="<?= baseurl('/admin/dashboard') ?>" class="mbn-item <?= isActive('/admin/dashboard') ?>">
      <i class="bi bi-speedometer2"></i>
      <span>Dashboard</span>
    </a>

    <a href="<?= baseurl('/admin/userAccounts') ?>" class="mbn-item <?= isActive('/admin/userAccounts') ?>">
      <i class="bi bi-people-fill"></i>
      <span>Users</span>
    </a>

    <a href="<?= baseurl('/admin/modules') ?>" class="mbn-item <?= isActive('/admin/modules') ?>">
      <i class="bi bi-journal-bookmark-fill"></i>
      <span>Modules</span>
    </a>

    <button class="mbn-item" id="mbn-more-btn"
            aria-expanded="false" aria-controls="mbn-drawer">
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
      <a href="<?= baseurl('/admin/progress') ?>" class="mbn-drawer-item <?= isActive('/admin/progress') ?>">
        <i class="bi bi-graph-up"></i><span>Student Progress</span>
      </a>
      <a href="<?= baseurl('/admin/profile') ?>" class="mbn-drawer-item <?= isActive('/admin/profile') ?>">
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