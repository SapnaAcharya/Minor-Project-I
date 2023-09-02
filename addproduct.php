<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="addproductstyle.css">
</head>
<body>  

<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $sellername = $_POST['sellername'];
    $contact = $_POST['contact'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $productname = $_POST['productname'];
    $price = $_POST['price'];
    $brand = $_POST['brand'];
    $description = $_POST['description'];
    //$seller_id = $_POST['seller_id'];

    //debug: display form data
    //var_dump($_POST);

    // Image upload and display
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the image file is a valid image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    // if (file_exists($targetFile)) {
    //     echo "Sorry, file already exists.";
    //     $uploadOk = 0;
    // }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only specific image file formats
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // If the image upload is successful, save the uploaded image and insert the form data into the database
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "user_authentication";
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Insert the form data into the database
           $imagePath = 'uploads/' . basename($_FILES["image"]["name"]);
           $sql = "INSERT INTO products (sellername, contact, category, subcategory, productname, price, brand, description, image)
            VALUES ('$sellername', '$contact', '$category', '$subcategory', '$productname', '$price', '$brand', '$description', '$targetFile')";

            if ($conn->query($sql) === TRUE) {
                echo "<h2>Product Details:</h2>";
             echo "<img src='" . $targetFile . "' alt='Product Image' style='max-width: 200px;'>";
                // echo "<img src='uploads/" . basename($_FILES["image"]["name"]) . "' alt='Product Image' style='max-width: 200px;'>";
                
                echo "<p><strong>Seller Name:</strong> " . $sellername . "</p>";
                echo "<p><strong>Contact:</strong> " . $contact . "</p>";
                echo "<p><strong>Category:</strong> " . $category . "</p>";
                echo "<p><strong>Subcategory:</strong> " . $subcategory . "</p>";
                echo "<p><strong>Product Name:</strong> " . $productname . "</p>";
                echo "<p><strong>Price:</strong> $" . $price . "</p>";
                echo "<p><strong>Brand:</strong> " . $brand . "</p>";
                echo "<p><strong>Description:</strong> " . $description . "</p>";
                //echo "<p><strong>SellerID:</strong> " . $seller_id . "</p>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            // Close the database connection
            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Retrieve all products from the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_authentication";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>All Products:</h2>";
    //echo "<div class='product-grid'>";
    // Display all the product details
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product-item'>";
        echo "<img src='" . $row['image'] . "' alt='Product Image' style='max-width: 400px;'>";
        echo "<p><strong>Seller Name:</strong> " . $row['sellername'] . "</p>";
        echo "<p><strong>Contact:</strong> " . $row['contact'] . "</p>";  
        echo "<p><strong>Product Name:</strong> " . $row['productname'] . "</p>";
        echo "<p><strong>Category:</strong> " . $row['category'] . "</p>";
        echo "<p><strong>Subcategory:</strong> " . $row['subcategory'] . "</p>";
        echo "<p><strong>Description:</strong> " . $row['description'] . "</p>";
        echo "<p><strong>Price:</strong> Rs." . $row['price'] . "</p>";
        echo "<p><strong>Brand:</strong> " . $row['brand'] . "</p>";

       //echo "<a href='loginregister.php'></a>";

      //  echo "<a href=edit_product.php?=" . $row['product_id'] . "'>Edit</a>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>No products found.</p>";
}

// Close the database connection
$conn->close();
?>
</body>
</html>