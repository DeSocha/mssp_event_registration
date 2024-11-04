<?php
session_start();
include('../includes/config.php'); // Adjust path if needed
include('../includes/sidebar.php');

// Fetch provinces for the Province dropdown
$provincesQuery = "SELECT id, name FROM province ORDER BY name";
$provincesResult = mysqli_query($conn, $provincesQuery);

// Get the current script name for highlighting the sidebar link
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }

        /* Adjust container and table alignment */
        .container-fluid {
            padding-top: 10px;
            padding-left: 220px;
        }

        /* Center table and limit its width */
        .table-wrapper {
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            margin-top: 50px;
            max-width: 90%; /* Limit the width to keep centered */
        }

        /* Responsive table */
        .table-responsive {
            overflow-x: auto;
        }

        /* Table Styling */
        .table {
            width: 100%;
            font-size: 0.85em;
        }

        /* Adjust row height and padding */
        .table th, .table td {
            padding: 8px 10px;
            line-height: 1.3;
            text-align: center;
            white-space: nowrap;
        }

        /* Center align for headers */
        .main-title {
            font-size: 2em;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* Styling for filter form */
        .filter-form-container {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }
        .filter-form {
            display: flex;
            gap: 5px; /* Spacing between elements */
            align-items: center;
        }
        .filter-form select, .filter-form input, .filter-form button {
            width: 140px; /* Reduced width for compact layout */
            padding: 5px; /* Smaller padding for compactness */
        }
    </style>
</head>
<body>

<!-- Main Content with Sidebar and Header -->
<div class="container-fluid">
    <!-- Centered Main Title -->
    <div class="d-flex justify-content-center align-items-center mt-4">
    <h2 class="page-title">Contact List</h2>
</div>


    <!-- Centered Filter Form -->
    <div class="table-wrapper">
        <div class="filter-form-container">
            <div class="filter-form">
                <input type="text" id="nameFilter" class="form-control" placeholder="Filter by Name">
                
                <select id="provinceFilter" class="form-control">
                    <option value="">Filter by Province</option>
                    <?php while ($province = mysqli_fetch_assoc($provincesResult)) : ?>
                        <option value="<?php echo $province['id']; ?>"><?php echo htmlspecialchars($province['name']); ?></option>
                    <?php endwhile; ?>
                </select>
                
                <input type="text" id="positionFilter" class="form-control" placeholder="Filter by Position">
                
                <button id="applyFilter" class="btn btn-primary">Apply Filter</button>
            </div>
        </div>

        <!-- Table Wrapper with Responsive Table -->
        <div id="contactsTable" class="table-responsive">
            <!-- Table data will be loaded here via AJAX from fetch_contacts.php -->
        </div>
    </div>
</div>

<!-- JavaScript to load filtered data dynamically -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function loadContacts() {
        let name = $('#nameFilter').val();
        let province = $('#provinceFilter').val();
        let position = $('#positionFilter').val();

        $.ajax({
            url: 'fetch_contacts.php',
            method: 'GET',
            data: { name: name, province: province, position: position },
            success: function(data) {
                $('#contactsTable').html(data);
            }
        });
    }

    $(document).ready(function() {
        // Initial load of contacts
        loadContacts();

        // Reload contacts on filter change
        $('#applyFilter').on('click', function() {
            loadContacts();
        });
    });
</script>
</body>
</html>
