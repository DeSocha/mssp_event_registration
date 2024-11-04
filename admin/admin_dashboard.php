<?php
session_start();
include('../includes/sidebar.php');
include('../includes/config.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Fetch data from the database
$eventCountQuery = "SELECT COUNT(*) as total_events FROM events";
$audienceCountQuery = "SELECT COUNT(*) as total_audience FROM attendees"; // Adjust the table name to your audience table
$finishedEventsQuery = "SELECT COUNT(*) as finished_events FROM events WHERE datetime_end < NOW()";
$ongoingEventsQuery = "SELECT COUNT(*) as ongoing_events FROM events WHERE datetime_start <= NOW() AND datetime_end >= NOW()";

$eventCountResult = mysqli_query($conn, $eventCountQuery);
$audienceCountResult = mysqli_query($conn, $audienceCountQuery);
$finishedEventsResult = mysqli_query($conn, $finishedEventsQuery);
$ongoingEventsResult = mysqli_query($conn, $ongoingEventsQuery);

// Fetch counts
$eventCount = mysqli_fetch_assoc($eventCountResult)['total_events'];
$audienceCount = mysqli_fetch_assoc($audienceCountResult)['total_audience'];
$finishedEvents = mysqli_fetch_assoc($finishedEventsResult)['finished_events'];
$ongoingEvents = mysqli_fetch_assoc($ongoingEventsResult)['ongoing_events'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Event Registration System</title>
    <!-- Link to external CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<!-- Main Content -->
<div class="main-content">

    <div class="top-bar">
        <h2>Welcome to MSSP-SoCha ERS</h2>
    </div>
    
    <div class="content">
       
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-calendar-alt"></i>
                    <h4>Events</h4>
                    <p><?php echo $eventCount; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-users"></i>
                    <h4>Listed Audience</h4>
                    <p><?php echo $audienceCount; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-check"></i>
                    <h4>Finished Events</h4>
                    <p><?php echo $finishedEvents; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-clock"></i>
                    <h4>On-Going Events</h4>
                    <p><?php echo $ongoingEvents; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>Copyright © 2024. All rights reserved.</p>
        <p>MSSP-ERS (by: USAID-MSSP) v1.0</p>
    </footer>
</div>

<!-- Include Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

</body>
</html>
