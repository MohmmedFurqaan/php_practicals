<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $feedback = $_POST["feedback"];

    if (!empty($name) && !empty($email) && !empty($feedback)) {

        $file = "feedback.txt";

        // Format the entry
        $entry  = "----------------------------\n";
        $entry .= "Date: " . date("Y-m-d H:i:s") . "\n";
        $entry .= "Name: $name\n";
        $entry .= "Email: $email\n";
        $entry .= "Feedback:\n$feedback\n\n";

        // file_put_contents() with FILE_APPEND keeps existing content
        // Creates feedback.txt automatically if it does not exist
        if (file_put_contents($file, $entry, FILE_APPEND | LOCK_EX) !== false) {
            $message = "Thank you! Your feedback has been submitted.";
        } else {
            $message = "Error saving feedback. Please try again.";
        }

    } else {
        $message = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <title>Feedback Result</title>
</head>
<body>
  <h2>Feedback Status</h2>
  <p><?php echo $message; ?></p>
  <a href="file_input.html">Go Back</a>

  <?php
  // Display saved feedback using fopen / fgets / feof / fclose
  $file = "feedback.txt";

  if (file_exists($file)) {
      echo "<h3>Saved Feedback (read using fgets):</h3><pre>";

      $handle = fopen($file, "r");          // open file for reading
      if ($handle) {
          while (!feof($handle)) {          // feof() checks end-of-file
              echo htmlspecialchars(fgets($handle)); // fgets() reads one line
          }
          fclose($handle);                  // fclose() closes the file
      }
      echo "</pre>";
  }
  ?>
</body>
</html>