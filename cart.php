<?php
session_start(); // Start session to store cart

// Database connection
$conn = new mysqli("localhost", "rana", "roro*2005", "glamlife");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the cart already exists in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize cart if it doesn't exist
}

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $Product_ID = $_POST['product_id'];
    //$quantity = $_POST['quantity'];

    // Check if the product already exists in the cart
    //if (isset($_SESSION['cart'][$Product_ID])) {
       // $_SESSION['cart'][$Product_ID]['quantity'] += $quantity; // Increment the quantity
    //} else {
        // Get product details from database
        $result = $conn->query("SELECT * FROM products WHERE Product_ID = $Product_ID");
        $product = $result->fetch_assoc();

        // Add product to cart
        $_SESSION['cart'][$Product_ID] = [
            'Product_ID' => $product['Product_ID'],
            'Product_Name' => $product['Product_Name'],
            'Product_Price' => $product['Product_Price'],
            //'quantity' => $quantity,
            'Product_Image' => $product['Product_Image']
        ];
    
    header("Location: cart.php"); // Redirect to cart page
    exit();
}

// Remove product from cart
if (isset($_GET['remove'])) {
    $Product_ID = $_GET['remove'];
    unset($_SESSION['cart'][$Product_ID]); // Remove product from cart
    header("Location: cart.php"); // Redirect to cart page
    exit();
}


// Check if user is logged in
$user_logged_in = isset($_SESSION['user_id']);
$User_ID = $user_logged_in ? $_SESSION['user_id'] : null; // Get User_ID if logged in, else null

$order_placed = false; // Flag to track order placement success
$user_found = false; // Flag to track if user is found in database
$show_message = false; // Flag to control when messages are displayed
if (isset($_POST['place_order'])) {
    if ($user_logged_in) {
        // Check if the user exists in the database
        $user_check_query = "SELECT * FROM users WHERE User_ID = ?";
        $stmt = $conn->prepare($user_check_query);
        $stmt->bind_param("i", $User_ID); // Bind the user_id to the query
        $stmt->execute();
        $user_result = $stmt->get_result();

        if ($user_result->num_rows > 0) {
            // User is found in the database, place the order
            $user_found = true;

            // Loop through the cart and insert each product into the orders table
            foreach ($_SESSION['cart'] as $Product_ID => $product) {
                // Prepare and execute the insert query
                $stmt = $conn->prepare("INSERT INTO orders (Product_ID, User_ID) VALUES (?, ?)");
                $stmt->bind_param("ii", $Product_ID, $User_ID); // Bind parameters: Product_ID and User_ID

                if ($stmt->execute()) {
                    // Order placed successfully
                    $order_placed = true;
                } else {
                    // Handle error
                    echo "Error placing order: " . $conn->error;
                    exit();
                }
            }

            if ($order_placed) {
                unset($_SESSION['cart']); // Clear the cart after placing the order
            }
        } else {
            // User not found in the database
            $user_found = false;
        }
    } else {
        // User is not logged in
        $show_message = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .product-card {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .product-details {
            flex-grow: 1;
            margin-left: 15px;
        }
        .remove-btn {
            color: red;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .remove-btn:hover {
            opacity: 0.7;
        }
        .checkout-btn {
            background-color: #fedaf6;
            color: black;
            font-weight: bold;
        }
        .product-price {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row g-4">
            <!-- Cart Items Section -->
            <div class="col-lg-8">
                <h4 class="mb-4">Shopping Cart</h4>
                <div class="d-flex flex-column gap-3">
                    <?php
                    $total_price = 0;
                    foreach ($_SESSION['cart'] as $Product_ID => $product) {
                        $subtotal = $product['Product_Price']; //$product['quantity'];
                        $total_price += $subtotal;
                    ?>
                        <div class="product-card p-3 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="<?php echo $product['Product_Image']; ?>" alt="Product" class="product-image">
                                </div>
                                
                                <div class="col-md-6 product-details">
                                    <span class="fw-bold"><?php echo $product['Product_Name']; ?></span><br>
                                    <span class="product-price">$<?php echo $product['Product_Price']; ?></span>
                                </div>
                                
                                <div class="col-md-1">
                                    <a href="cart.php?remove=<?php echo $Product_ID; ?>" class="bi bi-trash remove-btn"></a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="col-lg-4">
                <div class="p-4 shadow-sm">
                    <h5 class="mb-4">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span>$<?php echo number_format($total_price, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Standard Shipment</span>
                        <span>$70.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold">$<?php echo number_format($total_price+70, 2); ?></span>
                    </div>
                    <form method="POST">
                        <button class="btn btn-primary checkout-btn w-100 mb-3" name="place_order">
                            Place an order
                        </button>
                    </form>
                    
                    <!-- Display message only after trying to place an order -->
                    <?php if ($show_message && !$user_logged_in): ?>
                        <div class="alert alert-warning mt-4">
                            user not found
                        </div>
                    <?php endif; ?>
                    <?php if (!$user_found && !$user_logged_in && isset($_POST['place_order'])): ?>
                        <div class="alert alert-warning mt-4">
                            User not found. Please log in to place an order.
                        </div>
                    <?php endif; ?>
                    <?php if ($order_placed): ?>
                        <div class="alert alert-success mt-4">
                            Order placed successfully! Your cart has been cleared.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
