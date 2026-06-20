<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICT Asset Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #0d6efd;
            --sidebar-bg: #212529;
            --navbar-height: 56px;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .navbar {
            z-index: 1040;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
            height: var(--navbar-height);
        }
        .sidebar {
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            background-color: var(--sidebar-bg);
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1030;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: calc(100vh - var(--navbar-height));
            transition: all 0.3s;
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column;
        }
        .content-body {
            padding: 25px;
            flex: 1 0 auto;
        }
        .nav-link {
            color: rgba(255,255,255,.75);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 8px;
            margin: 4px 12px;
            text-decoration: none;
        }
        .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,.1);
        }
        .nav-link.active {
            color: #fff;
            background-color: var(--primary-color);
        }
        .master-menu-header {
            color: rgba(255,255,255,.4);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 20px 20px 10px;
            font-weight: 700;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 992px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>

    <!-- Core Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <button class="btn btn-dark d-lg-none me-2" type="button" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand d-flex align-items-center" href="<?= ROUTE_DASHBOARD ?>">
            <i class="bi bi-cpu-fill me-2 text-primary"></i>
            <span class="fw-bold">ICT ASSET MANAGER</span>
        </a>
        
        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle text-white p-0" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?= $_SESSION['user_name'] ?? 'Admin' ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="<?= ROUTE_LOGOUT ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
