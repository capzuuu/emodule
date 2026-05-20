<footer class="admin-footer">
  &copy; <?= date('Y') ?> State University of Northern Negros — E-Module LMS. All rights reserved.
</footer>

<!-- Skeleton Page Loader -->
<div id="page-skeleton" style="display:none;position:fixed;top:0;bottom:0;right:0;z-index:9999;background:#F8FAF7;overflow:hidden;">
  <style nonce="<?= csp_nonce() ?>">
    @keyframes skel-shimmer{0%{background-position:-600px 0}100%{background-position:600px 0}}
    .skel{border-radius:6px;background:linear-gradient(90deg,#e8ede6 25%,#d4dbd2 50%,#e8ede6 75%);background-size:600px 100%;animation:skel-shimmer 1.4s infinite linear;}
    #sk-topbar{height:54px;background:#fff;border-bottom:1px solid #E0E4DB;display:flex;align-items:center;padding:0 24px;gap:12px;}
    #sk-body{padding:20px 24px;flex:1;}
    .sk-cards{display:grid;gap:12px;margin-bottom:20px;grid-template-columns:repeat(4,1fr);}
    .sk-card{background:#fff;border-radius:14px;padding:16px;box-shadow:0 2px 16px rgba(0,0,0,0.06);}
    @media(max-width:767px){#sk-topbar{display:none!important;}#sk-body{padding:16px 12px;}.sk-cards{grid-template-columns:repeat(2,1fr);}}
  </style>
  <div style="display:flex;flex-direction:column;height:100%;">
    <div id="sk-topbar">
      <div class="skel" style="width:140px;height:16px;"></div>
      <div style="flex:1;"></div>
      <div class="skel" style="width:70px;height:12px;"></div>
      <div class="skel" style="width:34px;height:34px;border-radius:50%;"></div>
    </div>
    <div id="sk-body">
      <div class="skel" style="width:55%;max-width:260px;height:20px;margin-bottom:20px;"></div>
      <div class="sk-cards">
        <?php for($i=0;$i<4;$i++): ?>
        <div class="sk-card">
          <div class="skel" style="width:60%;height:11px;margin-bottom:10px;"></div>
          <div class="skel" style="width:40%;height:26px;margin-bottom:8px;"></div>
          <div class="skel" style="width:75%;height:10px;"></div>
        </div>
        <?php endfor; ?>
      </div>
    </div>
  </div>
</div>

<!-- Logout Spinner -->
<div id="logout-spinner" style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(15,36,16,0.82);align-items:center;justify-content:center;flex-direction:column;gap:16px;">
  <div style="width:52px;height:52px;border:4px solid rgba(255,255,255,0.2);border-top-color:#4ade80;border-radius:50%;animation:spin-logout .75s linear infinite;"></div>
  <div style="color:rgba(255,255,255,0.75);font-size:.82rem;font-weight:600;letter-spacing:.4px;">Logging out…</div>
  <style>@keyframes spin-logout{to{transform:rotate(360deg)}}</style>
</div>

<script nonce="<?= csp_nonce() ?>">
(function(){
  var sk = document.getElementById('page-skeleton');
  var sp = document.getElementById('logout-spinner');

  function showSkeleton(){
    if(window.innerWidth<=767){ sk.style.left='0'; sk.style.top='52px'; sk.style.bottom='58px'; }
    else { sk.style.left='220px'; sk.style.top='0'; sk.style.bottom='0'; }
    sk.style.display='block';
  }
  function hideSkeleton(){ sk.style.display='none'; }

  document.addEventListener('click', function(e){
    var a = e.target.closest('a[href]');
    if(!a) return;
    var href = a.getAttribute('href');
    if(!href||href==='#'||href.startsWith('#')||href.startsWith('javascript')||a.target==='_blank'||e.ctrlKey||e.metaKey||a.hasAttribute('data-toggle')||a.hasAttribute('data-bs-toggle')) return;
    e.preventDefault();
    if(a.hasAttribute('data-logout')){ sp.style.display='flex'; setTimeout(function(){ window.location.href=href; },400); }
    else { showSkeleton(); setTimeout(function(){ window.location.href=href; },500); }
  });

  document.addEventListener('submit', function(e){
    if(e.target&&e.target.tagName==='FORM'){ e.preventDefault(); showSkeleton(); var form=e.target; setTimeout(function(){ form.submit(); },500); }
  });

  window.addEventListener('pageshow', function(e){ if(e.persisted){ hideSkeleton(); sp.style.display='none'; } });
  window.addEventListener('load', hideSkeleton);
})();
</script>
