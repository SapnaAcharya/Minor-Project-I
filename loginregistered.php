
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="modify.css">
  <!-- <link rel="stylesheet" type="text/css" href="product.css">  -->

</head>
<body>
  <section>
    <nav>
      <div class="nav-container">
        <ul>
          <li><a href="#">.FewaExpress</a></li>
        </ul>
        <ul>

          <li><a href="seller.html">Become a seller</a></li>
          <!-- <li><a href="#">Home</a></li>
          <li><a href="#">About</a></li>  -->
          <li><a href="register.html">Register</a></li>
          <li><a href="login.html">Login</a></li>
          <li><i class="fa-solid fa-cart-shopping fa-shopping-cart"></i></li>
        </ul>
      </div>
    </nav>
  </section>

     
<section>
<!DOCTYPE html>
<html>
<head>
    <title>Product Search</title>
    <link rel="stylesheet" type="text/css" href="productdisplay.css">
</head>
<body>
    <div class="search-container">
        <form action="same_result.php" method="GET">
        <input type="text" id="searchInput" placeholder="Search for products..." name="query">
        <button type="button" id="searchButton">Search</button>
        </form>
    </div>
    <script>
        document.getElementById("searchButton").addEventListener("click", searchProducts);

        function searchProducts() {
            const searchTerm = document.getElementById("searchInput").value;

            if (searchTerm.trim() !== "") {
                // Redirect to the search results page with the search query as a URL parameter
                window.location.href = "same_result.php?query=" + encodeURIComponent(searchTerm);
            } else {
                alert("Please enter a search term.");
            }
        }
    </script>
</body>
</html>
</section>


  <section>
    <div class="sidebar">
      <ul class="sidebar-menu">
        <li class="category">
          <i class="fas fa-shopping-cart"></i>
          <a href="#">
            <b>Computers & Peripherals</b>
            <span class="arrow">&#8250;</span>
          </a>
          <ul class="sub-menu">
            <li><a href="#">Laptops</a></li>
              <li><a href="#">Printers & Scanners</a></li>
              <li><a href="#">Tablets PCs & More</a></li>
          </ul>
        </li>
        <li class="category">
          <i class="fas fa-user"></i>
          <a href="#">
            <b>Electronics, TVs, & More</b>
            <span class="arrow">&#8250;</span>
          </a>
          <ul class="sub-menu">
            <li><a href="#">Digital Cameras</a></li>
              <li><a href="#">Headphone & Earphone-Wired</a></li>
              <li><a href="#">Headphone & Earphone-Wireless</a></li>
              <li><a href="#">Televisions</a></li>
          </ul>
        </li>
        <li class="category">
          <i class="fas fa-cog"></i>
          <a href="#">
            <b>Kitchen Applicanes</b>
            <span class="arrow">&#8250;</span>
          </a>
          <ul class="sub-menu">
            <li><a href="#">Refrigerator</a></li>
            <li><a href="#">Microwave Oven</a></li>
            <li><a href="#">Mixture</a></li>
            <li><a href="#">Electric Kettle</a></li>
            <li><a href="#">Rice Cooker</a></li>
            <li><a href="#">Washing Machine</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </section>

  <!-- <section> -->
 <div id="banner">
      <img src="images/banner1.jpg" alt="Image 1">
     
     </div>
   <!-- </section>  -->

  <section>
    <div id="id">
        <h2>Shop <u></u>Now</h2>
    </div>
  </section>



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
</head>


<section>
  <!-- <div id="tag">
      <h2>Shop <u></u>Now</h2>
  </div>
</section> -->
<footer class="footer">
  <div class="footer-column">
    <h4>About</h4>
    <ul>
      <li>About Us</li>
      <li>Contact Us</li>
      <!-- <li>FAQ</li> -->
    </ul>
  </div>
  <div class="footer-column">
    <h4>Customer Service</h4>
    <ul>
      <!-- <li>Shipping &amp; Delivery</li>
      <li>Returns &amp; Exchanges</li> -->
      <li>Privacy Policy</li>
    </ul>
  </div>
  <div class="footer-column">
    <!-- <h4>My Account</h4>
    <ul>
      <li>View Cart</li>
      <li>Track My Order</li>
    </ul> -->
  </div>
</footer>

<footer class="copyright">
  <div class="footer-column">
    <h4>&copy; 2023 FewaExpress</h4>
    <p>All rights reserved.</p>
  </div>
</footer>
</section>

  <script src="navbar.js"></script>
  <script src="sidebar.js"></script>
  <script src="search.js"></script>
  <script src="explore.js"></script>
  </body>
  </html>
  
   