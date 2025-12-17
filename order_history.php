<?php
session_start();
include('db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $conn->real_escape_string($_SESSION['username']);

// Fetch orders for the logged-in user
$query = "SELECT * FROM orders WHERE username = '$username' ORDER BY ordered_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order History</title>
  <style>
    body { 
        font-family: Arial, sans-serif; 
        background: #f9f9f9; 
        margin: 0; 
        padding: 20px; 
    }
    h2 { 
        text-align: center; 
        margin-bottom: 30px;
        color: #333;
    }
    table { 
        width: 95%; 
        margin: auto; 
        border-collapse: collapse; 
        background: #fff; 
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    th, td { 
        padding: 12px; 
        border: 1px solid #ddd; 
        text-align: center; 
        font-size: 14px;
    }
    th {
        background-color: #38bdf8;
        color: white;
    }
    table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    table tr:hover {
        background-color: #e0f7ff;
    }
    img { 
        width: 60px; 
        height: auto;
        border-radius: 4px;
    }
    .return-home {
        display: block;
        width: 220px;
        margin: 30px auto;
        text-align: center;
        padding: 12px 20px;
        background-color: #38bdf8;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        text-decoration: none;
        transition: 0.3s;
    }
    .return-home:hover {
        background-color: #1e293b;
    }
    p.no-orders {
        text-align: center;
        font-size: 16px;
        margin-top: 30px;
        color: #555;
    }
  </style>
</head>
<body>
<h2>Your Order History</h2>

<?php if ($result && $result->num_rows > 0): ?>
  <table>
    <tr>
      <th>Image</th>
      <th>Item</th>
      <th>Price (Rs)</th>
      <th>Quantity</th>
      <th>Total (Rs)</th>
      <th>Rental Start</th>
      <th>Rental End</th>
      <th>Ordered Date</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td>
          <?php if (!empty($row['image'])): ?>
            <img src="Dashboard/<?php echo htmlspecialchars($row['image']); ?>" alt="Item Image">
          <?php else: ?>
            -
          <?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($row['cloth_name']); ?></td>
        <td><?php echo number_format($row['price']); ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><?php echo number_format($row['total']); ?></td>
        <td><?php echo htmlspecialchars($row['rental_start'] ?? '-'); ?></td>
        <td><?php echo htmlspecialchars($row['rental_end'] ?? '-'); ?></td>
        <td><?php echo htmlspecialchars($row['ordered_at']); ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
<?php else: ?>
  <p class="no-orders">You have no past orders.</p>
<?php endif; ?>

<a href="homepage2.php" class="return-home">Return to Homepage</a>
</body>
</html>
