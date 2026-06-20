<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Check if asset has assignments or maintenance records before deleting (optional but good practice)
    // For now, simple delete
    $query = "DELETE FROM assets WHERE asset_id=$id";
    
    if(mysqli_query($conn, $query)) {
        header("Location: assets_list.php?msg=deleted");
    } else {
        header("Location: assets_list.php?msg=error");
    }
} else {
    header("Location: assets_list.php");
}
exit();
?>