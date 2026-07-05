<div class="panel">
  <div class="panel-header">
    <h2><i class="fas fa-question-circle"></i> Student Questions</h2>
    <div style="display:flex;gap:.5rem;align-items:center">
      <?php $pending = array_filter($questions, fn($q) => $q['status'] === 'pending'); ?>
      <?php if (count($pending) > 0): ?>
        <span class="badge badge-warning"><?php echo count($pending); ?> pending</span>
      <?php endif; ?>
    </div>
  </div>

  <?php if (empty($questions)): ?>
    <div class="empty-state">
      <i class="fas fa-comments"></i>
      <p>No student questions yet. Questions will appear here once students ask something in your courses.</p>
    </div>
  <?php else: ?>
    <div class="questions-filter-tabs">
      <button class="filter-btn active" data-filter="all">All (<?php echo count($questions); ?>)</button>
      <button class="filter-btn" data-filter="pending">Pending (<?php echo count($pending); ?>)</button>
      <button class="filter-btn" data-filter="answered">Answered (<?php echo count($questions) - count($pending); ?>)</button>
    </div>

    <div class="questions-list" id="questionsList">
      <?php foreach ($questions as $q): ?>
        <div class="question-item question-item--<?php echo $q['status']; ?>" data-status="<?php echo $q['status']; ?>">
          <div class="question-header">
            <div class="question-author">
              <?php if (!empty($q['student_image'])): ?>
                <img src="/uploads/<?php echo $q['student_image']; ?>" class="q-avatar" alt="">
              <?php else: ?>
                <div class="q-avatar-placeholder"><i class="fas fa-user-graduate"></i></div>
              <?php endif; ?>
              <div>
                <span class="q-name"><?php echo $q['student_name']; ?></span>
                <span class="q-course"><?php echo $q['course_name']; ?></span>
              </div>
            </div>
            <span class="question-status-badge question-status-badge--<?php echo $q['status']; ?>">
              <?php echo $q['status'] === 'answered' ? '<i class="fas fa-check"></i> Answered' : '<i class="fas fa-clock"></i> Pending' ?>
            </span>
          </div>
          <p class="question-text"><?php echo $q['question_text']; ?></p>
          <?php if ($q['status'] === 'answered'): ?>
            <div class="answer-box">
              <span class="answer-by"><i class="fas fa-reply"></i> Your answer:</span>
              <p><?php echo nl2br($q['answer_text']); ?></p>
              <span class="answer-time"><?php echo timeAgo($q['answered_at']); ?></span>
            </div>
          <?php else: ?>
            <div class="answer-inline-form">
              <form method="POST" action="/doctor/question/<?php echo $q['id']; ?>/answer">
                <div style="display:flex;gap:.75rem;align-items:flex-end">
                  <div class="form-group" style="flex:1;margin-bottom:0">
                    <textarea name="answer_text" class="form-input" rows="2"
                      placeholder="Write your answer to <?php echo $q['student_name']; ?>…" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm" style="white-space:nowrap">
                    <i class="fas fa-reply"></i> Answer
                  </button>
                </div>
              </form>
            </div>
          <?php endif; ?>
          <span class="question-time"><?php echo timeAgo($q['created_at']); ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
