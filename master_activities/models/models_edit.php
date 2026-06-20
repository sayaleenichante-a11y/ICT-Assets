<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);

if(isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['model_name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $vendor_id = mysqli_real_escape_string($conn, $_POST['vendor_id']);
    $specifications = mysqli_real_escape_string($conn, $_POST['specifications']);

    $query = "UPDATE asset_models SET 
              model_name='$name', 
              category_id='$category_id', 
              vendor_id='$vendor_id', 
              specifications='$specifications' 
              WHERE model_id=$id";

    if(mysqli_query($conn, $query)) {
        header("Location: " . ROUTE_MODELS);
        exit();
    } else {
        $error = mysqli_error($conn);
    }
}

$result = mysqli_query($conn, "SELECT * FROM asset_models WHERE model_id=$id");
$row = mysqli_fetch_assoc($result);

include("../../includes/header.php");
include("../../includes/sidebar.php");
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Asset Model</h4>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Model Name</label>
                        <input type="text" name="model_name" value="<?= htmlspecialchars($row['model_name']) ?>" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM asset_categories ORDER BY category_name ASC");
                            while($cat = mysqli_fetch_assoc($res)) {
                                $selected = ($cat['category_id'] == $row['category_id']) ? "selected" : "";
                                echo "<option value='{$cat['category_id']}' $selected>{$cat['category_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vendor (Manufacturer)</label>
                        <select name="vendor_id" class="form-select" required>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM vendors ORDER BY vendor_name ASC");
                            while($ven = mysqli_fetch_assoc($res)) {
                                $selected = ($ven['vendor_id'] == $row['vendor_id']) ? "selected" : "";
                                echo "<option value='{$ven['vendor_id']}' $selected>{$ven['vendor_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Specifications</label>
                    <textarea name="specifications" class="form-control" rows="4"><?= htmlspecialchars($row['specifications']) ?></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" name="update" class="btn btn-primary px-4">Update Model</button>
                    <a href="<?= ROUTE_MODELS ?>" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
