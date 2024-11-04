<?php
// Database configuration
$host = 'localhost';         // Database host (usually 'localhost' for local development)
$dbname = 'mssp_events'; // Name of your database
$username = 'root';          // Database username (usually 'root' for local development)
$password = '';              // Database password (empty for local development with XAMPP, WAMP, etc.)

// Create a connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the character set to utf8mb4 (to support special characters)
mysqli_set_charset($conn, 'utf8mb4');
?>
