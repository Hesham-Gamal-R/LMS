
<div class="dashboard-stats">
  <div class="stat-card stat-card--blue">
    <div class="stat-icon"><i class="fas fa-book-open"></i></div>
    <div class="stat-body">
      <span class="stat-number"><?php echo count($courses); ?></span>
      <span class="stat-label">Enrolled Courses</span>
    </div>
  </div>
  <div class="stat-card stat-card--purple">
    <div class="stat-icon"><i class="fas fa-bullhorn"></i></div>
    <div class="stat-body">
      <span class="stat-number"><?php echo count($announcements); ?></span>
      <span class="stat-label">Recent Announcements</span>
    </div>
  </div>
  <div class="stat-card stat-card--green">
    <div class="stat-icon"><i class="fas fa-question-circle"></i></div>
    <div class="stat-body">
      <span class="stat-number"><?php echo count(array_filter($questions, fn($q) => $q['status'] === 'answered')); ?></span>
      <span class="stat-label">Answered Questions</span>
    </div>
  </div>
  <div class="stat-card stat-card--orange">
    <div class="stat-icon"><i class="fas fa-bell"></i></div>
    <div class="stat-body">
      <span class="stat-number"><?php echo $notifCount; ?></span>
      <span class="stat-label">New Notifications</span>
    </div>
  </div>
</div>

<?php if ($notifCount > 0): ?>
<div class="notif-banner">
  <div class="notif-banner-icon"><i class="fas fa-bell"></i></div>
  <div class="notif-banner-body">
    <h4><?php echo $notifCount; ?> new answer<?php echo $notifCount > 1 ? 's' : ''; ?> to your questions</h4>
    <?php foreach ($notifications as $n): ?>
      <div class="notif-item">
        <i class="fas fa-check-circle"></i>
        <span><strong><?php echo $n['doctor_name']; ?></strong> answered your question in <em><?php echo $n['course_name']; ?></em>: "<?php echo mb_substr($n['question_text'], 0, 60); ?>…"</span>
        <span class="notif-time"><?php echo timeAgo($n['answered_at']); ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<div class="dashboard-grid">
  <div class="dashboard-main">
    <div class="panel">
      <div class="panel-header">
        <h2><i class="fas fa-book-open"></i> My Courses</h2>
        <a href="/student/courses" class="btn btn-ghost btn-sm">Browse More</a>
      </div>
      <?php if (empty($courses)): ?>
        <div class="empty-state empty-state--sm">
          <i class="fas fa-book"></i>
          <p>You haven't enrolled in any courses yet.</p>
          <a href="/student/courses" class="btn btn-primary btn-sm">Browse Courses</a>
        </div>
      <?php else: ?>
        <div class="enrolled-courses-list">
          <?php foreach ($courses as $c): ?>
            <a href="/student/course/<?php echo $c['id']; ?>" class="enrolled-course-item">
              <div class="enrolled-course-icon"><i class="fas fa-book"></i></div>
              <div class="enrolled-course-info">
                <span class="enrolled-course-name"><?php echo $c['name']; ?></span>
                <span class="enrolled-course-meta">
                  <i class="fas fa-user-tie"></i> <?php echo $c['doctor_name']; ?>
                  &nbsp;&middot;&nbsp;
                  <i class="fas fa-file"></i> <?php echo $c['content_count']; ?> files
                </span>
              </div>
              <span class="course-code"><?php echo $c['code']; ?></span>
              <i class="fas fa-chevron-right enrolled-course-arrow"></i>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="panel">
      <div class="panel-header">
        <h2><i class="fas fa-question-circle"></i> My Questions</h2>
      </div>
      <?php if (empty($questions)): ?>
        <div class="empty-state empty-state--sm">
          <i class="fas fa-comments"></i>
          <p>No questions submitted yet.</p>
        </div>
      <?php else: ?>
        <div class="questions-list">
          <?php foreach (array_slice($questions, 0, 5) as $q): ?>
            <div class="question-item question-item--<?php echo $q['status']; ?>">
              <div class="question-header">
                <span class="question-course"><?php echo $q['course_name']; ?></span>
                <span class="question-status-badge question-status-badge--<?php echo $q['status']; ?>">
                  <?php echo $q['status'] === 'answered' ? '<i class="fas fa-check"></i> Answered' : '<i class="fas fa-clock"></i> Pending'; ?>
                </span>
              </div>
              <p class="question-text"><?php echo $q['question_text']; ?></p>
              <?php if ($q['status'] === 'answered'): ?>
                <div class="answer-box">
                  <span class="answer-by"><i class="fas fa-user-tie"></i> <?php echo $q['doctor_name'] ?? 'Doctor';?>:</span>
                  <p><?php echo $q['answer_text']; ?></p>
                  <span class="answer-time"><?php echo timeAgo($q['answered_at']); ?></span>
                </div>
              <?php endif; ?>
              <span class="question-time"><?php echo timeAgo($q['created_at']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="dashboard-side">
    <div class="panel">
      <div class="panel-header">
        <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
      </div>
      <?php if (empty($announcements)): ?>
        <div class="empty-state empty-state--sm">
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
                <span class="ann-course"><?php echo $a['course_name']; ?></span>
              </div>
              <h4 class="ann-title"><?php echo $a['title']; ?></h4>
              <p class="ann-body"><?php echo mb_substr($a['body'], 0, 120); ?><?php echo strlen($a['body']) > 120 ? '…' : ''; ?></p>
              <span class="ann-time"><?php echo timeAgo($a['created_at']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
