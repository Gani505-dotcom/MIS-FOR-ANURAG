<?php
include(__DIR__ . '/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'admin';

    // Insert into users table (no roll_number or course needed for admin)
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        header("Location: login_admin.php");
        exit();
    } else {
        $error = "Registration failed. Please try again!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Register</title>
  <link rel="stylesheet" href="auth-style.css">
</head>
<body>
  <div class="container">
    <div class="left">
      <h2>Welcome Admin</h2>
      <p>Create your admin account to manage the MIS system.</p>
      <a href="login_admin.php">Already have an account?</a>
    </div>
    <div class="right">
      <form method="post">
        <h2>Register</h2>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create account</button>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      </form>
    </div>
  </div>
</body>
</html>
