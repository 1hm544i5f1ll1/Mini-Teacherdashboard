<?php
namespace App\Core;

class View {
    public static function render($path, $data = []) {
        extract($data);
        $viewFile = BASE_PATH . '/app/views/' . $path . '.php';
        
        if (!file_exists($viewFile)) {
            die("View file not found: $path");
        }

        require $viewFile;
    }

    public static function redirect($path) {
        header("Location: " . APP_URL . $path);
        exit();
    }
}
