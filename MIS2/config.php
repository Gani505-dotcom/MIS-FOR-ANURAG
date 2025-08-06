<?php
$servername = "localhost";
$username = "root";
$password = ""; // default for XAMPP
$database = "aec_mis"; // replace with your actual database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
