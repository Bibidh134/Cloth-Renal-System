<?php
session_start();
include('db_connect.php');

//  Check if admin session is set
if (!isset($_SESSION['username'])) {
    header('Location: /ClothRental/admin.php');
    exit();
}

$admin_username = $_SESSION['username'];

//  Verify admin credentials
$stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->bind_param("s", $admin_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    session_destroy();
    header('Location: /ClothRental/admin.php');
    exit();
}

// Initialize counters
$nbr_user = $nbr_category = $nbr_clothing = $nbr_order = 0;

//  User count
$user_result = $conn->query("SELECT * FROM users");
if ($user_result) $nbr_user = $user_result->num_rows;

//  Clothing items count
$clothing_result = $conn->query("SELECT * FROM clothing_item");
if ($clothing_result) $nbr_clothing = $clothing_result->num_rows;

$category_result = $conn->query("SELECT * FROM category");
if ($category_result) $nbr_category = $category_result->num_rows;
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS (replace with your actual path) -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-white" id="sidebar-wrapper">
        <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
            <i class="fas fa-user-secret me-2"></i>Cloth
        </div>
        <div class="list-group list-group-flush my-3">
            <a href="dashboard.php" class="list-group-item list-group-item-action bg-transparent second-text active">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="users.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="fas fa-users me-2"></i>Users
            </a>
            <a href="Category.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="fas fa-tags me-2"></i>Category
            </a>
            <a href="Clothing_iteams.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="fas fa-tshirt me-2"></i>Clothing Items
            </a>
            
            <a href="logout.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
                <i class="fas fa-power-off me-2"></i>Logout
            </a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                <h2 class="fs-2 m-0">Dashboard</h2>
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($admin_username); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Dashboard Cards -->
        <div class="container-fluid px-4">
            <div class="row g-3 my-2">
                <div class="col-md-3">
                    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded dashboard-item">
                        <div>
                            <h3><?php echo $nbr_user; ?></h3>
                            <p>Users</p>
                        </div>
                        <i class="fas fa-user-graduate fs-1 text-primary"></i>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded dashboard-item">
                        <div>
                            <h3><?php echo $nbr_clothing; ?></h3>
                            <p>Clothing Items</p>
                        </div>
                        <i class="fas fa-tshirt fs-1 text-success"></i>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded dashboard-item">
                        <div>
                            <h3><?php echo $nbr_category; ?></h3>
                            <p>Categories</p>
                        </div>
                        <i class="fas fa-tags fs-1 text-warning"></i>
                    </div>
                </div>

                
        </div>
        <!-- /Dashboard Cards -->
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById("menu-toggle").addEventListener("click", function () {
        document.getElementById("wrapper").classList.toggle("toggled");
    });
</script>
</body>
</html>
