<?php
include('db_connect.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate  form data
    $new_username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
    $new_email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $new_password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';

    // Check if required fields are not empty
    if (!empty($new_username) && !empty($new_email) && !empty($new_password)) {
        // Check if email already exists in the database
        $check_query = "SELECT * FROM users WHERE email = '$new_email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // If email already exists, display an error message
            echo "Error adding student: Email already exists.";
        } else {
            // SQL to insert new student using prepared statement
            $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            
            // Prepare and bind parameters
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $new_username, $new_email, $new_password);

            // Execute the statement
            if ($stmt->execute()) {
                // If insertion is successful, redirect back to student list page
                header("Location: users.php");
                exit();
            } else {
                // If insertion fails, display an error message
                echo "Error adding student: " . $conn->error;
            }

            // Close statement
            $stmt->close();
        }
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"]
         {
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Users</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="email">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Add User">
        </form>
    </div>
</body>
</html>
