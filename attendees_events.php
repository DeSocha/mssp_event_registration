<?php
// Start session and include config
session_start();
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Events</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/custom.css"><!-- Custom CSS -->
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">USAID-MSSP Events Portal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin/admin_login.php">Admin Portal</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Search Event -->
<div class="container mt-4">
    <div class="row justify-content-center mb-3">
        <div class="col-md-8 text-center">
            <h4>Upcoming and Ongoing Events</h4>
        </div>
    </div>
    <div class="row justify-content-center mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Search Event" id="searchEvent">
        </div>
    </div>
</div>

<!-- Display Events -->
<div class="container">
    <div class="row" id="eventsContainer">
        <!-- Event cards will be inserted here dynamically -->
    </div>
</div>

<!-- Footer -->
<footer class="text-center mt-4">
    <p>&copy; 2024. All rights reserved.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Function to render event cards
    function renderEvents(events) {
    var eventsContainer = $('#eventsContainer');
    eventsContainer.empty(); // Clear existing events

    if (events.length === 0) {
        eventsContainer.append('<div class="col-md-12"><p>No events found.</p></div>');
    } else {
        events.forEach(function(event) {
            var currentDateTime = new Date().toISOString().slice(0, 19).replace('T', ' ');

            // Determine the event status
            var statusBadge;
            if (event.datetime_start > currentDateTime) {
                // Upcoming event
                statusBadge = '<span class="badge badge-warning">Upcoming</span>';
            } else if (event.datetime_start <= currentDateTime && event.datetime_end >= currentDateTime) {
                // Ongoing event
                statusBadge = '<span class="badge badge-success">On-Going</span>';
            }

            var card = `
                <div class="col-md-3 mb-4">
                    <!-- Make the entire card clickable, wrapping it in an anchor tag -->
                    <a href="event_registration.php?event_id=${event.id}" class="text-decoration-none">
                        <div class="card shadow-sm" style="height: 100%;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${event.title}</h5>
                                <p class="card-text flex-grow-1">Venue: ${event.venue}</p>
                                ${statusBadge}
                            </div>
                        </div>
                    </a>
                </div>
            `;
            eventsContainer.append(card);
        });
    }
}

    // Function to fetch events based on search query
    function fetchEvents(query) {
        $.ajax({
            url: 'fetch_events.php', // The PHP file to fetch events
            type: 'POST',
            data: { search: query },
            success: function(response) {
                var events = JSON.parse(response);
                renderEvents(events);
            }
        });
    }

    // Initial load of events (both upcoming and ongoing)
    fetchEvents('');

    // Add event listener to the search input
    $('#searchEvent').on('input', function() {
        var searchValue = $(this).val();
        fetchEvents(searchValue); // Fetch events on each keystroke
    });
</script>

</body>
</html>
