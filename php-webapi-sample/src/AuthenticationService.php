<?php

namespace Src;

require __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\JWK;

class AuthenticationService {

    private $tokenService;

    public function __constructor($tokenService) {
        $this->tokenService = $tokenService;
    }

    public function authenticate() {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            throw \Exception("Authorization header is missing.");
        }

        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];

        preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches);

        if(!isset($matches[1])) {
            throw new \Exception("No bearer token available.");
        }
    
        $token = $matches[1];
        $_SERVER['user_authenticated'] = $this->isTokenValid($token);

        if (!$_SERVER['user_authenticated']) {
            throw new \Exception("Provided token is invalid.");
        }
    }
    
    public function isTokenValid($token) {
        $tokenParts = explode('.', $token);
        $decodedToken = array(
            'header' => json_decode($this->base64UrlDecode($tokenParts[0]), true),
            'payload' => json_decode($this->base64UrlDecode($tokenParts[1]), true),
            'signatureProvided' => $this->base64UrlDecode($tokenParts[2])
        );

        $issuer = 'https://signer.grantid.com';
        $keySet = $this->fetchKeySet($issuer);

        $publicKey = JWK::parseKeySet($keySet);
        $decoded = JWT::decode($token, $publicKey, array('RS256'));

        $validToken = $decoded !== null;
        $validToken &= $this->checkAlgorithm($decodedToken['header']);
        $validToken &= $this->checkIssuer($decodedToken['payload'], $issuer);
        $validToken &= $this->checkScope($decodedToken['payload'], 'openid');
 
        if ($validToken) {
            $_SERVER['user_claims'] = $decodedToken['payload'];
        }

        return $validToken;
    }

    private function fetchKeySet($issuer) {
        $openidConfigurationUrl = $issuer.'/.well-known/openid-configuration';
        $metadata = json_decode(file_get_contents($openidConfigurationUrl), true);
        $jwksUri = $metadata['jwks_uri'];
        $keys = json_decode(file_get_contents($jwksUri), true);
        return $keys;
    }

    private function extractPublicKey($header, $keySet) {
        $publicKey = null;
        foreach($keySet['keys'] as $key) {
            if($key['kid'] == $header['kid']) {
                $publicKey = $key;
                break;
            }
        }

        if (!isset($publicKey)) {
            throw new \Exception("No matching KID on pair header and keySet");
        }

        return $publicKey;
    }

    private function checkAlgorithm($header) {
        return $header['alg'] == 'RS256';
    }

    private function validate($token, $publicKey) {
        $result = JWT::decode($token, $publicKey, array('RS256'));
        if (!$result) {
            throw new \Exception("Invalid token");
        }
        return $result;
    }

    private function checkIssuer($payload, $issuer) {
        return $payload['iss'] == $issuer;
    }

    private function checkScope($payload, $scope) {
        return in_array($scope, $payload['scope'], true);
    }

    private function base64UrlDecode($input) {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }
}