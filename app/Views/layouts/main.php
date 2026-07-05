<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title ?? APP_NAME; ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="app-body">

<?php
$role     = $_SESSION['role'] ?? '';
$notifCnt = $notifCount ?? 0;
$user     = currentUser();
?>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <a href="/" class="sidebar-brand">
      <i class="fas fa-graduation-cap"></i>
      <span>UniLearn</span>
    </a>
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <div class="sidebar-user">
    <?php if (!empty($user['profile_image'])): ?>
      <img src="/uploads/<?php echo $user['profile_image']; ?>" alt="Profile" class="sidebar-avatar">
    <?php else: ?>
      <div class="sidebar-avatar-placeholder">
        <i class="fas fa-user"></i>
      </div>
    <?php endif; ?>
    <div class="sidebar-user-info">
      <span class="sidebar-user-name"><?php echo $user['name'] ?? ''; ?></span>
      <span class="sidebar-user-role"><?php echo ucfirst($role); ?></span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <?php if ($role === 'student'): ?>
      <a href="/student/dashboard" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'],'/student/dashboard') ? 'active':''; ?>">
        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
      </a>
      <a href="/student/courses" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'],'/student/courses') ? 'active':''; ?>">
        <i class="fas fa-book-open"></i><span>Browse Courses</span>
      </a>
      <a href="/student/profile" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'],'/student/profile') ? 'active':''; ?>">
        <i class="fas fa-user-circle"></i><span>My Profile</span>
      </a>
    <?php elseif ($role === 'doctor'): ?>
      <a href="/doctor/dashboard" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'],'/doctor/dashboard') ? 'active':''; ?>">
        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
      </a>
      <a href="/doctor/courses" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'],'/doctor/courses') ? 'active':''; ?>">
        <i class="fas fa-chalkboard-teacher"></i><span>My Courses</span>
      </a>
      <a href="/doctor/questions" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'],'/doctor/questions') ? 'active':''; ?>">
        <i class="fas fa-question-circle"></i>
        <span>Questions</span>
        <?php if (!empty($pendingCount) && $pendingCount > 0): ?>
          <span class="badge badge-danger"><?php echo $pendingCount; ?></span>
        <?php endif; ?>
      </a>
      <a href="/doctor/profile" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'],'/doctor/profile') ? 'active':''; ?>">
        <i class="fas fa-user-tie"></i><span>My Profile</span>
      </a>
    <?php endif; ?>
    <a href="/logout" class="sidebar-link sidebar-logout">
      <i class="fas fa-sign-out-alt"></i><span>Sign Out</span>
    </a>
  </nav>
</aside>

<div class="main-wrapper">
  <header class="app-header">
    <button class="header-toggle" id="headerToggle">
      <i class="fas fa-bars"></i>
    </button>
    <div class="header-title"><?php echo $title ?? ''; ?></div>
    <div class="header-actions">
      <?php if ($notifCnt > 0): ?>
        <div class="notif-badge">
          <i class="fas fa-bell"></i>
          <span class="notif-count"><?php echo $notifCnt; ?></span>
        </div>
      <?php else: ?>
        <i class="fas fa-bell" style="color:#64748b;font-size:1.1rem;cursor:default"></i>
      <?php endif; ?>
    </div>
  </header>

  <main class="app-content">
    <?php $f = flashGet('flash_success'); if ($f): ?>
      <div class="flash flash-success"><i class="fas fa-check-circle"></i> <?php echo $f; ?></div>
    <?php endif; ?>
    <?php $f = flashGet('flash_error'); if ($f): ?>
      <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> <?php echo $f; ?></div>
    <?php endif; ?>
    <?php echo $content; ?>
  </main>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>
