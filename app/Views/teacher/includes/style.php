<style nonce="<?= csp_nonce() ?>">
  :root {
    --primary: #258517;
    --primary-dark: #1a5e10;
    --accent: #F9A825;
    --bg: #F8FAF7;
    --surface: #FFFFFF;
    --text: #1A1C19;
    --text-muted: #5C6359;
    --divider: #E0E4DB;
    --sidebar-bg: #0f2410;
  }

  body { font-family:'Nunito',sans-serif; background:var(--bg); font-size:0.875rem; color:var(--text); }
  #wrapper { display:flex; min-height:100vh; }

  /* ── SIDEBAR ── */
  #sidebar { width:220px; min-height:100vh; background:var(--sidebar-bg); flex-shrink:0; display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:100; box-shadow:4px 0 24px rgba(0,0,0,0.25); }
  .sidebar-header { padding:16px 14px 12px; border-bottom:1px solid rgba(255,255,255,0.08); text-align:center; }
  .sidebar-header .school-logo { width:48px; height:48px; border-radius:50%; object-fit:contain; background:#fff; padding:4px; border:2px solid rgba(255,255,255,0.2); box-shadow:0 2px 8px rgba(0,0,0,0.4); margin:0 auto 7px; display:block; }
  .sidebar-header .school-name { font-size:0.72rem; font-weight:700; color:#fff; margin:0 0 4px; line-height:1.3; }
  .sidebar-header .role-badge { background:rgba(249,168,37,0.18); color:var(--accent); font-size:0.6rem; font-weight:700; padding:2px 8px; border-radius:20px; letter-spacing:0.6px; text-transform:uppercase; display:inline-block; margin-bottom:4px; }
  .sidebar-header .user-name { color:rgba(255,255,255,0.45); font-size:0.68rem; }
  .sidebar-nav { flex:1; padding:6px 8px; overflow-y:auto; }
  .nav-label { font-size:0.58rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:rgba(255,255,255,0.3); padding:10px 6px 3px; display:block; }
  .sidebar-nav a { display:flex; align-items:center; gap:8px; padding:7px 10px; color:rgba(255,255,255,0.55); text-decoration:none; border-radius:8px; font-size:0.78rem; font-weight:500; margin-bottom:1px; transition:0.18s; }
  .sidebar-nav a i { font-size:0.85rem; flex-shrink:0; }
  .sidebar-nav a:hover { color:#fff; background:rgba(255,255,255,0.07); }
  .sidebar-nav a.active { color:#fff; background:var(--primary); box-shadow:0 4px 12px rgba(37,133,23,0.4); }
  .sidebar-logout { padding:4px 8px 12px; }
  .sidebar-logout a { display:flex; align-items:center; gap:8px; color:rgba(248,113,113,0.8); padding:7px 10px; border-radius:8px; border:1px solid rgba(248,113,113,0.15); font-size:0.78rem; text-decoration:none; transition:0.18s; }
  .sidebar-logout a:hover { background:rgba(248,113,113,0.1); color:#f87171; }

  /* ── CONTENT ── */
  #content-wrapper { margin-left:220px; flex:1; display:flex; flex-direction:column; }
  #content { flex:1; }

  /* ── TOPBAR ── */
  .topbar { background:var(--surface); border-bottom:1px solid var(--divider); padding:10px 24px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:99; box-shadow:0 1px 8px rgba(0,0,0,0.06); }
  .topbar-title { font-weight:800; font-size:1rem; color:var(--text); }
  .topbar-breadcrumb { display:flex; align-items:center; font-size:0.72rem; color:var(--text-muted); margin-top:2px; }
  .topbar-breadcrumb a { color:var(--primary); text-decoration:none; font-weight:600; }
  .topbar-breadcrumb a:hover { text-decoration:underline; }
  .topbar-breadcrumb-sep { margin:0 5px; opacity:0.4; }
  .topbar-right { display:flex; align-items:center; gap:14px; }
  .topbar-date { font-size:0.75rem; color:var(--text-muted); font-weight:600; white-space:nowrap; }
  .topbar-divider { width:1px; height:28px; background:var(--divider); }
  .topbar-user { display:flex; align-items:center; gap:8px; }
  .topbar-user-name { font-size:0.8rem; font-weight:700; color:var(--text); line-height:1.2; }
  .topbar-user-role { font-size:0.65rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.4px; }
  .topbar-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--primary),#4F9516); color:#fff; font-size:0.7rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
  .topbar-logout { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#dc2626; font-size:1rem; text-decoration:none; border:1px solid rgba(220,38,38,0.2); transition:0.15s; }
  .topbar-logout:hover { background:#fef2f2; color:#dc2626; border-color:rgba(220,38,38,0.4); }
  @media (max-width:768px) { .topbar-date, .topbar-divider, .topbar-user-info { display:none; } .topbar-right { gap:8px; } }

  /* ── CARDS ── */
  .stat-card { background:var(--surface); border-radius:14px; padding:18px 20px; box-shadow:0 2px 16px rgba(26,46,31,0.08); border:none; position:relative; overflow:hidden; }
  .card-accent-bar { height:4px; }
  .stat-label { font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.6px; }
  .stat-number { font-size:2rem; font-weight:800; line-height:1.1; margin:4px 0 2px; }
  .stat-sub { font-size:0.72rem; color:var(--text-muted); }
  .stat-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
  .form-card { background:var(--surface); border-radius:14px; padding:20px; box-shadow:0 2px 16px rgba(26,46,31,0.07); }
  .dashboard-card { border:none; border-radius:14px; overflow:hidden; }
  .card-header-clean { background:var(--surface); border-bottom:1px solid var(--divider); padding:12px 16px; font-size:.82rem; font-weight:700; color:var(--text); }

  /* ── TABLE ── */
  .table thead th { background:#f0f7ee; color:var(--primary); font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; }
  .table td { font-size:0.82rem; vertical-align:middle; }

  /* ── FOOTER ── */
  .admin-footer { background:var(--surface); border-top:1px solid var(--divider); padding:12px 24px; font-size:0.72rem; color:var(--text-muted); text-align:center; }

  /* ── NOTYF ── */
  .notyf__toast { font-family:'Nunito',sans-serif !important; font-size:0.875rem !important; }

  /* ── MOBILE TOP HEADER ── */
  #mobile-header { display:none; position:fixed; top:0; left:0; right:0; z-index:200; background:var(--sidebar-bg); padding:10px 16px; align-items:center; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.08); box-shadow:0 2px 12px rgba(0,0,0,0.3); }
  #mobile-header .mh-brand { display:flex; align-items:center; gap:10px; }
  #mobile-header .mh-brand img { width:32px; height:32px; border-radius:50%; background:#fff; padding:3px; object-fit:contain; }
  #mobile-header .mh-brand span { color:#fff; font-weight:700; font-size:0.88rem; }
  #mobile-header .mh-user { display:flex; align-items:center; gap:8px; color:rgba(255,255,255,0.6); font-size:0.75rem; }
  #mobile-header .mh-avatar { width:30px; height:30px; border-radius:50%; background:var(--primary); color:#fff; font-size:0.65rem; font-weight:800; display:flex; align-items:center; justify-content:center; }

  /* ── MOBILE BOTTOM NAV ── */
  #mobile-bottom-nav { display:none; position:fixed; bottom:0; left:0; right:0; z-index:200; background:var(--sidebar-bg); border-top:1px solid rgba(255,255,255,0.08); padding:6px 0 calc(6px + env(safe-area-inset-bottom)); box-shadow:0 -4px 20px rgba(0,0,0,0.3); }
  #mobile-bottom-nav .mbn-items { display:flex; justify-content:space-around; align-items:center; }
  #mobile-bottom-nav .mbn-item { display:flex; flex-direction:column; align-items:center; gap:3px; padding:5px 8px; color:rgba(255,255,255,0.45); text-decoration:none; font-size:0.6rem; font-weight:600; border-radius:10px; transition:0.15s; min-width:52px; text-align:center; border:none; background:none; cursor:pointer; }
  #mobile-bottom-nav .mbn-item i { font-size:1.2rem; line-height:1; }
  #mobile-bottom-nav .mbn-item.active { color:#fff; }
  #mobile-bottom-nav .mbn-item.active i { background:var(--primary); color:#fff; border-radius:10px; padding:5px 12px; box-shadow:0 4px 10px rgba(37,133,23,0.4); }

  /* ── MOBILE MORE DRAWER ── */
  #mbn-overlay { display:none; position:fixed; inset:0; z-index:299; background:rgba(0,0,0,0.45); backdrop-filter:blur(2px); }
  #mbn-overlay.open { display:block; }
  #mbn-drawer { position:fixed; bottom:0; left:0; right:0; z-index:300; transform:translateY(100%); transition:transform .28s cubic-bezier(.4,0,.2,1); background:var(--sidebar-bg); border-radius:20px 20px 0 0; box-shadow:0 -8px 32px rgba(0,0,0,0.4); padding-bottom:calc(env(safe-area-inset-bottom) + 8px); visibility:hidden; pointer-events:none; }
  #mbn-drawer.open { transform:translateY(0); visibility:visible; pointer-events:all; }
  #mbn-drawer-inner { padding:0 0 8px; }
  .mbn-drawer-header { display:flex; align-items:center; justify-content:space-between; padding:14px 20px 10px; border-bottom:1px solid rgba(255,255,255,0.08); font-size:.78rem; font-weight:700; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:.6px; }
  .mbn-drawer-header button { background:none; border:none; color:rgba(255,255,255,0.5); font-size:1rem; cursor:pointer; padding:4px; line-height:1; transition:color .15s; }
  .mbn-drawer-header button:hover { color:#fff; }
  .mbn-drawer-items { padding:8px 12px; }
  .mbn-drawer-item { display:flex; align-items:center; gap:14px; padding:12px 14px; border-radius:10px; color:rgba(255,255,255,0.65); text-decoration:none; font-size:.85rem; font-weight:600; transition:background .15s,color .15s; margin-bottom:2px; }
  .mbn-drawer-item i { font-size:1.1rem; flex-shrink:0; width:22px; text-align:center; }
  .mbn-drawer-item:hover, .mbn-drawer-item.active { background:rgba(255,255,255,0.08); color:#fff; }
  .mbn-drawer-item.active { background:var(--primary); color:#fff; box-shadow:0 4px 12px rgba(37,133,23,0.35); }
  .mbn-drawer-logout { color:rgba(248,113,113,0.8) !important; margin-top:4px; border-top:1px solid rgba(255,255,255,0.06); padding-top:14px !important; }
  .mbn-drawer-logout:hover { background:rgba(248,113,113,0.1) !important; color:#f87171 !important; }

  /* ── RESPONSIVE ── */
  @media (max-width:768px) {
    #sidebar { display:none !important; }
    #content-wrapper { margin-left:0 !important; }
    #content { padding-bottom:70px; }
    #mobile-header { display:flex; }
    #mobile-bottom-nav { display:block; }
    .topbar { top:52px; }
  }
</style>
