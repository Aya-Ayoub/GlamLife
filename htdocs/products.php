<?php
$conn = new mysqli("localhost", "Admin_Page", "202301062", "glamlife");
session_start();
if (empty($_SESSION['browser_opened'])) {
    shell_exec("start http://localhost/products.php");
    $_SESSION['browser_opened'] = true;
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Query the Database
$sql = "SELECT Product_ID, Product_Name, Product_Description, Product_Price, Product_Image FROM products"; 
$result = $conn->query($sql);
   
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
                    <ul class="navbar-nav ml-auto"> <!-- Added ml-auto to align to the right -->
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php" onclick="return confirm('Are you sure you want to log out?');">
                                <button class="btn btn-danger text-white">Logout</button>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Products</h1>
                    <p class="mb-4">In this page you can add/edit/delete the products.</p>

                    <!-- Search Bar -->
                    <label for="searchInput">Search:</label>
                    <input type="search" id="searchInput" class="form-control form-control-sm" placeholder="Search products..." aria-controls="dataTable" style="width: 200px; display: inline-block;">
                    <script>
                     document.getElementById('searchInput').addEventListener('keyup', function() {
                    var input = this.value.toLowerCase();
                    var table = document.getElementById('dataTable');
                     var rows = table.getElementsByTagName('tr');

                     for (var i = 1; i < rows.length; i++) { // Skip the first row (headers)
                    var cells = rows[i].getElementsByTagName('td');
                    var found = false;

                     for (var j = 0; j < cells.length; j++) {
                            if (cells[j].textContent.toLowerCase().indexOf(input) > -1) {
                            found = true;
                             break;
                             }
                        }

                         rows[i].style.display = found ? '' : 'none'; // Show or hide the row
                         }
                         });
                    </script>
                    <!-- Product table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-secondary">Products</h6>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-primary text-secondary" onclick="window.location.href='addproductpage.php'">Add Product</button>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Product ID</th>
                                            <th>Product Image</th>
                                            <th>Product Name</th>
                                            <th>Product Description</th>
                                            <th>Product Price</th>
                                            <th>Edit</th> 
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                       
            // Check if there are results
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['Product_ID'] . "</td>";
                    echo "<td><img src='" . $row['Product_Image'] . "' alt='Product Image' width='50'></td>";
                    echo "<td>" . $row['Product_Name'] . "</td>";
                    echo "<td>" . $row['Product_Description'] . "</td>";
                    echo "<td>" . $row['Product_Price'] . "</td>";
                    // Edit button
                // Edit button
                    echo "<td>
                        <a href='edit.php?id=" . $row['Product_ID'] . "'>
                         <img src='GlamLife/startbootstrap-sb-admin-2-gh-pages/img/edit.png' alt='Edit' width='20' style='cursor:pointer;'>
                        </a>
                    </td>";
        
        // Delete button
        echo "<td>
                <a href='delete.php?id=" . $row['Product_ID'] . "' onclick='return confirm(\"Are you sure you want to delete this item?\");'>
                    <img src='GlamLife/startbootstrap-sb-admin-2-gh-pages/img/delete.png' alt='Delete' width='20' style='cursor:pointer;'>
                </a>
              </td>";
        

                }
            } else {
                echo "<tr><td colspan='6'>No records found</td></tr>"; // Adjusted colspan to match the number of columns
            }
            // Close connection
            $conn->close();
            ?>
             </table>
             
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; GlamLife 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
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