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
    $firstEventImageFile = $row['EventImageFile']
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
            var firstImage = <?php echo json_encode($firstEventImageFile); ?>;
            var firstEventID = <?php echo json_encode($firstEventID); ?>;
            $("#game-image").css("background-image", `url(./../img/logos/${firstImage})`);
            $(`#average-${firstEventID}`).css("display", "inline");

            $('a[name=games]').click(function() {
                const id = $(this).attr('value');
                $("#dropdownMenuButton").html($(this).html());
                $("#game").val(id);
                var imagePath = $(`#eventid-${id}`).attr('value');
                console.log(imagePath);
                $("#game-image").css("background-image", `url(./../img/logos/${imagePath})`);

                $("small[name='averages']").css("display", "none");
                $(`#average-${id}`).css("display", "inline");
            });
        });
    </script>

</head>

<body>
    <!-- Navbar -->
    <div id="nav-placeholder"></div>
    <script type="text/javascript">
        $(function() {
            $("#nav-placeholder").load("nav.html #navbar", function(responseTxt, statusTxt, xhr) {
                if (statusTxt == "success") {
                    $("#nav-sell").addClass("active");
                    $("#nav-signup").addClass("d-none");
                    $("#nav-login").addClass("d-none");
                }     
            });
        });
    </script>
    <div class="container bg">
        <div class="row justify-content-center w-100">
            <div class="col-xl-10 ">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <h3 class="h4 font-weight-bold text-theme mb-5">List Ticket</h3>
                                    <p class="text-muted mt-2 mb-5">Enter details about your ticket below to list your ticket.</p>

                                    <form action="sell.php" method="post">
                                        <input type="hidden" name="action" value="addListing">

                                        <div class="form-group row">
                                            <label for="dro" class="col-sm-3 col-form-label">Game</label>
                                            <div class="col-sm-7 offset-sm-1">
                                                <div id="dropdown" class="dropdown">
                                                    <button class="btn btn-theme dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <?php echo ($firstEventName) ?>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <?php
                                                        $result = getAllEvents($connection);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<a class='dropdown-item' href='javascript:void(0);' name='games' value=" . $row['EventID'] . ">" . $row['EventName'] . "</a>";
                                                            echo "<span class='hidden-span' id='eventid-" . $row['EventID'] . "' value='" . $row['EventImageFile'] . "'></span>"; //hacky way of storing data :sob:
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                    echo ('<input type="hidden" id="game" name="game" required value="' . $firstEventID . '">')
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="section" class="col-sm-3 col-form-label">Section</label>
                                            <div class="col-sm-7 offset-sm-1">
                                                <input type="number" min="23" max="44" class="form-control" id="section" name="section" value="32" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="row" class="col-sm-3 col-form-label">Row</label>
                                            <div class="col-sm-7 offset-sm-1">
                                                <input type="number" min="0" class="form-control" id="row" name="row" value="4" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="seat" class="col-sm-3 col-form-label">Seat</label>
                                            <div class="col-sm-7 offset-sm-1">
                                                <input type="number" min="0" class="form-control" id="seat" name="seat" value="17" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="price" class="col-sm-3 col-form-label">Price</label>
                                            <div class="col-sm-7 offset-sm-1">
                                                <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="20.00"
                                                    onchange="(function(i){i.value=parseFloat(i.value).toFixed(2);})(this)" required>
                                                <?php
                                                $result = getEventsAveragePrices($connection);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<small class='text-muted' name='averages' id='average-" . $row["EventID"] . "' style='display: none;'>Average listing price: $" . number_format($row["AveragePrice"], 2) . "</small>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="negotiable" class="col-sm-4 col-form-label">Negotiable?</label>
                                            <div class="col-sm-2">
                                                <input style="accent-color: #5369f8;" type="checkbox" class="form-control" id="negotiable" name="negotiable">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <input class="btn btn-theme" type="submit" value="Confirm">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-lg-6 d-none d-lg-inline-block">
                                <div id="game-image" class="account-block rounded-right">
                                    <div class="overlay rounded-right"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>