<?php
require_once ('database.php');

function check_login()
{
    if (isset($_SESSION['session_token']) && isset($_SESSION['user_ID'])) {
        $dbConnection = connect();
        // check if session token is valid
        $query = "SELECT * FROM User WHERE UserSessionToken=? AND UserID=?";
        $stmt = $dbConnection->prepare($query);
        $stmt->bind_param("ss", $_SESSION['session_token'], $_SESSION['user_ID']);
        $stmt->execute();
        $result = $stmt->get_result();
        disconnect($dbConnection);


        if ($result->num_rows == 1) {
            return true;
        }

        return false;

    } else {
        return false;
    }
}
?>