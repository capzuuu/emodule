<!DOCTYPE html>
<html lang="en">
<?php admin_view_layout(['header', 'style']); ?>

<body>
<div id="wrapper">

  <?php admin_view_layout(['sidebar']); ?>

  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

      <?php admin_view_layout(['navbar']); ?>

      <div class="container-fluid px-4 py-4">

        <!-- Welcome Banner -->
        <div class="d-flex align-items-center justify-content-between mb-4 p-3 rounded"
             style="background:linear-gradient(135deg,#258517,#396619);color:#fff;box-shadow:0 4px 20px rgba(37,133,23,0.25);">
          <div>
            <div style="font-size:.65rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;opacity:.7;margin-bottom:4px;">Admin Panel</div>
            <h5 class="m-0 font-weight-bold">
              <?php
                $hour = (int)date('H');
                $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                echo $greeting . ', ' . htmlspecialchars(explode(' ', $_SESSION['user']['full_name'] ?? 'Admin')[0]) . '!';
              ?>
            </h5>
            <div style="font-size:.8rem;opacity:.85;">Here's an overview of your E-Module LMS system.</div>
          </div>
          <i class="bi bi-mortarboard-fill" style="font-size:2.5rem;opacity:.2;"></i>
        </div>

        <!-- Stat Cards -->
        <div class="row mb-4">

          <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card h-100" style="--card-color:#258517;">
              <div class="card-accent-bar" style="background:#258517;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Students</div>
                  <div class="stat-number" style="color:#258517;"><?= $counts['students'] ?? 0 ?></div>
                  <div class="stat-sub">Enrolled learners</div>
                </div>
                <div class="stat-icon" style="background:#e8f5e9;color:#258517;">
                  <i class="bi bi-person-fill"></i>
                </div>
              </div>
              <a href="<?= baseurl('/admin/userAccounts') ?>" class="stretched-link"></a>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#F9A825;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Teachers</div>
                  <div class="stat-number" style="color:#e65100;"><?= $counts['teachers'] ?? 0 ?></div>
                  <div class="stat-sub">Active educators</div>
                </div>
                <div class="stat-icon" style="background:#fff8e1;color:#e65100;">
                  <i class="bi bi-person-workspace"></i>
                </div>
              </div>
              <a href="<?= baseurl('/admin/userAccounts') ?>" class="stretched-link"></a>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#4e73df;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Modules</div>
                  <div class="stat-number" style="color:#4e73df;"><?= $counts['modules'] ?? 0 ?></div>
                  <div class="stat-sub">Learning units</div>
                </div>
                <div class="stat-icon" style="background:#eef2ff;color:#4e73df;">
                  <i class="bi bi-journal-bookmark-fill"></i>
                </div>
              </div>
              <a href="<?= baseurl('/admin/modules') ?>" class="stretched-link"></a>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#1cc88a;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Completions</div>
                  <div class="stat-number" style="color:#1cc88a;"><?= $counts['completed_progress'] ?? 0 ?></div>
                  <div class="stat-sub">Modules completed</div>
                </div>
                <div class="stat-icon" style="background:#d1fae5;color:#059669;">
                  <i class="bi bi-check-circle-fill"></i>
                </div>
              </div>
              <a href="<?= baseurl('/admin/progress') ?>" class="stretched-link"></a>
            </div>
          </div>

        </div>

        <!-- Main Row -->
        <div class="row">

          <!-- Recent Users -->
          <div class="col-xl-8 mb-4">
            <div class="card dashboard-card shadow-sm h-100">
              <div class="card-header-clean d-flex align-items-center justify-content-between">
                <span><i class="bi bi-people-fill mr-2 text-success"></i>Recent Users</span>
                <a href="<?= baseurl('/admin/userAccounts') ?>" class="small text-success" style="font-size:12px;">View All →</a>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="ps-4">Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($recentUsers)): ?>
                        <?php foreach ($recentUsers as $u):
                          $ini = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $u['name']), 0, 2))));
                          $bg  = $u['role'] === 'teacher' ? '#fff8e1' : '#e8f5e9';
                          $fg  = $u['role'] === 'teacher' ? '#e65100' : '#258517';
                        ?>
                          <tr>
                            <td class="ps-4">
                              <div class="d-flex align-items-center g-2">
                                <div style="width:30px;height:30px;border-radius:50%;background:<?= $bg ?>;color:<?= $fg ?>;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;flex-shrink:0;">
                                  <?= htmlspecialchars($ini) ?>
                                </div>
                                <span class="font-weight-bold"><?= htmlspecialchars($u['name']) ?></span>
                              </div>
                            </td>
                            <td class="text-muted"><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                              <span class="badge badge-<?= $u['role'] === 'teacher' ? 'teachers' : 'students' ?> px-2">
                                <?= ucfirst($u['role']) ?>
                              </span>
                            </td>
                            <td class="text-muted"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">No users yet.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-xl-4 mb-4 d-flex flex-column" style="gap:20px;">

            <!-- User Distribution -->
            <div class="card dashboard-card shadow-sm">
              <div class="card-header-clean">
                <i class="bi bi-pie-chart-fill mr-2 text-success"></i>User Distribution
              </div>
              <div class="card-body px-4 py-3">
                <?php
                $total = max(1, ($counts['students'] ?? 0) + ($counts['teachers'] ?? 0) + ($counts['admins'] ?? 0));
                $roles = [
                  ['label' => 'Students', 'count' => $counts['students'] ?? 0, 'color' => '#258517'],
                  ['label' => 'Teachers', 'count' => $counts['teachers'] ?? 0, 'color' => '#F9A825'],
                  ['label' => 'Admins',   'count' => $counts['admins']   ?? 0, 'color' => '#4e73df'],
                ];
                foreach ($roles as $r):
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

            <!-- Quick Actions -->
            <div class="card dashboard-card shadow-sm">
              <div class="card-header-clean">
                <i class="bi bi-lightning-fill mr-2 text-warning"></i>Quick Actions
              </div>
              <div class="card-body px-3 py-3">
                <div class="row g-2">
                  <div class="col-6">
                    <a href="<?= baseurl('/admin/userAccounts') ?>" class="quick-action-btn flex-column text-center" style="gap:8px;padding:14px 8px;">
                      <div class="quick-action-icon mx-auto" style="background:#e8f5e9;color:#258517;"><i class="bi bi-person-fill-add"></i></div>
                      <span style="font-size:11.5px;">Add User</span>
                    </a>
                  </div>
                  <div class="col-6">
                    <a href="<?= baseurl('/admin/modules') ?>" class="quick-action-btn flex-column text-center" style="gap:8px;padding:14px 8px;">
                      <div class="quick-action-icon mx-auto" style="background:#eef2ff;color:#4e73df;"><i class="bi bi-journal-plus"></i></div>
                      <span style="font-size:11.5px;">Add Module</span>
                    </a>
                  </div>
                  <div class="col-6">
                    <a href="<?= baseurl('/admin/progress') ?>" class="quick-action-btn flex-column text-center" style="gap:8px;padding:14px 8px;">
                      <div class="quick-action-icon mx-auto" style="background:#d1fae5;color:#059669;"><i class="bi bi-graph-up"></i></div>
                      <span style="font-size:11.5px;">Progress</span>
                    </a>
                  </div>
                  <div class="col-6">
                    <a href="<?= baseurl('/admin/profile') ?>" class="quick-action-btn flex-column text-center" style="gap:8px;padding:14px 8px;">
                      <div class="quick-action-icon mx-auto" style="background:#fef3c7;color:#d97706;"><i class="bi bi-person-circle"></i></div>
                      <span style="font-size:11.5px;">Profile</span>
                    </a>
                  </div>
                </div>
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
                <a href="<?= baseurl('/admin/progress') ?>" class="small text-success" style="font-size:12px;">View All →</a>
              </div>
              <div class="card-body px-4 py-3">
                <?php if (!empty($progressSummary)): ?>
                  <?php foreach ($progressSummary as $s):
                    $totalMods = max(1, $s['total_modules']);
                    $pct = $totalMods > 0 ? round($s['completed'] / $totalMods * 100) : 0;
                    $ini = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $s['name']), 0, 2))));
                  ?>
                    <div class="d-flex align-items-center g-3 mb-3">
                      <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#258517,#4F9516);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;flex-shrink:0;">
                        <?= htmlspecialchars($ini) ?>
                      </div>
                      <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <span class="font-weight-bold" style="font-size:.875rem;"><?= htmlspecialchars($s['name']) ?></span>
                          <small class="text-muted"><?= $s['completed'] ?>/<?= $totalMods ?> modules · <?= $pct ?>%</small>
                        </div>
                        <div class="progress" style="height:6px;border-radius:99px;">
                          <div class="progress-bar" style="width:<?= $pct ?>%"></div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-muted text-center py-3 mb-0">No student progress data yet.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <?php admin_view_layout(['footer']); ?>
  </div>
</div>

<?php admin_view_layout(['script']); ?>

</body>
</html>


