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
    .footer-order{
        /* border:1px solid; */
        margin-top:9rem;
    }
</style>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Add Order</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <?php
            if ($_POST) {
                $customer = $_POST["customer"];
                $desc = $_POST["desc"];
                $date_received = $_POST["date_received"];
                $completed = $_POST["completed"];
                $date_collected = $_POST["date_collected"];
                $amount = $_POST["amount"];
                $paid = $_POST["paid"];
                $received_by = $_POST["received_by"];

                $name = $pdo->query("SELECT fullname FROM customer WHERE id='" . $customer . "'");
                $name = $name->fetch(PDO::FETCH_ASSOC);
                $name = $name['fullname'] . ": " . substr($desc, 0, 100);

                $color = ($completed == 'No') ? '#a00000' : '#00a014';

                $res = $pdo->exec("INSERT INTO `order`(`customer`, `description`, `amount`, `paid`, `received_by`, `date_received`, `completed`, `date_collected`) VALUES ('$customer','$desc','$amount','$paid','$received_by','$date_received','$completed','$date_collected')");
                $cid = $pdo->lastInsertId();
                $res2 = $pdo->exec("INSERT INTO `calendar`(`title`, `description`, `start`, `end`, `allDay`, `color`, `url`, `category`, `user_id`) VALUES ('$name','$desc','$date_received','$date_collected','true','$color','../orderedit.php?id=$cid','Orders','$uid')");

                if ($res) {
                    echo "<div class='alert alert-success alert-dismissable'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                            Order Added Successfully!
                          </div>";
                } else {
                    echo "<div class='alert alert-danger alert-dismissable'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            Error adding order. Please try again.
                          </div>";
                }
            }
            ?>

            <script>
                $(function() {
                    $("#datepicker").datepicker();
                });
            </script>

            <form action="orderadd.php" method="post">
                <div class="form-group">
                    <label>Select Customer</label>
                    <select name="customer" class="form-control" required>
                        <option value="0">Please Select a Customer</option>
                        <?php
                        $ddaa = $pdo->query("SELECT id, fullname FROM customer ORDER BY id");
                        while ($data = $ddaa->fetch(PDO::FETCH_ASSOC)) {
                            $selected = (isset($_GET['id']) && $data['id'] == $_GET['id']) ? "selected='selected'" : "";
                            echo "<option value='$data[id]' $selected>$data[fullname]</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="desc" class="form-control" placeholder="Enter description" required>
                </div>

                <div class="form-group">
                    <label>Date Received</label>
                    <input type="date" name="date_received" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Received By</label>
                    <select name="received_by" class="form-control" required>
                        <?php
                        $ddaa = $pdo->query("SELECT id, fullname FROM staff ORDER BY id");
                        while ($data = $ddaa->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='$data[id]'>$data[fullname]</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <?php echo($currency); ?> <input type="text" name="amount" class="form-control" placeholder="Enter amount" required>
                </div>

                <div class="form-group">
                    <label>Paid</label>
                    <?php echo($currency); ?> <input type="text" name="paid" class="form-control" placeholder="Enter amount paid" required>
                </div>

                <div class="form-group">
                    <label>Completed?</label>
                    <select name="completed" class="form-control" required>
                        <option value='No'>No</option>
                        <option value='Yes'>Yes</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date to Collect</label>
                    <input type="date" name="date_collected" class="form-control" required>
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

<div class="footer-order">
<?php include('footer.php'); ?>
</div>
