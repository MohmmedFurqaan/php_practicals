<?php
// modules/attendance/list.php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

requireLogin(); // Both admin and employees can view, but they see different things

$pageTitle = "Attendance Records";
include '../../includes/header.php';

$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
$userId = $_SESSION['user_id'];
$isAdmin = $_SESSION['role'] === 'admin';

// Query building based on role
if ($isAdmin) {
    $query = "SELECT a.date, a.status, e.id as emp_id, u.name, e.department 
              FROM attendance a 
              JOIN employees e ON a.employee_id = e.id 
              JOIN users u ON e.user_id = u.id ";
    $params = [];
    
    if (!empty($dateFilter)) {
        $query .= " WHERE a.date = ? ";
        $params[] = $dateFilter;
    }
    
    $query .= " ORDER BY a.date DESC, u.name ASC LIMIT 100"; // limit for performance in a real app
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $records = $stmt->fetchAll();

} else {
    // Determine employee_id for the logged-in user
    $stmt = $pdo->prepare("SELECT id FROM employees WHERE user_id = ?");
    $stmt->execute([$userId]);
    $empId = $stmt->fetchColumn();

    if ($empId) {
        $query = "SELECT a.date, a.status 
                  FROM attendance a 
                  WHERE a.employee_id = ? ";
        $params = [$empId];
        
        if (!empty($dateFilter)) {
            $query .= " AND a.date = ? ";
            $params[] = $dateFilter;
        }
        
        $query .= " ORDER BY a.date DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $records = $stmt->fetchAll();
    } else {
        $records = [];
    }
}
?>

<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2>Attendance Records</h2>
            <?php if (!$isAdmin): ?>
                <p class="text-muted">Viewing your attendance history.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-6 text-md-end">
            <form action="list.php" method="GET" class="d-inline-flex align-items-center">
                <label class="me-2 fw-bold">Filter Date:</label>
                <input type="date" name="date" class="form-control me-2" style="width: auto;" value="<?= htmlspecialchars($dateFilter) ?>">
                <button type="submit" class="btn btn-outline-secondary">Filter</button>
                <?php if(!empty($dateFilter)): ?>
                    <a href="list.php" class="btn btn-link">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <?php if ($isAdmin): ?>
                                <th>Employee Name</th>
                                <th>Department</th>
                            <?php endif; ?>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($records) > 0): ?>
                            <?php foreach ($records as $row): ?>
                                <tr>
                                    <td><?= date('F j, Y', strtotime($row['date'])) ?></td>
                                    <?php if ($isAdmin): ?>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['department']) ?></td>
                                    <?php endif; ?>
                                    <td>
                                        <?php if ($row['status'] === 'Present'): ?>
                                            <span class="badge bg-success">Present</span>
                                        <?php elseif ($row['status'] === 'Absent'): ?>
                                            <span class="badge bg-danger">Absent</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Leave</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= $isAdmin ? '4' : '2' ?>" class="text-center py-4">No attendance records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
