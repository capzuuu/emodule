<!DOCTYPE html>
<html lang="en">
<?php admin_view_layout(['header', 'style']); ?>

<body>
<style nonce="<?= csp_nonce() ?>">
/* ════════════════════════════════════════════
   PROFILE PAGE — RESPONSIVE STYLES
   Breakpoints:
     xs  < 480px
     sm  < 576px
     md  < 768px
     lg  < 992px
     xl  ≥ 1200px
   ════════════════════════════════════════════ */

/* ── Profile layout ── */
.profile-page-row {
  display: flex;
  flex-wrap: wrap;
  gap: 0;
}

/* ── Avatar card ── */
.profile-avatar-col {
  width: 100%;
  padding: 0 12px 24px;
}
@media (min-width: 768px) {
  .profile-avatar-col { width: 41.6667%; } /* col-md-5 */
}
@media (min-width: 992px) {
  .profile-avatar-col { width: 33.3333%; } /* col-lg-4 */
}

/* ── Forms col ── */
.profile-forms-col {
  width: 100%;
  padding: 0 12px;
}
@media (min-width: 768px) {
  .profile-forms-col { width: 58.3333%; } /* col-md-7 */
}
@media (min-width: 992px) {
  .profile-forms-col { width: 66.6667%; } /* col-lg-8 */
}

/* ── form-card padding shrinks on small screens ── */
@media (max-width: 575px) {
  .form-card { padding: 16px 14px; }
}
@media (max-width: 375px) {
  .form-card { padding: 12px 10px; }
}

/* ── Section header (icon + title row) ── */
.profile-section-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 1.25rem;
  flex-wrap: nowrap;
}
@media (max-width: 375px) {
  .profile-section-header { gap: 8px; }
  .profile-section-header .section-icon { width: 34px !important; height: 34px !important; }
}

/* ── Avatar circle ── */
.profile-avatar-circle {
  width: 80px; height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg,#258517,#4F9516);
  color: #fff;
  font-size: 1.6rem;
  font-weight: 800;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 14px;
}
@media (max-width: 575px) {
  .profile-avatar-circle { width: 68px; height: 68px; font-size: 1.3rem; }
}

/* ── Info rows inside avatar card ── */
.profile-info-row {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
}
.profile-info-row:last-child { margin-bottom: 0; }
@media (max-width: 375px) {
  .profile-info-row { gap: 8px; }
  .profile-info-icon { width: 30px !important; height: 30px !important; }
}

/* ── Buttons — full width on xs ── */
@media (max-width: 479px) {
  #updateInfoBtn,
  #changePwdBtn,
  #applyFontBtn,
  #resetFontBtn {
    width: 100%;
    justify-content: center;
  }
  .font-action-row {
    flex-direction: column;
  }
}

/* ── Input group eye button ── */
@media (max-width: 375px) {
  .input-group .btn { padding: 6px 10px; }
}

/* ════════════════════════════════════════════
   FONT SIZE STEP SLIDER
   ════════════════════════════════════════════ */
.fs-track-wrap {
  position: relative;
  height: 6px;
  background: #e3e6f0;
  border-radius: 99px;
  margin: 36px 0 18px;
}
.fs-track-fill {
  position: absolute;
  left: 0; top: 0;
  height: 100%;
  border-radius: 99px;
  background: linear-gradient(90deg,#4e73df,#6f8fe8);
  transition: width .2s;
}
.fs-steps {
  position: absolute;
  top: 0; left: 0;
  width: 100%;
  display: flex;
  justify-content: space-between;
  transform: translateY(-50%);
  pointer-events: none;
}
.fs-dot {
  width: 16px; height: 16px;
  border-radius: 50%;
  background: #fff;
  border: 2px solid #e3e6f0;
  cursor: pointer;
  pointer-events: all;
  transition: border-color .15s, background .15s, transform .15s;
  flex-shrink: 0;
}
.fs-dot.active {
  background: #4e73df;
  border-color: #4e73df;
  box-shadow: 0 0 0 4px rgba(78,115,223,.2);
  transform: scale(1.2);
}
/* Larger tap targets on touch screens */
@media (max-width: 768px) {
  .fs-dot { width: 20px; height: 20px; }
}
@media (max-width: 479px) {
  .fs-dot { width: 24px; height: 24px; }
  .fs-track-wrap { margin: 40px 0 20px; }
}

.fs-bubble {
  position: absolute;
  top: -30px;
  transform: translateX(-50%);
  background: #4e73df;
  color: #fff;
  font-size: .7rem;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 20px;
  white-space: nowrap;
  transition: left .2s;
  pointer-events: none;
}
.fs-bubble::after {
  content: '';
  position: absolute;
  bottom: -5px; left: 50%;
  transform: translateX(-50%);
  border: 5px solid transparent;
  border-top-color: #4e73df;
  border-bottom: none;
}
@media (max-width: 479px) {
  .fs-bubble { font-size: .75rem; padding: 3px 10px; top: -32px; }
}

.fs-labels {
  display: flex;
  justify-content: space-between;
  font-size: .7rem;
  color: var(--text-muted, #858796);
  margin-top: 4px;
}
.fs-label-item {
  cursor: pointer;
  padding: 4px 2px; /* bigger tap area */
}
.fs-label-item.active { color: #4e73df; font-weight: 700; }
@media (max-width: 479px) {
  .fs-labels { font-size: .72rem; }
  .fs-label-item { padding: 6px 2px; }
}

/* ── Preview box ── */
.fs-preview-box {
  margin-top: 12px;
  padding: 12px 14px;
  border-radius: 10px;
  background: var(--bg-secondary, #f8f9fc);
  border: 1px solid var(--divider, #e3e6f0);
}
@media (max-width: 479px) {
  .fs-preview-box { padding: 10px 12px; }
}
.fs-preview-label {
  font-size: .68rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--text-muted);
  margin-bottom: 6px;
}
</style>
<div id="wrapper">

  <?php admin_view_layout(['sidebar']); ?>

  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

      <?php admin_view_layout(['navbar']); ?>

      <div class="container-fluid py-4">

        <?php
          $name     = $user['name']       ?? '';
          $email    = $user['email']      ?? '';
          $role     = $user['role']       ?? 'admin';
          $joined   = !empty($user['created_at']) ? date('F d, Y', strtotime($user['created_at'])) : '—';
          $initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $name), 0, 2))));
        ?>

        <div class="row profile-page-row">

          <!-- LEFT: Avatar card -->
          <div class="profile-avatar-col">
            <div class="form-card text-center">

              <div class="profile-avatar-circle">
                <?= htmlspecialchars($initials) ?>
              </div>

              <h5 class="font-weight-bold mb-1" id="profile-display-name"><?= htmlspecialchars($name) ?></h5>
              <div class="text-muted mb-2" style="font-size:0.82rem;"><?= htmlspecialchars($email) ?></div>

              <span class="badge px-3 py-1" style="background:#e8f5e9;color:#258517;font-size:0.75rem;border-radius:20px;">
                <?= ucfirst($role) ?>
              </span>

              <hr style="border-color:var(--divider);margin:16px 0;">

              <div style="text-align:left;">
                <div class="profile-info-row">
                  <div class="profile-info-icon" style="width:36px;height:36px;border-radius:10px;background:#e8f5e9;color:#258517;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-person-fill"></i>
                  </div>
                  <div>
                    <div style="font-size:0.68rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Full Name</div>
                    <div class="font-weight-bold" style="font-size:0.875rem;" id="profile-info-name"><?= htmlspecialchars($name) ?></div>
                  </div>
                </div>

                <div class="profile-info-row">
                  <div class="profile-info-icon" style="width:36px;height:36px;border-radius:10px;background:#e8f5e9;color:#258517;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-envelope-fill"></i>
                  </div>
                  <div style="min-width:0;">
                    <div style="font-size:0.68rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Email</div>
                    <div class="font-weight-bold" style="font-size:0.875rem;word-break:break-all;" id="profile-info-email"><?= htmlspecialchars($email) ?></div>
                  </div>
                </div>

                <div class="profile-info-row">
                  <div class="profile-info-icon" style="width:36px;height:36px;border-radius:10px;background:#e8f5e9;color:#258517;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-shield-fill"></i>
                  </div>
                  <div>
                    <div style="font-size:0.68rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Role</div>
                    <div class="font-weight-bold" style="font-size:0.875rem;"><?= ucfirst($role) ?></div>
                  </div>
                </div>

                <div class="profile-info-row">
                  <div class="profile-info-icon" style="width:36px;height:36px;border-radius:10px;background:#e8f5e9;color:#258517;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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
          <div class="profile-forms-col">

            <!-- Update Info -->
            <div class="form-card mb-4">
              <div class="profile-section-header">
                <div class="section-icon" style="width:40px;height:40px;border-radius:10px;background:#e8f5e9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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
              <div class="profile-section-header">
                <div class="section-icon" style="width:40px;height:40px;border-radius:10px;background:#fff8e1;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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
              <div class="profile-section-header">
                <div class="section-icon" style="width:40px;height:40px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="bi bi-type" style="color:#4e73df;font-size:1rem;"></i>
                </div>
                <div>
                  <div class="font-weight-bold" style="font-size:0.95rem;">Display Settings</div>
                  <div class="text-muted" style="font-size:0.78rem;">Adjust the font size across the interface</div>
                </div>
              </div>

              <label class="font-weight-bold" style="font-size:.82rem;">Font Size</label>

              <!-- Step track -->
              <div class="fs-track-wrap" id="fsTrackWrap">
                <div class="fs-track-fill" id="fsTrackFill"></div>
                <div class="fs-bubble" id="fsBubble">100%</div>
                <div class="fs-steps" id="fsSteps"></div>
              </div>

              <!-- Labels -->
              <div class="fs-labels" id="fsLabels"></div>

              <div class="mt-4 d-flex font-action-row" style="gap:8px;">
                <button type="button" id="applyFontBtn" class="btn btn-primary font-weight-bold">
                  <i class="bi bi-check-lg mr-1"></i> Apply
                </button>
                <button type="button" id="resetFontBtn" class="btn btn-light font-weight-bold">
                  <i class="bi bi-arrow-counterclockwise mr-1"></i> Reset
                </button>
              </div>

              <!-- Preview -->
              <div class="fs-preview-box">
                <div class="fs-preview-label">Preview</div>
                <div id="fontPreviewText">The quick brown fox jumps over the lazy dog.</div>
              </div>

            </div>

          </div>
        </div>

      </div>
    </div>

    <?php admin_view_layout(['footer']); ?>
  </div>
</div>

<?php admin_view_layout(['script']); ?>

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
      url: '<?= baseurl("/admin/profile/update") ?>',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          notyf.success(res.message);
          // Update displayed name in sidebar and card
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
      url: '<?= baseurl("/admin/profile/change-password") ?>',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          notyf.success(res.message);
          $('#changePasswordForm')[0].reset();
          // Reset eye icons
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

  // ── Font size step slider (25 / 50 / 85 / 100) ──
  var FONT_KEY    = 'adminFontSize';
  var STEPS       = [25, 50, 85, 100];
  var DEFAULT_VAL = 100;
  // Map each step → actual px
  var PX_MAP      = { 25: 12, 50: 14, 85: 16, 100: 18 };
  var current     = DEFAULT_VAL;

  var $fill    = $('#fsTrackFill');
  var $bubble  = $('#fsBubble');
  var $steps   = $('#fsSteps');
  var $labels  = $('#fsLabels');
  var $preview = $('#fontPreviewText');

  // Build dots and labels
  STEPS.forEach(function (val, i) {
    var pct = (i / (STEPS.length - 1)) * 100;
    $steps.append('<div class="fs-dot" data-val="' + val + '" style="margin-left:' + (i === 0 ? '-8px' : '') + ';margin-right:' + (i === STEPS.length - 1 ? '-8px' : '') + ';"></div>');
    $labels.append('<span class="fs-label-item" data-val="' + val + '">' + val + '%</span>');
  });

  function posForIndex(i) {
    return (i / (STEPS.length - 1)) * 100;
  }

  function updateUI(val) {
    var idx = STEPS.indexOf(val);
    if (idx === -1) idx = STEPS.indexOf(DEFAULT_VAL);
    var pct = posForIndex(idx);

    // Fill track
    $fill.css('width', pct + '%');

    // Bubble
    $bubble.text(val + '%').css('left', pct + '%');

    // Dots
    $('.fs-dot').each(function (i) {
      $(this).toggleClass('active', i <= idx);
    });

    // Labels
    $('.fs-label-item').each(function () {
      $(this).toggleClass('active', parseInt($(this).data('val')) === val);
    });

    // Preview
    $preview.css('font-size', PX_MAP[val] + 'px');
  }

  function applyFontSize(val) {
    current = val;
    $('html').css('font-size', PX_MAP[val] + 'px');
    updateUI(val);
  }

  // Load saved
  var saved = localStorage.getItem(FONT_KEY);
  var init  = saved !== null && STEPS.indexOf(parseInt(saved)) !== -1 ? parseInt(saved) : DEFAULT_VAL;
  applyFontSize(init);

  // Click dot
  $(document).on('click', '.fs-dot', function () {
    applyFontSize(parseInt($(this).data('val')));
  });

  // Click label
  $(document).on('click', '.fs-label-item', function () {
    applyFontSize(parseInt($(this).data('val')));
  });

  // Apply
  $('#applyFontBtn').on('click', function () {
    localStorage.setItem(FONT_KEY, current);
    notyf.success('Font size set to ' + current + '%.');
  });

  // Reset
  $('#resetFontBtn').on('click', function () {
    localStorage.removeItem(FONT_KEY);
    applyFontSize(DEFAULT_VAL);
    notyf.success('Font size reset to ' + DEFAULT_VAL + '%.');
  });

});
</script>

</body>
</html>
