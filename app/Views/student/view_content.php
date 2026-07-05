<div class="panel">
  <div class="panel-header">
    <h2>
      <i class="fas <?php echo $content['type'] === 'pdf' ? 'fa-file-pdf' : 'fa-play-circle'; ?>"></i>
      <?php echo $content['title']; ?>
    </h2>
    <div style="display:flex;gap:.5rem">
      <a href="/student/course/<?php echo $content['course_id']; ?>" class="btn btn-ghost btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
      <a href="/student/content/<?php echo $content['id']; ?>/download" class="btn btn-primary btn-sm">
        <i class="fas fa-download"></i> Download
      </a>
    </div>
  </div>
  <?php if (!empty($content['description'])): ?>
    <p class="content-description"><?php echo $content['description']; ?></p>
  <?php endif; ?>

  <div class="content-viewer">
    <?php
    $filePath = UPLOAD_PATH . $content['file_path'];
    $fileUrl  = '/uploads/' . $content['file_path'];
    ?>
    <?php if ($content['type'] === 'pdf'): ?>
      <div class="pdf-viewer">
        <iframe src="<?php echo $fileUrl; ?>" class="pdf-iframe" title="<?php echo $content['title']; ?>"></iframe>
      </div>
    <?php elseif ($content['type'] === 'video'): ?>
      <div class="video-viewer">
        <video controls class="video-player" preload="metadata">
          <source src="<?php echo $fileUrl; ?>" type="video/<?php echo pathinfo($content['file_path'], PATHINFO_EXTENSION); ?>">
          Your browser does not support the video tag.
          <a href="<?php echo $fileUrl; ?>">Download the video</a>
        </video>
      </div>
    <?php endif; ?>
  </div>
</div>
