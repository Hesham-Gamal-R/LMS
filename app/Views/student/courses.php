<div class="panel">
  <div class="panel-header">
    <h2><i class="fas fa-book-open"></i> Browse All Courses</h2>
    <form action="/student/courses" method="GET" class="search-form search-form--inline">
      <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="q" value="<?php echo $search; ?>" placeholder="Search by professor…" class="search-input">
      </div>
      <button type="submit" class="btn btn-primary btn-sm">Search</button>
      <?php if ($search): ?><a href="/student/courses" class="btn btn-ghost btn-sm">Clear</a><?php endif; ?>
    </form>
  </div>

  <?php if (empty($courses)): ?>
    <div class="empty-state">
      <i class="fas fa-search"></i>
      <p>No courses found<?php echo $search ? ' for that professor' : ''; ?>.</p>
    </div>
  <?php else: ?>
    <div class="courses-grid">
      <?php foreach ($courses as $course): ?>
        <div class="course-card">
          <div class="course-card-header">
            <span class="course-code"><?php echo $course['code']; ?></span>
            <span class="course-status course-status--<?php echo $course['status']; ?>"><?php echo ucfirst($course['status']); ?></span>
          </div>
          <h3 class="course-name"><?php echo $course['name']; ?></h3>
          <?php if (!empty($course['description'])): ?>
            <p class="course-desc"><?php echo mb_substr($course['description'], 0, 100); ?><?php echo strlen($course['description']) > 100 ? '…' : ''; ?></p>
          <?php endif; ?>
          <div class="course-meta">
            <span><i class="fas fa-user-tie"></i> <?php echo $course['doctor_name']; ?></span>
            <span><i class="fas fa-building"></i> <?php echo $course['doctor_dept']; ?></span>
            <span><i class="fas fa-clock"></i> <?php echo $course['credit_hours']; ?> hrs</span>
            <span><i class="fas fa-users"></i> <?php echo $course['student_count']; ?> enrolled</span>
          </div>
          <div class="course-card-footer">
            <?php if (isset($enrolled[$course['id']])): ?>
              <a href="/student/course/<?php echo $course['id']; ?>" class="btn btn-success btn-sm">
                <i class="fas fa-check"></i> Enrolled — Open
              </a>
              <form method="POST" action="/student/course/<?php echo $course['id']; ?>/unenroll" style="display:inline">
                <button type="submit" class="btn btn-ghost btn-sm btn-danger-ghost"
                  onclick="return confirm('Are you sure you want to unenroll from this course?')">
                  <i class="fas fa-times"></i> Unenroll
                </button>
              </form>
            <?php else: ?>
              <form method="POST" action="/student/course/<?php echo $course['id']; ?>/enroll">
                <button type="submit" class="btn btn-primary btn-sm btn-full">
                  <i class="fas fa-plus"></i> Enroll
                </button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
