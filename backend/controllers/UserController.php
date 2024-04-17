<?php
require_once ("../config/db_credentials.php");

class UserController {
    public function registerUser($firstName, $lastName, $email, $password) {
        
        $firstName = Utilities::sanitizeInput($firstName);
        $lastName = Utilities::sanitizeInput($lastName);
        $email = Utilities::sanitizeInput($email);
        $password = Utilities::sanitizeInput($password);

        return RegistrationService::registerUser($firstName, $lastName, $email, $password);
        
    }
    public function authenticateUser($email, $password) {

        $email = Utilities::sanitizeInput($email);
        $password = Utilities::sanitizeInput($password);

        return AuthService::authenticate($email, $password);
    }

    
}

?>