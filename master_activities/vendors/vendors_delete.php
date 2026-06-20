<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM vendors WHERE vendor_id=$id");

header("Location: " . ROUTE_VENDORS);
exit();
?>