<?php

namespace Src;

use Jumbojett\OpenIDConnectClient;

class OpenIdClient {

    private $oidc;

    public function __construct($issuer, $clientId, $clientSecret, $redirectUri, $apiScopes, $verifyHost = false, $verifyPeer = false) {
        $this->oidc = new OpenIDConnectClient($issuer, $clientId, $clientSecret);

        $this->oidc->setRedirectURL($redirectUri);
        $this->oidc->addScope(explode(" ", $apiScopes));

        $this->oidc->setVerifyHost($verifyHost);
        $this->oidc->setVerifyPeer($verifyPeer);

        $this->oidc->setResponseTypes(array('code id_token'));
        $this->oidc->addAuthParam(array('response_mode' => 'form_post'));
    }

    public function getOidc() {
        return $this->oidc;
    }

    public function userAuthenticated() {
        return array_key_exists('authenticated', $_SESSION) && $_SESSION['authenticated'];
    }

    public function authenticate() {
        $this->oidc->authenticate();
        $_SESSION['access_token'] = $this->oidc->getAccessToken();
        $_SESSION['id_token'] = $this->oidc->getIdToken();
        $_SESSION['user_claims'] = json_encode($this->oidc->getVerifiedClaims());
        $_SESSION['authenticated'] = true;
    }
    
    public function deauthorize($postLogoutRedirectUri) {
        $idToken = $_SESSION['id_token'];
        $_SESSION['authenticated'] = false;
        $_SESSION['id_token'] = null;
        $_SESSION['access_token'] = null;
        $_SESSION['user_claims'] = null;
        $this->oidc->signOut($idToken, $postLogoutRedirectUri);
     
    }
}