<?php
require_once('function.php');
dbconnect();
session_start();

if (!is_user()) {
    redirect('index.php');
}

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
            <h1 class="page-header">Add Staff</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <?php
            if ($_POST) {
                $stafftype = $_POST["stafftype"];
                $fullname = $_POST["fullname"];
                $address = $_POST["address"];
                $phonenumber = $_POST["phonenumber"];
                $salary = $_POST["salary"];

                $error = 0;

                if ($stafftype == 0) {
                    $err1 = 1;
                }

                if (isset($err1)) {
                    $error = $err1;
                }

                if (!isset($error) || $error == 0) {
                    $res = $pdo->exec("INSERT INTO staff SET stafftype='" . $stafftype . "', fullname='" . $fullname . "', address='" . $address . "', phonenumber='" . $phonenumber . "', salary='" . $salary . "'");
                    if ($res) {
                        echo "<div class='alert alert-success alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                                Staff Added Successfully!
                              </div>";
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                                Some Problem Occurs, Please Try Again. 
                              </div>";
                    }
                } else {
                    if (!isset($err1) || $err1 == 1) {
                        echo "<div class='alert alert-danger alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                                Please select a Category!!!!
                              </div>";
                    }
                }
            }
            ?>

            <form action="staffadd.php" method="post">
                <div class="form-group">
                    <label>Select Staff Designation</label>
                    <select name="stafftype" class="form-control" required>
                        <option value="0">Please Select a Staff Designation</option>
                        <?php
                        $ddaa = $pdo->query("SELECT id, title FROM stafftype ORDER BY id");
                        while ($data = $ddaa->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='$data[id]'>$data[title]</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" class="form-control" placeholder="Enter full name" required>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" placeholder="Enter address" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phonenumber" class="form-control" placeholder="Enter phone number" required>
                </div>

                <div class="form-group">
                    <label>Salary</label>
                    <?php echo($currency); ?><input type="text" name="salary" class="form-control" placeholder="Enter salary" required>
                </div>

                <input type="submit" class="btn btn-lg btn-success btn-block" value="ADD">
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
