<?php
// Check if function.php exists and is not empty
if (!file_exists('function.php') || filesize('function.php') == 0) {
    header('Location: /install/');
    exit; // Make sure to exit after redirecting
}

require_once('function.php');
session_start(); // Start the session

// Redirect to home if the user is already logged in
if (is_user()) {
    redirect('home.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .pass {
            width: 100%;
            height: 50px;
        }
    </style>
</head>

<body>
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1><strong>ADMIN</strong> Login</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 form-box">
                        <div class="form-top">
                            <div class="form-top-left">
                                <h3>Sign In</h3>
                                <p>Enter your username and password to log in:</p>
                            </div>
                            <div class="form-top-right">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                        <div class="form-bottom">
                            <!-- Display error message if any -->
                            <?php if (!empty($_GET['error'])): ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <?php echo htmlspecialchars($_GET['error']); ?>
                                </div>
                            <?php endif; ?>

                            <form role="form" action="signin_post.php" method="post" class="registration-form">
                                <div class="form-group">
                                    <input type="text" name="username" placeholder="Username" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" placeholder="Password" class="pass form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </form>

                            <!-- Sign Up Link -->
                            <p class="text-center mt-3">Don't have an account? <a href="register.php">Sign Up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/retina-1.1.0.min.js"></script>
    <script src="assets/js/scripts.js"></script>

    <!-- IE10 or older compatibility -->
    <!--[if lt IE 10]>
        <script src="assets/js/placeholder.js"></script>
    <![endif]-->
</body>
</html>
