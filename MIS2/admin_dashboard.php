<?php
session_start();
include(__DIR__ . '/config.php'); 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard - Anurag Engineering College MIS</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background: #f4f4f4;
    }
    h1 {
      color: #333;
    }
    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    .card {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      text-align: center;
    }
    .card a, .card button {
      display: inline-block;
      margin-top: 10px;
      text-decoration: none;
      color: white;
      background: #007BFF;
      padding: 10px 15px;
      border-radius: 5px;
      border: none;
      cursor: pointer;
    }
    .card button:hover, .card a:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

  <h1>Welcome Admin 👨‍💼</h1>
  <p>Manage all key aspects of Anurag Engineering College's MIS</p>

  <div class="dashboard">
    <div class="card">
      <h3>Manage Students</h3>
      <a href="admin_manage_students.php">Go</a>
    </div>

    <div class="card">
      <h3>Manage Faculty</h3>
      <a href="admin_manage_faculty.php">Go</a>
    </div>

    <div class="card">
      <h3>Manage Courses</h3>
      <a href="admin_manage_courses.php">Go</a>
    </div>

    <div class="card">
      <h3>Manage Exams</h3>
      <a href="admin_manage_exams.php">Go</a>
    </div>

    <div class="card">
      <h3>Send Notifications</h3>
      <a href="admin_notifications.php">Go</a>
    </div>

    <div class="card">
      <h3>Download Report</h3>
      <a href="export_report.php">📄 Download Report CSV</a>
    </div>
  </div>

</body>
</html>
