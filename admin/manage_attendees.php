<?php
include('../includes/config.php');

if (isset($_GET['id'])) {
    $audienceId = $_GET['id'];
    $query = "SELECT a.id, a.name, a.email, a.contact, a.organization, a.signature, a.event_id
              FROM attendees a 
              WHERE a.id = $audienceId";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $audience = mysqli_fetch_assoc($result);
        echo json_encode($audience);
    }
}
?>
