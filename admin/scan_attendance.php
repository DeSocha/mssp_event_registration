<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Event</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Create/Edit Event</h2>
    <form action="save_event.php" method="POST">
        <!-- Event Name -->
        <div class="form-group">
            <label for="event_name">Event Name</label>
            <input type="text" class="form-control" id="event_name" name="event_name" required>
        </div>

        <!-- Event Date -->
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" class="form-control" id="event_date" name="event_date" required>
        </div>

        <!-- Survey Link for WO Attendees -->
        <div class="form-group">
            <label for="survey_link_wo">Survey Link for WO Attendees</label>
            <input type="url" class="form-control" id="survey_link_wo" name="survey_link_wo" placeholder="https://forms.google.com/wo-survey">
        </div>

        <!-- Survey Link for Non-WO Attendees -->
        <div class="form-group">
            <label for="survey_link_non_wo">Survey Link for Non-WO Attendees</label>
            <input type="url" class="form-control" id="survey_link_non_wo" name="survey_link_non_wo" placeholder="https://forms.google.com/non-wo-survey">
        </div>

        <!-- Save Button -->
        <button type="submit" class="btn btn-primary">Save Event</button>
    </form>
</div>

<!-- Include Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
