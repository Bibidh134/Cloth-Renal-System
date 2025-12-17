<?php
include('db_connect.php');

// Check if the ID parameter is set in the URL
if(isset($_GET['id'])) {
    // Sanitize and store the user ID
    $user_id = mysqli_real_escape_string($conn, $_GET['id']);

    // SQL to delete user record
    $query = "DELETE FROM users WHERE user_id = '$user_id'";

    // Execute the query
    if(mysqli_query($conn, $query)) {
        // If deletion is successful, redirect back to user list page
        header("Location: users.php");
        exit();
    } else {
        // If deletion fails, display an error message
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // If no ID parameter is provided, display an error message
    echo "No user ID provided.";
}
?>
