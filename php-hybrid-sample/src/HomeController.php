<?php

class HomeController extends Controller {

    public function index() {
        $this->view('index');
    }

    public function privateRoute() {
        $claims = json_decode($_SESSION['user_claims'], true);
        
        $data = array(
            'access_token' => $_SESSION['access_token'],
            'id_token' => $_SESSION['id_token'],
            'claims' => $claims
        );
        $this->view('privateRoute', $data);
    }

    public function privacy() {
        $this->view('privacy');
    }

}