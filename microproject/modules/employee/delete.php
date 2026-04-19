<?php
// modules/employee/delete.php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

requireAdmin();

$empId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($empId > 0) {
    // Find the user_id associated with this employee
    $stmt = $pdo->prepare("SELECT user_id FROM employees WHERE id = ?");
    $stmt->execute([$empId]);
    $employee = $stmt->fetch();

    if ($employee) {
        $userId = $employee['user_id'];

        // Because we set ON DELETE CASCADE in our foreign keys,
        // deleting the user will automatically delete the employee 
        // record and their attendance records.
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
    }
}

header("Location: list.php?msg=deleted");
exit();
?>
