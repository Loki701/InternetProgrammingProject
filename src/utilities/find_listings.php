#!/usr/local/bin/php

<?php

require_once ("database.php");
$connection = connect();
if ($_POST['eventID'] && $_POST['order']) {
    $eventID = $_POST['eventID'];
    $order = $_POST['order'];

    if ($_POST['section']) {
        $section = $_POST['section'];
        $listings = getListingsByEventSection($connection, $eventID, $section, $order);
    } else {
        $listings = getListingsByEvent($connection, $eventID, $order);
    }

    $listingsArray = array();
    while ($listing = mysqli_fetch_assoc($listings)) {
        $listingsArray[] = $listing;
    }
    echo json_encode($listingsArray);
}