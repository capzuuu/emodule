<!DOCTYPE html>
<html lang="en">
<?php teacher_view_layout(['header', 'style']); ?>
<link href="<?= asset('dist/assets/css/select2.min.css') ?>" rel="stylesheet">
<link href="<?= asset('dist/assets/css/select2-bootstrap4.min.css') ?>" rel="stylesheet">

<body>
<div id="wrapper">
  <?php teacher_view_layout(['sidebar']); ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?php teacher_view_layout(['navbar']); ?>
      <div class="container-fluid py-4">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
          <div>
            <h5 class="m-0 mb-1 font-weight-bold"><?= htmlspecialchars($pageTitle) ?></h5>
            <div class="text-muted" style="font-size:13px;">Assign admin-created students to your class.</div>
          </div>
          <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#addStudentModal">
            <i class="bi bi-plus mr-1"></i> Add Student
          </button>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="form-card">
              <div class="table-responsive">
                <table id="studentsTable" class="table table-hover w-100" style="font-size:13px;">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Grade</th>
                      <th>Section</th>
                      <th>Completed</th>
                      <th style="width:90px;">Actions</th>
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
    <?php teacher_view_layout(['footer']); ?>
  </div>
</div>

<!-- ADD STUDENT MODAL -->
<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#258517,#396619);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-person-plus mr-2"></i>Add Student</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <form id="addStudentForm">
        <div class="modal-body px-4 py-3">
          <div class="form-group">
            <label class="font-weight-bold" style="font-size:.82rem;">Select Student <span class="text-danger">*</span></label>
            <select id="studentSelect" name="student_user_id" class="form-control" style="width:100%;">
              <option value=""></option>
            </select>
            <small class="text-muted">Only students not yet assigned to you are shown.</small>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group mb-0">
                <label class="font-weight-bold" style="font-size:.82rem;">Grade</label>
                <select class="form-control" id="addGradeId">
                  <option value="">— Select —</option>
                  <?php foreach ($grades as $g): ?>
                    <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group mb-0">
                <label class="font-weight-bold" style="font-size:.82rem;">Section</label>
                <select class="form-control" id="addSectionId">
                  <option value="">— Select —</option>
                  <?php foreach ($sections as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" id="addStudentBtn" class="btn btn-success font-weight-bold">
            <i class="bi bi-check-circle mr-1"></i>Assign Student
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#1565C0,#0d47a1);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-pencil mr-2"></i>Edit Student</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <form id="editStudentForm">
        <div class="modal-body px-4 py-3">
          <input type="hidden" id="editStudentId">
          <div class="form-group">
            <label class="font-weight-bold" style="font-size:.82rem;">Name</label>
            <input type="text" class="form-control" id="editStudentName" disabled>
          </div>
          <div class="form-group">
            <label class="font-weight-bold" style="font-size:.82rem;">Email</label>
            <input type="text" class="form-control" id="editStudentEmail" disabled>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group mb-0">
                <label class="font-weight-bold" style="font-size:.82rem;">Grade</label>
                <select class="form-control" id="editGradeId">
                  <option value="">— Select —</option>
                  <?php foreach ($grades as $g): ?>
                    <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group mb-0">
                <label class="font-weight-bold" style="font-size:.82rem;">Section</label>
                <select class="form-control" id="editSectionId">
                  <option value="">— Select —</option>
                  <?php foreach ($sections as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" id="editStudentBtn" class="btn btn-primary font-weight-bold">
            <i class="bi bi-check-lg mr-1"></i>Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- REMOVE MODAL -->
<div class="modal fade" id="deleteStudentModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow text-center" style="border-radius:16px;">
      <div class="modal-header justify-content-center border-0" style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title">Remove Student?</h5>
      </div>
      <form id="deleteStudentForm">
        <div class="modal-body px-4 py-4">
          <input type="hidden" id="deleteStudentId">
          <div style="font-size:2.5rem;margin-bottom:12px;">🗑️</div>
          <p class="text-muted mb-0" style="font-size:.875rem;">
            Remove <strong id="deleteStudentName"></strong> from your class? This cannot be undone.
          </p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-4">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" id="deleteStudentBtn" class="btn btn-danger font-weight-bold">Yes, Remove</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php teacher_view_layout(['script']); ?>
<script src="<?= asset('dist/assets/js/select2.min.js') ?>" nonce="<?= csp_nonce() ?>"></script>
<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });

$(document).ready(function () {

  // ── DataTable ──
  var table = $('#studentsTable').DataTable({
    ajax: {
      url: '<?= baseurl('/teacher/api/students') ?>',
      dataSrc: 'data',
      error: function () { notyf.error('Failed to load students.'); }
    },
    columns: [
      { data: 'name',  render: function (d) { return '<span class="font-weight-bold">' + d + '</span>'; } },
      { data: 'email', render: function (d) { return '<span class="text-muted">' + d + '</span>'; } },
      { data: 'grade',   defaultContent: '<span class="text-muted">—</span>' },
      { data: 'section', defaultContent: '<span class="text-muted">—</span>' },
      { data: 'completed_modules', render: function (d) { return '<span class="badge badge-success">' + (d || 0) + ' modules</span>'; } },
      {
        data: null, orderable: false, searchable: false,
        render: function (d) {
          var n = $('<div>').text(d.name).html();
          return '<button class="btn btn-sm btn-light mr-1 btn-edit" data-id="' + d.id + '" data-name="' + n + '" data-email="' + d.email + '" data-grade="' + (d.grade_id || '') + '" data-section="' + (d.section_id || '') + '" title="Edit"><i class="bi bi-pencil-fill text-secondary"></i></button>' +
                 '<button class="btn btn-sm btn-light btn-delete" data-id="' + d.id + '" data-name="' + n + '" title="Remove"><i class="bi bi-trash3-fill text-danger"></i></button>';
        }
      }
    ],
    order: [[0, 'asc']], responsive: true, autoWidth: false, processing: true,
    language: {
      processing: '<span class="spinner-border spinner-border-sm text-success mr-1"></span> Loading...',
      emptyTable: 'No students assigned yet.'
    }
  });

  // ── Select2 ──
  $('#studentSelect').select2({
    theme: 'bootstrap4',
    dropdownParent: $('#addStudentModal'),
    placeholder: '— Search student name or email —',
    allowClear: true
  });

  // Load unassigned students when modal opens
  $('#addStudentModal').on('show.bs.modal', function () {
    $('#studentSelect').empty().append('<option value=""></option>').trigger('change');

    $.getJSON('<?= baseurl('/teacher/api/students/unassigned') ?>', function (res) {
      if (!res.success) { notyf.error('Failed to load students.'); return; }

      var data = res.data || [];
      if (data.length === 0) {
        notyf.error('No available students to assign.');
        return;
      }

      $.each(data, function (i, s) {
        var option = new Option(s.name + ' — ' + s.email, s.id, false, false);
        $('#studentSelect').append(option);
      });

      $('#studentSelect').trigger('change');
    }).fail(function (xhr) {
      notyf.error('Failed to load students. Status: ' + xhr.status);
    });
  });

  $('#addStudentModal').on('hidden.bs.modal', function () {
    $('#studentSelect').empty().append('<option value=""></option>').trigger('change');
    $('#addGradeId, #addSectionId').val('');
  });

  // ── Assign ──
  $('#addStudentForm').on('submit', function (e) {
    e.preventDefault();
    var studentUserId = $('#studentSelect').val();
    if (!studentUserId) { notyf.error('Please select a student.'); return; }
    var $btn = $('#addStudentBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Assigning...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl('/teacher/api/students/create') ?>', type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ student_user_id: parseInt(studentUserId), grade_id: $('#addGradeId').val(), section_id: $('#addSectionId').val() }),
      dataType: 'json',
      success: function (res) {
        if (res.success) { notyf.success(res.message); table.ajax.reload(null, false); $('#addStudentModal').modal('hide'); }
        else notyf.error(res.message);
      },
      error: function () { notyf.error('Server error.'); },
      complete: function () { $btn.html('<i class="bi bi-check-circle mr-1"></i>Assign Student').prop('disabled', false); }
    });
  });

  // ── Edit ──
  $(document).on('click', '.btn-edit', function () {
    var $b = $(this);
    $('#editStudentId').val($b.data('id'));
    $('#editStudentName').val($b.data('name'));
    $('#editStudentEmail').val($b.data('email'));
    $('#editGradeId').val($b.data('grade'));
    $('#editSectionId').val($b.data('section'));
    $('#editStudentModal').modal('show');
  });

  $('#editStudentForm').on('submit', function (e) {
    e.preventDefault();
    var $btn = $('#editStudentBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Saving...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl('/teacher/api/students/edit') ?>', type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ id: $('#editStudentId').val(), grade_id: $('#editGradeId').val(), section_id: $('#editSectionId').val() }),
      dataType: 'json',
      success: function (res) {
        if (res.success) { notyf.success(res.message); table.ajax.reload(null, false); $('#editStudentModal').modal('hide'); }
        else notyf.error(res.message);
      },
      error: function () { notyf.error('Server error.'); },
      complete: function () { $btn.html('<i class="bi bi-check-lg mr-1"></i>Save Changes').prop('disabled', false); }
    });
  });

  // ── Delete ──
  $(document).on('click', '.btn-delete', function () {
    $('#deleteStudentId').val($(this).data('id'));
    $('#deleteStudentName').text($(this).data('name'));
    $('#deleteStudentModal').modal('show');
  });

  $('#deleteStudentForm').on('submit', function (e) {
    e.preventDefault();
    var $btn = $('#deleteStudentBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Removing...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl('/teacher/api/students/delete') ?>', type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ id: $('#deleteStudentId').val() }),
      dataType: 'json',
      success: function (res) {
        if (res.success) { notyf.success(res.message); table.ajax.reload(null, false); $('#deleteStudentModal').modal('hide'); }
        else notyf.error(res.message);
      },
      error: function () { notyf.error('Server error.'); },
      complete: function () { $btn.html('Yes, Remove').prop('disabled', false); }
    });
  });

});
</script>
</body>
</html>
