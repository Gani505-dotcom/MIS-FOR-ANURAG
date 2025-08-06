<?php
include('php/config.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "No exam ID provided.";
    exit();
}

$exam_id = $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $conn->prepare("UPDATE exams SET subject=?, date=?, time=? WHERE id=?");
    $stmt->bind_param("sssi", $subject, $date, $time, $exam_id);

    if ($stmt->execute()) {
        echo "<script>alert('Exam updated successfully.'); window.location.href='admin_manage_exams.php';</script>";
    } else {
        echo "Error updating exam: " . $conn->error;
    }
}

// Fetch existing exam data
$stmt = $conn->prepare("SELECT * FROM exams WHERE id=?");
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();
$exam = $result->fetch_assoc();

if (!$exam) {
    echo "Exam not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Exam</title>
</head>
<body>
    <h2>Edit Exam</h2>
    <form method="POST">
        <label>Subject:</label><br>
        <input type="text" name="subject" value="<?= htmlspecialchars($exam['subject']) ?>" required><br><br>

        <label>Date:</label><br>
        <input type="date" name="date" value="<?= $exam['date'] ?>" required><br><br>

        <label>Time:</label><br>
        <input type="time" name="time" value="<?= $exam['time'] ?>" required><br><br>

        <input type="submit" value="Update Exam">
        <a href="admin_manage_exams.php"><button type="button">Cancel</button></a>
    </form>
</body>
</html>
