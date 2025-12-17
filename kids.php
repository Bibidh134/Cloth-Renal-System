<?php
session_start();
include('db_connect.php');

// Add to Cart Handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $cloth_id = $_POST['cloth_id'];
    $cloth_name = $_POST['cloth_name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$cloth_id])) {
        $_SESSION['cart'][$cloth_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$cloth_id] = [
            'cloth_id' => $cloth_id,
            'cloth_name' => $cloth_name,
            'price' => $price,
            'image' => $image,
            'quantity' => $quantity
        ];
    }

    $_SESSION['message'] = "Added to cart: $cloth_name";
    header("Location: kids.php");
    exit();
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT ci.*, c.category_name 
          FROM clothing_item ci
          JOIN category c ON ci.category_id = c.category_id
          WHERE c.category_name = 'Baby'";

if (!empty($search)) {
    $query .= " AND ci.cloth_name LIKE '%$search%'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Baby's Collection - Cloth Rental</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
    }

    /* Navigation */
    .navbar {
      background-color: #1b2735;
      padding: 15px 0;
    }

    .nav-links {
      display: flex;
      justify-content: center;
      list-style: none;
      gap: 30px;
    }

    .nav-links li a {
      color: #fff;
      text-decoration: none;
      font-size: 18px;
      font-weight: bold;
    }

    .nav-links li a:hover {
      color: #00aced;
    }

    /* Search & Cart Section */
    .search-bar-wrapper {
      display: flex;
      align-items: center;
      padding: 35px 40px;
      background-color: #fff;
      gap: 10px;
      justify-content: center;
    }

    .search-form {
      display: flex;
      align-items: center;
      gap: 5px;
      margin-right: 15px;
    }

    .search-form input[type="text"] {
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 250px;
    }

    .search-form button {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
    }

    .search-form button:hover {
      background-color: #218838;
    }

    .search-cart-container a {
      color: #1b2735;
      text-decoration: none;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 16px;
    }

    .search-cart-container a:hover {
      color: #007bff;
    }

    .hero1 {
      background: linear-gradient(to right, #667eea, #764ba2);
      padding: 30px;
      color:white;
      text-align: center;
    }

    .category-section {
      padding: 20px 40px;
    }

    .category-container {
      display: grid;
      grid-template-columns: repeat(3,1fr);
      gap: 20px;
    }

    .category-card {
      background-color: #fff;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
    }

    .category-card img {
      width: 100%;
      height: 200px;
      object-fit: contain; /* Adjust image to fit without cropping */
      border-radius: 8px;
      background-color: #f0f0f0; /* optional for transparent images */
      display: block;
    }

    .category-card h3 {
      margin: 10px 0 5px;
      font-size: 18px;
    }

    .category-card p {
      margin: 5px 0;
    }

    input[type="number"] {
      width: 50px;
      margin-top: 5px;
      padding: 3px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .add-to-cart-btn {
      background-color: #e63946;
      color: #fff;
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 8px;
      font-size: 14px;
    }

    .add-to-cart-btn:hover {
      background-color: #d62828;
    }

    .success-message {
      background-color: #d4edda;
      color: #155724;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
      margin: 10px auto;
      max-width: 600px;
    }
  </style>
</head>
<body>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="success-message">
      <?php 
        echo $_SESSION['message']; 
        unset($_SESSION['message']); 
      ?>
    </div>
  <?php endif; ?>

  <!-- Navigation -->
  <header>
    <nav class="navbar">
      <ul class="nav-links">
        <li><a href="homepage.php">Home</a></li>
        <li><a href="men.php">Men</a></li>
        <li><a href="women.php">Women</a></li>
        <li><a href="kids.php">Kids</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>

    <div class="search-bar-wrapper">
      <form action="kids.php" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search Baby's Clothing" value="<?php echo htmlspecialchars($search); ?>" />
        <button type="submit">Search</button>
      </form>
      <div class="search-cart-container">
        <a href="cart_view.php">
          <i class="fas fa-shopping-cart"></i> Cart
        </a>
      </div>
    </div>
  </header>

  <section class="hero1">
    <h1>Baby Fashion Collection</h1>
  </section>

  <section class="category-section">
    <div class="category-container">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="category-card">
            <img src="/ClothRental/Dashboard/<?php echo htmlspecialchars($row['image']); ?>" alt="Clothing Image">
            <h3><?php echo htmlspecialchars($row['cloth_name']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p><strong>Rs <?php echo htmlspecialchars($row['price']); ?></strong></p>

            <form action="kids.php" method="POST">
              <input type="hidden" name="add_to_cart" value="1">
              <input type="hidden" name="cloth_id" value="<?php echo $row['cloth_id']; ?>">
              <input type="hidden" name="cloth_name" value="<?php echo htmlspecialchars($row['cloth_name']); ?>">
              <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
              <input type="hidden" name="image" value="<?php echo htmlspecialchars($row['image']); ?>">
              <label for="qty_<?php echo $row['cloth_id']; ?>">Qty:</label>
              <input type="number" name="quantity" value="1" min="1" id="qty_<?php echo $row['cloth_id']; ?>">
              <button type="submit" class="add-to-cart-btn">Add to Cart</button>
            </form>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No Baby's clothing items available.</p>
      <?php endif; ?>
    </div>
  </section>

</body>
</html>
