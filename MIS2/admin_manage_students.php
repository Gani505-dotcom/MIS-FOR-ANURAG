
<?php
include(__DIR__ . '/config.php'); 
session_start();
if ($_SESSION['role'] !== 'admin') exit("Unauthorized access.");

$message = "";

// Handle deletion
if (isset($_GET['delete'])) {
    $student_id = intval($_GET['delete']);
    
    // Delete related data first
    $conn->query("DELETE FROM attendance WHERE student_id = $student_id");
    $conn->query("DELETE FROM grades WHERE student_id = $student_id");
    $conn->query("DELETE FROM enrollments WHERE student_id = $student_id");
    $conn->query("DELETE FROM users WHERE id = $student_id");
    
    $message = "Student removed successfully.";
}

// Fetch all students
$students = $conn->query("SELECT * FROM users WHERE role = 'student'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; background-color: #f5f5f5; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
        th { background-color: #004080; color: white; }
        .btn-danger {
            background-color: #e74c3c;
            border: none;
            padding: 8px 14px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        .message { color: green; font-weight: bold; margin-bottom: 10px; }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 100px;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: auto;
            padding: 20px;
            width: 400px;
            border-radius: 8px;
            text-align: center;
        }
        .modal-buttons button {
            margin: 10px;
            padding: 8px 14px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<h2>Manage Students</h2>

<?php if (!empty($message)): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<table>
    <tr>
        <th>Name</th>
        <th>Roll No</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php while ($s = $students->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($s['name']) ?></td>
        <td><?= htmlspecialchars($s['roll_number']) ?></td>
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td>
            <button class="btn-danger" onclick="confirmDelete(<?= $s['id'] ?>, '<?= htmlspecialchars($s['name']) ?>')">Remove</button>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<!-- Confirmation Modal -->
<div id="confirmModal" class="modal">
  <div class="modal-content">
    <p id="confirmText">Are you sure you want to delete this student?</p>
    <div class="modal-buttons">
        <button onclick="proceedDelete()" class="btn-danger">Yes, Delete</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
  </div>
</div>

<script>
let deleteId = null;

function confirmDelete(id, name) {
    deleteId = id;
    document.getElementById("confirmText").innerText = `Are you sure you want to delete "${name}"?`;
    document.getElementById("confirmModal").style.display = "block";
}

function closeModal() {
    document.getElementById("confirmModal").style.display = "none";
    deleteId = null;
}

function proceedDelete() {
    if (deleteId !== null) {
        window.location.href = "admin_manage_students.php?delete=" + deleteId;
    }
}
</script>

</body>
</html>

