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

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
          <div>
            <h5 class="m-0 mb-1 font-weight-bold"><?= htmlspecialchars($pageTitle) ?></h5>
            <div class="text-muted" style="font-size:13px;">Create and manage your learning modules.</div>
          </div>
          <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#createModuleModal">
            <i class="bi bi-plus mr-1"></i> Add Module
          </button>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="form-card">
              <div class="table-responsive">
                <table id="modulesTable" class="table table-hover w-100" style="font-size:13px;">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Unit</th>
                      <th>Title</th>
                      <th>Outcome</th>
                      <th>Pre-Test</th>
                      <th>Post-Test</th>
                      <th style="width:110px;">Actions</th>
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

<!-- CREATE MODAL -->
<div class="modal fade" id="createModuleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#2d7a4f,#4ba265);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-journal-plus mr-2"></i>Add Module</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <form id="createModuleForm" enctype="multipart/form-data">
        <div class="modal-body px-4 py-3">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title" placeholder="Module title" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Unit Number <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="unit_number" min="1" placeholder="e.g. 1" required>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Learning Outcome</label>
                <textarea class="form-control" name="outcome" rows="2" placeholder="What students will learn..."></textarea>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Content <span class="text-danger">*</span></label>
                <textarea class="form-control" name="content" rows="5" placeholder="Module lesson content..." required></textarea>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Attach PDF (optional)</label>
                <input type="file" class="form-control-file" name="module_file" accept=".pdf">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" id="createModuleBtn" class="btn btn-success font-weight-bold">
            <i class="bi bi-check-circle mr-1"></i>Create Module
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModuleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#1565C0,#0d47a1);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-pencil mr-2"></i>Edit Module</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <form id="editModuleForm">
        <div class="modal-body px-4 py-3">
          <input type="hidden" name="id" id="editModuleId">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title" id="editTitle" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Unit Number <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="unit_number" id="editUnitNumber" min="1" required>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Learning Outcome</label>
                <textarea class="form-control" name="outcome" id="editOutcome" rows="2"></textarea>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Content <span class="text-danger">*</span></label>
                <textarea class="form-control" name="content" id="editContent" rows="5" required></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" id="editModuleBtn" class="btn btn-primary font-weight-bold">
            <i class="bi bi-check-lg mr-1"></i>Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModuleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow text-center" style="border-radius:16px;">
      <div class="modal-header justify-content-center border-0" style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title">Delete Module?</h5>
      </div>
      <form id="deleteModuleForm">
        <div class="modal-body px-4 py-4">
          <input type="hidden" id="deleteModuleId" name="id">
          <div style="font-size:2.5rem;margin-bottom:12px;">🗑️</div>
          <p class="text-muted mb-0" style="font-size:.875rem;">
            Permanently delete <strong id="deleteModuleTitle"></strong>? This cannot be undone.
          </p>
        </div>
        <div class="modal-footer justify-content-center border-0 pb-4">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" id="deleteModuleBtn" class="btn btn-danger font-weight-bold">Yes, Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php teacher_view_layout(['script']); ?>
<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });

$(document).ready(function () {

  var table = $('#modulesTable').DataTable({
    ajax: { url: '<?= baseurl('/teacher/api/modules') ?>', dataSrc: 'data', error: function(){ notyf.error('Failed to load modules.'); } },
    columns: [
      { data: 'id', render: function(d, type, row, meta){ return '<span class="badge badge-secondary px-2">#' + (meta.row + 1) + '</span>'; } },
      { data: 'unit_number', render: function(d){ return '<span class="badge badge-secondary px-2">Unit ' + d + '</span>'; } },
      { data: 'title', render: function(d){ return '<span class="font-weight-bold">' + d + '</span>'; } },
      { data: 'outcome', render: function(d){ return d ? (d.length > 60 ? d.substring(0,60) + '…' : d) : '<span class="text-muted">—</span>'; } },
      { data: 'pre_count',  defaultContent: '0', render: function(d){ return '<span class="badge ' + (d > 0 ? 'badge-success' : 'badge-secondary') + '">' + d + ' Q</span>'; } },
      { data: 'post_count', defaultContent: '0', render: function(d){ return '<span class="badge ' + (d > 0 ? 'badge-info'    : 'badge-secondary') + '">' + d + ' Q</span>'; } },
      {
        data: null, orderable: false, searchable: false,
        render: function(d){
          var t = $('<div>').text(d.title).html();
          return '<button class="btn btn-sm btn-light mr-1 btn-edit" data-id="' + d.id + '" data-title="' + t + '" data-unit="' + d.unit_number + '" data-outcome="' + $('<div>').text(d.outcome||'').html() + '" data-content="' + $('<div>').text(d.content||'').html() + '" title="Edit"><i class="bi bi-pencil-fill text-secondary"></i></button>' +
                 '<button class="btn btn-sm btn-light btn-delete" data-id="' + d.id + '" data-title="' + t + '" title="Delete"><i class="bi bi-trash3-fill text-danger"></i></button>';
        }
      }
    ],
    order: [[0, 'asc']],
    responsive: true, autoWidth: false, processing: true,
    language: { processing: '<span class="spinner-border spinner-border-sm text-success mr-1"></span> Loading...', emptyTable: 'No modules found.' }
  });

  // Create
  $('#createModuleForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#createModuleBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Creating...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl('/teacher/api/modules/create') ?>', type: 'POST',
      data: new FormData(this), processData: false, contentType: false, dataType: 'json',
      success: function(res){
        if(res.success){ notyf.success(res.message); table.ajax.reload(null, false); $('#createModuleModal').modal('hide'); }
        else notyf.error(res.message);
      },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('<i class="bi bi-check-circle mr-1"></i>Create Module').prop('disabled', false); }
    });
  });
  $('#createModuleModal').on('hidden.bs.modal', function(){ $('#createModuleForm')[0].reset(); });

  // Edit — open
  $(document).on('click', '.btn-edit', function(){
    var $b = $(this);
    $('#editModuleId').val($b.data('id'));
    $('#editTitle').val($b.data('title'));
    $('#editUnitNumber').val($b.data('unit'));
    $('#editOutcome').val($b.data('outcome'));
    $('#editContent').val($b.data('content'));
    $('#editModuleModal').modal('show');
  });

  $('#editModuleForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#editModuleBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Saving...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl('/teacher/api/modules/edit') ?>', type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        id: $('#editModuleId').val(),
        title: $('#editTitle').val(),
        unit_number: $('#editUnitNumber').val(),
        outcome: $('#editOutcome').val(),
        content: $('#editContent').val()
      }),
      dataType: 'json',
      success: function(res){
        if(res.success){ notyf.success(res.message); table.ajax.reload(null, false); $('#editModuleModal').modal('hide'); }
        else notyf.error(res.message);
      },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('<i class="bi bi-check-lg mr-1"></i>Save Changes').prop('disabled', false); }
    });
  });

  // Delete — open
  $(document).on('click', '.btn-delete', function(){
    $('#deleteModuleId').val($(this).data('id'));
    $('#deleteModuleTitle').text($(this).data('title'));
    $('#deleteModuleModal').modal('show');
  });

  $('#deleteModuleForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#deleteModuleBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Deleting...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl('/teacher/api/modules/delete') ?>', type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ id: $('#deleteModuleId').val() }),
      dataType: 'json',
      success: function(res){
        if(res.success){ notyf.success(res.message); table.ajax.reload(null, false); $('#deleteModuleModal').modal('hide'); }
        else notyf.error(res.message);
      },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('Yes, Delete').prop('disabled', false); }
    });
  });

});
</script>
</body>
</html>
