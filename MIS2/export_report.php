<?php
include('php/config.php');
session_start();

if ($_SESSION['role'] !== 'admin') exit("Unauthorized access.");

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=performance_report.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['Student Name', 'Roll No', 'Course', 'Grade', 'Attendance (%)']);

$query = "
    SELECT u.name, u.roll_no, c.title AS course, g.grade,
           (SELECT ROUND(100 * SUM(status = 'Present') / COUNT(*), 2) 
            FROM attendance a 
            WHERE a.student_id = u.id AND a.course_id = c.id) AS attendance_percentage
    FROM grades g 
    JOIN users u ON g.student_id = u.id 
    JOIN courses c ON g.course_id = c.id
";

$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['name'], $row['roll_no'], $row['course'], $row['grade'], $row['attendance_percentage'] . "%"]);
}

fclose($output);
exit;
