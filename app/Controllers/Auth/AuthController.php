<?php
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../app/Models/Student.php';
require_once __DIR__ . '/../../../app/Models/Doctor.php';

class AuthController extends Controller {

    public function loginForm(): void {
        if (isLoggedIn()) {
            $this->redirect($_SESSION['role'] === 'doctor' ? '/doctor/dashboard' : '/student/dashboard');
        }
        $this->view('auth.login', ['title' => 'Sign In'], 'auth');
    }

    public function login(): void {
        if (!$this->isPost()) { $this->redirect('/login'); }

        $email    = sanitize($this->post('email'));
        $password = $this->post('password');
        $role     = $this->post('role', 'student');

        if (empty($email) || empty($password)) {
            $this->view('auth.login', ['title' => 'Sign In', 'error' => 'Please fill in all fields.', 'old_email' => $email, 'old_role' => $role], 'auth');
            return;
        }

        if ($role === 'doctor') {
            $model = new Doctor();
            $user  = $model->findByEmail($email);
        } else {
            $model = new Student();
            $user  = $model->findByEmail($email);
        }

        if (!$user || !password_verify($password, $user['password'])) {
            $this->view('auth.login', ['title' => 'Sign In', 'error' => 'Invalid email or password.', 'old_email' => $email, 'old_role' => $role], 'auth');
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['role']      = $role;
        $_SESSION['user']      = $user;
        $_SESSION['user_name'] = $user['name'];

        $this->flashSuccess('Welcome back, ' . $user['name'] . '!');
        $this->redirect($role === 'doctor' ? '/doctor/dashboard' : '/student/dashboard');
    }

    public function registerForm(): void {
        if (isLoggedIn()) {
            $this->redirect($_SESSION['role'] === 'doctor' ? '/doctor/dashboard' : '/student/dashboard');
        }
        $this->view('auth.register', ['title' => 'Create Account'], 'auth');
    }

    public function register(): void {
        if (!$this->isPost()) { $this->redirect('/register'); }

        $role       = $this->post('role', 'student');
        $name       = sanitize($this->post('name'));
        $email      = sanitize($this->post('email'));
        $password   = $this->post('password');
        $confirm    = $this->post('confirm_password');
        $age        = (int)$this->post('age');
        $nationalId = sanitize($this->post('national_id'));
        $department = sanitize($this->post('department'));
        $bio        = sanitize($this->post('bio', ''));
 
        $errors = [];
        if (empty($name))       $errors[] = 'Name is required.';
        if (empty($email))      $errors[] = 'Email is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';
        if ($age < 16 || $age > 100) $errors[] = 'Age must be between 16 and 100.';
        if (empty($nationalId)) $errors[] = 'National ID is required.';
        if (empty($department)) $errors[] = 'Department is required.';

        if (!empty($errors)) {
            $this->view('auth.register', [
                'title' => 'Create Account', 'errors' => $errors,
                'old' => compact('name','email','age','nationalId','department','role','bio')
            ], 'auth');
            return;
        }

        if ($role === 'doctor') {
            $model = new Doctor();
            if ($model->findByEmail($email)) {
                $this->view('auth.register', ['title' => 'Create Account', 'errors' => ['Email already registered.'], 'old' => compact('name','email','age','nationalId','department','role','bio')], 'auth');
                return;
            }
            if ($model->findByNationalId($nationalId)) {
                $this->view('auth.register', ['title' => 'Create Account', 'errors' => ['National ID already registered.'], 'old' => compact('name','email','age','nationalId','department','role','bio')], 'auth');
                return;
            }
            $id = $model->insert([
                'name' => $name, 'age' => $age, 'national_id' => $nationalId,
                'department' => $department, 'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'bio' => $bio
            ]);
            $user = $model->find($id);
        } else {
            $model = new Student();
            if ($model->findByEmail($email)) {
                $this->view('auth.register', ['title' => 'Create Account', 'errors' => ['Email already registered.'], 'old' => compact('name','email','age','nationalId','department','role','bio')], 'auth');
                return;
            }
            if ($model->findByNationalId($nationalId)) {
                $this->view('auth.register', ['title' => 'Create Account', 'errors' => ['National ID already registered.'], 'old' => compact('name','email','age','nationalId','department','role','bio')], 'auth');
                return;
            }
            $id = $model->insert([
                'name' => $name, 'age' => $age, 'national_id' => $nationalId,
                'department' => $department, 'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
            $user = $model->find($id);
        }

        session_regenerate_id(true);
        $_SESSION['user_id']   = $id;
        $_SESSION['role']      = $role;
        $_SESSION['user']      = $user;
        $_SESSION['user_name'] = $name;

        $this->flashSuccess('Account created successfully. Welcome, ' . $name . '!');
        $this->redirect($role === 'doctor' ? '/doctor/dashboard' : '/student/dashboard');
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('/login');
    }
}
