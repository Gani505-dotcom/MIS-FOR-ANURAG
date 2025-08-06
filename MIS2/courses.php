<?php
include(__DIR__ . '/config.php'); // Use this if config.php is not in /php
session_start();

$result = $conn->query("SELECT c.*, u.name AS faculty_name 
                        FROM courses c 
                        LEFT JOIN users u ON c.faculty_id = u.id");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Courses</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      padding: 20px;
    }
    h2 {
      color: #004080;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
    }
    th {
      background-color: #004080;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>
<h2>Courses Offered</h2>
<table>
  <tr>
    <th>Course Title</th>
    <th>Description</th>
    <th>Faculty</th>
    <th>Schedule</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td><?= htmlspecialchars($row['description']) ?></td>
      <td><?= htmlspecialchars($row['faculty_name']) ?></td>
      <td><?= htmlspecialchars($row['schedule']) ?></td>
    </tr>
  <?php endwhile; ?>
</table>
</body>
</html>
