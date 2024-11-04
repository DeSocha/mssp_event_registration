<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Event Registration System</title>
    <!-- Link to external CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <img src="../assets/images/logo.png" alt="Event System Logo">
        <h2>Admin Login</h2>
        <?php
        session_start();
        if (isset($_SESSION['login_error'])) {
            echo "<p class='error-message'>" . $_SESSION['login_error'] . "</p>";
            unset($_SESSION['login_error']);
        }
        ?>
        <form action="admin_login_process.php" method="POST">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <a href="../attendees_events.php">Go to Events Page</a>
    </div>
</div>

<!-- Include Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
