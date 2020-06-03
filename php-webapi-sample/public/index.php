<?php

require __DIR__ . '/../vendor/autoload.php';

use Src\ResourceController;
use Src\ResourceGateway;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$gateway = new ResourceGateway();
$controller = new ResourceController($_SERVER['REQUEST_URI'], $gateway);

$controller->processRequest();