<?php
// Establish a database connection
$servername = 'localhost';
$dbname = 'user_authentication';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $sellername = $_POST['sellername'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $identitynumber = $_POST['identitynumber'];
    $province = $_POST['province'];
    $location = $_POST['location'];

    // Validation for email: It should contain '@gmail.com'
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strpos($email, '@gmail.com') === false) {
        echo "Invalid email address. It should be a valid Gmail address.";
        exit;
    }

    // Phone Number Validation: 10-digit number starting with "98" or "977"
    if (!preg_match('/^(98|977)\d{8}$/', $phonenumber)) {
        echo "Invalid phone number. It should be a 10-digit number starting with '98' or '977'.";
       exit;
    }


    // Identity number validation: Check for valid citizenship or driver's license number
    // if (!isValidCitizenshipNumber($identitynumber) && !isValidDriverLicenseNumber($identitynumber)) {
    //     echo "Invalid identity number. It should be either a citizenship number or a driver's license number.";
    //     exit;
    // }


    // Additional validation for seller name, province, and location if needed

    // Prepare and execute the SQL statement to insert the data into the database
    $stmt = $conn->prepare("INSERT INTO sellers (seller_name, email, phone_number, identity_number, province, location) VALUES (:sellername, :email, :phonenumber, :identitynumber, :province, :location)");
    $stmt->bindParam(':sellername', $sellername);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phonenumber', $phonenumber);
    $stmt->bindParam(':identitynumber', $identitynumber);
    $stmt->bindParam(':province', $province);
    $stmt->bindParam(':location', $location);

    try {
        $stmt->execute();
        $form_submission_is_successful = true; // Set it to true if the execution is successful
        echo "Seller account verification data has been successfully stored.";


        // After processing the form and verifying the data, and if it's successful:
if ($form_submission_is_successful) {
    // Redirect to the otp-sending.html page
    header("Location: seller_otp_sending.html");
    exit; // Important: Terminate the script after redirection
}

        // Redirect to the appropriate page upon successful registration
        // header("Location: sell.html");
        // exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// // Function to validate citizenship number
// function isValidCitizenshipNumber($identitynumber) {
//     $pattern = '/^\d{4}-\d{6}-\d{2}-\d$/';
//     return preg_match($pattern, $identitynumber) === 1;
// }

// // Function to validate driver's license number
// function isValidDriverLicenseNumber($identitynumber) {
//     $pattern = '/^[A-Z]{2}-\d{7}$/';
//     return preg_match($pattern, $identitynumber) === 1;
// }
// ?> 
