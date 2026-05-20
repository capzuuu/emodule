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
          <div style="min-width:0;">
            <div style="font-size:.65rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;opacity:.7;margin-bottom:4px;">Teacher Panel</div>
            <h5 class="m-0 font-weight-bold" style="font-size:clamp(.95rem,3vw,1.15rem);">
              <?php
                $hour = (int)date('H');
                $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                echo $greeting . ', ' . htmlspecialchars(explode(' ', $userName)[0]) . '!';
              ?>
            </h5>
            <div style="font-size:.8rem;opacity:.85;">Manage your modules and track student progress.</div>
          </div>
          <i class="bi bi-person-workspace d-none d-sm-block" style="font-size:2.5rem;opacity:.2;flex-shrink:0;"></i>
        </div>

        <!-- Stat Cards -->
        <div class="row mb-4">
          <div class="col-md-3 col-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#258517;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Modules</div>
                  <div class="stat-number" style="color:#258517;"><?= count($modules) ?></div>
                  <div class="stat-sub">Created</div>
                </div>
                <div class="stat-icon" style="background:#e8f5e9;color:#258517;"><i class="bi bi-journal-text"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#4e73df;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Students</div>
                  <div class="stat-number" style="color:#4e73df;"><?= count($students) ?></div>
                  <div class="stat-sub">Assigned</div>
                </div>
                <div class="stat-icon" style="background:#eef2ff;color:#4e73df;"><i class="bi bi-people-fill"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#1cc88a;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Teacher ID</div>
                  <div class="stat-number" style="color:#1cc88a;"><?= $teacherId ?? '—' ?></div>
                  <div class="stat-sub">Profile</div>
                </div>
                <div class="stat-icon" style="background:#d1fae5;color:#059669;"><i class="bi bi-person-badge-fill"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#F9A825;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">School</div>
                  <div class="stat-number" style="color:#e65100;font-size:1rem;">SUNN</div>
                  <div class="stat-sub">E-Module LMS</div>
                </div>
                <div class="stat-icon" style="background:#fff8e1;color:#e65100;"><i class="bi bi-building"></i></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modules Table -->
        <div class="card dashboard-card shadow-sm mb-4">
          <div class="card-header-clean d-flex align-items-center justify-content-between">
            <span><i class="bi bi-journal-text mr-2 text-success"></i>My Modules</span>
            <a href="<?= baseurl('/teacher/modules') ?>" class="small text-success" style="font-size:12px;">Manage →</a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th class="pl-4">Unit</th>
                    <th>Title</th>
                    <th>Outcome</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($modules as $m): ?>
                    <tr>
                      <td class="pl-4"><span class="badge badge-secondary">Unit <?= $m['unit_number'] ?></span></td>
                      <td class="font-weight-bold"><?= htmlspecialchars($m['title']) ?></td>
                      <td class="text-muted"><?= htmlspecialchars(mb_strimwidth($m['outcome'] ?? '', 0, 60, '…')) ?></td>
                    </tr>
                  <?php endforeach; ?>
                  <?php if (empty($modules)): ?>
                    <tr><td colspan="3" class="text-center text-muted py-4">No modules yet. <a href="<?= baseurl('/teacher/modules') ?>">Create one →</a></td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Students Table -->
        <div class="card dashboard-card shadow-sm">
          <div class="card-header-clean d-flex align-items-center justify-content-between">
            <span><i class="bi bi-people-fill mr-2 text-primary"></i>My Students</span>
            <a href="<?= baseurl('/teacher/students') ?>" class="small text-primary" style="font-size:12px;">Manage →</a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th class="pl-4">Name</th>
                    <th>Email</th>
                    <th>Grade</th>
                    <th>Section</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($students as $s): ?>
                    <tr>
                      <td class="pl-4 font-weight-bold"><?= htmlspecialchars($s['name']) ?></td>
                      <td class="text-muted"><?= htmlspecialchars($s['email']) ?></td>
                      <td><?= htmlspecialchars($s['grade_name'] ?? '—') ?></td>
                      <td><?= htmlspecialchars($s['section_name'] ?? '—') ?></td>
                    </tr>
                  <?php endforeach; ?>
                  <?php if (empty($students)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">No students assigned yet.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
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
