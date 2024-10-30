<?php
require_once('function.php');
dbconnect();
session_start();

if (!is_user()) {
    redirect('index.php');
}
?>

<?php
$user = $_SESSION['username'];
$usid = $pdo->query("SELECT id FROM register WHERE username='" . $user . "'");
$usid = $usid->fetch(PDO::FETCH_ASSOC);
$uid = $usid['id'];
include('header.php');
?>

<!-- Custom CSS for styling -->
<style>
    .form-control {
        width: 100%; /* Full width */
        max-width: 600px; /* Max width for larger screens */
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 16px;
        transition: all 0.3s ease;
        margin: 0 auto; /* Center alignment */
        display: block;
    }

    .form-control:focus {
        border-color: #5cb85c;
        box-shadow: 0 0 8px rgba(92, 184, 92, 0.6);
    }

    .form-group label {
        font-weight: bold;
        color: #333;
        text-align: left;
        display: block;
        margin-bottom: 5px;
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
</style>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Add Customer</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <?php
            if ($_POST) {
                $fullname = $_POST["fullname"];
                $address = $_POST["address"];
                $phonenumber = $_POST["phonenumber"];
                $sex = $_POST["sex"];
                $email = $_POST["email"];
                $city = $_POST["city"];
                $comment = $_POST["comment"];

                $res = $pdo->exec("INSERT INTO customer SET fullname='" . $fullname . "', address='" . $address . "', phonenumber='" . $phonenumber . "', sex='" . $sex . "', email='" . $email . "', city='" . $city . "', comment='" . $comment . "'");
                $cid = $pdo->lastInsertId();
                if ($res) {
                    echo "<div class='alert alert-success alert-dismissable'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>    
                            Customer Added Successfully!
                          </div>
                          <meta http-equiv='refresh' content='2; url=addmeasurement.php?id=$cid' />";
                }
            }
            ?>

            <script>
                $(function() {
                    $("#datepicker").datepicker();
                });
            </script>

            <form action="customeradd.php" method="post">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" class="form-control" placeholder="Enter full name">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" placeholder="Enter address">
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phonenumber" class="form-control" placeholder="Enter phone number">
                </div>

                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" placeholder="Enter city">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email">
                </div>

                <div class="form-group">
                    <label>Comment</label>
                    <textarea rows="4" name="comment" class="form-control" placeholder="Enter comment"></textarea>
                </div>

                <div class="form-group">
                    <label>Sex</label>
                    <select name="sex" class="form-control">
                        <option value="0">Male</option>
                        <option value="1">Female</option>
                    </select>
                </div>

                <input type="submit" class="btn btn-lg btn-success" value="ADD">
            </form>
        </div>
    </div>
</div>

<script src="js/bootstrap-timepicker.min.js"></script>

<script>
jQuery(document).ready(function(){
    jQuery("#ssn").mask("999-99-9999");

    // Time Picker
    jQuery('#timepicker').timepicker({defaultTIme: false});
    jQuery('#timepicker2').timepicker({showMeridian: false});
    jQuery('#timepicker3').timepicker({minuteStep: 15});
});
</script>

<?php include('footer.php'); ?>
