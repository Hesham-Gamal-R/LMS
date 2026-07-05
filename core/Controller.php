<?php
abstract class Controller {
    protected function view(string $view, array $data = [], string $layout = 'main'): void {
        extract($data);
        $viewFile = __DIR__ . '/../app/Views/' . str_replace('.', '/', $view) . '.php';
        $layoutFile = __DIR__ . '/../app/Views/layouts/' . $layout . '.php';

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout && file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
 
    protected function redirect(string $url): void {
        header("Location: " .$url);
        exit;
    }

    protected function json(mixed $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireAuth(string $role = ''): void {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        if ($role && $_SESSION['role'] !== $role) {
            $this->redirect('/');
        }
    }

    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function post(string $key, mixed $default = ''): mixed {
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key, mixed $default = ''): mixed {
        return $_GET[$key] ?? $default;
    }

    protected function flashSuccess(string $msg): void {
        $_SESSION['flash_success'] = $msg;
    }

    protected function flashError(string $msg): void {
        $_SESSION['flash_error'] = $msg;
    }

    protected function uploadFile(array $file, string $subdir): string|false {
        if ($file['error'] !== UPLOAD_ERR_OK) return false;
        if ($file['size'] > MAX_FILE_SIZE) return false;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'mp4', 'mkv', 'avi', 'mov', 'webm'];
        if (!in_array($ext, $allowed)) return false;

        $filename = uniqid('', true) . '.' . $ext;
        $destDir  = UPLOAD_PATH . $subdir . '/';
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);

        if (move_uploaded_file($file['tmp_name'], $destDir . $filename)) {
            return $subdir . '/' . $filename;
        }
        return false;
    }
}
