<?php
// Database connection (phpMyAdmin uses root with no password by default)
$conn = new mysqli("localhost", "Aya", "password", "glamlife1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products
$sql = "SELECT Product_Name, Product_img FROM product";
$result = $conn->query($sql);

// Display products
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h2>" . $row['Product_Name'] . "</h2>";
        echo "<img src='" . $row['Product_img'] ."'>";
        echo "</div>";
    }
} else {
    echo "No products found.";
}
$conn->close();
?>
