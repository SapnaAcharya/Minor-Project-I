<?php
// Include your database connection code here
$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "user_authentication";

$message = '';

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["email"]) && isset($_POST["otp"]) && isset($_POST["newpassword"])) {
        $email = $_POST["email"];
        $otp = $_POST["otp"];
        $newpassword = password_hash($_POST["newpassword"], PASSWORD_DEFAULT);

        // Check if the email and OTP exist in the database
        $stmt = $conn->prepare("SELECT * FROM registers WHERE email = :email AND otp = :otp");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':otp', $otp);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $current_time = date("Y-m-d H:i:s");
            if ($user["otp_expiration"] >= $current_time) {
                // Update the user's password and remove the OTP
                $stmt = $conn->prepare("UPDATE registers SET password = :password, otp = NULL, otp_expiration = NULL WHERE email = :email");
                $stmt->bindParam(':password', $newpassword);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                $message = "Password reset successfully.";
            } else {
                $message = "OTP has expired. Please request a new OTP.";
            }
        } else {
            $message = "Invalid OTP or email. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="reseting_password.css">
</head>
<body>
    <h1>Reset Password</h1>
    <form method="post" action="login.html">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <!-- <label for="otp">OTP:</label>
        <input type="text" name="otp" required><br> -->

        <label for="password">New Password:</label>
        <div class="password-input-container">
            <input type="password" id="newpassword" name="newpassword" required>
            <span class="password-toggle" onclick="togglePasswordVisibility('newpassword', 'newvisibility-icon')">
                <i id="newvisibility-icon" class="fas fa-eye"></i>
            </span>
        </div>

        <label for="password">Confirm Password:</label>
        <div class="password-input-container">
            <input type="password" id="confirmpassword" name="confirmpassword" required>
            <span class="password-toggle" onclick="togglePasswordVisibility('confirmpassword', 'confirmvisibility-icon')">
                <i id="confirmvisibility-icon" class="fas fa-eye"></i>
            </span>
        </div>

        <input type="submit" value="Reset Password" onclick="return validatePassword();">
        
        <!-- Display the success message here -->
        <div id="success-message" style="display: none">
            <p><?php echo $message; ?></p>
            <!-- <p>You can now <a href='login.html'>log in</a> with your new password.</p> --> 
            <p>You can now register in with your new password.</p>
        </div>
    </form>

    <script>
        function togglePasswordVisibility(fieldId, iconId) {
            var passwordField = document.getElementById(fieldId);
            var visibilityIcon = document.getElementById(iconId);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                visibilityIcon.classList.remove("fa-eye");
                visibilityIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                visibilityIcon.classList.remove("fa-eye-slash");
                visibilityIcon.classList.add("fa-eye");
            }
        }

        function validatePassword() {
            var newPassword = document.getElementById("newpassword").value;
            var confirmPassword = document.getElementById("confirmpassword").value;

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match. Please make sure both passwords are the same.");
                return false; // Prevent form submission
            }

            // Show the success message if passwords match
            document.getElementById("success-message").style.display = "block";
            
            return true; // Allow form submission if passwords match
        }
    </script>
</body>
</html>
