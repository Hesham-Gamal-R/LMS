<div class="auth-container">
  <div class="auth-card">
    <a href="/" class="auth-logo">
      <i class="fas fa-graduation-cap"></i>
      <span>UniLearn</span>
    </a>
    <h1 class="auth-title">Sign In</h1>
    <p class="auth-subtitle">Access your university portal</p>

    <?php if (!empty($error)): ?>
      <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error ?></div>
    <?php endif; ?>

    <form method="POST" action="/login" class="auth-form">
      <div class="form-group">
        <label class="form-label">I am a</label>
        <div class="role-toggle">
          <label class="role-option">
            <input type="radio" name="role" value="student" <?php echo ($old_role ?? 'student') === 'student' ? 'checked' : ''; ?>>
            <span><i class="fas fa-user-graduate"></i> Student</span>
          </label>
          <label class="role-option">
            <input type="radio" name="role" value="doctor" <?php echo ($old_role ?? '') === 'doctor' ? 'checked' : ''; ?>>
            <span><i class="fas fa-user-tie"></i> Doctor</span>
          </label>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="email"><i class="fas fa-envelope"></i> Email Address</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="your@university.edu"
              value="<?php echo $old_email ?? '' ?>"  autofocus>
      </div>
 
      <div class="form-group">
        <label class="form-label" for="password"><i class="fas fa-lock"></i> Password</label>
        <div class="input-icon-right">
          <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" >
          <button type="button" class="toggle-password" data-target="password"><i class="fas fa-eye"></i></button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-full">
        <i class="fas fa-sign-in-alt"></i> Sign In
      </button>
    </form>

    <p class="auth-footer">
      Don't have an account? <a href="/register">Create one</a>
    </p>
  </div>

</div>
