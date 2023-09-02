<?php
// Include your database connection code here
$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "user_authentication";

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// require 'path-to-phpmailer/src/Exception.php';
// require 'path-to-phpmailer/src/PHPMailer.php';
// require 'path-to-phpmailer/src/SMTP.php';

require 'C:\xampp\htdocs\fewaexpress-website\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\fewaexpress-website\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\fewaexpress-website\phpmailer\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["email"])) {
        // Get the user's email from the form
        $email = $_POST["email"];

        // Generate a unique OTP
        $otp = mt_rand(100000, 999999); // You can customize the OTP length

        // Store the OTP and its expiration time in the database
        $otp_expiration = date("Y-m-d H:i:s", strtotime("+5 minutes")); // OTP expires in 5 minutes

        $stmt = $conn->prepare("UPDATE registers SET otp = :otp, otp_expiration = :otp_expiration WHERE email = :email");
        $stmt->bindParam(':otp', $otp);
        $stmt->bindParam(':otp_expiration', $otp_expiration);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Create a PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS
            $mail->Port = 587;
            $mail->Username = 'acharyasapna2059@gmail.com'; // Replace with your Gmail address
            $mail->Password = 'upqm gmch esur kdxa'; // Replace with your Gmail password

            // Recipients
            $mail->setFrom('acharyasapana2059@gmail.com', 'sapana acharya');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'One-Time Password (OTP)';
            $mail->Body = "Your OTP is: $otp"; // You can customize the email message

            $mail->send();
            echo "OTP sent to your email. Check your inbox.";

            header("Location: verify_seller_otp.php");
            exit; // Make sure to exit to prevent further script execution

        } catch (Exception $e) {
            echo "Email sending failed. Please try again later.";
        }
    }
}
?>
