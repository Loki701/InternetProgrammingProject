<?php
class Utilities {
    public static function sanitizeInput($input) {
        // Code to sanitize user input
        return htmlspecialchars($input);
    }
    public static function hashFunction($password) {
        // Generate a hashed password using password_hash() function
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $hashedPassword;
    }

    // Other utility functions...
}
?>
