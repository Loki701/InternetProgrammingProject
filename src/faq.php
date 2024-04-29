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
    <div class="container">
        <br><br><br>
        <h1>Frequently Asked Questions</h1>
        <br>
        <h2>What is Gator Ticket Swap?</h2> 
        <p>
            This website is a platform for students at the University of Florida to resell their student-section football tickets.
            Oftentimes, students with season passes aren't able to make it to every game.
            Or, simply, plans change. On the other hand, many students would like to buy tickets to invididual games, either for
            themselves or for their guests. In the past, students would post these tickets for sale in various groupchats, but this was messy.
            Gator Ticket Swap is a dedicated site for buying and reselling tickets where you can create and view listings on an interactive page!
        </p>
        <br>
        <h2>Do I need an account?</h2> 
        <p>
            You can view available listings without an account, but you won't be able to access contact information or create listings without one.
        </p>
        <br>
        <h2>How do I create an account?</h2>
        <p>Click on the "Sign Up" button in the navigation bar. Fill in the required information and click "Sign Up".</p>
        <br>
        <h2>Do I need to be a UF student?</h2>
        <p>
            Yes, this site is only for UF students, and you need to use your @ufl.edu email to sign up. Non-students should not have student
            section tickets, and should not buy student section tickets. UF students can, of course, purchase tickets for their family and friends.
        </p>
        <br>
        <h2>How do I create a listing?</h2>
        <p>
            Start by signing in. Then, click the "Sell" button on the navigation bar. You'll see a listing creation form which will ask for various
            information about your ticket. Conveniently, the site will also show you the average current listing price for this game. When you're done,
            click "Confirm" to submit your listing. You can manage your listings from the profile page.
        </p>
        <br>
        <h2>As a seller, how do I transfer a sold ticket?</h2>
        <p>
            To find instructions on how to transfer student section tickets, see
            <a href="https://floridagators.com/sports/2021/8/31/mobile-ticketing-transfer-tickets.aspx" target="_blank">this article</a>
            on the Florida Gators Ticket Office site.
        <br>
        <h2>How do I buy a ticket?</h2>
        <p>
            Find listings by going to the home page and clicking on a game. You can see available listings in a map or list view, and you can
            sort by row and price. When you see a listing you like, click on it to see more details. This will show you the seller's email address,
            where you can reach out to ask more or arrange a transfer.
        </p>
        <br>
        <h2>How do I stay safe when buying and selling tickets?</h2>
        <p>
            The best way to stay safe is to meet in person, at a public place on campus. This way, you can verify the ticket and the payment.
            If you can't meet in person, you can make a transfer through Venmo or Zelle, but be careful of scams. Never send more than
            the agreed-upon amount. Many money transfer services allow you to mark a transaction as a payment for a small fee. This can help
            you if you need to dispute a transaction later.
            <br><br>
            <b>NOTE:</b> We attempt to mitigate any false listings by requiring users to sign up with their @ufl.edu email address. The only contact
            information exchanged, by design, is this email. Buyers should also only reach out to sellers using their ufl email. This way, the
            buyer and seller are tied to their real identity. Remember, not transferring a paid-for ticket, or not transferring an agreed-upon
            payment is <b>theft</b> and can be reported to the university. Theft is not only illegal, but a violation of the student honor code,
            and can result in expulsion.
        </p>

        <br><br><br>

    </div>
</body>

</html>