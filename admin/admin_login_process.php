<?php
session_start();
include('../includes/config.php'); // Include the database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query to check if the username exists and belongs to an admin
    $sql = "SELECT * FROM users WHERE username = '$username' AND role = 'admin' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // If password matches, set session variables and redirect to the admin dashboard
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header('Location: admin_dashboard.php');
        exit();
    } else {
        // If credentials are incorrect, redirect back to the login page with an error
        $_SESSION['login_error'] = "Invalid username or password!";
        header('Location: admin_login.php');
        exit();
    }
}
?>
