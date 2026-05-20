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

        <div class="mb-4">
          <h5 class="m-0 font-weight-bold"><?= htmlspecialchars($pageTitle) ?></h5>
          <div class="text-muted" style="font-size:13px;">Manage pre-test and post-test questions per module.</div>
        </div>

        <!-- Module Selector -->
        <div class="form-card mb-4">
          <div class="form-group mb-0">
            <label class="font-weight-bold" style="font-size:.82rem;">Select Module</label>
            <select class="form-control" id="moduleSelect" style="max-width:400px;">
              <option value="">— Choose a module —</option>
              <?php foreach ($modules as $m): ?>
                <option value="<?= $m['id'] ?>">Unit <?= $m['unit_number'] ?> — <?= htmlspecialchars($m['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Test Tabs -->
        <div id="testPanel" style="display:none;">
          <ul class="nav nav-tabs mb-3" id="testTabs">
            <li class="nav-item">
              <a class="nav-link active font-weight-bold" data-toggle="tab" href="#preTab" data-type="pre">Pre-Test</a>
            </li>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" data-toggle="tab" href="#postTab" data-type="post">Post-Test</a>
            </li>
          </ul>

          <div class="tab-content">
            <?php foreach (['pre', 'post'] as $type): ?>
            <div class="tab-pane fade <?= $type === 'pre' ? 'show active' : '' ?>" id="<?= $type ?>Tab">
              <div class="form-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <h6 class="font-weight-bold m-0"><?= ucfirst($type) ?>-Test Questions</h6>
                  <button class="btn btn-success btn-sm font-weight-bold btn-add-question" data-type="<?= $type ?>">
                    <i class="bi bi-plus-lg mr-1"></i> Add Question
                  </button>
                </div>

                <?php if ($type === 'post'): ?>
                <div class="form-group mb-3">
                  <label class="font-weight-bold" style="font-size:.82rem;">Passing Rate (%)</label>
                  <input type="number" class="form-control" id="passingRate" min="1" max="100" style="max-width:160px;" placeholder="e.g. 75">
                </div>
                <?php endif; ?>

                <div id="<?= $type ?>Questions"></div>

                <button class="btn btn-primary font-weight-bold mt-3 btn-save-questions" data-type="<?= $type ?>">
                  <i class="bi bi-save mr-1"></i> Save <?= ucfirst($type) ?>-Test
                </button>
              </div>
            </div>
            <?php endforeach; ?>
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
var currentModuleId = null;

function questionHtml(type, idx, q) {
  q = q || {};
  return '<div class="border rounded p-3 mb-3 question-block" data-type="' + type + '" data-idx="' + idx + '">' +
    '<div class="d-flex justify-content-between align-items-center mb-2">' +
      '<strong style="font-size:.82rem;">Question ' + (idx+1) + '</strong>' +
      '<button type="button" class="btn btn-sm btn-outline-danger btn-remove-question"><i class="bi bi-trash"></i></button>' +
    '</div>' +
    '<div class="form-group"><input type="text" class="form-control q-text" placeholder="Question text" value="' + ($('<span>').text(q.question_text||'').html()) + '" required></div>' +
    ['A','B','C','D'].map(function(opt) {
      return '<div class="form-group"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">' + opt + '</span></div>' +
        '<input type="text" class="form-control q-opt-' + opt.toLowerCase() + '" placeholder="Option ' + opt + '" value="' + ($('<span>').text(q['option_'+opt.toLowerCase()]||'').html()) + '" required></div></div>';
    }).join('') +
    '<div class="form-group mb-0"><label style="font-size:.78rem;font-weight:700;">Correct Answer</label>' +
      '<select class="form-control q-correct" style="max-width:120px;">' +
        ['A','B','C','D'].map(function(opt) {
          return '<option value="' + opt + '"' + (q.correct_answer===opt?' selected':'') + '>' + opt + '</option>';
        }).join('') +
      '</select></div>' +
  '</div>';
}

function loadQuestions(moduleId, type) {
  $.getJSON('<?= baseurl('/teacher/tests/questions') ?>?module_id=' + moduleId + '&test_type=' + type, function(res) {
    var container = $('#' + type + 'Questions');
    container.empty();
    if (res.success && res.questions && res.questions.length) {
      res.questions.forEach(function(q, i) { container.append(questionHtml(type, i, q)); });
    }
    if (type === 'post' && res.passing_rate) {
      $('#passingRate').val(res.passing_rate);
    }
  });
}

$('#moduleSelect').on('change', function() {
  currentModuleId = $(this).val();
  if (!currentModuleId) { $('#testPanel').hide(); return; }
  $('#testPanel').show();
  loadQuestions(currentModuleId, 'pre');
  loadQuestions(currentModuleId, 'post');
});

$(document).on('click', '.btn-add-question', function() {
  var type = $(this).data('type');
  var container = $('#' + type + 'Questions');
  var idx = container.find('.question-block').length;
  container.append(questionHtml(type, idx));
});

$(document).on('click', '.btn-remove-question', function() {
  $(this).closest('.question-block').remove();
});

$(document).on('click', '.btn-save-questions', function() {
  if (!currentModuleId) { notyf.error('Please select a module first.'); return; }
  var type = $(this).data('type');
  var questions = [];
  var valid = true;

  $('#' + type + 'Questions .question-block').each(function() {
    var $b = $(this);
    var q = {
      question_text:  $b.find('.q-text').val().trim(),
      option_a:       $b.find('.q-opt-a').val().trim(),
      option_b:       $b.find('.q-opt-b').val().trim(),
      option_c:       $b.find('.q-opt-c').val().trim(),
      option_d:       $b.find('.q-opt-d').val().trim(),
      correct_answer: $b.find('.q-correct').val(),
    };
    if (!q.question_text || !q.option_a || !q.option_b || !q.option_c || !q.option_d) { valid = false; }
    questions.push(q);
  });

  if (!valid) { notyf.error('Please fill in all question fields.'); return; }

  var payload = { module_id: parseInt(currentModuleId), test_type: type, questions: questions };
  if (type === 'post') payload.passing_rate = parseInt($('#passingRate').val()) || null;

  var $btn = $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span>Saving…');
  $.ajax({
    url: '<?= baseurl('/teacher/tests/questions/save') ?>',
    type: 'POST', contentType: 'application/json',
    data: JSON.stringify(payload), dataType: 'json',
    success: function(res) {
      if (res.success) notyf.success(res.message);
      else notyf.error(res.message);
    },
    complete: function() { $btn.prop('disabled', false).html('<i class="bi bi-save mr-1"></i> Save ' + (type==='pre'?'Pre':'Post') + '-Test'); }
  });
});
</script>
</body>
</html>
