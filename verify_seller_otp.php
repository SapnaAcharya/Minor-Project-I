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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["email"]) && isset($_POST["otp"])) {
        // Get the user's email and OTP from the form
        $email = $_POST["email"];
        $otp = $_POST["otp"];

        // Check if the email and OTP exist in the database
        $stmt = $conn->prepare("SELECT * FROM registers WHERE email = :email AND otp = :otp");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':otp', $otp);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Check if the OTP is still valid (not expired)
            $current_time = date("Y-m-d H:i:s");
            if ($user["otp_expiration"] >= $current_time) {
                // Redirect the user to the password reset page
                header("Location: sell.html?email=$email");
            } else {
                echo "OTP has expired. Please request a new OTP.";
            }
        } else {
            echo "Invalid OTP or email. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" type="text/css" href="verify_otp.css">
</head>
<body>
  <h1 class="heading">Verify OTP</h1>
    <form method="post" action="verify_seller_otp.php">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="otp">OTP:</label>
        <input type="text" name="otp" required><br>

        <input type="submit" value="Verify OTP">
    </form>
</body>
</html>
