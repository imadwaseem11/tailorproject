<?php
require_once('function.php');
dbconnect();
session_start();

if (!is_user()) {
    redirect('index.php');
}

$user = $_SESSION['username'];
$usid = $pdo->query("SELECT id FROM users WHERE username='" . $user . "'");
$usid = $usid->fetch(PDO::FETCH_ASSOC);
$uid = $usid['id'];
include('header.php');
?>

<!-- Custom CSS for styling -->
<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Ensures the body takes full viewport height */
        margin: 0; /* Removes default margin */
    }

    #page-wrapper {
        flex: 1; /* Makes the main content area take the remaining height */
        padding: 20px; /* Optional padding */
    }

    .form-control {
        width: 100%;
        max-width: 600px;
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 16px;
        transition: all 0.3s ease;
        margin: 10px auto; /* Center alignment */
        display: block;
    }

    .form-control:focus {
        border-color: #5cb85c;
        box-shadow: 0 0 8px rgba(92, 184, 92, 0.6);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .btn-success {
        transition: all 0.3s ease;
        width: 100%;
        max-width: 600px;
        margin: 20px auto; /* Center alignment */
        display: block;
    }

    .btn-success:hover {
        background-color: #4cae4c;
        transform: scale(1.05);
    }

    footer {
        text-align: center; /* Centers the footer text */
        padding: 10px 0; /* Adds some padding to the footer */
        background-color: #f8f9fa; /* Optional footer background color */
        border-top: 1px solid #ddd; /* Optional footer border */
    }
</style>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Change ADMIN Password</h1>
        </div>
    </div>

    <?php
    if ($_POST) {
        $oldword = $_POST["oldword"];
        $newword = $_POST["newword"];
        $newwword = $_POST["newwword"];
        $oldmd = MD5($oldword);

        $cpass = $pdo->query("SELECT password FROM users WHERE id='" . $uid . "'");
        $cpass = $cpass->fetch(PDO::FETCH_ASSOC);
        $err1 = $err2 = $err3 = $err4 = 0;

        if ($newword != $newwword) {
            $err2 = 1;
        }
        if (trim($newword) == "") {
            $err3 = 1;
        }
        if (strlen($newword) <= 3) {
            $err4 = 1;
        }

        if (!isset($error)) {
            $error = $err1 + $err2 + $err3 + $err4;
        }

        if ($oldmd != $cpass['password']) {
            $err1 = 1;
        }

        if (!isset($error) || $error == 0) {
            $passmd = MD5($newword);
            $res = $pdo->exec("UPDATE users SET password='" . $passmd . "' WHERE id='" . $uid . "'");
            if ($res) {
                echo "<div class='alert alert-success alert-dismissable'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                        Password Updated Successfully!
                      </div>";
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                        Some Problem Occurs, Please Try Again. 
                      </div>";
            }
        } else {
            if ($err1 == 1) {
                echo "<div class='alert alert-danger alert-dismissable'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                        Your Current Password Does Not Match.
                      </div>";
            }
            if ($err2 == 1) {
                echo "<div class='alert alert-danger alert-dismissable'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                        You Enter Different Password in two fields. Please enter the same password in both fields.
                      </div>";
            }
            if ($err3 == 1) {
                echo "<div class='alert alert-danger alert-dismissable'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                        Password Can Not Be Empty!!!
                      </div>";
            }
            if ($err4 == 1) {
                echo "<div class='alert alert-danger alert-dismissable'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                        Password Must be 4 or more characters.
                      </div>";
            }
        }
    }
    ?>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form action="changepass.php" method="post">
                <div class="form-group">
                    <input class="form-control" placeholder="Current Password" name="oldword" type="password" required>
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="New Password" name="newword" type="password" required>
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="New Password Again" name="newwword" type="password" required>
                </div>
                <input type="submit" class="btn btn-lg btn-success btn-block" value="Change">
            </form>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

<footer>
    <p>&copy; <?php echo date('Y'); ?> Your Company. All rights reserved.</p>
</footer>

<?php include('footer.php'); ?>
