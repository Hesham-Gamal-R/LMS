<div class="profile-page">
  <div class="profile-header">
    <div class="profile-avatar-wrap">
      <?php if (!empty($student['profile_image'])): ?>
        <img src="/uploads/<?php echo$student['profile_image']; ?>" class="profile-avatar" alt="Profile">
      <?php else: ?>
        <div class="profile-avatar-placeholder"><i class="fas fa-user-graduate"></i></div>
      <?php endif; ?>
    </div>
    <div>
      <h1 class="profile-name"><?php echo $student['name']; ?></h1>
      <span class="profile-role"><i class="fas fa-user-graduate"></i> Student</span>
      <p class="profile-dept"><i class="fas fa-building"></i> <?php echo $student['department']; ?></p>
    </div>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i>
      <ul><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <div class="profile-grid">
    <div class="panel">
      <div class="panel-header"><h2><i class="fas fa-info-circle"></i> Profile Information</h2></div>
      <div class="info-list">
        <div class="info-item"><span class="info-label">Full Name</span><span class="info-value"><?php echo $student['name']; ?></span></div>
        <div class="info-item"><span class="info-label">Email</span><span class="info-value"><?php echo $student['email']; ?></span></div>
        <div class="info-item"><span class="info-label">National ID</span><span class="info-value"><?php echo $student['national_id']; ?></span></div>
        <div class="info-item"><span class="info-label">Age</span><span class="info-value"><?php echo $student['age']; ?></span></div>
        <div class="info-item"><span class="info-label">Department</span><span class="info-value"><?php echo $student['department']; ?></span></div>
        <div class="info-item"><span class="info-label">Member Since</span><span class="info-value"><?php echo date('F j, Y', strtotime($student['created_at'])); ?></span></div>
      </div>
    </div>

    <div class="panel">
      <div class="panel-header"><h2><i class="fas fa-edit"></i> Edit Profile</h2></div>
      <form method="POST" action="/student/profile" enctype="multipart/form-data" class="profile-form">
        <div class="form-group">
          <label class="form-label"><i class="fas fa-user"></i> Full Name</label>
          <input type="text" name="name" class="form-input" value="<?php echo $student['name']; ?>" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label"><i class="fas fa-calendar"></i> Age</label>
            <input type="number" name="age" class="form-input" value="<?php echo $student['age']; ?>" min="16" max="100" required>
          </div>
          <div class="form-group">
            <label class="form-label"><i class="fas fa-building"></i> Department</label>
            <input type="text" name="department" class="form-input" value="<?php echo $student['department']; ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label"><i class="fas fa-camera"></i> Profile Photo <small>(optional)</small></label>
          <div class="file-upload-area" id="avatarUpload">
            <i class="fas fa-cloud-upload-alt"></i>
            <span>Click to upload or drag &amp; drop</span>
            <small>JPG, PNG, WebP — max 5MB</small>
            <input type="file" name="profile_image" accept="image/*" class="file-input">
          </div>
          <div class="file-preview" id="avatarPreview" style="display:none">
            <img id="avatarPreviewImg" src="" alt="">
            <button type="button" id="removeAvatar" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <hr class="form-divider">
        <h4 class="form-section-title"><i class="fas fa-lock"></i> Change Password <small>(leave blank to keep current)</small></h4>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">New Password</label>
            <div class="input-icon-right">
              <input type="password" name="password" id="newPass" class="form-input" placeholder="Min. 6 characters">
              <button type="button" class="toggle-password" data-target="newPass"><i class="fas fa-eye"></i></button>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <div class="input-icon-right">
              <input type="password" name="confirm_password" id="confPass" class="form-input" placeholder="Repeat password">
              <button type="button" class="toggle-password" data-target="confPass"><i class="fas fa-eye"></i></button>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
      </form>
    </div>
  </div>
</div>
