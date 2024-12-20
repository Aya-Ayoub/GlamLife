<?php
// Database connection (phpMyAdmin uses root with no password by default)
$conn = new mysqli("localhost", "rana", "roro*2005", "glamlife");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product details from the database
$Product_ID = 15; // Example Product ID, you can dynamically set this based on user interaction or URL
$product_query = "SELECT * FROM products WHERE Product_ID = $Product_ID";
$product_result = $conn->query($product_query);

// Check if the product exists
if ($product_result && $product_result->num_rows > 0) {
    $product = $product_result->fetch_assoc();
} else {
    echo "Product not found!";
    exit();  // Terminate script if no product is found
}

//  Add to cart logic (if form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $User_ID = 1; // Example User ID, modify as per session or authentication.
    $cart_quantity = $_POST['quantity'];

    // Insert into ShoppingCart table
    $cart_query = "INSERT INTO ShoppingCart (User_Id, Product_Id, Cart_Quantity) VALUES ($User_ID, $Product_ID, $Cart_Quantity)";
    if ($conn->query($cart_query) === TRUE) {
        echo "Product added to cart successfully!";
    } else {
        echo "Error: " . $conn->error;
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #fedaf6; /* Set the page background color to purple */
            color: black; /* Set text color to white for contrast */
        }

        .product-image-container {
            width: 400px; /* Make the frame a square */
            height: 400px; /* Same height as width */
            background-color: white; /* Grey background for the placeholder */
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px; /* Optional: Add rounded corners */
            overflow: hidden; /* Ensure the image fits inside the frame */
            margin: 0 auto; /* Center the image on the page */
        }

        .product-image-container img {
            max-height: 100%;
            max-width: 100%;
            object-fit: cover; /* Ensure the image covers the frame */
        }

        .btn-purple {
            background-color: #fedaf6; /* Button background color */
            border: 1px solid black; /* Thin black frame */
            color: black; /* Text color */
        }

        .btn-purple:hover {
            background-color: violet; /* Slightly darker purple on hover */
        }
        btn-submit-review {
            background-color: #fedaf6; /* Button background color */
            border: 1px solid black; /* Thin black frame */
            color: black; /* Text color */

}

.btn-submit-review:hover {
    background-color: violet; 
}
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6 mb-4">
                <div class="product-image-container">
                    <img src="<?php echo $product['Product_Image']; ?>" alt="Product Image" id="productImage">
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <h2 class="mb-3"><?php echo $product['Product_Name']; ?></h2>
                <p class="text-muted mb-4">SKU: <?php echo $product['Product_ID']; ?></p>
                <div class="mb-3">
                    <span class="h4 me-2">$<?php echo $product['Product_Price']; ?></span>
                </div>
               
                <p class="mb-4"><?php echo $product['Product_Description']; ?></p>
                
                <!-- Add to Cart Form -->
                <form method="POST" action="cart.php">
                    <div class="mb-4">
                      <label for="rating" class="form-label">Review:</label>
                      <input type="number" class="form-control" id="rating" name="rating" value="1" min="1" max="5" style="width: 80px;">
                    </div>
                    <input type="hidden" name="product_id" value="<?php echo $Product_ID; ?>"> <!-- Pass Product ID -->
                    <button type="submit" class="btn btn-submit-review">Submit Review</button>
                    <button class="btn btn-purple btn-lg mb-3 me-2" type="submit" name="add_to_cart">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
</form>

               <!-- <form method="POST" action="cart.php">
                    <div class="mb-4">
                        <label for="quantity" class="form-label">Review:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1"  max="5"style="width: 80px;">
                    </div>
                    <button class="btn btn-purple btn-lg mb-3 me-2" type="submit" name="add_to_cart">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </form>-->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
