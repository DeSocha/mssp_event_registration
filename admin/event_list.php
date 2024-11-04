<?php

session_start();  // Start the session at the beginning of your script


include('../includes/sidebar.php');
// Include the database connection
include('../includes/config.php');

// Fetch the event data
$query = "SELECT * FROM events"; 
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List | Admin</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<!-- Main content -->
<div class="main-content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Event List</h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEventModal">+ Add New</button>
        </div>
        
        <table id="eventTable" class="table table-bordered table-hover event-list-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
    // Fetch the events along with the assigned user's name
    $query = "SELECT events.*, users.username AS assigned_user 
              FROM events 
              JOIN users ON events.user_id = users.id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $i = 1;
        $current_time = new DateTime(); // Get the current date and time
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Convert the event start and end times to DateTime objects
            $datetime_start = new DateTime($row['datetime_start']);
            $datetime_end = new DateTime($row['datetime_end']);
            
            // Determine the event status
            if ($current_time > $datetime_end) {
                $status = "Done";
                $badge_class = "badge-success"; // Green background for Done
            } elseif ($current_time >= $datetime_start && $current_time <= $datetime_end) {
                $status = "Ongoing";
                $badge_class = "badge-warning"; // Yellow background for Ongoing
            } else {
                $status = "Upcoming";
                $badge_class = "badge-info"; // Optional: Blue background for upcoming events
            }

            // Render the table row
            echo "<tr>";
            echo "<td>" . $i++ . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>DateTime Start: <span class='unbold'>" . date('M d Y h:i A', strtotime($row['datetime_start'])) . "</span><br>";
            echo "DateTime End: <span class='unbold'>" . date('M d Y h:i A', strtotime($row['datetime_end'])) . "</span><br>";
            echo "Assigned User: <span class='unbold'>" . $row['assigned_user'] . "</span></td>"; // Display assigned user's name
            
            // Output the status with the correct badge class
            echo "<td><span class='badge $badge_class'>$status</span></td>";
            
            // Action buttons (edit, delete)
            echo "<td>
                    <a href='javascript:void(0);' class='btn btn-primary btn-sm editEventBtn' data-id='" . $row['id'] . "'><i class='fas fa-edit'></i></a>
                    <a href='delete_event.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No events found</td></tr>";
    }
?>


</tbody>
        </table>
    </div>
</div>
<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="add_event.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="addEventModalLabel">New Event</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="form-group">
            <label for="venue">Venue</label>
            <select class="form-control" id="venue" name="venue" required>
                <option value="" disabled selected>Select Venue</option>
                <option value="BONOBO">BONOBO</option>
                <option value="OKAPI">OKAPI</option>
                <option value="RUMBA">RUMBA</option>
                <option value="POOL MALEBO">POOL MALEBO</option>
                <option value="GORILLE">GORILLE</option>
                <option value="NYIRAGONGO">NYIRAGONGO</option>
                <option value="FLEUVE CONGO">FLEUVE CONGO</option>
                <option value="CERCEL ELAIS">CERCEL ELAIS</option>
                <option value="SULTANI">SULTANI</option>
                <option value="SULTANI RIVER">SULTANI RIVER</option>
                <option value="HILTON">HILTON</option>
                <option value="BEATRICE H.">BEATRICE H.</option>
            </select>
          </div>
          

<!-- Dropdown for selecting a survey -->
<div class="form-group">
    <label for="surveySelect">Select the Survey Type</label>
    <select class="form-control" id="surveySelect" name="surveySelect">
        <option value="">Select a survey</option>
        <option value="surveyA">Survey A</option>
        <option value="surveyB">Survey B</option>
        <option value="surveyC">Survey C</option>
    </select>
</div>

<!-- Text areas for Google Form links, hidden by default -->
<div class="form-group" id="surveyALink" style="display: none;">
    <label for="surveyone">Survey A Link</label>
    <textarea class="form-control" id="surveyone" name="surveyone" placeholder="Enter Google Form link for Survey A"></textarea>
</div>

<div class="form-group" id="surveyBLink" style="display: none;">
    <label for="surveytwo">Survey B Link</label>
    <textarea class="form-control" id="surveytwo" name="surveytwo" placeholder="Enter Google Form link for Survey B"></textarea>
</div>

<div class="form-group" id="surveyCLink" style="display: none;">
    <label for="surveythree">Survey C Link</label>
    <textarea class="form-control" id="surveythree" name="surveythree" placeholder="Enter Google Form link for Survey C"></textarea>
</div>


<!-- jQuery to handle dropdown change and show the corresponding Google form link text area -->
<script>
   
</script>
          <div class="form-group">
            <label for="datetime_start">DateTime Start</label>
            <input type="datetime-local" class="form-control" id="datetime_start" name="datetime_start" required>
          </div>
          <div class="form-group">
            <label for="datetime_end">DateTime End</label>
            <input type="datetime-local" class="form-control" id="datetime_end" name="datetime_end" required>
          </div>
          <div class="form-group">
            <label for="assign_to">Assign To</label>
            <select class="form-control" id="assign_to" name="assign_to" required>
              <option value="" selected disabled>Select User</option>
              <!-- Populate users here -->
            </select>
          </div>
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <!--<button type="submit" class="btn btn-primary">Save</button>-->

          <button type="submit" class="btn btn-primary" onclick="validateForm(event)">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editEventLabel">Manage Event</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editEventForm">
          <input type="hidden" id="eventId" name="id">
          <div class="form-group">
            <label for="eventTitle">Title</label>
            <input type="text" class="form-control" id="eventTitle" name="title" required>
          </div>
          <div class="form-group">
            <label for="venue">Venue</label>
            <select class="form-control" id="venue" name="venue" required>
                <option value="" disabled selected>Select Venue</option>
                <option value="BONOBO">BONOBO</option>
                <option value="OKAPI">OKAPI</option>
                <option value="RUMBA">RUMBA</option>
                <option value="POOL MALEBO">POOL MALEBO</option>
                <option value="GORILLE">GORILLE</option>
                <option value="NYIRAGONGO">NYIRAGONGO</option>
                <option value="FLEUVE CONGO">FLEUVE CONGO</option>
                <option value="CERCEL ELAIS">CERCEL ELAIS</option>
                <option value="SULTANI">SULTANI</option>
                <option value="SULTANI RIVER">SULTANI RIVER</option>
                <option value="HILTON">HILTON</option>
                <option value="BEATRICE H.">BEATRICE H.</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="eventStart">DateTime Start</label>
            <input type="datetime-local" class="form-control" id="eventStart" name="datetime_start" required>
          </div>
          <div class="form-group">
            <label for="eventEnd">DateTime End</label>
            <input type="datetime-local" class="form-control" id="eventEnd" name="datetime_end" required>
          </div>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
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
        $('#eventTable').DataTable();
    });
    $(document).ready(function() {
        $('#surveySelect').change(function() {
            var selectedSurvey = $(this).val();

            // Hide all Google Form text areas initially
            $('#surveyALink, #surveyBLink, #surveyCLink').hide();

            // Show the correct Google Form link text area based on the selection
            if (selectedSurvey === 'surveyA') {
                $('#surveyALink').show();
            } else if (selectedSurvey === 'surveyB') {
                $('#surveyBLink').show();
            } else if (selectedSurvey === 'surveyC') {
                $('#surveyCLink').show();
            }
        });
    });

    function validateForm(event) {
    // Define a regex pattern for Google Forms URLs
    var googleFormRegex = /^https:\/\/docs\.google\.com\/forms\/d\/e\/.+$/;

    // Get the input values from the textareas
    var surveyone = document.getElementById('surveyone').value;
    var surveytwo = document.getElementById('surveytwo').value;
    var surveytwo = document.getElementById('surveythree').value;

    // Check if both URLs match the Google Form URL pattern
    if (!googleFormRegex.test(surveyone)) {
        alert("Survey Link A is not a valid Google Form URL.");
        event.preventDefault(); // Prevent form submission
        return false;
    }

    if (!googleFormRegex.test(surveytwo)) {
        alert("Survey Link B is not a valid Google Form URL.");
        event.preventDefault(); // Prevent form submission
        return false;
    }

    // If both links are valid, allow the form submission
    return true;
}


$(document).ready(function() {
  // When an edit button is clicked
  $('.editEventBtn').on('click', function() {
    var eventId = $(this).data('id'); // Get event ID from data-id attribute
    
    // Make AJAX request to get event data
    $.ajax({
      url: 'manage_events.php', // Backend PHP script to get the event details
      type: 'POST',
      data: { id: eventId },
      dataType: 'json',
      success: function(response) {
        // Fill the modal fields with the event data
        $('#eventId').val(response.id);
        $('#eventTitle').val(response.title);
        $('#eventVenue').val(response.venue);
        $('#eventDescription').val(response.description);
        $('#eventStart').val(response.datetime_start.replace(' ', 'T'));
        $('#eventEnd').val(response.datetime_end.replace(' ', 'T'));

        // Show the modal
        $('#editEventModal').modal('show');
      },
      error: function(err) {
        console.log("Error fetching event details:", err);
      }
    });
  });

  // Handle the form submission
  $('#editEventForm').on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    // Send AJAX request to update the event details
    $.ajax({
      url: 'edit_event.php', // Backend PHP script to handle the update
      type: 'POST',
      data: $(this).serialize(),
      success: function(response) {
        // Close the modal
        $('#editEventModal').modal('hide');
        
        // Optionally, refresh the page or update the table row
        location.reload();
      },
      error: function(err) {
        console.log("Error updating event:", err);
      }
    });
  });
});

    $(document).ready(function() {
        $('#eventTable').DataTable();
    });


</script>
</body>
</html>
