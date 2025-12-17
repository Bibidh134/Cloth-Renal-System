<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login</title>
    <link rel="stylesheet" href="/ClothRental/style/css.css">
</head>
<body>
<div class="login-form" id="loginForm">
    <h2>Login</h2>
    <form id="loginForm" action="login.php" method="post" onsubmit="return validateForm()">
        <?php include('errors.php'); ?>
        <input type="text" name="email" id="email" placeholder="Email">
        <input type="password" name="password" id="password" placeholder="Password">
        <button type="submit" name="login">Login</button>
    </form>
</div>

<script>
function validateForm() {
    var email = document.getElementById('email').value;
    var pattern = /^\w+([\.]?\w+)*@\w+([\.]?\w+)*(\.\w{2,3})+$/;
    if (!pattern.test(email)) {
        alert("Please enter a valid email address");
        return false;
    }
    var password = document.getElementById('password').value;
    if (password.length < 8) {
        alert("Password must be at least 8 characters long");
        return false;
    }
    return true;
}
</script>
</body>
</html>
