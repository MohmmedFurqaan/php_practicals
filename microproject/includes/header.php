<?php
// includes/header.php
// Expected $pageTitle to be defined before including this file

// Find relative path to root for asset linking, assuming files are at most 2 levels deep
$rootPath = dirname($_SERVER['PHP_SELF']);
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/php_practicals/microproject/";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Employee Management System' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="<?= $base_url ?>assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar col-md-3 col-lg-2 p-0 bg-dark text-white shadow-sm">
        <div class="p-3 text-center">
            <h4>EMS</h4>
            <hr>
            <p class="mb-0 small">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></p>
            <p class="badge text-bg-primary"><?= ucfirst(htmlspecialchars($_SESSION['role'])) ?></p>
        </div>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="<?= $base_url ?>public/dashboard.php" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'dashboard.php') !== false ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
                <a href="<?= $base_url ?>modules/employee/list.php" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'employee/list.php') !== false || strpos($_SERVER['REQUEST_URI'], 'employee/add.php') !== false ? 'active' : '' ?>">
                    <i class="bi bi-people me-2"></i> Manage Employees
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $base_url ?>modules/attendance/mark.php" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'attendance/mark.php') !== false ? 'active' : '' ?>">
                    <i class="bi bi-calendar-check me-2"></i> Mark Attendance
                </a>
            </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="<?= $base_url ?>modules/attendance/list.php" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'attendance/list.php') !== false ? 'active' : '' ?>">
                    <i class="bi bi-calendar me-2"></i> View Attendance
                </a>
            </li>
            
            <?php if ($_SESSION['role'] === 'employee'): ?>
            <li class="nav-item">
                <a href="<?= $base_url ?>modules/employee/profile.php" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'employee/profile.php') !== false ? 'active' : '' ?>">
                    <i class="bi bi-person me-2"></i> My Profile
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <div class="mt-auto p-3">
            <a href="<?= $base_url ?>modules/auth/logout.php" class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content w-100">
<?php endif; ?>
