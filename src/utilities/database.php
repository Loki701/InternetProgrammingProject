<?php
require_once ("db_credentials.php");

function connect()
{
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    return $connection;
}

function disconnect($connection)
{
    if (isset($connection)) {
        mysqli_close($connection);
    }
}

function getAllUsers($connection)
{
    $query = "SELECT * FROM User";
    $result = mysqli_query($connection, $query);
    return $result;
}

function doesUserExist($connection, $userID)
{
    $query = "SELECT * FROM User WHERE UserID = '$userID'";
    $result = mysqli_query($connection, $query);

    if ($result->num_rows > 0) {
        return true;
    }
    return false;
}

function verifyUserPassword($connection, $userID, $password)
{
    $query = "SELECT * FROM User WHERE UserID = '$userID'";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['UserPasswordHash'])) {
        return true;
    }
    return false;
}

function updateUserToken($connection, $userID, $token)
{
    $query = "UPDATE User SET UserSessionToken='$token' WHERE UserID='$userID'";
    $result = mysqli_query($connection, $query);
}

function getAllEvents($connection)
{
    $query = "SELECT * FROM Event ORDER BY EventDate ASC";
    $result = mysqli_query($connection, $query);
    return $result;
}

function getEvent($connection, $eventID)
{
    $query = "SELECT * FROM Event WHERE EventID = '$eventID'";
    $result = mysqli_query($connection, $query);
    $event = mysqli_fetch_assoc($result);
    return $event;
}

function getAllListings($connection)
{
    $query = "SELECT * FROM Listing";
    $result = mysqli_query($connection, $query);
    return $result;
}

function replaceOrderString($order)
{
    if ($order == "price") {
        $order = "ListingPrice";
    } else if ($order == "row") {
        $order = "ListingRow";
    } else {
        $order = "ListingPrice";
    }
    return $order;
}

function getListingsByEvent($connection, $eventID, $order)
{
    $order = replaceOrderString($order);
    $query = "SELECT * FROM Listing WHERE EventID = '$eventID' ORDER BY $order ASC";
    $result = mysqli_query($connection, $query);
    return $result;
}

function getListingsByEventSection($connection, $eventID, $section, $order)
{
    $order = replaceOrderString($order);
    $query = "SELECT * FROM Listing WHERE EventID = '$eventID' AND ListingSection = '$section' ORDER BY $order ASC";
    $result = mysqli_query($connection, $query);
    return $result;
}

function addUser($connection, $userID, $userPasswordHash, $userFirstName, $userLastName)
{
    $query = "INSERT INTO User (UserID, UserPasswordHash, UserFirstName, UserLastName) VALUES ('$userID', '$userPasswordHash', '$userFirstName', '$userLastName')";
    $result = mysqli_query($connection, $query);
    return $result;
}

function addEvent($connection, $eventName, $eventDate, $eventImageFile)
{
    $query = "INSERT INTO Event (EventName, EventDate, EventImageFile) VALUES ('$eventName', '$eventDate', '$eventImageFile')";
    $result = mysqli_query($connection, $query);
    return $result;
}

function addListing($connection, $userID, $eventID, $listingPrice, $listingSection, $listingRow, $listingSeat, $listingNegotiable)
{
    $query = "INSERT INTO Listing (UserID, EventID, ListingPrice, ListingSection, ListingRow, ListingSeat, ListingNegotiable) VALUES ('$userID', '$eventID', '$listingPrice', '$listingSection', '$listingRow', '$listingSeat', '$listingNegotiable')";
    $result = mysqli_query($connection, $query);
    return $result;
}

function deleteUser($connection, $userID)
{
    $query = "DELETE FROM User WHERE UserID = '$userID'";
    $result = mysqli_query($connection, $query);
    return $result;
}

function deleteEvent($connection, $eventID)
{
    $query = "DELETE FROM Event WHERE EventID = '$eventID'";
    $result = mysqli_query($connection, $query);
    return $result;
}

function deleteListing($connection, $listingID)
{
    $query = "DELETE FROM Listing WHERE ListingID = '$listingID'";
    $result = mysqli_query($connection, $query);
    return $result;
}



?>