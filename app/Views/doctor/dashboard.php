<div class="dashboard-stats">
  <div class="stat-card stat-card--blue">
    <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
    <div class="stat-body">
      <span class="stat-number"><?php echo count($courses); ?></span>
      <span class="stat-label">My Courses</span>
    </div>
  </div>
  <div class="stat-card stat-card--green">
    <div class="stat-icon"><i class="fas fa-users"></i></div>
    <div class="stat-body">
      <span class="stat-number"><?php echo $totalStudents; ?></span>
      <span class="stat-label">Total Students</span>
    </div>
  </div>
  <div class="stat-card stat-card--orange">
    <div class="stat-icon"><i class="fas fa-question-circle"></i></div>
    <div class="stat-body">
      <span class="stat-number"><?php echo $pendingCount; ?></span>
      <span class="stat-label">Pending Questions</span>
    </div>
  </div>
  <div class="stat-card stat-card--purple">
    <div class="stat-icon"><i class="fas fa-bullhorn"></i></div>
    <div class="stat-body">
      <span class="stat-number"><?php echo count($announcements); ?></span>
      <span class="stat-label">Announcements</span>
    </div>
  </div>
</div>

<?php if ($notifCount > 0): ?>
<div class="notif-banner">
  <div class="notif-banner-icon"><i class="fas fa-bell"></i></div>
  <div class="notif-banner-body">
    <h4><?php echo $notifCount; ?> new student question<?php echo $notifCount > 1 ? 's' : ''; ?> waiting for your response</h4>
    <a href="/doctor/questions" class="btn btn-primary btn-sm" style="margin-top:.5rem">
      <i class="fas fa-reply"></i> View Questions
    </a>
  </div>
</div>
<?php endif; ?>

<div class="dashboard-grid">
  <div class="dashboard-main">
    <div class="panel">
      <div class="panel-header">
        <h2><i class="fas fa-chalkboard-teacher"></i> My Courses</h2>
        <a href="/doctor/courses/create" class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> New Course
        </a>
      </div>
      <?php if (empty($courses)): ?>
        <div class="empty-state empty-state--sm">
          <i class="fas fa-chalkboard"></i>
          <p>You haven't created any courses yet.</p>
          <a href="/doctor/courses/create" class="btn btn-primary btn-sm">Create Your First Course</a>
        </div>
      <?php else: ?>
        <div class="enrolled-courses-list">
          <?php foreach ($courses as $c): ?>
            <a href="/doctor/course/<?php echo $c['id']; ?>" class="enrolled-course-item">
              <div class="enrolled-course-icon enrolled-course-icon--doctor"><i class="fas fa-book"></i></div>
              <div class="enrolled-course-info">
                <span class="enrolled-course-name"><?php echo $c['name']; ?></span>
                <span class="enrolled-course-meta">
                  <i class="fas fa-users"></i> <?php echo $c['student_count']; ?> students
                  &nbsp;&middot;&nbsp;
                  <i class="fas fa-file"></i> <?php echo $c['content_count']; ?> files
                  <?php if ($c['pending_questions'] > 0): ?>
                    &nbsp;&middot;&nbsp;<span class="text-warning"><i class="fas fa-question-circle"></i> <?php echo $c['pending_questions']; ?> pending</span>
                  <?php endif; ?>
                </span>
              </div>
              <span class="course-code"><?php echo $c['code']; ?></span>
              <span class="course-status course-status--<?php echo $c['status']; ?>"><?php echo ucfirst($c['status']); ?></span>
              <i class="fas fa-chevron-right enrolled-course-arrow"></i>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="panel">
      <div class="panel-header">
        <h2><i class="fas fa-question-circle"></i> Recent Questions</h2>
        <a href="/doctor/questions" class="btn btn-ghost btn-sm">View All</a>
      </div>
      <?php if (empty($questions)): ?>
        <div class="empty-state empty-state--sm"><i class="fas fa-comments"></i><p>No student questions yet.</p></div>
      <?php else: ?>
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
                  <span class="text-muted" style="font-size:.8rem"><?php echo $q['course_name']; ?></span>
                </div>
                <span class="question-status-badge question-status-badge--<?php echo $q['status']; ?>">
                  <?php echo $q['status'] === 'answered' ? '<i class="fas fa-check"></i> Answered' : '<i class="fas fa-clock"></i> Pending'; ?>
                </span>
              </div>
              <p class="question-text"><?php echo mb_substr($q['question_text'], 0, 120); ?><?php echo strlen($q['question_text']) > 120 ? '…' : ''; ?></p>
              <span class="question-time"><?php echo timeAgo($q['created_at']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="dashboard-side">
    <div class="panel">
      <div class="panel-header"><h2><i class="fas fa-bullhorn"></i> Recent Announcements</h2></div>
      <?php if (empty($announcements)): ?>
        <div class="empty-state empty-state--sm"><i class="fas fa-bell-slash"></i><p>No announcements yet.</p></div>
      <?php else: ?>
        <div class="announcements-list">
          <?php foreach ($announcements as $a): ?>
            <div class="announcement-item announcement-item--<?php echo $a['type']; ?>">
              <div class="announcement-header">
                <span class="ann-type ann-type--<?php echo $a['type']; ?>">
                  <?php echo match($a['type']) { 'exam'=>'<i class="fas fa-pen-ruler"></i> Exam','assignment'=>'<i class="fas fa-tasks"></i> Assignment','general'=>'<i class="fas fa-info-circle"></i> General', default=>'<i class="fas fa-bell"></i>' }; ?>
                </span>
                <span class="ann-course"><?php echo $a['course_name']; ?></span>
              </div>
              <h4 class="ann-title"><?php echo $a['title']; ?></h4>
              <span class="ann-time"><?php echo timeAgo($a['created_at']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
