<?php 
include('db_connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check_query = "SELECT * FROM admin WHERE username='$username' AND password='$password'"; 
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 1) {
        $_SESSION['username'] = $username;
        header('Location: /ClothRental/Dashboard/dashboard.php');
        exit();
    } else {
        $error_message = "Incorrect username or password";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/ClothRental/style/css.css">
</head>
<body>
    <div class="login-form" id="loginForm">
        <h2>Admin Login</h2>
        <!-- Display error message if set -->
        <?php if (isset($error_message)) : ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form id="loginForm" action="#" method="post" onsubmit="return validateForm()">
            <input type="text" name="username" id="username" placeholder="Username">
            <input type="password" name="password" id="password" placeholder="Password">
            <button type="submit" name="login">Login</button>
        </form>
    </div>
    <script>
        function validateForm() {
            var username = document.getElementById('username').value;
            if (username === "") {
                alert("Please enter a valid username");
                return false;
            }
            var password = document.getElementById('password').value;
            if (password.length < 8) {
                alert("Password must be at least 8 characters long");
                return false;
            }
            if (username === "" || password === "") {
                alert("All fields must be filled out");
                return false;
            }
            return true; 
        }
    </script> 
</body>
</html>
