<?php
session_start();
include('../includes/sidebar.php');
include('../includes/config.php'); // Include database connection

// Fetch all events for the event dropdown
$eventQuery = "SELECT id, title FROM events";
$eventResult = mysqli_query($conn, $eventQuery);

// Fetch selected event details and attendees
$selectedEventId = isset($_POST['event']) ? $_POST['event'] : null;
$eventDetails = null;
$attendeesResult = false; // Initialize as false if no attendees are found

if ($selectedEventId) {
    // Fetch event details
    $eventDetailsQuery = "SELECT * FROM events WHERE id = '$selectedEventId'";
    $eventDetailsResult = mysqli_query($conn, $eventDetailsQuery);
    $eventDetails = mysqli_fetch_assoc($eventDetailsResult);

    // Fetch attendees for the event, using the event's datetime_start instead of the attendees' date_created
    $attendeesQuery = "SELECT a.id, a.name, a.contact, a.email, a.position, a.organization, a.signature, e.datetime_start 
                       FROM attendees a
                       JOIN events e ON a.event_id = e.id
                       WHERE a.event_id = '$selectedEventId'";
    $attendeesResult = mysqli_query($conn, $attendeesQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report | Admin</title>
    <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    
<!-- Custom CSS -->
<link rel="stylesheet" href="../assets/css/style.css">
    <!-- Custom CSS to reduce font size -->
    <style>
        body {
            font-size: 0.85rem; /* Reduces base font size */
        }
        h2, .card-title {
            font-size: 1.25rem; /* Adjust heading size */
        }
        .table th, .table td {
            font-size: 0.85rem; /* Table font size */
        }
        .form-group label, .form-control {
            font-size: 0.85rem; /* Form control and label font size */
        }
        .btn {
            font-size: 0.85rem; /* Button font size */
        }
        p {
            font-size: 0.85rem; /* Paragraph font size */
        }
    </style>
</head>
<body>



<!-- Main content -->
<div class="main-content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Reports</h2>
        </div>
        
        <!-- Event Filter -->
        <form action="generate_reports.php" method="post" class="mb-4">
            <div class="form-group row">
                <label for="event" class="col-sm-2 col-form-label">Event</label>
                <div class="col-sm-6">
                    <select class="form-control" id="event" name="event">
                        <?php while ($row = mysqli_fetch_assoc($eventResult)) { ?>
                            <option value="<?php echo $row['id']; ?>" <?php echo ($selectedEventId == $row['id']) ? 'selected' : ''; ?>>
                                <?php echo $row['title']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <button type="button" class="btn btn-success" onclick="window.print()">Print</button>
                </div>
            </div>
        </form>

        <!-- Event Details Section -->
        <?php if ($eventDetails) { ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <p><strong>Event Title:</strong> <?php echo $eventDetails['title']; ?></p>
                        <p><strong>Event Venue:</strong> <?php echo $eventDetails['venue']; ?></p>
                        <p><strong>Event Description:</strong> <?php echo $eventDetails['description']; ?></p>
                    </div>
                    <!-- Right Column -->
                    <div class="col-md-6">
                        <p><strong>Event Start:</strong> <?php echo date('M d, Y h:i A', strtotime($eventDetails['datetime_start'])); ?></p>
                        <p><strong>Event End:</strong> <?php echo date('M d, Y h:i A', strtotime($eventDetails['datetime_end'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- Attendees List Section -->
        <div class="card">
            <div class="card-header">
                Report
            </div>
            <div class="card-body">
                <table id="attendeesTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date/Time</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Organization</th>
                            <th>Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($attendeesResult && mysqli_num_rows($attendeesResult) > 0) {
                            $i = 1;
                            while ($attendee = mysqli_fetch_assoc($attendeesResult)) { ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date('M d, Y h:i A', strtotime($attendee['datetime_start'])); ?></td>
                                <td><?php echo $attendee['name']; ?></td>
                                <td><?php echo $attendee['contact']; ?></td>
                                <td><?php echo $attendee['email']; ?></td>
                                <td><?php echo $attendee['position']; ?></td>
                                <td><?php echo $attendee['organization']; ?></td>
                                <td><?php echo $attendee['signature']; ?></td>
                            </tr>
                            <?php }
                        } else { ?>
                            <tr><td colspan="8" class="text-center">No Data.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap and jQuery JS libraries -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#attendeesTable').DataTable();
    });
</script>

</body>
</html>
