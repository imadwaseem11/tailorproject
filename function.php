<?php
// Database connection settings
$baseurl = "http://localhost/tailor/";	
$dbname = "tailor";
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";

// Enable error reporting
error_reporting(E_ALL);

// Create a PDO instance for database connection
function dbconnect() {
    global $pdo, $dbname, $dbuser, $dbhost, $dbpass;

    try {
        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

// Call dbconnect at the beginning of the script
dbconnect(); // Ensure the connection is established

// Function to register a new user
// Function to register a new user
function register_user($username, $email, $phonenumber, $gender, $password) {
    global $pdo;

    // Validate input
    if (empty($username) || empty($email) || empty($phonenumber) || empty($gender) || empty($password)) {
        return "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    if (!preg_match('/^\+?\d+$/', $phonenumber)) {
        return "Invalid phone number format.";
    }

    // Check if the email already exists
    $stmt = $pdo->prepare("SELECT id FROM register WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->rowCount() > 0) {
        return "Email already registered!";   
    }

    // Hash the password and insert the new user into the database
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO register (username, email, phonenumber, gender, password) VALUES (:username, :email, :phonenumber, :gender, :password)");

    if ($stmt->execute([
        'username' => $username,
        'email' => $email,
        'phonenumber' => $phonenumber,
        'gender' => $gender,
        'password' => $hashedPassword
    ])) {
        return true; // Registration successful
    } else {
        return "Failed to register. Please try again!";
    }
}


// Function to check if a user is logged in
function is_user() {
    return isset($_SESSION['user_id']);
}

// Function to redirect to a given URL
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Function to validate username format
function valid_username($str) {
    return preg_match('/^[a-z0-9_-]{3,16}$/', $str);
}

// Function to validate password format
function valid_password($str) {
    return preg_match('/^[a-z0-9_-]{6,18}$/', $str);
}

// Function to authenticate a user
// Function to authenticate a user
function attempt($username, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id, username, password FROM register WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username']; // Set the username in the session
        return true;
    } else {
        return false;
    }
}



