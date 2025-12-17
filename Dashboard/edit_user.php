<?php
include('db_connect.php');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user info
    $query = "SELECT * FROM users WHERE user_id = $user_id";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
    } else {
        header("Location: users.php");
        exit();
    }
} else {
    header("Location: users.php");
    exit();
}

$message = ""; // To display feedback

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    // Check if the new email is the same as the current one
    if ($new_email === $email) {
        // If the email is the same as the current one, update the user info without error
        $update_query = "UPDATE users SET username = '$new_username', email = '$new_email' WHERE user_id = $user_id";
        if ($conn->query($update_query) === TRUE) {
            header("Location: users.php");
            exit();
        } else {
            $message = "<p style='color: red; text-align: center;'>Error updating record: " . $conn->error . "</p>";
        }
    } else {
        // Check if the new email already exists for another user
        $check_email_query = "SELECT * FROM users WHERE email = '$new_email' AND user_id != $user_id";
        $check_result = $conn->query($check_email_query);

        if ($check_result->num_rows > 0) {
            // If the email is taken by another user, display error
            $message = "<p style='color: red; text-align: center;'>Email already in use by another user.</p>";
        } else {
            // If the email is not taken, proceed with the update
            $update_query = "UPDATE users SET username = '$new_username', email = '$new_email' WHERE user_id = $user_id";
            if ($conn->query($update_query) === TRUE) {
                header("Location: users.php");
                exit();
            } else {
                $message = "<p style='color: red; text-align: center;'>Error updating record: " . $conn->error . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form label {
            display: block;
            margin-bottom: 10px;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Edit Student</h2>
    <?php echo $message; ?>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">

        <input type="submit" value="Update">
    </form>
</body>
</html>
