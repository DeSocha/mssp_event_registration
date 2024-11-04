<?php
session_start();
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data securely
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middleName = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $organization = mysqli_real_escape_string($conn, $_POST['organization']);
    $provinceId = mysqli_real_escape_string($conn, $_POST['province']); // This is the province ID
    $signature = isset($_POST['signature']) ? mysqli_real_escape_string($conn, $_POST['signature']) : null; // Signature can be null
    $eventId = mysqli_real_escape_string($conn, $_POST['event_id']); // Event ID passed from the form

    // Determine values for `sector` and `activity` based on organization selection
    if ($organization === 'USAID') {
        // For USAID, `sector` is taken from the Bureau or Office dropdown, and `activity` is set to "Donor"
        $sector = mysqli_real_escape_string($conn, $_POST['sector']); // Bureau or Office for USAID
        $activity = 'Donor';
    } else {
        // For other organizations, `organization` holds the selected organization, `sector` holds the user-entered organization name, and `activity` holds the entered activity
        $sector = mysqli_real_escape_string($conn, $_POST['sector']); // User-specified organization name for other types
        $activity = mysqli_real_escape_string($conn, $_POST['activity']); // Activity input by the user
    }

    // Construct full name for legacy purposes
    $fullName = trim($firstName . ' ' . $middleName . ' ' . $lastName);

    // Verify that event_id exists in the events table (optional validation step)
    $eventCheckQuery = "SELECT id FROM events WHERE id = '$eventId'";
    $eventCheckResult = mysqli_query($conn, $eventCheckQuery);

    if (mysqli_num_rows($eventCheckResult) > 0) {
        // Insert the attendee into the database
        $insertQuery = "INSERT INTO attendees (
                            event_id, first_name, middle_name, last_name, name, email, contact, position, organization, 
                            sector, activity, province_id, signature, date_created
                        ) VALUES (
                            '$eventId', '$firstName', '$middleName', '$lastName', '$fullName', '$email', 
                            '$contact', '$position', '$organization', '$sector', '$activity', '$provinceId', '$signature', NOW()
                        )";

        if (mysqli_query($conn, $insertQuery)) {
            // Redirect to event_registration.php with success message
            header("Location: event_registration.php?event_id=$eventId&status=success");
            exit();
        } else {
            // Redirect to event_registration.php with error message
            header("Location: event_registration.php?event_id=$eventId&status=error&message=" . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        // Redirect to event_registration.php with error message if event ID is invalid
        header("Location: event_registration.php?event_id=$eventId&status=error&message=Invalid+Event+ID");
        exit();
    }
} else {
    // Redirect to event_registration.php with error message for invalid request method
    header("Location: event_registration.php?event_id=$eventId&status=error&message=Invalid+Request+Method");
    exit();
}
