<?php
session_start();

$errors = array(); 
$db = mysqli_connect('localhost', 'root', '', 'clothrental');

// Registration
if (isset($_POST['reg'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($username)) array_push($errors, "Username is required");
    if (empty($email)) array_push($errors, "Email is required");
    if (empty($password)) array_push($errors, "Password is required");

    $check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($db, $check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) array_push($errors, "Email already exists");

    if (count($errors) == 0) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        mysqli_query($db, $query);

        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: homepage2.php');
        exit();
    }
}

// Login
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($email)) array_push($errors, "Email is required");
    if (empty($password)) array_push($errors, "Password is required");

    if (count($errors) == 0) {
        $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $results = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($results);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $user['username'];
            $_SESSION['success'] = "You are now logged in";
            header('location: homepage2.php');
            exit();
        } else {
            array_push($errors, "Wrong email/password combination");
        }
    }
}
?>
