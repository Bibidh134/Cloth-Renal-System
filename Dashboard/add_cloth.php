<?php
session_start();
include('db_connect.php');

$message = "";

// Fetch category_id and Category_name
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
    if (
        isset($_POST['cloth_name'], $_POST['description'], $_POST['category_id'], $_POST['price']) &&
        isset($_FILES['image'])
    ) {
        $cloth_name = $_POST['cloth_name'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $price = $_POST['price'];

        // Auto-generate cloth_id like C001, C002, etc.
        $idQuery = "SELECT cloth_id FROM clothing_item ORDER BY cloth_id DESC LIMIT 1";
        $idResult = $conn->query($idQuery);
        if ($idResult && $idResult->num_rows > 0) {
            $lastRow = $idResult->fetch_assoc();
            $lastId = intval(substr($lastRow['cloth_id'], 1)); // remove 'C'
            $cloth_id = 'C' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $cloth_id = 'C001'; // first entry
        }

        // Handle image upload
        $uploadDir = realpath(__DIR__ . "/uploads/pictures/") . "/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = basename($_FILES["image"]["name"]);
        $imagePath = "uploads/pictures/" . $imageName;
        $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        $allowedImageTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($imageFileType, $allowedImageTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDir . $imageName)) {
                // ✅ Use prepared statement
                $stmt = $conn->prepare("INSERT INTO clothing_item (cloth_id, cloth_name, description, image, category_id, price) 
                                        VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssd", $cloth_id, $cloth_name, $description, $imagePath, $category_id, $price);

                if ($stmt->execute()) {
                    header("Location: Clothing_iteams.php");
                    exit();
                } else {
                    $message = "<p class='text-danger'>Database error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            } else {
                $message = "<p class='text-danger'>Image upload failed. Error: " . $_FILES["image"]["error"] . "</p>";
            }
        } else {
            $message = "<p class='text-danger'>Invalid image format. Allowed: jpg, jpeg, png, gif.</p>";
        }
    } else {
        $message = "<p class='text-danger'>All fields are required.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Cloth</title>
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
        .btn-danger {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Cloth</h2>
        <?php echo $message; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="cloth_name" class="form-label">Cloth Name</label>
                <input type="text" class="form-control" id="cloth_name" name="cloth_name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>">
                            <?php echo htmlspecialchars($category['Category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price (in Rs.)</label>
                <input type="number" class="form-control" id="price" name="price" required min="0" step="0.01">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Cloth Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-danger">Add Cloth</button>
        </form>
    </div>
</body>
</html>
