<div class="panel panel--narrow">
  <div class="panel-header">
    <h2><i class="fas fa-edit"></i> Edit Material</h2>
    <a href="/doctor/course/<?php echo $content['course_id']; ?>" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
  <form method="POST" action="/doctor/content/<?php echo $content['id']; ?>/update" enctype="multipart/form-data">
    <div class="form-group">
      <label class="form-label"><i class="fas fa-tag"></i> Title *</label>
      <input type="text" name="title" class="form-input" value="<?php echo $content['title']; ?>" required>
    </div>
    <div class="form-group">
      <label class="form-label"><i class="fas fa-align-left"></i> Description</label>
      <input type="text" name="description" class="form-input" value="<?php echo $content['description'] ?? ''; ?>" placeholder="Short description…">
    </div>
    <div class="form-group">
      <label class="form-label"><i class="fas fa-sort-numeric-up"></i> Order Number</label>
      <input type="number" name="order_num" class="form-input" value="<?php echo $content['order_num']; ?>" min="1" max="999">
      <span class="form-hint">Controls the display order within the course</span>
    </div>
    <div class="form-group">
      <label class="form-label"><i class="fas fa-file-upload"></i> Replace File <small>(optional — leave blank to keep current)</small></label>
      <div class="current-file-info">
        <i class="fas <?php echo $content['type'] === 'pdf' ? 'fa-file-pdf' : 'fa-file-video'; ?>"></i>
        <span>Current: <?php echo basename($content['file_path']); ?></span>
        <span class="content-type-badge content-type-badge--<?php echo $content['type']; ?>"><?php echo strtoupper($content['type']); ?></span>
      </div>
      <div class="file-upload-area">
        <i class="fas fa-cloud-upload-alt"></i>
        <span>Click to upload new file</span>
        <small>Max 500MB — <?php echo strtoupper($content['type']); ?> only</small>
        <input type="file" name="file" class="file-input"
              accept="<?php echo $content['type'] === 'pdf' ? '.pdf' : 'video/*'; ?>">
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
      <a href="/doctor/course/<?php echo $content['course_id']; ?>" class="btn btn-ghost">Cancel</a>
    </div>
  </form>
</div>
