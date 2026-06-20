<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");
include("../../includes/header.php");
include("../../includes/sidebar.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);

/* ---------- MAINTENANCE RECORD INFO ---------- */
$query = "
SELECT m.*, a.asset_name, a.serial_number, a.asset_id, v.vendor_name, v.phone as vendor_phone, v.email as vendor_email, c.category_name
FROM maintenance_records m
LEFT JOIN assets a ON m.asset_id = a.asset_id
LEFT JOIN vendors v ON m.vendor_id = v.vendor_id
LEFT JOIN asset_categories c ON a.category_id = c.category_id
WHERE m.maintenance_id = '$id'
";
$record = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (!$record) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Maintenance record not found.</div></div>";
    include("../../includes/footer.php");
    exit();
}
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Maintenance Record Details</h2>
        <div>
            <a href="maintenance_list.php" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="row">
        <!-- LEFT COLUMN: SERVICE SUMMARY -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Service Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Service Date</label>
                        <span class="h5 fw-bold"><?= date('d M Y', strtotime($record['maintenance_date'])) ?></span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Maintenance Cost</label>
                        <span class="h4 fw-bold text-primary">₹ <?= number_format($record['cost'], 2) ?></span>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small d-block">Record ID</label>
                        <span>#<?= $record['maintenance_id'] ?></span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Asset Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Asset Name</label>
                        <a href="../assets/asset_details.php?id=<?= $record['asset_id'] ?>" class="fw-bold text-decoration-none">
                            <?= htmlspecialchars($record['asset_name']) ?>
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Serial Number</label>
                        <code><?= htmlspecialchars($record['serial_number']) ?></code>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small d-block">Category</label>
                        <span><?= htmlspecialchars($record['category_name']) ?></span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Vendor Details</h5>
                </div>
                <div class="card-body">
                    <?php if($record['vendor_id']): ?>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Vendor Name</label>
                            <a href="../vendors/vendors_details.php?id=<?= $record['vendor_id'] ?>" class="fw-bold text-decoration-none">
                                <?= htmlspecialchars($record['vendor_name']) ?>
                            </a>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Contact Phone</label>
                            <span><?= htmlspecialchars($record['vendor_phone'] ?: 'N/A') ?></span>
                        </div>
                        <div class="mb-0">
                            <label class="text-muted small d-block">Email Address</label>
                            <span><?= htmlspecialchars($record['vendor_email'] ?: 'N/A') ?></span>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No vendor associated with this record.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: DESCRIPTIONS -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Issue Description</h5>
                </div>
                <div class="card-body">
                    <p class="fs-5"><?= nl2br(htmlspecialchars($record['issue_description'])) ?></p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Additional Remarks</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted"><?= nl2br(htmlspecialchars($record['remarks'] ?: 'No additional remarks provided.')) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
