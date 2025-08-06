<?php
include('php/config.php');
session_start();
$query = $_GET['q'] ?? '';

$courses = $conn->query("SELECT * FROM courses WHERE title LIKE '%$query%' OR description LIKE '%$query%'");
$grades = $conn->query("SELECT g.*, u.name, c.title 
                        FROM grades g 
                        JOIN users u ON g.student_id = u.id 
                        JOIN courses c ON g.course_id = c.id 
                        WHERE u.name LIKE '%$query%' OR c.title LIKE '%$query%'");
$events = $conn->query("SELECT * FROM notifications WHERE title LIKE '%$query%' OR message LIKE '%$query%'");
?>

<h2>Search Results for: "<?= htmlspecialchars($query) ?>"</h2>

<h3>Courses</h3>
<ul>
  <?php while ($row = $courses->fetch_assoc()): ?>
    <li><?= $row['title'] ?> – <?= $row['description'] ?></li>
  <?php endwhile; ?>
</ul>

<h3>Grades</h3>
<ul>
  <?php while ($row = $grades->fetch_assoc()): ?>
    <li><?= $row['name'] ?> – <?= $row['title'] ?> – Grade: <?= $row['grade'] ?></li>
  <?php endwhile; ?>
</ul>

<h3>Events/Notifications</h3>
<ul>
  <?php while ($row = $events->fetch_assoc()): ?>
    <li><strong><?= $row['title'] ?>:</strong> <?= $row['message'] ?></li>
  <?php endwhile; ?>
</ul>
