<?php
include('php/config.php');
session_start();

$role = $_SESSION['role'] ?? 'guest'; // fallback for non-logged-in users
$result = $conn->query("SELECT * FROM notifications 
                        WHERE target_role = 'all' OR target_role = '$role' 
                        ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Notifications</title></head>
<body>
<h2>Notifications</h2>
<ul>
  <?php while ($row = $result->fetch_assoc()): ?>
    <li><strong><?= $row['title'] ?></strong> – <?= $row['message'] ?> <em>(<?= $row['created_at'] ?>)</em></li>
  <?php endwhile; ?>
</ul>
</body>
</html>
