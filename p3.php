<?php
// CONSTANTS
define("TAX_RATE", 0.10);
define("MIN_WORK_HOURS", 40);

// VARIABLES
$employeeName = "Maria Santos";
$hoursWorked = 45;
$ratePerHour = 200;

// OPERATORS (Arithmetic)
$grossSalary = $hoursWorked * $ratePerHour;
$tax = $grossSalary * TAX_RATE;
$netSalary = $grossSalary - $tax;

// OPERATORS (Comparison & Logical)
if ($hoursWorked >= MIN_WORK_HOURS) {
    $status = "Full-time Employee";
} else {
    $status = "Part-time Employee";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Salary System</title>
</head>
<body>

<h2>Employee Information</h2>

<p><strong>Name:</strong> <?php echo $employeeName; ?></p>
<p><strong>Status:</strong> <?php echo $status; ?></p>

<h3>Salary Details</h3>
<p>Hours Worked: <?php echo $hoursWorked; ?></p>
<p>Rate per Hour: ₱<?php echo $ratePerHour; ?></p>
<p>Gross Salary: ₱<?php echo $grossSalary; ?></p>
<p>Tax (10%): ₱<?php echo $tax; ?></p>
<p><strong>Net Salary:</strong> ₱<?php echo $netSalary; ?></p>

</body>
</html>
