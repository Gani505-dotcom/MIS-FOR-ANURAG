<?php
session_start();
include(__DIR__ . '/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No admin found with this email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="auth-style.css">
</head>
<body>
  <div class="container">
    <div class="left">
      <h2>Admin Portal</h2>
      <p>Log in to manage the MIS system.</p>
      <a href="admin_register.php">Create an admin account</a>
    </div>
    <div class="right">
      <form method="post">
        <h2>Admin Login</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      </form>
    </div>
  </div>
</body>
</html>
