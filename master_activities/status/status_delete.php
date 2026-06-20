<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");
include("../../includes/header.php");
include("../../includes/sidebar.php");

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM asset_status WHERE status_id=$id");

header("Location: status_list.php");
?>
<?php include("../../includes/footer.php"); ?>
