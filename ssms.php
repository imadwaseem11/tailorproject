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
$usid = $pdo->query("SELECT id FROM register WHERE username='".$user."'");
$usid = $usid->fetch(PDO::FETCH_ASSOC);
$uid = $usid['id'];
include ('header.php');
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
        text-align: left; /* Align content on larger fields */
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
        margin: 0 auto;
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
            <h1 class="page-header">Send SMS</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <?php
            if ($_POST) {
                $customer = $_POST["customer"];
                $message = $_POST["message"];
                $smss = $pdo->query("SELECT sms FROM general_setting");
                $smss = $smss->fetch(PDO::FETCH_ASSOC);
                $sms = $smss['sms'];
                $phone = $_POST["phone"];

                $message = urlencode($message);
                $url1 = str_replace("[TO]", $phone, $sms);
                $url = str_replace("[MESSAGE]", $message, $url1);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $url);
                $content = curl_exec($ch);
                $response = curl_getinfo($ch);
                curl_close($ch);
                $date = date('Y-m-d');
                $message = $_POST["message"];
                $res = $pdo->exec("INSERT INTO `sms`(`customer`, `message`, `date`) VALUES ('$customer','$message','$date')");

                if ($res) {
                    echo "<div class='alert alert-success alert-dismissable'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>    
                            Message Sent Successfully!
                          </div>";
                }
            }
            ?>

            <script>
                $(function() {
                    $("#datepicker").datepicker();
                });
            </script>

            <form action="ssms.php" method="post">
                <div class="form-group">
                    <label>Name of Recipient</label>
                    <input name="customer" id="customer" class="form-control" placeholder="Enter recipient's name"><br/>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input name="phone" value="+2347XXXXXXXXX" class="form-control" placeholder="Enter phone number"><br/>
                </div>

                <div class="form-group">
                    <label>Select a Template</label>
                    <select name="template" id="template" class="form-control">
                        <option value="0">Please Select a Template</option>
                        <?php
                        $ddaa = $pdo->query("SELECT id, title, msg FROM template ORDER BY id");
                        while ($data = $ddaa->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='$data[msg]'>$data[title]</option>";
                        }
                        ?>
                    </select><br/>
                </div>

                <div class="form-group">
                    <label>Message</label><br/>
                    <textarea rows="4" cols="50" name="message" id="message" class="form-control" placeholder="Enter your message"></textarea><br/><br/>
                </div>

                <input type="submit" class="btn btn-lg btn-success" value="SEND">
            </form>
        </div>

        <script>
            // Display selected template message in textarea
            document.getElementById("template").onchange = function () {
                document.getElementById("message").value = 'Dear ' + document.getElementById("customer").value + ',' + '\n' + this.value;
            };
        </script>
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

<?php include ('footer.php'); ?>
