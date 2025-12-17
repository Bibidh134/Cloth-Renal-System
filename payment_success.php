<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    exit();
}

$username = $_SESSION['username'];

foreach ($cart as $item) {
    $cloth_name = $item['cloth_name'];
    $price = $item['price'];
    $quantity = $item['quantity'];
    $subtotal = $price * $quantity;
    $image = $item['image'];
    $rental_start = $item['rental_start'] ?? null;
    $rental_end = $item['rental_end'] ?? null;

    $stmt = $conn->prepare("INSERT INTO orders (username, cloth_name, price, quantity, total, image, rental_start, rental_end, ordered_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssiiisss", $username, $cloth_name, $price, $quantity, $subtotal, $image, $rental_start, $rental_end);
    $stmt->execute();
}

$stmt->close();

// Clear the cart after saving order
$_SESSION['cart'] = [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Payment Success</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; text-align: center; padding: 50px; }
        .success-message { background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; display: inline-block; }
        a { display: block; margin-top: 20px; color: #0d6efd; text-decoration: none; }
    </style>
</head>
<body>

<div class="success-message">
    <h2>✅ Payment Successful!</h2>
    <p>Your order has been placed.</p>
    <a href="homepage2.php">Return to Homepage</a>
</div>

</body>
</html>
