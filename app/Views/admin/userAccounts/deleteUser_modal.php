<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow text-center" style="border-radius:16px;">

      <div class="modal-header justify-content-center border-0"
        style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title">Delete Account?</h5>
      </div>

      <form id="deleteUserForm" method="POST" action="<?= baseurl('/admin/userAccounts/delete') ?>">
        <div class="modal-body px-4 py-4">
          <input type="hidden" id="deleteUserId" name="id">
          <p class="text-muted mb-0" style="font-size:.875rem;">
            Permanently remove <strong id="deleteUserName"></strong>? This cannot be undone.
          </p>
        </div>

        <div class="modal-footer justify-content-center border-0 pb-4">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" id="deleteConfirmBtn" class="btn btn-danger font-weight-bold">Yes, Delete</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script nonce="<?= csp_nonce() ?>">
  $(function () {
    $('#deleteUserModal').on('show.bs.modal', function (e) {
      var b = $(e.relatedTarget);
      $('#deleteUserId').val(b.data('id'));
      $('#deleteUserName').text(b.data('name'));
      $('#deleteConfirmBtn').prop('disabled', false).text('Yes, Delete');
    });

    $('#deleteUserForm').on('submit', function (e) {
      e.preventDefault();
      var $btn = $('#deleteConfirmBtn');
      $btn.html('<span class="spinner-border spinner-border-sm mr-1"></span>Deleting...').prop('disabled', true);
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (res) {
          if (res.status === 'success') {
            notyf.success(res.message);
            $('#usersTable').DataTable().ajax.reload(null, false);
            setTimeout(function () { $('#deleteUserModal').modal('hide'); }, 800);
          } else {
            notyf.error(res.message);
            $btn.html('Yes, Delete').prop('disabled', false);
          }
        },
        error: function () {
          notyf.error('Server error. Please try again.');
          $btn.html('Yes, Delete').prop('disabled', false);
        }
      });
    });
  });
</script>