<?php
// public/dashboard.php
require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

$pageTitle = "Dashboard";
include '../includes/header.php';

// Fetch statistics
$totalEmployees = 0;
$todayPresent = 0;
$todayAbsent = 0;
$todayLeave = 0;
$today = date('Y-m-d');

if ($_SESSION['role'] === 'admin') {
    // Total Active Employees
    $stmt = $pdo->query("SELECT COUNT(*) FROM employees WHERE status = 'active'");
    $totalEmployees = $stmt->fetchColumn();

    // Today's Attendance Stats
    $stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM attendance WHERE date = ? GROUP BY status");
    $stmt->execute([$today]);
    $attendanceStats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $todayPresent = $attendanceStats['Present'] ?? 0;
    $todayAbsent = $attendanceStats['Absent'] ?? 0;
    $todayLeave = $attendanceStats['Leave'] ?? 0;
} else {
    // Employee perspective - fetch own data
    $stmt = $pdo->prepare("SELECT id FROM employees WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $employeeId = $stmt->fetchColumn();

    if ($employeeId) {
        $stmt = $pdo->prepare("SELECT status FROM attendance WHERE employee_id = ? AND date = ?");
        $stmt->execute([$employeeId, $today]);
        $myAttendanceToday = $stmt->fetchColumn();
    }
}
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Dashboard</h2>
            <p class="text-muted">Overview of the system</p>
        </div>
    </div>

    <?php if ($_SESSION['role'] === 'admin'): ?>
    <div class="row">
        <!-- Total Employees -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stat shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Active Employees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($totalEmployees) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300 fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Present Today -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stat shadow h-100 py-2" style="border-left-color: #28a745;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Present Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($todayPresent) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300 fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Absent Today -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stat shadow h-100 py-2" style="border-left-color: #dc3545;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Absent Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($todayAbsent) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-x-circle fa-2x text-gray-300 fs-1 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- On Leave Today -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stat shadow h-100 py-2" style="border-left-color: #ffc107;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">On Leave Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($todayLeave) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-dash fa-2x text-gray-300 fs-1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="../modules/employee/add.php" class="btn btn-primary mb-2"><i class="bi bi-person-plus"></i> Add New Employee</a>
                    <a href="../modules/attendance/mark.php" class="btn btn-success mb-2"><i class="bi bi-calendar-check"></i> Mark Today's Attendance</a>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- Employee Dashboard View -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">My Status Today (<?= htmlspecialchars($today) ?>)</h6>
                </div>
                <div class="card-body text-center py-5">
                    <?php if (isset($myAttendanceToday) && $myAttendanceToday): ?>
                        <?php 
                            $bgClass = 'bg-secondary';
                            if ($myAttendanceToday === 'Present') $bgClass = 'bg-success';
                            if ($myAttendanceToday === 'Absent') $bgClass = 'bg-danger';
                            if ($myAttendanceToday === 'Leave') $bgClass = 'bg-warning text-dark';
                        ?>
                        <h3 class="badge <?= $bgClass ?> fs-3 p-3"><?= htmlspecialchars($myAttendanceToday) ?></h3>
                    <?php else: ?>
                        <h3 class="text-muted">Not Marked Yet</h3>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>
