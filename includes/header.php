<!-- Header Section -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- Sidebar Toggle Button -->
        <button class="navbar-toggler" type="button" id="sidebarToggle" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- System Title -->
        <a class="navbar-brand" href="#">MSSP-SoCha Event Registration System</a>

        <!-- Right Section: Logout and Settings -->
        <div class="ml-auto d-flex align-items-center">
            <!-- Settings Icon -->
            <button class="btn btn-light" id="settings-btn">
                <i class="fas fa-cog"></i>
            </button>

            <!-- Logout Icon -->
            <a href="logout.php" class="btn btn-danger ml-3">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>

<!-- Add JavaScript to toggle sidebar -->
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
</script>
