<!-- <?php
require_once('function.php'); // Your DB connection functions
require 'vendor/autoload.php'; // Load Composer dependencies

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

dbconnect();
session_start();

// Check if user is logged in
if (!is_user()) {
    redirect('index.php');
}

if ($_POST) {
    // Sanitize and validate input
    $customer = htmlspecialchars(trim($_POST["customer"]));
    $message = htmlspecialchars(trim($_POST["message"]));
    $toemail = filter_var($_POST["toemail"], FILTER_VALIDATE_EMAIL);

    if (!$toemail) {
        echo "<div class='alert alert-danger'>Invalid email address.</div>";
    } else {
        // Initialize PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_FROM'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'];

            // Set sender and recipient
            $mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_FROM_NAME']);
            $mail->addAddress($toemail, $customer);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Subject Here";
            $mail->Body    = nl2br($message); // Converts line breaks for HTML

            // Send email
            $mail->send();
            echo "<div class='alert alert-success'>Message Sent Successfully!</div>";

            // Log email in the database
            $stmt = $pdo->prepare("INSERT INTO email (customer, message, date) VALUES (:customer, :message, :date)");
            $stmt->execute([
                ':customer' => $customer,
                ':message' => $message,
                ':date' => date('Y-m-d')
            ]);
        } catch (Exception $e) {
            // Display error if email could not be sent
            echo "<div class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Email</title>
</head>
<body>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Send EMAIL</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form action="emailsend.php" method="post">
                <div class="form-group">
                    <label>Name of Recipient</label>
                    <input name="customer" id="customer" class="form-control"><br/>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input name="toemail" type="email" class="form-control"><br/>
                </div>

                <div class="form-group">
                    <label>Select a Template</label>
                    <select name="template" id="template" class="form-control">
                        <option value="0">Please Select a Template</option>
                        <?php
                        // Fetch templates from the database
                        $ddaa = $pdo->query("SELECT id, title, msg FROM template ORDER BY id");
                        while ($data = $ddaa->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$data['msg']}'>{$data['title']}</option>";
                        }
                        ?>
                    </select><br/>
                </div>

                <div class="form-group">
                    <label>Message</label><br/>
                    <textarea rows="4" cols="50" name="message" id="message" class="form-control"></textarea><br/><br/>
                </div>  
                <input type="submit" class="btn btn-lg btn-success btn-block" value="SEND">
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("template").onchange = function () {
        document.getElementById("message").value = 'Dear ' + document.getElementById("customer").value + ',\n' + this.value;
    };
</script>

</body>
</html> -->
