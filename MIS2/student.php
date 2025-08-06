<?php
session_start();
include('config.php');
if ($_SESSION['role'] != 'student') exit("Unauthorized");

$user_id = $_SESSION['user_id'];

$info = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$grades = $conn->query("SELECT c.title, g.marks FROM grades g JOIN courses c ON g.course_id = c.id WHERE g.student_id = $user_id");
$attendance = $conn->query("SELECT c.title, a.date, a.status FROM attendance a JOIN courses c ON a.course_id = c.id WHERE a.student_id = $user_id");
?>
<h2>Welcome, <?= $info['name'] ?></h2>
<p>Roll No: <?= $info['roll_number'] ?></p>
<h3>Grades:</h3>
<ul><?php while ($g = $grades->fetch_assoc()) echo "<li>{$g['title']}: {$g['marks']}</li>"; ?></ul>

<h3>Attendance:</h3>
<ul><?php while ($a = $attendance->fetch_assoc()) echo "<li>{$a['title']} on {$a['date']}: {$a['status']}</li>"; ?></ul>
