<?php

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if file was uploaded
        if(isset($_FILES["file"])) {
            $file = $_FILES["file"]["name"];
            $file_tmp = $_FILES["file"]["tmp_name"];
            $file_size = $_FILES["file"]["size"];
            $file_type = $_FILES["file"]["type"];
            
            $TARGET_DIR = "uploads/";

            // Create uploads directory if it doesn't exist
            if(!is_dir($TARGET_DIR)) {
                mkdir($TARGET_DIR);
            }

            // extract the file name and put it to the $TARGET_DIR inside upload folder
            $FILE_DIRECTORY_UPLOAD = $TARGET_DIR.basename($file);

            // fetch the extension of the file
            $file_extension = strtolower(pathinfo($FILE_DIRECTORY_UPLOAD, PATHINFO_EXTENSION));

            // Allowed image extensions
            $allowed_extensions = array("jpg", "jpeg", "png", "gif", "bmp", "webp");

            // Check if uploaded file is an image
            if(in_array($file_extension, $allowed_extensions)) {
                
                // Move the uploaded file to the target directory
                if(move_uploaded_file($file_tmp, $FILE_DIRECTORY_UPLOAD)) {
                    
                    // Get image dimensions
                    $image_info = getimagesize($FILE_DIRECTORY_UPLOAD);
                    
                
                    
                    // Display file information
                    echo "<h3>File Upload Successful!</h3>";
                    echo "<p><strong>File Name:</strong> " . htmlspecialchars($file) . "</p>";
                    echo "<p><strong>File Extension:</strong> " . $file_extension . "</p>";

                    echo "<p><strong>File Type:</strong> " . $file_type . "</p>";

                    echo "<hr>";
                    echo "<h4>Thumbnail:</h4>";
                    echo "<img src='" . $FILE_DIRECTORY_UPLOAD . "' style='max-width: 150px;'>";
                    
                } else {
                    echo "<p style='color: red;'>Error uploading file!</p>";
                }
                
            } else {
                echo "<p style='color: red;'><strong>Upload only image file!</strong></p>";
                echo "<p>Allowed formats: " . implode(", ", $allowed_extensions) . "</p>";
            }
            
        } else {
            echo "No file was uploaded!";
        }
    }

    
?>