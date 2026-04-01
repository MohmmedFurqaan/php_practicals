<?php
$errors = [];
$name = '';
$email = '';
$gender = '';
$website = '';
$aboutyou = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $gender = isset($_POST['gender']) ? htmlspecialchars(trim($_POST['gender'])) : '';
    $website = isset($_POST['website']) ? htmlspecialchars(trim($_POST['website'])) : '';
    $aboutyou = isset($_POST['aboutyou']) ? htmlspecialchars(trim($_POST['aboutyou'])) : '';
    
    // Basic server-side validation
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($gender)) {
        $errors[] = "Gender is required";
    }
    
    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $errors[] = "Website URL must be valid";
    }
} else {
    header('Location: form.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Submission Success</title>
    <style>
        body {
            padding: 20px;
            background-color: #f5f5f5;
        }

        .success-container {
            background: white;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
        }

        .success-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .success-header h2 {
            margin: 0;
            color: #28a745;
        }

        .info-card {
            margin-bottom: 15px;
            padding: 10px;
            border-left: 3px solid #007bff;
            background: #f9f9f9;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        .info-value {
            margin-top: 5px;
            color: #555;
        }

        .empty-field {
            color: #aaa;
            font-style: italic;
        }

        .action-buttons {
            text-align: center;
            margin-top: 25px;
        }

        .btn-custom {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-custom:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>

<body>

    <div class="success-container">
        <?php if (!empty($errors)): ?>
            <div class="success-header">
                <h2 style="color: #dc3545;">Registration Failed</h2>
            </div>
            
            <div class="info-section">
                <div class="info-card" style="border-left-color: #dc3545; background-color: #f8d7da;">
                    <div class="info-label" style="color: #721c24;">Please fix the following errors:</div>
                    <ul style="color: #721c24; margin-top: 10px; margin-bottom: 0;">
                        <?php foreach($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <div class="action-buttons">
                <button onclick="history.back()" class="btn-custom" style="background-color: #6c757d;">← Go Back</button>
            </div>
        <?php else: ?>
            <div class="success-header">
                <h2>Registration Successful!</h2>
            </div>

            <div class="info-section">
                <div class="info-card">
                    <div class="info-label">Name</div>
                    <div class="info-value"><?php echo $name; ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo $email; ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">Gender</div>
                    <div class="info-value"><?php echo $gender; ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">Website URL (Optional)</div>
                    <div class="info-value <?php echo empty($website) ? 'empty-field' : ''; ?>">
                        <?php echo empty($website) ? 'Not provided' : $website; ?>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">About You (Optional)</div>
                    <div class="info-value <?php echo empty($aboutyou) ? 'empty-field' : ''; ?>">
                        <?php echo empty($aboutyou) ? 'Not provided' : nl2br($aboutyou); ?>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="form.html" class="btn-custom">← Back to Form</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

</html>
