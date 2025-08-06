<?php
session_start();
include('config.php');
if ($_SESSION['role'] != 'admin') exit("Unauthorized");

$students = $conn->query("SELECT * FROM users WHERE role='student'");
$faculty = $conn->query("SELECT * FROM users WHERE role='faculty'");

echo "<h2>Student Accounts</h2>";
while ($s = $students->fetch_assoc()) echo "<p>{$s['name']} - {$s['email']}</p>";

echo "<h2>Faculty Accounts</h2>";
while ($f = $faculty->fetch_assoc()) echo "<p>{$f['name']} - {$f['email']}</p>";
