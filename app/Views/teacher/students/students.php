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

        <div class="d-flex align-items-center justify-content-between mb-4">
          <div>
            <h5 class="m-0 font-weight-bold"><?= htmlspecialchars($pageTitle) ?></h5>
            <div class="text-muted" style="font-size:13px;">Assign and manage your students.</div>
          </div>
          <div>
            <button class="btn btn-outline-success font-weight-bold mr-2" id="btnAssignStudent">
              <i class="bi bi-person-plus mr-1"></i> Assign Existing
            </button>
            <button class="btn btn-success font-weight-bold" id="btnNewStudent">
              <i class="bi bi-plus-lg mr-1"></i> New Student
            </button>
          </div>
        </div>

        <div class="form-card">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Grade</th>
                  <th>Section</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="studentsTbody">
                <tr><td colspan="5" class="text-center text-muted py-4">Loading…</td></tr>
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

var grades   = <?= json_encode($grades) ?>;
var sections = <?= json_encode($sections) ?>;

function gradeOptions(selected) {
  return '<option value="">— Grade —</option>' + grades.map(function(g) {
    return '<option value="' + g.id + '"' + (selected==g.id?' selected':'') + '>' + $('<span>').text(g.name).html() + '</option>';
  }).join('');
}

function sectionOptions(selected) {
  return '<option value="">— Section —</option>' + sections.map(function(s) {
    return '<option value="' + s.id + '"' + (selected==s.id?' selected':'') + '>' + $('<span>').text(s.name).html() + '</option>';
  }).join('');
}

function loadStudents() {
  $.getJSON('<?= baseurl('/teacher/api/students') ?>', function(res) {
    var tbody = $('#studentsTbody');
    tbody.empty();
    if (!res.success || !res.data.length) {
      tbody.html('<tr><td colspan="5" class="text-center text-muted py-4">No students assigned yet.</td></tr>');
      return;
    }
    res.data.forEach(function(s) {
      tbody.append(
        '<tr>' +
        '<td class="font-weight-bold">' + $('<span>').text(s.name).html() + '</td>' +
        '<td class="text-muted">' + $('<span>').text(s.email).html() + '</td>' +
        '<td>' + $('<span>').text(s.grade_name||'—').html() + '</td>' +
        '<td>' + $('<span>').text(s.section_name||'—').html() + '</td>' +
        '<td>' +
          '<button class="btn btn-sm btn-outline-primary mr-1 btn-edit-student" data-id="' + s.id + '" data-grade="' + (s.grade_id||'') + '" data-section="' + (s.section_id||'') + '"><i class="bi bi-pencil"></i></button>' +
          '<button class="btn btn-sm btn-outline-danger btn-delete-student" data-id="' + s.id + '" data-name="' + $('<span>').text(s.name).html() + '"><i class="bi bi-trash"></i></button>' +
        '</td>' +
        '</tr>'
      );
    });
  });
}

$(document).ready(function() {
  loadStudents();

  $('#btnAssignStudent').on('click', function() {
    $.getJSON('<?= baseurl('/teacher/api/students/unassigned') ?>', function(res) {
      var opts = '<option value="">— Select student —</option>';
      (res.data||[]).forEach(function(s) { opts += '<option value="' + s.id + '">' + $('<span>').text(s.name + ' (' + s.email + ')').html() + '</option>'; });
      $('#assignStudentSelect').html(opts);
      $('#assignModal').modal('show');
    });
  });

  $('#assignForm').on('submit', function(e) {
    e.preventDefault();
    var payload = { student_user_id: parseInt($('#assignStudentSelect').val()), grade_id: parseInt($('#assignGrade').val())||null, section_id: parseInt($('#assignSection').val())||null };
    $.ajax({ url: '<?= baseurl('/teacher/api/students/create') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify(payload), dataType: 'json',
      success: function(res) {
        if (res.success) { notyf.success(res.message); $('#assignModal').modal('hide'); loadStudents(); }
        else notyf.error(res.message);
      }
    });
  });

  $('#btnNewStudent').on('click', function() { $('#newStudentForm')[0].reset(); $('#newStudentModal').modal('show'); });

  $('#newStudentForm').on('submit', function(e) {
    e.preventDefault();
    var payload = { name: $('#nsName').val(), email: $('#nsEmail').val(), password: $('#nsPassword').val(), grade_id: parseInt($('#nsGrade').val())||null, section_id: parseInt($('#nsSection').val())||null };
    $.ajax({ url: '<?= baseurl('/teacher/api/students/create-new') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify(payload), dataType: 'json',
      success: function(res) {
        if (res.success) { notyf.success(res.message); $('#newStudentModal').modal('hide'); loadStudents(); }
        else notyf.error(res.message);
      }
    });
  });

  $(document).on('click', '.btn-edit-student', function() {
    var $btn = $(this);
    $('#editStudentId').val($btn.data('id'));
    $('#editGrade').html(gradeOptions($btn.data('grade')));
    $('#editSection').html(sectionOptions($btn.data('section')));
    $('#editStudentModal').modal('show');
  });

  $('#editStudentForm').on('submit', function(e) {
    e.preventDefault();
    var payload = { id: parseInt($('#editStudentId').val()), grade_id: parseInt($('#editGrade').val())||null, section_id: parseInt($('#editSection').val())||null };
    $.ajax({ url: '<?= baseurl('/teacher/api/students/edit') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify(payload), dataType: 'json',
      success: function(res) {
        if (res.success) { notyf.success(res.message); $('#editStudentModal').modal('hide'); loadStudents(); }
        else notyf.error(res.message);
      }
    });
  });

  $(document).on('click', '.btn-delete-student', function() {
    var id = $(this).data('id'), name = $(this).data('name');
    if (!confirm('Remove student "' + name + '" from your class?')) return;
    $.ajax({ url: '<?= baseurl('/teacher/api/students/delete') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify({ id: id }), dataType: 'json',
      success: function(res) {
        if (res.success) { notyf.success(res.message); loadStudents(); }
        else notyf.error(res.message);
      }
    });
  });
});
</script>

<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title font-weight-bold">Assign Existing Student</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
      <form id="assignForm">
        <div class="modal-body">
          <div class="form-group"><label class="font-weight-bold" style="font-size:.82rem;">Student</label><select class="form-control" id="assignStudentSelect"></select></div>
          <div class="form-group"><label class="font-weight-bold" style="font-size:.82rem;">Grade</label><select class="form-control" id="assignGrade"><?= '<option value="">— Grade —</option>' . implode('', array_map(fn($g) => '<option value="'.$g['id'].'">'.htmlspecialchars($g['name']).'</option>', $grades)) ?></select></div>
          <div class="form-group mb-0"><label class="font-weight-bold" style="font-size:.82rem;">Section</label><select class="form-control" id="assignSection"><?= '<option value="">— Section —</option>' . implode('', array_map(fn($s) => '<option value="'.$s['id'].'">'.htmlspecialchars($s['name']).'</option>', $sections)) ?></select></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success font-weight-bold">Assign</button></div>
      </form>
    </div>
  </div>
</div>

<!-- New Student Modal -->
<div class="modal fade" id="newStudentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title font-weight-bold">New Student Account</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
      <form id="newStudentForm">
        <div class="modal-body">
          <div class="form-group"><label class="font-weight-bold" style="font-size:.82rem;">Full Name</label><input type="text" class="form-control" id="nsName" required></div>
          <div class="form-group"><label class="font-weight-bold" style="font-size:.82rem;">Email</label><input type="email" class="form-control" id="nsEmail" required></div>
          <div class="form-group"><label class="font-weight-bold" style="font-size:.82rem;">Password</label><input type="password" class="form-control" id="nsPassword" minlength="6" required></div>
          <div class="form-group"><label class="font-weight-bold" style="font-size:.82rem;">Grade</label><select class="form-control" id="nsGrade"><?= '<option value="">— Grade —</option>' . implode('', array_map(fn($g) => '<option value="'.$g['id'].'">'.htmlspecialchars($g['name']).'</option>', $grades)) ?></select></div>
          <div class="form-group mb-0"><label class="font-weight-bold" style="font-size:.82rem;">Section</label><select class="form-control" id="nsSection"><?= '<option value="">— Section —</option>' . implode('', array_map(fn($s) => '<option value="'.$s['id'].'">'.htmlspecialchars($s['name']).'</option>', $sections)) ?></select></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success font-weight-bold">Create</button></div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title font-weight-bold">Edit Student</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
      <form id="editStudentForm">
        <input type="hidden" id="editStudentId">
        <div class="modal-body">
          <div class="form-group"><label class="font-weight-bold" style="font-size:.82rem;">Grade</label><select class="form-control" id="editGrade"></select></div>
          <div class="form-group mb-0"><label class="font-weight-bold" style="font-size:.82rem;">Section</label><select class="form-control" id="editSection"></select></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary font-weight-bold">Save</button></div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
