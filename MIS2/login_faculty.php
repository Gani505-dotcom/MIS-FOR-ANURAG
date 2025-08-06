<?php
session_start();
include(__DIR__ . '/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check user by email and role
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'faculty'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: faculty_dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Faculty account not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Faculty Login</title>
  <link rel="stylesheet" href="auth-style.css">
</head>
<body>
  <div class="container">
    <div class="left">
      <h2>Welcome Back, Faculty!</h2>
      <p>Login to manage your courses and students.</p>
      <a href="faculty_register.php">Don't have an account?</a>
    </div>
    <div class="right">
      <form method="post">
        <h2>Login</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      </form>
    </div>
  </div>
</body>
</html>
