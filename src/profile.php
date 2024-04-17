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
echo "id: " . $userID;

?>

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
    // Placeholders, once backend is implemented, set these values to fetched values
    const name = "John Doe";
    const email = "johndoe@ufl.edu";
    const profilePicture = "../img/profile_picture.png";
    const games = ['Alabama', 'Georgia', 'Tennessee', 'Auburn', 'LSU', 'Kentucky', 'Mississippi', 'Mississippi State',
        'Vanderbilt'
    ];

    function newListing(number, game, section, row, seat, price) {
        let listing = `<div id="listing-${number}" class="col-lg-3 rounded-xl mr-4 mt-4 p-4 bg-cream">
                                <h3 id="game">${game}</h3><br><br>
                                <b>Section: </b><span id="section">${section}</span><br>
                                <b>Row: </b><span id="row">${row}</span><br>
                                <b>Seat: </b><span id="seat">${seat}</span><br><br>
                                <b>Price: </b><span id="price">${price}</span><br><br>
                                <div class="d-flex justify-content-center">
                                    <input type="button" class="btn btn-primary mr-1" value="Edit">
                                    <input type="button" class="btn btn-primary ml-1" value="Mark Sold">
                                </div>
                            </div>`;
        $("#listings").append(listing);
    }
    $(document).ready(function() {
        $("#name").html(name)
        $("#email").html(email)
        $("#profile_picture").attr("src", profilePicture);

        for (let i = 0; i < 10; i++) {
            //Temporarily generating random values as placeholders, once backend is implemented, replace random values with fetched values.
            let game = games[Math.floor(Math.random() * games.length)];
            let section = Math.floor(Math.random() * 10) + 1;
            let row = Math.floor(Math.random() * 20) + 1;
            let seat = Math.floor(Math.random() * 15) + 1;
            let price = "$" + (Math.floor(Math.random() * 150) + 1);

            newListing(i + 1, game, section, row, seat, price);
        }
    });
    </script>
</head>

<body class="bg-light">
    <div id="nav-placeholder"></div>
    <script>
    $(function() {
        $("#nav-placeholder").load("nav.html #navbar", function(responseTxt, statusTxt, xhr) {
            if (statusTxt == "success")
                $("#nav-profile").addClass("active");
        });
    });
    </script>
    <div id="main" class="container">
        <div class="row ml-3">
            <h2>Profile</h2>
        </div>
        <div class="row align-items-center ml-3">
            <div class="col-md-3">
                <img id="profile_picture" src="../img/profile_picture.png">
            </div>
            <div class="col-md-4">
                <label>Name:</label> <span id="name"></span><br>
                <label>Email:</label> <span id="email"></span><br>
            </div>
        </div>
        <div class="row mt-5"></div>
        <div class="row ml-3">
            <h2>Your Listings:</h2>
        </div>
        <div id="listings" class="row ml-3 justify-content-center">
            <!-- Listings get added here programmatically -->
        </div>
    </div>
</body>

</html>