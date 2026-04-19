<?php
// modules/employee/list.php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

requireAdmin();

$pageTitle = "Manage Employees";
include '../../includes/header.php';

// Fetch all employees
$query = "SELECT e.id, u.name, u.email, e.designation, e.department, e.status, e.joining_date 
          FROM employees e 
          JOIN users u ON e.user_id = u.id
          ORDER BY e.id DESC";
$stmt = $pdo->query($query);
$employees = $stmt->fetchAll();

// Handle messages
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Employee List</h2>
        <a href="add.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Employee</a>
    </div>

    <?php if ($msg === 'added'): ?>
        <div class="alert alert-success alert-dismissible fade show">Employee added successfully. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($msg === 'edited'): ?>
        <div class="alert alert-success alert-dismissible fade show">Employee updated successfully. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($msg === 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show">Employee deleted successfully. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($employees) > 0): ?>
                            <?php foreach ($employees as $emp): ?>
                                <tr>
                                    <td><?= htmlspecialchars($emp['id']) ?></td>
                                    <td><?= htmlspecialchars($emp['name']) ?></td>
                                    <td><?= htmlspecialchars($emp['email']) ?></td>
                                    <td><?= htmlspecialchars($emp['designation']) ?></td>
                                    <td><?= htmlspecialchars($emp['department']) ?></td>
                                    <td>
                                        <?php if ($emp['status'] === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit.php?id=<?= $emp['id'] ?>" class="btn btn-sm btn-info text-white"><i class="bi bi-pencil"></i></a>
                                        <a href="delete.php?id=<?= $emp['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this employee? This will also delete their login account and attendance records.');"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">No employees found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
