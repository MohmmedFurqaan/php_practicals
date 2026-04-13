<?php
include 'db.php';

// ADD TASK
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    $sql = "INSERT INTO tasks (title, priority, deadline) 
            VALUES ('$title', '$priority', '$deadline')";
    $conn->query($sql);
}

// DELETE TASK
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM tasks WHERE id=$id");
}

// MARK AS COMPLETED
if (isset($_GET['complete'])) {
    $id = $_GET['complete'];
    $conn->query("UPDATE tasks SET status='Completed' WHERE id=$id");
}

// FETCH SINGLE TASK FOR EDIT
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $resultEdit = $conn->query("SELECT * FROM tasks WHERE id=$id");
    $editData = $resultEdit->fetch_assoc();
}

// UPDATE TASK
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    $sql = "UPDATE tasks 
            SET title='$title', priority='$priority', deadline='$deadline' 
            WHERE id=$id";
    $conn->query($sql);

    // Reset edit mode
    header("Location: index.php");
}

// FETCH TASKS
$result = $conn->query("SELECT * FROM tasks ORDER BY deadline ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student To-Do List</title>
</head>
<body>

<h2><?php echo $editData ? "Edit Task" : "Add Task"; ?></h2>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">

    Task:
    <input type="text" name="title"
        value="<?php echo $editData['title'] ?? ''; ?>" required><br><br>

    Priority:
    <select name="priority">
        <option <?php if(($editData['priority'] ?? '')=='Low') echo 'selected'; ?>>Low</option>
        <option <?php if(($editData['priority'] ?? '')=='Medium') echo 'selected'; ?>>Medium</option>
        <option <?php if(($editData['priority'] ?? '')=='High') echo 'selected'; ?>>High</option>
    </select><br><br>

    Deadline:
    <input type="date" name="deadline"
        value="<?php echo $editData['deadline'] ?? ''; ?>" required><br><br>

    <?php if ($editData) { ?>
        <button type="submit" name="update">Update Task</button>
    <?php } else { ?>
        <button type="submit" name="add">Add Task</button>
    <?php } ?>
</form>

<hr>

<h2>Task List</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Task</th>
    <th>Priority</th>
    <th>Deadline</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['title']; ?></td>
    <td><?php echo $row['priority']; ?></td>
    <td><?php echo $row['deadline']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
        <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
        <a href="?delete=<?php echo $row['id']; ?>">Delete</a> |

        <?php if ($row['status'] == 'Pending') { ?>
            <a href="?complete=<?php echo $row['id']; ?>">Mark Done</a>
        <?php } ?>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>