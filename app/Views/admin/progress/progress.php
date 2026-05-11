<!DOCTYPE html>
<html lang="en">
<?php admin_view_layout(['header', 'style']); ?>

<body>
<div id="wrapper">
  <?php admin_view_layout(['sidebar']); ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?php admin_view_layout(['navbar']); ?>
      <div class="container-fluid py-4">

        <div class="mb-4">
          <h5 class="m-0 mb-1 font-weight-bold"><?= htmlspecialchars($pageTitle) ?></h5>
          <div class="text-muted" style="font-size:13px;"><?= htmlspecialchars($pageSubtitle) ?></div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
          <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card h-100" style="--card-color:#258517;">
              <div class="card-accent-bar" style="background:#258517;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Total Students</div>
                  <div class="stat-number" style="color:#258517;"><?= (int)($summary['total_students'] ?? 0) ?></div>
                  <div class="stat-sub">Enrolled learners</div>
                </div>
                <div class="stat-icon" style="background:#e8f5e9;color:#258517;">
                  <i class="bi bi-people-fill"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#4e73df;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Total Modules</div>
                  <div class="stat-number" style="color:#4e73df;"><?= (int)($summary['total_modules'] ?? 0) ?></div>
                  <div class="stat-sub">Available units</div>
                </div>
                <div class="stat-icon" style="background:#eef2ff;color:#4e73df;">
                  <i class="bi bi-journal-bookmark-fill"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#1cc88a;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Total Completions</div>
                  <div class="stat-number" style="color:#1cc88a;"><?= (int)($summary['total_completions'] ?? 0) ?></div>
                  <div class="stat-sub">Modules completed</div>
                </div>
                <div class="stat-icon" style="background:#d1fae5;color:#059669;">
                  <i class="bi bi-check-circle-fill"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card h-100">
              <div class="card-accent-bar" style="background:#f6c23e;"></div>
              <div class="d-flex align-items-center justify-content-between py-1">
                <div>
                  <div class="stat-label">Active Learners</div>
                  <div class="stat-number" style="color:#e0a800;"><?= (int)($summary['students_with_completion'] ?? 0) ?></div>
                  <div class="stat-sub">Students with progress</div>
                </div>
                <div class="stat-icon" style="background:#fef9e7;color:#e0a800;">
                  <i class="bi bi-person-check-fill"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">

          <!-- Student Progress Table -->
          <div class="col-xl-8 mb-4">
            <div class="card dashboard-card shadow-sm h-100">
              <div class="card-header-clean">
                <i class="bi bi-bar-chart-fill mr-2 text-success"></i>Student Progress
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table id="progressTable" class="table table-hover w-100" style="font-size:13px;">
                    <thead>
                      <tr>
                        <th style="width:36px;"></th>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Completed</th>
                        <th>Progress</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Module Completion Stats -->
          <div class="col-xl-4 mb-4">
            <div class="card dashboard-card shadow-sm h-100">
              <div class="card-header-clean">
                <i class="bi bi-journal-check mr-2 text-success"></i>Module Completion
              </div>
              <div class="card-body px-4 py-3">
                <?php if (!empty($moduleStats)): ?>
                  <?php foreach ($moduleStats as $m):
                    $total = max(1, (int)$m['total_students']);
                    $pct   = round((int)$m['completions'] / $total * 100);
                  ?>
                    <div class="mb-3">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="font-weight-bold" style="font-size:.8rem;">
                          <span class="badge badge-secondary mr-1">Unit <?= (int)$m['unit_number'] ?></span>
                          <?= htmlspecialchars($m['title']) ?>
                        </span>
                        <small class="text-muted"><?= (int)$m['completions'] ?>/<?= $total ?></small>
                      </div>
                      <div class="progress" style="height:6px;border-radius:99px;">
                        <div class="progress-bar bg-success" style="width:<?= $pct ?>%;border-radius:99px;"></div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-muted text-center py-3 mb-0">No module data yet.</p>
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

<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });

$(document).ready(function () {

  $('#progressTable').DataTable({
    ajax: {
      url: '<?= baseurl("/admin/progress/json") ?>',
      dataSrc: 'data',
      error: function () { notyf.error('Failed to load progress data.'); }
    },
    columns: [
      {
        data: 'name',
        orderable: false,
        searchable: false,
        render: function (d) {
          var ini = (d || '?').split(' ').map(function (w) { return w[0]; }).join('').slice(0, 2).toUpperCase();
          return '<div style="width:32px;height:32px;border-radius:50%;background:#e8f5e9;color:#258517;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;">' + ini + '</div>';
        }
      },
      {
        data: 'name',
        render: function (d) { return '<span class="font-weight-bold">' + d + '</span>'; }
      },
      {
        data: 'email',
        render: function (d) { return '<span class="text-muted">' + d + '</span>'; }
      },
      {
        data: 'completed',
        render: function (d, t, row) {
          return '<span class="badge badge-success">' + d + ' / ' + row.total_modules + '</span>';
        }
      },
      {
        data: 'completed',
        render: function (d, t, row) {
          var pct = row.total_modules > 0 ? Math.round(d / row.total_modules * 100) : 0;
          return '<div style="min-width:100px;">' +
            '<div class="d-flex justify-content-between mb-1"><small class="text-muted">' + pct + '%</small></div>' +
            '<div class="progress" style="height:6px;border-radius:99px;">' +
              '<div class="progress-bar bg-success" style="width:' + pct + '%;border-radius:99px;"></div>' +
            '</div></div>';
        }
      }
    ],
    order: [[3, 'desc']],
    responsive: true,
    autoWidth: false,
    processing: true,
    language: {
      processing:  '<span class="spinner-border spinner-border-sm text-success mr-1"></span> Loading...',
      emptyTable:  'No students found.',
      zeroRecords: 'No matching students found.'
    }
  });

});
</script>

</body>
</html>
