<?php
include(__DIR__ . '/config.php'); // Adjust path if needed
session_start();

$today = date('Y-m-d');
$upcoming = $conn->query("SELECT e.*, c.title FROM exams e 
                          JOIN courses c ON e.course_id = c.id 
                          WHERE e.exam_date >= '$today' 
                          ORDER BY e.exam_date ASC");

$past = $conn->query("SELECT e.*, c.title FROM exams e 
                      JOIN courses c ON e.course_id = c.id 
                      WHERE e.exam_date < '$today' 
                      ORDER BY e.exam_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exams - Anurag MIS</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f9;
      color: #333;
      padding: 20px;
      margin: 0;
    }

    h2 {
      color: #004080;
      border-left: 5px solid #004080;
      padding-left: 10px;
      margin-top: 40px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: white;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
    }

    th {
      background-color: #004080;
      color: white;
      font-weight: bold;
    }

    tr:nth-child(even) {
      background-color: #f0f4fa;
    }

    tr:hover {
      background-color: #d6e4f0;
    }

    @media (max-width: 600px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      th {
        position: absolute;
        top: -9999px;
        left: -9999px;
      }

      td {
        position: relative;
        padding-left: 50%;
        border: none;
        border-bottom: 1px solid #ccc;
      }

      td:before {
        position: absolute;
        left: 10px;
        top: 12px;
        white-space: nowrap;
        font-weight: bold;
        color: #004080;
      }

      td:nth-of-type(1):before { content: "Course"; }
      td:nth-of-type(2):before { content: "Date"; }
      td:nth-of-type(3):before { content: "Time"; }
    }
  </style>
</head>
<body>

<h2>📅 Upcoming Exams</h2>
<?php if ($upcoming->num_rows > 0): ?>
<table>
  <tr><th>Course</th><th>Date</th><th>Time</th></tr>
  <?php while ($row = $upcoming->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td><?= htmlspecialchars($row['exam_date']) ?></td>
      <td><?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></td>
    </tr>
  <?php endwhile; ?>
</table>
<?php else: ?>
  <p>No upcoming exams.</p>
<?php endif; ?>

<h2>🕓 Past Exams</h2>
<?php if ($past->num_rows > 0): ?>
<table>
  <tr><th>Course</th><th>Date</th><th>Time</th></tr>
  <?php while ($row = $past->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td><?= htmlspecialchars($row['exam_date']) ?></td>
      <td><?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></td>
    </tr>
  <?php endwhile; ?>
</table>
<?php else: ?>
  <p>No past exams.</p>
<?php endif; ?>

</body>
</html>
