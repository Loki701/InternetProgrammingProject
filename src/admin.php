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


    <?php
      require_once ("utilities/database.php");
      require_once ("utilities/utility_functions.php");
      // Sample Listing: [ListingID] => 1 [UserID] => dev [EventID] => 1 [ListingPrice] => 20.00 [ListingSection] => 42 [ListingRow] => 10 [ListingSeat] => 3 [ListingNegotiable] => 1
      // Sample Event: [EventID] => 1 [EventName] => Gator Football [EventDate] => 2022-09-03 [EventImageFile] => alabama
      // Sample User: [UserID] => dev [UserPassword] => pass

      session_start();

      //Check if user is logged in already before validating whether they are admin or not:
      if (!check_login()) {
           header("Location: login.php");
            die;
      }
      
      //Validataion to confirm they are admin or not:
      $userID = $_SESSION['user_ID'];
      $connection = connect();
      $user = getUser($connection, $userID);

      //Note the user ID portion absent a SQL column is hard coded for now:
      if($user["UserID"] != "spallonea" && $user["UserID"] != "matdev" && $user["UserID"] != "kevindaniel" && $user["UserID"] != "j.figueredo"){
        header("Location: index.php");
      }
      
      if ($_POST['action']) {
          if ($_POST['action'] == 'addUser') {
              addUser($connection, $_POST['userID'], $_POST['userPasswordHash'], $_POST['userFirstName'], $_POST['userLastName']);
          } else if ($_POST['action'] == 'addEvent') {
              addEvent($connection, $_POST['eventName'], $_POST['eventDate'], $_POST['eventImageFile']);
          } else if ($_POST['action'] == 'addListing') {
              addListing($connection, $_POST['userID'], $_POST['eventID'], $_POST['listingPrice'], $_POST['listingSection'], $_POST['listingRow'], $_POST['listingSeat'], $_POST['listingNegotiable']);
          } else if ($_POST['action'] == 'deleteUser') {
              deleteUser($connection, $_POST['userID']);
          } else if ($_POST['action'] == 'deleteEvent') {
              deleteEvent($connection, $_POST['eventID']);
          } else if ($_POST['action'] == 'deleteListing') {
              deleteListing($connection, $_POST['listingID']);
          }
          $_POST = array();
      }
    ?>
</head>

<body class="bg-light">
    <div id="nav-placeholder"></div>
    <script>
    $(function() {
        $("#nav-placeholder").load("nav.html #navbar", function(responseTxt, statusTxt, xhr) {
            
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
        // Users table
        echo "<h2>Users</h2>";
        $result = getAllUsers($connection);
        echo "<table class='table table-striped'>";
        echo "<tr>";
        echo "<th>UserID</th>";
        echo "<th>UserPasswordHash</th>";
        echo "<th>UserFirstName</th>";
        echo "<th>UserLastName</th>";
        echo "<th>Token</th>";
        echo "<th>Actions</th>";
        echo "</tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['UserID'] . "</td>";
            echo "<td>" . $row['UserPasswordHash'] . "</td>";
            echo "<td>" . $row['UserFirstName'] . "</td>";
            echo "<td>" . $row['UserLastName'] . "</td>";
            echo "<td>" . $row["UserSessionToken"] . "</td>";
            //echo "<td><a href='admin.php?action=deleteUser&userID=" . $row['UserID'] . "'>Delete</a></td>";
            echo "<td><form action='admin.php' method='post'><input type='hidden' name='action' value='deleteUser'><input type='hidden' name='userID' value='" . $row['UserID'] . "'><input class='btn btn-outline-secondary' type='submit' value='Delete'></form></td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>

        <form action="admin.php" method="post">
            <h5>Add User</h5>
            <input type="hidden" name="action" value="addUser">
            <label for="userID">ID:</label>
            <input type="text" id="userID" name="userID" required>
            <label for="userPasswordHash">Password (hash):</label>
            <input type="text" id="userPasswordHash" name="userPasswordHash" required>
            <label for="userFirstName">First Name:</label>
            <input type="text" id="userFirstName" name="userFirstName" required>
            <label for="userLastName">Last Name:</label>
            <input type="text" id="userLastName" name="userLastName" required>

            <input class="btn btn-outline-primary" type="submit" value="Submit">
        </form>
        <br><br><br>

        <?php
        // Events table
        echo "<h2>Events</h2>";
        $result = getAllEvents($connection);
        echo "<table class='table table-striped'>";
        echo "<tr>";
        echo "<th>EventID</th>";
        echo "<th>EventName</th>";
        echo "<th>EventDate</th>";
        echo "<th>EventImageFile</th>";
        echo "<th>Actions</th>";
        echo "</tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['EventID'] . "</td>";
            echo "<td>" . $row['EventName'] . "</td>";
            echo "<td>" . $row['EventDate'] . "</td>";
            echo "<td>" . $row['EventImageFile'] . "</td>";
            //echo "<td><a href='admin.php?action='deleteEvent'&eventID='" . $row['EventID'] . "'>Delete</a></td>";
            echo "<td><form action='admin.php' method='post'><input type='hidden' name='action' value='deleteEvent'><input type='hidden' name='eventID' value='" . $row['EventID'] . "'><input class='btn btn-outline-secondary' type='submit' value='Delete'></form></td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>

        <form action="admin.php" method="post">
            <h5>Add Event</h5>
            <input type="hidden" name="action" value="addEvent">
            <label for="eventName">Name:</label>
            <input type="text" id="eventName" name="eventName" required>
            <label for="eventDate">Date (yyyy-mm-dd):</label>
            <input type="text" id="eventDate" name="eventDate" required>
            <label for="eventImageFile">ImageFile:</label>
            <input type="text" id="eventImageFile" name="eventImageFile" required>

            <input class="btn btn-outline-primary" type="submit" value="Submit">
        </form>
        <br><br><br>

        <?php
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
        echo "<th>Actions</th>";
        echo "</tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['ListingID'] . "</td>";
            echo "<td>" . $row['UserID'] . "</td>";
            echo "<td>" . $row['EventID'] . "</td>";
            echo "<td>" . $row['ListingPrice'] . "</td>";
            echo "<td>" . $row['ListingSection'] . "</td>";
            echo "<td>" . $row['ListingRow'] . "</td>";
            echo "<td>" . $row['ListingSeat'] . "</td>";
            echo "<td>" . $row['ListingNegotiable'] . "</td>";
            //echo "<td><a href='admin.php?action='deleteListing'&listingID='" . $row['ListingID'] . "'>Delete</a></td>";
            echo "<td><form action='admin.php' method='post'><input type='hidden' name='action' value='deleteListing'><input type='hidden' name='listingID' value='" . $row['ListingID'] . "'><input class='btn btn-outline-secondary' type='submit' value='Delete'></form></td>";
            echo "</tr>";
        }
        echo "</table>";

        disconnect($connection);
        ?>

        <form action="admin.php" method="post">
            <h5>Add Listing</h5>
            <input type="hidden" name="action" value="addListing">
            <label for="userID">UserID:</label>
            <input type="text" id="userID" name="userID" required>
            <label for="eventID">EventID:</label>
            <input type="text" id="eventID" name="eventID" required>
            <label for="listingPrice">Price:</label>
            <input type="text" id="listingPrice" name="listingPrice" required>
            <label for="listingSection">Section:</label>
            <input type="text" id="listingSection" name="listingSection" required>
            <label for="listingRow">Row:</label>
            <input type="text" id="listingRow" name="listingRow" required>
            <label for="listingSeat">Seat:</label>
            <input type="text" id="listingSeat" name="listingSeat" required>
            <label for="listingNegotiable">Negotiable? (0 or 1):</label>
            <input type="text" id="listingNegotiable" name="listingNegotiable" required>

            <input class="btn btn-outline-primary" type="submit" value="Submit">
        </form>

    </div> <!-- /container -->


</body>
