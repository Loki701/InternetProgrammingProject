<?php

class Router {
    private $routes = [];

    private function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }
    private function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }
    private function put($path, $handler) {
        $this->routes['PUT'][$path] = $handler;
    }
    private function delete($path, $handler) {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function registerRoutes() {
        // Define routes
        $this->get('get-events', function() {
            return EventController::getEvents();
        });

        $this->get('get-listings', function() {
            return ListingController::getListings();
        });

        $this->post('signup', function() {
            return UserController::registerUser();
        });

        $this->post('login', function() {
            return UserController::authenticateUser();
        });
    }

    public function route($method, $body, $headers) {

        $requestData = json_decode($body, true);
        $action = isset($requestData['action']) ? $requestData['action'] : null;

        if (isset($this->routes[$method][$action])) {

            $handler = $this->routes[$method][$action];

            switch($action){
                case 'get-events':
                    return $handler();
                    break;
                case 'get-listings':
                    return $handler();
                    break;
                case 'signup':
                    $firstName = $requestData['User']['firstName'];
                    $lastName = $requestData['User']['lastName'];
                    $email = $requestData['User']['email'];
                    $password = $requestData['User']['password'];
                    return $handler($firstName, $lastName, $email, $password);
                    break;
                case 'login':
                    $email = $requestData['email'];
                    $password = $requestData['password'];
                    return $handler($email, $password);
                    break;
                default:
                    return $handler();
            }

            return $handler();
        } else {
            // Handle 404 Not Found
            return null;
        }
    }
}

?>