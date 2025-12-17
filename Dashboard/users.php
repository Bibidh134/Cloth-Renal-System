<?php
include('db_connect.php');

$query = "SELECT * FROM users";
$result = $conn->query($query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../Dashboard/style/user.css"> 
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="d-flex" id="wrapper">
       
        <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i
                    class="fas fa-user-secret me-2"></i>Cloth</div>
            <div class="list-group list-group-flush my-3">
                <a href="dashboard.php" class="list-group-item list-group-item-action bg-transparent second-text active"><i
                        class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                <a href="users.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-users"></i>Users</a>
                <a href="Category.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-exclamation-circle"></i>Category</a>
                <a href="Clothing_iteams.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-female"></i>Clothing_iteams</a>
             
                <a href="\Cloth Rental\homepage.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold"><i
                        class="fas fa-power-off me-2"></i>Logout</a>
            </div>
        </div>

        <div class="main-content">
            <h2>Users List</h2>
            <form action="add_user.php" method="post">
                <button type="submit" class="btn btn-primary">Add users</button>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <a href="#" class="btn btn-primary" onclick="edituser(<?php echo $row['user_id']; ?>)">Edit</a>
                                <a href="#" class="btn btn-danger" onclick="deleteuser(<?php echo $row['user_id']; ?>)">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Function to handle edit action
        function edituser(id) {
            window.location.href = "edit_user.php?id=" + id;
        }

        // Function to handle delete action
        function deleteuser(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "delete.php?id=" + id;
            }
        }
    </script>
</body>
</html>
