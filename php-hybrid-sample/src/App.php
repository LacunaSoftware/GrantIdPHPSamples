<?php

use Jumbojett\OpenIDConnectClient;

class App {
    
    protected $controller = 'Home';

    protected $method = 'index';

    protected $params = [];

    public function __construct() {
        session_start();

        $oidc = new OpenIDConnectClient('https://lacuna-dev.grantid.com', 'hybrid-sample', 'Erm6sTU5CkJnBzjA5aWPLqJUCaGL4ILsY3OWTiwRw2s=');

        $oidc->setRedirectURL('http://localhost:8091/login');
        $oidc->addScope(explode(" ", "openid profile sample-api"));

        $oidc->setVerifyHost(false);
        $oidc->setVerifyPeer(false);

        $oidc->setResponseTypes(array('code id_token'));
        $oidc->addAuthParam(array('response_mode' => 'form_post'));

        if ($oidc->authenticate()) {
            $_SESSION['oidc'] = $oidc;
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

            call_user_func_array([$this->controller, $this->method], $this->params);
        }
    }

}