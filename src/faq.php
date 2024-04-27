#!/usr/local/bin/php
<?php
session_start();
require_once ("utilities/utility_functions.php");
$loggedIn = check_login();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>FAQ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/all_pages.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <div id="nav-placeholder"></div>
    <script type="text/javascript">
        var loggedIn = <?php echo json_encode($loggedIn); ?>;
        $(function () {
            $("#nav-placeholder").load("nav.html #navbar", function (responseTxt, statusTxt, xhr) {
                if (statusTxt == "success") {
                    $("#nav-faq").addClass("active");
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
</body>

</html>