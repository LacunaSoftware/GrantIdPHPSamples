<?php

require_once __DIR__.'/../vendor/autoload.php';

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class Controller {
    
    protected function model($model) {
        require_once $model.'.php';
        return new $model;
    }

    protected function view($view, $data = []) {
        $data['authenticated'] = array_key_exists('authenticated', $_SESSION) && $_SESSION['authenticated'];
        $loader = new FileSystemLoader(__DIR__.'/../public/html');
        $twig = new \Twig\Environment($loader);
        echo $twig->render($view.'.html', $data);
    }
}