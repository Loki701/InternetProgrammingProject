
<?php
    require_once("db_credentials.php");

    function connect() {
        $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        return $connection;
    }

    function disconnect($connection) {
        if (isset($connection)) {
            mysqli_close($connection);
        }
    }

    function getAllUsers($connection) {
        $query = "SELECT * FROM User";
        $result = mysqli_query($connection, $query);
        return $result;
    }

    function getAllEvents($connection) {
        $query = "SELECT * FROM Event";
        $result = mysqli_query($connection, $query);
        return $result;
    }

    function getAllListings($connection) {
        $query = "SELECT * FROM Listing";
        $result = mysqli_query($connection, $query);
        return $result;
    }

    function addUser($connection, $userID, $userPassword) {
        $query = "INSERT INTO User (UserID, UserPassword) VALUES ('$userID', '$userPassword')";
        $result = mysqli_query($connection, $query);
        return $result;
    }

    function addEvent($connection, $eventName, $eventDate, $eventImageFile) {
        $query = "INSERT INTO Event (EventName, EventDate, EventImageFile) VALUES ('$eventName', '$eventDate', '$eventImageFile')";
        $result = mysqli_query($connection, $query);
        return $result;
    }

    function addListing($connection, $userID, $eventID, $listingPrice, $listingSection, $listingRow, $listingSeat) {
        $query = "INSERT INTO Listing (UserID, EventID, ListingPrice, ListingSection, ListingRow, ListingSeat) VALUES ('$userID', '$eventID', '$listingPrice', '$listingSection', '$listingRow', '$listingSeat')";
        $result = mysqli_query($connection, $query);
        return $result;
    }

    function deleteUser($connection, $userID) {
        $query = "DELETE FROM User WHERE UserID = '$userID'";
        $result = mysqli_query($connection, $query);
        return $result;
    }

    function deleteEvent($connection, $eventID) {
        $query = "DELETE FROM Event WHERE EventID = '$eventID'";
        $result = mysqli_query($connection, $query);
        return $result;
    }

    function deleteListing($connection, $listingID) {
        $query = "DELETE FROM Listing WHERE ListingID = '$listingID'";
        $result = mysqli_query($connection, $query);
        return $result;
    }



?>