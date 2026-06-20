<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");

if(isset($_POST['save'])) {
    $name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "INSERT INTO asset_categories (category_name, description) VALUES ('$name', '$description')";
    
    if(mysqli_query($conn, $query)) {
        header("Location: " . ROUTE_CATEGORIES);
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
            <h4 class="mb-0">Add New Category</h4>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="category_name" class="form-control" placeholder="e.g. Laptops, Printers, Servers" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Brief description of this category..."></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" name="save" class="btn btn-success px-4">Save Category</button>
                    <a href="<?= ROUTE_CATEGORIES ?>" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
