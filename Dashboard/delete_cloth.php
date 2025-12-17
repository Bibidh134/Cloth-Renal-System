<?php
session_start();
include('db_connect.php');

// Check if the cloth ID is set
if (isset($_GET['id'])) {
    $cloth_id = $_GET['id'];

    // Fetch the cloth to get its image file path
    $query = "SELECT image FROM clothing_item WHERE cloth_id = $cloth_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = 'uploads/pictures/' . $row['image'];

        // Delete the cloth record from the database
        $delete_query = "DELETE FROM clothing_item WHERE cloth_id = $cloth_id";
        if ($conn->query($delete_query) === TRUE) {
            // Check if the image file exists and delete it
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image file from the server
            }

            // Redirect to the cloth listing page
            header('Location: Clothing_iteams.php');
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Cloth not found!";
    }
} else {
    echo "Invalid cloth ID!";
}
?>
