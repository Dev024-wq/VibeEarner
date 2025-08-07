<?php
// Use PHPMailer namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

// Enable detailed error reporting (for debugging, remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed.']);
    exit;
}

// Clean and validate inputs
function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

$firstName = clean_input($_POST['firstName'] ?? '');
$lastName  = clean_input($_POST['lastName'] ?? '');
$location  = clean_input($_POST['location'] ?? '');
$email     = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$message   = clean_input($_POST['description'] ?? '');
$honeypot  = $_POST['website'] ?? ''; // hidden honeypot field

$errors = [];
if (!$firstName) $errors[] = "First Name is required.";
if (!$lastName) $errors[] = "Last Name is required.";
if (!$location) $errors[] = "Location is required.";
if (!$email) $errors[] = "Valid Email is required.";
if (!empty($honeypot)) { // if honeypot filled, likely spam
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Spam detected.']);
    exit;
}
if ($errors) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

$mail = new PHPMailer(true);

try {
    // SMTP server configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'randomzeno204@gmail.com';   // << Replace with your Gmail
    $mail->Password   = 'nxaj mvhx oyam dnvn';      // << Use Gmail App Password here
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender and recipient
    $mail->setFrom('randomzeno204@gmail.com', 'VibeEarning Contact');
    $mail->addAddress('randomzeno204@gmail.com');    // << Your email where you receive messages

    // Reply-to is the submitter
    $mail->addReplyTo($email, $firstName . ' ' . $lastName);

    // Email content
    $mail->isHTML(false);
    $mail->Subject = 'New Join Submission from VibeEarning';
    $mail->Body    = "New Join Application Details:\n"
        . "First Name: $firstName\n"
        . "Last Name: $lastName\n"
        . "Location: $location\n"
        . "Email: $email\n"
        . "Message:\n$message\n"
        . "\nSent at " . date("Y-m-d H:i:s") . " from IP " . $_SERVER['REMOTE_ADDR'];

    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Thank you for your application! We will contact you soon.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
}
?>

