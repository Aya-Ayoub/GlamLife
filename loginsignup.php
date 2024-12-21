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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $firstName = $_POST['User_Fname'];
    $lastName = $_POST['User_Lname'];
    $email = $_POST['User_Email'];
    $password = password_hash($_POST['User_Password'], PASSWORD_BCRYPT);
    $userType = $_POST['User_Type'];

    $sql = "INSERT INTO users (User_Fname, User_Lname, User_Email, User_Password, User_Type) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $password, $userType);

    if ($stmt->execute()) {
        $message = "Signup successful!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Login functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['User_Email'];
    $password = $_POST['User_Password'];

    // Check for admin credentials
    if ($email == "admin@admin" && $password == "adminadmin") {
        session_start();
        // Redirect to the admin page
        header("Location: admin_page.php");
        exit();
    }

    // Fetch the user from the database by email
    $sql = "SELECT * FROM users WHERE User_Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['User_Password'])) {
            session_start();
            $_SESSION['user_id'] = $user['User_ID'];
            // Redirect to homepage
            header("Location: node_modules/startbootstrap-shop-homepage/dist/index3.php");
            exit();
        } else {
            // Incorrect password
            $message = "Incorrect email or password!";
        }
    } else {
        // No user found with the given email
        $message = "Incorrect email or password!";
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
    <title>Login and Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            color: #fedaf6;
            background: #e1ccdd;
            font: 600 16px/18px 'Open Sans', sans-serif;
        }

        .login-box {
            width: 100%;
            margin: auto;
            max-width: 525px;
            min-height: 670px;
            position: relative;
            box-shadow: 0 12px 15px 0 rgba(0, 0, 0, .24), 0 17px 50px 0 rgba(0, 0, 0, .19);
        }

        .login-snip {
            width: 100%;
            height: 100%;
            position: absolute;
            padding: 90px 70px 50px 70px;
            background: #fedaf6(0, 77, 77, .9);
        }

        .login-snip .login,
        .login-snip .sign-up-form {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: absolute;
            transform: rotateY(180deg);
            backface-visibility: hidden;
            transition: all .4s linear;
        }

        .login-snip .sign-in,
        .login-snip .sign-up,
        .login-space .group .check {
            display: none;
        }

        .login-snip .tab,
        .login-space .group .label,
        .login-space .group .button {
            text-transform: uppercase;
        }

        .login-snip .tab {
            font-size: 22px;
            margin-right: 15px;
            padding-bottom: 5px;
            margin: 0 15px 10px 0;
            display: inline-block;
            border-bottom: 2px solid transparent;
        }

        .login-snip .sign-in:checked+.tab,
        .login-snip .sign-up:checked+.tab {
            color: #231e1e;
            border-color: #fedaf6;
        }

        .login-space {
            min-height: 345px;
            position: relative;
            perspective: 1000px;
            transform-style: preserve-3d;
        }

        .login-space .group {
            margin-bottom: 15px;
        }

        .login-space .group .label,
        .login-space .group .input,
        .login-space .group .button {
            width: 100%;
            color: #231e1e;
            display: block;
        }

        .login-space .group .input,
        .login-space .group .button {
            border: none;
            padding: 15px 20px;
            border-radius: 25px;
            background: rgba(255, 255, 255, .1);
        }

        .login-space .group input[data-type="password"] {
            text-security: circle;
            -webkit-text-security: circle;
        }

        .login-space .group .label {
            color: #231e1e;
            font-size: 12px;
        }

        .login-space .group .button {
            background: #fedaf6;
        }

        .login-space .group label .icon {
            width: 15px;
            height: 15px;
            border-radius: 2px;
            position: relative;
            display: inline-block;
            background: rgba(255, 255, 255, .1);
        }

        .login-space .group label .icon:before,
        .login-space .group label .icon:after {
            content: '';
            width: 10px;
            height: 2px;
            background: #231e1e;
            position: absolute;
            transition: all .2s ease-in-out 0s;
        }

        .login-space .group label .icon:before {
            left: 3px;
            width: 5px;
            bottom: 6px;
            transform: scale(0) rotate(0);
        }

        .login-space .group label .icon:after {
            top: 6px;
            right: 0;
            transform: scale(0) rotate(0);
        }

        .login-space .group .check:checked+label {
            color: #231e1e;
        }

        .login-space .group .check:checked+label .icon {
            background: #fedaf6;
        }

        .login-space .group .check:checked+label .icon:before {
            transform: scale(1) rotate(45deg);
        }

        .login-space .group .check:checked+label .icon:after {
            transform: scale(1) rotate(-45deg);
        }

        .login-snip .sign-in:checked+.tab+.sign-up+.tab+.login-space .login {
            transform: rotate(0);
        }

        .login-snip .sign-up:checked+.tab+.login-space .sign-up-form {
            transform: rotate(0);
        }

        *,
        :after,
        :before {
            box-sizing: border-box
        }

        .clearfix:after,
        .clearfix:before {
            content: '';
            display: table
        }

        .clearfix:after {
            clear: both;
            display: block
        }

        a {
            color: inherit;
            text-decoration: none
        }

        .hr {
            height: 2px;
            margin: 60px 0 50px 0;
            background: rgba(255, 255, 255, .2);
        }

        .foot {
            text-align: center;
        }

        .card {
            width: 500px;
            left: 100px;
        }

        ::placeholder {
            color: #b3b3b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto p-0">
                <div class="card">
                    <div class="login-box">
                        <div class="login-snip">
                            <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
                            <label for="tab-1" class="tab">Login</label>
                            <input id="tab-2" type="radio" name="tab" class="sign-up">
                            <label for="tab-2" class="tab">Sign Up</label>
                            <div class="login-space">
                                <!-- Login Form -->
                                <div class="login">
                                    <form method="POST" action="">
                                        <div class="group">
                                            <label for="email" class="label">Email Address</label>
                                            <input id="email" name="User_Email" type="email" class="input"
                                                placeholder="Enter your email address" required>
                                        </div>
                                        <div class="group">
                                            <label for="pass" class="label">Password</label>
                                            <input id="pass" name="User_Password" type="password" class="input"
                                                placeholder="Enter your password" required>
                                        </div>
                                        <div class="group">
                                            <input type="submit" name="login" class="button" value="Sign In">
                                        </div>
                                        <div class="hr"></div>
                                        <div class="foot">
                                            <a href="forgot_password.php">Forgot Password?</a>
                                        </div>
                                        <div class="foot">
                                            <a href="reset_password.php">Reset Password?</a>
                                        </div>
                                    </form>
                                    <?php if (isset($message)) {
                                        echo "<p>$message</p>";
                                    } ?>
                                </div>
                                <!-- Signup Form -->
                                <div class="sign-up-form">
                                    <form method="POST" action="">
                                        <div class="group">
                                            <label for="fname" class="label">First Name</label>
                                            <input id="fname" name="User_Fname" type="text" class="input"
                                                placeholder="Enter your first name" required>
                                        </div>
                                        <div class="group">
                                            <label for="lname" class="label">Last Name</label>
                                            <input id="lname" name="User_Lname" type="text" class="input"
                                                placeholder="Enter your last name" required>
                                        </div>
                                        <div class="group">
                                            <label for="email" class="label">Email Address</label>
                                            <input id="email" name="User_Email" type="email" class="input"
                                                placeholder="Enter your email address" required>
                                        </div>
                                        <div class="group">
                                            <label for="pass" class="label">Password</label>
                                            <input id="pass" name="User_Password" type="password" class="input"
                                                placeholder="Enter your password" required>
                                        </div>
                                        <div class="group">
                                            <label for="usertype" class="label">User Type</label>
                                            <select name="User_Type" class="input" required>
                                                <option value="customer">Customer</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="group">
                                            <input type="submit" name="signup" class="button" value="Sign Up">
                                        </div>
                                    </form>
                                    <?php if (isset($message)) {
                                        echo "<p>$message</p>";
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
