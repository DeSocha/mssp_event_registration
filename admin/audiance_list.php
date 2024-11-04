<?php
session_start();
// Include database connection
include('../includes/config.php');
require_once('../phpqrcode/qrlib.php'); // Adjust path according to your structure

// Fetch audience data with their respective event
$query = "SELECT a.id, e.title AS event_title, a.name AS attendee_name, a.email, a.contact, a.organization, a.signature
          FROM attendees a 
          JOIN events e ON a.event_id = e.id";

$result = mysqli_query($conn, $query);

// Fetch events for the dropdown in the modal
$eventsQuery = "SELECT id, title FROM events";
$eventsResult = mysqli_query($conn, $eventsQuery);


function generateQRCode($data) {
  // Path to save the QR code images
  $qrCodePath = '../qrcodes/';
  
  // Ensure the directory exists
  if (!is_dir($qrCodePath)) {
      mkdir($qrCodePath, 0777, true); // Create the directory if it doesn't exist
  }
  
  // Generate a unique filename for the QR code
  $fileName = uniqid() . '.png';
  $filePath = $qrCodePath . $fileName;

  // Create QR code with the data provided
  $qrTempPath = $qrCodePath . 'temp_' . $fileName; // Temporary file before adding custom colors
  QRcode::png($data, $qrTempPath, 'L', 5, 2); // Size set to 5, adjust for your needs

  // Load the generated QR code
  $qrImage = imagecreatefrompng($qrTempPath);
  
  // Get dimensions of the original QR code
  $qrWidth = imagesx($qrImage);
  $qrHeight = imagesy($qrImage);

  // Create a new image with white background and the same size as the QR code
  $finalQRImage = imagecreatetruecolor($qrWidth, $qrHeight);
  $white = imagecolorallocate($finalQRImage, 255, 255, 255); // White background
  $blue = imagecolorallocate($finalQRImage, 0, 0, 255); // Blue color for the QR code
  imagefill($finalQRImage, 0, 0, $white); // Fill the background with white

  // Replace black pixels with blue in the QR code
  for ($y = 0; $y < $qrHeight; $y++) {
      for ($x = 0; $x < $qrWidth; $x++) {
          $rgb = imagecolorat($qrImage, $x, $y);
          if ($rgb == 0) { // If the pixel is black (0)
              imagesetpixel($finalQRImage, $x, $y, $blue); // Set it to blue
          }
      }
  }

  // Save the new QR code with white background and blue pattern
  imagepng($finalQRImage, $filePath);

  // Clean up temporary files and memory
  imagedestroy($qrImage);
  imagedestroy($finalQRImage);
  unlink($qrTempPath); // Delete the temporary file

  return $filePath; // Return the path to the generated QR code
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants List | Admin</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- Sidebar include -->
<?php include('../includes/sidebar.php'); ?>

<!-- Main content -->
<div class="main-content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Participants List</h2>
            <a href="#" id="addNewButton" class="btn btn-primary">+ Add New</a>

           
        </div>
        
        <table id="audienceTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Signature</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <?php
    if (mysqli_num_rows($result) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $i++ . "</td>";
            echo "<td>" . $row['event_title'] . "</td>";
            // Display attendee name and add QR code button next to it
        echo "<td>" . $row['attendee_name'] . "
        <button class='btn btn-info btn-sm qr-button' data-toggle='modal' data-target='#qrModal' data-id='" . $row['id'] . "' data-event='" . $row['event_title'] . "' data-name='" . $row['attendee_name'] . "'>
        <i class='fas fa-qrcode'></i></button></td>";
            echo "<td>Email: <span class='unbold'>" . $row['email'] . "</span><br>Contact #: <span class='unbold'>" . $row['contact'] . "</span><br>Organization: <span class='unbold'>" . $row['organization'] . "</span></td>";
            echo "<td>" . $row['signature'] . "</td>";
            echo "<td>
                    <button class='btn btn-primary btn-sm edit-button' data-id='" . $row['id'] . "'><i class='fas fa-edit'></i></button>
                    <a href='delete_audience.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No audience found</td></tr>";
    }
    ?>
            </tbody>
        </table>
    </div>
</div>


<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">QR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <!-- Display the QR code image here -->
                <img id="qrCodeImage" src="" alt="QR Code" style="width: 150px; height: 150px;">
                <hr>
                <!-- Display the event and attendee name -->
                <p><strong>Event</strong><br><span id="eventText"></span></p>
                <p><strong>Name</strong><br><span id="nameText"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">Print</button>
            </div>
        </div>
    </div>
</div>



<!-- Manage Audience Modal -->
<div class="modal fade" id="manageAudienceModal" tabindex="-1" aria-labelledby="manageAudienceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="manageAudienceModalLabel">Manage Participants</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="manageAudienceForm">
        <div class="modal-body">
          <input type="hidden" id="audience_id" name="audience_id">
          <div class="form-group">
            <label for="fullname">Fullname</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="contact">Contact</label>
            <input type="text" class="form-control" id="contact" name="contact" required>
          </div>
          <div class="form-group">
            <label for="organization">Organization</label>
            <input type="text" class="form-control" id="organization" name="organization" required>
          </div>
          
          <div class="form-group">
            <label for="signature">Signature</label>
            <input type="text" class="form-control" id="signature" name="signature" readonly> <!-- Signature field is read-only -->
          </div>
          <div class="form-group">
            <label for="event">Event</label>
            <select class="form-control" id="event" name="event" required>
              <?php
              while($eventRow = mysqli_fetch_assoc($eventsResult)) {
                echo "<option value='" . $eventRow['id'] . "'>" . $eventRow['title'] . "</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Manage Audience Modal -->
<div class="modal fade" id="manageAudienceModal" tabindex="-1" role="dialog" aria-labelledby="manageAudienceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageAudienceModalLabel">Manage Participants</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="manageAudienceForm">
                    <input type="hidden" id="audience_id" name="audience_id">
                    
                    <div class="form-group">
                        <label for="fullname">Fullname</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact</label>
                        <input type="text" class="form-control" id="contact" name="contact" required>
                    </div>

                    <div class="form-group">
                        <label for="organization">Organization</label>
                        <input type="text" class="form-control" id="organization" name="organization">
                    </div>

                    

                    <div class="form-group">
                        <label for="signature">Signature (Uneditable)</label>
                        <input type="text" class="form-control" id="signature" name="signature" readonly>
                    </div>

                    <div class="form-group">
                        <label for="event">Event</label>
                        <select class="form-control" id="event" name="event">
                            <!-- Populate this with event options from the database -->
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
        $('#audienceTable').DataTable();

        // Show modal when edit button is clicked
        $('.edit-button').on('click', function() {
            let audienceId = $(this).data('id');
            // Make an AJAX call to fetch the audience data using the ID and populate the modal fields
            $.ajax({
                url: 'manage_attendees.php',  // Adjust this path to point to your data fetching file
                type: 'GET',
                data: { id: audienceId },
                success: function(response) {
                    const audience = JSON.parse(response);
                    $('#audience_id').val(audience.id);
                    $('#fullname').val(audience.attendee_name);
                    $('#email').val(audience.email);
                    $('#contact').val(audience.contact);
                    $('#organization').val(audience.organization);
                   
                    $('#signature').val(audience.signature); // Make sure the signature field is read-only
                    $('#event').val(audience.event_id); // Set the event based on the fetched data
                    $('#manageAudienceModal').modal('show');
                }
            });
        });
        // Handle the form submission
        $('#manageAudienceForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: 'save_audience.php',  // Adjust this path to point to your saving file
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#manageAudienceModal').modal('hide');
                    location.reload(); // Reload the page after saving
                }
            });
        });
    });

    $(document).ready(function() {
    // Handle the "Add New" button click
    $('#addNewButton').on('click', function() {
        // Clear all fields in the form
        $('#manageAudienceForm')[0].reset();
        $('#manageAudienceModalLabel').text('Add New Participant'); // Change modal title
        $('#audience_id').val('');  // Clear the hidden ID field for new audience

        // Show the modal
        $('#manageAudienceModal').modal('show');
    });

    // Handle the "Edit" button click
    $(document).on('click', '.edit-button', function() {
        let audienceId = $(this).data('id');  // Get the audience ID from the data-id attribute

        // Make an AJAX call to fetch the audience data using the ID and populate the modal fields
        $.ajax({
            url: 'manage_attendees.php',  // The PHP script to fetch data
            type: 'GET',
            data: { id: audienceId },  // Send the audience ID to the PHP script
            success: function(response) {
                const audience = JSON.parse(response);  // Parse the JSON response
                // Populate modal fields with fetched data
                $('#audience_id').val(audience.id);
                $('#fullname').val(audience.attendee_name);
                $('#email').val(audience.email);
                $('#contact').val(audience.contact);
                $('#organization').val(audience.organization);
                
                $('#signature').val(audience.signature); // Make sure signature is read-only
                $('#event').val(audience.event_id); // Set the event based on the fetched data

                $('#manageAudienceModalLabel').text('Edit Participant');  // Change modal title to edit mode
                $('#manageAudienceModal').modal('show'); // Show the modal
            }
        });
    });

    // Handle the form submission via AJAX for both Add and Edit
    $('#manageAudienceForm').on('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting normally
        $.ajax({
            url: 'save_audience.php',  // Path to save or update audience details
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
                $('#manageAudienceModal').modal('hide');  // Hide the modal after success
                location.reload();  // Reload the page to reflect updated data
            }
        });
    });
});

$(document).on('click', '.qr-button', function() {
    let attendeeId = $(this).data('id');
    let eventTitle = $(this).data('event');
    let attendeeName = $(this).data('name');

    // Set the event and name in the modal
    $('#eventText').text(eventTitle);
    $('#nameText').text(attendeeName);

    // Make an AJAX call to generate the QR code for the attendee
    $.ajax({
    url: 'qr_generator.php',
    type: 'POST',
    data: {
        event: event,
        name: name
    },
    success: function(response) {
        var data = JSON.parse(response);
        if (data.status === 'success') {
            // Debug the file path to make sure it's correct
            console.log('QR code file path:', data.filePath);
            
            // Set the image src to the generated QR code file
            $('#qrModal img').attr('src', data.filePath);
            $('#qrModal').modal('show');
        } else {
            alert(data.message); // Show error if something goes wrong
        }
    }
});

});


</script>

</body>
</html>
