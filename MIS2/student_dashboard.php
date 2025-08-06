<?php
session_start();
include(__DIR__ . '/config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login_student.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch student info
$info = $conn->query("SELECT * FROM users WHERE id = $student_id")->fetch_assoc();

// Fetch grades & attendance
$performance = $conn->query("
    SELECT c.title AS course, g.grade, a.attendance_percentage 
    FROM courses c 
    LEFT JOIN grades g ON c.id = g.course_id AND g.student_id = $student_id
    LEFT JOIN attendance a ON c.id = a.course_id AND a.student_id = $student_id
");

// Fetch course materials
$materials = $conn->query("
    SELECT * FROM materials WHERE student_id = $student_id OR student_id IS NULL
");

// Fetch exams
$exams = $conn->query("
    SELECT c.title, e.exam_date, e.start_time, e.end_time 
    FROM exams e 
    JOIN courses c ON e.course_id = c.id 
    WHERE e.exam_date >= CURDATE()
    ORDER BY e.exam_date ASC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 30px; }
    h2 { color: #004080; }
    section { margin-bottom: 30px; background: white; padding: 20px; border-radius: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 10px; }
    th { background-color: #004080; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
  </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($info['name']) ?></h2>

<section>
  <h3>Personal Information</h3>
  <!-- <p><strong>Roll Number:</strong> <?= htmlspecialchars($info['username']) ?></p> -->
  <p><strong>Roll Number:</strong> <?= htmlspecialchars($info['roll_number']) ?></p>

  <p><strong>Email:</strong> <?= htmlspecialchars($info['email']) ?></p>
</section>

<section>
  <h3>Grades & Attendance</h3>
  <table>
    <tr><th>Course</th><th>Grade</th><th>Attendance (%)</th></tr>
    <?php while ($row = $performance->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['course']) ?></td>
        <td><?= htmlspecialchars($row['grade'] ?? 'N/A') ?></td>
        <td><?= htmlspecialchars($row['attendance_percentage'] ?? 'N/A') ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</section>

<section>
  <h3>Course Materials</h3>
  <ul>
    <?php while ($row = $materials->fetch_assoc()): ?>
      <li><a href="uploads/<?= htmlspecialchars($row['filename']) ?>" download><?= htmlspecialchars($row['title']) ?></a></li>
    <?php endwhile; ?>
  </ul>
</section>

<section>
  <h3>Upcoming Exams</h3>
  <table>
    <tr><th>Course</th><th>Date</th><th>Time</th></tr>
    <?php while ($row = $exams->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['exam_date']) ?></td>
        <td><?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</section>

</body>
</html>
