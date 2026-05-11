<style nonce="<?= csp_nonce() ?>">
/* ════════════════════════════════════════════════════════════
   ADMIN GLOBAL STYLES — FULLY RESPONSIVE
   Breakpoints (mobile-first):
     xs   < 480px
     sm   576px
     md   768px
     lg   992px
     xl   1200px
     xxl  1400px
   ════════════════════════════════════════════════════════════ */

/* ── CSS VARIABLES ── */
:root {
  --primary:      #258517;
  --primary-dark: #1a5e10;
  --secondary:    #396619;
  --accent:       #F9A825;
  --bg:           #F8FAF7;
  --surface:      #FFFFFF;
  --text:         #1A1C19;
  --text-muted:   #5C6359;
  --divider:      #E0E4DB;
  --sidebar-bg:   #0f2410;
  --sidebar-w:    220px;
  --radius-card:  14px;
  --radius-btn:   8px;
}

/* ── BASE ── */
*, *::before, *::after { box-sizing: border-box; }

body {
  font-family: 'Nunito', sans-serif;
  background: var(--bg);
  font-size: 0.875rem;
  color: var(--text);
  -webkit-text-size-adjust: 100%;
}

img { max-width: 100%; height: auto; }

/* ════════════════════════════════════════════
   LAYOUT
   ════════════════════════════════════════════ */
#wrapper { display: flex; min-height: 100vh; }

#content-wrapper {
  margin-left: var(--sidebar-w);
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0; /* prevent flex overflow */
}
#content { flex: 1; }

/* ── Container fluid padding ── */
.container-fluid { padding-left: 20px; padding-right: 20px; }
@media (max-width: 575px) { .container-fluid { padding-left: 12px; padding-right: 12px; } }
@media (max-width: 375px) { .container-fluid { padding-left: 8px;  padding-right: 8px;  } }

/* ════════════════════════════════════════════
   SIDEBAR (desktop)
   ════════════════════════════════════════════ */
#sidebar {
  width: var(--sidebar-w);
  min-height: 100vh;
  background: var(--sidebar-bg);
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  position: fixed;
  top: 0; left: 0; bottom: 0;
  z-index: 100;
  box-shadow: 4px 0 24px rgba(0,0,0,0.25);
  overflow-y: auto;
}

.sidebar-header {
  padding: 16px 14px 12px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
  text-align: center;
}
.sidebar-header .school-logo {
  width: 48px; height: 48px;
  border-radius: 50%;
  object-fit: contain;
  background: #fff;
  padding: 4px;
  border: 2px solid rgba(255,255,255,0.2);
  box-shadow: 0 2px 8px rgba(0,0,0,0.4);
  margin: 0 auto 7px;
  display: block;
}
.sidebar-header .school-name {
  font-size: 0.72rem; font-weight: 700; color: #fff;
  margin: 0 0 4px; line-height: 1.3;
}
.sidebar-header .role-badge {
  background: rgba(249,168,37,0.18); color: var(--accent);
  font-size: 0.6rem; font-weight: 700;
  padding: 2px 8px; border-radius: 20px;
  letter-spacing: 0.6px; text-transform: uppercase;
  display: inline-block; margin-bottom: 4px;
}
.sidebar-header .user-name { color: rgba(255,255,255,0.45); font-size: 0.68rem; }

.sidebar-nav { flex: 1; padding: 6px 8px; overflow-y: auto; }
.nav-label {
  font-size: 0.58rem; font-weight: 700; letter-spacing: 1px;
  text-transform: uppercase; color: rgba(255,255,255,0.3);
  padding: 10px 6px 3px; display: block;
}
.sidebar-nav a {
  display: flex; align-items: center; gap: 8px;
  padding: 7px 10px;
  color: rgba(255,255,255,0.55);
  text-decoration: none; border-radius: 8px;
  font-size: 0.78rem; font-weight: 500;
  margin-bottom: 1px; transition: 0.18s;
}
.sidebar-nav a i { font-size: 0.85rem; flex-shrink: 0; }
.sidebar-nav a:hover  { color: #fff; background: rgba(255,255,255,0.07); }
.sidebar-nav a.active { color: #fff; background: var(--primary); box-shadow: 0 4px 12px rgba(37,133,23,0.4); }

.sidebar-logout { padding: 4px 8px 12px; }
.sidebar-logout a {
  display: flex; align-items: center; gap: 8px;
  color: rgba(248,113,113,0.8);
  padding: 7px 10px; border-radius: 8px;
  border: 1px solid rgba(248,113,113,0.15);
  font-size: 0.78rem; text-decoration: none; transition: 0.18s;
}
.sidebar-logout a:hover { background: rgba(248,113,113,0.1); color: #f87171; }

/* ════════════════════════════════════════════
   TOPBAR / NAVBAR
   ════════════════════════════════════════════ */
.topbar {
  background: var(--surface);
  border-bottom: 1px solid var(--divider);
  padding: 10px 24px;
  display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; z-index: 99;
  box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}
.topbar-title       { font-weight: 800; font-size: 1rem; color: var(--text); }
.topbar-breadcrumb  { display: flex; align-items: center; font-size: 0.72rem; color: var(--text-muted); margin-top: 2px; }
.topbar-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
.topbar-breadcrumb a:hover { text-decoration: underline; }
.topbar-breadcrumb-sep { margin: 0 5px; opacity: 0.4; }
.topbar-right       { display: flex; align-items: center; gap: 14px; }
.topbar-date        { font-size: 0.75rem; color: var(--text-muted); font-weight: 600; white-space: nowrap; }
.topbar-divider     { width: 1px; height: 28px; background: var(--divider); }
.topbar-user        { display: flex; align-items: center; gap: 8px; }
.topbar-user-name   { font-size: 0.8rem; font-weight: 700; color: var(--text); line-height: 1.2; }
.topbar-user-role   { font-size: 0.65rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; }
.topbar-avatar      { width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg,var(--primary),#4F9516); color: #fff; font-size: 0.7rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.topbar-logout      { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #dc2626; font-size: 1rem; transition: 0.15s; text-decoration: none; border: 1px solid rgba(220,38,38,0.2); }
.topbar-logout:hover { background: #fef2f2; color: #dc2626; border-color: rgba(220,38,38,0.4); }

/* ════════════════════════════════════════════
   STAT CARDS
   ════════════════════════════════════════════ */
.stat-card {
  background: var(--surface);
  border-radius: var(--radius-card);
  padding: 18px 20px;
  box-shadow: 0 2px 16px rgba(26,28,25,0.08);
  position: relative; overflow: hidden; border: none;
}
.stat-card::before {
  content: ''; position: absolute;
  top: 0; left: 0; right: 0; height: 4px;
  border-radius: var(--radius-card) var(--radius-card) 0 0;
}
.stat-label  { font-size: 0.72rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; }
.stat-number { font-size: 2rem; font-weight: 800; line-height: 1.1; margin: 4px 0 2px; }
.stat-sub    { font-size: 0.72rem; color: var(--text-muted); }
.stat-icon   { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
.card-accent-bar { height: 4px; }

/* ════════════════════════════════════════════
   FORM CARD
   ════════════════════════════════════════════ */
.form-card {
  background: var(--surface);
  border-radius: var(--radius-card);
  padding: 20px;
  box-shadow: 0 2px 16px rgba(26,28,25,0.07);
}

/* ════════════════════════════════════════════
   DATATABLES
   ════════════════════════════════════════════ */
.table thead th {
  background: #f0f7ee; color: var(--secondary);
  font-size: 0.78rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.5px;
  white-space: nowrap;
}
.table td { font-size: 0.82rem; vertical-align: middle; }

/* DataTables controls */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter { margin-bottom: 10px; }
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
  border-radius: 8px; border: 1px solid var(--divider);
  padding: 4px 8px; font-size: 0.82rem;
}
.dataTables_wrapper .dataTables_filter input { min-width: 0; width: auto; max-width: 100%; }
.dataTables_wrapper .dataTables_info { font-size: 0.78rem; color: var(--text-muted); }
.dataTables_wrapper .dataTables_paginate { margin-top: 8px; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
  background: var(--primary) !important; color: #fff !important;
  border-color: var(--primary) !important; border-radius: 6px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  background: #e8f5e9 !important; color: var(--primary) !important; border-radius: 6px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
  padding: 4px 10px !important; font-size: 0.8rem !important;
}

/* Responsive table wrapper */
.table-responsive { -webkit-overflow-scrolling: touch; }

/* ════════════════════════════════════════════
   DASHBOARD CARD
   ════════════════════════════════════════════ */
.dashboard-card { border: none; border-radius: var(--radius-card); overflow: hidden; }
.card-header-clean {
  background: var(--surface); border-bottom: 1px solid var(--divider);
  padding: 12px 16px; font-size: 0.82rem; font-weight: 700; color: var(--text);
}
.dashboard-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }

/* ── QUICK ACTIONS ── */
.quick-action-btn {
  display: flex; align-items: center; gap: 10px;
  background: var(--surface); border: 1px solid var(--divider);
  border-radius: 10px; padding: 10px 14px;
  text-decoration: none; color: var(--text);
  transition: 0.2s; font-size: 0.8rem; font-weight: 600; width: 100%;
}
.quick-action-btn:hover { border-color: var(--primary); box-shadow: 0 4px 16px rgba(37,133,23,0.12); transform: translateY(-2px); color: var(--text); }
.quick-action-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0; }

/* ── DOC STATUS ROW ── */
.doc-status-row { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--divider); }
.doc-status-row:last-child { border-bottom: none; }
.doc-status-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

/* ── ACTIVITY ── */
.activity-icon { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0; }
.activity-icon--created   { background: #e8f5e9; color: var(--primary); }
.activity-icon--updated   { background: #e3f2fd; color: #1565C0; }
.activity-icon--completed { background: #d1fae5; color: #059669; }
.activity-icon--deleted   { background: #fef2f2; color: #dc2626; }
.activity-icon--default   { background: #f3f4f6; color: #6b7280; }

/* ── PROGRESS BAR ── */
.progress-bar { background: linear-gradient(90deg,var(--primary),#4F9516); }

/* ── BADGES ── */
.badge-students { background: #e8f5e9; color: var(--primary); }
.badge-teachers { background: #fff8e1; color: #e65100; }
.badge-admin    { background: #e8f0fe; color: #1565C0; }

/* ── MODAL ── */
.modal-content { border-radius: 16px; }
.modal-header .close { text-shadow: none; }

/* ── FORM UTILITIES ── */
.form-group { margin-bottom: 1rem; }
.input-group-append .btn { border-top-left-radius: 0; border-bottom-left-radius: 0; }

/* ── FOOTER ── */
.admin-footer { background: var(--surface); border-top: 1px solid var(--divider); padding: 12px 24px; font-size: 0.72rem; color: var(--text-muted); text-align: center; }

/* ── NOTYF ── */
.notyf__toast { font-family: 'Nunito', sans-serif !important; font-size: 0.875rem !important; }

/* ════════════════════════════════════════════
   MOBILE TOP HEADER
   ════════════════════════════════════════════ */
#mobile-header {
  display: none;
  position: fixed; top: 0; left: 0; right: 0; z-index: 200;
  background: var(--sidebar-bg);
  padding: 10px 16px;
  align-items: center; justify-content: space-between;
  border-bottom: 1px solid rgba(255,255,255,0.08);
  box-shadow: 0 2px 12px rgba(0,0,0,0.3);
}
#mobile-header .mh-brand { display: flex; align-items: center; gap: 10px; }
#mobile-header .mh-brand img { width: 32px; height: 32px; border-radius: 50%; background: #fff; padding: 3px; object-fit: contain; }
#mobile-header .mh-brand span { color: #fff; font-weight: 700; font-size: 0.88rem; }
#mobile-header .mh-user { display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.6); font-size: 0.75rem; }
#mobile-header .mh-avatar { width: 30px; height: 30px; border-radius: 50%; background: var(--primary); color: #fff; font-size: 0.65rem; font-weight: 800; display: flex; align-items: center; justify-content: center; }

/* ════════════════════════════════════════════
   MOBILE BOTTOM NAV
   ════════════════════════════════════════════ */
#mobile-bottom-nav {
  display: none;
  position: fixed; bottom: 0; left: 0; right: 0; z-index: 200;
  background: var(--sidebar-bg);
  border-top: 1px solid rgba(255,255,255,0.08);
  box-shadow: 0 -4px 20px rgba(0,0,0,0.3);
  padding: 6px 0 calc(6px + env(safe-area-inset-bottom));
}
#mobile-bottom-nav .mbn-items { display: flex; justify-content: space-around; align-items: center; }
#mobile-bottom-nav .mbn-item {
  display: flex; flex-direction: column; align-items: center; gap: 3px;
  padding: 5px 10px; color: rgba(255,255,255,0.45);
  text-decoration: none; font-size: 0.6rem; font-weight: 600;
  letter-spacing: 0.3px; border-radius: 10px; transition: 0.15s;
  min-width: 56px; text-align: center; border: none; background: none; cursor: pointer;
}
#mobile-bottom-nav .mbn-item i { font-size: 1.2rem; line-height: 1; }
#mobile-bottom-nav .mbn-item.active { color: #fff; }
#mobile-bottom-nav .mbn-item.active i { background: var(--primary); color: #fff; border-radius: 10px; padding: 5px 12px; box-shadow: 0 4px 10px rgba(37,133,23,0.4); }
#mobile-bottom-nav .mbn-item:hover:not(.active) { color: rgba(255,255,255,0.75); }

/* ════════════════════════════════════════════
   RESPONSIVE BREAKPOINTS
   ════════════════════════════════════════════ */

/* ── xxl ≥ 1400px ── */
@media (min-width: 1400px) {
  .container-fluid { padding-left: 28px; padding-right: 28px; }
  .stat-number { font-size: 2.2rem; }
}

/* ── xl ≥ 1200px ── */
@media (min-width: 1200px) {
  .topbar { padding: 10px 28px; }
}

/* ── lg < 992px ── */
@media (max-width: 991px) {
  .stat-number { font-size: 1.7rem; }
  .stat-card   { padding: 14px 16px; }
  .form-card   { padding: 18px; }
  .topbar      { padding: 8px 18px; }
}

/* ── md < 768px — switch to mobile layout ── */
@media (max-width: 767px) {
  /* Hide desktop sidebar, show mobile chrome */
  #sidebar            { display: none !important; }
  #content-wrapper    { margin-left: 0 !important; }
  #content            { padding-bottom: 72px; }
  #mobile-header      { display: flex; }
  #mobile-bottom-nav  { display: block; }

  /* Topbar sits below mobile header */
  .topbar {
    top: 52px;
    padding: 8px 14px;
    padding-top: 60px;
  }
  .topbar-date,
  .topbar-divider,
  .topbar-user-info { display: none; }
  .topbar-right { gap: 8px; }

  /* Stat cards — 2 per row */
  .stat-card   { padding: 14px 14px; }
  .stat-number { font-size: 1.6rem; }
  .stat-icon   { width: 38px; height: 38px; font-size: 1rem; }

  /* Form card */
  .form-card { padding: 16px; border-radius: 12px; }

  /* DataTables controls stack */
  .dataTables_wrapper .dataTables_length,
  .dataTables_wrapper .dataTables_filter { width: 100%; text-align: left !important; }
  .dataTables_wrapper .dataTables_filter input { width: 100% !important; }
  .dataTables_wrapper .dataTables_info,
  .dataTables_wrapper .dataTables_paginate { text-align: center !important; float: none !important; }
  .dataTables_wrapper .dataTables_paginate { margin-top: 10px; }
  .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 5px 8px !important; font-size: 0.75rem !important; }

  /* Table font */
  .table td, .table th { font-size: 0.78rem; }

  /* Modal full-screen on mobile */
  .modal-dialog { margin: 8px; }
  .modal-dialog.modal-lg,
  .modal-dialog.modal-xl { max-width: calc(100vw - 16px); }

  /* Quick action buttons */
  .quick-action-btn { padding: 8px 10px; font-size: 0.75rem; }
  .quick-action-icon { width: 28px; height: 28px; font-size: 0.85rem; }

  /* Dashboard card header */
  .card-header-clean { padding: 10px 14px; font-size: 0.78rem; }

  /* Page title area */
  .d-flex.align-items-center.justify-content-between.mb-4 { flex-wrap: wrap; gap: 10px; }
  .d-flex.align-items-center.justify-content-between.mb-4 .btn { width: 100%; }
}

/* ── sm < 576px ── */
@media (max-width: 575px) {
  .container-fluid { padding-left: 10px; padding-right: 10px; }

  .stat-card   { padding: 12px; }
  .stat-number { font-size: 1.4rem; }
  .stat-label  { font-size: 0.65rem; }
  .stat-sub    { font-size: 0.65rem; }
  .stat-icon   { width: 34px; height: 34px; font-size: 0.9rem; border-radius: 10px; }

  .form-card { padding: 14px 12px; border-radius: 10px; }

  /* Table — hide less important columns via DataTables responsive or manual */
  .table td, .table th { font-size: 0.74rem; padding: 6px 8px; }

  /* Pagination compact */
  .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 4px 6px !important; font-size: 0.7rem !important; }

  /* Modals */
  .modal-dialog { margin: 4px; }
  .modal-content { border-radius: 12px; }
  .modal-body { padding: 14px 14px; }
  .modal-footer { padding: 10px 14px; }
  .modal-header { padding: 12px 14px; }

  /* Buttons full-width in page headers */
  .page-header-btn { width: 100%; }

  /* Progress bars */
  .progress { height: 5px !important; }

  /* Badge font */
  .badge { font-size: 0.68rem; }
}

/* ── xs < 480px ── */
@media (max-width: 479px) {
  .container-fluid { padding-left: 8px; padding-right: 8px; }

  .stat-number { font-size: 1.25rem; }
  .stat-card   { padding: 10px 10px; }

  /* Stack DataTables length + filter */
  .dataTables_wrapper > .row > div { width: 100% !important; }
  .dataTables_wrapper .dataTables_length label,
  .dataTables_wrapper .dataTables_filter label { display: flex; flex-direction: column; gap: 4px; font-size: 0.72rem; }
  .dataTables_wrapper .dataTables_filter input { width: 100% !important; }

  /* Table cells tighter */
  .table td, .table th { font-size: 0.7rem; padding: 5px 6px; }

  /* Mobile bottom nav items smaller */
  #mobile-bottom-nav .mbn-item { min-width: 44px; font-size: 0.55rem; padding: 4px 6px; }
  #mobile-bottom-nav .mbn-item i { font-size: 1.1rem; }

  /* Modals */
  .modal-dialog { margin: 2px; }
  .modal-body { padding: 12px 10px; }
  .modal-footer { padding: 8px 10px; flex-wrap: wrap; gap: 6px; }
  .modal-footer .btn { flex: 1 1 auto; }

  /* Form card */
  .form-card { padding: 12px 10px; }

  /* Quick action text */
  .quick-action-btn span { font-size: 0.7rem; }
}

/* ── xxs < 375px ── */
@media (max-width: 374px) {
  body { font-size: 0.8rem; }
  .container-fluid { padding-left: 6px; padding-right: 6px; }
  .stat-number { font-size: 1.1rem; }
  .stat-card   { padding: 8px; }
  .form-card   { padding: 10px 8px; }
  .table td, .table th { font-size: 0.65rem; padding: 4px 5px; }
  .topbar { padding: 6px 10px; padding-top: 58px; }
  .modal-body { padding: 10px 8px; }
  .modal-footer .btn { font-size: 0.78rem; padding: 6px 10px; }
  #mobile-bottom-nav .mbn-item { min-width: 38px; padding: 3px 4px; }
}

/* ════════════════════════════════════════════
   DATATABLES — RESPONSIVE COLUMN HIDING
   (works with DataTables responsive extension
    OR manual overflow scroll)
   ════════════════════════════════════════════ */
@media (max-width: 767px) {
  /* Allow horizontal scroll on table containers */
  .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
  /* Prevent table from collapsing too small */
  .table-responsive > table { min-width: 480px; }
}
@media (max-width: 479px) {
  .table-responsive > table { min-width: 360px; }
}

/* ════════════════════════════════════════════
   PRINT
   ════════════════════════════════════════════ */
@media print {
  #sidebar, #mobile-header, #mobile-bottom-nav,
  .topbar, .admin-footer, .btn, .dataTables_wrapper .dataTables_paginate { display: none !important; }
  #content-wrapper { margin-left: 0 !important; }
  .form-card, .stat-card, .dashboard-card { box-shadow: none !important; border: 1px solid #ddd !important; }
  body { font-size: 11pt; background: #fff; }
}
</style>
