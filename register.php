<?php
// Replace these database connection details with your own
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "User_authentication";

// Establish the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["psw"];
    $confirm_password = $_POST["psw-repeat"];

     // Password validation rules
     $password_min_length = 8;  // Minimum length
     $password_requires_special_char = true;
     $password_requires_number = true;
     $password_requires_uppercase = true;

    // Validate the email address
    // if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strpos($email, '@gmail.com') === false) {
    //     echo "Invalid email address. It should be a valid Gmail address.";
    // } elseif ($password != $confirm_password) {
    //     echo "Error: Passwords do not match.";
    // } else {
         // Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strpos($email, '@gmail.com') === false) {
        echo "Invalid email address. It should be a valid Gmail address.";
    } elseif ($password != $confirm_password) {
        echo "Error: Passwords do not match.";
    // } elseif (strlen($password) < $password_min_length) {
    //     echo "Error: Password should be at least $password_min_length characters long.";
    // } elseif ($password_requires_special_char && !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
    //     echo "Error: Password should contain at least one special character.";
    // } elseif ($password_requires_number && !preg_match('/[0-9]/', $password)) {
    //     echo "Error: Password should contain at least one number.";
    // } elseif ($password_requires_uppercase && !preg_match('/[A-Z]/', $password)) {
    //     echo "Error: Password should contain at least one uppercase letter.";
    } else {

        // Check if the user already exists in the database
        $query = "SELECT * FROM registers WHERE email=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "User with this email already exists. Please log in instead.";
            // <p>You can now <a href='login.html'>log in</a>log in </p>
        } else {
            // Hash the passwords before storing them in the database
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Prepare the SQL statement to insert data into the database
            $sql = "INSERT INTO registers (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                // Registration successful, redirect to the login page
                header("Location: login.html");
                echo "Registration successful. You can now log in.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // Check if $stmt is defined before trying to close it
    if (isset($stmt)) {
        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>
