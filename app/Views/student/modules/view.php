<!DOCTYPE html>
<html lang="en">
<?php student_view_layout(['header', 'style']); ?>
<style nonce="<?= csp_nonce() ?>">
  .module-hero {
    background: linear-gradient(135deg, #0f2410 0%, #1a4a1f 60%, #258517 100%);
    border-radius: 20px; padding: 28px 32px; color: #fff;
    margin-bottom: 28px; position: relative; overflow: hidden;
  }
  .module-hero::after {
    content: ''; position: absolute; top: -50px; right: -30px;
    width: 180px; height: 180px; border-radius: 50%;
    background: rgba(255,255,255,0.04);
  }
  .module-hero-meta { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap; }
  .module-hero-badge { font-size: .65rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; background: rgba(255,255,255,0.12); color: rgba(255,255,255,.9); }
  .module-hero-status { font-size: .68rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; display: flex; align-items: center; gap: 5px; }
  .module-hero-status.completed { background: rgba(28,200,138,.2); color: #4ade80; }
  .module-hero-status.available { background: rgba(255,255,255,.12); color: rgba(255,255,255,.85); }

  .module-tabs { display: flex; gap: 4px; background: #fff; border-radius: 14px; padding: 5px; box-shadow: 0 2px 16px rgba(26,28,25,.08); margin-bottom: 24px; position: sticky; top: 60px; z-index: 50; }
  .module-tab { flex: 1; padding: 10px 16px; border-radius: 10px; font-size: .8rem; font-weight: 700; text-align: center; cursor: pointer; transition: .2s; border: none; background: none; color: #5C6359; display: flex; align-items: center; justify-content: center; gap: 6px; }
  .module-tab.active { background: linear-gradient(135deg,#258517,#4F9516); color: #fff; box-shadow: 0 4px 12px rgba(37,133,23,.3); }
  .module-tab:hover:not(.active) { background: #f0f7ee; color: #258517; }
  .module-tab .tab-count { font-size: .6rem; background: rgba(255,255,255,.25); padding: 1px 6px; border-radius: 10px; font-weight: 700; }
  .module-tab:not(.active) .tab-count { background: #e8f0e9; color: #258517; }

  .lesson-card { background: #fff; border-radius: 18px; border: 1.5px solid #e8f0e9; padding: 32px; line-height: 1.85; font-size: .9rem; color: #1A1C19; }
  .lesson-outcome-box { background: linear-gradient(135deg,#f0fdf4,#e8f5e9); border-left: 4px solid #258517; border-radius: 0 12px 12px 0; padding: 16px 20px; margin-bottom: 28px; font-size: .85rem; color: #1a4a1f; }
  .lesson-outcome-box strong { display: block; font-size: .7rem; text-transform: uppercase; letter-spacing: .6px; color: #258517; margin-bottom: 4px; }

  /* PDF Viewer */
  .pdf-viewer-wrap { background: #1e1e1e; border-radius: 14px; overflow: hidden; margin-bottom: 20px; border: 1px solid #2d2d2d; }
  .pdf-toolbar { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; background: #2d2d2d; flex-wrap: wrap; gap: 8px; }
  .pdf-toolbar-left { display: flex; align-items: center; gap: 8px; }
  .pdf-toolbar-title { color: #e5e7eb; font-size: .78rem; font-weight: 600; }
  .pdf-toolbar-right { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
  .pdf-tool-btn { display: flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 8px; border: none; font-size: .72rem; font-weight: 600; cursor: pointer; transition: .15s; background: rgba(255,255,255,.08); color: #e5e7eb; text-decoration: none; }
  .pdf-tool-btn:hover { background: rgba(255,255,255,.15); color: #fff; }
  .pdf-tool-btn.primary { background: #258517; color: #fff; }
  .pdf-tool-btn.primary:hover { background: #1a5e10; }
  .pdf-page-nav { display: flex; align-items: center; gap: 6px; background: rgba(255,255,255,.06); border-radius: 8px; padding: 4px 10px; color: #e5e7eb; font-size: .72rem; font-weight: 600; }
  .pdf-page-nav button { background: none; border: none; color: #e5e7eb; cursor: pointer; padding: 2px 6px; border-radius: 4px; transition: .15s; font-size: .85rem; line-height: 1; }
  .pdf-page-nav button:hover { background: rgba(255,255,255,.15); }
  .pdf-page-nav button:disabled { opacity: .3; cursor: not-allowed; }
  .pdf-canvas-wrap { overflow-y: auto; background: #525659; display: flex; flex-direction: column; align-items: center; padding: 16px; gap: 12px; height: 580px; }
  .pdf-canvas-wrap canvas { box-shadow: 0 4px 20px rgba(0,0,0,.4); max-width: 100%; }
  .pdf-loading { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 200px; color: #9ca3af; gap: 12px; }
  .pdf-fullscreen { position: fixed; inset: 0; z-index: 9999; background: #111; display: flex; flex-direction: column; }
  .pdf-fullscreen .pdf-toolbar { border-radius: 0; }
  .pdf-fullscreen .pdf-canvas-wrap { flex: 1; height: 100% !important; }

  /* Quiz */
  .quiz-card { background: #fff; border-radius: 18px; border: 1.5px solid #e8f0e9; padding: 28px; }
  .quiz-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1.5px solid #f0f4f0; }
  .quiz-header-title { font-size: 1rem; font-weight: 800; color: #1A1C19; }
  .quiz-counter { font-size: .75rem; color: #5C6359; font-weight: 600; }
  .question-card { background: #fafcfa; border-radius: 14px; border: 1.5px solid #e8f0e9; padding: 20px; margin-bottom: 16px; transition: .2s; }
  .question-card.answered { border-color: #a7f3d0; background: #f0fdf4; }
  .question-num { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: #258517; color: #fff; font-size: .7rem; font-weight: 800; flex-shrink: 0; margin-right: 10px; }
  .question-text { font-size: .875rem; font-weight: 700; color: #1A1C19; line-height: 1.5; }
  .option-row { display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 10px; border: 1.5px solid #e8f0e9; background: #fff; cursor: pointer; transition: .15s; margin-top: 8px; font-size: .83rem; color: #1A1C19; }
  .option-row:hover { border-color: #258517; background: #f0fdf4; }
  .option-row.selected { border-color: #258517; background: #e8f5e9; font-weight: 600; }
  .option-row.correct  { border-color: #059669; background: #d1fae5; font-weight: 600; }
  .option-row.wrong    { border-color: #dc2626; background: #fef2f2; }
  .option-letter { width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: .72rem; font-weight: 800; background: #f0f4f0; color: #5C6359; transition: .15s; }
  .option-row.selected .option-letter { background: #258517; color: #fff; }
  .option-row.correct  .option-letter { background: #059669; color: #fff; }
  .option-row.wrong    .option-letter { background: #dc2626; color: #fff; }
  .quiz-progress-dots { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 20px; }
  .quiz-dot { width: 10px; height: 10px; border-radius: 50%; background: #e8f0e9; transition: .2s; }
  .quiz-dot.answered { background: #258517; }
  .quiz-submit-btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 13px 28px; border-radius: 12px; background: linear-gradient(135deg,#258517,#4F9516); color: #fff; font-size: .875rem; font-weight: 700; border: none; cursor: pointer; transition: .2s; box-shadow: 0 4px 16px rgba(37,133,23,.3); width: 100%; margin-top: 8px; }
  .quiz-submit-btn:hover { box-shadow: 0 6px 24px rgba(37,133,23,.4); transform: translateY(-1px); }
  .quiz-submit-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }
  .result-overlay { background: #fff; border-radius: 18px; border: 1.5px solid #e8f0e9; padding: 40px 32px; text-align: center; margin-top: 20px; }
  .result-score-circle { width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 900; margin: 0 auto 16px; }
  .result-score-circle.pass { background: linear-gradient(135deg,#d1fae5,#a7f3d0); color: #059669; }
  .result-score-circle.fail { background: linear-gradient(135deg,#fef3c7,#fde68a); color: #d97706; }
</style>

<body>
<div id="wrapper">
  <?php student_view_layout(['sidebar']); ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?php student_view_layout(['navbar']); ?>
      <div class="container-fluid py-4">

        <a href="<?= baseurl('/student/modules') ?>" class="btn btn-sm btn-outline-secondary mb-3" style="border-radius:10px;">
          <i class="bi bi-arrow-left mr-1"></i> Back to Modules
        </a>

        <div class="module-hero">
          <div style="position:relative;z-index:1;">
            <div class="module-hero-meta">
              <span class="module-hero-badge">Unit <?= $module['unit_number'] ?></span>
              <span class="module-hero-status <?= $status ?>">
                <?php if ($status === 'completed'): ?>
                  <i class="bi bi-check-circle-fill"></i> Completed · <?= $progress[$module['id']]['quiz_score'] ?? 0 ?>%
                <?php else: ?>
                  <i class="bi bi-play-circle-fill"></i> In Progress
                <?php endif; ?>
              </span>
            </div>
            <h4 class="m-0 font-weight-bold" style="line-height:1.3;"><?= htmlspecialchars($module['title']) ?></h4>
            <?php if (!empty($module['outcome'])): ?>
              <div style="font-size:.82rem;opacity:.75;margin-top:6px;max-width:600px;"><?= htmlspecialchars($module['outcome']) ?></div>
            <?php endif; ?>
          </div>
        </div>

        <?php
          $lessonUnlocked = $status === 'completed' || $preDone || empty($preQuestions);
          $postUnlocked   = $status === 'completed' || $lessonDone;
          // Default active tab
          if ($status === 'completed') {
            $activeTab = 'pretest';
          } elseif (!empty($preQuestions)) {
            $activeTab = 'pretest';
          } else {
            $activeTab = 'content';
          }
        ?>

        <div class="module-tabs">
          <?php if (!empty($preQuestions)): ?>
          <button class="module-tab <?= $activeTab === 'pretest' ? 'active' : '' ?>"
                  data-tab="pretest" id="tab-btn-pretest">
            <i class="bi bi-clipboard-check"></i> Pre-Test
            <span class="tab-count"><?= count($preQuestions) ?>Q</span>
          </button>
          <?php endif; ?>

          <button class="module-tab <?= $activeTab === 'content' ? 'active' : '' ?>"
                  data-tab="content" id="tab-btn-content"
                  <?= !$lessonUnlocked ? 'data-locked="1"' : '' ?>>
            <i class="bi bi-book"></i> Lesson
            <?php if (!$lessonUnlocked): ?>
              <span style="font-size:.6rem;opacity:.55;margin-left:2px;"><i class="bi bi-lock-fill"></i></span>
            <?php endif; ?>
          </button>

          <?php if (!empty($postQuestions)): ?>
          <button class="module-tab"
                  data-tab="posttest" id="tab-btn-posttest"
                  <?= !$postUnlocked ? 'data-locked="1"' : '' ?>>
            <i class="bi bi-clipboard-data"></i> Post-Test
            <span class="tab-count"><?= count($postQuestions) ?>Q</span>
            <?php if (!$postUnlocked): ?>
              <span style="font-size:.6rem;opacity:.55;margin-left:2px;"><i class="bi bi-lock-fill"></i></span>
            <?php endif; ?>
          </button>
          <?php endif; ?>
        </div>

        <!-- LESSON TAB -->
        <div id="tab-content" class="tab-pane-custom" <?= $activeTab !== 'content' ? 'style="display:none;"' : '' ?>>
          <div class="lesson-card">
            <?php if (!empty($module['outcome'])): ?>
              <div class="lesson-outcome-box">
                <strong>Learning Outcome</strong>
                <?= htmlspecialchars($module['outcome']) ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($module['file_path'])): ?>
              <div class="pdf-viewer-wrap" id="pdfViewerWrap">
                <div class="pdf-toolbar">
                  <div class="pdf-toolbar-left">
                    <i class="bi bi-file-earmark-pdf" style="color:#f87171;font-size:1rem;"></i>
                    <span class="pdf-toolbar-title"><?= htmlspecialchars($module['title']) ?></span>
                  </div>
                  <div class="pdf-toolbar-right">
                    <div class="pdf-page-nav">
                      <button id="pdfPrev" disabled><i class="bi bi-chevron-left"></i></button>
                      <span id="pdfPageInfo">— / —</span>
                      <button id="pdfNext" disabled><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <a href="<?= baseurl('/student/modules/' . $module['id'] . '/pdf') ?>" target="_blank" class="pdf-tool-btn">
                      <i class="bi bi-box-arrow-up-right"></i> Open
                    </a>
                    <a href="<?= baseurl('/student/modules/' . $module['id'] . '/pdf') ?>" download class="pdf-tool-btn">
                      <i class="bi bi-download"></i> Download
                    </a>
                    <button class="pdf-tool-btn primary" id="pdfMaxBtn">
                      <i class="bi bi-fullscreen" id="pdfMaxIcon"></i> Maximize
                    </button>
                  </div>
                </div>
                <div class="pdf-canvas-wrap" id="pdfCanvasWrap">
                  <div class="pdf-loading" id="pdfLoading">
                    <div class="spinner-border text-light" style="width:2rem;height:2rem;"></div>
                    <span style="font-size:.82rem;color:#d1d5db;">Loading PDF...</span>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <?php if (!empty($module['content'])): ?>
              <div style="white-space:pre-wrap;line-height:1.85;font-size:.9rem;"><?= nl2br(htmlspecialchars($module['content'])) ?></div>
            <?php endif; ?>

            <?php if ($status !== 'completed' && !empty($postQuestions)): ?>
              <div class="mt-4 pt-3" style="border-top:1.5px solid #e8f0e9;">
                <?php if ($lessonDone): ?>
                  <div class="d-flex align-items-center gap-2" style="gap:10px;">
                    <span style="display:inline-flex;align-items:center;gap:6px;background:#d1fae5;color:#059669;font-size:.82rem;font-weight:700;padding:8px 18px;border-radius:10px;">
                      <i class="bi bi-check-circle-fill"></i> Lesson completed — Post-Test is now unlocked
                    </span>
                    <button class="btn btn-success font-weight-bold" id="goToPostBtn" style="border-radius:10px;">
                      <i class="bi bi-arrow-right mr-1"></i> Go to Post-Test
                    </button>
                  </div>
                <?php else: ?>
                  <div class="d-flex align-items-center" style="gap:12px;flex-wrap:wrap;">
                    <div style="font-size:.82rem;color:#5C6359;">
                      <i class="bi bi-info-circle mr-1" style="color:#258517;"></i>
                      Read through the lesson, then mark it as done to unlock the Post-Test.
                    </div>
                    <button class="btn btn-success font-weight-bold" id="markLessonDoneBtn" style="border-radius:10px;white-space:nowrap;">
                      <i class="bi bi-check-lg mr-1"></i> Mark Lesson as Done
                    </button>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>

          </div>
        </div>

        <!-- PRE-TEST TAB -->
        <?php if (!empty($preQuestions)): ?>
        <div id="tab-pretest" class="tab-pane-custom">
          <?php if ($status === 'completed'): ?>
            <div class="quiz-card">
              <div class="quiz-header">
                <div>
                  <div class="quiz-header-title"><i class="bi bi-clipboard-check mr-2" style="color:#258517;"></i>Pre-Test — Your Answers</div>
                  <div class="quiz-counter"><?= count($preQuestions) ?> questions · Read-only</div>
                </div>
                <span class="badge" style="background:#d1fae5;color:#059669;font-size:.75rem;padding:6px 12px;border-radius:20px;">
                  <i class="bi bi-lock-fill mr-1"></i>Submitted
                </span>
              </div>
              <?php foreach ($preQuestions as $i => $q):
                $studentAnswer = $preAnswers[$q['id']] ?? null;
                $isCorrect     = $studentAnswer === $q['correct_answer'];
              ?>
                <div class="question-card" style="margin-bottom:16px;">
                  <div class="d-flex align-items-start mb-3">
                    <span class="question-num" style="background:<?= $isCorrect ? '#059669' : '#dc2626' ?>;"><?= $i + 1 ?></span>
                    <span class="question-text"><?= htmlspecialchars($q['question_text']) ?></span>
                  </div>
                  <?php foreach (['A','B','C','D'] as $letter): $opt = 'option_' . strtolower($letter); ?>
                    <?php
                      $isSelected = $studentAnswer === $letter;
                      $isCorrectOpt = $q['correct_answer'] === $letter;
                      $cls = '';
                      if ($isSelected && $isCorrect)   $cls = 'correct';
                      elseif ($isSelected && !$isCorrect) $cls = 'wrong';
                      elseif ($isCorrectOpt)            $cls = 'correct';
                    ?>
                    <div class="option-row <?= $cls ?>" style="pointer-events:none;">
                      <div class="option-letter"><?= $letter ?></div>
                      <span><?= htmlspecialchars($q[$opt]) ?></span>
                      <?php if ($isSelected && $isCorrect): ?>
                        <i class="bi bi-check-circle-fill ml-auto" style="color:#059669;"></i>
                      <?php elseif ($isSelected && !$isCorrect): ?>
                        <i class="bi bi-x-circle-fill ml-auto" style="color:#dc2626;"></i>
                      <?php elseif ($isCorrectOpt): ?>
                        <i class="bi bi-check-circle ml-auto" style="color:#059669;opacity:.6;"></i>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                  <?php if (!$studentAnswer): ?>
                    <div class="text-muted mt-2" style="font-size:.75rem;"><i class="bi bi-dash-circle mr-1"></i>Not answered</div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
          <div class="quiz-card">
            <div class="quiz-header">
              <div>
                <div class="quiz-header-title"><i class="bi bi-clipboard-check mr-2" style="color:#258517;"></i>Pre-Test</div>
                <div class="quiz-counter"><span id="pre-q-current">1</span> of <?= count($preQuestions) ?> questions</div>
              </div>

            </div>

            <!-- Progress bar -->
            <div style="height:4px;background:#e8f0e9;border-radius:4px;margin-bottom:24px;">
              <div id="pre-progress-bar" style="height:100%;background:linear-gradient(90deg,#258517,#4F9516);border-radius:4px;transition:.3s;width:0%;"></div>
            </div>

            <div id="pretest-questions">
              <?php
                $shuffled = $preQuestions;
                shuffle($shuffled);
              ?>
              <?php foreach ($shuffled as $i => $q): ?>
                <div class="question-card" data-qid="<?= $q['id'] ?>" data-index="<?= $i ?>" style="<?= $i > 0 ? 'display:none;' : '' ?>">
                  <div class="d-flex align-items-start mb-3">
                    <span class="question-num"><?= $i + 1 ?></span>
                    <span class="question-text"><?= htmlspecialchars($q['question_text']) ?></span>
                  </div>
                  <?php foreach (['A','B','C','D'] as $letter): $opt = 'option_' . strtolower($letter); ?>
                    <div class="option-row" data-value="<?= $letter ?>">
                      <div class="option-letter"><?= $letter ?></div>
                      <span><?= htmlspecialchars($q[$opt]) ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Navigation -->
            <div class="d-flex align-items-center justify-content-between mt-3" style="gap:10px;">
              <button class="btn btn-outline-secondary" id="preNavPrev" style="border-radius:10px;min-width:90px;" disabled>
                <i class="bi bi-chevron-left mr-1"></i> Prev
              </button>
              <button class="btn btn-outline-success font-weight-bold" id="preNavNext" style="border-radius:10px;min-width:90px;">
                Next <i class="bi bi-chevron-right ml-1"></i>
              </button>
              <button class="quiz-submit-btn" id="submitPreBtn" style="display:none;max-width:200px;"><i class="bi bi-send mr-1"></i> Submit</button>
            </div>
          </div>
          <div id="pre-result"></div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- POST-TEST TAB -->
        <?php if (!empty($postQuestions)): ?>
        <div id="tab-posttest" class="tab-pane-custom" style="display:none;">
          <?php if ($status === 'completed'): ?>
            <div class="quiz-card">
              <div class="quiz-header">
                <div>
                  <div class="quiz-header-title"><i class="bi bi-clipboard-data mr-2" style="color:#4e73df;"></i>Post-Test — Your Answers</div>
                  <div class="quiz-counter"><?= count($postQuestions) ?> questions · Score: <strong style="color:#059669;"><?= $progress[$module['id']]['quiz_score'] ?? 0 ?>%</strong></div>
                </div>
                <span class="badge" style="background:#d1fae5;color:#059669;font-size:.75rem;padding:6px 12px;border-radius:20px;">
                  <i class="bi bi-lock-fill mr-1"></i>Submitted
                </span>
              </div>
              <?php foreach ($postQuestions as $i => $q):
                $studentAnswer = $postAnswers[$q['id']] ?? null;
                $isCorrect     = $studentAnswer === $q['correct_answer'];
              ?>
                <div class="question-card" style="margin-bottom:16px;">
                  <div class="d-flex align-items-start mb-3">
                    <span class="question-num" style="background:<?= $isCorrect ? '#059669' : '#dc2626' ?>;"><?= $i + 1 ?></span>
                    <span class="question-text"><?= htmlspecialchars($q['question_text']) ?></span>
                  </div>
                  <?php foreach (['A','B','C','D'] as $letter): $opt = 'option_' . strtolower($letter); ?>
                    <?php
                      $isSelected   = $studentAnswer === $letter;
                      $isCorrectOpt = $q['correct_answer'] === $letter;
                      $cls = '';
                      if ($isSelected && $isCorrect)    $cls = 'correct';
                      elseif ($isSelected && !$isCorrect) $cls = 'wrong';
                      elseif ($isCorrectOpt)             $cls = 'correct';
                    ?>
                    <div class="option-row <?= $cls ?>" style="pointer-events:none;">
                      <div class="option-letter"><?= $letter ?></div>
                      <span><?= htmlspecialchars($q[$opt]) ?></span>
                      <?php if ($isSelected && $isCorrect): ?>
                        <i class="bi bi-check-circle-fill ml-auto" style="color:#059669;"></i>
                      <?php elseif ($isSelected && !$isCorrect): ?>
                        <i class="bi bi-x-circle-fill ml-auto" style="color:#dc2626;"></i>
                      <?php elseif ($isCorrectOpt): ?>
                        <i class="bi bi-check-circle ml-auto" style="color:#059669;opacity:.6;"></i>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                  <?php if (!$studentAnswer): ?>
                    <div class="text-muted mt-2" style="font-size:.75rem;"><i class="bi bi-dash-circle mr-1"></i>Not answered</div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else:
            $shuffledPost = $postQuestions;
            shuffle($shuffledPost);
          ?>
          <div class="quiz-card">
            <div class="quiz-header">
              <div>
                <div class="quiz-header-title"><i class="bi bi-clipboard-data mr-2" style="color:#258517;"></i>Post-Test</div>
                <div class="quiz-counter"><span id="post-q-current">1</span> of <?= count($shuffledPost) ?> questions</div>
              </div>

            </div>

            <!-- Progress bar -->
            <div style="height:4px;background:#e8f0e9;border-radius:4px;margin-bottom:24px;">
              <div id="post-progress-bar" style="height:100%;background:linear-gradient(90deg,#258517,#4F9516);border-radius:4px;transition:.3s;width:0%;"></div>
            </div>

            <div id="posttest-questions">
              <?php foreach ($shuffledPost as $i => $q): ?>
                <div class="question-card" data-qid="<?= $q['id'] ?>" data-index="<?= $i ?>" style="<?= $i > 0 ? 'display:none;' : '' ?>">
                  <div class="d-flex align-items-start mb-3">
                    <span class="question-num"><?= $i + 1 ?></span>
                    <span class="question-text"><?= htmlspecialchars($q['question_text']) ?></span>
                  </div>
                  <?php foreach (['A','B','C','D'] as $letter): $opt = 'option_' . strtolower($letter); ?>
                    <div class="option-row" data-value="<?= $letter ?>">
                      <div class="option-letter"><?= $letter ?></div>
                      <span><?= htmlspecialchars($q[$opt]) ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Navigation -->
            <div class="d-flex align-items-center justify-content-between mt-3" style="gap:10px;">
              <button class="btn btn-outline-secondary" id="postNavPrev" style="border-radius:10px;min-width:90px;" disabled>
                <i class="bi bi-chevron-left mr-1"></i> Prev
              </button>
              <button class="btn btn-outline-success font-weight-bold" id="postNavNext" style="border-radius:10px;min-width:90px;">
                Next <i class="bi bi-chevron-right ml-1"></i>
              </button>
              <button class="quiz-submit-btn" id="submitPostBtn" style="display:none;max-width:200px;"><i class="bi bi-send mr-1"></i> Submit</button>
            </div>
          </div>
          <div id="post-result"></div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

      </div>
    </div>
    <?php student_view_layout(['footer']); ?>
  </div>
</div>

<?php student_view_layout(['script']); ?>
<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });
var moduleId    = <?= (int)$module['id'] ?>;
var lessonDone  = <?= $lessonDone  ? 'true' : 'false' ?>;
var preDone     = <?= $preDone     ? 'true' : 'false' ?>;
var hasPost     = <?= !empty($postQuestions) ? 'true' : 'false' ?>;
var hasPre      = <?= !empty($preQuestions)  ? 'true' : 'false' ?>;

$(document).ready(function () {

  // ── Tab switching — respect locked state ──
  $('.module-tab').on('click', function () {
    if ($(this).data('locked')) {
      var tab = $(this).data('tab');
      if (tab === 'content') {
        notyf.error('Complete the Pre-Test first to unlock the Lesson.');
      } else if (tab === 'posttest') {
        notyf.error('Mark the Lesson as done first to unlock the Post-Test.');
      }
      return;
    }
    switchTab($(this).data('tab'));
  });

  function switchTab(tab) {
    $('.module-tab').removeClass('active');
    $('.module-tab[data-tab="' + tab + '"]').addClass('active');
    $('.tab-pane-custom').hide();
    $('#tab-' + tab).show();
  }

  // ── Go to Post-Test button ──
  $(document).on('click', '#goToPostBtn', function () {
    switchTab('posttest');
  });

  // ── Mark Lesson as Done ──
  $(document).on('click', '#markLessonDoneBtn', function () {
    var $btn = $(this).html('<span class="spinner-border spinner-border-sm mr-1"></span> Saving...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl('/student/api/lesson/done') ?>', type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ module_id: moduleId }),
      dataType: 'json',
      success: function (res) {
        if (!res.success) { notyf.error('Could not save. Try again.'); $btn.html('<i class="bi bi-check-lg mr-1"></i> Mark Lesson as Done').prop('disabled', false); return; }
        lessonDone = true;
        // Unlock post-test tab
        $('#tab-btn-posttest').removeAttr('data-locked').find('span:last-child').remove();
        // Replace button with done state
        $btn.closest('div.d-flex').html(
          '<span style="display:inline-flex;align-items:center;gap:6px;background:#d1fae5;color:#059669;font-size:.82rem;font-weight:700;padding:8px 18px;border-radius:10px;">' +
            '<i class="bi bi-check-circle-fill"></i> Lesson completed — Post-Test is now unlocked' +
          '</span>' +
          '<button class="btn btn-success font-weight-bold" id="goToPostBtn" style="border-radius:10px;">' +
            '<i class="bi bi-arrow-right mr-1"></i> Go to Post-Test' +
          '</button>'
        );
        notyf.success('Lesson marked as done. Post-Test is now unlocked!');
      },
      error: function () { notyf.error('Server error.'); $btn.html('<i class="bi bi-check-lg mr-1"></i> Mark Lesson as Done').prop('disabled', false); }
    });
  });

  // ── Option selection ──
  $(document).on('click', '.option-row', function () {
    if ($(this).hasClass('correct') || $(this).hasClass('wrong')) return;
    var $card = $(this).closest('.question-card');
    var qid   = $card.data('qid');
    $card.find('.option-row').removeClass('selected');
    $(this).addClass('selected');
    $card.addClass('answered');
  });

  function submitTest(testType, btnId, containerId, resultId) {
    var answers = {};
    $('#' + containerId + ' .question-card').each(function () {
      var sel = $(this).find('.option-row.selected').data('value');
      if (sel) answers[$(this).data('qid')] = sel;
    });
    var total = $('#' + containerId + ' .question-card').length;
    if (Object.keys(answers).length < total) {
      notyf.error('Please answer all questions before submitting.');
      return;
    }

    var $btn = $('#' + btnId).html('<span class="spinner-border spinner-border-sm mr-1"></span> Submitting...').prop('disabled', true);
    $.ajax({
      url: '<?= baseurl('/student/api/test/submit') ?>', type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ module_id: moduleId, test_type: testType, answers: answers }),
      dataType: 'json',
      success: function (res) {
        if (!res.success) { notyf.error(res.message); return; }
        var passed = res.passed !== undefined ? res.passed : (res.score >= 50);

        $('#' + containerId + ' .option-row').css('pointer-events', 'none');
        $('#' + btnId).hide();

        // After pre-test: unlock lesson tab
        if (testType === 'pre') {
          preDone = true;
          $('#tab-btn-content').removeAttr('data-locked').find('span:last-child').remove();
        }

        var passingInfo = '';
        var retryBtn = '<button class="quiz-submit-btn" style="max-width:200px;margin:0 auto;" id="retryPostBtn"><i class="bi bi-arrow-clockwise"></i> Try Again</button>';

        $('#' + resultId).html(
          '<div class="result-overlay">' +
            '<div class="result-score-circle ' + (passed ? 'pass' : 'fail') + '">' + res.score + '%</div>' +
            '<h5 class="font-weight-bold mb-1">' + (passed ? '🎉 Well done!' : '📝 Keep trying!') + '</h5>' +
            '<div class="text-muted mb-3" style="font-size:.85rem;">' + res.correct + ' out of ' + res.total + ' correct' + passingInfo + '</div>' +
            (testType === 'pre'
              ? '<button class="quiz-submit-btn" style="max-width:240px;margin:0 auto;" id="goToLessonBtn"><i class="bi bi-book mr-1"></i> Go to Lesson</button>'
              : (passed
                  ? '<a href="<?= baseurl('/student/modules') ?>" class="quiz-submit-btn" style="max-width:260px;margin:0 auto;text-decoration:none;"><i class="bi bi-arrow-right"></i> Continue</a>'
                  : retryBtn
                )
            ) +
          '</div>'
        );


        notyf.success(res.score + '% — ' + (passed ? 'Passed!' : 'Keep going!'));
      },
      error: function () { notyf.error('Server error.'); },
      complete: function () {
        if ($('#' + btnId).is(':visible')) $btn.html('<i class="bi bi-send"></i> Submit ' + (testType === 'pre' ? 'Pre' : 'Post') + '-Test').prop('disabled', false);
      }
    });
  }

  // Go to Lesson after pre-test
  $(document).on('click', '#goToLessonBtn', function () {
    switchTab('content');
  });

  // ── Pre-Test one-by-one navigation ──
  var preTotal   = $('#pretest-questions .question-card').length;
  var preCurrent = 0;

  function preGoTo(index) {
    $('#pretest-questions .question-card').hide();
    var $card = $('#pretest-questions .question-card[data-index="' + index + '"]');
    $card.show();
    preCurrent = index;
    $('#pre-q-current').text(index + 1);
    $('#pre-progress-bar').css('width', (((index + 1) / preTotal) * 100) + '%');
    $('#preNavPrev').prop('disabled', index === 0);
    if (index === preTotal - 1) {
      $('#preNavNext').hide();
      $('#submitPreBtn').show();
    } else {
      $('#preNavNext').show();
      $('#submitPreBtn').hide();
    }
  }

  $('#preNavNext').on('click', function () {
    if (preCurrent < preTotal - 1) preGoTo(preCurrent + 1);
  });

  $('#preNavPrev').on('click', function () {
    if (preCurrent > 0) preGoTo(preCurrent - 1);
  });

  // init
  if (preTotal > 0) preGoTo(0);

  $('#submitPreBtn').on('click', function () { submitTest('pre', 'submitPreBtn', 'pretest-questions', 'pre-result'); });
  $('#submitPostBtn').on('click', function () { submitTest('post', 'submitPostBtn', 'posttest-questions', 'post-result'); });

  // ── Post-Test one-by-one navigation ──
  var postTotal   = $('#posttest-questions .question-card').length;
  var postCurrent = 0;

  function postGoTo(index) {
    $('#posttest-questions .question-card').hide();
    $('#posttest-questions .question-card[data-index="' + index + '"]').show();
    postCurrent = index;
    $('#post-q-current').text(index + 1);
    $('#post-progress-bar').css('width', (((index + 1) / postTotal) * 100) + '%');
    $('#postNavPrev').prop('disabled', index === 0);
    if (index === postTotal - 1) {
      $('#postNavNext').hide();
      $('#submitPostBtn').show();
    } else {
      $('#postNavNext').show();
      $('#submitPostBtn').hide();
    }
  }

  $('#postNavNext').on('click', function () {
    if (postCurrent < postTotal - 1) postGoTo(postCurrent + 1);
  });

  $('#postNavPrev').on('click', function () {
    if (postCurrent > 0) postGoTo(postCurrent - 1);
  });

  if (postTotal > 0) postGoTo(0);

  // Reset post-test navigation on retry with re-shuffle
  $(document).on('click', '#retryPostBtn', function () {
    $('#post-result').hide();
    var $container = $('#posttest-questions');
    var cards = $container.find('.question-card').toArray();
    for (var i = cards.length - 1; i > 0; i--) {
      var j = Math.floor(Math.random() * (i + 1));
      var t = cards[i]; cards[i] = cards[j]; cards[j] = t;
    }
    $.each(cards, function (i, card) {
      $(card).attr('data-index', i).removeClass('answered')
             .find('.option-row').removeClass('selected correct wrong').css('pointer-events', '');
      $container.append(card);
    });
    $.each(cards, function (i, card) {
      $container.append(card);
    });
    postTotal = cards.length;
    postGoTo(0);
  });
});
</script>

<?php if (!empty($module['file_path'])): ?>
<script src="<?= asset('dist/assets/js/pdf.min.js') ?>" nonce="<?= csp_nonce() ?>"></script>
<script nonce="<?= csp_nonce() ?>">
  var pdfjsLib   = window['pdfjs-dist/build/pdf'];
  pdfjsLib.GlobalWorkerOptions.workerSrc = '<?= asset('dist/assets/js/pdf.worker.min.js') ?>';

  var pdfDoc      = null;
  var currentPage = 1;
  var totalPages  = 1;
  var rendering   = false;
  var pdfUrl      = '<?= baseurl('/student/modules/' . $module['id'] . '/pdf') ?>';

  pdfjsLib.getDocument({ url: pdfUrl, withCredentials: true }).promise
    .then(function (doc) {
      pdfDoc     = doc;
      totalPages = doc.numPages;
      document.getElementById('pdfLoading').style.display = 'none';
      document.getElementById('pdfPrev').disabled = true;
      document.getElementById('pdfNext').disabled = totalPages <= 1;
      renderPage(1);
    })
    .catch(function (err) {
      document.getElementById('pdfLoading').innerHTML =
        '<i class="bi bi-exclamation-circle" style="font-size:2rem;color:#f87171;"></i>' +
        '<span style="font-size:.82rem;color:#d1d5db;">Could not load PDF. ' +
        '<a href="' + pdfUrl + '" target="_blank" style="color:#4ade80;">Open directly</a></span>';
      console.error('PDF.js error:', err);
    });

  function renderPage(num) {
    if (rendering || !pdfDoc) return;
    rendering = true;
    pdfDoc.getPage(num).then(function (page) {
      var wrap      = document.getElementById('pdfCanvasWrap');
      var wrapWidth = wrap.clientWidth - 32;
      var viewport  = page.getViewport({ scale: 1 });
      var scale     = Math.max(wrapWidth / viewport.width, 0.5);
      var scaled    = page.getViewport({ scale: scale });

      wrap.querySelectorAll('canvas').forEach(function (c) { c.remove(); });

      var canvas        = document.createElement('canvas');
      canvas.height     = scaled.height;
      canvas.width      = scaled.width;
      wrap.appendChild(canvas);

      page.render({ canvasContext: canvas.getContext('2d'), viewport: scaled }).promise.then(function () {
        rendering   = false;
        currentPage = num;
        document.getElementById('pdfPageInfo').textContent = num + ' / ' + totalPages;
        document.getElementById('pdfPrev').disabled = num <= 1;
        document.getElementById('pdfNext').disabled = num >= totalPages;
      });
    });
  }

  function changePage(delta) {
    var next = currentPage + delta;
    if (next >= 1 && next <= totalPages) renderPage(next);
  }

  document.getElementById('pdfPrev').addEventListener('click', function () { changePage(-1); });
  document.getElementById('pdfNext').addEventListener('click', function () { changePage(1); });
  document.getElementById('pdfMaxBtn').addEventListener('click', togglePdfFullscreen);

  var pdfIsFullscreen = false;
  var pdfPlaceholder  = null;

  function togglePdfFullscreen() {
    var wrap = document.getElementById('pdfViewerWrap');
    var icon = document.getElementById('pdfMaxIcon');
    var btn  = document.getElementById('pdfMaxBtn');

    if (!pdfIsFullscreen) {
      // Insert a placeholder so we can restore the element later
      pdfPlaceholder = document.createElement('div');
      pdfPlaceholder.id = 'pdfViewerPlaceholder';
      wrap.parentNode.insertBefore(pdfPlaceholder, wrap);

      // Move the viewer to <body> so position:fixed is not clipped by any ancestor
      document.body.appendChild(wrap);
      wrap.classList.add('pdf-fullscreen');

      icon.className = 'bi bi-fullscreen-exit';
      btn.innerHTML  = '<i class="bi bi-fullscreen-exit" id="pdfMaxIcon"></i> Minimize';
      document.body.style.overflow = 'hidden';
      pdfIsFullscreen = true;
    } else {
      // Restore the viewer to its original position
      wrap.classList.remove('pdf-fullscreen');
      pdfPlaceholder.parentNode.insertBefore(wrap, pdfPlaceholder);
      pdfPlaceholder.remove();
      pdfPlaceholder = null;

      icon = document.getElementById('pdfMaxIcon');
      icon.className = 'bi bi-fullscreen';
      btn.innerHTML  = '<i class="bi bi-fullscreen" id="pdfMaxIcon"></i> Maximize';
      document.body.style.overflow = '';
      pdfIsFullscreen = false;
    }
    setTimeout(function () { if (pdfDoc) renderPage(currentPage); }, 300);
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape'      && pdfIsFullscreen) togglePdfFullscreen();
    if (e.key === 'ArrowRight') changePage(1);
    if (e.key === 'ArrowLeft')  changePage(-1);
  });
</script>
<?php endif; ?>
</body>
</html>
