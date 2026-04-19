<?php
// modules/employee/profile.php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

requireLogin();

$pageTitle = "My Profile";
include '../../includes/header.php';

// Fetch own employee details
$stmt = $pdo->prepare("
    SELECT e.*, u.name, u.email 
    FROM employees e 
    JOIN users u ON e.user_id = u.id 
    WHERE u.id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$employee = $stmt->fetch();
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow mt-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-badge"></i> My Profile</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($employee): ?>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-sm-4 fw-bold">Full Name</div>
                            <div class="col-sm-8"><?= htmlspecialchars($employee['name']) ?></div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-sm-4 fw-bold">Email Address</div>
                            <div class="col-sm-8"><?= htmlspecialchars($employee['email']) ?></div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-sm-4 fw-bold">Designation</div>
                            <div class="col-sm-8"><?= htmlspecialchars($employee['designation']) ?></div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-sm-4 fw-bold">Department</div>
                            <div class="col-sm-8"><?= htmlspecialchars($employee['department']) ?></div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-sm-4 fw-bold">Joining Date</div>
                            <div class="col-sm-8"><?= date('F j, Y', strtotime($employee['joining_date'])) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Status</div>
                            <div class="col-sm-8">
                                <?php if ($employee['status'] === 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">No employee record found for your account. You might be an admin without an employee profile.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
