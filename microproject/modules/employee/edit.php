<?php
// modules/employee/edit.php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

requireAdmin();

$pageTitle = "Edit Employee";
$error = '';
$empId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($empId === 0) {
    header("Location: list.php");
    exit();
}

// Fetch existing data
$stmt = $pdo->prepare("SELECT e.*, u.name, u.email FROM employees e JOIN users u ON e.user_id = u.id WHERE e.id = ?");
$stmt->execute([$empId]);
$employee = $stmt->fetch();

if (!$employee) {
    header("Location: list.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $designation = trim($_POST['designation']);
    $department = trim($_POST['department']);
    $salary = floatval($_POST['salary']);
    $joining_date = $_POST['joining_date'];
    $status = $_POST['status'];
    
    if (empty($name) || empty($email) || empty($designation) || empty($department) || empty($joining_date)) {
        $error = "All fields are required.";
    } else {
        // Check if email exists for other users
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $employee['user_id']]);
        if ($stmt->rowCount() > 0) {
            $error = "Email already exists for another user!";
        } else {
            try {
                $pdo->beginTransaction();

                // Update User
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $stmt->execute([$name, $email, $employee['user_id']]);

                // Update Employee details
                $stmt = $pdo->prepare("UPDATE employees SET designation = ?, department = ?, salary = ?, joining_date = ?, status = ? WHERE id = ?");
                $stmt->execute([$designation, $department, $salary, $joining_date, $status, $empId]);

                // Update Password if provided
                if (!empty($_POST['password'])) {
                    $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$hashedPassword, $employee['user_id']]);
                }

                $pdo->commit();
                
                header("Location: list.php?msg=edited");
                exit();
            } catch (\PDOException $e) {
                $pdo->rollBack();
                $error = "Database Error: " . $e->getMessage();
            }
        }
    }
}

include '../../includes/header.php';
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow align-middle">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Edit Employee: <?= htmlspecialchars($employee['name']) ?></h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="edit.php?id=<?= $empId ?>" method="POST">
                        <h5 class="text-muted mb-3 border-bottom pb-2">Login Credentials</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name" required value="<?= htmlspecialchars($employee['name']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" required value="<?= htmlspecialchars($employee['email']) ?>">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Password <small class="text-muted">(Leave blank to keep current)</small></label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?= $employee['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $employee['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <h5 class="text-muted mb-3 border-bottom pb-2">Employment Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Designation</label>
                                <input type="text" class="form-control" name="designation" required value="<?= htmlspecialchars($employee['designation']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" name="department" required value="<?= htmlspecialchars($employee['department']) ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Salary</label>
                                <input type="number" step="0.01" class="form-control" name="salary" required value="<?= htmlspecialchars($employee['salary']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Joining Date</label>
                                <input type="date" class="form-control" name="joining_date" required value="<?= htmlspecialchars($employee['joining_date']) ?>">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Employee</button>
                            <a href="list.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
