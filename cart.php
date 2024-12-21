<?php
session_start(); // Start session to store cart

// Database connection
$conn = new mysqli("localhost", "admin", "password", "glamlife");

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize an empty cart
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $Product_ID = $_POST['product_id'];

    // Get product details from database
    $result = $conn->query("SELECT * FROM products WHERE Product_ID = $Product_ID");
    $product = $result->fetch_assoc();

    // Add product to cart session
    $_SESSION['cart'][$Product_ID] = [
        'Product_ID' => $product['Product_ID'],
        'Product_Name' => $product['Product_Name'],
        'Product_Price' => $product['Product_Price'],
        'Product_Image' => $product['Product_Image']
    ];

    // Insert into ShoppingCart table
    $User_ID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Ensure user ID is set
    $Cart_Quantity = $product['Product_Price']; // Using price as Cart_Quantity

    if ($User_ID) {
        $cart_insert_query = "INSERT INTO shoppingcart (User_ID, Cart_Quantity) VALUES (?, ?)";
        $stmt = $conn->prepare($cart_insert_query);
        $stmt->bind_param("ii", $User_ID, $Cart_Quantity);
        $stmt->execute();
    }

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
$show_message = false; // Flag to control when messages are displayed
if (isset($_POST['place_order'])) {
    if ($user_logged_in) {
        // Check if the cart is empty
        if (empty($_SESSION['cart'])) {
            echo '<div class="alert alert-warning">Your cart is empty. Please add items to proceed with the order.</div>';
        } else {
            // Insert a new cart for the user
            $cart_insert_query = "INSERT INTO shoppingcart (User_ID, Cart_Quantity) VALUES (?, 0)";
            $stmt = $conn->prepare($cart_insert_query);
            $stmt->bind_param("i", $User_ID);
            if ($stmt->execute()) {
                $Cart_ID = $conn->insert_id; // Get the generated Cart_ID

                // Loop through the cart and insert each product into the orders table
                foreach ($_SESSION['cart'] as $Product_ID => $product) {
                    $stmt = $conn->prepare("INSERT INTO orders (Product_ID, User_ID) VALUES (?, ?)");
                    $stmt->bind_param("ii", $Product_ID, $User_ID); // Bind parameters: Product_ID and User_ID
                    
                    if ($stmt->execute()) {
                        $order_placed = true; // Order placed successfully
                    } else {
                        echo "Error placing order: " . $conn->error; // Handle error
                        exit();
                    }
                }

                unset($_SESSION['cart']); // Clear the cart after placing the order
            }
        }
    } else {
        $show_message = true; // Show message if not logged in
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
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $Product_ID => $product) {
                            $subtotal = $product['Product_Price'];
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
                    <?php 
                        } 
                    } else {
                        echo '<div class="alert alert-warning">Your cart is empty.</div>';
                    } ?>
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
                        <span class="fw-bold">$<?php echo number_format($total_price + 70, 2); ?></span>
                    </div>
                    <form method="POST">
                        <?php if (!empty($_SESSION['cart'])): ?>
                            <button class="btn btn-primary checkout-btn w-100 mb-3" name="place_order">
                                Place an order
                            </button>
                        <?php else: ?>
                            <button class="btn btn-primary checkout-btn w-100 mb-3" disabled>
                                Cart is empty
                            </button>
                        <?php endif; ?>
                    </form>
                    
                    <?php if ($show_message && !$user_logged_in): ?>
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
