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

        <?php
          $name     = $user['name']       ?? '';
          $email    = $user['email']      ?? '';
          $role     = $user['role']       ?? 'teacher';
          $joined   = !empty($user['created_at']) ? date('F d, Y', strtotime($user['created_at'])) : '—';
          $initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $name), 0, 2))));
          $picUrl = !empty($user['profile_picture'])
            ? baseurl('/teacher/profile/picture') . '?t=' . time()
            : null;
        ?>

        <div class="row">

          <!-- LEFT: Avatar card -->
          <div class="col-lg-4 col-md-5 mb-4">
            <div class="form-card text-center">

              <div style="position:relative;width:90px;height:90px;margin:0 auto 14px;cursor:pointer;" id="avatarWrap" title="Click to change photo">
                <?php if ($picUrl): ?>
                  <img id="profilePicImg" src="<?= htmlspecialchars($picUrl) ?>" alt="Profile"
                       style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #258517;pointer-events:none;">
                <?php else: ?>
                  <div id="profilePicInitials" style="width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg,#258517,#4F9516);color:#fff;font-size:1.6rem;font-weight:800;display:flex;align-items:center;justify-content:center;pointer-events:none;">
                    <?= htmlspecialchars($initials) ?>
                  </div>
                <?php endif; ?>
                <div style="position:absolute;bottom:2px;right:2px;width:26px;height:26px;border-radius:50%;background:#258517;color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,.3);">
                  <i class="bi bi-camera-fill" style="font-size:.7rem;"></i>
                </div>
              </div>
              <input type="file" id="profilePicInput" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none;">

              <h5 class="font-weight-bold mb-1" id="profile-display-name"><?= htmlspecialchars($name) ?></h5>
              <div class="text-muted mb-2" style="font-size:0.82rem;"><?= htmlspecialchars($email) ?></div>
              <span class="badge px-3 py-1" style="background:#e8f5e9;color:#258517;font-size:0.75rem;border-radius:20px;"><?= ucfirst($role) ?></span>

              <hr style="border-color:var(--divider);margin:16px 0;">

              <div style="text-align:left;">
                <?php foreach ([
                  ['bi-person-fill',         'Full Name',    $name,   'profile-info-name'],
                  ['bi-envelope-fill',        'Email',        $email,  'profile-info-email'],
                  ['bi-shield-fill',          'Role',         ucfirst($role), null],
                  ['bi-calendar-check-fill',  'Member Since', $joined, null],
                ] as [$icon, $label, $value, $id]): ?>
                <div class="d-flex align-items-center mb-3" style="gap:12px;">
                  <div style="width:36px;height:36px;border-radius:10px;background:#e8f5e9;color:#258517;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi <?= $icon ?>"></i>
                  </div>
                  <div>
                    <div style="font-size:0.68rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;"><?= $label ?></div>
                    <div class="font-weight-bold" style="font-size:0.875rem;"<?= $id ? ' id="'.$id.'"' : '' ?>><?= htmlspecialchars($value) ?></div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>

            </div>
          </div>

          <!-- RIGHT: Forms -->
          <div class="col-lg-8 col-md-7">

            <!-- Update Info -->
            <div class="form-card mb-4">
              <div class="d-flex align-items-center mb-4" style="gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:#e8f5e9;display:flex;align-items:center;justify-content:center;">
                  <i class="bi bi-pencil-fill" style="color:#258517;font-size:1rem;"></i>
                </div>
                <div>
                  <div class="font-weight-bold" style="font-size:0.95rem;">Edit Profile</div>
                  <div class="text-muted" style="font-size:0.78rem;">Update your name and email address</div>
                </div>
              </div>
              <form id="updateInfoForm">
                <div class="form-group">
                  <label class="font-weight-bold" style="font-size:.82rem;">Full Name</label>
                  <input type="text" class="form-control" name="name" id="infoName" value="<?= htmlspecialchars($name) ?>" required>
                </div>
                <div class="form-group mb-0">
                  <label class="font-weight-bold" style="font-size:.82rem;">Email Address</label>
                  <input type="email" class="form-control" name="email" id="infoEmail" value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <button type="submit" id="updateInfoBtn" class="btn btn-success font-weight-bold mt-3">
                  <i class="bi bi-check-lg mr-1"></i> Save Changes
                </button>
              </form>
            </div>

            <!-- Change Password -->
            <div class="form-card">
              <div class="d-flex align-items-center mb-4" style="gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:#fff8e1;display:flex;align-items:center;justify-content:center;">
                  <i class="bi bi-lock-fill" style="color:#e65100;font-size:1rem;"></i>
                </div>
                <div>
                  <div class="font-weight-bold" style="font-size:0.95rem;">Change Password</div>
                  <div class="text-muted" style="font-size:0.78rem;">Use a strong password of at least 6 characters</div>
                </div>
              </div>
              <form id="changePasswordForm">
                <?php foreach ([['currentPwd','current_password','Enter current password'],['newPwd','new_password','Min. 6 characters'],['confirmPwd','confirm_password','Repeat new password']] as [$id,$name,$ph]): ?>
                <div class="form-group <?= $id==='confirmPwd'?'mb-0':'' ?>">
                  <label class="font-weight-bold" style="font-size:.82rem;"><?= $id==='currentPwd'?'Current':($id==='newPwd'?'New':'Confirm New') ?> Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="<?= $name ?>" id="<?= $id ?>" placeholder="<?= $ph ?>" required>
                    <div class="input-group-append">
                      <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="<?= $id ?>"><i class="bi bi-eye"></i></button>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
                <small id="pwdMatchMsg" class="text-danger d-none">Passwords do not match.</small>
                <button type="submit" id="changePwdBtn" class="btn btn-warning font-weight-bold mt-3" style="color:#fff;">
                  <i class="bi bi-lock mr-1"></i> Change Password
                </button>
              </form>
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

$(document).ready(function () {

  $('.toggle-pwd').on('click', function () {
    var $input = $('#' + $(this).data('target'));
    $input.attr('type', $input.attr('type') === 'password' ? 'text' : 'password');
    $(this).find('i').toggleClass('bi-eye bi-eye-slash');
  });

  $('#confirmPwd, #newPwd').on('input', function () {
    $('#pwdMatchMsg').toggleClass('d-none', $('#newPwd').val() === $('#confirmPwd').val() || $('#confirmPwd').val() === '');
  });

  $('#updateInfoForm').on('submit', function (e) {
    e.preventDefault();
    var $btn = $('#updateInfoBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Saving...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl("/teacher/profile/update") ?>', type: 'POST', data: $(this).serialize(), dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          notyf.success(res.message);
          var name = $('#infoName').val();
          $('#profile-display-name, #profile-info-name').text(name);
          $('#profile-info-email').text($('#infoEmail').val());
        } else notyf.error(res.message);
      },
      error: function () { notyf.error('Server error. Please try again.'); },
      complete: function () { $btn.html('<i class="bi bi-check-lg mr-1"></i> Save Changes').prop('disabled', false); }
    });
  });

  $('#changePasswordForm').on('submit', function (e) {
    e.preventDefault();
    if ($('#newPwd').val() !== $('#confirmPwd').val()) { notyf.error('New passwords do not match.'); return; }
    var $btn = $('#changePwdBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Updating...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl("/teacher/profile/change-password") ?>', type: 'POST', data: $(this).serialize(), dataType: 'json',
      success: function (res) {
        if (res.status === 'success') { notyf.success(res.message); $('#changePasswordForm')[0].reset(); }
        else notyf.error(res.message);
      },
      error: function () { notyf.error('Server error. Please try again.'); },
      complete: function () { $btn.html('<i class="bi bi-lock mr-1"></i> Change Password').prop('disabled', false); }
    });
  });

  $('#avatarWrap').on('click', function () { $('#profilePicInput').click(); });

  $('#profilePicInput').on('change', function () {
    var file = this.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) { notyf.error('File must be under 2MB.'); return; }
    var fd = new FormData();
    fd.append('profile_picture', file);
    $('#avatarWrap').css('opacity', '.5');
    $.ajax({
      url: '<?= baseurl('/teacher/profile/upload-picture') ?>', type: 'POST',
      data: fd, processData: false, contentType: false, dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          notyf.success(res.message);
          var url = '<?= baseurl('/teacher/profile/picture') ?>?t=' + Date.now();
          if ($('#profilePicImg').length) { $('#profilePicImg').attr('src', url); }
          else { $('#profilePicInitials').replaceWith('<img id="profilePicImg" src="' + url + '" alt="Profile" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #258517;pointer-events:none;">'); }
        } else notyf.error(res.message);
      },
      error: function () { notyf.error('Upload failed. Try again.'); },
      complete: function () { $('#avatarWrap').css('opacity', '1'); }
    });
  });

});
</script>

</body>
</html>
