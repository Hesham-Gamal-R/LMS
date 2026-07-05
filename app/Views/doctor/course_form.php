<div class="panel panel--narrow">
  <div class="panel-header">
    <h2><?php echo $course ? '<i class="fas fa-edit"></i> Edit Course' : '<i class="fas fa-plus-circle"></i> Create New Course'; ?></h2>
    <?php if ($course): ?>
      <a href="/doctor/course/<?php echo $course['id']; ?>" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    <?php else: ?>
      <a href="/doctor/courses" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    <?php endif; ?>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i>
      <ul><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <form method="POST" action="<?php echo $course ? '/doctor/course/' . $course['id'] . '/update' : '/doctor/courses/store'; ?>">
    <div class="form-row">
      <div class="form-group">
        <label class="form-label"><i class="fas fa-hashtag"></i> Course Code *</label>
        <input type="text" name="code" class="form-input"
              placeholder="e.g. CS301"
              value="<?php echo $course['code'] ?? $old['code'] ?? ''; ?>">
        <span class="form-hint">Unique identifier for this course</span>
      </div>
      <div class="form-group">
        <label class="form-label"><i class="fas fa-clock"></i> Credit Hours *</label>
        <input type="number" name="credit_hours" class="form-input" min="1" max="10"
              value="<?php echo $course['credit_hours'] ?? $old['creditHours'] ?? 3; ?>">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label"><i class="fas fa-book"></i> Course Name *</label>
      <input type="text" name="name" class="form-input"
            placeholder="e.g. Introduction to Programming"
            value="<?php echo $course['name'] ?? $old['name'] ?? ''; ?>">
    </div>

    <div class="form-group">
      <label class="form-label"><i class="fas fa-align-left"></i> Description <small>(optional)</small></label>
      <textarea name="description" class="form-input" rows="4"
        placeholder="Describe what students will learn in this course…"><?php echo $course['description'] ?? $old['description'] ?? ''; ?></textarea>
    </div>

    <div class="form-group">
      <label class="form-label"><i class="fas fa-toggle-on"></i> Status</label>
      <div class="radio-group">
        <label class="radio-option">
          <input type="radio" name="status" value="active" <?php echo ($course['status'] ?? $old['status'] ?? 'active') === 'active' ? 'checked' : ''; ?>>
          <span><i class="fas fa-check-circle"></i> Active — visible to students</span>
        </label>
        <label class="radio-option">
          <input type="radio" name="status" value="inactive" <?php echo ($course['status'] ?? $old['status'] ?? '') === 'inactive' ? 'checked' : ''; ?>>
          <span><i class="fas fa-pause-circle"></i> Inactive — hidden from students</span>
        </label>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary btn-lg">
        <?php echo $course ? '<i class="fas fa-save"></i> Save Changes' : '<i class="fas fa-plus"></i> Create Course'; ?>
      </button>
      <?php if ($course): ?>
        <a href="/doctor/course/<?php echo $course['id']; ?>" class="btn btn-ghost">Cancel</a>
      <?php endif; ?>
    </div>
  </form>
</div>
