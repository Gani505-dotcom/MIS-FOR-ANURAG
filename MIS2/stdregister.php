<?php
include(__DIR__ . '/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $roll_number = $_POST['roll_number'];
    $course = $_POST['course'];
    $role = 'student';

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, roll_number, course) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $password, $role, $roll_number, $course);

    if ($stmt->execute()) {
        header("Location: stdlogin.php");
        exit();
    } else {
        $error = "Registration failed. Try again!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Register</title>
  <link rel="stylesheet" href="auth-style.css">
</head>
<body>
  <div class="container">
    <div class="left">
      <h2>Join the world of creators.</h2>
      <p>Create your student account now.</p>
      <a href="stdlogin.php">Already have an account?</a>
    </div>
    <div class="right">
      <form method="post">
        <h2>Register</h2>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="roll_number" placeholder="Roll Number" required>
        <input type="text" name="course" placeholder="Course (e.g. CSE)" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create account</button>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      </form>
    </div>
  </div>
</body>
</html>
