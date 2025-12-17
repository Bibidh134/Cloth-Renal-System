<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Handle delete
if (isset($_GET['delete'])) {
    $itemIndex = $_GET['delete'];
    unset($cart[$itemIndex]);
    $_SESSION['cart'] = array_values($cart);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle save dates or checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_dates']) || isset($_POST['save_and_checkout'])) {
        foreach ($_POST['rental_start'] as $index => $start_date) {
            $end_date = $_POST['rental_end'][$index];
            $_SESSION['cart'][$index]['rental_start'] = $start_date;
            $_SESSION['cart'][$index]['rental_end'] = $end_date;
        }

        if (isset($_POST['save_and_checkout'])) {
            if (isset($_SESSION['username'])) {
                header("Location: checkout.php");
            } else {
                header("Location: login_to_checkout.php");
            }
            exit;
        } else {
            header("Location: cart_view.php");
            exit;
        }
    }
}

$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Cart</title>
<style>
body {
   font-family: Arial;
    background: #f4f4f4;
     padding: 20px; 
    }
h2 {
   text-align: center;
    margin-bottom: 30px;
   }
table {
   width: 95%;
    margin: auto; 
    border-collapse: collapse;
     background: #fff; 
    }
th, td {
   padding: 10px;
    border: 1px solid #ccc; 
    text-align: center;
   }
th {
   background: #38bdf8; 
   color: white;
   }
tr:nth-child(even) {
   background: #f9f9f9;
   }
img { width: 60px;
   border-radius: 4px;
   }
.buttons {
   text-align: center;
    margin-top: 20px;
   }
.buttons button, .return-home { 
  padding: 10px 20px;
   margin: 5px;
  background: #38bdf8;
    color: white; 
   border: none;
      border-radius: 4px;
       cursor: pointer;
        text-decoration: none;
       }
.buttons button:hover, .return-home:hover { 
  background-color: #1e293b;
 }
.delete-btn {
   color: red;
    text-decoration: none; 
    cursor: pointer;
   }
.error { color: red;
   font-size: 12px; 
   margin-top: 5px; 
  }
.no-cart { 
  text-align: center; 
  font-size: 16px; 
  margin-top: 30px;
   }
</style>
</head>
<body>

<h2>🛒 Your Cart</h2>

<?php if (empty($cart)): ?>
<p class="no-cart">Your cart is empty.</p>
<div class="buttons">
    <a href="homepage.php" class="return-home">Return to Homepage</a>
</div>
<?php else: ?>
<form method="POST" action="cart_view.php" id="cartForm">
<table>
<tr>
<th>Image</th>
<th>Item</th>
<th>Price (Rs)</th>
<th>Quantity</th>
<th>Rental Start</th>
<th>Rental End</th>
<th>Subtotal (Rs)</th>
<th>Action</th>
</tr>

<?php foreach ($cart as $index => $item): ?>
<?php $subtotal = $item['price'] * $item['quantity']; ?>
<tr>
<td><img src="/ClothRental/Dashboard/<?php echo htmlspecialchars($item['image']); ?>" alt=""></td>
<td><?php echo htmlspecialchars($item['cloth_name']); ?></td>
<td><?php echo $item['price']; ?></td>
<td><?php echo $item['quantity']; ?></td>
<td>
<input type="date" name="rental_start[<?php echo $index; ?>]" value="<?php echo $item['rental_start'] ?? ''; ?>" required>
<div id="error-<?php echo $index; ?>" class="error"></div>
</td>
<td>
<input type="date" name="rental_end[<?php echo $index; ?>]" value="<?php echo $item['rental_end'] ?? ''; ?>" required>
</td>
<td><?php echo $subtotal; ?></td>
<td><a href="?delete=<?php echo $index; ?>" class="delete-btn">Delete</a></td>
</tr>
<?php $total += $subtotal; ?>
<?php endforeach; ?>

<tr>
<td colspan="6" style="text-align:right;"><strong>Total</strong></td>
<td colspan="2"><strong>Rs <?php echo $total; ?></strong></td>
</tr>
</table>

<div class="buttons">
<a href="homepage.php" class="return-home">Return to Homepage</a>
<button type="submit" name="save_and_checkout">Checkout</button>
</div>
</form>

<script>
document.getElementById("cartForm").addEventListener("submit", function(e) {
    let valid = true;

    <?php foreach ($cart as $index => $item): ?>
        const start<?php echo $index; ?> = document.querySelector('input[name="rental_start[<?php echo $index; ?>]"]');
        const end<?php echo $index; ?> = document.querySelector('input[name="rental_end[<?php echo $index; ?>]"]');
        const errorDiv<?php echo $index; ?> = document.getElementById('error-<?php echo $index; ?>');

        errorDiv<?php echo $index; ?>.textContent = '';

        const startDate = new Date(start<?php echo $index; ?>.value);
        const endDate = new Date(end<?php echo $index; ?>.value);

        if (!start<?php echo $index; ?>.value || !end<?php echo $index; ?>.value) {
            errorDiv<?php echo $index; ?>.textContent = "Both start and end dates are required.";
            valid = false;
        } else if (endDate <= startDate) {
            errorDiv<?php echo $index; ?>.textContent = "Rental end date must be later than start date.";
            valid = false;
        }
    <?php endforeach; ?>

    if (!valid) e.preventDefault();
});
</script>

<?php endif; ?>

</body>
</html>
