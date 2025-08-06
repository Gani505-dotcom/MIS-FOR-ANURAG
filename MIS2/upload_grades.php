<?php
session_start();
include(__DIR__ . '/config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    header("Location: faculty_login.php");
    exit();
}

$course_id = $_GET['course_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $grade = $_POST['grade'];

    // Insert or update grade
    $stmt = $conn->prepare("REPLACE INTO grades (student_id, course_id, grade) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $student_id, $course_id, $grade);
    if ($stmt->execute()) {
        $success = "Grade uploaded successfully!";
    } else {
        $error = "Failed to upload grade.";
    }
}

// Fetch students enrolled in the course
$students = $conn->query("
    SELECT u.id, u.name, u.roll_number 
    FROM users u 
    JOIN enrollments e ON u.id = e.student_id 
    WHERE e.course_id = $course_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Grades</title>
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
        select,
        input[type="text"] {
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
    <h2>Upload Grades</h2>
    <form method="post">
        <select name="student_id" required>
            <option value="">Select Student</option>
            <?php while ($student = $students->fetch_assoc()): ?>
                <option value="<?= $student['id'] ?>">
                    <?= htmlspecialchars($student['name']) ?> (<?= $student['roll_number'] ?>)
                </option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="grade" placeholder="Enter Grade (e.g., A+, 95)" required>
        <button type="submit">Submit Grade</button>
    </form>

    <?php if (!empty($success)) echo "<p class='message'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>
</div>

</body>
</html>
