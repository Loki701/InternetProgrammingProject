#!/usr/local/bin/php
<?php
session_start();
require_once ("utilities/database.php");
require_once ("utilities/utility_functions.php");
$isLoggedIn = check_login();

$userID = $_SESSION['user_ID'];

$connection = connect();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Gator Ticket Finder</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
    let params = new URLSearchParams(window.location.search);
    let isLoggedIn = <?php echo $isLoggedIn ? "true" : "false"; ?>;
    let prevSection = 0;

    function initialize() {
        showMapView();
        selectSection(29);
        fillListView();
    }

    function showMapView() {
        $("#map-view").show();
        $("#list-view").hide();

        $("#map_view_btn").addClass("active");
        $("#list_view_btn").removeClass("active");
    }

    function selectSection(section = $("#section-label").text()) {
        $.ajax({
            type: "POST",
            url: "utilities/find_listings.php",
            data: {
                eventID: params.get("id"),
                section: section,
                order: $("#sort-listings-map").val()
            },
            success: function(data) {
                populateMapListings(JSON.parse(data));
            }
        });
        $("#section-label").text(section);
        $("#section" + prevSection).css("border", "");
        $("#section" + prevSection).addClass("border border-dark");
        $("#section" + section).css("border", "thick solid black");
        $("#section" + section).removeClass("border border-dark");
        prevSection = section;
    }

    function populateMapListings(listings) {
        $("#listings-map").empty();
        listings.forEach(function(listing) {
            newListing =
                "<li class='list-group-item' " +
                "id='listing-list-" + listing.ListingID +
                "' data-toggle='collapse' data-target='#details-list-" +
                listing.ListingID + "'>" +
                "Row: " + listing.ListingRow + ", Seat " + listing
                .ListingSeat + "" +
                "<div class='float-right'>" +
                "<span class='badge badge-secondary badge-pill'>$" + listing.ListingPrice + "</span>" +
                "<i class='bi bi-chevron-down'></i>" +
                "</div>" +
                "<div id='details-list-" + listing.ListingID + "' class='collapse font-weight-light'>";
            if (listing.ListingNegotiable == 1) {
                newListing += "<div class='float-right'>" +
                    "<span class='badge badge-success'>Negotiable</span>" +
                    "</div>";
            }
            if (isLoggedIn) {
                newListing += "Contact: " + listing.UserID + "@ufl.edu";
            } else {
                newListing += "Contact: " +
                    "<a href='login.php'>Log In</a>" +
                    " to view.";
            }
            newListing += "</div>" + "</li>";
            $("#listings-map").append(newListing);

        });
    }

    function fillListView() {
        $.ajax({
            type: "POST",
            url: "utilities/find_listings.php",
            data: {
                eventID: params.get("id"),
                order: $("#sort-listings-list").val()
            },
            success: function(data) {
                populateListListings(JSON.parse(data));
            }
        });
    }

    function populateListListings(listings) {
        $("#listings-list").empty();
        listings.forEach(function(listing) {
            newListing =
                "<li class='list-group-item' " +
                "id='listing-list-" + listing.ListingID +
                "' data-toggle='collapse' data-target='#details-list-" +
                listing.ListingID + "'>" +
                "Section: " + listing.ListingSection + ", Row: " + listing.ListingRow + ", Seat " + listing
                .ListingSeat + "" +
                "<div class='float-right'>" +
                "<span class='badge badge-secondary badge-pill'>$" + listing.ListingPrice + "</span>" +
                "<i class='bi bi-chevron-down'></i>" +
                "</div>" +
                "<div id='details-list-" + listing.ListingID + "' class='collapse font-weight-light'>";
            if (listing.ListingNegotiable == 1) {
                newListing += "<div class='float-right'>" +
                    "<span class='badge badge-success'>Negotiable</span>" +
                    "</div>";
            }
            if (isLoggedIn) {
                newListing += "Contact: " + listing.UserID + "@ufl.edu";
            } else {
                newListing += "Contact: " +
                    "<a href='login.php'>Log In</a>" +
                    " to view.";
            }
            newListing += "</div>" + "</li>";
            $("#listings-list").append(newListing);

        });
    }

    function showListView() {
        fillListView();

        $("#map-view").hide();
        $("#list-view").show();

        $("#map_view_btn").removeClass("active");
        $("#list_view_btn").addClass("active");
    }
    </script>

    <?php
    $eventID = $_GET['id'];
    $event = getEvent($connection, $eventID);
    ?>
</head>

<body onload="initialize();">
    <div id="nav-placeholder"></div>
    <script type="text/javascript">
        var loggedIn = <?php echo json_encode($isLoggedIn); ?>;
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

    <div class="container bg-white">
        <br>
        <h2 id="event-name">
            <?php echo $event['EventName']; ?>
        </h2>
        <h4 id="event-date"><small>
                <?php
                $date = new DateTimeImmutable($event['EventDate']);
                $dateString = $date->format('F j, Y');
                echo $dateString;
                ?>
            </small></h4>
        <br>

        <div class="btn-group">
            <button type="button" class="btn btn-secondary active" id="map_view_btn"
                onclick="showMapView();">Map</button>
            <button type="button" class="btn btn-secondary" id="list_view_btn" onclick="showListView();">List</button>
        </div>
        <br><br><br>

        <div class="row" id="map-view">
            <div class="col-12 col-lg-8">
                <div class="row">
                    <div class="col-4"><br><br><br><br></div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(29);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section29">29</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(31);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section31">31</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(33);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section33">33</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(35);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section35">35</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(37);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section37">37</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(39);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section39">39</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(41);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section41">41</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(43);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section43">43</div>
                </div> <!-- section row 1 -->

                <div class="row">
                    <div class="col-1"><br><br><br><br></div>
                    <div class="col-2 border border-dark text-center" onclick="selectSection(26);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section26">26</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(28);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section28">28</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(30);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section30">30</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(32);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section32">32</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(34);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section34">34</div>
                    <div class="col-2 border border-dark border-bottom-0"></div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(40);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section40">40</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(42);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section42">42</div>
                    <div class="col-1 border border-dark text-center" onclick="selectSection(44);"
                        style="cursor: pointer; display: grid; place-content: center;" id="section44">44</div>
                </div> <!-- section row 2 -->

                <div class="row">
                    <div class="col-3 text-center">
                        <div class="row h-100">
                            <div class="col-12 h-20 d-inline-block border border-dark text-center"
                                onclick="selectSection(25);"
                                style="cursor: pointer; display: grid; place-content: center;" id="section25">25</div>
                            <div class="col-12 border border-dark text-center" onclick="selectSection(24);"
                                style="cursor: pointer; display: grid; place-content: center;" id="section24">24</div>
                            <div class="col-12 border border-dark text-center" onclick="selectSection(23);"
                                style="cursor: pointer; display: grid; place-content: center;" id="section23">23</div>
                            <div class="col-12"></div>
                            <div class="col-12"></div>
                            <div class="col-12"></div>
                            <div class="col-12"></div>
                        </div>
                    </div>
                    <div class="col-9 text-center">
                        <img src="../img/field.png" class="img-fluid" alt="Responsive image" style="padding:10px">
                        <br><br>
                    </div>

                </div> <!-- section/field row -->
            </div> <!-- map column -->

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>
                            Section <span id="section-label">29</span>
                            <div class="float-right small">
                                <label for="sort-listings-map"><i class="bi bi-sort-down"></i></label>
                                <select id="sort-listings-map" onchange="selectSection();">
                                    <option value="price">Price</option>
                                    <option value="row">Row</option>
                                </select>
                            </div>
                        </h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        <ul class="list-group" id="listings-map">
                            <li class="list-group-item" id="listing-map-1" data-toggle="collapse"
                                data-target="#details-map-1">
                                Row 5, Seat 20
                                <div class="float-right">
                                    <span class="badge badge-secondary badge-pill">$100</span>
                                    <i class="bi bi-chevron-down"></i>
                                </div> <!-- text-right -->
                                <div id="details-map-1" class="collapse">
                                    Contact: johndoe@ufl.edu
                                </div>
                            </li>
                        </ul>
                    </div> <!-- map listings card body -->
                </div> <!-- map listings card -->
            </div> <!-- event details column -->
        </div> <!-- map row -->

        <div class="card" id="list-view">
            <div class="card-header">
                <h5>
                    Section 29
                    <div class="float-right small">
                        <label for="sort-listings-list"><i class="bi bi-sort-down"></i></label>
                        <select id="sort-listings-list" onchange="fillListView();">
                            <option value="price">Price</option>
                            <option value="row">Row</option>
                        </select>
                    </div>
                </h5>
            </div>
            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                <ul class="list-group" id="listings-list">
                    <li class="list-group-item" id="listing-list-1" data-toggle="collapse"
                        data-target="#details-list-1">
                        Section 29, Row 5, Seat 20
                        <div class="float-right">
                            <span class="badge badge-secondary badge-pill">$100</span>
                            <i class="bi bi-chevron-down"></i>
                        </div> <!-- text-right -->
                        <div id="details-list-1" class="collapse">
                            Contact: johndoe@ufl.edu
                        </div>
                    </li>
                </ul>
            </div> <!-- list listings card body -->
        </div> <!-- list listings card -->



        <br><br><br><br><br>
    </div> <!-- container -->


</body>

</html>