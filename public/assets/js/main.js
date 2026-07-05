/* ===== UniLearn LMS — main.js ===== */
(function () {
  'use strict';

  /* ----- Sidebar Toggle ----- */
  const sidebar       = document.getElementById('sidebar');
  const headerToggle  = document.getElementById('headerToggle');
  const sidebarToggle = document.getElementById('sidebarToggle');

  function openSidebar()  { sidebar && sidebar.classList.add('open'); }
  function closeSidebar() { sidebar && sidebar.classList.remove('open'); }

  headerToggle  && headerToggle.addEventListener('click', openSidebar);
  sidebarToggle && sidebarToggle.addEventListener('click', closeSidebar);

  // Close sidebar when clicking outside on mobile
  document.addEventListener('click', function (e) {
    if (sidebar && sidebar.classList.contains('open') &&
        !sidebar.contains(e.target) && e.target !== headerToggle) {
      closeSidebar();
    }
  });

  /* ----- Course Tabs ----- */
  const tabBtns    = document.querySelectorAll('.tab-btn');
  const tabContent = document.querySelectorAll('.tab-content');

  tabBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const targetId = 'tab-' + this.dataset.tab;
      tabBtns.forEach(function (b)    { b.classList.remove('active'); });
      tabContent.forEach(function (c) { c.classList.remove('active'); });
      this.classList.add('active');
      const target = document.getElementById(targetId);
      if (target) target.classList.add('active');
    });
  });

  /* ----- Password Toggle ----- */
  document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const targetId = this.dataset.target;
      const input    = document.getElementById(targetId);
      if (!input) return;
      const icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon && icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon && icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
  });

  /* ----- Role Toggle (register page) ----- */
  const roleRadios   = document.querySelectorAll('input[name="role"]');
  const doctorOnlyEl = document.querySelectorAll('.doctor-only');

  function updateDoctorFields() {
    const selected = document.querySelector('input[name="role"]:checked');
    const isDoctor = selected && selected.value === 'doctor';
    doctorOnlyEl.forEach(function (el) {
      el.style.display = isDoctor ? 'block' : 'none';
    });
  }

  if (roleRadios.length) {
    updateDoctorFields();
    roleRadios.forEach(function (r) {
      r.addEventListener('change', updateDoctorFields);
    });
  }

  /* ----- Content type → file accept change ----- */
  const contentTypeSelect = document.getElementById('contentTypeSelect');
  const contentFileInput  = document.getElementById('contentFile');
  const uploadLabel       = document.getElementById('uploadLabel');

  if (contentTypeSelect && contentFileInput) {
    contentTypeSelect.addEventListener('change', function () {
      if (this.value === 'pdf') {
        contentFileInput.accept = '.pdf,application/pdf';
        uploadLabel && (uploadLabel.textContent = 'Click to upload or drag & drop (PDF)');
      } else {
        contentFileInput.accept = 'video/*,.mp4,.mkv,.avi,.mov,.webm';
        uploadLabel && (uploadLabel.textContent = 'Click to upload or drag & drop (Video)');
      }
    });
  }

  /* ----- File selection display ----- */
  const contentFile = document.getElementById('contentFile');
  const fileSelected = document.getElementById('fileSelected');
  const fileNameEl   = document.getElementById('fileName');
  const clearFileBtn = document.getElementById('clearFile');

  if (contentFile && fileSelected && fileNameEl) {
    contentFile.addEventListener('change', function () {
      if (this.files.length) {
        fileNameEl.textContent = this.files[0].name;
        fileSelected.style.display = 'flex';
        document.getElementById('contentUpload') && (document.getElementById('contentUpload').style.opacity = '0.5');
      } else {
        fileSelected.style.display = 'none';
        document.getElementById('contentUpload') && (document.getElementById('contentUpload').style.opacity = '1');
      }
    });
    clearFileBtn && clearFileBtn.addEventListener('click', function () {
      contentFile.value = '';
      fileSelected.style.display = 'none';
      document.getElementById('contentUpload') && (document.getElementById('contentUpload').style.opacity = '1');
    });
  }

  /* ----- Profile image preview ----- */
  const avatarUpload   = document.getElementById('avatarUpload');
  const avatarPreview  = document.getElementById('avatarPreview');
  const avatarPreviewImg = document.getElementById('avatarPreviewImg');
  const removeAvatar   = document.getElementById('removeAvatar');

  if (avatarUpload) {
    const fileInput = avatarUpload.querySelector('.file-input');
    fileInput && fileInput.addEventListener('change', function () {
      if (this.files.length) {
        const reader = new FileReader();
        reader.onload = function (e) {
          if (avatarPreviewImg) avatarPreviewImg.src = e.target.result;
          if (avatarPreview) avatarPreview.style.display = 'flex';
          avatarUpload.style.display = 'none';
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }

  removeAvatar && removeAvatar.addEventListener('click', function () {
    if (avatarPreview)  avatarPreview.style.display  = 'none';
    if (avatarUpload)   avatarUpload.style.display   = '';
    const fi = avatarUpload && avatarUpload.querySelector('.file-input');
    if (fi) fi.value = '';
  });

  /* ----- Questions filter ----- */
  const filterBtns  = document.querySelectorAll('.filter-btn');
  const questionItems = document.querySelectorAll('#questionsList .question-item');

  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      filterBtns.forEach(function (b) { b.classList.remove('active'); });
      this.classList.add('active');
      const filter = this.dataset.filter;
      questionItems.forEach(function (item) {
        if (filter === 'all' || item.dataset.status === filter) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });

  /* ----- Flash auto-dismiss ----- */
  const flashes = document.querySelectorAll('.flash');
  flashes.forEach(function (f) {
    setTimeout(function () {
      f.style.transition = 'opacity .5s ease, max-height .5s ease';
      f.style.opacity    = '0';
      f.style.maxHeight  = '0';
      f.style.overflow   = 'hidden';
      f.style.padding    = '0';
      f.style.margin     = '0';
    }, 4000);
  });

  /* ----- Drag & drop on file upload areas ----- */
  document.querySelectorAll('.file-upload-area').forEach(function (area) {
    area.addEventListener('dragover', function (e) {
      e.preventDefault();
      this.style.borderColor = 'var(--brand)';
      this.style.background  = 'var(--brand-light)';
    });
    area.addEventListener('dragleave', function () {
      this.style.borderColor = '';
      this.style.background  = '';
    });
    area.addEventListener('drop', function (e) {
      e.preventDefault();
      this.style.borderColor = '';
      this.style.background  = '';
      const fileInput = this.querySelector('.file-input');
      if (fileInput && e.dataTransfer.files.length) {
        const dt = new DataTransfer();
        dt.items.add(e.dataTransfer.files[0]);
        fileInput.files = dt.files;
        fileInput.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
  });

  /* ----- Active nav link highlight ----- */
  const currentPath = window.location.pathname;
  document.querySelectorAll('.sidebar-link').forEach(function (link) {
    const href = link.getAttribute('href');
    if (href && href !== '/' && currentPath.startsWith(href)) {
      link.classList.add('active');
    }
  });

}());
