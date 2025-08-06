<?php
session_start();
include(__DIR__ . '/config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    header("Location: faculty_login.php");
    exit();
}

$faculty_id = $_SESSION['user_id'];
$faculty = $conn->query("SELECT * FROM users WHERE id = $faculty_id")->fetch_assoc();

// Fetch courses taught by faculty
$courses = $conn->query("SELECT * FROM courses WHERE faculty_id = $faculty_id");

// Fetch class schedules
// Fetch class schedules from courses table
$schedules = $conn->query("
    SELECT title, schedule 
    FROM courses 
    WHERE faculty_id = $faculty_id
");

// Fetch upcoming exams
$exams = $conn->query("
    SELECT c.title, e.exam_date, e.start_time, e.end_time 
    FROM exams e 
    JOIN courses c ON e.course_id = c.id 
    WHERE c.faculty_id = $faculty_id
    ORDER BY e.exam_date ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Dashboard</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 20px; }
        h2 { color: #004080; }
        section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border: 1px solid #ccc; }
        th { background: #004080; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        a.button { display: inline-block; padding: 8px 12px; background: #004080; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($faculty['name']) ?> (Faculty)</h2>

<section>
    <h3>Your Courses</h3>
    <ul>
        <?php while ($course = $courses->fetch_assoc()): ?>
            <li>
                <?= htmlspecialchars($course['title']) ?> -
                <a class="button" href="upload_content.php?course_id=<?= $course['id'] ?>">Upload Content</a>
                <a class="button" href="upload_grades.php?course_id=<?= $course['id'] ?>">Upload Grades</a>
                <a class="button" href="upload_assignments.php?course_id=<?= $course['id'] ?>">Upload Assignment</a>
                <a class="button" href="attendance.php?course_id=<?= $course['id'] ?>">Manage Attendance</a>
            </li>
        <?php endwhile; ?>
    </ul>
</section>
<section>
    <h3>Class Schedule</h3>
    <table>
        <tr><th>Course</th><th>Schedule</th></tr>
        <?php while ($row = $schedules->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['schedule']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>


<!-- <section>
    <h3>Class Schedule</h3>
    <table>
        <tr><th>Course</th><th>Day</th><th>Start Time</th><th>End Time</th></tr>
        <?php while ($row = $schedules->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['day']) ?></td>
                <td><?= htmlspecialchars($row['start_time']) ?></td>
                <td><?= htmlspecialchars($row['end_time']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</section> -->

<section>
    <h3>Upcoming Exams</h3>
    <table>
        <tr><th>Course</th><th>Date</th><th>Time</th></tr>
        <?php while ($row = $exams->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['exam_date']) ?></td>
                <td><?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

</body>
</html>
