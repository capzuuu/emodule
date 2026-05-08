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
            <div class="text-muted" style="font-size:13px;">Manage pre-test and post-test questions for each module.</div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="form-card">
              <div class="table-responsive">
                <table id="testsTable" class="table table-hover w-100" style="font-size:13px;">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Unit</th>
                      <th>Module Title</th>
                      <th>Pre-Test</th>
                      <th>Post-Test</th>
                      <th style="width:80px;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($modules as $i => $m): ?>
                      <tr>
                        <td><span class="badge badge-secondary px-2">#<?= $i + 1 ?></span></td>
                        <td><span class="badge badge-secondary px-2">Unit <?= $m['unit_number'] ?></span></td>
                        <td class="font-weight-bold"><?= htmlspecialchars($m['title']) ?></td>
                        <td id="pre-count-<?= $m['id'] ?>"><span class="badge badge-secondary">Loading…</span></td>
                        <td id="post-count-<?= $m['id'] ?>"><span class="badge badge-secondary">Loading…</span></td>
                        <td>
                          <button class="btn btn-sm btn-light btn-quiz"
                                  data-id="<?= $m['id'] ?>"
                                  data-title="<?= htmlspecialchars($m['title'], ENT_QUOTES) ?>"
                                  title="Manage Questions">
                            <i class="bi bi-patch-question-fill text-success"></i>
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    <?php if (empty($modules)): ?>
                      <tr><td colspan="5" class="text-center text-muted py-4">No modules found. Create a module first.</td></tr>
                    <?php endif; ?>
                  </tbody>
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

<!-- QUIZ MODAL -->
<div class="modal fade" id="quizModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#2d7a4f,#4ba265);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-patch-question mr-2"></i>Quiz Questions — <span id="quizModuleTitle"></span></h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <div class="modal-body px-4 py-3">
        <input type="hidden" id="quizModuleId">
        <ul class="nav nav-tabs mb-3" id="quizTabs">
          <li class="nav-item">
            <a class="nav-link active" id="tab-pre" href="#" data-type="pre">
              <i class="bi bi-clipboard-check mr-1"></i>Pre-Test
              <span class="badge badge-secondary ml-1" id="pre-count-badge">0</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tab-post" href="#" data-type="post">
              <i class="bi bi-clipboard-data mr-1"></i>Post-Test
              <span class="badge badge-secondary ml-1" id="post-count-badge">0</span>
            </a>
          </li>
        </ul>
        <div id="quizQuestionsContainer"></div>
        <button type="button" class="btn btn-outline-success btn-sm mt-2" id="addQuestionBtn">
          <i class="bi bi-plus mr-1"></i>Add Question
        </button>
      </div>
      <div class="modal-footer px-4">
        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success font-weight-bold" id="saveQuestionsBtn">
          <i class="bi bi-save mr-1"></i>Save Questions
        </button>
      </div>
    </div>
  </div>
</div>

<?php teacher_view_layout(['script']); ?>
<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });
var currentQuizType = 'pre';

$(document).ready(function () {

  // Load question counts for all modules
  <?php foreach ($modules as $m): ?>
  $.get('<?= baseurl('/teacher/api/questions') ?>', { module_id: <?= $m['id'] ?> }, function(res){
    if(!res.success) return;
    var pre  = res.counts ? res.counts.pre  : 0;
    var post = res.counts ? res.counts.post : 0;
    $('#pre-count-<?= $m['id'] ?>').html('<span class="badge ' + (pre  > 0 ? 'badge-success' : 'badge-secondary') + '">' + pre  + ' Q</span>');
    $('#post-count-<?= $m['id'] ?>').html('<span class="badge ' + (post > 0 ? 'badge-info'    : 'badge-secondary') + '">' + post + ' Q</span>');
  }, 'json');
  <?php endforeach; ?>

  // Open quiz modal
  $(document).on('click', '.btn-quiz', function(){
    var id    = $(this).data('id');
    var title = $(this).data('title');
    $('#quizModuleId').val(id);
    $('#quizModuleTitle').text(title);
    currentQuizType = 'pre';
    $('#tab-pre').addClass('active');
    $('#tab-post').removeClass('active');
    loadQuestions(id, 'pre');
    $('#quizModal').modal('show');
  });

  // Tab switch
  $('#quizTabs a').on('click', function(e){
    e.preventDefault();
    currentQuizType = $(this).data('type');
    $('#quizTabs a').removeClass('active');
    $(this).addClass('active');
    loadQuestions($('#quizModuleId').val(), currentQuizType);
  });

  function loadQuestions(moduleId, testType){
    $('#quizQuestionsContainer').html('<div class="text-center py-3"><span class="spinner-border spinner-border-sm text-success"></span></div>');
    $.get('<?= baseurl('/teacher/api/questions') ?>', { module_id: moduleId, test_type: testType }, function(res){
      renderQuestions(res.questions || []);
      $('#' + testType + '-count-badge').text((res.questions || []).length);
    }, 'json').fail(function(){ notyf.error('Failed to load questions.'); });
  }

  function renderQuestions(questions){
    if(questions.length === 0){
      $('#quizQuestionsContainer').html('<p class="text-muted" style="font-size:.82rem;">No questions yet. Click "Add Question" to start.</p>');
      return;
    }
    var html = '';
    questions.forEach(function(q, i){ html += buildQuestionRow(i, q); });
    $('#quizQuestionsContainer').html(html);
  }

  function buildQuestionRow(i, q){
    q = q || {};
    var opts = ['A','B','C','D'];
    var optHtml = opts.map(function(o){
      return '<div class="col-md-6"><div class="input-group mb-1">' +
        '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.75rem;font-weight:700;min-width:32px;">' + o + '</span></div>' +
        '<input type="text" class="form-control form-control-sm" name="option_' + o.toLowerCase() + '" placeholder="Option ' + o + '" value="' + (q['option_' + o.toLowerCase()] || '') + '" required>' +
        '</div></div>';
    }).join('');
    var correctHtml = opts.map(function(o){
      return '<div class="form-check form-check-inline">' +
        '<input class="form-check-input" type="radio" name="correct_' + i + '" value="' + o + '"' + (q.correct_answer === o ? ' checked' : '') + ' required>' +
        '<label class="form-check-label" style="font-size:.78rem;">' + o + '</label></div>';
    }).join('');
    return '<div class="q-card question-row" data-index="' + i + '">' +
      '<div class="d-flex justify-content-between align-items-center mb-2">' +
        '<span class="q-num">Question ' + (i+1) + '</span>' +
        '<button type="button" class="btn btn-sm btn-outline-danger remove-question-btn" style="padding:2px 8px;font-size:.75rem;">Remove</button>' +
      '</div>' +
      '<div class="form-group mb-2"><input type="text" class="form-control form-control-sm" name="question_text" placeholder="Enter question..." value="' + (q.question_text || '') + '" required></div>' +
      '<div class="row">' + optHtml + '</div>' +
      '<div class="mt-2"><small class="font-weight-bold text-muted" style="font-size:.75rem;">Correct Answer:</small><br>' + correctHtml + '</div>' +
    '</div>';
  }

  $('#addQuestionBtn').on('click', function(){
    var count = $('#quizQuestionsContainer .question-row').length;
    $('#quizQuestionsContainer p.text-muted').remove();
    $('#quizQuestionsContainer').append(buildQuestionRow(count, {}));
  });

  $(document).on('click', '.remove-question-btn', function(){
    $(this).closest('.question-row').remove();
    $('#quizQuestionsContainer .question-row').each(function(i){
      $(this).find('.q-num').text('Question ' + (i+1));
      $(this).find('input[type="radio"]').attr('name', 'correct_' + i);
      $(this).attr('data-index', i);
    });
    if($('#quizQuestionsContainer .question-row').length === 0){
      $('#quizQuestionsContainer').html('<p class="text-muted" style="font-size:.82rem;">No questions yet. Click "Add Question" to start.</p>');
    }
  });

  $('#saveQuestionsBtn').on('click', function(){
    var moduleId  = $('#quizModuleId').val();
    var testType  = currentQuizType;
    var questions = [];
    var valid     = true;

    $('#quizQuestionsContainer .question-row').each(function(i){
      var $row    = $(this);
      var qText   = $row.find('input[name="question_text"]').val().trim();
      var optA    = $row.find('input[name="option_a"]').val().trim();
      var optB    = $row.find('input[name="option_b"]').val().trim();
      var optC    = $row.find('input[name="option_c"]').val().trim();
      var optD    = $row.find('input[name="option_d"]').val().trim();
      var correct = $row.find('input[name="correct_' + i + '"]:checked').val();
      if(!qText || !optA || !optB || !optC || !optD || !correct){ valid = false; return false; }
      questions.push({ question_text: qText, option_a: optA, option_b: optB, option_c: optC, option_d: optD, correct_answer: correct });
    });

    if(!valid){ notyf.error('Please fill in all question fields and select a correct answer.'); return; }

    var $btn = $(this).html('<span class="spinner-border spinner-border-sm mr-1"></span>Saving...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl('/teacher/api/questions/save') ?>', type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ module_id: parseInt(moduleId), test_type: testType, questions: questions }),
      dataType: 'json',
      success: function(res){
        if(res.success){
          notyf.success(res.message);
          $('#' + testType + '-count-badge').text(questions.length);
          $('#' + testType + '-count-' + moduleId).html('<span class="badge ' + (questions.length > 0 ? (testType==='pre'?'badge-success':'badge-info') : 'badge-secondary') + '">' + questions.length + ' Q</span>');
          $('#quizModal').modal('hide');
        } else notyf.error(res.message);
      },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('<i class="bi bi-save mr-1"></i>Save Questions').prop('disabled', false); }
    });
  });

  $('#quizModal').on('hidden.bs.modal', function(){
    $('#quizQuestionsContainer').html('');
    $('#pre-count-badge, #post-count-badge').text('0');
  });

});
</script>
</body>
</html>
