<?php
// db_connect.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "glamlife";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['User_Email'];

    // Check if email exists in the database
    $sql = "SELECT * FROM users WHERE User_Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Here you would typically send a reset link via email
        $message = "Reset link sent to your email!";
    } else {
        $message = "Email not found!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <h2>Forgot Password</h2>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="User_Email" required>
                    </div>
                    <button class="btn btn-custom" type="submit">Resend Link</button>
                </form>
                <?php if (isset($message)) {
                    echo "<div class='alert alert-info mt-3'>$message</div>";
                } ?>
            </div>
        </div>
    </div>
</body>

</html>