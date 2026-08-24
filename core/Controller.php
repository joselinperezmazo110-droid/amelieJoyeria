<?php
class Controller {
    protected $viewPath = __DIR__ . '/../app/views/';

    protected function render($view, $data = []) {
        $viewFile = $this->viewPath . $view . '.php';
        extract($data);
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "Vista $view no encontrada";
        }
    }

    protected function loadModel($model) {
        if (class_exists($model)) {
            return new $model();
        }
        $file = __DIR__ . '/../app/models/' . $model . '.php';
        if (file_exists($file)) {
            require_once $file;
            return new $model();
        }
        return null;
    }
}
