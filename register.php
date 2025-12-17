<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/ClothRental/style/css.css">
</head>
<body>
    
    <div class="register-form" id="registerForm">
        <h2>Register</h2>
        <form action="#" method="post" onsubmit="return validateForm()">
     
            <input type="text" id="username" name="username" placeholder="Username" >
            <input type="email" id="email" name="email" placeholder="Email" >
            <input type="password" id="password" name="password" placeholder="Password" >
            <button type="submit" name="reg">Register</button>
            <div class="register">
                <p>Already have an account? <a href="login.php" class="register-link" id="loginLink">Login</a></p>
            </div>
        </form>
    </div>
<script>
function validateForm() {
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    if (username === "") {
        alert("Username must be filled out");
        return false;
    }

    var Alpha = /^\w+([\.]?\w+)*@\w+([\.]?\w+)*(\.\w{2,3})+$/;
    if (!email.match(Alpha) || email === "") {
        alert("Please enter a valid email address");
        return false;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters long");
        return false;
    }

    if (username === "" || email === "" || password === "") {
        alert("All fields must be filled out");
        return false;
    }

    return true; 
}
</script>
</body>
</html>
