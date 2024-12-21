<?php
// db_connect.php
$servername = "localhost";
$username = "admin";
$password = "password";
$dbname = "glamlife";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['User_Email'];
    $newPassword = password_hash($_POST['User_Password'], PASSWORD_BCRYPT);

    // Update the password in the database
    $sql = "UPDATE users SET User_Password = ? WHERE User_Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newPassword, $email);

    if ($stmt->execute()) {
        $message = "Password reset successful!";
    } else {
        $message = "Error resetting password: " . $stmt->error;
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
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <h2>Reset Password</h2>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="User_Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="pass" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="pass" name="User_Password" required>
                    </div>
                    <button class="btn btn-custom" type="submit">Reset Password</button>
                </form>
                <?php if (isset($message)) {
                    echo "<div class='alert alert-info mt-3'>$message</div>";
                } ?>
            </div>
        </div>
    </div>
</body>

</html>