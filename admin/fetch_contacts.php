<?php
include('../includes/config.php');

// Get filter values
$nameFilter = isset($_GET['name']) ? trim($_GET['name']) : '';
$provinceFilter = isset($_GET['province']) ? intval($_GET['province']) : '';
$positionFilter = isset($_GET['position']) ? trim($_GET['position']) : '';

// Build the query with filters
$sql = "SELECT a.*, p.name AS province FROM attendees a
        LEFT JOIN province p ON a.province_id = p.id
        WHERE 1=1";

if ($nameFilter) {
    $sql .= " AND (a.first_name LIKE '%" . mysqli_real_escape_string($conn, $nameFilter) . "%' 
              OR a.last_name LIKE '%" . mysqli_real_escape_string($conn, $nameFilter) . "%')";
}
if ($provinceFilter) {
    $sql .= " AND a.province_id = $provinceFilter";
}
if ($positionFilter) {
    $sql .= " AND a.position LIKE '%" . mysqli_real_escape_string($conn, $positionFilter) . "%'";
}

$sql .= " ORDER BY a.last_name, a.first_name";
$result = mysqli_query($conn, $sql);
?>

<table class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Rgred Date</th>
            <th>Name</th>
            <th>Phome Number</th>
            <th>Email</th>
            <th>Position</th>
            <th>Organization</th>
            <th>Province</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php $count = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($row['date_created'])); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td><?php echo htmlspecialchars($row['organization']); ?></td>
                    <td><?php echo htmlspecialchars($row['province']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" class="text-center">No contacts found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
