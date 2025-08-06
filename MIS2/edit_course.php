<?php
include('php/config.php');
session_start();
if ($_SESSION['role'] !== 'admin') exit("Unauthorized access.");

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM courses WHERE id = $id");
$course = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $schedule = $_POST['schedule'];
    $conn->query("UPDATE courses SET title='$title', description='$desc', schedule='$schedule' WHERE id = $id");
    header("Location: admin_manage_courses.php");
}
?>

<h2>Edit Course</h2>
<form method="post">
  <input name="title" value="<?= $course['title'] ?>" required><br>
  <textarea name="description" required><?= $course['description'] ?></textarea><br>
  <input name="schedule" value="<?= $course['schedule'] ?>" required><br>
  <button type="submit">Update</button>
</form>
