<!DOCTYPE html>
<html lang="en">
<?php student_view_layout(['header', 'style']); ?>
<style nonce="<?= csp_nonce() ?>">
  /* ── HERO ── */
  .modules-hero {
    background: linear-gradient(135deg, #0f2410 0%, #1a4a1f 50%, #258517 100%);
    border-radius: 20px; padding: 32px 36px; color: #fff;
    margin-bottom: 28px; position: relative; overflow: hidden;
  }
  .modules-hero::before {
    content: ''; position: absolute; top: -40px; right: -40px;
    width: 200px; height: 200px; border-radius: 50%;
    background: rgba(255,255,255,0.04);
  }
  .modules-hero::after {
    content: ''; position: absolute; bottom: -60px; right: 80px;
    width: 140px; height: 140px; border-radius: 50%;
    background: rgba(255,255,255,0.03);
  }
  .hero-stats { display: flex; gap: 32px; margin-top: 20px; flex-wrap: wrap; }
  .hero-stat { text-align: center; }
  .hero-stat-num   { font-size: 1.8rem; font-weight: 800; line-height: 1; }
  .hero-stat-label { font-size: .68rem; opacity: .65; text-transform: uppercase; letter-spacing: .6px; margin-top: 2px; }

  /* ── TOOLBAR ── */
  .modules-toolbar {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 20px; flex-wrap: wrap;
  }
  .search-wrap {
    position: relative; flex: 1; min-width: 200px; max-width: 340px;
  }
  .search-wrap i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #9ca3af; font-size: .85rem; pointer-events: none;
  }
  .search-input {
    width: 100%; padding: 8px 12px 8px 34px;
    border: 1.5px solid #e0e4db; border-radius: 10px;
    font-size: .82rem; color: #1A1C19; background: #fff;
    transition: .15s; outline: none;
  }
  .search-input:focus { border-color: #258517; box-shadow: 0 0 0 3px rgba(37,133,23,.1); }
  .search-clear {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: #9ca3af; cursor: pointer;
    font-size: .8rem; padding: 2px; display: none;
  }
  .filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; }
  .filter-tab {
    padding: 6px 16px; border-radius: 20px; font-size: .78rem; font-weight: 600;
    border: 1.5px solid #e0e4db; background: #fff; color: #5C6359;
    cursor: pointer; transition: .15s;
  }
  .filter-tab.active { background: #258517; color: #fff; border-color: #258517; }
  .filter-tab:hover:not(.active) { border-color: #258517; color: #258517; }

  /* ── MODULE CARD ── */
  .mod-card {
    background: #fff; border-radius: 16px; border: 1px solid #e8f0e9;
    overflow: hidden; transition: .22s; position: relative;
    height: 100%; display: flex; flex-direction: column;
    box-shadow: 0 2px 12px rgba(26,28,25,.06);
  }
  .mod-card:hover:not(.mod-locked) {
    transform: translateY(-3px);
    box-shadow: 0 10px 32px rgba(37,133,23,.13);
    border-color: #258517;
  }
  .mod-card.mod-locked { opacity: .55; }

  /* "Next up" highlight */
  .mod-card.mod-next-up {
    border-color: #258517;
    box-shadow: 0 0 0 3px rgba(37,133,23,.18), 0 4px 20px rgba(37,133,23,.12);
  }
  .next-up-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .62rem; font-weight: 800; letter-spacing: .5px;
    text-transform: uppercase; padding: 3px 10px; border-radius: 20px;
    background: linear-gradient(135deg,#258517,#4F9516); color: #fff;
    animation: pulse-badge 2s ease-in-out infinite;
  }
  @keyframes pulse-badge {
    0%, 100% { box-shadow: 0 0 0 0 rgba(37,133,23,.5); }
    50%       { box-shadow: 0 0 0 6px rgba(37,133,23,0); }
  }

  /* Left accent bar */
  .mod-card-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    border-radius: 16px 0 0 16px;
  }
  .accent-completed { background: linear-gradient(180deg,#1cc88a,#059669); }
  .accent-available { background: linear-gradient(180deg,#258517,#4F9516); }
  .accent-locked    { background: #d1d5db; }

  .mod-card-body { padding: 18px 18px 0 22px; flex: 1; display: flex; flex-direction: column; }

  /* Icon */
  .mod-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
  }
  .mod-icon-completed { background: #d1fae5; color: #059669; }
  .mod-icon-available { background: #e8f5e9; color: #258517; }
  .mod-icon-locked    { background: #f3f4f6; color: #9ca3af; }

  .mod-card-top { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 10px; }
  .mod-card-top-text { flex: 1; min-width: 0; }

  .mod-unit-badge {
    font-size: .6rem; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; padding: 2px 8px; border-radius: 20px;
    background: #f0f7ee; color: #258517; display: inline-block; margin-bottom: 4px;
  }
  .mod-title { font-size: .9rem; font-weight: 800; color: #1A1C19; line-height: 1.3; margin: 0; }

  .mod-status-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .65rem; font-weight: 700; padding: 3px 9px;
    border-radius: 20px; white-space: nowrap; flex-shrink: 0;
  }
  .mod-status-badge.completed { background: #d1fae5; color: #059669; }
  .mod-status-badge.available { background: #e8f5e9; color: #258517; }
  .mod-status-badge.locked    { background: #f3f4f6; color: #9ca3af; }

  .mod-outcome { font-size: .77rem; color: #5C6359; line-height: 1.55; flex: 1; margin-bottom: 10px; }

  /* ── STEP PROGRESS BAR ── */
  .mod-steps {
    display: flex; align-items: center; gap: 0;
    margin-bottom: 12px;
  }
  .mod-step {
    flex: 1; height: 4px; border-radius: 99px;
    background: #e8f0e9; position: relative; transition: .3s;
  }
  .mod-step.done  { background: #059669; }
  .mod-step.active { background: linear-gradient(90deg,#258517,#4F9516); }
  .mod-step-connector { width: 6px; height: 2px; background: #e8f0e9; flex-shrink: 0; }
  .mod-step-connector.done { background: #059669; }
  .mod-steps-labels {
    display: flex; justify-content: space-between;
    font-size: .58rem; color: #9ca3af; font-weight: 600;
    text-transform: uppercase; letter-spacing: .4px;
    margin-bottom: 12px; margin-top: 3px;
  }
  .mod-steps-labels span.done  { color: #059669; }
  .mod-steps-labels span.active { color: #258517; }

  /* Divider */
  .mod-divider { height: 1px; background: #f0f4f0; margin-bottom: 12px; }

  /* Meta row */
  .mod-meta {
    display: flex; align-items: center; justify-content: space-between;
    font-size: .72rem; color: #5C6359; margin-bottom: 14px;
  }
  .mod-meta-item { display: flex; align-items: center; gap: 4px; }

  /* Score badge — colour-coded */
  .mod-score {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .72rem; font-weight: 800; padding: 3px 9px; border-radius: 20px;
  }
  .mod-score.score-high   { background: #d1fae5; color: #059669; }
  .mod-score.score-mid    { background: #fef3c7; color: #d97706; }
  .mod-score.score-low    { background: #fef2f2; color: #dc2626; }

  /* Read time */
  .mod-read-time {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .68rem; color: #9ca3af; font-weight: 600;
  }

  /* Completion date */
  .mod-completed-date {
    font-size: .68rem; color: #9ca3af; font-weight: 600;
    display: flex; align-items: center; gap: 4px; margin-bottom: 10px;
  }

  /* CTA button */
  .mod-btn {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    padding: 9px 16px; border-radius: 10px;
    font-size: .78rem; font-weight: 700; text-decoration: none;
    transition: .18s; border: none; cursor: pointer; width: 100%;
    margin: 0 -18px 0 -22px; width: calc(100% + 40px);
    border-radius: 0 0 16px 16px; padding: 11px 16px;
  }
  .mod-btn-continue { background: linear-gradient(135deg,#258517,#4F9516); color: #fff; box-shadow: 0 3px 10px rgba(37,133,23,.25); }
  .mod-btn-continue:hover { box-shadow: 0 5px 16px rgba(37,133,23,.35); color: #fff; }
  .mod-btn-start  { background: linear-gradient(135deg,#258517,#4F9516); color: #fff; box-shadow: 0 3px 10px rgba(37,133,23,.25); }
  .mod-btn-start:hover  { box-shadow: 0 5px 16px rgba(37,133,23,.35); color: #fff; }
  .mod-btn-review { background: #f0fdf4; color: #059669; border-top: 1px solid #a7f3d0; }
  .mod-btn-review:hover { background: #d1fae5; color: #059669; }
  .mod-btn-locked { background: #f9fafb; color: #9ca3af; cursor: not-allowed; border-top: 1px solid #e5e7eb; }

  /* ── EMPTY STATE ── */
  .empty-state {
    text-align: center; padding: 48px 24px;
    background: #fff; border-radius: 16px; border: 1.5px dashed #e0e4db;
  }
  .empty-state i { font-size: 2.8rem; color: #d1d5db; display: block; margin-bottom: 12px; }
  .empty-state p { font-size: .85rem; color: #9ca3af; margin: 0; }

  /* ── NO SEARCH RESULTS ── */
  #noSearchResults { display: none; }
</style>

<body>
<div id="wrapper">
  <?php student_view_layout(['sidebar']); ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?php student_view_layout(['navbar']); ?>
      <div class="container-fluid py-4">

        <?php
          $total     = count($modules);
          $completed = count(array_filter($modules, fn($m) => $m['status'] === 'completed'));
          $available = count(array_filter($modules, fn($m) => $m['status'] === 'available'));
          $locked    = count(array_filter($modules, fn($m) => $m['status'] === 'locked'));
          $pct       = $total > 0 ? round($completed / $total * 100) : 0;

          // Find the single "next up" module (first available)
          $nextUpId = null;
          foreach ($modules as $m) {
              if ($m['status'] === 'available') { $nextUpId = $m['id']; break; }
          }

          // Read-time helper: ~200 words/min, min 1 min
          function estimateReadTime(array $m): int {
              $words = str_word_count(strip_tags($m['content'] ?? ''));
              // Add 2 min if there's a PDF
              $pdfBonus = !empty($m['file_path']) ? 2 : 0;
              return max(1, (int)ceil($words / 200) + $pdfBonus);
          }
        ?>

        <!-- Hero Banner -->
        <div class="modules-hero">
          <div style="position:relative;z-index:1;">
            <div style="font-size:.65rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;opacity:.6;margin-bottom:6px;">Learning Path</div>
            <h4 class="m-0 font-weight-bold">My Modules</h4>
            <div style="font-size:.82rem;opacity:.75;margin-top:4px;">Complete modules in order to unlock the next one.</div>

            <div style="margin-top:18px;">
              <div style="display:flex;justify-content:space-between;font-size:.72rem;opacity:.8;margin-bottom:6px;">
                <span>Overall Progress</span>
                <span><?= $completed ?>/<?= $total ?> completed</span>
              </div>
              <div style="height:8px;border-radius:99px;background:rgba(255,255,255,0.15);overflow:hidden;">
                <div style="height:100%;width:<?= $pct ?>%;background:linear-gradient(90deg,#4ade80,#86efac);border-radius:99px;transition:width .5s;"></div>
              </div>
            </div>

            <div class="hero-stats">
              <div class="hero-stat">
                <div class="hero-stat-num"><?= $total ?></div>
                <div class="hero-stat-label">Total</div>
              </div>
              <div class="hero-stat">
                <div class="hero-stat-num" style="color:#4ade80;"><?= $completed ?></div>
                <div class="hero-stat-label">Completed</div>
              </div>
              <div class="hero-stat">
                <div class="hero-stat-num" style="color:#fde68a;"><?= $available ?></div>
                <div class="hero-stat-label">Available</div>
              </div>
              <div class="hero-stat">
                <div class="hero-stat-num" style="color:rgba(255,255,255,.5);"><?= $locked ?></div>
                <div class="hero-stat-label">Locked</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Toolbar: search + filter tabs -->
        <div class="modules-toolbar">
          <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="search-input" id="moduleSearch" placeholder="Search modules…" autocomplete="off">
            <button class="search-clear" id="searchClear" title="Clear"><i class="bi bi-x"></i></button>
          </div>
          <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">All <span style="opacity:.6;">(<?= $total ?>)</span></button>
            <button class="filter-tab" data-filter="available">Available <span style="opacity:.6;">(<?= $available ?>)</span></button>
            <button class="filter-tab" data-filter="completed">Completed <span style="opacity:.6;">(<?= $completed ?>)</span></button>
            <button class="filter-tab" data-filter="locked">Locked <span style="opacity:.6;">(<?= $locked ?>)</span></button>
          </div>
        </div>

        <!-- No search results message -->
        <div id="noSearchResults" class="empty-state mb-4">
          <i class="bi bi-search"></i>
          <p>No modules match your search.</p>
        </div>

        <!-- Module Cards -->
        <div class="row" id="modulesGrid">
          <?php foreach ($modules as $i => $m):
            $score     = (int)$m['quiz_score'];
            $isNextUp  = ($m['id'] === $nextUpId);
            $readTime  = estimateReadTime($m);
            $hasPre    = (int)$m['pre_count']  > 0;
            $hasPost   = (int)$m['post_count'] > 0;
            $preDone   = !empty($m['pre_done']);
            $postDone  = $m['status'] === 'completed';
            $lessonDone = $postDone || $preDone; // lesson is "done" once pre-test submitted

            // Score colour class
            if ($score >= 75)      $scoreClass = 'score-high';
            elseif ($score >= 50)  $scoreClass = 'score-mid';
            else                   $scoreClass = 'score-low';

            // Completion date
            $completedDate = '';
            if (!empty($m['completed_date'])) {
                $completedDate = date('M j, Y', strtotime($m['completed_date']));
            }

            // Step states: pre → lesson → post
            // Step is "done" if completed, "active" if it's the current step, else default
            $stepPre    = $postDone ? 'done' : ($preDone    ? 'done'   : ($m['status'] === 'available' && $hasPre  ? 'active' : ''));
            $stepLesson = $postDone ? 'done' : ($lessonDone ? 'active' : '');
            $stepPost   = $postDone ? 'done' : ($lessonDone && $hasPost ? 'active' : '');
          ?>
          <div class="col-xl-4 col-md-6 mb-4 module-item" data-status="<?= $m['status'] ?>" data-title="<?= htmlspecialchars(strtolower($m['title'])) ?>">
            <div class="mod-card <?= $m['status'] === 'locked' ? 'mod-locked' : '' ?> <?= $isNextUp ? 'mod-next-up' : '' ?>">

              <div class="mod-card-accent accent-<?= $m['status'] ?>"></div>

              <div class="mod-card-body">

                <!-- Top row -->
                <div class="mod-card-top">
                  <div class="mod-icon mod-icon-<?= $m['status'] ?>">
                    <?php if ($m['status'] === 'completed'): ?>
                      <i class="bi bi-check-circle-fill"></i>
                    <?php elseif ($m['status'] === 'available'): ?>
                      <i class="bi bi-journal-text"></i>
                    <?php else: ?>
                      <i class="bi bi-lock-fill"></i>
                    <?php endif; ?>
                  </div>
                  <div class="mod-card-top-text">
                    <span class="mod-unit-badge">Unit <?= $m['unit_number'] ?></span>
                    <div class="mod-title"><?= htmlspecialchars($m['title']) ?></div>
                  </div>
                  <?php if ($isNextUp): ?>
                    <span class="next-up-badge"><i class="bi bi-arrow-right-circle-fill"></i> Next Up</span>
                  <?php else: ?>
                    <span class="mod-status-badge <?= $m['status'] ?>">
                      <?php if ($m['status'] === 'completed'): ?>
                        <i class="bi bi-check-circle-fill"></i> Done
                      <?php elseif ($m['status'] === 'available'): ?>
                        <i class="bi bi-play-circle-fill"></i> Open
                      <?php else: ?>
                        <i class="bi bi-lock-fill"></i> Locked
                      <?php endif; ?>
                    </span>
                  <?php endif; ?>
                </div>

                <!-- Outcome -->
                <div class="mod-outcome"><?= htmlspecialchars(mb_strimwidth($m['outcome'] ?? '', 0, 90, '…')) ?></div>

                <!-- Step progress bar (only for available/completed) -->
                <?php if ($m['status'] !== 'locked'): ?>
                  <div class="mod-steps">
                    <div class="mod-step <?= $stepPre ?>"></div>
                    <div class="mod-step-connector <?= $stepPre === 'done' ? 'done' : '' ?>"></div>
                    <div class="mod-step <?= $stepLesson ?>"></div>
                    <div class="mod-step-connector <?= $stepLesson === 'done' ? 'done' : '' ?>"></div>
                    <div class="mod-step <?= $stepPost ?>"></div>
                  </div>
                  <div class="mod-steps-labels">
                    <span class="<?= $stepPre ?>">Pre-Test</span>
                    <span class="<?= $stepLesson ?>">Lesson</span>
                    <span class="<?= $stepPost ?>">Post-Test</span>
                  </div>
                <?php endif; ?>

                <div class="mod-divider"></div>

                <!-- Meta row -->
                <div class="mod-meta">
                  <div class="d-flex" style="gap:12px;">
                    <div class="mod-meta-item">
                      <i class="bi bi-clipboard-check" style="color:#258517;"></i>
                      <span><?= $m['pre_count'] ?> Pre</span>
                    </div>
                    <div class="mod-meta-item">
                      <i class="bi bi-clipboard-data" style="color:#4e73df;"></i>
                      <span><?= $m['post_count'] ?> Post</span>
                    </div>
                    <div class="mod-read-time">
                      <i class="bi bi-clock"></i>
                      <span>~<?= $readTime ?> min</span>
                    </div>
                  </div>
                  <?php if ($m['status'] === 'completed'): ?>
                    <span class="mod-score <?= $scoreClass ?>">
                      <i class="bi bi-star-fill"></i><?= $score ?>%
                    </span>
                  <?php else: ?>
                    <span style="font-size:.7rem;color:#9ca3af;">#<?= $i + 1 ?></span>
                  <?php endif; ?>
                </div>

                <!-- Completion date -->
                <?php if ($completedDate): ?>
                  <div class="mod-completed-date">
                    <i class="bi bi-calendar-check" style="color:#059669;"></i>
                    Completed <?= $completedDate ?>
                  </div>
                <?php endif; ?>

              </div><!-- /.mod-card-body -->

              <!-- CTA button flush to card bottom -->
              <?php if ($m['status'] === 'available' && $isNextUp): ?>
                <a href="<?= baseurl('/student/modules/' . $m['id']) ?>" class="mod-btn mod-btn-continue">
                  <i class="bi bi-play-fill"></i> Continue Learning
                </a>
              <?php elseif ($m['status'] === 'available'): ?>
                <a href="<?= baseurl('/student/modules/' . $m['id']) ?>" class="mod-btn mod-btn-start">
                  <i class="bi bi-play-fill"></i> Start Module
                </a>
              <?php elseif ($m['status'] === 'completed'): ?>
                <a href="<?= baseurl('/student/modules/' . $m['id']) ?>" class="mod-btn mod-btn-review">
                  <i class="bi bi-eye"></i> Review
                </a>
              <?php else: ?>
                <button class="mod-btn mod-btn-locked" disabled>
                  <i class="bi bi-lock"></i> Complete previous to unlock
                </button>
              <?php endif; ?>

            </div><!-- /.mod-card -->
          </div>
          <?php endforeach; ?>

          <?php if (empty($modules)): ?>
            <div class="col-12">
              <div class="empty-state">
                <i class="bi bi-journal-x"></i>
                <p>No modules available yet. Check back soon.</p>
              </div>
            </div>
          <?php endif; ?>
        </div><!-- /#modulesGrid -->

        <!-- Per-filter empty states (shown by JS) -->
        <div id="emptyAvailable"  class="empty-state mb-4" style="display:none;">
          <i class="bi bi-hourglass"></i>
          <p>No available modules right now — complete the current one to unlock the next.</p>
        </div>
        <div id="emptyCompleted"  class="empty-state mb-4" style="display:none;">
          <i class="bi bi-trophy"></i>
          <p>No completed modules yet — keep going, you've got this!</p>
        </div>
        <div id="emptyLocked"     class="empty-state mb-4" style="display:none;">
          <i class="bi bi-unlock"></i>
          <p>All modules are unlocked — nothing locked here.</p>
        </div>

      </div>
    </div>
    <?php student_view_layout(['footer']); ?>
  </div>
</div>

<?php student_view_layout(['script']); ?>
<script nonce="<?= csp_nonce() ?>">
$(document).ready(function () {

  var $items      = $('.module-item');
  var $grid       = $('#modulesGrid');
  var $search     = $('#moduleSearch');
  var $clear      = $('#searchClear');
  var $noSearch   = $('#noSearchResults');
  var activeFilter = 'all';

  // ── Filter tabs ──
  $('.filter-tab').on('click', function () {
    $('.filter-tab').removeClass('active');
    $(this).addClass('active');
    activeFilter = $(this).data('filter');
    applyFilters();
  });

  // ── Search ──
  $search.on('input', function () {
    $clear.toggle($(this).val().length > 0);
    applyFilters();
  });

  $clear.on('click', function () {
    $search.val('').trigger('input').focus();
  });

  function applyFilters() {
    var query  = $search.val().toLowerCase().trim();
    var filter = activeFilter;
    var visible = 0;

    $items.each(function () {
      var $el     = $(this);
      var status  = $el.data('status');
      var title   = $el.data('title') || '';
      var matchF  = (filter === 'all') || (status === filter);
      var matchQ  = !query || title.indexOf(query) !== -1;

      if (matchF && matchQ) {
        $el.show();
        visible++;
      } else {
        $el.hide();
      }
    });

    // No search results message
    $noSearch.toggle(query.length > 0 && visible === 0);

    // Per-filter empty states (only when no search query)
    $('#emptyAvailable, #emptyCompleted, #emptyLocked').hide();
    if (!query && visible === 0 && filter !== 'all') {
      $('#empty' + filter.charAt(0).toUpperCase() + filter.slice(1)).show();
    }
  }

});
</script>
</body>
</html>
