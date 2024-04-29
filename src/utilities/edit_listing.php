#!/usr/local/bin/php
<?php

require_once ("database.php");
$connection = connect();
echo $_POST['listingNegotiable'];
if ($_POST['listingID'] && $_POST['listingPrice'] && $_POST['listingNegotiable']) {
    $listingID = $_POST['listingID'];
    $listingPrice = $_POST['listingPrice'];
    $listingNegotiable = $_POST['listingNegotiable'] == "true"? "1" : "0";
    echo $listingNegotiable;

    editListingPricing($connection, $listingID, $listingPrice, $listingNegotiable);
}