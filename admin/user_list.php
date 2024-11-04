<?php
session_start();
// Include database connection
include('../includes/config.php');

// Fetch users from the database
$query = "SELECT id, fullname, username, role FROM users";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List | Admin</title>
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
            <h2>User List</h2>
            <a href="#" id="addNewButton" class="btn btn-primary">+ Add New</a>
        </div>
        
        <table id="userTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Type</th>
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
                    
    
                    echo "<td>" . $row['fullname'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . ucfirst($row['role']) . "</td>"; // Capitalize the type
                    echo "<td>
                            <button class='btn btn-primary btn-sm edit-button' data-id='" . $row['id'] . "'><i class='fas fa-edit'></i></button>
                            <a href='delete_user.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Manage User Modal -->
<div class="modal fade" id="manageUserModal" tabindex="-1" aria-labelledby="manageUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="manageUserModalLabel">Manage User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="manageUserForm">
        <div class="modal-body">
          <input type="hidden" id="user_id" name="user_id">
          <div class="form-group">
            <label for="name">Fullname</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="type">User Type</label>
            <select class="form-control" id="type" name="type" required>
              <option value="admin">Admin</option>
              <option value="registrar">Registrar</option>
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

<!-- Include Bootstrap and jQuery JS libraries -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#userTable').DataTable();

        // Show modal when edit button is clicked
        $('.edit-button').on('click', function() {
            let userId = $(this).data('id');
            // Make an AJAX call to fetch the user data using the ID and populate the modal fields
            $.ajax({
                url: 'manage_user.php',  // Adjust this path to point to your data fetching file
                type: 'GET',
                data: { id: userId },
                success: function(response) {
                    const user = JSON.parse(response);
                    $('#user_id').val(user.id);
                    $('#name').val(user.name);
                    $('#username').val(user.username);
                    $('#type').val(user.type); // Set the type (admin/registrar)
                    $('#manageUserModal').modal('show');
                }
            });
        });

        // Handle the form submission
        $('#manageUserForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: 'save_user.php',  // Adjust this path to point to your saving file
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#manageUserModal').modal('hide');
                    location.reload(); // Reload the page after saving
                }
            });
        });

        // Handle the "Add New" button click
        $('#addNewButton').on('click', function() {
            $('#manageUserForm')[0].reset();  // Clear all form fields
            $('#user_id').val('');  // Clear the hidden ID field for new user
            $('#manageUserModalLabel').text('Add New User');  // Change modal title
            $('#manageUserModal').modal('show');  // Show the modal
        });
    });
</script>

</body>
</html>
