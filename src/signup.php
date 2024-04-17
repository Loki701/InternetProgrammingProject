
<?php

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $firstName = $_POST['firstname'];
        $lastName = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['passwordConfirmation'];

        // Validate email using regex
        $emailRegex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
        if (!preg_match($emailRegex, $email)) {
            echo 'Please enter a valid email address.';
            return;
        }

        // Validate password using regex
        $passwordRegex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/';
        if (!preg_match($passwordRegex, $password)) {
            echo 'Password must be at least 8 characters long and contain at least one digit, one lowercase letter, and one uppercase letter.';
            return;
        }

        // Check if the password and confirmation match
        if ($password !== $passwordConfirmation) {
            echo 'Password and confirmation do not match.';
            return;
        }

        // Check if users already exists
        $query = "SELECT * FROM users WHERE UserID = ?";
        $stmt = $dbConnection->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo 'User already exists.';
            return;
        }

        //if not exists then add user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (UserID, UserFirstName, UserLastName, UserPasswordHash) VALUES (?, ?, ?, ?)";
        $stmt = $dbConnection->prepare($query);
        $stmt->bind_param("ssss", $email, $firstName, $lastName, $hashedPassword);
        $stmt->execute();

        // Redirect to login page
        header("Location: login.php");
        die;
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
    <script>
        $(function () {
            $("#nav-placeholder").load("nav.html #navbar", function (responseTxt, statusTxt, xhr) {
                if (statusTxt == "success")
                    $("#nav-profile").addClass("active");
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

                        <!-- First Name -->
                        <div class="input-group col-lg-6 mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white px-4 border-md border-right-0">
                                    <i class="fab fa-twitter"></i>

                                </span>
                            </div>
                            <input id="firstName" type="text" name="firstname" placeholder="First Name"
                                class="form-control bg-white border-left-0 border-md">
                        </div>

                        <!-- Last Name -->
                        <div class="input-group col-lg-6 mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white px-4 border-md border-right-0">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                            </div>
                            <input id="lastName" type="text" name="lastname" placeholder="Last Name"
                                class="form-control bg-white border-left-0 border-md">
                        </div>

                        <!-- Email Address -->
                        <div class="input-group col-lg-12 mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white px-4 border-md border-right-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                            </div>
                            <input id="email" type="email" name="email" placeholder="Email Address"
                                class="form-control bg-white border-left-0 border-md">
                        </div>


                        <!-- Password -->
                        <div class="input-group col-lg-6 mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white px-4 border-md border-right-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                            </div>
                            <input id="password" type="password" name="password" placeholder="Password"
                                class="form-control bg-white border-left-0 border-md">
                        </div>

                        <!-- Password Confirmation -->
                        <div class="input-group col-lg-6 mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white px-4 border-md border-right-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                            </div>
                            <input id="passwordConfirmation" type="text" name="passwordConfirmation"
                                placeholder="Confirm Password" class="form-control bg-white border-left-0 border-md">
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group col-lg-12 mx-auto mb-0 ">
                            <button type='submit' class="btn btn-primary btn-block py-2 btn">
                                <span class="font-weight-bold">Create your account</span>
                            </button>
                        </div>

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