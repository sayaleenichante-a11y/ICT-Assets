<?php
global $conn;
session_start();
include("../config/db.php");
include("../config/config.php");

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit();
}

if(isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $query = "SELECT * FROM users WHERE email='$email' AND role IN ('Admin', 'ICT Staff', 'Employees')";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password (supports both hashed and plain text for transition)
        $is_valid = false;
        if (password_verify($password, $user['password'])) {
            $is_valid = true;
        } elseif ($password === $user['password']) {
            // Fallback for plain text passwords (should be updated to hashed)
            $is_valid = true;
        }

        if ($is_valid) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: ../dashboard.php");
            exit();
        }
    }

    $error = "Invalid email/password or unauthorized access.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ICT Asset Management</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }
        .login-header {
            background: #212529;
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .login-header i {
            font-size: 3rem;
            margin-bottom: 10px;
            color: #0d6efd;
        }
        .login-body {
            padding: 40px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            border-color: #0d6efd;
        }
        .btn-login {
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .brand-text {
            font-weight: 700;
            letter-spacing: -0.5px;
        }
    </style>
</head>
<body>

<div class="login-card animate__animated animate__fadeIn">
    <div class="login-header">
        <i class="bi bi-cpu-fill"></i>
        <h4 class="brand-text mb-0">ICT ASSET MANAGER</h4>
        <p class="text-muted small mb-0 mt-2">Secure Administrative Access</p>
    </div>
    
    <div class="login-body">
        <?php if(isset($error)): ?>
            <div class="alert alert-danger d-flex align-items-center small" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <div><?= $error ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="name@company.com" required autofocus>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-primary btn-login w-100 mb-3">
                Sign In <i class="bi bi-arrow-right ms-2"></i>
            </button>
            
            <div class="text-center">
                <a href="#" class="text-decoration-none small text-muted">Forgot password?</a>
            </div>
        </form>
    </div>
    
    <div class="bg-light py-3 text-center border-top">
        <span class="text-muted small">&copy; <?= date('Y') ?> Asset Management System</span>
    </div>
</div>

</body>
</html>
