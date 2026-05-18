<!DOCTYPE html>
<html lang="en">
<?php student_view_layout(['header', 'style']); ?>

<body>
<div id="wrapper">

  <?php student_view_layout(['sidebar']); ?>

  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

      <?php student_view_layout(['navbar']); ?>

      <div class="container-fluid py-4">

        <?php
          $name     = $user['name']       ?? '';
          $email    = $user['email']      ?? '';
          $role     = $user['role']       ?? 'student';
          $joined   = !empty($user['created_at']) ? date('F d, Y', strtotime($user['created_at'])) : '—';
          $initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $name), 0, 2))));
          $picUrl = !empty($user['profile_picture'])
            ? baseurl('/student/profile/picture') . '?t=' . time()
            : null;
        ?>

        <div class="row">

          <!-- LEFT: Avatar card -->
          <div class="col-lg-4 col-md-5 mb-4">
            <div class="form-card text-center">

              <!-- Profile picture upload -->
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

              <span class="badge px-3 py-1" style="background:#e8f5e9;color:#258517;font-size:0.75rem;border-radius:20px;">
                <?= ucfirst($role) ?>
              </span>

              <hr style="border-color:var(--divider);margin:16px 0;">

              <div style="text-align:left;">
                <div class="d-flex align-items-center mb-3" style="gap:12px;">
                  <div style="width:36px;height:36px;border-radius:10px;background:#e8f5e9;color:#258517;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-person-fill"></i>
                  </div>
                  <div>
                    <div style="font-size:0.68rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Full Name</div>
                    <div class="font-weight-bold" style="font-size:0.875rem;" id="profile-info-name"><?= htmlspecialchars($name) ?></div>
                  </div>
                </div>

                <div class="d-flex align-items-center mb-3" style="gap:12px;">
                  <div style="width:36px;height:36px;border-radius:10px;background:#e8f5e9;color:#258517;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-envelope-fill"></i>
                  </div>
                  <div>
                    <div style="font-size:0.68rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Email</div>
                    <div class="font-weight-bold" style="font-size:0.875rem;" id="profile-info-email"><?= htmlspecialchars($email) ?></div>
                  </div>
                </div>

                <div class="d-flex align-items-center mb-3" style="gap:12px;">
                  <div style="width:36px;height:36px;border-radius:10px;background:#e8f5e9;color:#258517;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-shield-fill"></i>
                  </div>
                  <div>
                    <div style="font-size:0.68rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Role</div>
                    <div class="font-weight-bold" style="font-size:0.875rem;"><?= ucfirst($role) ?></div>
                  </div>
                </div>

                <div class="d-flex align-items-center" style="gap:12px;">
                  <div style="width:36px;height:36px;border-radius:10px;background:#e8f5e9;color:#258517;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-calendar-check-fill"></i>
                  </div>
                  <div>
                    <div style="font-size:0.68rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Member Since</div>
                    <div class="font-weight-bold" style="font-size:0.875rem;"><?= $joined ?></div>
                  </div>
                </div>
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
                  <input type="text" class="form-control" name="name" id="infoName"
                    value="<?= htmlspecialchars($name) ?>" required>
                </div>
                <div class="form-group mb-0">
                  <label class="font-weight-bold" style="font-size:.82rem;">Email Address</label>
                  <input type="email" class="form-control" name="email" id="infoEmail"
                    value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <button type="submit" id="updateInfoBtn" class="btn btn-success font-weight-bold mt-3">
                  <i class="bi bi-check-lg mr-1"></i> Save Changes
                </button>
              </form>
            </div>

            <!-- Change Password -->
            <div class="form-card mb-4">
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
                <div class="form-group">
                  <label class="font-weight-bold" style="font-size:.82rem;">Current Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="current_password" id="currentPwd" placeholder="Enter current password" required>
                    <div class="input-group-append">
                      <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="currentPwd">
                        <i class="bi bi-eye"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="font-weight-bold" style="font-size:.82rem;">New Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="new_password" id="newPwd" placeholder="Min. 6 characters" required>
                    <div class="input-group-append">
                      <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="newPwd">
                        <i class="bi bi-eye"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="form-group mb-0">
                  <label class="font-weight-bold" style="font-size:.82rem;">Confirm New Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="confirm_password" id="confirmPwd" placeholder="Repeat new password" required>
                    <div class="input-group-append">
                      <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="confirmPwd">
                        <i class="bi bi-eye"></i>
                      </button>
                    </div>
                  </div>
                  <small id="pwdMatchMsg" class="text-danger d-none">Passwords do not match.</small>
                </div>

                <button type="submit" id="changePwdBtn" class="btn btn-warning font-weight-bold mt-3" style="color:#fff;">
                  <i class="bi bi-lock mr-1"></i> Change Password
                </button>
              </form>
            </div>

            <!-- Display Settings -->
            <div class="form-card">
              <div class="d-flex align-items-center mb-4" style="gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
                  <i class="bi bi-type" style="color:#4e73df;font-size:1rem;"></i>
                </div>
                <div>
                  <div class="font-weight-bold" style="font-size:0.95rem;">Display Settings</div>
                  <div class="text-muted" style="font-size:0.78rem;">Adjust the font size across the interface</div>
                </div>
              </div>

              <label class="font-weight-bold" style="font-size:.82rem;">Font Size</label>

              <div class="fs-track-wrap" id="fsTrackWrap">
                <div class="fs-track-fill" id="fsTrackFill"></div>
                <div class="fs-bubble" id="fsBubble">100%</div>
                <div class="fs-steps" id="fsSteps"></div>
              </div>
              <div class="fs-labels" id="fsLabels"></div>

              <div class="mt-4 d-flex" style="gap:8px;">
                <button type="button" id="applyFontBtn" class="btn btn-success font-weight-bold">
                  <i class="bi bi-check-lg mr-1"></i> Apply
                </button>
                <button type="button" id="resetFontBtn" class="btn btn-light font-weight-bold">
                  <i class="bi bi-arrow-counterclockwise mr-1"></i> Reset
                </button>
              </div>

              <div class="mt-3 p-3 rounded" style="background:var(--bg-secondary,#f8f9fc);border:1px solid var(--divider,#e3e6f0);">
                <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:6px;">Preview</div>
                <div id="fontPreviewText">The quick brown fox jumps over the lazy dog.</div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

    <?php student_view_layout(['footer']); ?>
  </div>
</div>

<?php student_view_layout(['script']); ?>

<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });

$(document).ready(function () {

  // ── Toggle password visibility ──
  $('.toggle-pwd').on('click', function () {
    var targetId = $(this).data('target');
    var $input   = $('#' + targetId);
    var show     = $input.attr('type') === 'password';
    $input.attr('type', show ? 'text' : 'password');
    $(this).find('i').toggleClass('bi-eye bi-eye-slash');
  });

  // ── Live confirm password match check ──
  $('#confirmPwd, #newPwd').on('input', function () {
    var match = $('#newPwd').val() === $('#confirmPwd').val();
    $('#pwdMatchMsg').toggleClass('d-none', match || $('#confirmPwd').val() === '');
  });

  // ── Update profile info ──
  $('#updateInfoForm').on('submit', function (e) {
    e.preventDefault();
    var $btn = $('#updateInfoBtn');
    $btn.html('<span class="spinner-border spinner-border-sm mr-1"></span>Saving...').prop('disabled', true);

    $.ajax({
      url: '<?= baseurl("/student/profile/update") ?>',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          notyf.success(res.message);
          var name = $('#infoName').val();
          $('#profile-display-name').text(name);
          $('#profile-info-name').text(name);
          $('#profile-info-email').text($('#infoEmail').val());
        } else {
          notyf.error(res.message);
        }
      },
      error: function () { notyf.error('Server error. Please try again.'); },
      complete: function () {
        $btn.html('<i class="bi bi-check-lg mr-1"></i> Save Changes').prop('disabled', false);
      }
    });
  });

  // ── Change password ──
  $('#changePasswordForm').on('submit', function (e) {
    e.preventDefault();

    if ($('#newPwd').val() !== $('#confirmPwd').val()) {
      notyf.error('New passwords do not match.');
      return;
    }

    var $btn = $('#changePwdBtn');
    $btn.html('<span class="spinner-border spinner-border-sm mr-1"></span>Updating...').prop('disabled', true);

    $.ajax({
      url: '<?= baseurl("/student/profile/change-password") ?>',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          notyf.success(res.message);
          $('#changePasswordForm')[0].reset();
          $('.toggle-pwd i').removeClass('bi-eye-slash').addClass('bi-eye');
          $('input[type="text"].form-control').attr('type', 'password');
        } else {
          notyf.error(res.message);
        }
      },
      error: function () { notyf.error('Server error. Please try again.'); },
      complete: function () {
        $btn.html('<i class="bi bi-lock mr-1"></i> Change Password').prop('disabled', false);
      }
    });
  });

  // ── Profile picture upload ──
  $('#avatarWrap').on('click', function () { $('#profilePicInput').click(); });

  $('#profilePicInput').on('change', function () {
    var file = this.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) { notyf.error('File must be under 2MB.'); return; }

    var formData = new FormData();
    formData.append('profile_picture', file);

    var $wrap = $('#avatarWrap');
    $wrap.css('opacity', '.5');

    $.ajax({
      url: '<?= baseurl('/student/profile/upload-picture') ?>',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          notyf.success(res.message);
          var serveUrl = '<?= baseurl('/student/profile/picture') ?>?t=' + Date.now();
          if ($('#profilePicImg').length) {
            $('#profilePicImg').attr('src', serveUrl);
          } else {
            $('#profilePicInitials').replaceWith(
              '<img id="profilePicImg" src="' + serveUrl + '" alt="Profile" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #258517;pointer-events:none;">'
            );
          }
        } else {
          notyf.error(res.message);
        }
      },
      error: function () { notyf.error('Upload failed. Try again.'); },
      complete: function () { $wrap.css('opacity', '1'); }
    });
  });

  // ── Font size step slider (25 / 50 / 85 / 100) ──
  var FONT_KEY    = 'studentFontSize';
  var STEPS       = [25, 50, 85, 100];
  var DEFAULT_VAL = 100;
  var PX_MAP      = { 25: 12, 50: 14, 85: 16, 100: 18 };
  var current     = DEFAULT_VAL;

  var $fill    = $('#fsTrackFill');
  var $bubble  = $('#fsBubble');
  var $steps   = $('#fsSteps');
  var $labels  = $('#fsLabels');
  var $preview = $('#fontPreviewText');

  STEPS.forEach(function (val, i) {
    $steps.append('<div class="fs-dot" data-val="' + val + '"></div>');
    $labels.append('<span class="fs-label-item" data-val="' + val + '">' + val + '%</span>');
  });

  function posForIndex(i) { return (i / (STEPS.length - 1)) * 100; }

  function updateUI(val) {
    var idx = STEPS.indexOf(val);
    if (idx === -1) idx = STEPS.indexOf(DEFAULT_VAL);
    var pct = posForIndex(idx);
    $fill.css('width', pct + '%');
    $bubble.text(val + '%').css('left', pct + '%');
    $('.fs-dot').each(function (i) { $(this).toggleClass('active', i <= idx); });
    $('.fs-label-item').each(function () { $(this).toggleClass('active', parseInt($(this).data('val')) === val); });
    $preview.css('font-size', PX_MAP[val] + 'px');
  }

  function applyFontSize(val) {
    current = val;
    $('html').css('font-size', PX_MAP[val] + 'px');
    updateUI(val);
  }

  var saved = localStorage.getItem(FONT_KEY);
  applyFontSize(saved !== null && STEPS.indexOf(parseInt(saved)) !== -1 ? parseInt(saved) : DEFAULT_VAL);

  $(document).on('click', '.fs-dot',        function () { applyFontSize(parseInt($(this).data('val'))); });
  $(document).on('click', '.fs-label-item', function () { applyFontSize(parseInt($(this).data('val'))); });

  $('#applyFontBtn').on('click', function () {
    localStorage.setItem(FONT_KEY, current);
    notyf.success('Font size set to ' + current + '%.');
  });

  $('#resetFontBtn').on('click', function () {
    localStorage.removeItem(FONT_KEY);
    applyFontSize(DEFAULT_VAL);
    notyf.success('Font size reset to ' + DEFAULT_VAL + '%.');
  });

});
</script>

</body>
</html>