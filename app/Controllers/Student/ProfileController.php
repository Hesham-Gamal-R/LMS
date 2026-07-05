<?php
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../app/Models/Student.php';

class ProfileController extends Controller {
    public function show(): void {
        $this->requireAuth('student');
        $sm      = new Student();
        $student = $sm->find(currentUserId());
        $this->view('student.profile', ['title' => 'My Profile', 'student' => $student]);
    }

    public function update(): void {
        $this->requireAuth('student');
        if (!$this->isPost()) { $this->redirect('/student/profile'); }

        $id         = currentUserId();
        $sm         = new Student();
        $student    = $sm->find($id);
        $name       = sanitize($this->post('name'));
        $age        = (int)$this->post('age');
        $department = sanitize($this->post('department'));
        $password   = $this->post('password');
        $confirm    = $this->post('confirm_password');

        $errors = [];
        if (empty($name))            $errors[] = 'Name is required.';
        if ($age < 16 || $age > 100) $errors[] = 'Invalid age.';
        if (empty($department))      $errors[] = 'Department is required.';
        if ($password && strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if ($password && $password !== $confirm) $errors[] = 'Passwords do not match.';

        if (!empty($errors)) {
            $this->view('student.profile', ['title' => 'My Profile', 'student' => $student, 'errors' => $errors]);
            return;
        }

        $data = ['name' => $name, 'age' => $age, 'department' => $department];
        if ($password) $data['password'] = password_hash($password, PASSWORD_DEFAULT);

        // Profile image upload
        if (!empty($_FILES['profile_image']['name'])) {
            $path = $this->uploadFile($_FILES['profile_image'], 'images');
            if ($path) {
                // Remove old image
                if ($student['profile_image'] && file_exists(UPLOAD_PATH . $student['profile_image'])) {
                    unlink(UPLOAD_PATH . $student['profile_image']);
                }
                $data['profile_image'] = $path;
            } else {
                $errors[] = 'Invalid image file. Allowed: jpg, jpeg, png, gif, webp.';
                $this->view('student.profile', ['title' => 'My Profile', 'student' => $student, 'errors' => $errors]);
                return;
            }
        }

        $sm->update($id, $data);
        $updated = $sm->find($id);
        $_SESSION['user']      = $updated;
        $_SESSION['user_name'] = $updated['name'];

        $this->flashSuccess('Profile updated successfully.');
        $this->redirect('/student/profile');
    }
}
