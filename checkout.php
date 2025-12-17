<?php 
session_start();
include('db_connect.php');

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<p style='text-align:center; font-size:20px;'>Your cart is empty. <a href='homepage.php'>Shop now</a></p>";
    exit();
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body { font-family: Arial; background: #f4f6f8; padding: 20px; }
        .checkout-box { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        .total { text-align: right; font-weight: bold; }
        .btn { background: #38bdf8; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #1e293b; }
    </style>
</head>
<body>

<div class="checkout-box">
    <h2>Checkout</h2>

    <div class="user-info">
        <strong>User:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?>
    </div>

    <table>
        <tr>
            <th>Item</th>
            <th>Price (Rs)</th>
            <th>Quantity</th>
            <th>Rental Start</th>
            <th>Rental End</th>
            <th>Subtotal (Rs)</th>
        </tr>
        <?php foreach ($cart as $item): ?>
            <?php $subtotal = $item['price'] * $item['quantity']; ?>
            <tr>
                <td><?php echo htmlspecialchars($item['cloth_name']); ?></td>
                <td><?php echo $item['price']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo htmlspecialchars($item['rental_start'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($item['rental_end'] ?? '-'); ?></td>
                <td><?php echo $subtotal; ?></td>
            </tr>
            <?php $total += $subtotal; ?>
        <?php endforeach; ?>
        <tr>
            <td colspan="5" class="total">Total</td>
            <td><strong>Rs <?php echo $total; ?></strong></td>
        </tr>
    </table>

    <form action="stripe_payment.php" method="POST">
        <input type="hidden" name="amount" value="<?php echo $total * 100; ?>"> <!-- Stripe expects paisa/cents -->
        <button type="submit" class="btn">Pay with Stripe</button>
    </form>
</div>

</body>
</html>
