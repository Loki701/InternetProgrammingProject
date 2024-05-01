#!/usr/local/bin/php

<?php

require_once ("utilities/database.php");
require_once ("utilities/utility_functions.php");
session_start();
$error_message = '';

//checks if the user is already logged in and redirects appropriately
if (check_login()) {
    header("Location: index.php");
    die;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $validInput = true;
    //something was posted
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email using regex
    $emailRegex = '/^[^\s@]+@ufl\\.edu/';
    if (!preg_match($emailRegex, $email)) {
        $error_message .= "! Email must end with '@ufl.edu'<br>";
        $validInput = false;
    }

    // Validate password using regex
    $passwordRegex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/';
    if (!preg_match($passwordRegex, $password)) {
        $error_message .= '! Password must be at least 8 characters long and contain at least one digit, one lowercase letter, and one uppercase letter.<br>';
        $validInput = false;
    }

    if ($validInput) {
        $userID = str_replace('@ufl.edu', '', $email);

        // Query the database to check if the user exists
        $connection = connect();

        if (doesUserExist($connection, $userID) && verifyUserPassword($connection, $userID, $password)) {
            // Start session and set session token
            $_SESSION['session_token'] = bin2hex(random_bytes(16));
            $_SESSION['user_ID'] = $userID;

            // save session token in database
            updateUserToken($connection, $userID, $_SESSION['session_token']);

            disconnect($connection);

            // Redirect to home page
            header("Location: index.php");
            die;
        } else {
            disconnect($connection);
            // Authentication failed
            $error_message = "Wrong username or password!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link type="text/css" rel="stylesheet" href="./css/login.css">
    <title>Log In</title>
</head>

<body>
    <div id="nav-placeholder"></div>
    <script type="text/javascript">
        $(function() {
            $("#nav-placeholder").load("nav.html #navbar", function(responseTxt, statusTxt, xhr) {
                if (statusTxt == "success") {
                    $("#nav-login").addClass("active");
                    $("#nav-profile").addClass("d-none");
                    $("#nav-logout").addClass("d-none");
                }     
            });
        });
    </script>
    <br><br>

    <div id="main-wrapper " class="container bg">
        <div class="row justify-content-center w-100">
            <div class="col-xl-10 ">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="mb-5">
                                        <h3 class="h3 text-theme">Login</h3>
                                    </div>

                                    <h6 class="h5 mb-0">Welcome back!</h6>
                                    <p class="text-muted mt-2 mb-5">Enter your email address and password to access your
                                        account.</p>

                                    <form method='post'>
                                        <div class="form-group">
                                            <label for="email">Email address</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="form-group mb-5">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>

                                        <p style="color: red;"><?php echo $error_message; ?></p>

                                        <button type="submit" class="btn btn-theme">Login</button>
                                        <!--<a href="#l" class="forgot-link float-right text-primary">Forgot password?</a>-->
                                    </form>
                                </div>
                            </div>

                            <div class="col-lg-6 d-none d-lg-inline-block">
                                <div class="account-block rounded-right">
                                    <div class="overlay rounded-right"></div>
                                    <div class="account-testimonial">
                                        <h4 class="text-white mb-4">Such a time save!</h4>
                                        <p class="lead text-white">"Best account I have made for a long time. Can only
                                            recommend it for other students."</p>
                                        <p>- Jose Figueredo</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->

                <p class="text-muted text-center mt-3 mb-4 mb-0">Don't have an account? <a href="./signup.php"
                        class="text-primary ml-1">register</a></p>

                <!-- end row -->

            </div>
            <!-- end col -->
        </div>
        <!-- Row -->
    </div>
</body>

</html>