<!DOCTYPE html>
<html lang="en">
<?php teacher_view_layout(['header', 'style']); ?>

<body>
<div id="wrapper">
  <?php teacher_view_layout(['sidebar']); ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?php teacher_view_layout(['navbar']); ?>
      <div class="container-fluid px-4 py-4">

        <!-- Welcome Banner -->
        <div class="d-flex align-items-center justify-content-between mb-4 p-3 rounded"
             style="background:linear-gradient(135deg,#258517,#396619);color:#fff;box-shadow:0 4px 20px rgba(37,133,23,0.25);">
          <div>
            <div style="font-size:.65rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;opacity:.7;margin-bottom:4px;">Teacher Panel</div>
            <h5 class="m-0 font-weight-bold">
              <?php
                $hour = (int)date('H');
                $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                echo $greeting . ', ' . htmlspecialchars(explode(' ', $userName)[0]) . '!';
              ?>
            </h5>
            <div style="font-size:.8rem;opacity:.85;">Here's an overview of your class and modules.</div>
          </div>
          <i class="bi bi-mortarboard-fill" style="font-size:2.5rem;opacity:.2;"></i>
        </div>

        <!-- Stat Cards -->
        <div class="row mb-4">
          <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#258517;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">My Modules</div>
                  <div class="stat-number" style="color:#258517;"><?= count($modules) ?></div>
                  <div class="stat-sub">Learning units</div>
                </div>
                <div class="stat-icon" style="background:#e8f5e9;color:#258517;"><i class="bi bi-journal-text"></i></div>
              </div>
              <a href="<?= baseurl('/teacher/modules') ?>" class="stretched-link"></a>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#F9A825;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">My Students</div>
                  <div class="stat-number" style="color:#e65100;"><?= count($students) ?></div>
                  <div class="stat-sub">Enrolled learners</div>
                </div>
                <div class="stat-icon" style="background:#fff8e1;color:#e65100;"><i class="bi bi-people-fill"></i></div>
              </div>
              <a href="<?= baseurl('/teacher/students') ?>" class="stretched-link"></a>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-3">
            <?php
              $withQuiz = 0;
              foreach ($modules as $m) { if (!empty($m['quiz'])) $withQuiz++; }
            ?>
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#4e73df;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Tests</div>
                  <div class="stat-number" style="color:#4e73df;"><?= $withQuiz ?></div>
                  <div class="stat-sub">Modules with quiz</div>
                </div>
                <div class="stat-icon" style="background:#eef2ff;color:#4e73df;"><i class="bi bi-patch-question"></i></div>
              </div>
              <a href="<?= baseurl('/teacher/tests') ?>" class="stretched-link"></a>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-3">
            <?php
              $completions = 0;
              foreach ($students as $s) $completions += (int)($s['completed_modules'] ?? 0);
            ?>
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#1cc88a;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Completions</div>
                  <div class="stat-number" style="color:#1cc88a;"><?= $completions ?></div>
                  <div class="stat-sub">Modules completed</div>
                </div>
                <div class="stat-icon" style="background:#d1fae5;color:#059669;"><i class="bi bi-check-circle-fill"></i></div>
              </div>
              <a href="<?= baseurl('/teacher/progress') ?>" class="stretched-link"></a>
            </div>
          </div>
        </div>

        <!-- Main Row -->
        <div class="row">

          <!-- Recent Modules -->
          <div class="col-xl-8 mb-4">
            <div class="card dashboard-card shadow-sm h-100">
              <div class="card-header-clean d-flex align-items-center justify-content-between">
                <span><i class="bi bi-journal-text mr-2 text-success"></i>My Modules</span>
                <a href="<?= baseurl('/teacher/modules') ?>" class="small text-success" style="font-size:12px;">View All →</a>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="ps-4">Unit</th>
                        <th>Title</th>
                        <th>Outcome</th>
                        <th>Quiz</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($modules)): ?>
                        <?php foreach ($modules as $m): ?>
                          <tr>
                            <td class="ps-4"><span class="badge badge-secondary px-2">Unit <?= $m['unit_number'] ?></span></td>
                            <td class="font-weight-bold"><?= htmlspecialchars($m['title']) ?></td>
                            <td class="text-muted"><?= htmlspecialchars(mb_strimwidth($m['outcome'] ?? '', 0, 50, '…')) ?></td>
                            <td>
                              <?php if (!empty($m['quiz'])): ?>
                                <span class="badge badge-success">Has Quiz</span>
                              <?php else: ?>
                                <span class="badge badge-secondary">None</span>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">No modules yet.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-xl-4 mb-4 d-flex flex-column" style="gap:20px;">

            <!-- Quick Actions -->
            <div class="card dashboard-card shadow-sm">
              <div class="card-header-clean">
                <i class="bi bi-lightning-fill mr-2 text-warning"></i>Quick Actions
              </div>
              <div class="card-body px-3 py-3">
                <div class="row g-2">
                  <div class="col-6">
                    <a href="<?= baseurl('/teacher/modules') ?>" class="quick-action-btn flex-column text-center" style="gap:8px;padding:14px 8px;">
                      <div class="quick-action-icon mx-auto" style="background:#e8f5e9;color:#258517;"><i class="bi bi-journal-plus"></i></div>
                      <span style="font-size:11.5px;">Add Module</span>
                    </a>
                  </div>
                  <div class="col-6">
                    <a href="<?= baseurl('/teacher/tests') ?>" class="quick-action-btn flex-column text-center" style="gap:8px;padding:14px 8px;">
                      <div class="quick-action-icon mx-auto" style="background:#eef2ff;color:#4e73df;"><i class="bi bi-patch-question"></i></div>
                      <span style="font-size:11.5px;">Manage Tests</span>
                    </a>
                  </div>
                  <div class="col-6">
                    <a href="<?= baseurl('/teacher/students') ?>" class="quick-action-btn flex-column text-center" style="gap:8px;padding:14px 8px;">
                      <div class="quick-action-icon mx-auto" style="background:#fff8e1;color:#e65100;"><i class="bi bi-person-plus"></i></div>
                      <span style="font-size:11.5px;">Add Student</span>
                    </a>
                  </div>
                  <div class="col-6">
                    <a href="<?= baseurl('/teacher/progress') ?>" class="quick-action-btn flex-column text-center" style="gap:8px;padding:14px 8px;">
                      <div class="quick-action-icon mx-auto" style="background:#d1fae5;color:#059669;"><i class="bi bi-graph-up"></i></div>
                      <span style="font-size:11.5px;">Progress</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Module Status -->
            <div class="card dashboard-card shadow-sm">
              <div class="card-header-clean">
                <i class="bi bi-pie-chart-fill mr-2 text-success"></i>Module Status
              </div>
              <div class="card-body px-4 py-3">
                <?php
                  $total = max(1, count($modules));
                  $rows  = [
                    ['label' => 'With Quiz',    'count' => $withQuiz,          'color' => '#258517'],
                    ['label' => 'Without Quiz', 'count' => $total - $withQuiz, 'color' => '#F9A825'],
                  ];
                  foreach ($rows as $r):
                    $pct = round($r['count'] / $total * 100);
                ?>
                  <div class="doc-status-row">
                    <div class="d-flex align-items-center" style="gap:10px;">
                      <div class="doc-status-dot" style="background:<?= $r['color'] ?>;"></div>
                      <span style="font-size:13px;font-weight:600;color:#4a5568;"><?= $r['label'] ?></span>
                    </div>
                    <div class="d-flex align-items-center" style="gap:10px;">
                      <div style="width:80px;height:6px;background:#f1f3f9;border-radius:3px;overflow:hidden;">
                        <div style="width:<?= $pct ?>%;height:100%;background:<?= $r['color'] ?>;border-radius:3px;"></div>
                      </div>
                      <span class="text-muted" style="font-size:12px;min-width:20px;text-align:right;"><?= $r['count'] ?></span>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

          </div>
        </div>

        <!-- Student Progress Overview -->
        <div class="row">
          <div class="col-12 mb-4">
            <div class="card dashboard-card shadow-sm">
              <div class="card-header-clean d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bar-chart-fill mr-2 text-success"></i>Student Progress Overview</span>
                <a href="<?= baseurl('/teacher/progress') ?>" class="small text-success" style="font-size:12px;">View All →</a>
              </div>
              <div class="card-body px-4 py-3">
                <?php if (!empty($students)): ?>
                  <?php foreach ($students as $s):
                    $totalMods = max(1, count($modules));
                    $done      = (int)($s['completed_modules'] ?? 0);
                    $pct       = round($done / $totalMods * 100);
                    $ini       = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $s['name']), 0, 2))));
                  ?>
                    <div class="d-flex align-items-center g-3 mb-3">
                      <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#258517,#4F9516);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;flex-shrink:0;">
                        <?= htmlspecialchars($ini) ?>
                      </div>
                      <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <span class="font-weight-bold" style="font-size:.875rem;"><?= htmlspecialchars($s['name']) ?></span>
                          <small class="text-muted"><?= $done ?>/<?= count($modules) ?> modules · <?= $pct ?>%</small>
                        </div>
                        <div class="progress" style="height:6px;border-radius:99px;">
                          <div class="progress-bar" style="width:<?= $pct ?>%"></div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-muted text-center py-3 mb-0">No students enrolled yet.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <?php teacher_view_layout(['footer']); ?>
  </div>
</div>
<?php teacher_view_layout(['script']); ?>
</body>
</html>
