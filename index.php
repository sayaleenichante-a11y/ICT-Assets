<?php
include("includes/auth.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

header("Location: dashboard.php");
exit();

