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
            <div class="text-muted" style="font-size:13px;">Create and manage your learning modules.</div>
          </div>
          <button class="btn btn-success font-weight-bold" id="btnAddModule">
            <i class="bi bi-plus-lg mr-1"></i> New Module
          </button>
        </div>

        <div class="form-card" id="modulesContainer">
          <div class="table-responsive">
            <table class="table table-hover mb-0" id="modulesTable">
              <thead>
                <tr>
                  <th>Unit</th>
                  <th>Title</th>
                  <th>Outcome</th>
                  <th>Pre</th>
                  <th>Post</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="modulesTbody">
                <tr><td colspan="6" class="text-center text-muted py-4">Loading…</td></tr>
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

function loadModules() {
  $.getJSON('<?= baseurl('/teacher/modules/json') ?>', function(res) {
    var tbody = $('#modulesTbody');
    tbody.empty();
    if (!res.success || !res.data.length) {
      tbody.html('<tr><td colspan="6" class="text-center text-muted py-4">No modules yet. Click "New Module" to create one.</td></tr>');
      return;
    }
    res.data.forEach(function(m) {
      tbody.append(
        '<tr>' +
        '<td><span class="badge badge-secondary">Unit ' + m.unit_number + '</span></td>' +
        '<td class="font-weight-bold">' + $('<span>').text(m.title).html() + '</td>' +
        '<td class="text-muted">' + $('<span>').text((m.outcome||'').substring(0,60) + ((m.outcome||'').length>60?'…':'')).html() + '</td>' +
        '<td>' + (m.pre_count||0) + ' Q</td>' +
        '<td>' + (m.post_count||0) + ' Q</td>' +
        '<td>' +
          '<button class="btn btn-sm btn-outline-primary mr-1 btn-edit-module" data-id="' + m.id + '" data-title="' + $('<span>').text(m.title).html() + '" data-outcome="' + $('<span>').text(m.outcome||'').html() + '" data-unit="' + m.unit_number + '"><i class="bi bi-pencil"></i></button>' +
          '<button class="btn btn-sm btn-outline-danger btn-delete-module" data-id="' + m.id + '" data-title="' + $('<span>').text(m.title).html() + '"><i class="bi bi-trash"></i></button>' +
        '</td>' +
        '</tr>'
      );
    });
  });
}

$(document).ready(function() {
  loadModules();

  $('#btnAddModule').on('click', function() {
    $('#moduleModalTitle').text('New Module');
    $('#moduleForm')[0].reset();
    $('#moduleId').val('');
    $('#moduleModal').modal('show');
  });

  $(document).on('click', '.btn-edit-module', function() {
    var $btn = $(this);
    $('#moduleModalTitle').text('Edit Module');
    $('#moduleId').val($btn.data('id'));
    $('#moduleTitle').val($btn.data('title'));
    $('#moduleOutcome').val($btn.data('outcome'));
    $('#moduleUnit').val($btn.data('unit'));
    $('#moduleModal').modal('show');
  });

  $(document).on('click', '.btn-delete-module', function() {
    var id    = $(this).data('id');
    var title = $(this).data('title');
    if (!confirm('Delete module "' + title + '"? This cannot be undone.')) return;
    $.ajax({
      url: '<?= baseurl('/teacher/modules/delete') ?>',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ id: id }),
      dataType: 'json',
      success: function(res) {
        if (res.success) { notyf.success(res.message); loadModules(); }
        else notyf.error(res.message);
      }
    });
  });

  $('#moduleForm').on('submit', function(e) {
    e.preventDefault();
    var id = $('#moduleId').val();
    var payload = {
      id:          id ? parseInt(id) : undefined,
      title:       $('#moduleTitle').val(),
      outcome:     $('#moduleOutcome').val(),
      content:     $('#moduleContent').val(),
      unit_number: parseInt($('#moduleUnit').val()),
    };
    var url = id ? '<?= baseurl('/teacher/modules/edit') ?>' : '<?= baseurl('/teacher/modules/create') ?>';
    var $btn = $('#moduleSaveBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span>Saving…');

    if (!id) {
      var fd = new FormData(this);
      $.ajax({ url: url, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json',
        success: function(res) {
          if (res.success) { notyf.success(res.message); $('#moduleModal').modal('hide'); loadModules(); }
          else notyf.error(res.message);
        },
        complete: function() { $btn.prop('disabled', false).html('<i class="bi bi-check-lg mr-1"></i> Save'); }
      });
    } else {
      $.ajax({ url: url, type: 'POST', contentType: 'application/json', data: JSON.stringify(payload), dataType: 'json',
        success: function(res) {
          if (res.success) { notyf.success(res.message); $('#moduleModal').modal('hide'); loadModules(); }
          else notyf.error(res.message);
        },
        complete: function() { $btn.prop('disabled', false).html('<i class="bi bi-check-lg mr-1"></i> Save'); }
      });
    }
  });
});
</script>

<!-- Module Modal -->
<div class="modal fade" id="moduleModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold" id="moduleModalTitle">New Module</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="moduleForm" enctype="multipart/form-data">
        <input type="hidden" id="moduleId" name="id">
        <div class="modal-body">
          <div class="form-group">
            <label class="font-weight-bold" style="font-size:.82rem;">Unit Number</label>
            <input type="number" class="form-control" id="moduleUnit" name="unit_number" min="1" required>
          </div>
          <div class="form-group">
            <label class="font-weight-bold" style="font-size:.82rem;">Title</label>
            <input type="text" class="form-control" id="moduleTitle" name="title" required>
          </div>
          <div class="form-group">
            <label class="font-weight-bold" style="font-size:.82rem;">Learning Outcome</label>
            <input type="text" class="form-control" id="moduleOutcome" name="outcome">
          </div>
          <div class="form-group">
            <label class="font-weight-bold" style="font-size:.82rem;">Content</label>
            <textarea class="form-control" id="moduleContent" name="content" rows="6" required></textarea>
          </div>
          <div class="form-group mb-0" id="fileGroup">
            <label class="font-weight-bold" style="font-size:.82rem;">Attach PDF (optional)</label>
            <input type="file" class="form-control-file" name="module_file" accept=".pdf">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success font-weight-bold" id="moduleSaveBtn">
            <i class="bi bi-check-lg mr-1"></i> Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
