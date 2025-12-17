<?php
require __DIR__ . '/vendor/autoload.php';  // Correct path from the same folder

session_start();
include('db_connect.php');

\Stripe\Stripe::setApiKey('sk_test_51RPbMPBDhgPumJRQDm9b4wTBUF1wmtloDVI5I9MTs0xDV6VazqiJl6jCiZdvp1JT6ZN9fRBxtMOKyxfQndnGxYcb00dWal32xg'); // Replace with your Stripe secret key

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die("Your cart is empty.");
}

try {
    $line_items = [];
    foreach ($cart as $item) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => intval($item['price'] * 100),
                'product_data' => [
                    'name' => $item['cloth_name'],
                ],
            ],
            'quantity' => $item['quantity'],
        ];
    }

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => 'http://localhost/ClothRental/payment_success.php',
        'cancel_url' => 'http://localhost/ClothRental/checkout.php',
    ]);

    header("Location: " . $session->url);
    exit();

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
