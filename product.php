<?php
session_start(); // Start session to manage cart and review

// Database connection
$conn = new mysqli("localhost", "admin", "password", "glamlife");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID from the URL
$Product_ID = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

// Fetch product details from the database
if ($Product_ID > 0) {
    $product_query = "SELECT * FROM products WHERE Product_ID = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $Product_ID);
    $stmt->execute();
    $product_result = $stmt->get_result();

    if ($product_result && $product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
    } else {
        echo "Product not found!";
        exit();
    }
} else {
    echo "Invalid product ID!";
    exit();
}

// Review Submission Logic (without User_ID)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating']) && isset($_POST['product_id'])) {
    $rating = $_POST['rating'];
    $product_id = $_POST['product_id'];

    // Check if rating is valid (between 1 and 5)
    if ($rating >= 1 && $rating <= 5) {
        // Insert review into database without User_ID
        $insert_review_query = "INSERT INTO reviews (Product_ID, Rating) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_review_query);
        $stmt->bind_param("ii", $product_id, $rating);
        $stmt->execute();
    }
}

// Add to Cart Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    // Assume user is always logged in, no check for $_SESSION['user_id']
    // Default to a dummy user_id (you should replace this with the actual logged-in user's ID logic)
    $user_id = 1; // Default user ID for testing (change this if needed)

    // Check if cart exists in session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$Product_ID])) {
        $_SESSION['cart'][$Product_ID]['quantity'] += 1; // Increase quantity (since there's no quantity input now)
    } else {
        $_SESSION['cart'][$Product_ID] = [
            'Product_ID' => $product['Product_ID'],
            'Product_Name' => $product['Product_Name'],
            'Product_Price' => $product['Product_Price'],
            'Product_Image' => $product['Product_Image'],
            'quantity' => 1 // Default quantity is 1
        ];
    }

    // Redirect to the cart page after adding to cart
    header("Location: cart.php");
    exit();
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
    <link rel="stylesheet" href="startbootstrap-sb-admin-2-gh-pages\css\sb-admin-2.css">
    <link rel="stylesheet" href="startbootstrap-sb-admin-2-gh-pages\css\sb-admin-2.min.css">
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

        .btn-submit-review {
            background-color: #fedaf6;
            color: black;
            border: 1px solid black;
        }

        .btn-submit-review:hover {
            background-color: violet;
        }

        .product-info {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product-details h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .product-details p {
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6 mb-4">
                <div class="product-image-container">
                    <img src="<?php echo $product['Product_Image']; ?>" alt="Product Image">
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <div class="product-info">
                    <div class="product-details">
                        <h2><?php echo $product['Product_Name']; ?></h2>
                        <p class="text-muted">SKU: <?php echo $product['Product_ID']; ?></p>
                        <div class="mb-3">
                            <span class="h4 me-2">$<?php echo $product['Product_Price']; ?></span>
                        </div>
                        <p class="mb-4"><?php echo $product['Product_Description']; ?></p>
                    </div>

                    <!-- Review Form -->
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?product_id=<?php echo $Product_ID; ?>">
                        <div class="mb-4">
                            <label for="rating" class="form-label">Review (Rating 1-5):</label>
                            <input type="number" class="form-control" id="rating" name="rating" value="1" min="1" max="5" style="width: 80px;">
                        </div>
                        <input type="hidden" name="product_id" value="<?php echo $Product_ID; ?>">
                        <button type="submit" class="btn btn-submit-review">Submit Review</button>
                        <button class="btn btn-purple btn-lg mb-3 me-2" type="submit" name="add_to_cart">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                </div>
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
