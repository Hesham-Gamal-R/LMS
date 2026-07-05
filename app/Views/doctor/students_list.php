<div class="panel">
  <div class="panel-header">
    <h2><i class="fas fa-users"></i> Enrolled Students — <?php echo $course['name']; ?></h2>
    <a href="/doctor/course/<?php echo $course['id']; ?>" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back to Course</a>
  </div>
  <p class="panel-subtitle">
    <span class="course-code"><?php echo $course['code']; ?></span>
    <span><?php echo count($students); ?> student<?php echo count($students) !== 1 ? 's' : ''; ?> enrolled</span>
  </p>

  <?php if (empty($students)): ?>
    <div class="empty-state">
      <i class="fas fa-user-slash"></i>
      <p>No students have enrolled in this course yet.</p>
    </div>
  <?php else: ?>
    <div class="students-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Email</th>
            <th>Department</th>
            <th>Enrolled On</th>
          </tr>
        </thead>
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
                  <span><?php echo $s['name']; ?></span>
                </div>
              </td>
              <td><?php echo $s['email']; ?></td>
              <td><?php echo $s['department']; ?></td>
              <td><?php echo date('M j, Y', strtotime($s['enrolled_at'])); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
