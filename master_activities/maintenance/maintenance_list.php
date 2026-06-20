<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");
include("../../includes/header.php");
include("../../includes/sidebar.php");

/* ---------- FILTER HANDLING ---------- */
$search = trim($_GET['search'] ?? '');
$vendor = $_GET['vendor'] ?? '';
$where = "WHERE 1=1";

if($search != ""){
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $where .= " AND (a.asset_name LIKE '%$search_escaped%' OR m.issue_description LIKE '%$search_escaped%')";
}

if($vendor != ""){
    $vendor_escaped = mysqli_real_escape_string($conn, $vendor);
    $where .= " AND m.vendor_id = '$vendor_escaped'";
}

/* ---------- PAGINATION LOGIC ---------- */
$limit = 10;
$page = $_GET['page'] ?? 1;
$page = max(1, (int)$page);
$offset = ($page - 1) * $limit;

/* ---------- MAIN QUERY ---------- */
$query = "
SELECT m.*, a.asset_name, a.serial_number, v.vendor_name
FROM maintenance_records m
LEFT JOIN assets a ON m.asset_id = a.asset_id
LEFT JOIN vendors v ON m.vendor_id = v.vendor_id
$where
ORDER BY m.maintenance_date DESC
LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $query);
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Maintenance History</h2>
        <a href="maintenance_add.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Record
        </a>
    </div>

    <!-- SEARCH & FILTER FORM -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by asset name, serial or issue..." 
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Service Vendor</label>
                    <select name="vendor" class="form-select">
                        <option value="">All Vendors</option>
                        <?php
                        $vendors = mysqli_query($conn,"SELECT vendor_id, vendor_name FROM vendors ORDER BY vendor_name ASC");
                        while($v = mysqli_fetch_assoc($vendors)){
                            $selected = ($vendor == $v['vendor_id']) ? "selected" : "";
                            echo "<option value='{$v['vendor_id']}' $selected>{$v['vendor_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2 w-100">Filter</button>
                    <a href="maintenance_list.php" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Asset Name</th>
                            <th>Issue Description</th>
                            <th>Vendor</th>
                            <th class="text-end">Cost</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($row['maintenance_date'])) ?></td>
                                    <td>
                                        <a href="../assets/asset_details.php?id=<?= $row['asset_id'] ?>" class="text-decoration-none fw-bold">
                                            <?= htmlspecialchars($row['asset_name']) ?>
                                        </a>
                                        <br><small class="text-muted">SN: <?= htmlspecialchars($row['serial_number']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars(substr($row['issue_description'], 0, 50)) ?><?= strlen($row['issue_description']) > 50 ? '...' : '' ?></td>
                                    <td><?= htmlspecialchars($row['vendor_name'] ?: 'N/A') ?></td>
                                    <td class="text-end fw-bold text-primary">₹ <?= number_format($row['cost'], 2) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="maintenance_details.php?id=<?= $row['maintenance_id'] ?>" class="btn btn-info">View</a>
                                            <a href="maintenance_delete.php?id=<?= $row['maintenance_id'] ?>" 
                                               onclick="return confirm('Are you sure you want to delete this record?')"
                                               class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No maintenance records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PAGINATION -->
    <?php
    $total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM maintenance_records m LEFT JOIN assets a ON m.asset_id = a.asset_id $where");
    $total_rows = mysqli_fetch_assoc($total_query)['total'];
    $total_pages = ceil($total_rows / $limit);
    ?>
    <?php if($total_pages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <li class="page-item <?= ($i==$page)?'active':'' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&vendor=<?= urlencode($vendor) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php include("../../includes/footer.php"); ?>
