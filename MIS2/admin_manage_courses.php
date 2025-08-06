<?php
include(__DIR__ . '/config.php'); 
session_start();
if ($_SESSION['role'] !== 'admin') exit("Unauthorized access.");

$courses = $conn->query("SELECT * FROM courses");
?>

<h2>Manage Courses</h2>
<table border="1">
  <tr><th>Title</th><th>Description</th><th>Schedule</th><th>Actions</th></tr>
  <?php while ($c = $courses->fetch_assoc()): ?>
    <tr>
      <td><?= $c['title'] ?></td>
      <td><?= $c['description'] ?></td>
      <td><?= $c['schedule'] ?></td>
      <td>
        <a href="edit_course.php?id=<?= $c['id'] ?>">Edit</a> |
        <a href="?delete=<?= $c['id'] ?>" onclick="return confirm('Delete this course?')">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
