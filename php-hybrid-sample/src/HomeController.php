<?php

class HomeController extends Controller {

    public function index() {
        $this->view('index');
    }

    public function privateRoute() {
        $this->view('privateRoute');
    }

    public function privacy() {
        $this->view('privacy');
    }
}