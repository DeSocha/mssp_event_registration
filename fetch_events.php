<?php
// Start session and include config
session_start();
include('includes/config.php');

// Check if search term is provided
$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';

// Build the query to fetch both upcoming and ongoing events
$eventsQuery = "SELECT * FROM events WHERE datetime_end > NOW()";

if (!empty($searchQuery)) {
    // Search both title and description based on the search input
    $eventsQuery .= " AND (title LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%' OR description LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%')";
}

$eventsQuery .= " ORDER BY datetime_start ASC";
$eventsResult = mysqli_query($conn, $eventsQuery);

// Store events data in an array
$events = [];
if (mysqli_num_rows($eventsResult) > 0) {
    while ($event = mysqli_fetch_assoc($eventsResult)) {
        $events[] = $event;
    }
}

// Return the events as JSON
echo json_encode($events);
?>
