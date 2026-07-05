<div class="course-hero">
  <div class="course-hero-info">
    <span class="course-code"><?php echo $course['code']; ?></span>
    <h1><?php echo $course['name']; ?></h1>
    <div class="course-hero-meta">
      <span><i class="fas fa-clock"></i> <?php echo $course['credit_hours']; ?> credit hours</span>
      <span><i class="fas fa-users"></i> <?php echo $course['student_count']; ?> enrolled</span>
      <span class="course-status course-status--<?php echo $course['status']; ?>"><?php echo ucfirst($course['status']); ?></span>
    </div>
    <?php if (!empty($course['description'])): ?><p class="course-hero-desc"><?php echo $course['description']; ?></p><?php endif; ?>
  </div>
  <div class="course-hero-actions">
    <a href="/doctor/course/<?php echo $course['id']; ?>/edit" class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i> Edit</a>
    <form method="POST" action="/doctor/course/<?php echo $course['id']; ?>/delete" style="display:inline">
      <button type="submit" class="btn btn-danger btn-sm"
        onclick="return confirm('Delete this course? This cannot be undone.')">
        <i class="fas fa-trash"></i> Delete
      </button>
    </form>
  </div>
</div>

<div class="course-tabs-wrap">
  <div class="course-tabs" id="courseTabs">
    <button class="tab-btn active" data-tab="materials"><i class="fas fa-file-alt"></i> Materials <span class="tab-count"><?php echo count($contents); ?></span></button>
    <button class="tab-btn" data-tab="students"><i class="fas fa-users"></i> Students <span class="tab-count"><?php echo count($students); ?></span></button>
    <button class="tab-btn" data-tab="announcements"><i class="fas fa-bullhorn"></i> Announcements <span class="tab-count"><?php echo count($announcements); ?></span></button>
    <button class="tab-btn" data-tab="qa"><i class="fas fa-comments"></i> Q&amp;A
      <?php $pending = array_filter($questions, fn($q) => $q['status'] === 'pending'); ?>
      <?php if (count($pending) > 0): ?><span class="tab-count tab-count--warning"><?php echo count($pending); ?></span><?php else: ?><span class="tab-count"><?php echo count($questions); ?></span><?php endif; ?>
    </button>
  </div>
</div>

<div class="tab-content active" id="tab-materials">
  <div class="panel">
    <div class="panel-header">
      <h3><i class="fas fa-upload"></i> Upload New Material</h3>
    </div>
    <form method="POST" action="/doctor/course/<?php echo $course['id']; ?>/content" enctype="multipart/form-data" class="upload-form">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-input" placeholder="e.g. Lecture 3 — Functions">
        </div>
        <div class="form-group form-group--sm">
          <label class="form-label">Type *</label>
          <select name="type" class="form-input form-select" id="contentTypeSelect">
            <option value="pdf">PDF</option>
            <option value="video">Video</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Description <small>(optional)</small></label>
        <input type="text" name="description" class="form-input" placeholder="Short description…">
      </div>
      <div class="form-group">
        <label class="form-label">File *</label>
        <div class="file-upload-area" id="contentUpload">
          <i class="fas fa-cloud-upload-alt"></i>
          <span id="uploadLabel">Click to upload or drag &amp; drop (PDF)</span>
          <small>Max 500MB</small>
          <input type="file" name="file" class="file-input" id="contentFile" accept=".pdf">
        </div>
        <div class="file-selected" id="fileSelected" style="display:none">
          <i class="fas fa-file"></i>
          <span id="fileName"></span>
          <button type="button" id="clearFile" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i></button>
        </div>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Material</button>
    </form>
  </div>

  <?php if (empty($contents)): ?>
    <div class="empty-state"><i class="fas fa-folder-open"></i><p>No materials uploaded yet.</p></div>
  <?php else: ?>
    <div class="contents-list">
      <?php foreach ($contents as $idx => $c): ?>
        <div class="content-item">
          <div class="content-item-icon content-item-icon--<?php echo $c['type']; ?>">
            <i class="fas <?php echo $c['type'] === 'pdf' ? 'fa-file-pdf' : 'fa-play-circle'; ?>"></i>
          </div>
          <div class="content-item-info">
            <span class="content-item-num"><?php echo $idx + 1; ?>.</span>
            <div>
              <h4 class="content-item-title"><?php echo$c['title']; ?></h4>
              <?php if (!empty($c['description'])): ?><p class="content-item-desc"><?php echo$c['description']; ?></p><?php endif; ?>
              <span class="content-item-meta">
                <span class="content-type-badge content-type-badge--<?php echo $c['type']; ?>"><?php echo strtoupper($c['type']); ?></span>
                <span>Order: <?php echo $c['order_num']; ?></span>
                <span><?php echo $c['view_count']; ?> views</span>
                <span><?php echo timeAgo($c['created_at']); ?></span>
              </span>
            </div>
          </div>
          <div class="content-item-actions">
            <a href="/doctor/content/<?php echo $c['id']; ?>/edit" class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i></a>
            <form method="POST" action="/doctor/content/<?php echo $c['id']; ?>/delete" style="display:inline">
              <button type="submit" class="btn btn-danger btn-sm"
                onclick="return confirm('Delete this material?')"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<div class="tab-content" id="tab-students">
  <a href="/doctor/course/<?php echo $course['id']; ?>/students" class="btn btn-ghost btn-sm" style="margin-bottom:1rem">
    <i class="fas fa-expand-alt"></i> Full List
  </a>
  <?php if (empty($students)): ?>
    <div class="empty-state"><i class="fas fa-user-slash"></i><p>No students enrolled yet.</p></div>
  <?php else: ?>
    <div class="students-table-wrap">
      <table class="data-table">
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Department</th><th>Enrolled</th></tr></thead>
        <tbody>
          <?php foreach ($students as $i => $s): ?>
            <tr>
              <td><?php echo $i + 1; ?></td>
              <td>
                <div class="table-user">
                  <?php if (!empty($s['profile_image'])): ?>
                    <img src="/uploads/<?php echo $s['profile_image']; ?>" class="table-avatar" alt="">
                  <?php else: ?>
                    <div class="table-avatar-placeholder"><i class="fas fa-user-graduate"></i></div>
                  <?php endif; ?>
                  <?php echo $s['name']; ?>
                </div>
              </td>
              <td><?php echo $s['email']; ?></td>
              <td><?php echo$s['department']; ?></td>
              <td><?php echo date('M j, Y', strtotime($s['enrolled_at'])); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<div class="tab-content" id="tab-announcements">
  <div class="panel">
    <div class="panel-header"><h3><i class="fas fa-plus-circle"></i> Post Announcement</h3></div>
    <form method="POST" action="/doctor/course/<?php echo $course['id']; ?>/announcement" class="announcement-form">
      <div class="form-row">
        <div class="form-group" style="flex:2">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-input" placeholder="Announcement title…" required>
        </div>
        <div class="form-group">
          <label class="form-label">Type</label>
          <select name="type" class="form-input form-select">
            <option value="general">General</option>
            <option value="assignment">Assignment</option>
            <option value="exam">Exam</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Body *</label>
        <textarea name="body" class="form-input" rows="3" placeholder="Write your announcement…" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Post Announcement</button>
    </form>
  </div>

  <?php if (empty($announcements)): ?>
    <div class="empty-state"><i class="fas fa-bell-slash"></i><p>No announcements yet.</p></div>
  <?php else: ?>
    <div class="announcements-list">
      <?php foreach ($announcements as $a): ?>
        <div class="announcement-item announcement-item--<?php echo $a['type']; ?>">
          <div class="announcement-header">
            <span class="ann-type ann-type--<?php echo $a['type']; ?>"><?php echo ucfirst($a['type']); ?></span>
            <div style="display:flex;gap:.5rem;align-items:center">
              <span class="ann-time"><?php echo timeAgo($a['created_at']); ?></span>
              <form method="POST" action="/doctor/announcement/<?php echo $a['id']; ?>/delete" style="display:inline">
                <button type="submit" class="btn btn-danger btn-sm"
                  onclick="return confirm('Delete this announcement?')"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </div>
          <h4 class="ann-title"><?php echo $a['title']; ?></h4>
          <p class="ann-body"><?php echo nl2br($a['body']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<div class="tab-content" id="tab-qa">
  <?php if (empty($questions)): ?>
    <div class="empty-state"><i class="fas fa-comments"></i><p>No student questions yet.</p></div>
  <?php else: ?>
    <div class="questions-list">
      <?php foreach ($questions as $q): ?>
        <div class="question-item question-item--<?php echo $q['status']; ?>">
          <div class="question-header">
            <div class="question-author">
              <?php if (!empty($q['student_image'])): ?>
                <img src="/uploads/<?php echo$q['student_image']; ?>" class="q-avatar" alt="">
              <?php else: ?>
                <div class="q-avatar-placeholder"><i class="fas fa-user-graduate"></i></div>
              <?php endif; ?>
              <span><?php echo$q['student_name']; ?></span>
            </div>
            <span class="question-status-badge question-status-badge--<?php echo $q['status']; ?>">
              <?php echo $q['status'] === 'answered' ? '<i class="fas fa-check"></i> Answered' : '<i class="fas fa-clock"></i> Pending'; ?>
            </span>
          </div>
          <p class="question-text"><?php echo $q['question_text']; ?></p>
          <?php if ($q['status'] === 'answered'): ?>
            <div class="answer-box">
              <p><?php echo nl2br($q['answer_text']); ?></p>
              <span class="answer-time"><?php echo timeAgo($q['answered_at']); ?></span>
            </div>
          <?php else: ?>
            <div class="answer-form" id="answerForm-<?php echo $q['id']; ?>">
              <form method="POST" action="/doctor/question/<?php echo $q['id']; ?>/answer">
                <div class="form-group">
                  <textarea name="answer_text" class="form-input" rows="2" placeholder="Type your answer…" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-reply"></i> Submit Answer</button>
              </form>
            </div>
          <?php endif; ?>
          <span class="question-time"><?php echo timeAgo($q['created_at']); ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
