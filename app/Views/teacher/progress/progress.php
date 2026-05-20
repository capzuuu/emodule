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

        <div class="mb-4">
          <h5 class="m-0 font-weight-bold"><?= htmlspecialchars($pageTitle) ?></h5>
          <div class="text-muted" style="font-size:13px;">Monitor student progress across all modules.</div>
        </div>

        <!-- Filters -->
        <div class="form-card mb-4">
          <div class="row">
            <div class="col-md-4 mb-2 mb-md-0">
              <label class="font-weight-bold" style="font-size:.82rem;">Student</label>
              <select class="form-control" id="filterStudent">
                <option value="">All Students</option>
                <?php foreach ($students as $s): ?>
                  <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4 mb-2 mb-md-0">
              <label class="font-weight-bold" style="font-size:.82rem;">Module</label>
              <select class="form-control" id="filterModule">
                <option value="">All Modules</option>
                <?php foreach ($modules as $m): ?>
                  <option value="<?= $m['id'] ?>">Unit <?= $m['unit_number'] ?> — <?= htmlspecialchars($m['title']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <button class="btn btn-success font-weight-bold w-100" id="btnFilter">
                <i class="bi bi-funnel mr-1"></i> Filter
              </button>
            </div>
          </div>
        </div>

        <!-- Progress Table -->
        <div class="form-card">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Student</th>
                  <th>Module</th>
                  <th>Status</th>
                  <th>Pre-Test</th>
                  <th>Post-Test</th>
                  <th>Attempts</th>
                  <th>Completed</th>
                </tr>
              </thead>
              <tbody id="progressTbody">
                <?php if (empty($students)): ?>
                  <tr><td colspan="7" class="text-center text-muted py-4">No students assigned yet.</td></tr>
                <?php elseif (empty($modules)): ?>
                  <tr><td colspan="7" class="text-center text-muted py-4">No modules created yet.</td></tr>
                <?php else: ?>
                  <tr><td colspan="7" class="text-center text-muted py-4">Select filters and click Filter to view progress.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
    <?php teacher_view_layout(['footer']); ?>
  </div>
</div>
<?php teacher_view_layout(['script']); ?>
<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });

$('#btnFilter').on('click', function() {
  var studentId = $('#filterStudent').val();
  var moduleId  = $('#filterModule').val();
  var url = '<?= baseurl('/teacher/progress/json') ?>?student_id=' + studentId + '&module_id=' + moduleId;
  var $btn = $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span>Loading…');

  $.getJSON(url, function(res) {
    var tbody = $('#progressTbody');
    tbody.empty();
    var data = res.data || [];
    if (!data.length) {
      tbody.html('<tr><td colspan="7" class="text-center text-muted py-4">No progress data found.</td></tr>');
      return;
    }
    data.forEach(function(r) {
      var statusBadge = r.status === 'completed'
        ? '<span class="badge" style="background:#d1fae5;color:#059669;"><i class="bi bi-check-circle-fill mr-1"></i>Completed</span>'
        : r.status === 'available'
          ? '<span class="badge" style="background:#e8f5e9;color:#258517;"><i class="bi bi-play-circle-fill mr-1"></i>Available</span>'
          : '<span class="badge" style="background:#f3f4f6;color:#6b7280;"><i class="bi bi-lock-fill mr-1"></i>Locked</span>';

      tbody.append('<tr>' +
        '<td class="font-weight-bold">' + $('<span>').text(r.student_name).html() + '</td>' +
        '<td>' + $('<span>').text('Unit ' + r.unit_number + ' — ' + r.module_title).html() + '</td>' +
        '<td>' + statusBadge + '</td>' +
        '<td>' + (r.pre_correct !== null ? '<span style="color:#4e73df;font-weight:700;">' + r.pre_correct + '/' + r.pre_count + '</span>' : '<span class="text-muted">—</span>') + '</td>' +
        '<td>' + (r.post_correct !== null ? '<span style="color:#059669;font-weight:700;">' + r.post_correct + '/' + r.post_count + '</span>' : '<span class="text-muted">—</span>') + '</td>' +
        '<td>' + (r.quiz_attempts > 0 ? '<span style="color:#f6c23e;font-weight:700;">' + r.quiz_attempts + '</span>' : '<span class="text-muted">—</span>') + '</td>' +
        '<td class="text-muted" style="font-size:.78rem;">' + (r.completed_date ? r.completed_date : '—') + '</td>' +
        '</tr>');
    });
  }).fail(function() {
    notyf.error('Failed to load progress data.');
  }).always(function() {
    $btn.prop('disabled', false).html('<i class="bi bi-funnel mr-1"></i> Filter');
  });
});
</script>
</body>
</html>
