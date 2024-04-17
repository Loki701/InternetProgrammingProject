<?php

require_once ("../config/database.php");

class AuthService {
    public function authenticate($username, $password) {
         // Hash the password
         $hashedPassword = Utilities::hashFunction($password);

         // Query the database to check if the user exists
         $dbConnection = connect();
         
         // Use prepared statements to prevent SQL injection
         $query = "SELECT * FROM users WHERE UserName=? AND UserPasswordHash=?";
         $stmt = $dbConnection->prepare($query);
         $stmt->bind_param("ss", $username, $hashedPassword);
         $stmt->execute();
         $result = $stmt->get_result();
 
         // Check if the query returned exactly one row
         if ($result->num_rows == 1) {
             // User authenticated successfully
             $user = $result->fetch_assoc();
             // Start session and set session token
             session_start();
             $_SESSION['user_id'] = $user['UserID'];
             $_SESSION['session_token'] = bin2hex(random_bytes(16)); // Generate a random session token
             
             return true;
         } else {
             // Authentication failed
             return false;
         }
 
         // Close prepared statement and database connection
         $stmt->close();
         disconnect($dbConnection);
    }
}
?>

