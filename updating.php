<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Products</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        td .edit-field {
            width: 100%;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>sellername</th>
                <th>contact</th>
                <th>category</th>
                <th>subcategory</th>
                <th>productname</th>
                <th>id</th>
                <th>price</th>
                <th>brand</th>
                <th>description</th>
                <th>image</th>
                <th>created_at</th>
                <th>action</th>
            </tr>
        </thead>
        <tbody>
        <?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_authentication";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to save edited values
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $productname = $_POST['productname'];
    $price = $_POST['price'];
    $brand = $_POST['brand'];
    $description = $_POST['description'];

    $sql = "UPDATE products SET category=?, subcategory=?, productname=?, price=?, brand=?, description=? WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $category, $subcategory, $productname, $price, $brand, $description, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

// Execute the query to fetch the products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Check if any products were returned
if ($result->num_rows > 0) {
    // Output the products in the table
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["sellername"] . "</td>";
        echo "<td>" . $row["contact"] . "</td>";
        echo "<td data-field='category'><input type='text' name='category' id='category_" . $row["id"] . "' value='" . $row["category"] . "'></td>";
        echo "<td data-field='subcategory'><input type='text' name='subcategory' id='subcategory_" . $row["id"] . "' value='" . $row["subcategory"] . "'></td>";
        echo "<td data-field='productname'><input type='text' name='productname' id='productname_" . $row["id"] . "' value='" . $row["productname"] . "'></td>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td data-field='price'><input type='text' name='price' id='price_" . $row["id"] . "' value='" . $row["price"] . "'></td>";
        echo "<td data-field='brand'><input type='text' name='brand' id='brand_" . $row["id"] . "' value='" . $row["brand"] . "'></td>";
        echo "<td data-field='description'><input type='text' name='description' id='description_" . $row["id"] . "' value='" . $row["description"] . "'></td>";
        echo "<td>" . ($row["image"] ?? $row["image"]) . "</td>";
        echo "<td>" . $row["created_at"] . "</td>";
        echo "<td>";
        echo "<button class='edit-button' onclick='editProduct(" . $row["id"] . ")'>Edit</button>";
        echo "<button class='save-button' onclick='saveProduct(" . $row["id"] . ")'>Save</button>";
        echo "<button class='delete-button' onclick='deleteProduct(" . $row["id"] . ")'>Delete</button>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No products found.</td></tr>";
}

// Close the connection
$conn->close();
?>
</tbody>
</table>

<script>
function editProduct(id) {
    const categoryField = document.getElementById(`category_${id}`);
    const subcategoryField = document.getElementById(`subcategory_${id}`);
    const productnameField = document.getElementById(`productname_${id}`);
    const priceField = document.getElementById(`price_${id}`);
    const brandField = document.getElementById(`brand_${id}`);
    const descriptionField = document.getElementById(`description_${id}`);
    
    categoryField.disabled = false;
    subcategoryField.disabled = false;
    productnameField.disabled = false;
    priceField.disabled = false;
    brandField.disabled = false;
    descriptionField.disabled = false;
}

function saveProduct(id) {
    const categoryField = document.getElementById(`category_${id}`);
    const subcategoryField = document.getElementById(`subcategory_${id}`);
    const productnameField = document.getElementById(`productname_${id}`);
    const priceField = document.getElementById(`price_${id}`);
    const brandField = document.getElementById(`brand_${id}`);
    const descriptionField = document.getElementById(`description_${id}`);
    
    const category = categoryField.value;
    const subcategory = subcategoryField.value;
    const productname = productnameField.value;
    const price = priceField.value;
    const brand = brandField.value;
    const description = descriptionField.value;
    
    // Send the edited values to the server using AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'edit.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log('Data saved successfully');
            } else {
                console.log('Error saving data');
            }
        }
    };
    xhr.send(`id=${id}&category=${category}&subcategory=${subcategory}&productname=${productname}&price=${price}&brand=${brand}&description=${description}`);
    
    categoryField.disabled = true;
    subcategoryField.disabled = true;
    productnameField.disabled = true;
    priceField.disabled = true;
    brandField.disabled = true;
    descriptionField.disabled = true;
}

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        // Send the delete request to the server using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Product deleted successfully');
                    // Remove the deleted row from the table
                    const row = document.getElementById(`row_${id}`);
                    row.parentNode.removeChild(row);
                } else {
                    console.log('Error deleting product');
                }
            }
        };
        xhr.send(`seller_id=${id}`);
    }
}
</script>
</body>
</html>
