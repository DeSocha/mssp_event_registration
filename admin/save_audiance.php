<?php
// Include database connection
include('../includes/config.php');

// Retrieve the form data
$audience_id = $_POST['audience_id'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$organization = $_POST['organization'];
$remarks = $_POST['remarks'];
$event_id = $_POST['event'];

// If audience_id is empty, it's a new entry; otherwise, we're updating an existing record
if (empty($audience_id)) {
    // Insert new audience
    $query = "INSERT INTO attendees (name, email, contact, organization, remarks, event_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $fullname, $email, $contact, $organization, $remarks, $event_id);
} else {
    // Update existing audience
    $query = "UPDATE attendees SET name = ?, email = ?, contact = ?, organization = ?, remarks = ?, event_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $fullname, $email, $contact, $organization, $remarks, $event_id, $audience_id);
}

$stmt->execute();

// Return success or error message (for debugging)
if ($stmt->affected_rows > 0) {
    echo "Success";
} else {
    echo "Error";
}
?>
