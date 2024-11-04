<?php
include('../includes/config.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $venue = $_POST['venue'];
    $description = $_POST['description'];
    $datetime_start = $_POST['datetime_start'];
    $datetime_end = $_POST['datetime_end'];

    // Update event data in the database
    $query = "UPDATE events SET title = ?, venue = ?, description = ?, datetime_start = ?, datetime_end = ?, date_update = NOW() WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $title, $venue, $description, $datetime_start, $datetime_end, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Event updated successfully";
    } else {
        echo "No changes made or update failed";
    }
}
?>
