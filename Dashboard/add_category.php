<?php
session_start();

include('db_connect.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Category_name'], $_POST['description']) && isset($_FILES['picture'])) {
        $CategoryName = $_POST['Category_name'];
        $description = $_POST['description'];

        $uploadDirPictures = realpath(__DIR__ . "/uploads/pictures/") . "/";

        if (!is_dir($uploadDirPictures)) {
            mkdir($uploadDirPictures, 0777, true);
        }

        $pictureName = basename($_FILES["picture"]["name"]);
        $picturePath = "uploads/pictures/" . $pictureName;
        $pictureFileType = strtolower(pathinfo($picturePath, PATHINFO_EXTENSION));

        $allowedPictureTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($pictureFileType, $allowedPictureTypes)) {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $uploadDirPictures . $pictureName)) {
                $query = "INSERT INTO Category (Category_name, Description, Image) 
                          VALUES ('$CategoryName', '$description', '$picturePath')";
                if ($conn->query($query) === TRUE) {
                    header("Location: Category.php");
                    exit();
                } else {
                    $message = "<p class='text-danger'>Database error: " . $conn->error . "</p>";
                }
            } else {
                $message = "<p class='text-danger'>Image upload failed. Error: " . $_FILES["picture"]["error"] . "</p>";
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
    <meta charset="UTF-8" />
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Category</h2>
        <?php echo $message; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="Category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="Category_name" name="Category_name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="picture" class="form-label">Image</label>
                <input type="file" class="form-control" id="picture" name="picture" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
    </div>
</body>
</html>
