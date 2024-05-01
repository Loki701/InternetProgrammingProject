#!/usr/local/bin/php
<?php
$verified = false;
$error_message = "";

if ($_GET['uid'] && $_GET['fn'] && $_GET['ln'] && $_GET['hp'] && $_GET['hash']) {
    $secret = "0c_@5e9.ay2'*hn9b8LY";
    $userID = urldecode($_GET['uid']);
    $firstName = urldecode($_GET['fn']);
    $lastName = urldecode($_GET['ln']);
    $hashedPassword = urldecode($_GET['hp']);
    $hash = urldecode($_GET['hash']);

    $tohash = $secret . $userID . $firstName . $lastName . $hashedPassword;
    $expectedHash = md5($tohash);
    if ($expectedHash == $hash) {
        $verified = true;
    } else {
        $error_message = "Invalid verification link";
    }

    if($verified) {
        require_once ("utilities/database.php");
        $connection = connect();
        addUser($connection, $userID, $hashedPassword, $firstName, $lastName);
        disconnect($connection);
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link type="text/css" rel="stylesheet" href="./css/signup.css">
    <title>Sign Up</title>
</head>

<body>
    <!-- Navbar-->
    <div id="nav-placeholder"></div>
    <script type="text/javascript">
        $(function() {
            $("#nav-placeholder").load("nav.html #navbar", function(responseTxt, statusTxt, xhr) {
                if (statusTxt == "success") {
                    $("#nav-signup").addClass("active");
                    $("#nav-profile").addClass("d-none");
                    $("#nav-logout").addClass("d-none");
                }     
            });
        });
    </script>

    <div class="container bg">
        <div class="row py-5 mt-4 align-items-center card-style p-4">
            <!-- For Demo Purpose -->
            <div class="col-md-5 pr-lg-5 mb-5 mb-md-0">
                <img src="https://bootstrapious.com/i/snippets/sn-registeration/illustration.svg" alt=""
                    class="img-fluid mb-3 d-none d-md-block">
                <h1>Create an Account</h1>
                <p class="font-italic text-muted mb-0">Take advantage of all we have to offer by just
                    creating an
                    account.</p>
            </div>

            <!-- Registeration Form -->
            <div class="col-md-7 col-lg-6 ml-auto">
                <form method='post'>
                    <div class="row">
                        <h2>Email Verification<br></h2>
                        
                        <h5 <?php echo $verified? "" : "hidden" ?>><br>Thank you for verifying your email!<br></h5>
                        <br>
                        <h5 <?php echo $verified? "hidden" : "" ?>><br>Check your email for a verification link!<br></h5>
                        <br>
                        <p class="lbl" <?php echo $verified? "hidden" : "" ?>>If you didn't receive the link, or input an incorrect email, you will need to 
                        <a href="signup.php"><b>Register</b></a>
                        again</p>
                        <p class="lbl" <?php echo $verified? "" : "hidden" ?>>Your account has been created. You can now 
                        <a href="login.php"><b>Log in</b></a></p>

                        <p style="color: red;"><?php echo $error_message; ?></p>

                    </div>
                </form>
            </div>
        </div>
        <!-- Already Registered -->
        <p class="text-muted text-center mt-3 mb-4 mb-0">Already Registered? <a href="./login.php"
                class="text-primary ml-1">login</a></p>

    </div>
</body>

</html>