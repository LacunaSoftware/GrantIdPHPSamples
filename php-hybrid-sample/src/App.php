<?php

use Src\OpenIdClient;

class App {
    
    protected $controller = 'Home';

    protected $method = 'index';

    protected $params = [];

    public function __construct() {
        session_start();

        $openIdClient = new OpenIdClient('https://lacuna-dev.grantid.com', 'hybrid-sample', 'Erm6sTU5CkJnBzjA5aWPLqJUCaGL4ILsY3OWTiwRw2s=', "http://localhost:8091/login","openid profile sample-api");
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

        
        if (!$openIdClient->userAuthenticated() && $this->method === 'PrivateRoute' || (isset($url[0]) && $url[0] == 'login')) {
            $openIdClient->authenticate();            
        }

        if (isset($url[0]) && $url[0] === 'logout') {
            $openIdClient->deauthorize('http://localhost:8091/');
        }
        else {
            call_user_func_array([$this->controller, $this->method], $this->params);
        }
    }
}