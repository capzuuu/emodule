<!DOCTYPE html>
<html lang="en">
<?php student_view_layout(['header', 'style']); ?>

<body>
<div id="wrapper">
  <?php student_view_layout(['sidebar']); ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?php student_view_layout(['navbar']); ?>
      <div class="container-fluid py-4">

        <div class="mb-4">
          <h5 class="m-0 mb-1 font-weight-bold"><?= htmlspecialchars($pageTitle) ?></h5>
          <div class="text-muted" style="font-size:13px;">Track your learning journey.</div>
        </div>

        <!-- Summary Cards -->
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
                  <div class="stat-label">Remaining</div>
                  <div class="stat-number" style="color:#e65100;"><?= $stats['total'] - $stats['completed'] ?></div>
                  <div class="stat-sub">To finish</div>
                </div>
                <div class="stat-icon" style="background:#fff8e1;color:#e65100;"><i class="bi bi-hourglass-split"></i></div>
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

        <!-- Progress Table -->
        <div class="form-card">
          <h6 class="font-weight-bold mb-3" style="color:var(--primary);"><i class="bi bi-bar-chart-fill mr-2"></i>Module Progress</h6>
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Unit</th>
                  <th>Title</th>
                  <th>Status</th>
                  <th>Pre-Test Score</th>
                  <th>Post-Test Score</th>
                  <th>Attempts</th>
                  <th>Completed</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($modules as $m): ?>
                  <tr>
                    <td><span class="badge badge-secondary">Unit <?= $m['unit_number'] ?></span></td>
                    <td class="font-weight-bold"><?= htmlspecialchars($m['title']) ?></td>
                    <td>
                      <?php if ($m['status'] === 'completed'): ?>
                        <span class="badge status-completed"><i class="bi bi-check-circle-fill mr-1"></i>Completed</span>
                      <?php elseif ($m['status'] === 'available'): ?>
                        <span class="badge status-available"><i class="bi bi-play-circle-fill mr-1"></i>Available</span>
                      <?php else: ?>
                        <span class="badge status-locked"><i class="bi bi-lock-fill mr-1"></i>Locked</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($m['pre_correct'] !== null): ?>
                        <span class="font-weight-bold" style="color:#4e73df;"><?= (int)$m['pre_correct'] ?>/<?= (int)$m['pre_count'] ?></span>
                      <?php else: ?>
                        <span class="text-muted">—</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($m['post_correct'] !== null): ?>
                        <span class="font-weight-bold" style="color:<?= $m['status'] === 'completed' ? '#059669' : '#e65100' ?>"><?= (int)$m['post_correct'] ?>/<?= (int)$m['post_count'] ?></span>
                      <?php else: ?>
                        <span class="text-muted">—</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($m['quiz_attempts'] > 0): ?>
                        <span class="font-weight-bold" style="color:#f6c23e;"><?= $m['quiz_attempts'] ?></span>
                      <?php else: ?>
                        <span class="text-muted">—</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-muted" style="font-size:.78rem;">
                      <?= $m['completed_date'] ? date('M d, Y', strtotime($m['completed_date'])) : '—' ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($modules)): ?>
                  <tr><td colspan="7" class="text-center text-muted py-4">No modules available yet.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
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
