<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Products Page</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light" style="background-color: #ffdbf7;">


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav mx-auto"> <!-- Center the button using mx-auto -->
                        <!-- Products Button -->
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">
                                <button class="btn btn-primary text-secondary">Products</button>
                            </a>
                        </li>
                    </ul>

                    <!-- Nav Item - User Information -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle" src="img/admin.jpg">
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
</head>
<body>
    <div class="container">
        <h1>Add Product</h1>
        <form action="addproductpage.php" method="post">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="product_description">Product Description:</label>
                <textarea class="form-control" id="product_description" name="product_description" required></textarea>
            </div>
            <div class="form-group">
                <label for="product_price">Product Price:</label>
                <input type="number" class="form-control" id="product_price" name="product_price" required>
            </div>
            <div class="form-group">
                <label for="product_image">Product Image URL:</label>
                <input type="url" class="form-control" id="product_image" name="product_image" required>
            </div>
            <div class="form-group">
                <label for="product_category">Product Category ID (1=HairCare, 2=BodyCare, 3=SkinCare, 4=Makeup):</label>
                <input type="number" class="form-control" id="product_category" name="product_category" required min="1" max="4">
            </div>
            <button type="submit" class="btn btn-primary text-secondary"> Add Product </button>
            <a href="products.php" class="btn btn-secondary"> Cancel </a>
        </form>
        
    </div>
</body>
</html>

<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "Admin_Page", "202301062", "glamlife");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $product_description = $conn->real_escape_string($_POST['product_description']);
    $product_price = $conn->real_escape_string($_POST['product_price']);
    $product_image = $conn->real_escape_string($_POST['product_image']); // Get the image URL

    // Get the product_category from user input
    if (isset($_POST['product_category']) && !empty($_POST['product_category'])) {
        $product_category = (int)$_POST['product_category']; // Get the selected category ID

        // Validate category ID
        if ($product_category < 1 || $product_category > 4) {
            die("Invalid category ID. Please enter a number between 1 and 4.");
        }

        // Check if the category exists in the categories table
        $category_check_sql = "SELECT * FROM category WHERE Category_ID = $product_category";
        $category_result = $conn->query($category_check_sql);

        if ($category_result->num_rows == 0) {
            die("Invalid category ID. The category does not exist.");
        }
    } else {
        die("Product category is not set.");
    }

    // Insert the new product into the database
    $sql = "INSERT INTO products (Product_Name, Product_Description, Product_Price, Product_Image, C_ID) VALUES ('$product_name', '$product_description', '$product_price', '$product_image', '$product_category')";

    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully.";
    } else {
        echo "Error: ";
    }
}
?>
