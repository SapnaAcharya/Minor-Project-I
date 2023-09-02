<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="productdisplay.css">
</head>
<body>  


<?php
if (isset($_GET['query'])) {
    $searchTerm = trim($_GET['query']);

    // Connect to your MySQL database (replace with your database credentials)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user_authentication";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $searchTerm = $conn->real_escape_string($searchTerm);

    // Perform a database query to retrieve product information
    $sql = "SELECT * FROM products WHERE productname LIKE '%$searchTerm'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        //create a div element with an id to apply css styling
        // echo '<div id="search-results">';

        // Build HTML for displaying search results
        $html = '<h2>Search Results for "' . $searchTerm . '":</h2>';

        //create a div element with an id to apply css styling
        echo '<div id="search-results">';

        while ($row = $result->fetch_assoc()) {
            $html .= '<div>';
            $html .= '<h3>Product Name: ' . $row['productname'] . '</h3>';

            //include the image tag within the html
            if (file_exists($row['image'])) {
                $html .= '<img src="' . $row['image'] . '" alt="Product Image" style="max-width: 700px;">';
            } else {
                $html .= 'Image not found';
            }  
            $html .= '<p>Category: ' . $row['category'] . '</p>';
            $html .= '<p>Subcategory: ' . $row['subcategory'] . '</p>';
            $html .= '<p>Description: ' . $row['description'] . '</p>';
            $html .= '<p>Price: $' . number_format($row['price'], 2) . '</p>';
            $html .= '<p>Brand: ' . $row['brand'] . '</p>';
            $html .= '<p>Seller Name: ' . $row['sellername'] . '</p>';
            $html .= '<p>Contact: ' . $row['contact'] . '</p>';
            $html .= '</div>';
        }
        echo $html;
    } else {
        // Check for an exact product name match and return product details if found
        $exactProductMatches = "SELECT * FROM products WHERE LOWER(TRIM(productname)) = '$searchTerm'";
        $exactProductResult = $conn->query($exactProductMatches);
        if ($exactProductResult->num_rows > 0) {
            $html = '<h2>Product Information:</h2>';
            while ($row = $exactProductResult->fetch_assoc()) {
                $html .= '<div>';
                $html .= '<h3>Product Name: ' . $row['productname'] . '</h3>';

                // Include the image tag within the HTML
                if (file_exists($row['image'])) {
                    $html .= '<img src="' . $row['image'] . '" alt="Product Image" style="max-width: 700px;">';
                } else {
                    $html .= 'Image not found';
                }
                $html .= '<p>Category: ' . $row['category'] . '</p>';
                $html .= '<p>Subcategory: ' . $row['subcategory'] . '</p>';
                $html .= '<p>Description: ' . $row['description'] . '</p>';
                $html .= '<p>Price: $' . number_format($row['price'], 2) . '</p>';
                $html .= '<p>Brand: ' . $row['brand'] . '</p>';
                $html .= '<p>Seller Name: ' . $row['sellername'] . '</p>';
                $html .= '<p>Contact: ' . $row['contact'] . '</p>';
                $html .= '</div>';
            }
            echo $html;
        } else {
            echo 'No products found.';
        }
    }

    $conn->close();
}
?>
