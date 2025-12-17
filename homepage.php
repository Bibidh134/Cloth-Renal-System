<?php
session_start();
include('db_connect.php');

//  Redirect admin to admin dashboard
if (isset($_SESSION['admin_username'])) {
    header('Location: /ClothRental/Dashboard/dashboard.php');
    exit();
}

//  Get user session if available
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$user_display_name = null; // Default value for user name

// Validate user session against the users table
if ($username) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows === 1) {
        $user = $userResult->fetch_assoc();
        $user_display_name = $user['fullname'] ?? $user['username']; // Show full name if available
    } else {
        session_unset();
        session_destroy();
        $username = null;
    }
    $stmt->close();
}

//  Get search term
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

//  Fetch categories
if (!empty($search)) {
    $query = "SELECT * FROM category WHERE category_name LIKE '%$search%' OR description LIKE '%$search%'";
} else {
    $query = "SELECT * FROM category";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cloth Rental - Homepage</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/> 
   <link rel="stylesheet" href="style/styleee.css">
  <style>
  .hero {
    text-align: center;
    padding: 60px 20px;
    background:  linear-gradient(to right, #667eea, #764ba2);
    color: white;
}
.hero h1 {
    font-size: 36px;
    margin-bottom: 10px;
}
.hero p {
    font-size: 18px;
    margin-bottom: 20px;
}
  </style>
</head>
<body>

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
</header>

<div class="top-bar">
  <img src="logo.png" alt="Logo" class="logo" />
  
  <?php if ($user_display_name): ?>
    <div class="welcome-message">Welcome, <?php echo htmlspecialchars($user_display_name); ?>! <a href="logout.php">(Logout)</a></div>
  <?php endif; ?>
  
  <div class="auth-cart">
    <a href="admin.php"><i class="fas fa-sign-in-alt"></i> Admin</a>
    <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
    <a href="cart_view.php"><i class="fas fa-shopping-cart"></i> Cart</a>
    <a href="order_history.php"><i class="fa fa-history"></i> History</a>
  </div>
  
  <div class="search-bar">
    <form method="GET" action="homepage.php">
      <input type="text" name="search" placeholder="Search Category" value="<?php echo htmlspecialchars($search); ?>" />
      <button type="submit">Search</button>
    </form>
  </div>
</div>

<section class="hero">
  <h1>Welcome to Cloth Rental</h1>
  <p>Rent Online for Latest Fashion</p>
</section>

<section id="category" class="category-section">
  <h2 class="category-title">Browse by Category</h2>
  <div class="category-container">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php $categoryLink = strtolower(str_replace(' ', '', $row['category_name'])) . '.php'; ?>
        <a href="<?php echo $categoryLink; ?>" class="category-card-link">
          <div class="category-card">
            <img src="Dashboard/uploads/pictures/<?php echo htmlspecialchars($row['image']) . '?v=' . time(); ?>" alt="Category Image" />
            <h3><?php echo htmlspecialchars($row['category_name']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
          </div>
        </a>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align: center;">No categories found matching "<?php echo htmlspecialchars($search); ?>"</p>
    <?php endif; ?>
  </div>
</section>

</body>
</html>
