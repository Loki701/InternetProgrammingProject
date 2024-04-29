#!/usr/local/bin/php
<?php

require_once ("database.php");
$connection = connect();
if ($_POST['listingID']) {
    $listingID = $_POST['listingID'];
    deleteListing($connection, $listingID);
}