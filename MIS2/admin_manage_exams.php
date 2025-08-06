<?php
include(__DIR__ . '/config.php'); 
session_start();
if ($_SESSION['role'] !== 'admin') exit("Unauthorized access.");

$exams = $conn->query("SELECT * FROM exams");
?>

<h2>Manage Exams</h2>
<table border="1">
  <tr><th>Subject</th><th>Date</th><th>Time</th><th>Actions</th></tr>
  <?php while ($e = $exams->fetch_assoc()): ?>
    <tr>
      <td><?= $e['subject'] ?></td>
      <td><?= $e['date'] ?></td>
      <td><?= $e['time'] ?></td>
      <td>
        <a href="edit_exam.php?id=<?= $e['id'] ?>">Edit</a> |
        <a href="?delete=<?= $e['id'] ?>" onclick="return confirm('Delete this exam?')">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
