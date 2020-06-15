<?php

use Src\OpenIdClient;

class App {
    
    protected $controller = 'Home';

    protected $method = 'index';

    protected $params = [];

    public function __construct() {
        session_start();

        $openIdClient = new OpenIdClient(
            $_ENV['ISSUER'], $_ENV['CLIENT_ID'], 
            $_ENV['CLIENT_SECRET'], $_ENV['LOGIN_URI'], 
            $_ENV['SCOPE']
        );

        $requestUri = substr($_SERVER['REQUEST_URI'], 1);
            
        $url = explode('/', filter_var(rtrim($requestUri, '/'), FILTER_SANITIZE_URL));

        clearstatcache();
        if ($url[0] != '' && file_exists(__DIR__.'\\'.$url[0].'Controller.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        $controllerName = $this->controller.'Controller';
        require_once $controllerName.'.php';
        $this->controller = new $controllerName;

        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];

        
        $loginRequest = isset($url[0]) && $url[0] == 'login';
        $protectedRequest = $this->protectedRoute($openIdClient);
        if ($protectedRequest || $loginRequest) {
            $openIdClient->authenticate();            
        }

        $logoutRequest = isset($url[0]) && $url[0] === 'logout';
        if ($logoutRequest) {
            $openIdClient->deauthorize($_ENV['POST_LOGOUT_REDIRECT_URI']);
        }
        else {
            call_user_func_array([$this->controller, $this->method], $this->params);
        }
    }

    private function protectedRoute($openIdClient) {
        // Implement here your own logic for protected routes.
        return !$openIdClient->userAuthenticated() && $this->method === 'PrivateRoute';
    }
}