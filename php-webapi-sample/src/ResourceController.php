<?php

namespace Src;

use Src\BaseController;

class ResourceController extends BaseController {

    public function home() {
        echo $this->message("Home");
    }

    public function secret() {
        echo $this->message("Secret");
    }

    public function claims() {
        echo json_encode($_SERVER['user_claims']);
    }
}