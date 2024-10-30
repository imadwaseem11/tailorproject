<?php
require_once('function.php');
session_start(); // Start the session

// Handle registration if the form is submitted
if ($_POST) {
    // Sanitize input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phonenumber = htmlspecialchars(trim($_POST['number']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $password = trim($_POST['password']);

    // Validate inputs (add more checks as necessary)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Call the register_user function from function.php
        $result = register_user($username, $email, $phonenumber, $gender, $hashedPassword);

        // Check for errors or success
        if ($result === true) {
            // Set a session variable to indicate success
            $_SESSION['registration_success'] = "Registration successful!";
            header("Location: index.php");
            exit;
        } else {
            $error = $result; // Capture error message
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .text h1 {
            margin-bottom: 10px;
        }
        .form-box {
            padding-top: 10px;
        }
        label, p {
            color: black;
        }
    </style>
</head>

<body>
<div class="top-content">
    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 text">
                    <h1><strong>Register</strong> for an Account</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3 form-box">
                    <div class="form-top">
                        <div class="form-top-left">
                            <h3>Sign Up</h3>
                            <p>Fill in the form below to create an account:</p>
                        </div>
                        <div class="form-top-right">
                            <i class="fa fa-pencil"></i>
                        </div>
                    </div>
                    <div class="form-bottom">
                        <!-- Display error message if any -->
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form action="register.php" method="post">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="number">Phone Number</label>
                                <input type="text" name="number" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </form>

                        <p class="text-center mt-3">Already have an account? <a href="index.php">Log In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?php
            if (isset($_SESSION['registration_success'])) {
                echo htmlspecialchars($_SESSION['registration_success']);
                unset($_SESSION['registration_success']); // Clear the message after displaying
            }
            ?>
        </div>
    </div>
</div>

<!-- Javascript -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.backstretch.min.js"></script>
<script src="assets/js/retina-1.1.0.min.js"></script>
<script src="assets/js/scripts.js"></script>

<script>
    // Show toast if there's a success message
    window.onload = function() {
        var toastEl = document.getElementById('successToast');
        if (toastEl && toastEl.innerText.trim() !== '') {
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    };
</script>

<!--[if lt IE 10]>
    <script src="assets/js/placeholder.js"></script>
<![endif]-->
</body>
</html>
