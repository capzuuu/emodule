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
          <div class="text-muted" style="font-size:13px;">Manage grade levels and class sections.</div>
        </div>

        <div class="row">
          <!-- Grades -->
          <div class="col-md-6 mb-4">
            <div class="form-card h-100">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="font-weight-bold m-0"><i class="bi bi-mortarboard-fill mr-2 text-success"></i>Grades</h6>
                <button class="btn btn-success btn-sm font-weight-bold" id="btnAddGrade"><i class="bi bi-plus-lg mr-1"></i> Add</button>
              </div>
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead><tr><th>Grade Name</th><th>Actions</th></tr></thead>
                  <tbody id="gradesTbody"><tr><td colspan="2" class="text-center text-muted py-3">Loading…</td></tr></tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Sections -->
          <div class="col-md-6 mb-4">
            <div class="form-card h-100">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="font-weight-bold m-0"><i class="bi bi-diagram-3-fill mr-2 text-primary"></i>Sections</h6>
                <button class="btn btn-primary btn-sm font-weight-bold" id="btnAddSection"><i class="bi bi-plus-lg mr-1"></i> Add</button>
              </div>
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead><tr><th>Section Name</th><th>Grade</th><th>Actions</th></tr></thead>
                  <tbody id="sectionsTbody"><tr><td colspan="3" class="text-center text-muted py-3">Loading…</td></tr></tbody>
                </table>
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
<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });
var gradesData = [];

function loadGrades() {
  $.getJSON('<?= baseurl('/teacher/grades/json') ?>', function(res) {
    gradesData = res.data || [];
    var tbody = $('#gradesTbody');
    tbody.empty();
    if (!gradesData.length) { tbody.html('<tr><td colspan="2" class="text-center text-muted py-3">No grades yet.</td></tr>'); return; }
    gradesData.forEach(function(g) {
      tbody.append('<tr><td class="font-weight-bold">' + $('<span>').text(g.name).html() + '</td><td>' +
        '<button class="btn btn-sm btn-outline-primary mr-1 btn-edit-grade" data-id="' + g.id + '" data-name="' + $('<span>').text(g.name).html() + '"><i class="bi bi-pencil"></i></button>' +
        '<button class="btn btn-sm btn-outline-danger btn-delete-grade" data-id="' + g.id + '" data-name="' + $('<span>').text(g.name).html() + '"><i class="bi bi-trash"></i></button>' +
        '</td></tr>');
    });
    // Refresh section grade dropdown
    var opts = '<option value="">— None —</option>' + gradesData.map(function(g) { return '<option value="' + g.id + '">' + $('<span>').text(g.name).html() + '</option>'; }).join('');
    $('#sectionGrade, #editSectionGrade').html(opts);
  });
}

function loadSections() {
  $.getJSON('<?= baseurl('/teacher/sections/json') ?>', function(res) {
    var tbody = $('#sectionsTbody');
    tbody.empty();
    var data = res.data || [];
    if (!data.length) { tbody.html('<tr><td colspan="3" class="text-center text-muted py-3">No sections yet.</td></tr>'); return; }
    data.forEach(function(s) {
      tbody.append('<tr><td class="font-weight-bold">' + $('<span>').text(s.name).html() + '</td><td>' + $('<span>').text(s.grade_name||'—').html() + '</td><td>' +
        '<button class="btn btn-sm btn-outline-primary mr-1 btn-edit-section" data-id="' + s.id + '" data-name="' + $('<span>').text(s.name).html() + '" data-grade="' + (s.grade_id||'') + '"><i class="bi bi-pencil"></i></button>' +
        '<button class="btn btn-sm btn-outline-danger btn-delete-section" data-id="' + s.id + '" data-name="' + $('<span>').text(s.name).html() + '"><i class="bi bi-trash"></i></button>' +
        '</td></tr>');
    });
  });
}

$(document).ready(function() {
  loadGrades(); loadSections();

  $('#btnAddGrade').on('click', function() { $('#gradeNameInput').val(''); $('#gradeModal').modal('show'); });
  $('#gradeForm').on('submit', function(e) {
    e.preventDefault();
    var id = $('#gradeId').val(), name = $('#gradeNameInput').val();
    var url = id ? '<?= baseurl('/teacher/grades/edit') ?>' : '<?= baseurl('/teacher/grades/create') ?>';
    $.ajax({ url: url, type: 'POST', contentType: 'application/json', data: JSON.stringify({ id: id ? parseInt(id) : undefined, name: name }), dataType: 'json',
      success: function(res) { if (res.success) { notyf.success(res.message); $('#gradeModal').modal('hide'); loadGrades(); } else notyf.error(res.message); }
    });
  });
  $(document).on('click', '.btn-edit-grade', function() {
    $('#gradeId').val($(this).data('id')); $('#gradeNameInput').val($(this).data('name')); $('#gradeModal').modal('show');
  });
  $(document).on('click', '.btn-delete-grade', function() {
    if (!confirm('Delete grade "' + $(this).data('name') + '"?')) return;
    $.ajax({ url: '<?= baseurl('/teacher/grades/delete') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify({ id: $(this).data('id') }), dataType: 'json',
      success: function(res) { if (res.success) { notyf.success(res.message); loadGrades(); loadSections(); } else notyf.error(res.message); }
    });
  });

  $('#btnAddSection').on('click', function() { $('#sectionId').val(''); $('#sectionNameInput').val(''); $('#sectionModal').modal('show'); });
  $('#sectionForm').on('submit', function(e) {
    e.preventDefault();
    var id = $('#sectionId').val(), name = $('#sectionNameInput').val(), gradeId = parseInt($('#sectionGrade').val())||null;
    var url = id ? '<?= baseurl('/teacher/sections/edit') ?>' : '<?= baseurl('/teacher/sections/create') ?>';
    $.ajax({ url: url, type: 'POST', contentType: 'application/json', data: JSON.stringify({ id: id ? parseInt(id) : undefined, name: name, grade_id: gradeId }), dataType: 'json',
      success: function(res) { if (res.success) { notyf.success(res.message); $('#sectionModal').modal('hide'); loadSections(); } else notyf.error(res.message); }
    });
  });
  $(document).on('click', '.btn-edit-section', function() {
    $('#sectionId').val($(this).data('id')); $('#sectionNameInput').val($(this).data('name')); $('#sectionGrade').val($(this).data('grade')); $('#sectionModal').modal('show');
  });
  $(document).on('click', '.btn-delete-section', function() {
    if (!confirm('Delete section "' + $(this).data('name') + '"?')) return;
    $.ajax({ url: '<?= baseurl('/teacher/sections/delete') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify({ id: $(this).data('id') }), dataType: 'json',
      success: function(res) { if (res.success) { notyf.success(res.message); loadSections(); } else notyf.error(res.message); }
    });
  });
});
</script>

<!-- Grade Modal -->
<div class="modal fade" id="gradeModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title font-weight-bold">Grade</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
    <form id="gradeForm"><input type="hidden" id="gradeId">
      <div class="modal-body"><div class="form-group mb-0"><label class="font-weight-bold" style="font-size:.82rem;">Grade Name</label><input type="text" class="form-control" id="gradeNameInput" required></div></div>
      <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success font-weight-bold">Save</button></div>
    </form>
  </div></div>
</div>

<!-- Section Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title font-weight-bold">Section</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
    <form id="sectionForm"><input type="hidden" id="sectionId">
      <div class="modal-body">
        <div class="form-group"><label class="font-weight-bold" style="font-size:.82rem;">Section Name</label><input type="text" class="form-control" id="sectionNameInput" required></div>
        <div class="form-group mb-0"><label class="font-weight-bold" style="font-size:.82rem;">Grade (optional)</label><select class="form-control" id="sectionGrade"><option value="">— None —</option></select></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary font-weight-bold">Save</button></div>
    </form>
  </div></div>
</div>
</body>
</html>
