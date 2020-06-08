<?php

namespace Src;

require __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\JWK;

use Src\DecodeUtil;

class JwtService {

    public function validateAndExtractPayload($token, $keySet, $issuer, $scope) {
        try {
            $publicKey = JWK::parseKeySet($keySet);
            $decodedToken = $this->getDecodedToken($token);

            $valuesAreValid = $this->checkAlgorithm($decodedToken['header']);
            $valuesAreValid &= $this->checkIssuer($decodedToken['payload'], $issuer);        
            $valuesAreValid &= $this->checkScope($decodedToken['payload'], $scope);
            
            echo $valuesAreValid;
            return JWT::decode($token, $publicKey, array('RS256'));
        }
        catch (Exception $exception) {
            return false;
        }
    }

    private function getDecodedToken($token) {
        $tokenParts = explode('.', $token);
        $decodedToken = array(
            'header' => json_decode(DecodeUtil::base64UrlDecode($tokenParts[0]), true),
            'payload' => json_decode(DecodeUtil::base64UrlDecode($tokenParts[1]), true),
            'signatureProvided' => DecodeUtil::base64UrlDecode($tokenParts[2])
        );
        return $decodedToken;
    }
    
    private function checkAlgorithm($header) {
        return $header['alg'] == 'RS256';
    }

    private function checkIssuer($payload, $issuer) {
        return $payload['iss'] == $issuer;
    }

    private function checkScope($payload, $scope) {
        return in_array($scope, $payload['scope'], true);
    }
} 