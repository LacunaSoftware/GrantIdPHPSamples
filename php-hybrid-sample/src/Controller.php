<?php

class Controller {
    
    protected function model($model) {
        require_once $model.'.php';
        return new $model;
    }

    protected function view($view, $data = []) {
        require_once 'views/'.$view.'.php';
    }
}