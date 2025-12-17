<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Fetch all orders for this user, latest first
$query = $conn->prepare("SELECT * FROM orders WHERE username = ? ORDER BY ordered_at DESC");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders - Cloth Rental</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f6f8; padding: 20px; }
h2 { text-align: center; margin-bottom: 30px; }
table { width: 95%; margin: auto; border-collapse: collapse; background: #fff; }
th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
th { background-color: #38bdf8; color: #fff; }
tr:nth-child(even) { background-color: #f9f9f9; }
tr:hover { background-color: #e0f7ff; }
img { width: 60px; border-radius: 4px; }
.return-home { display: inline-block; margin: 20px auto; padding: 10px 20px; background-color: #38bdf8; color: white; text-decoration: none; border-radius: 5px; }
.return-home:hover { background-color: #1e293b; }
</style>
</head>
<body>

<h2>🛒 My Orders</h2>

<?php if ($result->num_rows > 0): ?>
<table>
<tr>
    <th>Image</th>
    <th>Item</th>
    <th>Price (Rs)</th>
    <th>Quantity</th>
    <th>Rental Start</th>
    <th>Rental End</th>
    <th>Total (Rs)</th>
    <th>Ordered At</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><img src="/ClothRental/Dashboard/<?php echo htmlspecialchars($row['image']); ?>" alt=""></td>
    <td><?php echo htmlspecialchars($row['cloth_name']); ?></td>
    <td><?php echo $row['price']; ?></td>
    <td><?php echo $row['quantity']; ?></td>
    <td><?php echo $row['rental_start']; ?></td>
    <td><?php echo $row['rental_end']; ?></td>
    <td><?php echo $row['total']; ?></td>
    <td><?php echo $row['ordered_at']; ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<p style="text-align:center;">You have no orders yet.</p>
<?php endif; ?>

<div style="text-align:center;">
    <a href="homepage2.php" class="return-home">Return to Homepage</a>
</div>

</body>
</html>
