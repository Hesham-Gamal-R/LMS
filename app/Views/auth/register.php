<div class="auth-container auth-container--wide">
  <div class="auth-card">
    <a href="/" class="auth-logo">
      <i class="fas fa-graduation-cap"></i>
      <span>UniLearn</span>
    </a>
    <h1 class="auth-title">Create Account</h1>
    <p class="auth-subtitle">Join the university portal</p>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <ul><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="/register" class="auth-form">
      <div class="form-group">
        <label class="form-label">Register as</label>
        <div class="role-toggle">
          <label class="role-option">
            <input type="radio" name="role" value="student" <?php echo ($old['role'] ?? 'student') === 'student' ? 'checked' : ''; ?>>
            <span><i class="fas fa-user-graduate"></i> Student</span>
          </label>
          <label class="role-option">
            <input type="radio" name="role" value="doctor" <?php echo ($old['role'] ?? '') === 'doctor' ? 'checked' : ''; ?>>
            <span><i class="fas fa-user-tie"></i> Doctor</span>
          </label>
        </div>
      </div>
 
      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="name"><i class="fas fa-user"></i> Full Name</label>
          <input type="text" id="name" name="name" class="form-input" placeholder="Your full name"
                value="<?php echo $old['name'] ?? ''; ?>">
        </div>
        <div class="form-group">
          <label class="form-label" for="age"><i class="fas fa-calendar"></i> Age</label>
          <input type="number" id="age" name="age" class="form-input" placeholder="22" min="16" max="100"
                value="<?php echo $old['age'] ?? ''; ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="national_id"><i class="fas fa-id-card"></i> National ID</label>
          <input type="text" id="national_id" name="national_id" class="form-input" placeholder="National ID number"
                value="<?php echo $old['nationalId'] ?? ''; ?>">
        </div>
        <div class="form-group">
          <label class="form-label" for="department"><i class="fas fa-building"></i> Department</label>
          <input type="text" id="department" name="department" class="form-input" placeholder="e.g. Computer Science"
                value="<?php echo $old['department'] ?? ''; ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="email"><i class="fas fa-envelope"></i> Email Address</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="your@university.edu"
              value="<?php echo $old['email'] ?? ''; ?>">
      </div>

      <div class="form-group doctor-only" style="display:none">
        <label class="form-label" for="bio"><i class="fas fa-info-circle"></i> Bio <small>(optional)</small></label>
        <textarea id="bio" name="bio" class="form-input" rows="2" placeholder="Brief professional bio..."><?php echo $old['bio'] ?? ''; ?></textarea>
      </div>
 
      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="password"><i class="fas fa-lock"></i> Password</label>
          <div class="input-icon-right">
            <input type="password" id="password" name="password" class="form-input" placeholder="Min. 6 characters">
            <button type="button" class="toggle-password" data-target="password"><i class="fas fa-eye"></i></button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label" for="confirm_password"><i class="fas fa-lock"></i> Confirm Password</label>
          <div class="input-icon-right">
            <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Repeat password">
            <button type="button" class="toggle-password" data-target="confirm_password"><i class="fas fa-eye"></i></button>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-full">
        <i class="fas fa-user-plus"></i> Create Account
      </button>
    </form>

    <p class="auth-footer">Already have an account? <a href="/login">Sign in</a></p>
  </div>
</div>
