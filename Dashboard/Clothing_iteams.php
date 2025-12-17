<?php
session_start();
include('db_connect.php');

// Fetch clothing items with category name using JOIN
$query = "SELECT ci.*, c.Category_name 
          FROM clothing_item ci
          JOIN Category c ON ci.category_id = c.category_id";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="../Dashboard/style/user.css" />
    <link rel="stylesheet" href="style.css">
    <style>
        .main-content h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-white" id="sidebar-wrapper">
        <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
            <i class="fas fa-user-secret me-2"></i>Cloth
        </div>
        <div class="list-group list-group-flush my-3">
            <a href="Dashboard.php" class="list-group-item list-group-item-action bg-transparent second-text active">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="users.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="fas fa-user-graduate"></i>User
            </a>
            <a href="Category.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="fas fa-exclamation-circle"></i>Category
            </a>
            <a href="Clothing_iteams.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="fas fa-book-open"></i>Clothing Items
            </a>
        
            </a>
            <a href="\Cloth Rental\homepage.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
                <i class="fas fa-power-off me-2"></i>Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content p-4">
        <h2>Clothing Item List</h2>
        <a href="add_cloth.php" class="btn btn-primary mb-3">Add Clothing Item</a>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Cloth ID</th>
                    <th>Category Name</th>
                    <th>Cloth Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['cloth_id']) ?></td>
                        <td><?= htmlspecialchars($row['Category_name']) ?></td>
                        <td><?= htmlspecialchars($row['cloth_name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['price']) ?></td>
                        <td>
                            <img src="uploads/pictures/<?= htmlspecialchars(basename($row['image'])) ?>" alt="Cloth Image" style="max-width: 100px;">
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning me-1" onclick="editCategory(<?= htmlspecialchars($row['cloth_id']) ?>)">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteCategory(<?= htmlspecialchars($row['cloth_id']) ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function editCategory(id) {
        window.location.href = "edit_cloth.php?id=" + id;
    }

    function deleteCategory(id) {
        if (confirm("Are you sure you want to delete this item?")) {
            window.location.href = "delete_cloth.php?id=" + id;
        }
    }
</script>
</body>
</html>
