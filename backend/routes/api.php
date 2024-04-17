<?php
require_once 'Router.php';

$router = new Router();
$router->registerRoutes();

// Read the request body
$requestBody = file_get_contents('php://input');
// Get request headers
$requestHeaders = getallheaders();
// Simulate a request
$requestMethod = $_SERVER['REQUEST_METHOD'];
// $requestPath = $_SERVER['REQUEST_URI'];

$response = $router->route($requestMethod, $requestBody, $requestHeaders);

?>