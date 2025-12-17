<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    // User is logged in, redirect to checkout
    header('Location: checkout.php');
    exit();
} else {
    // Not logged in, show message and redirect to login
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Redirecting to Login</title>
        <style>
            body {
                background-color: #fefefe;
                font-family: Arial, sans-serif;
                text-align: center;
                margin-top: 100px;
                color: #333;
            }
            a {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #38bdf8;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: 500;
            }
            a:hover {
                background-color: #1e293b;
            }
        </style>
    </head>
    <body>
        <h2>You need to log in to proceed to checkout.</h2>
        <a href="register.php">Go to Login</a>
    </body>
    </html>
    <?php
    exit();
}
