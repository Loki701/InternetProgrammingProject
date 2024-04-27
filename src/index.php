#!/usr/local/bin/php
<?php
    session_start();
    require_once("utilities/utility_functions.php");
    $loggedIn = check_login();
?>

<!DOCTYPE html>
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
    <script>
    const games = ['Alabama', 'Georgia', 'Tennessee', 'Auburn', 'LSU', 'Kentucky', 'Mississippi', 'Mississippi State',
        'Vanderbilt'
    ];

    function newEvent(game, number) {
        var event =
            `<div id="event-${number}" class="col-lg-3 card rounded-xl mr-4 mt-4 p-4 bg-cream "><h3 class="text-center" id="game">${game}</h3><br><img src="../img/logos/${game.toLowerCase()}.png"></div>`
        $("#events").append(event);
        $(`#event-${number}`).height($(`#event-${number}`).width() * 4 / 3);
    }

    $(document).ready(function() {
        //Temporarily generating random values as placeholders, once backend is implemented, replace random values with fetched values.
        /*for (let i = 0; i < 10; i++) {
            let game = games[Math.floor(Math.random() * games.length)];
            newEvent(game, i + 1);
        }*/
    });

    $(window).on("resize", function() {
        var count = $("#events").children().length;
        for (let i = 1; i <= count; i++) {
            $(`#event-${i}`).height($(`#event-${1}`).width() * 4 / 3);
        }
    });
    </script>
</head>

<body class="bg-light">
    <div id="nav-placeholder"></div>
    <script type="text/javascript">
        var loggedIn = <?php echo json_encode($loggedIn); ?>;
        $(function() {
            $("#nav-placeholder").load("nav.html #navbar", function(responseTxt, statusTxt, xhr) {
                if (statusTxt == "success") {
                    $("#nav-home").addClass("active");
                    if(loggedIn) {
                        $("#nav-signup").addClass("d-none");
                        $("#nav-login").addClass("d-none");

                        $("#nav-logout").removeClass("d-none");
                        ("#nav-profile").removeClass("d-none");
                    } else {
                        $("#nav-profile").addClass("d-none");
                        $("#nav-logout").addClass("d-none");

                        $("#nav-signup").removeClass("d-none");
                        $("#nav-login").removeClass("d-none");
                    }
                }     
            });
        });
    </script>

    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Welcome to Gator Ticket Finder</h1>
                <p>Find tickets to events at the University of Florida</p>
            </div>
        </div>
        <div class="row ml-3">
            <h2>Upcoming Events:</h2>
        </div>
        <div id="events" class="row ml-3 justify-content-center">
            <!-- Events get added here programmatically -->

            <?php
            require_once ("utilities/database.php");
            $connection = connect();
            $events = getAllEventsWithListingCount($connection);
            while ($event = mysqli_fetch_assoc($events)) {
                $date = new DateTimeImmutable($event['EventDate']);
                $dateString = $date->format('F j, Y');

                echo "<a href='event.php?id=" . $event['EventID'] . "' id='event-" . $event['EventID'],
                    "' class='col-lg-3 card rounded-xl mr-4 mt-4 p-4 bg-cream' style='color: inherit; text-decoration: inherit;'>",
                    "<h3 class='text-center' id='name'>" . $event['EventName'] . "</h3>",
                    "<h6 class='text-center' id='date'>" . $dateString . "</h6>",
                    "<h8 class='text-center text-secondary font-weight-light' id='listing_count'>" . $event['ListingCount'] . " Available Listings</h8><br>",
                    "<img src='../img/logos/" . $event['EventImageFile'] . "'>",
                    "</a>";
            }
            disconnect($connection);
            ?>

        </div>
    </div>
</body>

</html>