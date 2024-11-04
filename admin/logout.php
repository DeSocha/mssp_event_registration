<?php
session_start();  // Start the session

// Destroy the session to log out the user
session_destroy();

// Redirect to the admin login page after logout
header("Location: admin_login.php");
exit();  // Ensure no further code is executed
?>
