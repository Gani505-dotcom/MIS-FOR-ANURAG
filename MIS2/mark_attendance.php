<?php
session_start();
include('php/config.php');
if ($_SESSION['role'] !== 'faculty') exit("Unauthorized access.");

$course_id = $_GET['course_id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    foreach ($_POST['attendance'] as $student_id => $status) {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, course_id, date, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $student_id, $course_id, $date, $status);
        $stmt->execute();
    }
    echo "Attendance recorded.";
}

$students = $conn->query("
  SELECT u.id, u.name 
  FROM enrollments e 
  JOIN users u ON e.student_id = u.id 
  WHERE e.course_id = $course_id
");
?>

<h2>Mark Attendance</h2>
<form method="post">
  <input type="date" name="date" required><br><br>
  <table border="1">
    <tr><th>Name</th><th>Status</th></tr>
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
  <br>
  <button type="submit">Submit</button>
</form>
