<!DOCTYPE html>
<html lang="en">
<?php teacher_view_layout(['header', 'style']); ?>

<body>
<div id="wrapper">
  <?php teacher_view_layout(['sidebar']); ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?php teacher_view_layout(['navbar']); ?>
      <div class="container-fluid py-4">

        <div class="mb-4">
          <h5 class="m-0 mb-1 font-weight-bold"><?= htmlspecialchars($pageTitle) ?></h5>
          <div class="text-muted" style="font-size:13px;">Track how your students are progressing through modules.</div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
          <?php
            $totalStudents   = count($students);
            $totalModules    = count($modules);
            $totalCompletions = 0;
            $fullyDone       = 0;
            foreach ($students as $s) {
              $done = (int)($s['completed_modules'] ?? 0);
              $totalCompletions += $done;
              if ($totalModules > 0 && $done >= $totalModules) $fullyDone++;
            }
            $avgPct = $totalStudents > 0 && $totalModules > 0
              ? round($totalCompletions / ($totalStudents * $totalModules) * 100)
              : 0;
          ?>
          <div class="col-md-4 mb-3">
            <div class="stat-card">
              <div class="card-accent-bar" style="background:#2d7a4f;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Total Students</div>
                  <div class="stat-number" style="color:#2d7a4f;"><?= $totalStudents ?></div>
                  <div class="stat-sub">In your class</div>
                </div>
                <div class="stat-icon" style="background:#e8f5e9;color:#2d7a4f;"><i class="bi bi-people"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="stat-card">
              <div class="card-accent-bar" style="background:#059669;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Fully Completed</div>
                  <div class="stat-number" style="color:#059669;"><?= $fullyDone ?></div>
                  <div class="stat-sub">All modules done</div>
                </div>
                <div class="stat-icon" style="background:#d1fae5;color:#059669;"><i class="bi bi-check-circle-fill"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="stat-card">
              <div class="card-accent-bar" style="background:#d97706;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Avg. Completion</div>
                  <div class="stat-number" style="color:#d97706;"><?= $avgPct ?>%</div>
                  <div class="stat-sub">Across all students</div>
                </div>
                <div class="stat-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-graph-up"></i></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Progress Table -->
        <div class="row">
          <div class="col-12">
            <div class="form-card">
              <h6 class="font-weight-bold mb-3" style="color:var(--primary);"><i class="bi bi-bar-chart-fill mr-2"></i>Student Progress</h6>
              <?php if (!empty($students)): ?>
                <div class="table-responsive">
                  <table id="progressTable" class="table table-hover w-100" style="font-size:13px;">
                    <thead>
                      <tr>
                        <th>Student</th>
                        <th>Grade</th>
                        <th>Section</th>
                        <th>Completed</th>
                        <th>Progress</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($students as $s):
                        $done    = (int)($s['completed_modules'] ?? 0);
                        $pct     = $totalModules > 0 ? round($done / $totalModules * 100) : 0;
                        $color   = $pct >= 100 ? '#059669' : ($pct >= 50 ? '#d97706' : '#dc2626');
                        $ini     = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $s['name']), 0, 2))));
                      ?>
                        <tr>
                          <td>
                            <div class="d-flex align-items-center" style="gap:10px;">
                              <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#2d7a4f,#4ba265);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.7rem;flex-shrink:0;">
                                <?= htmlspecialchars($ini) ?>
                              </div>
                              <div>
                                <div class="font-weight-bold"><?= htmlspecialchars($s['name']) ?></div>
                                <div class="text-muted" style="font-size:.75rem;"><?= htmlspecialchars($s['email']) ?></div>
                              </div>
                            </div>
                          </td>
                          <td><?= htmlspecialchars($s['grade'] ?? '—') ?></td>
                          <td><?= htmlspecialchars($s['section'] ?? '—') ?></td>
                          <td>
                            <span class="font-weight-bold" style="color:<?= $color ?>;"><?= $done ?></span>
                            <span class="text-muted">/ <?= $totalModules ?></span>
                          </td>
                          <td style="min-width:160px;">
                            <div class="d-flex align-items-center" style="gap:8px;">
                              <div class="progress flex-grow-1" style="height:8px;border-radius:99px;">
                                <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $color ?>;"></div>
                              </div>
                              <small class="font-weight-bold" style="color:<?= $color ?>;min-width:36px;"><?= $pct ?>%</small>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php else: ?>
                <p class="text-muted text-center py-4 mb-0">No students enrolled yet.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>

      </div>
    </div>
    <?php teacher_view_layout(['footer']); ?>
  </div>
</div>

<?php teacher_view_layout(['script']); ?>
<script nonce="<?= csp_nonce() ?>">
$(document).ready(function () {
  <?php if (!empty($students)): ?>
  $('#progressTable').DataTable({
    order: [[4, 'desc']],
    responsive: true, autoWidth: false,
    language: { emptyTable: 'No student progress data.' }
  });
  <?php endif; ?>
});
</script>
</body>
</html>
