<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");

if(isset($_POST['save'])) {
    $asset_id = mysqli_real_escape_string($conn, $_POST['asset_id']);
    $issue = mysqli_real_escape_string($conn, $_POST['issue_description']);
    $date = mysqli_real_escape_string($conn, $_POST['maintenance_date']);
    $cost = mysqli_real_escape_string($conn, $_POST['cost']);
    $vendor_id = mysqli_real_escape_string($conn, $_POST['vendor_id']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    $query = "INSERT INTO maintenance_records (asset_id, issue_description, maintenance_date, cost, vendor_id, remarks) 
              VALUES ('$asset_id', '$issue', '$date', '$cost', '$vendor_id', '$remarks')";
    
    if(mysqli_query($conn, $query)) {
        header("Location: maintenance_list.php");
        exit();
    } else {
        $error = mysqli_error($conn);
    }
}

include("../../includes/header.php");
include("../../includes/sidebar.php");
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Maintenance Record</h4>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Select Asset</label>
                        <select name="asset_id" class="form-select" required>
                            <option value="">-- Choose Asset --</option>
                            <?php
                            $assets = mysqli_query($conn,"SELECT asset_id, asset_name, serial_number FROM assets ORDER BY asset_name ASC");
                            while($row = mysqli_fetch_assoc($assets)) {
                                echo "<option value='{$row['asset_id']}'>{$row['asset_name']} ({$row['serial_number']})</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Maintenance Date</label>
                        <input type="date" name="maintenance_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Service Vendor</label>
                        <select name="vendor_id" class="form-select">
                            <option value="">-- Select Vendor --</option>
                            <?php
                            $vendors = mysqli_query($conn,"SELECT vendor_id, vendor_name FROM vendors ORDER BY vendor_name ASC");
                            while($row = mysqli_fetch_assoc($vendors)) {
                                echo "<option value='{$row['vendor_id']}'>{$row['vendor_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Maintenance Cost (₹)</label>
                        <input type="number" step="0.01" name="cost" class="form-control" placeholder="0.00">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Issue Description</label>
                    <textarea name="issue_description" class="form-control" rows="3" placeholder="Describe the problem or service performed..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Additional Remarks</label>
                    <textarea name="remarks" class="form-control" rows="2" placeholder="Any other notes..."></textarea>
                </div>

                <div class="mt-4 border-top pt-3">
                    <button type="submit" name="save" class="btn btn-success btn-lg px-5">Save Record</button>
                    <a href="maintenance_list.php" class="btn btn-secondary btn-lg px-5">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
