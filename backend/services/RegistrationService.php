<?php

require_once("../config/database.php");

class RegistrationService {
    public function registerUser($firstName, $lastName, $email, $password) {
        // Hash the password
        $hashedPassword = Utilities::hashFunction($password);

        // Query the database to insert the user
        $dbConnection = connect();

        // Use prepared statements to prevent SQL injection
        $query = "INSERT INTO users (UserID, UserPasswordHash, UserFirstName, UserLastName) VALUES (?, ?, ?, ?)";
        $stmt = $dbConnection->prepare($query);
        $stmt->bind_param("ssss", $email, $hashedPassword, $firstName, $lastName);
        $stmt->execute();

        // Check if the insertion was successful
        $success = $stmt->affected_rows > 0;

        // Close prepared statement and database connection
        $stmt->close();
        disconnect($dbConnection);

        return $success;
    }
}

?>
