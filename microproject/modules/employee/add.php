<?php
// modules/employee/add.php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

requireAdmin();

$pageTitle = "Add Employee";
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $designation = trim($_POST['designation']);
    $department = trim($_POST['department']);
    $salary = floatval($_POST['salary']);
    $joining_date = $_POST['joining_date'];
    
    if (empty($name) || empty($email) || empty($password) || empty($designation) || empty($department) || empty($joining_date)) {
        $error = "All fields except salary are required.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email already exists!";
        } else {
            try {
                $pdo->beginTransaction();

                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert User (role = employee)
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'employee')");
                $stmt->execute([$name, $email, $hashedPassword]);
                $userId = $pdo->lastInsertId();

                // Insert Employee details
                $stmt = $pdo->prepare("INSERT INTO employees (user_id, designation, department, salary, joining_date, status) VALUES (?, ?, ?, ?, ?, 'active')");
                $stmt->execute([$userId, $designation, $department, $salary, $joining_date]);

                $pdo->commit();
                
                header("Location: list.php?msg=added");
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
                    <h4 class="mb-0">Add New Employee</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="add.php" method="POST">
                        <h5 class="text-muted mb-3 border-bottom pb-2">Login Credentials</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name" required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <h5 class="text-muted mb-3 border-bottom pb-2">Employment Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Designation</label>
                                <input type="text" class="form-control" name="designation" required value="<?= isset($_POST['designation']) ? htmlspecialchars($_POST['designation']) : '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" name="department" required value="<?= isset($_POST['department']) ? htmlspecialchars($_POST['department']) : '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Salary</label>
                                <input type="number" step="0.01" class="form-control" name="salary" required value="<?= isset($_POST['salary']) ? htmlspecialchars($_POST['salary']) : '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Joining Date</label>
                                <input type="date" class="form-control" name="joining_date" required value="<?= isset($_POST['joining_date']) ? htmlspecialchars($_POST['joining_date']) : '' ?>">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Employee</button>
                            <a href="list.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
