<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">

      <div class="modal-header border-0" style="background:linear-gradient(135deg,#1565C0,#0d47a1);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-pencil mr-2"></i>Edit Account</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="editUserForm" method="POST" action="<?= baseurl('/admin/userAccounts/edit') ?>">
        <div class="modal-body px-4 py-3">
          <input type="hidden" id="editUserId" name="id">

          <div class="form-group mb-3">
            <label class="font-weight-bold" style="font-size:.82rem;">Full Name</label>
            <input type="text" class="form-control" name="name" id="editName" required>
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold" style="font-size:.82rem;">Email Address</label>
            <input type="email" class="form-control" name="email" id="editEmail" required>
          </div>

          <input type="hidden" name="role" value="teacher">

        </div>

        <div class="modal-footer px-4">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" id="editUserSubmitBtn" class="btn btn-primary font-weight-bold" disabled>
            <i class="bi bi-check-lg mr-1"></i>Save Changes
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<script nonce="<?= csp_nonce() ?>">
$(function () {
  var $form = $('#editUserForm');
  var $btn  = $('#editUserSubmitBtn');

  function checkForm() {
    var ok = $.trim($('#editName').val()) !== '' &&
             $.trim($('#editEmail').val()) !== '';
    $btn.prop('disabled', !ok);
  }

  $form.on('input change', 'input', checkForm);

  $('#editUserModal').on('show.bs.modal', function (e) {
    var b = $(e.relatedTarget);
    $('#editUserId').val(b.data('id'));
    $('#editName').val(b.data('name'));
    $('#editEmail').val(b.data('email'));
    checkForm();
  });

  $('#editUserModal').on('hidden.bs.modal', function () {
    $form[0].reset();
    $btn.html('<i class="bi bi-check-lg mr-1"></i>Save Changes').prop('disabled', true);
  });

  $form.on('submit', function (e) {
    e.preventDefault();
    $btn.html('<span class="spinner-border spinner-border-sm mr-1"></span>Saving...').prop('disabled', true);
    $.ajax({
      url: $form.attr('action'),
      type: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          notyf.success(res.message);
          $('#usersTable').DataTable().ajax.reload(null, false);
          setTimeout(function () { $('#editUserModal').modal('hide'); }, 800);
        } else {
          notyf.error(res.message);
          $btn.html('<i class="bi bi-check-lg mr-1"></i>Save Changes').prop('disabled', false);
        }
      },
      error: function () {
        notyf.error('Server error. Please try again.');
        $btn.html('<i class="bi bi-check-lg mr-1"></i>Save Changes').prop('disabled', false);
      }
    });
  });
});
</script>
