<div class="panel">
  <div class="panel-header">
    <h2><i class="fas fa-chalkboard-teacher"></i> My Courses</h2>
    <a href="/doctor/courses/create" class="btn btn-primary btn-sm">
      <i class="fas fa-plus"></i> New Course
    </a>
  </div>

  <?php if (empty($courses)): ?>
    <div class="empty-state">
      <i class="fas fa-chalkboard"></i>
      <p>You haven't created any courses yet.</p>
      <a href="/doctor/courses/create" class="btn btn-primary">Create Your First Course</a>
    </div>
  <?php else: ?>
    <div class="courses-grid">
      <?php foreach ($courses as $c): ?>
        <div class="course-card course-card--doctor">
          <div class="course-card-header">
            <span class="course-code"><?php echo $c['code']; ?></span>
            <span class="course-status course-status--<?php echo $c['status']; ?>"><?php echo ucfirst($c['status']); ?></span>
          </div>
          <h3 class="course-name"><?php echo $c['name']; ?></h3>
          <div class="course-meta">
            <span><i class="fas fa-clock"></i> <?php echo $c['credit_hours']; ?> credit hrs</span>
            <span><i class="fas fa-users"></i> <?php echo $c['student_count']; ?> students</span>
            <span><i class="fas fa-file"></i> <?php echo $c['content_count']; ?> materials</span>
            <?php if ($c['pending_questions'] > 0): ?>
              <span class="text-warning"><i class="fas fa-question-circle"></i> <?php echo $c['pending_questions']; ?> pending</span>
            <?php endif; ?>
          </div>
          <div class="course-card-footer">
            <a href="/doctor/course/<?php echo $c['id']; ?>" class="btn btn-primary btn-sm">
              <i class="fas fa-eye"></i> Manage
            </a>
            <a href="/doctor/course/<?php echo $c['id']; ?>/edit" class="btn btn-ghost btn-sm">
              <i class="fas fa-edit"></i> Edit
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
