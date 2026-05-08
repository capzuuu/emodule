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
          <div class="text-muted" style="font-size:13px;">Manage grade levels and class sections.</div>
        </div>

        <div class="row">

          <!-- Grades -->
          <div class="col-xl-6 mb-4">
            <div class="form-card">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="m-0 font-weight-bold" style="color:var(--primary);"><i class="bi bi-grid-3x3-gap mr-2"></i>Grade Levels</h6>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#createGradeModal">
                  <i class="bi bi-plus mr-1"></i>Add Grade
                </button>
              </div>
              <div class="table-responsive">
                <table id="gradesTable" class="table table-hover w-100" style="font-size:13px;">
                  <thead><tr><th>#</th><th>Grade Name</th><th style="width:90px;">Actions</th></tr></thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Sections -->
          <div class="col-xl-6 mb-4">
            <div class="form-card">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="m-0 font-weight-bold" style="color:var(--primary);"><i class="bi bi-collection mr-2"></i>Sections</h6>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#createSectionModal">
                  <i class="bi bi-plus mr-1"></i>Add Section
                </button>
              </div>
              <div class="table-responsive">
                <table id="sectionsTable" class="table table-hover w-100" style="font-size:13px;">
                  <thead><tr><th>#</th><th>Section Name</th><th>Grade</th><th style="width:90px;">Actions</th></tr></thead>
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

<!-- CREATE GRADE -->
<div class="modal fade" id="createGradeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#2d7a4f,#4ba265);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-plus-circle mr-2"></i>Add Grade</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <form id="createGradeForm">
        <div class="modal-body px-4 py-3">
          <div class="form-group mb-0">
            <label class="font-weight-bold" style="font-size:.82rem;">Grade Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" placeholder="e.g. Grade 7" required>
          </div>
        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" id="createGradeBtn" class="btn btn-success font-weight-bold">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- EDIT GRADE -->
<div class="modal fade" id="editGradeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#1565C0,#0d47a1);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-pencil mr-2"></i>Edit Grade</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <form id="editGradeForm">
        <div class="modal-body px-4 py-3">
          <input type="hidden" id="editGradeId">
          <div class="form-group mb-0">
            <label class="font-weight-bold" style="font-size:.82rem;">Grade Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="editGradeName" required>
          </div>
        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" id="editGradeBtn" class="btn btn-primary font-weight-bold">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- DELETE GRADE -->
<div class="modal fade" id="deleteGradeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow text-center" style="border-radius:16px;">
      <div class="modal-header justify-content-center border-0" style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title">Delete Grade?</h5>
      </div>
      <form id="deleteGradeForm">
        <div class="modal-body px-4 py-4">
          <input type="hidden" id="deleteGradeId">
          <div style="font-size:2.5rem;margin-bottom:12px;">🗑️</div>
          <p class="text-muted mb-0" style="font-size:.875rem;">Delete <strong id="deleteGradeName"></strong>? This cannot be undone.</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-4">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" id="deleteGradeBtn" class="btn btn-danger font-weight-bold">Yes, Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- CREATE SECTION -->
<div class="modal fade" id="createSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#2d7a4f,#4ba265);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-plus-circle mr-2"></i>Add Section</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <form id="createSectionForm">
        <div class="modal-body px-4 py-3">
          <div class="form-group">
            <label class="font-weight-bold" style="font-size:.82rem;">Section Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" placeholder="e.g. Section A" required>
          </div>
          <div class="form-group mb-0">
            <label class="font-weight-bold" style="font-size:.82rem;">Grade (optional)</label>
            <select class="form-control" name="grade_id" id="createSectionGradeId">
              <option value="">— None —</option>
            </select>
          </div>
        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" id="createSectionBtn" class="btn btn-success font-weight-bold">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- EDIT SECTION -->
<div class="modal fade" id="editSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#1565C0,#0d47a1);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-pencil mr-2"></i>Edit Section</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <form id="editSectionForm">
        <div class="modal-body px-4 py-3">
          <input type="hidden" id="editSectionId">
          <div class="form-group mb-0">
            <label class="font-weight-bold" style="font-size:.82rem;">Section Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="editSectionName" required>
          </div>
        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" id="editSectionBtn" class="btn btn-primary font-weight-bold">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- DELETE SECTION -->
<div class="modal fade" id="deleteSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow text-center" style="border-radius:16px;">
      <div class="modal-header justify-content-center border-0" style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title">Delete Section?</h5>
      </div>
      <form id="deleteSectionForm">
        <div class="modal-body px-4 py-4">
          <input type="hidden" id="deleteSectionId">
          <div style="font-size:2.5rem;margin-bottom:12px;">🗑️</div>
          <p class="text-muted mb-0" style="font-size:.875rem;">Delete <strong id="deleteSectionName"></strong>? This cannot be undone.</p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-4">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" id="deleteSectionBtn" class="btn btn-danger font-weight-bold">Yes, Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php teacher_view_layout(['script']); ?>
<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });

$(document).ready(function () {

  // ── Grades Table ──
  var gradesTable = $('#gradesTable').DataTable({
    ajax: { url: '<?= baseurl('/teacher/api/grades') ?>', dataSrc: 'data', error: function(){ notyf.error('Failed to load grades.'); } },
    columns: [
      { data: 'id' },
      { data: 'name', render: function(d){ return '<span class="font-weight-bold">' + d + '</span>'; } },
      {
        data: null, orderable: false, searchable: false,
        render: function(d){
          var n = $('<div>').text(d.name).html();
          return '<button class="btn btn-sm btn-light mr-1 btn-edit-grade" data-id="' + d.id + '" data-name="' + n + '" title="Edit"><i class="bi bi-pencil-fill text-secondary"></i></button>' +
                 '<button class="btn btn-sm btn-light btn-delete-grade" data-id="' + d.id + '" data-name="' + n + '" title="Delete"><i class="bi bi-trash3-fill text-danger"></i></button>';
        }
      }
    ],
    order: [[0, 'asc']], responsive: true, autoWidth: false,
    language: { emptyTable: 'No grades found.' }
  });

  // ── Sections Table ──
  var sectionsTable = $('#sectionsTable').DataTable({
    ajax: { url: '<?= baseurl('/teacher/api/sections') ?>', dataSrc: 'data', error: function(){ notyf.error('Failed to load sections.'); } },
    columns: [
      { data: 'id' },
      { data: 'name', render: function(d){ return '<span class="font-weight-bold">' + d + '</span>'; } },
      { data: 'grade_name', defaultContent: '<span class="text-muted">—</span>' },
      {
        data: null, orderable: false, searchable: false,
        render: function(d){
          var n = $('<div>').text(d.name).html();
          return '<button class="btn btn-sm btn-light mr-1 btn-edit-section" data-id="' + d.id + '" data-name="' + n + '" title="Edit"><i class="bi bi-pencil-fill text-secondary"></i></button>' +
                 '<button class="btn btn-sm btn-light btn-delete-section" data-id="' + d.id + '" data-name="' + n + '" title="Delete"><i class="bi bi-trash3-fill text-danger"></i></button>';
        }
      }
    ],
    order: [[0, 'asc']], responsive: true, autoWidth: false,
    language: { emptyTable: 'No sections found.' }
  });

  // Populate grade dropdown in create section modal
  function loadGradeOptions(){
    $.get('<?= baseurl('/teacher/api/grades') ?>', function(res){
      var opts = '<option value="">— None —</option>';
      (res.data || []).forEach(function(g){ opts += '<option value="' + g.id + '">' + g.name + '</option>'; });
      $('#createSectionGradeId').html(opts);
    }, 'json');
  }
  $('#createSectionModal').on('show.bs.modal', loadGradeOptions);

  // ── Grade CRUD ──
  $('#createGradeForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#createGradeBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>').prop('disabled', true);
    $.ajax({ url: '<?= baseurl('/teacher/api/grades/create') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify({ name: $('[name="name"]', this).val() }), dataType: 'json',
      success: function(res){ if(res.success){ notyf.success(res.message); gradesTable.ajax.reload(null, false); $('#createGradeModal').modal('hide'); } else notyf.error(res.message); },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('Create').prop('disabled', false); }
    });
  });
  $('#createGradeModal').on('hidden.bs.modal', function(){ $('#createGradeForm')[0].reset(); });

  $(document).on('click', '.btn-edit-grade', function(){
    $('#editGradeId').val($(this).data('id'));
    $('#editGradeName').val($(this).data('name'));
    $('#editGradeModal').modal('show');
  });
  $('#editGradeForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#editGradeBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>').prop('disabled', true);
    $.ajax({ url: '<?= baseurl('/teacher/api/grades/edit') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify({ id: $('#editGradeId').val(), name: $('#editGradeName').val() }), dataType: 'json',
      success: function(res){ if(res.success){ notyf.success(res.message); gradesTable.ajax.reload(null, false); $('#editGradeModal').modal('hide'); } else notyf.error(res.message); },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('Save').prop('disabled', false); }
    });
  });

  $(document).on('click', '.btn-delete-grade', function(){
    $('#deleteGradeId').val($(this).data('id'));
    $('#deleteGradeName').text($(this).data('name'));
    $('#deleteGradeModal').modal('show');
  });
  $('#deleteGradeForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#deleteGradeBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>').prop('disabled', true);
    $.ajax({ url: '<?= baseurl('/teacher/api/grades/delete') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify({ id: $('#deleteGradeId').val() }), dataType: 'json',
      success: function(res){ if(res.success){ notyf.success(res.message); gradesTable.ajax.reload(null, false); $('#deleteGradeModal').modal('hide'); } else notyf.error(res.message); },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('Yes, Delete').prop('disabled', false); }
    });
  });

  // ── Section CRUD ──
  $('#createSectionForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#createSectionBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>').prop('disabled', true);
    $.ajax({ url: '<?= baseurl('/teacher/api/sections/create') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify({ name: $('[name="name"]', this).val(), grade_id: $('#createSectionGradeId').val() }), dataType: 'json',
      success: function(res){ if(res.success){ notyf.success(res.message); sectionsTable.ajax.reload(null, false); $('#createSectionModal').modal('hide'); } else notyf.error(res.message); },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('Create').prop('disabled', false); }
    });
  });
  $('#createSectionModal').on('hidden.bs.modal', function(){ $('#createSectionForm')[0].reset(); });

  $(document).on('click', '.btn-edit-section', function(){
    $('#editSectionId').val($(this).data('id'));
    $('#editSectionName').val($(this).data('name'));
    $('#editSectionModal').modal('show');
  });
  $('#editSectionForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#editSectionBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>').prop('disabled', true);
    $.ajax({ url: '<?= baseurl('/teacher/api/sections/edit') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify({ id: $('#editSectionId').val(), name: $('#editSectionName').val() }), dataType: 'json',
      success: function(res){ if(res.success){ notyf.success(res.message); sectionsTable.ajax.reload(null, false); $('#editSectionModal').modal('hide'); } else notyf.error(res.message); },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('Save').prop('disabled', false); }
    });
  });

  $(document).on('click', '.btn-delete-section', function(){
    $('#deleteSectionId').val($(this).data('id'));
    $('#deleteSectionName').text($(this).data('name'));
    $('#deleteSectionModal').modal('show');
  });
  $('#deleteSectionForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#deleteSectionBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>').prop('disabled', true);
    $.ajax({ url: '<?= baseurl('/teacher/api/sections/delete') ?>', type: 'POST', contentType: 'application/json', data: JSON.stringify({ id: $('#deleteSectionId').val() }), dataType: 'json',
      success: function(res){ if(res.success){ notyf.success(res.message); sectionsTable.ajax.reload(null, false); $('#deleteSectionModal').modal('hide'); } else notyf.error(res.message); },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('Yes, Delete').prop('disabled', false); }
    });
  });

});
</script>
</body>
</html>
