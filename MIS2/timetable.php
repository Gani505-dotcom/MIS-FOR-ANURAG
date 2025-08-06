<?php
// Sample static timetable (you can later pull this from the database if needed)
$timetable = [
  'Monday'    => ['10:00 AM - 11:00 AM' => 'Mathematics',     '11:00 AM - 12:00 PM' => 'Physics'],
  'Tuesday'   => ['10:00 AM - 11:00 AM' => 'Chemistry',       '11:00 AM - 12:00 PM' => 'English'],
  'Wednesday' => ['10:00 AM - 11:00 AM' => 'Computer Science','11:00 AM - 12:00 PM' => 'Mathematics'],
  'Thursday'  => ['10:00 AM - 11:00 AM' => 'Electronics',     '11:00 AM - 12:00 PM' => 'Physics'],
  'Friday'    => ['10:00 AM - 11:00 AM' => 'English',         '11:00 AM - 12:00 PM' => 'Chemistry'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Timetable - Anurag MIS</title>
  <link rel="stylesheet" href="style.css"> 
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      padding: 20px;
      margin: 0;
    }
    header {
      background-color: #004080;
      color: white;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }
    nav a {
      margin-right: 15px;
      color: white;
      text-decoration: none;
    }
    h2 {
      margin-top: 20px;
      color: #004080;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      background-color: white;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #004080;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .dark-mode {
      background-color: #121212;
      color: white;
    }
    .dark-mode table {
      background-color: #1e1e1e;
      color: white;
    }
    .dark-mode th {
      background-color: #333;
    }
    .dark-mode td {
      border-color: #444;
    }
    #darkToggle {
      background-color: #fff;
      border: none;
      padding: 8px 12px;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <header>
    <h1>Anurag Engineering College MIS</h1>
    <nav>
      <a href="index.html">Home</a>
      <a href="courses.php">Courses</a>
      <a href="exams.php">Exams</a>
      <a href="timetable.php">Timetable</a>
      <a href="notifications.php">Notifications</a>
      <a href="login.php">Login</a>
    </nav>
    <button id="darkToggle">🌙 Toggle Dark Mode</button>
  </header>

  <h2>📅 Weekly Class Timetable</h2>

  <table>
    <thead>
      <tr>
        <th>Day</th>
        <th>10:00 AM - 11:00 AM</th>
        <th>11:00 AM - 12:00 PM</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($timetable as $day => $slots): ?>
        <tr>
          <td><?= htmlspecialchars($day) ?></td>
          <td><?= htmlspecialchars($slots['10:00 AM - 11:00 AM']) ?></td>
          <td><?= htmlspecialchars($slots['11:00 AM - 12:00 PM']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script>
    const toggleBtn = document.getElementById('darkToggle');
    toggleBtn.addEventListener('click', () => {
      document.body.classList.toggle('dark-mode');
    });
  </script>
</body>
</html>
