<?php
include(__DIR__ . '/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $employee_id = $_POST['employee_id'];
    $department = $_POST['department'];
    $role = 'faculty';

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, roll_number, course) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $password, $role, $employee_id, $department);

    if ($stmt->execute()) {
        header("Location: login_faculty.php");
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
  <title>Faculty Register</title>
  <link rel="stylesheet" href="auth-style.css">
</head>
<body>
  <div class="container">
    <div class="left">
      <h2>Welcome Faculty</h2>
      <p>Create your faculty account to manage courses and students.</p>
      <a href="login_faculty.php">Already have an account?</a>
    </div>
    <div class="right">
      <form method="post">
        <h2>Register</h2>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="employee_id" placeholder="Employee ID" required>
        <input type="text" name="department" placeholder="Department (e.g. CSE)" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create account</button>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      </form>
    </div>
  </div>
</body>
</html>
