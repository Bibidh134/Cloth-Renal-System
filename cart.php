<?php
session_start();

// Add item to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $cloth_id = $_POST['cloth_id'];
    $cloth_name = $_POST['cloth_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_POST['image'];

    // Get rental dates
    $rental_start = $_POST['rental_start'];
    $rental_end = $_POST['rental_end'];

    // Create cart array if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If item exists and rental dates match, update quantity
    if (isset($_SESSION['cart'][$cloth_id]) &&
        $_SESSION['cart'][$cloth_id]['rental_start'] === $rental_start &&
        $_SESSION['cart'][$cloth_id]['rental_end'] === $rental_end
    ) {
        $_SESSION['cart'][$cloth_id]['quantity'] += $quantity;
    } else {
        // Use unique key to differentiate same item with different dates
        $unique_key = $cloth_id . '_' . $rental_start . '_' . $rental_end;

        $_SESSION['cart'][$unique_key] = [
            'cloth_id' => $cloth_id,
            'cloth_name' => $cloth_name,
            'price' => $price,
            'quantity' => $quantity,
            'image' => $image,
            'rental_start' => $rental_start,
            'rental_end' => $rental_end
        ];
    }

    header("Location: cart_view.php");
    exit();
}
?>
