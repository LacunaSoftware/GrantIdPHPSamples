<?php

class HomeController extends Controller {

    public function index() {
        $this->view('index');
    }

    public function privateRoute() {
        $data = array(
            'access_token' => 'access_token',
            'id_token' => 'id_token',
            'claims' => array(
                'key_1' => 'value_1',
                'key_2' => 'value_2'
            )
        );
        $this->view('privateRoute', $data);
    }

    public function privacy() {
        $this->view('privacy');
    }
}