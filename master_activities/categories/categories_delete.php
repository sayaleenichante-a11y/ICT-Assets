<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM asset_categories WHERE category_id=$id");

header("Location: " . ROUTE_CATEGORIES);
exit();
