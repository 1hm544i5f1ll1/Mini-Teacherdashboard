<?php
namespace App\Core;

class Controller {
    protected function view($path, $data = []) {
        View::render($path, $data);
    }

    protected function redirect($path) {
        View::redirect($path);
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
