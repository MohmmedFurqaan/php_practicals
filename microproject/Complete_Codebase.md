Filename: assets/css/style.css
```css
/* assets/css/style.css */

body {
    background-color: #f4f6f9;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.sidebar {
    min-height: 100vh;
    background-color: #343a40;
    padding-top: 20px;
}

.sidebar a {
    color: #c2c7d0;
    text-decoration: none;
    padding: 10px 20px;
    display: block;
}

.sidebar a:hover {
    background-color: #494e53;
    color: #ffffff;
}

.sidebar a.active {
    background-color: #007bff;
    color: #ffffff;
}

.main-content {
    padding: 20px;
}

.card-stat {
    border-left: 4px solid #007bff;
    transition: transform 0.2s;
}

.card-stat:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.attendance-form-row {
    margin-bottom: 15px;
}

.login-container {
    max-width: 400px;
    margin: 100px auto;
}
```

Filename: config/database.php
```php
<?php
// config/database.php

$host = '127.0.0.1';
$db   = 'ems';
$user = 'root'; // Change if required
$pass = '';     // Change if required
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In a production app, we would log this instead of outputting.
    // However for a microproject, it helps with debugging on startup.
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
```

Filename: database.sql
```sql
CREATE DATABASE IF NOT EXISTS ems;
USE ems;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'employee') DEFAULT 'employee',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    designation VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    joining_date DATE NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent', 'Leave') NOT NULL,
    UNIQUE KEY unique_attendance (employee_id, date),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

-- Insert default admin (Password is: admin123)
-- Password hash generated using PHP password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@example.com', '$2y$10$oYVp3r/mBv0uOfKjM9tGhuoOQK3X36fB4Cq/x8G2Y1z7Tj4U.9a2q', 'admin');
```

Filename: includes/auth.php
```php
<?php
// includes/auth.php

session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /php_practicals/microproject/modules/auth/login.php");
        exit();
    }
}

function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        die("Unauthorized access. Admin role required.");
    }
}
?>
```

Filename: includes/footer.php
```php
<?php if (isset($_SESSION['user_id'])): ?>
    </div> <!-- End Main Content -->
</div> <!-- End d-flex wrapper -->
<?php endif; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

Filename: includes/header.php
```php
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
```

Filename: index.php
```php
<?php
// index.php
// Redirect root to the dashboard

header("Location: public/dashboard.php");
exit();
?>
```

Filename: modules/attendance/list.php
```php
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
```

Filename: modules/attendance/mark.php
```php
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
```

Filename: modules/auth/login.php
```php
<?php
// modules/auth/login.php
require_once '../../config/database.php';
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../../public/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter email and password.";
    } else {
        // Prepare statement to fetch user
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect to dashboard
            header("Location: ../../public/dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 login-container">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Employee Management System</h4>
                </div>
                <div class="card-body p-4">
                    <form action="login.php" method="POST">
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center text-muted small">
                    College Microproject
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
```

Filename: modules/auth/logout.php
```php
<?php
// modules/auth/logout.php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>
```

Filename: modules/employee/add.php
```php
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
```

Filename: modules/employee/delete.php
```php
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
```

Filename: modules/employee/edit.php
```php
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
```

Filename: modules/employee/list.php
```php
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
```

Filename: modules/employee/profile.php
```php
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
```

Filename: public/dashboard.php
```php
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
```

