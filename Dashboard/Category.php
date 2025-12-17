<?php
session_start();
include('db_connect.php');

// Fetch categories from database
$query = "SELECT * FROM category";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Category</title>
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
             
                
                <a href="\Cloth Rental\homepage.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
                    <i class="fas fa-power-off me-2"></i>Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content p-4 container-fluid">
            <h2>Category List</h2>

            <!-- Success message -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <a href="add_category.php" class="btn btn-primary mb-3">Add Category</a>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['category_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td>
                                <img src="uploads/pictures/<?php echo htmlspecialchars(basename($row['image'])); ?>" alt="Category Image" style="max-width: 100px;">
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" onclick="editCategory(<?php echo $row['category_id']; ?>)">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteCategory(<?php echo $row['category_id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JS Scripts -->
    <script>
        function editCategory(categoryId) {
            window.location.href = "edit_category.php?id=" + categoryId;
        }

        function deleteCategory(categoryId) {
            if (confirm("Are you sure you want to delete this category?")) {
                window.location.href = "delete_category.php?id=" + categoryId;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
