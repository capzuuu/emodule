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

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
          <div>
            <h5 class="m-0 mb-1 font-weight-bold"><?= htmlspecialchars($pageTitle) ?></h5>
            <div class="text-muted" style="font-size:13px;"><?= htmlspecialchars($pageSubtitle) ?></div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="form-card">
              <div class="table-responsive">
                <table id="modulesTable" class="table table-hover w-100" style="font-size:13px;">
                  <thead>
                    <tr>
                      <th>Unit</th>
                      <th>Title</th>
                      <th>Outcome</th>
                      <th>Teacher</th>
                      <th>Pre-Test</th>
                      <th>Post-Test</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
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

  $('#modulesTable').DataTable({
    ajax: {
      url: '<?= baseurl("/admin/modules/json") ?>',
      dataSrc: 'data',
      error: function () { notyf.error('Failed to load modules.'); }
    },
    columns: [
      { data: 'unit_number', render: function (d) { return '<span class="badge badge-secondary px-2">Unit ' + d + '</span>'; } },
      { data: 'title',       render: function (d) { return '<span class="font-weight-bold">' + d + '</span>'; } },
      { data: 'outcome',     render: function (d) { return d.length > 60 ? d.substring(0, 60) + '…' : d; } },
      { data: 'teacher_name', render: function (d) { return d || '<span class="text-muted">—</span>'; } },
      { data: 'pre_count',   render: function (d) { return '<span class="badge ' + (d > 0 ? 'badge-success' : 'badge-secondary') + '">' + d + ' Q</span>'; } },
      { data: 'post_count',  render: function (d) { return '<span class="badge ' + (d > 0 ? 'badge-info'    : 'badge-secondary') + '">' + d + ' Q</span>'; } }
    ],
    order: [[0, 'asc']],
    responsive: true,
    autoWidth: false,
    processing: true,
    language: {
      processing: '<span class="spinner-border spinner-border-sm text-success mr-1"></span> Loading...',
      emptyTable: 'No modules found.'
    }
  });

});
</script>

</body>
</html>
