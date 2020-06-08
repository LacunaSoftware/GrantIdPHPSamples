<?php

namespace Src;

require __DIR__ . '/../vendor/autoload.php';

use Src\DecodeUtil;

class AuthenticationService {

    private $tokenService;

    private $keySet;

    private $issuer;

    public function __construct($tokenService, $issuer) {
        $this->tokenService = $tokenService;
        $this->issuer = $issuer;
        $this->keySet = $this->fetchKeySet($issuer);
    }

    private function fetchKeySet($issuer) {
        $openIdUrl = $issuer.'/.well-known/openid-configuration';
        $metadata = DecodeUtil::fetchAndJsonEncode($openIdUrl);

        $jwksUri = $metadata['jwks_uri'];
        $keys = DecodeUtil::fetchAndJsonEncode($jwksUri);

        return $keys;
    }

    public function authenticate() {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            throw new \Exception("Authorization header is missing.");
        }
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];

        preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches);
        if(!isset($matches[1])) {
            throw new \Exception("No bearer token available.");
        }

        $token = $matches[1];
        $decoded = $this->tokenService->validateAndExtractPayload($token, $this->keySet, $this->issuer, 'openid');
        if (!$decoded) {
            throw new \Exception("Provided token is invalid.");
        }

        $_SERVER['user_claims'] = $decoded;
    }
}