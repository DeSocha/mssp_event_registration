<?php
include('../includes/config.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch event data from the database
    $query = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    // Return event data as JSON
    echo json_encode($event);
}
?>
