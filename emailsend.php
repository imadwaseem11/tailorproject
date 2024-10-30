<?php
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
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Custom CSS -->
    <style>
        body {
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        /* Background image with blur effect */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://t3.ftcdn.net/jpg/00/98/64/08/240_F_98640817_fiDED8sG5aQ9qa8K700mZGlHi4FiCKBi.jpg') no-repeat center center fixed;
            background-size: cover;
            filter: blur(6px);
            z-index: -1;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            background: #fff;
            color: #333;
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.7s ease-in-out;
            position: relative;
            z-index: 1;
        }
        .btn-primary {
            background: #6a11cb;
            border: none;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #2575fc;
        }
        .form-control {
            border-radius: 5px;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
        }
        .footer {
            background-color: grey;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card animate__animated animate__fadeIn">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="m-0">Send Email</h2>
        </div>
        <div class="card-body">
            <form action="emailsend.php" method="post">
                <div class="form-group">
                    <label for="customer">Name of Recipient</label>
                    <input name="customer" id="customer" class="form-control" placeholder="Enter recipient's name" required>
                </div>
                <div class="form-group">
                    <label for="toemail">Recipient's Email</label>
                    <input name="toemail" type="email" class="form-control" placeholder="Enter recipient's email" required>
                </div>
                <div class="form-group">
                    <label for="template">Select a Template</label>
                    <select name="template" id="template" class="form-control">
                        <option value="0">Please Select a Template</option>
                        <?php
                        // Fetch templates from the database
                        $ddaa = $pdo->query("SELECT id, title, msg FROM template ORDER BY id");
                        while ($data = $ddaa->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$data['msg']}'>{$data['title']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea rows="4" name="message" id="message" class="form-control" placeholder="Type your message here" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send Email</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS, jQuery, and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Template selection change event
    document.getElementById("template").onchange = function () {
        document.getElementById("message").value = 'Dear ' + document.getElementById("customer").value + ',\n' + this.value;
    };

    // Animate submit button on hover
    $(".btn-primary").hover(
        function() {
            $(this).addClass("animate__animated animate__pulse");
        },
        function() {
            $(this).removeClass("animate__animated animate__pulse");
        }
    );
</script>

<div class="footer">
<?php
 include ('footer.php');
 ?>
</div>

</body>
</html>
