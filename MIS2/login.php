<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['role'] = $user['role'];
      if ($user['role'] == 'student') header("Location: student.php");
      elseif ($user['role'] == 'faculty') header("Location: faculty.php");
      else header("Location: admin.php");
    } else {
      echo "Invalid credentials.";
    }
  } else {
    echo "No user found.";
  }
}
?>
<form method="POST">
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Login</button>
</form>
