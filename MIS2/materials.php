<?php
session_start();
include('php/config.php');
if ($_SESSION['role'] !== 'student') exit("Unauthorized access.");

$student_id = $_SESSION['user_id'];

$query = "
    SELECT m.*, c.title as course_title 
    FROM materials m 
    JOIN courses c ON m.course_id = c.id
    JOIN enrollments e ON c.id = e.course_id
    WHERE e.student_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Course Materials</h2>
<table border="1">
  <tr><th>Course</th><th>Material</th><th>Download</th></tr>
  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['course_title'] ?></td>
      <td><?= $row['title'] ?></td>
      <td><a href="<?= $row['file_path'] ?>" download>Download</a></td>
    </tr>
  <?php endwhile; ?>
</table>
