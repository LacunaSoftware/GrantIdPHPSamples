<?php

use Jumbojett\OpenIDConnectClient;
 
class OpenIdClient {

    private $oidc;
    private $sessionKey = 'Session';

    public function __construct($issuer, $clientId, $clientSecret, $redirectUri, $apiScopes) {
        $this->oidc = OpenIDConnectClient($issuer, $clientId, $clientSecret);

        $oidc->setRedirectURL($redirectUri);
        $oidc->addScope(explode(" ", $apiScopes));

        $oidc->setVerifyHost(false);
        $oidc->setVerifyPeer(false);

        $oidc->setResponseTypes(array('code id_token'));
        $oidc->addAuthParam(array('response_mode' => 'form_post'));
    }

    public function getAuthentication() {
        $userSession = $_SESSION[$this->sessionKey];
        if (!$isset($_SESSION[$this->sessionKey])) {
            throw new \Exception('User session not found.');
        }
        return $userSession;
    }

    public function userAuthenticated() {
        try {
            $userSession = $this->getAuthentication();
            return true;
        }
        catch (\Exception $exception) {
            return false;
        }
    }

    public function authenticate() {
        $this->oidc->authenticate();
        $_SESSION[$this->sessionKey] = $oidc;
    }

    public function authorize() {
        $this->oidc->authorize();
    }
    
    public function deauthorize($postLogoutRedirectUri) {
        $authentication = $this->getAuthentication();
        $idToken = $authentication['id_token'];
        unset($_SESSION[$this->sessionKey]);

        $this->oidc->signOut($idToken, $postLogoutRedirectUri);
    }
}