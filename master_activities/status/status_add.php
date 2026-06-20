<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");

if(isset($_POST['save'])) {
    $name = mysqli_real_escape_string($conn, $_POST['status_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "INSERT INTO asset_status (status_name, description) VALUES ('$name', '$description')";
    
    if(mysqli_query($conn, $query)) {
        header("Location: status_list.php");
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
            <h4 class="mb-0">Add New Asset Status</h4>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Status Name</label>
                    <input type="text" name="status_name" class="form-control" placeholder="e.g. Working, Under Repair, Retired" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Define what this status means..."></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" name="save" class="btn btn-success px-4">Save Status</button>
                    <a href="status_list.php" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
