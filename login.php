<?php
// Change these variables according to your database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_authentication"; // Replace with the appropriate database name

// Establish a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $uname = $_POST["uname"];
    $psw = $_POST["psw"];

    // Prepare and execute a SQL query to retrieve the hashed password for the given username
    $stmt = $conn->prepare("SELECT password FROM registers WHERE username = ?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Username found, verify the password
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];
       // $role = $row["role"];
        
        if (password_verify($psw, $hashedPassword)) {
            // Password matches, user authenticated
            echo "Login successful!";
            // redirect to the sellerverifypage

            //check if the login is accessed from a specific source 
            //if(isset($_GET['source']) && $_GET['source'] === 'direct') {
                //if($role === "seller") {
            header("Location: seller_verifying.html");
            exit();
            
        } else {
            // Password doesn't match, show an error message
            echo "Invalid password!";
        }
    } else {
        // Username not found, show an error message and redirect to the registration form
        echo "User not found!";
        header("Refresh: 3; URL=register.html"); // Replace with the URL of your registration form
        exit();
    }

    // Close the statement and result
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

