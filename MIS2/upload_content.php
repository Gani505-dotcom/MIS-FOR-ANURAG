
<?php
session_start();
include(__DIR__ . '/config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    header("Location: faculty_login.php");
    exit();
}

$course_id = $_GET['course_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $file = $_FILES['material'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/materials/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $filename = basename($file['name']);
        $filepath = $upload_dir . $filename;
        move_uploaded_file($file['tmp_name'], $filepath);

        $stmt = $conn->prepare("INSERT INTO materials (course_id, title, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $course_id, $title, $filepath);
        $stmt->execute();
        $success = "Material uploaded successfully!";
    } else {
        $error = "File upload failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Course Material</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f4f8;
            padding: 40px;
            display: flex;
            justify-content: center;
        }
        .upload-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            color: #004080;
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #004080;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #0059b3;
        }
        .message {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="upload-box">
    <h2>Upload Course Material</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Material Title" required>
        <input type="file" name="material" required>
        <button type="submit">Upload</button>
    </form>
    
    <?php if (!empty($success)) echo "<p class='message'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>
</div>

</body>
</html>
