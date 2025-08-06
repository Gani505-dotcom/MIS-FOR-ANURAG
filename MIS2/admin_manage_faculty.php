










<?php
// admin_manage_faculty.php
// include('db_connect.php');
include(__DIR__ . '/config.php'); 
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Faculty - Admin</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f6f9; }
        .container { max-width: 1200px; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background: #222; color: white; }
        input, select, button { padding: 8px; margin: 5px; }
        .actions button { margin-right: 5px; }

        /* Modal Styling */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                 background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 20px; border-radius: 5px; width: 500px; max-width: 90%; }
        .modal-header { font-size: 18px; margin-bottom: 10px; }
        .modal input, .modal select { width: 100%; margin-bottom: 10px; }
        .modal-buttons { text-align: right; }
        .modal-buttons button { margin-left: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Faculty</h2>

    <?php if (isset($_SESSION['msg'])): ?>
        <div style="padding: 10px; background: #dff0d8; border: 1px solid #3c763d; color: #3c763d;">
            <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
    <?php endif; ?>

    <!-- 🔍 Search/Filter Section -->
    <form method="GET">
        <input type="text" name="search" placeholder="Search by name or ID" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <select name="department">
            <option value="">All Departments</option>
            <option value="CSE" <?= ($_GET['department'] ?? '') == 'CSE' ? 'selected' : '' ?>>CSE</option>
            <option value="ECE" <?= ($_GET['department'] ?? '') == 'ECE' ? 'selected' : '' ?>>ECE</option>
            <!-- Add more departments -->
        </select>
        <button type="submit">Search</button>
    </form>

    <!-- 📋 Faculty Table -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Employee ID</th>
                <th>Department</th>
                <th>Email</th>
                <th>Courses</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $search = $_GET['search'] ?? '';
        $department = $_GET['department'] ?? '';

        $query = "SELECT * FROM faculty WHERE 1";
        if ($search) {
            $query .= " AND (name LIKE '%$search%' OR employee_id LIKE '%$search%')";
        }
        if ($department) {
            $query .= " AND department = '$department'";
        }

        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['employee_id']}</td>
                <td>{$row['department']}</td>
                <td>{$row['email']}</td>
                <td>{$row['assigned_courses']}</td>
                <td>" . ($row['status'] == 1 ? "Active" : "Inactive") . "</td>
                <td class='actions'>
                    <button onclick='openEditModal(" . json_encode($row) . ")'>Edit</button>
                    <button onclick='openDeleteModal({$row['id']})'>Delete</button>
                    <button onclick='toggleStatus({$row['id']}, {$row['status']})'>" . ($row['status'] ? "Deactivate" : "Activate") . "</button>
                    <button onclick='openAssignModal({$row['id']})'>Assign</button>
                    <button onclick='resetPassword({$row['id']})'>Reset Password</button>
                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- ✏️ Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Edit Faculty Info</div>
        <form method="POST" action="edit_faculty.php">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="name" id="edit_name" required>
            <input type="text" name="employee_id" id="edit_empid" required>
            <input type="email" name="email" id="edit_email" required>
            <select name="department" id="edit_department" required>
                <option value="">Select Department</option>
                <option value="CSE">CSE</option>
                <option value="ECE">ECE</option>
            </select>
            <div class="modal-buttons">
                <button type="button" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- 📘 Assign Courses Modal -->
<div id="assignModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Assign Courses</div>
        <form method="POST" action="assign_courses.php">
            <input type="hidden" name="faculty_id" id="assign_faculty_id">
            <label>Select Courses:</label>
            <select name="courses[]" multiple required>
                <option value="CSE101">CSE101 - Programming</option>
                <option value="ECE201">ECE201 - Circuits</option>
                <!-- Load courses from DB if needed -->
            </select>
            <div class="modal-buttons">
                <button type="button" onclick="closeModal('assignModal')">Cancel</button>
                <button type="submit">Assign</button>
            </div>
        </form>
    </div>
</div>

<!-- ❗ Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Confirm Delete</div>
        <p>Are you sure you want to delete this faculty member?</p>
        <div class="modal-buttons">
            <button type="button" onclick="closeModal('deleteModal')">Cancel</button>
            <a id="confirmDeleteBtn" href="#"><button>Delete</button></a>
        </div>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_empid').value = data.employee_id;
    document.getElementById('edit_email').value = data.email;
    document.getElementById('edit_department').value = data.department;
    document.getElementById('editModal').style.display = 'flex';
}

function openAssignModal(id) {
    document.getElementById('assign_faculty_id').value = id;
    document.getElementById('assignModal').style.display = 'flex';
}

function openDeleteModal(id) {
    document.getElementById('confirmDeleteBtn').href = 'delete_faculty.php?id=' + id;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function toggleStatus(id, currentStatus) {
    window.location.href = 'toggle_faculty_status.php?id=' + id + '&status=' + currentStatus;
}

function resetPassword(id) {
    if (confirm("Reset this faculty member’s password to default?")) {
        window.location.href = 'reset_faculty_password.php?id=' + id;
    }
}
</script>

</body>
</html>
