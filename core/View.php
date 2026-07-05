<?php
class View {
    public static function render(string $view, array $data = [], string $layout = 'main'): void {
        extract($data);
        $viewFile   = __DIR__ . '/../app/Views/' . $view . '.php';
        $layoutFile = __DIR__ . '/../app/Views/layouts/' . $layout . '.php';

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }
}
