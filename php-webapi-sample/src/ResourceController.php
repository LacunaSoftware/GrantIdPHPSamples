<?php
namespace Src;

class ResourceController {

    private $uri;
    private $resourceGateway;

    public function __construct($uri, $resourceGateway) {
        $this->uri = $uri;
        $this->resourceGateway = $resourceGateway;
    }

    public function processRequest() {
        $response = null;
        switch ($this->uri) {
            case '/home':
                $response = $this->homeMessage();
                break;
            case '/secret':
                $response = $this->secretMessage();
                break;
            case '/claims':
                $response = $this->claimsResponse();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function message($value) {
        $result = array('message' => $value);
        $response['body'] = json_encode($result);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        return $response;
    }

    private function homeMessage() {
        $value = $this->resourceGateway->home();
        return $this->message($value);
    }

    private function secretMessage() {
        $value = $this->resourceGateway->secret();
        return $this->message($value);
    }

    private function claimsResponse() {
        $value = $this->resourceGateway->claims();
        return $this->message($value);
    }

    private function notFoundResponse() {
        $response['body'] = null;
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        return $response;
    }
}