<?php
session_start();
include('config.php');
if ($_SESSION['role'] != 'faculty') exit("Unauthorized");

$faculty_id = $_SESSION['user_id'];
$courses = $conn->query("SELECT * FROM courses WHERE faculty_id = $faculty_id");

echo "<h2>Your Courses:</h2>";
while ($c = $courses->fetch_assoc()) {
  echo "<p>{$c['title']}</p>";
  echo "<a href='upload_materials.php?course_id={$c['id']}'>Upload Material</a><br>";
  echo "<a href='mark_attendance.php?course_id={$c['id']}'>Mark Attendance</a><br>";
}
