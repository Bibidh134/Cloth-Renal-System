<?php
session_start();
include('db_connect.php');

// Check if the category ID is set
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Fetch category details
    $query = "SELECT * FROM category WHERE category_id = $category_id";
    $result = $conn->query($query);
    $note = $result->fetch_assoc();
} else {
    header('Location: category.php');
    exit();
}

// Update category
if (isset($_POST['update_category'])) {
    $category_name = $_POST['category_name'];
    $description = $_POST['description'];

    // Handle image upload
    if (isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
        $image = $_FILES['picture']['name'];
        $target_dir = "uploads/pictures/";
        $target_file = $target_dir . basename($image);

        if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_file)) {
            $image_path = $image;
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        $image_path = $note['image']; // Keep existing image
    }

    // Update query
    $update_query = "UPDATE category 
                     SET category_name = '$category_name', 
                         description = '$description', 
                         image = '$image_path' 
                     WHERE category_id = $category_id";

    if ($conn->query($update_query) === TRUE) {
        $_SESSION['success'] = "Category updated successfully!";
        header('Location: category.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Category</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="category_name" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="category_name" name="category_name"
                   value="<?php echo htmlspecialchars($note['category_name']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"
                      required><?php echo htmlspecialchars($note['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="picture" class="form-label">Image</label>
            <input type="file" class="form-control" id="picture" name="picture">
            <?php if (!empty($note['image'])): ?>
                <img src="uploads/pictures/<?php echo htmlspecialchars($note['image']); ?>"
                     alt="Current Image" style="max-width: 100px; margin-top: 10px;">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary" name="update_category">Update Category</button>
    </form>
</div>
</body>
</html>
