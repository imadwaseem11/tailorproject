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
            <h1 class="page-header">Pay Salary</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <?php
            if ($_POST) {
                $expcat = 2; // Category for salary payments
                $desc = $_POST["desc"];
                $date = $_POST["date"];
                $amount = $_POST["amount"];

                $error = 0;

                if ($expcat == 0) {
                    $err1 = 1;
                }

                if (isset($err1)) {
                    $error = $err1;
                }

                if (!isset($error) || $error == 0) {
                    $res = $pdo->exec("INSERT INTO expense SET expcat='" . $expcat . "', description='" . $desc . "', date='" . $date . "', amount='" . $amount . "'");
                    if ($res) {
                        echo "<div class='alert alert-success alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	
                                Salary Paid Successfully!
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

            <form action="paysalary.php" method="post">
                <div class="form-group">
                    <label>Select Staff</label>
                    <select id="staff" name="staff" class="form-control" required>
                        <option value="0">Select a Staff</option>
                        <?php
                        $ddaa = $pdo->query("SELECT id, fullname, salary FROM staff ORDER BY id");
                        while ($data = $ddaa->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='$data[salary]'>$data[fullname]</option>";
                        }
                        ?>
                    </select>
                    <input type="hidden" value="2" name="expcat" class="form-control" />
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <input id="desc" type="text" name="desc" class="form-control" placeholder="Enter description" required>
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <?php echo($currency); ?> 
                    <input id="amount" type="text" name="amount" class="form-control" placeholder="Enter amount" required>
                </div>

                <input type="submit" class="btn btn-lg btn-success btn-block" value="ADD">
            </form>
        </div>

        <script>
            document.getElementById("staff").onchange = function () {
                document.getElementById("amount").value = this.value;
                document.getElementById("desc").value = this.options[this.selectedIndex].text + ' Salary';
            };
        </script>
    </div>
</div>

<script src="js/bootstrap-timepicker.min.js"></script>

<script>
jQuery(document).ready(function() {
    jQuery("#ssn").mask("999-99-9999");
    // Time Picker
    jQuery('#timepicker').timepicker({ defaultTIme: false });
    jQuery('#timepicker2').timepicker({ showMeridian: false });
    jQuery('#timepicker3').timepicker({ minuteStep: 15 });
});
</script>

<?php include('footer.php'); ?>
