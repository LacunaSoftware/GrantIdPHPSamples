<?php

require __DIR__ . '/../vendor/autoload.php';

use Src\ResourceController;
use Src\ResourceGateway;
use Src\AuthenticationService;
use Src\JwtService;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$routes = [
    'resource.home' => [
        'method' => 'GET',
        'expression' => '/home',
        'controller_method' => 'home',
        'authentication_required' => false
    ],
    'resource.secret' => [
        'method' => 'GET',
        'expression' => '/secret',
        'controller_method' => 'secret',
        'authentication_required' => true
    ],
    'resource.claims' => [
        'method' => 'GET',
        'expression' => '/claims',
        'controller_method' => 'claims',
        'authentication_required' => true
    ]
];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER["REQUEST_METHOD"];
$uriParts = explode( '/', $uri );

$routeFound = null;
foreach ($routes as $route) {
    if ($route['method'] == $requestMethod && $route['expression'] == $uri) {
        $routeFound = $route;
        break;
    }
}

if (!$routeFound) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

if ($routeFound['authentication_required']) { 
    try {
        $jwtService = new JwtService();
        $authenticationService = new AuthenticationService($jwtService, 'https://signer.grantid.com');
        $authenticationService->authenticate();
    }
    catch (\Exception $exception) {
        header("HTTP/1.1 401 User Unauthorized");
        exit();
    }
}

$methodName = $routeFound['controller_method'];
$controller = new ResourceController();
$controller->$methodName();

