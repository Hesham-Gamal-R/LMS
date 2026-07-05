<div class="course-hero">
  <div class="course-hero-info">
    <span class="course-code"><?php echo $course['code']; ?></span>
    <h1><?php echo $course['name']; ?></h1>
    <div class="course-hero-meta">
      <span><i class="fas fa-user-tie"></i> <?php echo$course['doctor_name']; ?></span>
      <span><i class="fas fa-building"></i> <?php echo $course['doctor_dept']; ?></span>
      <span><i class="fas fa-clock"></i> <?php echo $course['credit_hours']; ?> credit hours</span>
      <span><i class="fas fa-users"></i> <?php echo $course['student_count']; ?> students</span>
    </div>
    <?php if (!empty($course['description'])): ?>
      <p class="course-hero-desc"><?php echo $course['description']; ?></p>
    <?php endif; ?>
  </div>
</div>

<div class="course-tabs-wrap">
  <div class="course-tabs" id="courseTabs">
    <button class="tab-btn active" data-tab="materials"><i class="fas fa-file-alt"></i> Materials</button>
    <button class="tab-btn" data-tab="announcements"><i class="fas fa-bullhorn"></i> Announcements <span class="tab-count"><?php echo count($announcements); ?></span></button>
    <button class="tab-btn" data-tab="qa"><i class="fas fa-comments"></i> Q&amp;A <span class="tab-count"><?php echo count($questions); ?></span></button>
  </div>
</div>

<div class="tab-content active" id="tab-materials">
  <?php if (empty($contents)): ?>
    <div class="empty-state">
      <i class="fas fa-folder-open"></i>
      <p>No course materials uploaded yet. Check back soon.</p>
    </div>
  <?php else: ?>
    <div class="contents-list">
      <?php foreach ($contents as $idx => $c): ?>
        <div class="content-item">
          <div class="content-item-icon content-item-icon--<?php echo $c['type']; ?>">
            <i class="fas <?php echo $c['type'] === 'pdf' ? 'fa-file-pdf' : 'fa-play-circle'; ?>"></i>
          </div>
          <div class="content-item-info">
            <span class="content-item-num"><?php echo $idx + 1; ?>.</span>
            <div>
              <h4 class="content-item-title"><?php  echo $c['title']; ?></h4>
              <?php if (!empty($c['description'])): ?>
                <p class="content-item-desc"><?php echo $c['description']; ?></p>
              <?php endif; ?>
              <span class="content-item-meta">
                <span class="content-type-badge content-type-badge--<?php echo $c['type']; ?>">
                  <?php echo strtoupper($c['type']); ?>
                </span>
                <span><?php echo timeAgo($c['created_at']); ?></span>
                <span><?php echo $c['view_count']; ?> views</span>
              </span>
            </div>
          </div>
          <div class="content-item-actions">
            <?php if ($c['type'] === 'pdf'): ?>
              <a href="/student/content/<?php echo $c['id']; ?>/view" class="btn btn-primary btn-sm" target="_blank">
                <i class="fas fa-eye"></i> View
              </a>
            <?php else: ?>
              <a href="/student/content/<?php echo $c['id']; ?>/view" class="btn btn-primary btn-sm">
                <i class="fas fa-play"></i> Watch
              </a>
            <?php endif; ?>
            <a href="/student/content/<?php echo $c['id']; ?>/download" class="btn btn-ghost btn-sm">
              <i class="fas fa-download"></i> Download
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<div class="tab-content" id="tab-announcements">
  <?php if (empty($announcements)): ?>
    <div class="empty-state">
      <i class="fas fa-bell-slash"></i>
      <p>No announcements yet.</p>
    </div>
  <?php else: ?>
    <div class="announcements-list">
      <?php foreach ($announcements as $a): ?>
        <div class="announcement-item announcement-item--<?php echo $a['type']; ?>">
          <div class="announcement-header">
            <span class="ann-type ann-type--<?php echo $a['type']; ?>">
              <?php echo match($a['type']) { 'exam'=>'<i class="fas fa-pen-ruler"></i> Exam','assignment'=>'<i class="fas fa-tasks"></i> Assignment','general'=>'<i class="fas fa-info-circle"></i> General', default=>'<i class="fas fa-bell"></i> Notice' }; ?>
            </span>
            <span class="ann-time"><?php echo timeAgo($a['created_at']); ?></span>
          </div>
          <h4 class="ann-title"><?php echo $a['title']; ?></h4>
          <p class="ann-body"><?php echo nl2br($a['body']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<div class="tab-content" id="tab-qa">
  <div class="qa-ask-form panel">
    <h3><i class="fas fa-paper-plane"></i> Ask a Question</h3>
    <form method="POST" action="/student/course/<?php echo $course['id']; ?>/question">
      <div class="form-group">
        <textarea name="question_text" class="form-input" rows="3"
          placeholder="Type your question to <?php echo $course['doctor_name']; ?>…" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit Question</button>
    </form>
  </div>

  <?php if (!empty($questions)): ?>
    <div class="questions-list">
      <?php foreach ($questions as $q): ?>
        <div class="question-item question-item--<?php echo $q['status']; ?>">
          <div class="question-header">
            <div class="question-author">
              <?php if (!empty($q['student_image'])): ?>
                <img src="/uploads/<?php echo $q['student_image']; ?>" class="q-avatar" alt="">
              <?php else: ?>
                <div class="q-avatar-placeholder"><i class="fas fa-user-graduate"></i></div>
              <?php endif; ?>
              <span><?php echo $q['student_name']; ?></span>
            </div>
            <span class="question-status-badge question-status-badge--<?php echo $q['status']; ?>">
              <?php echo $q['status'] === 'answered' ? '<i class="fas fa-check"></i> Answered' : '<i class="fas fa-clock"></i> Pending'; ?>
            </span>
          </div>
          <p class="question-text"><?php echo $q['question_text']; ?></p>
          <?php if ($q['status'] === 'answered'): ?>
            <div class="answer-box">
              <span class="answer-by"><i class="fas fa-user-tie"></i> <?php echo $q['doctor_name'] ?? 'Doctor'; ?>:</span>
              <p><?php echo nl2br($q['answer_text']); ?></p>
              <span class="answer-time"><?php echo timeAgo($q['answered_at']); ?></span>
            </div>
          <?php endif; ?>
          <span class="question-time"><?php echo timeAgo($q['created_at']); ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
