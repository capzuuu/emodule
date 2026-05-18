<!DOCTYPE html>
<html lang="en">
<?php student_view_layout(['header', 'style']); ?>

<body>
<div id="wrapper">
  <?php student_view_layout(['sidebar']); ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?php student_view_layout(['navbar']); ?>
      <div class="container-fluid px-4 py-4">

        <!-- Welcome Banner -->
        <div class="d-flex align-items-center justify-content-between mb-4 p-3 rounded"
             style="background:linear-gradient(135deg,#258517,#396619);color:#fff;box-shadow:0 4px 20px rgba(37,133,23,0.25);">
          <div style="min-width:0;">
            <div style="font-size:.65rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;opacity:.7;margin-bottom:4px;">Student Panel</div>
            <h5 class="m-0 font-weight-bold" style="font-size:clamp(.95rem,3vw,1.15rem);">
              <?php
                $hour = (int)date('H');
                $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                echo $greeting . ', ' . htmlspecialchars(explode(' ', $userName)[0]) . '!';
              ?>
            </h5>
            <div style="font-size:.8rem;opacity:.85;">Keep learning — you're doing great!</div>
          </div>
          <i class="bi bi-mortarboard-fill d-none d-sm-block" style="font-size:2.5rem;opacity:.2;flex-shrink:0;"></i>
        </div>

        <!-- Stat Cards -->
        <div class="row mb-4">
          <div class="col-md-3 col-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#258517;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Total</div>
                  <div class="stat-number" style="color:#258517;"><?= $stats['total'] ?></div>
                  <div class="stat-sub">Modules</div>
                </div>
                <div class="stat-icon" style="background:#e8f5e9;color:#258517;"><i class="bi bi-journal-text"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#1cc88a;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Completed</div>
                  <div class="stat-number" style="color:#1cc88a;"><?= $stats['completed'] ?></div>
                  <div class="stat-sub">Finished</div>
                </div>
                <div class="stat-icon" style="background:#d1fae5;color:#059669;"><i class="bi bi-check-circle-fill"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#F9A825;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Available</div>
                  <div class="stat-number" style="color:#e65100;"><?= $stats['available'] ?></div>
                  <div class="stat-sub">In progress</div>
                </div>
                <div class="stat-icon" style="background:#fff8e1;color:#e65100;"><i class="bi bi-play-circle-fill"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#4e73df;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Progress</div>
                  <div class="stat-number" style="color:#4e73df;"><?= $stats['pct'] ?>%</div>
                  <div class="stat-sub">Overall</div>
                </div>
                <div class="stat-icon" style="background:#eef2ff;color:#4e73df;"><i class="bi bi-graph-up"></i></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Overall Progress Bar -->
        <div class="form-card mb-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="font-weight-bold" style="font-size:.875rem;">Overall Completion</span>
            <span class="font-weight-bold" style="color:var(--primary);"><?= $stats['completed'] ?> / <?= $stats['total'] ?> modules</span>
          </div>
          <div class="progress" style="height:10px;border-radius:99px;">
            <div class="progress-bar" style="width:<?= $stats['pct'] ?>%;border-radius:99px;"></div>
          </div>
          <div class="text-muted mt-1" style="font-size:.72rem;"><?= $stats['pct'] ?>% complete</div>
        </div>

        <!-- Module List -->
        <div class="card dashboard-card shadow-sm">
          <div class="card-header-clean d-flex align-items-center justify-content-between">
            <span><i class="bi bi-journal-text mr-2 text-success"></i>My Modules</span>
            <a href="<?= baseurl('/student/modules') ?>" class="small text-success" style="font-size:12px;">View All →</a>
          </div>
          <div class="card-body p-0">

            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th class="pl-4">Unit</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Score</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($modules as $m): ?>
                    <tr>
                      <td class="pl-4"><span class="badge badge-secondary">Unit <?= $m['unit_number'] ?></span></td>
                      <td class="font-weight-bold"><?= htmlspecialchars($m['title']) ?></td>
                      <td>
                        <?php if ($m['status'] === 'completed'): ?>
                          <span class="badge status-completed"><i class="bi bi-check-circle mr-1"></i>Completed</span>
                        <?php elseif ($m['status'] === 'available'): ?>
                          <span class="badge status-available"><i class="bi bi-play-circle mr-1"></i>Available</span>
                        <?php else: ?>
                          <span class="badge status-locked"><i class="bi bi-lock mr-1"></i>Locked</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if ($m['status'] === 'completed'): ?>
                          <span class="font-weight-bold" style="color:#059669;"><?= $m['quiz_score'] ?>%</span>
                        <?php else: ?>
                          <span class="text-muted">—</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if ($m['status'] !== 'locked'): ?>
                          <a href="<?= baseurl('/student/modules/' . $m['id']) ?>" class="btn btn-sm btn-success">
                            <?= $m['status'] === 'completed' ? 'Review' : 'Start' ?>
                          </a>
                        <?php else: ?>
                          <button class="btn btn-sm btn-secondary" disabled><i class="bi bi-lock"></i></button>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <?php if (empty($modules)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No modules available yet.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <!-- Mobile Cards -->
            <div class="d-md-none p-3">
              <?php if (empty($modules)): ?>
                <p class="text-center text-muted py-3 mb-0">No modules available yet.</p>
              <?php endif; ?>
              <?php foreach ($modules as $m): ?>
              <div style="border:1px solid var(--divider);border-radius:10px;padding:12px 14px;margin-bottom:10px;background:#fff;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <span class="badge badge-secondary" style="font-size:.68rem;">Unit <?= $m['unit_number'] ?></span>
                  <?php if ($m['status'] === 'completed'): ?>
                    <span class="badge status-completed" style="font-size:.68rem;"><i class="bi bi-check-circle mr-1"></i>Completed</span>
                  <?php elseif ($m['status'] === 'available'): ?>
                    <span class="badge status-available" style="font-size:.68rem;"><i class="bi bi-play-circle mr-1"></i>Available</span>
                  <?php else: ?>
                    <span class="badge status-locked" style="font-size:.68rem;"><i class="bi bi-lock mr-1"></i>Locked</span>
                  <?php endif; ?>
                </div>
                <div class="font-weight-bold mb-2" style="font-size:.875rem;"><?= htmlspecialchars($m['title']) ?></div>
                <div class="d-flex align-items-center justify-content-between">
                  <span style="font-size:.78rem;color:#5C6359;">
                    Score:&nbsp;
                    <?php if ($m['status'] === 'completed'): ?>
                      <strong style="color:#059669;"><?= $m['quiz_score'] ?>%</strong>
                    <?php else: ?>
                      <span class="text-muted">—</span>
                    <?php endif; ?>
                  </span>
                  <?php if ($m['status'] !== 'locked'): ?>
                    <a href="<?= baseurl('/student/modules/' . $m['id']) ?>" class="btn btn-sm btn-success" style="font-size:.75rem;padding:4px 12px;">
                      <?= $m['status'] === 'completed' ? 'Review' : 'Start' ?>
                    </a>
                  <?php else: ?>
                    <button class="btn btn-sm btn-secondary" disabled style="font-size:.75rem;padding:4px 10px;"><i class="bi bi-lock"></i></button>
                  <?php endif; ?>
                </div>
              </div>
              <?php endforeach; ?>
            </div>

          </div>
        </div>

      </div>
    </div>
    <?php student_view_layout(['footer']); ?>
  </div>
</div>
<?php student_view_layout(['script']); ?>
</body>
</html>
