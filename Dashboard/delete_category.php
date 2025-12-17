<?php
session_start();
include('db_connect.php');

// Check if the category ID is set
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Fetch the category to get its image file path
    $query = "SELECT image FROM category WHERE category_id = $category_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = 'uploads/pictures/' . $row['image'];

        // Delete the category record from the database
        $delete_query = "DELETE FROM category WHERE category_id = $category_id";
        if ($conn->query($delete_query) === TRUE) {
            // Check if the image file exists and delete it
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image file from the server
            }

            // Redirect to the Category page
            header('Location: Category.php');
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Category not found!";
    }
} else {
    echo "Invalid category ID!";
}
?>
