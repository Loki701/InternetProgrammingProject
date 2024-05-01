#!/usr/local/bin/php
<?php
session_start();

require_once ("utilities/utility_functions.php");
if (!check_login()) {
    header("Location: login.php");
    die;
}

// Can use session variables to get user data and display it
$userID = $_SESSION['user_ID'];
require_once ("utilities/database.php");
$connection = connect();
$user = getUser($connection, $userID);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link type="text/css" rel="stylesheet" href="./css/profile.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    const profilePicture = "../img/profile_picture.png";

    function startEdit(id) {
        $("#price-" + id).hide();
        $("#edit-price-" + id).val($("#price-" + id).text());
        $("#edit-price-" + id).show();
        $("#negotiable-" + id).hide();
        $("#edit-negotiable-" + id).prop("checked", $("#negotiable-" + id).text() == "Yes");
        $("#edit-negotiable-" + id).show();
        $("#edit-button-" + id).hide();
        $("#save-button-" + id).show();
        $("#sold-button-" + id).hide();
        $("#delete-button-" + id).show();
    }

    function saveEdit(id) {
        let price = $("#edit-price-" + id).val();
        let negotiable = $("#edit-negotiable-" + id).prop("checked");
        $("#price-" + id).text(price);
        $("#price-" + id).show();
        $("#edit-price-" + id).hide();
        $("#negotiable-" + id).text(negotiable ? "Yes" : "No");
        $("#negotiable-" + id).show();
        $("#edit-negotiable-" + id).hide();
        $("#edit-button-" + id).show();
        $("#save-button-" + id).hide();
        $("#sold-button-" + id).show();
        $("#delete-button-" + id).hide();

        $.ajax({
            type: "POST",
            url: "utilities/edit_listing.php",
            data: {
                listingID: id,
                listingPrice: price,
                listingNegotiable: negotiable
            },
            success: function(response) {
                console.log(response);
            }
        });
    }

    function deleteListing(id) {
        $.ajax({
            type: "POST",
            url: "utilities/delete_listing.php",
            data: {
                listingID: id
            },
            success: function(response) {
                console.log(response);
                $("#listing-" + id).remove();
            }
        });
    }

    function newListing(id, game, section, row, seat, price, negotiable) {
        let listing = `<div id="listing-${id}" class="col-lg-3 rounded-xl mr-4 mt-4 p-4">
                                <h3 id="game">${game}</h3><br>
                                <b>Section: </b><span id="section">${section}</span><br>
                                <b>Row: </b><span id="row">${row}</span><br>
                                <b>Seat: </b><span id="seat">${seat}</span><br><br>
                                <b>Price: </b><span id="price-${id}">${price}</span>
                                <input type="number" step="0.01" min="0" class="" id="edit-price-${id}" name="price" style="display: none;"
                                    onchange="(function(i){i.value=parseFloat(i.value).toFixed(2);})(this)"></input><br>
                                <b>Negotiable: </b><span id="negotiable-${id}">${negotiable == "1"? "Yes" : "No"}</span>
                                <input style="accent-color: #5369f8; display: none;" type="checkbox" class="" id="edit-negotiable-${id}" name="negotiable"><br><br>
                                <div class="d-flex justify-content-center">
                                    <input id="edit-button-${id}" type="button" name="editListing" class="btn btn-primary mr-1" value="Edit" onclick="startEdit(${id});">
                                    <input id="save-button-${id}" type="button" name="editListing" class="btn btn-primary mr-1" value="Save" style="display: none;" onclick="saveEdit(${id});">
                                    <input id="sold-button-${id}"  type="button" class="btn btn-primary ml-1" value="Mark Sold" onclick="deleteListing(${id});">
                                    <input id="delete-button-${id}"  type="button" class="btn btn-primary ml-1" value="Delete" onclick="deleteListing(${id});" style="display: none;">
                                </div>
                            </div>`;
        $("#listings").append(listing);
    }
    $(document).ready(function() {
        $("#profile_picture").attr("src", profilePicture);
    });
    </script>
</head>

<body class="bg-light">
    <div id="nav-placeholder"></div>
    <script>
        var loggedIn = <?php echo json_encode($loggedIn); ?>;
        $(function() {
            $("#nav-placeholder").load("nav.html #navbar", function(responseTxt, statusTxt, xhr) {
                if (statusTxt == "success") {
                    $("#nav-profile").addClass("active");
                    $("#nav-signup").addClass("d-none");
                    $("#nav-login").addClass("d-none");
                } 
                
            });
        });
    </script>
    <div id="main" class="container">
        <div>
            <h2>Profile</h2>
        </div>
        <div class="row align-items-center ml-3">
            <div class="col-md-3">
                <img id="profile_picture" src="../img/profile_picture.png" alt="Profile Picture">
            </div>
            <div class="col-md-4">
                <label><b>Name:</b></label> <span id="name">
                    <?php echo $user['UserFirstName'] . ' ' . $user['UserLastName']; ?>
                </span><br>
                <label><b>Email:</b></label> <span id="email">
                    <?php echo $user['UserID'] . '@ufl.edu'; ?>
                </span><br>
            </div>
        </div>
        <div class="row mt-5"></div>
        <div class="row ml-3">
            <h2>Your Listings:</h2>
        </div>
        <div id="listings" class="row ml-3 justify-content-center">
            <!-- Listings get added here programmatically -->
        </div>
        <script>
            <?php
            $listings = getListingsByUser($connection, $userID);
            while ($listing = mysqli_fetch_assoc($listings)) {
                echo "newListing(" . $listing['ListingID'] . ", '" . $listing['EventName'] . "', '" . $listing['ListingSection'] . "', '" . $listing['ListingRow'] . "', '" . $listing['ListingSeat'] . "', '" . $listing['ListingPrice'] .  "', '" . $listing['ListingNegotiable'] . "');";
            }
            disconnect($connection);
            ?>
        </script>
    </div>
</body>

</html>