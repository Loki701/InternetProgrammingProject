#!/usr/local/bin/php

<html lang="en">

<head>
    <title>Gator Ticket Finder</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link type="text/css" rel="stylesheet" href="./css/index.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body class="bg-light">
    <div id="nav-placeholder"></div>
    <script>
        $(function () {
            $("#nav-placeholder").load("nav.html #navbar", function (responseTxt, statusTxt, xhr) {
                if (statusTxt == "success")
                    $("#nav-home").addClass("active");
            });
        });
    </script>

    <div class="container">
        <br><br><br>
        <h1>
            Gator Ticket Finder Admin
        </h1>
        <p>
            This site will allow you to manage the ticket database.
        </p>
    

        <?php
            require_once("utilities/database.php");
            // Sample Listing: [ListingID] => 1 [UserID] => dev [EventID] => 1 [ListingPrice] => 20.00 [ListingSection] => 42 [ListingRow] => 10 [ListingSeat] => 3 [ListingNegotiable] => 1
            // Sample Event: [EventID] => 1 [EventName] => Gator Football [EventDate] => 2022-09-03 [EventImageFile] => alabama
            // Sample User: [UserID] => dev [UserPassword] => pass

            $connection = connect();


            // Users table
            echo "<h2>Users</h2>";
            $result = getAllUsers($connection);
            echo "<table class='table table-striped'>";
            echo "<tr>";
            echo "<th>UserID</th>";
            echo "<th>UserPassword</th>";
            echo "</tr>";

            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['UserID'] . "</td>";
                echo "<td>" . $row['UserPassword'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";


            // Events table
            echo "<h2>Events</h2>";
            $result = getAllEvents($connection);
            echo "<table class='table table-striped'>";
            echo "<tr>";
            echo "<th>EventID</th>";
            echo "<th>EventName</th>";
            echo "<th>EventDate</th>";
            echo "<th>EventImageFile</th>";
            echo "</tr>";

            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['EventID'] . "</td>";
                echo "<td>" . $row['EventName'] . "</td>";
                echo "<td>" . $row['EventDate'] . "</td>";
                echo "<td>" . $row['EventImageFile'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";


            // Listings table
            echo "<h2>Listings</h2>";
            $result = getAllListings($connection);
            echo "<table class='table table-striped'>";
            echo "<tr>";
            echo "<th>ListingID</th>";
            echo "<th>UserID</th>";
            echo "<th>EventID</th>";
            echo "<th>ListingPrice</th>";
            echo "<th>ListingSection</th>";
            echo "<th>ListingRow</th>";
            echo "<th>ListingSeat</th>";
            echo "<th>ListingNegotiable</th>";
            echo "</tr>";
            
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['ListingID'] . "</td>";
                echo "<td>" . $row['UserID'] . "</td>";
                echo "<td>" . $row['EventID'] . "</td>";
                echo "<td>" . $row['ListingPrice'] . "</td>";
                echo "<td>" . $row['ListingSection'] . "</td>";
                echo "<td>" . $row['ListingRow'] . "</td>";
                echo "<td>" . $row['ListingSeat'] . "</td>";
                echo "<td>" . $row['ListingNegotiable'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            disconnect($connection);
        ?>

    </div> <!-- /container -->


</body>