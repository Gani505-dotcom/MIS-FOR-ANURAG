<?php
session_start();
include(__DIR__ . '/config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    header("Location: faculty_login.php");
    exit();
}

$course_id = $_GET['course_id'];
$students = $conn->query("SELECT * FROM users WHERE role = 'student'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    foreach ($_POST['attendance'] as $student_id => $status) {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, course_id, date, status) 
            VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE status = ?");
        $stmt->bind_param("iisss", $student_id, $course_id, $date, $status, $status);
        $stmt->execute();
    }
    echo "Attendance recorded successfully!";
}
?>

<form method="post">
    <input type="date" name="date" required>
    <table>
        <tr><th>Student Name</th><th>Present</th></tr>
        <?php while ($s = $students->fetch_assoc()): ?>
            <tr>
                <td><?= $s['name'] ?></td>
                <td>
                    <select name="attendance[<?= $s['id'] ?>]">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                    </select>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <button type="submit">Submit Attendance</button>
</form>
