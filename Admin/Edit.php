<?php
$conn = new mysqli("localhost", "Admin_Page", "202301062", "glamlife");
session_start();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['Product_ID'];
    $product_name = $_POST['Product_Name'];
    $product_description = $_POST['Product_Description'];
    $product_price = $_POST['Product_Price'];
    $product_image = $_POST['Product_Image']; 

    // Update the product in the database
    $sql = "UPDATE products SET Product_Name=?, Product_Description=?, Product_Price=?, Product_Image=? WHERE Product_ID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $product_name, $product_description, $product_price, $product_image, $product_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to products page
    header("Location: products.php");
    exit();
}

// Fetch the product details for the given ID
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE Product_ID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Product ID not specified.");
}
?>

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


    <div class="container">
        <h1>Edit Product</h1>
        <form method="POST" action="edit.php">
            <input type="hidden" name="Product_ID" value="<?php echo htmlspecialchars($product['Product_ID']); ?>">
            
            <div class="form-group">
                <label for="Product_Name">Product Name:</label>
                <input type="text" class="form-control" name="Product_Name" value="<?php echo htmlspecialchars($product['Product_Name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="Product_Description">Product Description:</label>
                <textarea class="form-control" name="Product_Description" required><?php echo htmlspecialchars($product['Product_Description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="Product_Price">Product Price:</label>
                <input type="number" class="form-control" name="Product_Price" value="<?php echo htmlspecialchars($product['Product_Price']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="Product_Image">Product Image URL:</label>
                <input type="text" class="form-control" name="Product_Image" value="<?php echo htmlspecialchars($product['Product_Image']); ?>">
            </div>
            
            <button type="submit" class="btn btn-primary text-secondary">Update Product</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; GlamLife 2024</span>
                    </div>
                </div>
            </footer>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
     <!-- Bootstrap core JavaScript-->
     <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
</body>
</html>

<?php
$conn->close();
?>