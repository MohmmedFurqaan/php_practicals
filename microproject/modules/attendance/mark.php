<?php
// modules/attendance/mark.php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

requireAdmin();

$pageTitle = "Mark Attendance";
$msg = '';
$error = '';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Fetch all active employees
$stmt = $pdo->query("SELECT e.id, u.name, e.designation FROM employees e JOIN users u ON e.user_id = u.id WHERE e.status = 'active' ORDER BY u.name");
$employees = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedDate = $_POST['date'];
    $attendanceData = $_POST['attendance'] ?? []; // employee_id => status

    if (empty($selectedDate) || empty($attendanceData)) {
        $error = "Please select a date and mark attendance for at least one employee.";
    } else {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO attendance (employee_id, date, status) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE status = ?");
            
            foreach ($attendanceData as $empId => $status) {
                // If it exists, it updates due to the UNIQUE KEY (employee_id, date) we set in the schema
                $stmt->execute([$empId, $selectedDate, $status, $status]);
            }

            $pdo->commit();
            $msg = "Attendance saved successfully for $selectedDate.";
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

// Fetch existing attendance for the selected date to populate the form
$stmt = $pdo->prepare("SELECT employee_id, status FROM attendance WHERE date = ?");
$stmt->execute([$date]);
$existingAttendance = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

include '../../includes/header.php';
?>

<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2>Mark Attendance</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <form action="mark.php" method="GET" class="d-inline-flex align-items-center">
                <label class="me-2 fw-bold">Select Date:</label>
                <input type="date" name="date" class="form-control me-2" style="width: auto;" value="<?= htmlspecialchars($date) ?>" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if(!empty($msg)): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($msg) ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <form action="mark.php?date=<?= htmlspecialchars($date) ?>" method="POST">
                <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50%">Employee Name</th>
                                <th>Designation</th>
                                <th style="width: 30%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($employees) > 0): ?>
                                <?php foreach ($employees as $emp): 
                                    $empId = $emp['id'];
                                    $currentStatus = isset($existingAttendance[$empId]) ? $existingAttendance[$empId] : 'Present'; // Default to Present
                                ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($emp['name']) ?></strong></td>
                                        <td><?= htmlspecialchars($emp['designation']) ?></td>
                                        <td>
                                            <div class="form-check form-check-inline form-check-success">
                                                <input class="form-check-input" type="radio" name="attendance[<?= $empId ?>]" id="present_<?= $empId ?>" value="Present" <?= $currentStatus === 'Present' ? 'checked' : '' ?>>
                                                <label class="form-check-label text-success fw-bold" for="present_<?= $empId ?>">Present</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attendance[<?= $empId ?>]" id="absent_<?= $empId ?>" value="Absent" <?= $currentStatus === 'Absent' ? 'checked' : '' ?>>
                                                <label class="form-check-label text-danger fw-bold" for="absent_<?= $empId ?>">Absent</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attendance[<?= $empId ?>]" id="leave_<?= $empId ?>" value="Leave" <?= $currentStatus === 'Leave' ? 'checked' : '' ?>>
                                                <label class="form-check-label text-warning fw-bold" for="leave_<?= $empId ?>">Leave</label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4">No active employees found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (count($employees) > 0): ?>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-calendar-check"></i> Save Attendance</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
