<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);

if(isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['vendor_name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact_person']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $query = "UPDATE vendors SET 
              vendor_name='$name', 
              contact_person='$contact', 
              phone='$phone', 
              email='$email', 
              address='$address' 
              WHERE vendor_id=$id";

    if(mysqli_query($conn, $query)) {
        header("Location: " . ROUTE_VENDORS);
        exit();
    } else {
        $error = mysqli_error($conn);
    }
}

$result = mysqli_query($conn, "SELECT * FROM vendors WHERE vendor_id=$id");
$row = mysqli_fetch_assoc($result);

include("../../includes/header.php");
include("../../includes/sidebar.php");
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Vendor</h4>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vendor Name</label>
                        <input type="text" name="vendor_name" value="<?= htmlspecialchars($row['vendor_name']) ?>" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" value="<?= htmlspecialchars($row['contact_person']) ?>" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($row['phone']) ?>" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Office Address</label>
                    <textarea name="address" class="form-control" rows="4"><?= htmlspecialchars($row['address']) ?></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" name="update" class="btn btn-primary px-4">Update Vendor</button>
                    <a href="<?= ROUTE_VENDORS ?>" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
