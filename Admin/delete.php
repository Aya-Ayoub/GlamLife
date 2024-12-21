<?php
// Database connection
$conn = new mysqli("localhost", "admin", "password", "glamlife");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure the ID is an integer

    // Prepare the delete statement
    $delete_sql = "DELETE FROM products WHERE Product_ID = $id";

    if ($conn->query($delete_sql) === TRUE) {
        // Reassign IDs after deletion
        $update_sql = "UPDATE products SET Product_ID = Product_ID - 1 WHERE Product_ID > $id";
        $conn->query($update_sql);

        // Redirect back to the product list after deletion
        header("Location: products.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error; // Output error
    }
} else {
    echo "No ID provided for deletion."; // Debugging line
}

// Close connection
$conn->close();
?>