<?php
// Kết nối đến cơ sở dữ liệu
$host = 'localhost';
$dbname = 'SE06302';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
 
</head>
<body>
        <!-- Back to Home Button -->
<div style="margin-bottom: 20px;">
    <a href="Admin_Home.php">
        <button style="padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Back to Home
        </button>
    </a>
</div>

<!-- Add Attendance Form -->
<h2>Add Attendance</h2>
<form method="POST">
    <select name="student_id" required>
        <option value="">Select Student</option>
        <?php
        $stmt = $pdo->query("SELECT * FROM students");
        while ($student = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$student['Student_ID']}'>{$student['Name']}</option>";
        }
        ?>
    </select>
    <select name="class_id" required>
        <option value="">Select Class</option>
        <?php
        $stmt = $pdo->query("SELECT * FROM classes");
        while ($class = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$class['id']}'>{$class['class_name']}</option>";
        }
        ?>
    </select>
    <input type="date" name="date" required>
    <select name="status" required>
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
        <option value="Late">Late</option>
    </select>
    <button type="submit" name="add_attendance">Add Attendance</button>
</form>

<!-- Process Add Attendance -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_attendance'])) {
    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO attendance (student_id, class_id, date, status) VALUES (:student_id, :class_id, :date, :status)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':student_id' => $student_id,
            ':class_id' => $class_id,
            ':date' => $date,
            ':status' => $status,
        ]);
        echo "Attendance added successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Edit Attendance Form -->
<h2>Edit Attendance</h2>
<?php
// Process Edit Attendance
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM attendance WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $edit_id]);
    $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($attendance) {
        ?>
        <form method="POST">
            <input type="hidden" name="attendance_id" value="<?= $attendance['id'] ?>">
            <select name="student_id" required>
                <option value="">Select Student</option>
                <?php
                $stmt = $pdo->query("SELECT * FROM students");
                while ($student = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$student['Student_ID']}' " . ($attendance['student_id'] == $student['Student_ID'] ? 'selected' : '') . ">{$student['Name']}</option>";
                }
                ?>
            </select>
            <select name="class_id" required>
                <option value="">Select Class</option>
                <?php
                $stmt = $pdo->query("SELECT * FROM classes");
                while ($class = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$class['id']}' " . ($attendance['class_id'] == $class['id'] ? 'selected' : '') . ">{$class['class_name']}</option>";
                }
                ?>
            </select>
            <input type="date" name="date" value="<?= $attendance['date'] ?>" required>
            <select name="status" required>
                <option value="Present" <?= $attendance['status'] == 'Present' ? 'selected' : '' ?>>Present</option>
                <option value="Absent" <?= $attendance['status'] == 'Absent' ? 'selected' : '' ?>>Absent</option>
                <option value="Late" <?= $attendance['status'] == 'Late' ? 'selected' : '' ?>>Late</option>
            </select>
            <button type="submit" name="update_attendance">Update Attendance</button>
        </form>
        <?php
    }
}

// Handle Update Attendance
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_attendance'])) {
    $attendance_id = $_POST['attendance_id'];
    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $sql = "UPDATE attendance SET student_id = :student_id, class_id = :class_id, date = :date, status = :status WHERE id = :attendance_id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':student_id' => $student_id,
            ':class_id' => $class_id,
            ':date' => $date,
            ':status' => $status,
            ':attendance_id' => $attendance_id,
        ]);
        echo "Attendance updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- List of Attendance -->
<h2>Attendance List</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Class</th>
        <th>Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php
    $sql = "SELECT attendance.id, students.Name as student_name, classes.class_name, attendance.date, attendance.status 
            FROM attendance 
            JOIN students ON attendance.student_id = students.Student_ID 
            JOIN classes ON attendance.class_id = classes.id";
    $stmt = $pdo->query($sql);
    while ($attendance = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$attendance['id']}</td>";
        echo "<td>{$attendance['student_name']}</td>";
        echo "<td>{$attendance['class_name']}</td>";
        echo "<td>{$attendance['date']}</td>";
        echo "<td>{$attendance['status']}</td>";
        echo "<td>
                <a href='?edit_id={$attendance['id']}'>
                <button class='edit-btn'>Edit</button>
                </a> | 
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='attendance_id' value='{$attendance['id']}'>
                    <button type='submit' name='delete_attendance' class = 'delete-btn'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }
    ?>
</table>

<!-- Process Delete Attendance -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_attendance'])) {
    $attendance_id = $_POST['attendance_id'];
    $sql = "DELETE FROM attendance WHERE id = :attendance_id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([':attendance_id' => $attendance_id]);
        echo "Attendance deleted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

</body>
</html>
