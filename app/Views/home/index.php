<section class="hero">
  <div class="hero-content">
    <div class="hero-badge"><i class="fas fa-university"></i> University LMS</div>
    <h1 class="hero-title">Your University,<br><span>All in One Place</span></h1>
    <p class="hero-desc">Access courses, lecture materials, announcements, and communicate directly with your professors — all from one unified platform.</p>
    <div class="hero-actions">
      <a href="/register" class="btn btn-primary btn-lg"><i class="fas fa-rocket"></i> Get Started</a>
      <a href="/courses" class="btn btn-ghost btn-lg"><i class="fas fa-search"></i> Browse Courses</a>
    </div>
    <div class="hero-stats">
      <div class="stat"><span><?php echo count($courses); ?></span><label>Active Courses</label></div>
      <div class="stat"><span><?php
        $total = 0; foreach($courses as $c) $total += (int)$c['student_count']; echo $total;
      ?></span><label>Enrollments</label></div>
      <div class="stat"><span><?php echo $doctorCount; ?></span><label>Professors</label></div>
    </div>
  </div>
  <div class="hero-visual">
    <div class="hero-card-stack">
      <div class="hero-card hero-card--1"><i class="fas fa-video"></i><span>Video Lectures</span></div>
      <div class="hero-card hero-card--2"><i class="fas fa-file-pdf"></i><span>PDF Materials</span></div>
      <div class="hero-card hero-card--3"><i class="fas fa-comments"></i><span>Q&amp;A with Professors</span></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="section-container">
    <div class="section-header">
      <h2 class="section-title">Available Courses</h2>
      <a href="/courses" class="section-link">View All <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="search-bar">
      <form action="/courses" method="GET" class="search-form">
        <div class="search-input-wrap">
          <i class="fas fa-search"></i>
          <input type="text" name="q" placeholder="Search by professor name..." class="search-input">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
    </div>

    <?php if (empty($courses)): ?>
      <div class="empty-state">
        <i class="fas fa-book-open"></i>
        <p>No courses available yet.</p>
      </div>
    <?php else: ?>
      <div class="courses-grid">
        <?php foreach (array_slice($courses, 0, 6) as $course): ?>
          <div class="course-card">
            <div class="course-card-header">
              <span class="course-code"><?php echo $course['code']; ?></span>
              <span class="course-status course-status--<?php echo $course['status']; ?>"><?php echo ucfirst($course['status']); ?></span>
            </div>
            <h3 class="course-name"><?php echo $course['name']; ?></h3>
            <p class="course-desc"><?php echo mb_substr($course['description'] ?? '', 0, 100); ?><?php echo strlen($course['description'] ?? '') > 100 ? '…' : ''; ?></p>
            <div class="course-meta">
              <span><i class="fas fa-user-tie"></i> <?php echo $course['doctor_name']; ?></span>
              <span><i class="fas fa-clock"></i> <?php echo $course['credit_hours']; ?> hrs</span>
              <span><i class="fas fa-users"></i> <?php echo $course['student_count']; ?></span>
            </div>
            <div class="course-card-footer">
              <?php if (!isLoggedIn()): ?>
                <a href="/register" class="btn btn-primary btn-sm btn-full">Enroll Now</a>
              <?php elseif (isStudent()): ?>
                <a href="/student/courses" class="btn btn-primary btn-sm btn-full">View in Dashboard</a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="features-section">
  <div class="section-container">
    <h2 class="section-title text-center">Everything You Need</h2>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-play-circle"></i></div>
        <h3>Video Lectures</h3>
        <p>Watch or download course videos uploaded directly by your professors from their own devices.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-file-pdf"></i></div>
        <h3>PDF Materials</h3>
        <p>Access and download all lecture notes, slides, and handouts in one organized place.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-bullhorn"></i></div>
        <h3>Announcements</h3>
        <p>Stay updated with course announcements, assignment deadlines, and exam schedules.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-comments"></i></div>
        <h3>Direct Q&amp;A</h3>
        <p>Ask your professor questions directly within each course and get notified when answered.</p>
      </div>
    </div>
  </div>
</section>
