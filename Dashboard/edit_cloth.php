<?php
session_start();
include('C:\wamp64\www\ClothRental\server.php');
include('db_connect.php');

$message = "";

// Get cloth ID
if (!isset($_GET['id'])) {
    die("Cloth ID not provided!");
}

$cloth_id = $_GET['id'];

// Fetch existing cloth data
$query = "SELECT * FROM clothing_item WHERE cloth_id = '$cloth_id'";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    $cloth = $result->fetch_assoc();
} else {
    die("Cloth not found!");
}

// Fetch categories
$categories = [];
$categoryQuery = "SELECT category_id, Category_name FROM Category";
$categoryResult = $conn->query($categoryQuery);
if ($categoryResult && $categoryResult->num_rows > 0) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cloth_name = $_POST['cloth_name'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];

    $imagePath = $cloth['image']; // default: old image

    // If a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = realpath(__DIR__ . "/uploads/pictures/") . "/";
        $imageName = basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedImageTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($imageFileType, $allowedImageTypes)) {
            $newImagePath = "uploads/pictures/" . $imageName;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDir . $imageName)) {
                // Delete old image if exists
                $oldImageFullPath = realpath(__DIR__ . "/" . $cloth['image']);
                if (file_exists($oldImageFullPath)) {
                    unlink($oldImageFullPath);
                }
                $imagePath = $newImagePath;
            } else {
                $message = "<p class='text-danger'>Image upload failed.</p>";
            }
        } else {
            $message = "<p class='text-danger'>Invalid image format. Allowed: jpg, jpeg, png, gif.</p>";
        }
    }

    // Update database
    $updateQuery = "UPDATE clothing_item SET 
                        cloth_name = '$cloth_name', 
                        description = '$description', 
                        category_id = $category_id, 
                        price = $price,
                        image = '$imagePath' 
                    WHERE cloth_id = '$cloth_id'";

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: Clothing_iteams.php");
        exit();
    } else {
        $message = "<p class='text-danger'>Database error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Cloth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .text-danger {
            text-align: center;
            margin-bottom: 15px;
        }
        .btn-primary {
            width: 100%;
        }
        img {
            max-width: 150px;
            height: auto;
            display: block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Cloth</h2>
        <?php echo $message; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="cloth_name" class="form-label">Cloth Name</label>
                <input type="text" class="form-control" id="cloth_name" name="cloth_name" value="<?php echo htmlspecialchars($cloth['cloth_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($cloth['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>" <?php echo ($cloth['category_id'] == $category['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['Category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price (in Rs.)</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($cloth['price']); ?>" required min="0" step="0.01">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Cloth Image</label><br>
                <img src="<?php echo $cloth['image']; ?>" alt="Current Image">
                <input type="file" class="form-control" id="image" name="image">
                <small class="form-text text-muted">Leave empty to keep existing image.</small>
            </div>
            <button type="submit" class="btn btn-primary">Update Cloth</button>
        </form>
    </div>
</body>
</html>
