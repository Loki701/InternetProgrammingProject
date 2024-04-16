<?php
require_once ("../config/db_credentials.php");

class UserController {
    public function getUsers(){

    }
    public function createUser(){

    }
    public function authenticateUser($email, $password) {
        // Placeholder code for user authentication
        // This could involve querying the database to verify credentials
        // Assuming you have a users table with email and password columns
        $email = Utilities::sanitizeInput($email);
        $password = Utilities::sanitizeInput($password);

        // Hash the password
        $hashedPassword = hashFunction($password);

        // Query the database to check if the user exists
        $dbConnection = connect();
        
        $query = "SELECT * FROM users WHERE email='$email' AND password='$hashedPassword'";
        $result = $dbConnection->query($query);

        if ($result->num_rows == 1) {
            // User authenticated successfully
            $user = $result->fetch_assoc();
            // Start session and set session token
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['session_token'] = bin2hex(random_bytes(16)); // Generate a random session token
            return true;
        } else {
            // Authentication failed
            return false;
        }
    }
}

?>