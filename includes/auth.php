<?php
/**
 * Authentication & Session Management
 * Ensures only authorized users can access the system
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include configuration for BASE_URL and routes
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../config/config.php');

/**
 * Redirect to login if user is not authenticated
 */
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

/**
 * Helper function to check user roles
 * @param array $allowed_roles
 * @return bool
 */
function checkRole(array $allowed_roles): bool
{
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles, true)) {
        header("Location: " . ROUTE_DASHBOARD . "?error=unauthorized");
        exit();
    }
    return true;
}

/**
 * CSRF Protection Token Generation
 */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

