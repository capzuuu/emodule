<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">

      <div class="modal-header border-0" style="background:linear-gradient(135deg,#258517,#396619);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-person-plus mr-2"></i>Add New User</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="addUserForm" method="POST" action="<?= baseurl('/admin/userAccounts/create') ?>">
        <div class="modal-body px-4 py-3">

          <div class="form-group mb-3">
            <label class="font-weight-bold" style="font-size:.82rem;">Full Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" id="addName" placeholder="Juan Dela Cruz" required>
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold" style="font-size:.82rem;">Email Address <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email" id="addEmail" placeholder="juan@school.edu.ph" required>
            <small id="addEmailFeedback" class="text-danger d-none"></small>
          </div>

          <input type="hidden" name="role" value="teacher">

          <div class="form-group mb-0">
            <label class="font-weight-bold" style="font-size:.82rem;">Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="password" class="form-control" name="password" id="addPassword" placeholder="Min. 6 characters" required>
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary" id="toggleAddPwd">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="text-right mt-1">
              <button type="button" class="btn btn-sm btn-outline-success" id="generatePwdBtn">Generate</button>
            </div>
          </div>

        </div>

        <div class="modal-footer px-4">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" id="addUserSubmitBtn" class="btn btn-success font-weight-bold" disabled>
            <i class="bi bi-check-circle mr-1"></i>Save
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<script nonce="<?= csp_nonce() ?>">
$(function () {
  var $form    = $('#addUserForm');
  var $btn     = $('#addUserSubmitBtn');
  var $email   = $('#addEmail');
  var $pwd     = $('#addPassword');
  var emailDup = false;

  function checkForm() {
    var ok = $.trim($('#addName').val()) !== '' &&
             $.trim($email.val()) !== '' &&
             $.trim($pwd.val()) !== '';
    $btn.prop('disabled', !ok || emailDup);
  }

  $form.on('input change', 'input, select', checkForm);

  $email.on('input', function () {
    var val = $.trim($(this).val());
    emailDup = false;
    $('#addEmailFeedback').addClass('d-none').text('');
    if (!val) { checkForm(); return; }
    $.post('<?= baseurl("/admin/userAccounts/checkEmailDuplicate") ?>', { email: val }, function (res) {
      if (res.isDuplicate) {
        emailDup = true;
        $('#addEmailFeedback').removeClass('d-none').text('Email already exists.');
      }
      checkForm();
    }, 'json');
  });

  $('#toggleAddPwd').on('click', function () {
    var show = $pwd.attr('type') === 'password';
    $pwd.attr('type', show ? 'text' : 'password');
    $(this).find('i').toggleClass('bi-eye bi-eye-slash');
  });

  $('#generatePwdBtn').on('click', function () {
    var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#';
    var p = '';
    for (var i = 0; i < 8; i++) p += chars.charAt(Math.floor(Math.random() * chars.length));
    $pwd.val(p).attr('type', 'text').trigger('input');
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
          setTimeout(function () { $('#addUserModal').modal('hide'); }, 800);
        } else {
          notyf.error(res.message);
          $btn.html('<i class="bi bi-check-circle mr-1"></i>Save').prop('disabled', false);
        }
      },
      error: function () {
        notyf.error('Server error. Please try again.');
        $btn.html('<i class="bi bi-check-circle mr-1"></i>Save').prop('disabled', false);
      }
    });
  });

  $('#addUserModal').on('hidden.bs.modal', function () {
    $form[0].reset();
    emailDup = false;
    $('#addEmailFeedback').addClass('d-none').text('');
    $btn.prop('disabled', true);
    $pwd.attr('type', 'password');
    $('#toggleAddPwd i').removeClass('bi-eye-slash').addClass('bi-eye');
  });
});
</script>
