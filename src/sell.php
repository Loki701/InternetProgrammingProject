#!/usr/local/bin/php
<?php
session_start();
require_once("utilities/database.php");
require_once("utilities/utility_functions.php");
if (!check_login()) {
    header("Location: login.php");
    die;
}

$userID = $_SESSION['user_ID'];

$connection = connect();

$result = getAllEvents($connection);
$row = mysqli_fetch_assoc($result);
$firstEventName = $row['EventName'];
$firstEventID = $row['EventID'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create a Listing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link type="text/css" rel="stylesheet" href="./css/sell.css">

    <?php
        if ($_POST['action']) {
            if ($_POST['action'] == 'addListing') {
                $negotiable = isset($_POST['negotiable']) ? "1" : "0";
                addListing($connection, $userID, $_POST['game'], $_POST['price'], $_POST['section'], $_POST['row'], $_POST['seat'], $negotiable);
                header('Location: profile.php');
            }
            $_POST = array();
        }
    ?>
    <script>
        $(document).ready(function() {
            $('a[name=games]').click(function() {
                $("#dropdownMenuButton").html($(this).html());
                $("#game").val($(this).attr('value'));
            });          
        });
        </script>

</head>

<body class="bg-light">
    <!-- Navbar -->
    <div id="nav-placeholder"></div>
    <script>
        $(function() {
            $("#nav-placeholder").load("nav.html #navbar", function(responseTxt, statusTxt, xhr) {
                if (statusTxt == "success")
                    $("#nav-sell").addClass("active");
            });
        });
    </script>
    <div class="container bg-light pt-3 pb-3">
        <div class="row justify-content-center">
            <h2>Post your ticket for sale</h2>
            <br><br><br>
        </div>
        <div class="row col-md-4 bg-white p-4 rounded-xl offset-md-4 justify-content-center">
            <form action="sell.php" method="post">
                <input type="hidden" name="action" value="addListing">

                <div class="form-group row">
                    <label for="dro" class="col-sm-3 col-form-label">Game</label>
                    <div class="col-sm-7 offset-sm-1">
                        <div id="dropdown" class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo($firstEventName)?>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php
                                $result = getAllEvents($connection);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<a class='dropdown-item' href='javascript:void(0);' name='games' value=" . $row['EventID'] . ">" . $row['EventName'] . "</a>";
                                }
                                ?>
                            </div>
                            <?php
                            echo('<input type="hidden" id="game" name="game" required value="' . $firstEventID . '">')
                            ?>
                            
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="section" class="col-sm-3 col-form-label">Section</label>
                    <div class="col-sm-7 offset-sm-1">
                        <input type="number" min="23" max="44" class="form-control" id="section" name="section" placeholder="32" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="row" class="col-sm-3 col-form-label">Row</label>
                    <div class="col-sm-7 offset-sm-1">
                        <input type="number" min="0" class="form-control" id="row" name="row" placeholder="4" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="seat" class="col-sm-3 col-form-label">Seat</label>
                    <div class="col-sm-7 offset-sm-1">
                        <input type="number" min="0" class="form-control" id="seat" name="seat" placeholder="17" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-sm-3 col-form-label">Price</label>
                    <div class="col-sm-7 offset-sm-1">
                        <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" placeholder="20.00" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="negotiable" class="col-sm-3 col-form-label">Negotiable?</label>
                    <div class="col-sm-2 offset-sm-1">
                        <input type="checkbox" class="form-control" id="negotiable" name="negotiable">
                    </div>
                </div>
                <br>
                <div class="form-group row justify-content-center">
                    <div class="col-sm-4">
                        <input class="btn btn-primary" type="submit" value="Confirm">
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>