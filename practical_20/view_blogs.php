<?php
    include "check_connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs | View</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>All Blogs</h1>
    
    <a href="pages/index.html">Back to Home</a><br><br>

    <?php
        // Fetch all blogs
        $SELECT_QUERY = "SELECT id, title, content FROM blogs";
        $result = mysqli_query($conn, $SELECT_QUERY);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Title</th>";
            echo "<th>Content</th>";
            echo "</tr>";
            
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                echo "<td>" . nl2br(htmlspecialchars($row["content"])) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No blogs found in the database.</p>";
        }
    ?>
</body>
</html>