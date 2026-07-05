<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title ?? APP_NAME; ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
  <nav class="nav-container">
    <a href="/" class="nav-brand">
      <i class="fas fa-graduation-cap"></i>
      <span>UniLearn</span>
    </a>
    <div class="nav-links">
      <a href="/courses" class="nav-link">Courses</a>
      <?php if (isLoggedIn()): ?>
        <?php if (isStudent()): ?>
          <a href="/student/dashboard" class="btn btn-primary btn-sm">Dashboard</a>
        <?php else: ?>
          <a href="/doctor/dashboard" class="btn btn-primary btn-sm">Dashboard</a>
        <?php endif; ?>
      <?php else: ?>
        <a href="/login" class="nav-link">Sign In</a>
        <a href="/register" class="btn btn-primary btn-sm">Get Started</a>
      <?php endif; ?>
    </div>
  </nav>
</header>

<main>
<?php $f = flashGet('flash_success'); if ($f): ?>
  <div class="flash flash-success"><i class="fas fa-check-circle"></i> <?php echo $f; ?></div>
<?php endif; ?>
<?php $f = flashGet('flash_error'); if ($f): ?>
  <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> <?php echo $f; ?></div>
<?php endif; ?>
<?php echo $content; ?>
</main>

<footer class="site-footer">
  <div class="footer-container">
    <div class="footer-brand">
      <i class="fas fa-graduation-cap"></i>
      <span>UniLearn</span>
    </div>
    <p class="footer-text">University Learning Management System — Connecting Students &amp; Professors</p>
    <p class="footer-copy">&copy; <?php echo date('Y'); ?> UniLearn. All rights reserved.</p>
  </div>
</footer>
<script src="/assets/js/main.js"></script>
</body>
</html>
