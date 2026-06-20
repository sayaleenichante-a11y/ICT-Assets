<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");
include("../../includes/header.php");
include("../../includes/sidebar.php");

/* ---------- MAIN QUERY ---------- */
$query = "SELECT s.*, 
          (SELECT COUNT(*) FROM assets WHERE status_id = s.status_id) as asset_count
          FROM asset_status s 
          ORDER BY s.status_id ASC";
$result = mysqli_query($conn, $query);
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Asset Status Management</h2>
        <a href="<?= ROUTE_STATUS_ADD ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Status
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Status Name</th>
                            <th class="text-center">Total Assets</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $row['status_id'] ?></td>
                                    <td class="fw-bold">
                                        <?php 
                                        $badge_class = 'bg-secondary';
                                        if($row['status_name'] == 'Working' || $row['status_name'] == 'Available') $badge_class = 'bg-success';
                                        if($row['status_name'] == 'Assigned') $badge_class = 'bg-primary';
                                        if($row['status_name'] == 'Under Repair') $badge_class = 'bg-warning text-dark';
                                        if($row['status_name'] == 'Condemned' || $row['status_name'] == 'Retired') $badge_class = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badge_class ?> px-3 py-2"><?= htmlspecialchars($row['status_name']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info rounded-pill"><?= $row['asset_count'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="status_details.php?id=<?= $row['status_id'] ?>" class="btn btn-info">View Assets</a>
                                            <a href="status_edit.php?id=<?= $row['status_id'] ?>" class="btn btn-warning">Edit</a>
                                            <a href="status_delete.php?id=<?= $row['status_id'] ?>"
                                               onclick="return confirm('Are you sure? Deleting a status may affect asset tracking.')" 
                                               class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No status definitions found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
