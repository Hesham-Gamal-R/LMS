<div class="page-header">
  <div class="section-container">
    <h1 class="page-title"><i class="fas fa-book-open"></i> All Courses</h1>
    <p class="page-subtitle">Browse all available university courses</p>
  </div>
</div>

<div class="section-container" style="padding-top:2rem">
  <div class="search-bar">
    <form action="/courses" method="GET" class="search-form">
      <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="q" value="<?php echo $search; ?>" placeholder="Search by professor name..." class="search-input" autofocus>
      </div>
      <button type="submit" class="btn btn-primary">Search</button>
      <?php if ($search): ?>
        <a href="/courses" class="btn btn-ghost">Clear</a>
      <?php endif; ?>
    </form>
  </div>

  <?php if ($search): ?>
    <p class="search-result-info">
      <?php echo count($courses); ?> course<?php echo count($courses) !== 1 ? 's' : ''; ?> found for professor "<strong><?php echo $search; ?></strong>"
    </p>
  <?php endif; ?>

  <?php if (empty($courses)): ?>
    <div class="empty-state">
      <i class="fas fa-search"></i>
      <p>No courses found<?php echo $search ? ' matching your search' : ''; ?>.</p>
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
            <p class="course-desc"><?php echo mb_substr($course['description'], 0, 120); ?><?php echo strlen($course['description']) > 120 ? '…' : ''; ?></p>
          <?php endif; ?>
          <div class="course-meta">
            <span><i class="fas fa-user-tie"></i> <?php echo $course['doctor_name']; ?></span>
            <span><i class="fas fa-building"></i> <?php echo $course['doctor_dept']; ?></span>
            <span><i class="fas fa-clock"></i> <?php echo $course['credit_hours']; ?> credit hrs</span>
            <span><i class="fas fa-users"></i> <?php echo $course['student_count']; ?> enrolled</span>
          </div>
          <div class="course-card-footer">
            <?php if (!isLoggedIn()): ?>
              <a href="/register" class="btn btn-primary btn-sm btn-full">Sign Up to Enroll</a>
            <?php elseif (isStudent()): ?>
              <a href="/student/courses" class="btn btn-primary btn-sm btn-full">Enroll in Dashboard</a>
            <?php else: ?>
              <span class="text-muted" style="font-size:.85rem"><i class="fas fa-info-circle"></i> Log in as student to enroll</span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
