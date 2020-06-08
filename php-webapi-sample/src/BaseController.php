<?php

namespace Src;

class BaseController {

    protected function response($value) {
        $result = array('message' => $value);
        $response['body'] = json_encode($result);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        return $response;
    }

    protected function message($value) {
        $response = $this->response($value);
        header($response['status_code_header']);
        return $response['body'];
    }  
}