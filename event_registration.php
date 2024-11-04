<?php
session_start();
include('includes/config.php');

// Check if event_id is set in the URL
$eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;
$status = isset($_GET['status']) ? $_GET['status'] : null;
$message = isset($_GET['message']) ? $_GET['message'] : null;

if ($eventId) {
    // Fetch event details
    $eventQuery = "SELECT * FROM events WHERE id = '$eventId'";
    $eventResult = mysqli_query($conn, $eventQuery);
    $event = mysqli_fetch_assoc($eventResult);

    // Fetch attendees for the event
    $attendeesQuery = "SELECT * FROM attendees WHERE event_id = '$eventId'";
    $attendeesResult = mysqli_query($conn, $attendeesQuery);

    // Fetch provinces for dropdown
    $provincesQuery = "SELECT id, name FROM province";
    $provincesResult = mysqli_query($conn, $provincesQuery);
    $provinces = [];
    while ($province = mysqli_fetch_assoc($provincesResult)) {
        $provinces[] = $province;
    }
} else {
    echo "Invalid Event ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration and Attendance</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/eventcustom.css">
    <!-- Include Select2 CSS for searchable dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        #signaturePad {
            border: 1px solid #ddd;
            width: 100%;
            height: 200px;
            cursor: crosshair;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">USAID-MSSP ERS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="attendees_events.php">Home</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Display success/error message -->
<div class="container mt-4">
    <?php if ($status === 'success') : ?>
        <div class="alert alert-success" role="alert">
            Successfully Registered!
        </div>
    <?php elseif ($status === 'error') : ?>
        <div class="alert alert-danger" role="alert">
            Error: <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
</div>

<!-- Event Details -->
<div class="container mt-4">
    <div class="text-center mb-4">
        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#registerModal">Register</button>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">Event Title: <?php echo $event['title']; ?></h5>
                    <p class="card-text">Event Venue: <?php echo $event['venue']; ?></p>
                    <p class="card-text">Event Description: <?php echo $event['description']; ?></p>
                </div>
                <div class="col-md-6 text-right">
                    <p class="card-text">Event Start: <?php echo date('M d, Y h:i A', strtotime($event['datetime_start'])); ?></p>
                    <p class="card-text">Event End: <?php echo date('M d, Y h:i A', strtotime($event['datetime_end'])); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Present Attendees Section -->
    <h4>Present Attendees</h4>
    <div class="row">
        <?php if (mysqli_num_rows($attendeesResult) > 0) {
            while ($attendee = mysqli_fetch_assoc($attendeesResult)) { ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $attendee['name']; ?></h5>
                            <p class="card-text"><?php echo $attendee['contact']; ?></p>
                            <p class="card-text"><?php echo date('M d, Y h:i A', strtotime($attendee['date_created'])); ?></p>
                        </div>
                    </div>
                </div>
            <?php }
        } else { ?>
            <div class="col-md-12">
                <p>No attendees found.</p>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Registration Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="registrationForm" method="POST" action="register_attendee.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register for Event: <?php echo $event['title']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Name Fields -->
                    <div class="form-group">
                        <label for="attendeeFirstName">First Name</label>
                        <input type="text" class="form-control" id="attendeeFirstName" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="attendeeMiddleName">Middle Name</label>
                        <input type="text" class="form-control" id="attendeeMiddleName" name="middle_name">
                    </div>
                    <div class="form-group">
                        <label for="attendeeLastName">Last Name</label>
                        <input type="text" class="form-control" id="attendeeLastName" name="last_name" required>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="attendeeEmail">Email</label>
                        <input type="email" class="form-control" id="attendeeEmail" name="email" required>
                    </div>

                    <!-- Contact Field -->
                    <div class="form-group">
                        <label for="attendeeContact">Phone Number</label>
                        <input type="text" class="form-control" id="attendeeContact" name="contact" required>
                    </div>

                    <!-- Organization Dropdown -->
                    <div class="form-group">
                        <label for="attendeeOrganization">Organization</label>
                        <select class="form-control" id="attendeeOrganization" name="organization" required>
                            <option value="" disabled selected>Select your organization</option>
                            <option value="USAID">USAID</option>
                            <option value="INGO">INGO</option>
                            <option value="Local NGO">Local NGO</option>
                            <option value="Contractor">Contractor</option>
                            <option value="USG">USG Agency</option>
                            <option value="DRC Central Government">DRC Central Government</option>
                            <option value="DRC Local Government">DRC Local Government</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Specify Organization Field (shown for certain organizations) -->
                    <div class="form-group" id="specifyOrganizationGroup" style="display: none;">
                        <label for="specifyOrganization">Your Organization Name</label>
                        <input type="text" class="form-control" id="specifyOrganization" name="sector" placeholder="Enter your organization name">
                    </div>

                    <!-- Sector/Activity Field (Conditional) -->
                    <div id="sectorField"></div>

                    <!-- Position/Function Dropdown -->
                    <div class="form-group" id="positionField" style="display: none;">
                        <label for="attendeePosition">Position or Function</label>
                        <select class="form-control" id="attendeePosition" name="position" required>
                            <option value="" disabled selected>Select what matches your current job title or role.</option>
                            <option value="COP">COP</option>
                            <option value="DCOP">DCOP</option>
                            <option value="Program Manager">Program Manager</option>
                            <option value="Activity Manager">Activity Manager</option>
                            <option value="COR">COR</option>
                            <option value="Communications">Communications</option>
                            <option value="Finance">Admin/Finance</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="form-group" id="specifyPositionGroup" style="display: none;">
                        <label for="specifyPosition">Specify Position or role</label>
                        <input type="text" class="form-control" id="specifyPosition" name="specify_position" placeholder="Input your position or role">
                    </div>

                    <!-- Province Dropdown with Select2 -->
                    <div class="form-group">
                        <label for="province">Post Location (select province)</label>
                        <select class="form-control" id="province" name="province" required style="width: 100%;">
                            <option value="" disabled selected>Select your province</option>
                            <?php foreach ($provinces as $province) : ?>
                                <option value="<?php echo $province['id']; ?>"><?php echo htmlspecialchars($province['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Signature Field -->
                    <div class="form-group">
                        <label for="signature">Signature</label>
                        <canvas id="signaturePad" class="border w-100" style="height: 200px;"></canvas>
                        <button type="button" id="clearSignature" class="btn btn-secondary mt-2">Clear Signature</button>
                    </div>

                    <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and other scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include Select2 JS only for the province field -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Initialize Select2 on the province dropdown
    $(document).ready(function() {
        $('#province').select2({
            placeholder: "Type to search your province"
        });
    });

    // Show/hide fields based on organization selection
    document.getElementById('attendeeOrganization').addEventListener('change', function () {
        const selectedOrg = this.value;
        const specifyOrganizationGroup = document.getElementById('specifyOrganizationGroup');
        const sectorField = document.getElementById('sectorField');
        const positionField = document.getElementById('positionField');
        
        // Show specify organization field for non-USAID options
        const requireSpecifyOrganization = ['INGO', 'Local NGO', 'Contractor', 'USG', 'DRC Central Government', 'DRC Local Government', 'Other'];
        specifyOrganizationGroup.style.display = requireSpecifyOrganization.includes(selectedOrg) ? 'block' : 'none';

        // Show position field for any valid organization selection
        positionField.style.display = selectedOrg ? 'block' : 'none';

        // Show different options for sector based on organization selection
        if (selectedOrg === 'USAID') {
            sectorField.innerHTML = `
                <div class="form-group">
                    <label for="sector">Bureau or Office</label>
                    <select class="form-control" id="sector" name="sector" required>
                        <option value="" disabled selected>Please Select Bureau or Office</option>
                        <option value="Front Office">Front Office</option>
                        <option value="Program Office">Program Office</option>
                        <option value="EXO">EXO</option>
                        <option value="OFM">OFM</option>
                        <option value="OAA">OAA</option>
                        <option value="BHA">BHA</option>
                        <option value="CARPE">CARPE</option>
                        <option value="EG">EG</option>
                        <option value="EDU">EDU</option>
                        <option value="PSO">PSO</option>
                        <option value="DRG">DRG</option>
                        <option value="Health">Health</option>
                    </select>
                </div>
            `;
        } else {
            sectorField.innerHTML = `
                <div class="form-group">
                    <label for="activity">Put your activity</label>
                    <input type="text" class="form-control" id="activity" name="activity" placeholder="Specify your activity">
                </div>
            `;
        }
    });

    document.getElementById('attendeePosition').addEventListener('change', function () {
        const specifyPositionGroup = document.getElementById('specifyPositionGroup');
        specifyPositionGroup.style.display = this.value === 'Others' ? 'block' : 'none';
    });
</script>
</body>
</html>
