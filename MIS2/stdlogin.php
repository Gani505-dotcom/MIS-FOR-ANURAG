<?php
session_start();
include(__DIR__ . '/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role='student'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['user_id'] = $student['id'];
        $_SESSION['role'] = $student['role'];
        $_SESSION['name'] = $student['name'];
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error = "Invalid student credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Login</title>
  <link rel="stylesheet" href="auth-style.css">
</head>
<body>
  <div class="container">
    <div class="left">
      <h2>Welcome Back!</h2>
      <p>Login to access your student dashboard.</p>
      <a href="stdregister.php">Don't have an account?</a>
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
