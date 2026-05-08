<!DOCTYPE html>
<html lang="en">
<?php admin_view_layout(['header', 'style']); ?>

<body>
<div id="wrapper">
  <?php admin_view_layout(['sidebar']); ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?php admin_view_layout(['navbar']); ?>
      <div class="container-fluid py-4">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
          <div>
            <h5 class="m-0 mb-1 font-weight-bold"><?= htmlspecialchars($pageTitle) ?></h5>
            <div class="text-muted" style="font-size:13px;"><?= htmlspecialchars($pageSubtitle) ?></div>
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
                      <th>Unit</th>
                      <th>Title</th>
                      <th>Outcome</th>
                      <th>Teacher</th>
                      <th>Pre-Test</th>
                      <th>Post-Test</th>
                      <th style="width:130px;">Actions</th>
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
    <?php admin_view_layout(['footer']); ?>
  </div>
</div>

<!-- ══ CREATE MODAL ══ -->
<div class="modal fade" id="createModuleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#258517,#396619);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-journal-plus mr-2"></i>Add Module</h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <form id="createModuleForm">
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
                <label class="font-weight-bold" style="font-size:.82rem;">Learning Outcome <span class="text-danger">*</span></label>
                <textarea class="form-control" name="outcome" rows="2" placeholder="What students will learn..." required></textarea>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Content <span class="text-danger">*</span></label>
                <textarea class="form-control" name="content" rows="5" placeholder="Module lesson content..." required></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Quiz Question (legacy)</label>
                <input type="text" class="form-control" name="quiz" placeholder="Optional legacy quiz question">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Answer</label>
                <input type="text" class="form-control" name="answer" placeholder="Answer">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Assign Teacher</label>
                <select class="form-control" name="teacher_id">
                  <option value="">— None —</option>
                  <?php foreach ($teachers as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                  <?php endforeach; ?>
                </select>
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

<!-- ══ EDIT MODAL ══ -->
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
                <label class="font-weight-bold" style="font-size:.82rem;">Learning Outcome <span class="text-danger">*</span></label>
                <textarea class="form-control" name="outcome" id="editOutcome" rows="2" required></textarea>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Content <span class="text-danger">*</span></label>
                <textarea class="form-control" name="content" id="editContent" rows="5" required></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Quiz Question (legacy)</label>
                <input type="text" class="form-control" name="quiz" id="editQuiz">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Answer</label>
                <input type="text" class="form-control" name="answer" id="editAnswer">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold" style="font-size:.82rem;">Assign Teacher</label>
                <select class="form-control" name="teacher_id" id="editTeacherId">
                  <option value="">— None —</option>
                  <?php foreach ($teachers as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                  <?php endforeach; ?>
                </select>
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

<!-- ══ DELETE MODAL ══ -->
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

<!-- ══ QUIZ MODAL ══ -->
<div class="modal fade" id="quizModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#396619,#258517);color:#fff;border-radius:16px 16px 0 0;">
        <h5 class="modal-title"><i class="bi bi-patch-question mr-2"></i>Quiz Questions — <span id="quizModuleTitle"></span></h5>
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;font-size:1.4rem;"><span>&times;</span></button>
      </div>
      <div class="modal-body px-4 py-3">
        <input type="hidden" id="quizModuleId">

        <!-- Pre/Post Tabs -->
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

<?php admin_view_layout(['script']); ?>

<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });
var currentQuizType = 'pre';

$(document).ready(function () {

  // ── DataTable ──
  var table = $('#modulesTable').DataTable({
    ajax: { url: '<?= baseurl("/admin/modules/json") ?>', dataSrc: 'data', error: function(){ notyf.error('Failed to load modules.'); } },
    columns: [
      { data: 'unit_number', render: function(d){ return '<span class="badge badge-secondary px-2">Unit ' + d + '</span>'; } },
      { data: 'title', render: function(d){ return '<span class="font-weight-bold">' + d + '</span>'; } },
      { data: 'outcome', render: function(d){ return d.length > 60 ? d.substring(0,60) + '…' : d; } },
      { data: 'teacher_name', render: function(d){ return d || '<span class="text-muted">—</span>'; } },
      { data: 'pre_count',  render: function(d){ return '<span class="badge ' + (d > 0 ? 'badge-success' : 'badge-secondary') + '">' + d + ' Q</span>'; } },
      { data: 'post_count', render: function(d){ return '<span class="badge ' + (d > 0 ? 'badge-info'    : 'badge-secondary') + '">' + d + ' Q</span>'; } },
      {
        data: null, orderable: false, searchable: false,
        render: function(d){
          var t = $('<div>').text(d.title).html();
          return '<button class="btn btn-sm btn-light mr-1" data-toggle="modal" data-target="#editModuleModal"' +
            ' data-id="' + d.id + '" data-title="' + t + '" data-unit="' + d.unit_number + '"' +
            ' data-outcome="' + $('<div>').text(d.outcome).html() + '"' +
            ' title="Edit"><i class="bi bi-pencil-fill text-secondary"></i></button>' +
            '<button class="btn btn-sm btn-light mr-1 btn-quiz" data-id="' + d.id + '" data-title="' + t + '"' +
            ' title="Quiz"><i class="bi bi-patch-question-fill text-success"></i></button>' +
            '<button class="btn btn-sm btn-light" data-toggle="modal" data-target="#deleteModuleModal"' +
            ' data-id="' + d.id + '" data-title="' + t + '"' +
            ' title="Delete"><i class="bi bi-trash3-fill text-danger"></i></button>';
        }
      }
    ],
    order: [[0, 'asc']],
    responsive: true, autoWidth: false, processing: true,
    language: { processing: '<span class="spinner-border spinner-border-sm text-success mr-1"></span> Loading...', emptyTable: 'No modules found.' }
  });

  // ── Create ──
  $('#createModuleForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#createModuleBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Creating...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl("/admin/modules/create") ?>', type: 'POST',
      data: $(this).serialize(), dataType: 'json',
      success: function(res){
        if(res.status === 'success'){ notyf.success(res.message); table.ajax.reload(null, false); $('#createModuleModal').modal('hide'); }
        else notyf.error(res.message);
      },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('<i class="bi bi-check-circle mr-1"></i>Create Module').prop('disabled', false); }
    });
  });

  $('#createModuleModal').on('hidden.bs.modal', function(){ $('#createModuleForm')[0].reset(); });

  // ── Edit — populate ──
  $('#editModuleModal').on('show.bs.modal', function(e){
    var b = $(e.relatedTarget);
    var id = b.data('id');
    $('#editModuleId').val(id);
    $('#editTitle').val(b.data('title'));
    $('#editUnitNumber').val(b.data('unit'));
    $('#editOutcome').val(b.data('outcome'));
    // Fetch full data for content/quiz/answer/teacher
    $.get('<?= baseurl("/admin/modules/json") ?>', function(res){
      var m = res.data.find(function(x){ return x.id == id; });
      if(!m) return;
      $('#editContent').val(m.content);
      $('#editQuiz').val(m.quiz);
      $('#editAnswer').val(m.answer);
      $('#editTeacherId').val(m.teacher_id || '');
    });
  });

  $('#editModuleForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#editModuleBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Saving...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl("/admin/modules/edit") ?>', type: 'POST',
      data: $(this).serialize(), dataType: 'json',
      success: function(res){
        if(res.status === 'success'){ notyf.success(res.message); table.ajax.reload(null, false); $('#editModuleModal').modal('hide'); }
        else notyf.error(res.message);
      },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('<i class="bi bi-check-lg mr-1"></i>Save Changes').prop('disabled', false); }
    });
  });

  // ── Delete ──
  $('#deleteModuleModal').on('show.bs.modal', function(e){
    var b = $(e.relatedTarget);
    $('#deleteModuleId').val(b.data('id'));
    $('#deleteModuleTitle').text(b.data('title'));
  });

  $('#deleteModuleForm').on('submit', function(e){
    e.preventDefault();
    var $btn = $('#deleteModuleBtn').html('<span class="spinner-border spinner-border-sm mr-1"></span>Deleting...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl("/admin/modules/delete") ?>', type: 'POST',
      data: $(this).serialize(), dataType: 'json',
      success: function(res){
        if(res.status === 'success'){ notyf.success(res.message); table.ajax.reload(null, false); $('#deleteModuleModal').modal('hide'); }
        else notyf.error(res.message);
      },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('Yes, Delete').prop('disabled', false); }
    });
  });

  // ── Quiz — open ──
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

  // ── Quiz — tab switch ──
  $('#quizTabs a').on('click', function(e){
    e.preventDefault();
    var type = $(this).data('type');
    currentQuizType = type;
    $('#quizTabs a').removeClass('active');
    $(this).addClass('active');
    loadQuestions($('#quizModuleId').val(), type);
  });

  function loadQuestions(moduleId, testType){
    $('#quizQuestionsContainer').html('<div class="text-center py-3"><span class="spinner-border spinner-border-sm text-success"></span></div>');
    $.get('<?= baseurl("/admin/modules/questions") ?>', { module_id: moduleId, test_type: testType }, function(res){
      renderQuestions(res.data || []);
      updateBadge(testType, (res.data || []).length);
    }, 'json').fail(function(){ notyf.error('Failed to load questions.'); });
  }

  function updateBadge(type, count){
    $('#' + type + '-count-badge').text(count);
  }

  function renderQuestions(questions){
    var html = '';
    if(questions.length === 0){
      html = '<p class="text-muted" style="font-size:.82rem;">No questions yet. Click "Add Question" to start.</p>';
    } else {
      questions.forEach(function(q, i){
        html += buildQuestionRow(i, q);
      });
    }
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

    return '<div class="question-row border rounded p-3 mb-3" data-index="' + i + '">' +
      '<div class="d-flex justify-content-between align-items-center mb-2">' +
        '<span class="font-weight-bold" style="font-size:.82rem;">Question ' + (i+1) + '</span>' +
        '<button type="button" class="btn btn-sm btn-outline-danger remove-question-btn" style="padding:2px 8px;font-size:.75rem;">Remove</button>' +
      '</div>' +
      '<div class="form-group mb-2">' +
        '<input type="text" class="form-control form-control-sm" name="question_text" placeholder="Enter question..." value="' + (q.question_text || '') + '" required>' +
      '</div>' +
      '<div class="row">' + optHtml + '</div>' +
      '<div class="mt-2"><small class="font-weight-bold text-muted" style="font-size:.75rem;">Correct Answer:</small><br>' + correctHtml + '</div>' +
    '</div>';
  }

  // ── Add question row ──
  $('#addQuestionBtn').on('click', function(){
    var count = $('#quizQuestionsContainer .question-row').length;
    var $p = $('<p class="text-muted" style="font-size:.82rem;">');
    $('#quizQuestionsContainer p.text-muted').remove();
    $('#quizQuestionsContainer').append(buildQuestionRow(count, {}));
  });

  // ── Remove question row ──
  $(document).on('click', '.remove-question-btn', function(){
    $(this).closest('.question-row').remove();
    // Re-index
    $('#quizQuestionsContainer .question-row').each(function(i){
      $(this).find('span.font-weight-bold').first().text('Question ' + (i+1));
      $(this).find('input[type="radio"]').each(function(){
        var name = $(this).attr('name');
        $(this).attr('name', 'correct_' + i);
      });
      $(this).attr('data-index', i);
    });
    if($('#quizQuestionsContainer .question-row').length === 0){
      $('#quizQuestionsContainer').html('<p class="text-muted" style="font-size:.82rem;">No questions yet. Click "Add Question" to start.</p>');
    }
  });

  // ── Save questions ──
  $('#saveQuestionsBtn').on('click', function(){
    var moduleId  = $('#quizModuleId').val();
    var testType  = currentQuizType;
    var questions = [];
    var valid     = true;

    $('#quizQuestionsContainer .question-row').each(function(i){
      var $row     = $(this);
      var qText    = $row.find('input[name="question_text"]').val().trim();
      var optA     = $row.find('input[name="option_a"]').val().trim();
      var optB     = $row.find('input[name="option_b"]').val().trim();
      var optC     = $row.find('input[name="option_c"]').val().trim();
      var optD     = $row.find('input[name="option_d"]').val().trim();
      var correct  = $row.find('input[name="correct_' + i + '"]:checked').val();

      if(!qText || !optA || !optB || !optC || !optD || !correct){ valid = false; return false; }
      questions.push({ question_text: qText, option_a: optA, option_b: optB, option_c: optC, option_d: optD, correct_answer: correct });
    });

    if(!valid){ notyf.error('Please fill in all question fields and select a correct answer.'); return; }

    var $btn = $(this).html('<span class="spinner-border spinner-border-sm mr-1"></span>Saving...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl("/admin/modules/questions/save") ?>', type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ module_id: parseInt(moduleId), test_type: testType, questions: questions }),
      dataType: 'json',
      success: function(res){
        if(res.status === 'success'){
          notyf.success(res.message);
          updateBadge(testType, questions.length);
          table.ajax.reload(null, false);
        } else notyf.error(res.message);
      },
      error: function(){ notyf.error('Server error.'); },
      complete: function(){ $btn.html('<i class="bi bi-save mr-1"></i>Save Questions').prop('disabled', false); }
    });
  });

  $('#quizModal').on('hidden.bs.modal', function(){
    $('#quizQuestionsContainer').html('');
    $('#quizModuleId').val('');
    $('#pre-count-badge, #post-count-badge').text('0');
  });

});
</script>

</body>
</html>
