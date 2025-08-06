<?php
session_start();
include('php/config.php');
if ($_SESSION['role'] !== 'student') exit("Unauthorized access.");

$student_id = $_SESSION['user_id'];

$result = $conn->query("
    SELECT c.title AS course_title, a.date, a.status 
    FROM attendance a 
    JOIN courses c ON a.course_id = c.id 
    WHERE a.student_id = $student_id 
    ORDER BY a.date DESC
");
?>

<h2>Your Attendance</h2>
<table border="1">
  <tr><th>Course</th><th>Date</th><th>Status</th></tr>
  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['course_title'] ?></td>
      <td><?= $row['date'] ?></td>
      <td><?= $row['status'] ?></td>
    </tr>
  <?php endwhile; ?>
</table>
