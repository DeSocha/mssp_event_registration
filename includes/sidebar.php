<?php
// Get the current script name (e.g., event_list.php, admin_dashboard.php)
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <img src="../assets/images/logo.png" alt="Logo" class="logo">
        <h4>MSSP-ERS</h4>
        <p class="username" style="color: green;"><?php echo $_SESSION['admin_username']; ?></p> <!-- Display admin username in green -->
        <hr class="sidebar-divider"> <!-- Add a separator line here -->
    </div>
    <ul class="sidebar-menu">
        <li><a href="admin_dashboard.php" class="<?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="event_list.php" class="<?php echo ($current_page == 'event_list.php') ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> Event List</a></li>
        <li><a href="audiance_list.php" class="<?php echo ($current_page == 'audiance_list.php') ? 'active' : ''; ?>"><i class="fas fa-users"></i> Participants List</a></li>
        <li><a href="user_list.php" class="<?php echo ($current_page == 'user_list.php') ? 'active' : ''; ?>"><i class="fas fa-user"></i> User List</a></li>
        <li><a href="generate_reports.php" class="<?php echo ($current_page == 'generate_reports.php') ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Report</a></li>
        <li><a href="contact_list.php" class="<?php echo ($current_page == 'contact_list.php') ? 'active' : ''; ?>"><i class="fas fa-phone"></i> Contact List</a></li> <!-- Contact List Sidebar Item -->
    </ul>
</div>

<!-- Header Section -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- Sidebar Toggle Button (bars icon) -->
        <button class="navbar-toggler" type="button" id="sidebarToggle" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- System Title or Logo -->
        <a class="navbar-brand" href="#">MSSP-SoCha Event Registration System</a>

        <!-- Right Section: Settings and Logout Buttons -->
        <div class="ml-auto d-flex align-items-center">
            <!-- Logout Button -->
            <a href="logout.php" class="btn btn-danger ml-3">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>

<!-- Optional: Add JavaScript to toggle sidebar -->
<script>
    // Toggle sidebar visibility
    document.getElementById("sidebarToggle").addEventListener("click", function() {
        let sidebar = document.getElementById("sidebar");
        if (sidebar.style.display === "none") {
            sidebar.style.display = "block";
        } else {
            sidebar.style.display = "none";
        }
    });

    // Alternative Toggle for smoother transition
    document.getElementById("sidebarToggle").addEventListener("click", function() {
        let sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("show"); // Toggle class to show/hide sidebar
    });
</script>

<!-- Add FontAwesome CSS for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<!-- Link to the external CSS file -->
<link rel="stylesheet" href="../css/sidebar-header.css">
