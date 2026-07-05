<?php
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../app/Models/Doctor.php';

class ProfileController extends Controller {
    public function show(): void {
        $this->requireAuth('doctor');
        $dm     = new Doctor();
        $doctor = $dm->find(currentUserId());
        $this->view('doctor.profile', ['title' => 'My Profile', 'doctor' => $doctor]);
    }

    public function update(): void {
        $this->requireAuth('doctor');
        if (!$this->isPost()) { $this->redirect('/doctor/profile'); }

        $id         = currentUserId();
        $dm         = new Doctor();
        $doctor     = $dm->find($id);
        $name       = sanitize($this->post('name'));
        $age        = (int)$this->post('age');
        $department = sanitize($this->post('department'));
        $bio        = sanitize($this->post('bio', ''));
        $password   = $this->post('password');
        $confirm    = $this->post('confirm_password');

        $errors = [];
        if (empty($name))            $errors[] = 'Name is required.';
        if ($age < 18 || $age > 100) $errors[] = 'Invalid age.';
        if (empty($department))      $errors[] = 'Department is required.';
        if ($password && strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if ($password && $password !== $confirm) $errors[] = 'Passwords do not match.';

        if (!empty($errors)) {
            $this->view('doctor.profile', ['title' => 'My Profile', 'doctor' => $doctor, 'errors' => $errors]);
            return;
        }

        $data = ['name' => $name, 'age' => $age, 'department' => $department, 'bio' => $bio];
        if ($password) $data['password'] = password_hash($password, PASSWORD_DEFAULT);

        if (!empty($_FILES['profile_image']['name'])) {
            $path = $this->uploadFile($_FILES['profile_image'], 'images');
            if ($path) {
                if ($doctor['profile_image'] && file_exists(UPLOAD_PATH . $doctor['profile_image'])) {
                    unlink(UPLOAD_PATH . $doctor['profile_image']);
                }
                $data['profile_image'] = $path;
            } else {
                $errors[] = 'Invalid image file.';
                $this->view('doctor.profile', ['title' => 'My Profile', 'doctor' => $doctor, 'errors' => $errors]);
                return;
            }
        }

        $dm->update($id, $data);
        $updated = $dm->find($id);
        $_SESSION['user']      = $updated;
        $_SESSION['user_name'] = $updated['name'];

        $this->flashSuccess('Profile updated successfully.');
        $this->redirect('/doctor/profile');
    }
}
