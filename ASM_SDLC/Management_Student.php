<?php
include 'db_Connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Thêm hoặc sửa sinh viên
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        // Thêm sinh viên mới
        $name = $_POST['name'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $mark = $_POST['mark'];
        $rank = $_POST['rank'];

        $conn->query("INSERT INTO students (name, age, gender, email, phone, mark, rank) VALUES ('$name', '$age', '$gender', '$email','$phone', '$mark', '$rank')");
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        // Sửa sinh viên
        $id = $_POST['student_id'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $mark = $_POST['mark'];
        $rank = $_POST['rank'];

        $conn->query("UPDATE students SET name='$name', age='$age', gender='$gender', email='$email', phone='$phone', mark='$mark', rank='$rank' WHERE student_id = $id");
    }
} elseif (isset($_GET['delete'])) {
    // Xóa sinh viên
    $id = $_GET['delete'];
    $conn->query("DELETE FROM students WHERE student_id = $id");
}

// Lấy danh sách sinh viên
$students = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management</title>
    <link rel="stylesheet" href="Management_Student.css">
</head>
<body>
<div class="container">
    <h2>Student List</h2>

    <!-- Form thêm và sửa sinh viên -->
    <form method="post" id="studentForm">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="student_id" id="student_id">
        
        <label>Name:</label><input type="text" name="name" id="name" required><br>
        <label>Age:</label><input type="number" name="age" id="age" required><br>
        <label>Gender:</label>
        <select name="gender" id="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>
        <label>Email:</label><input type="email" name="email" id="email" required><br>
        <label>Phone:</label><input type="tel" name="phone" id="phone" required><br>
        <label>Mark:</label><input type="number" step="0.1" name="mark" id="mark" required><br>
        <label>Rank:</label>
        <select name="rank" id="rank">
            <option value="Fail">Fail</option>
            <option value="Pass">Pass</option>
            <option value="Merit">Merit</option>
            <option value="Distinction">Distinction</option>
        </select><br>
        <button type="submit" id="submitButton">Add Student</button>
        
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Mark</th>
            <th>Rank</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $students->fetch_assoc()): ?>
            <tr>
                <td><?= $row['Student_ID'] ?></td>
                <td><?= $row['Name'] ?></td>
                <td><?= $row['Age'] ?></td>
                <td><?= $row['Gender'] ?></td>
                <td><?= $row['Email'] ?></td>
                <td><?= $row['Phone'] ?></td>
                <td><?= $row['Mark'] ?></td>
                <td><?= $row['Rank'] ?></td>
                <td>
                    <button onclick="editStudent(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>
                    <a href="?delete=<?= $row['Student_ID'] ?>"><button>Delete</button></a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
function editStudent(student) {
    document.getElementById('student_ID').value = student.student_ID;
    document.getElementById('name').value = student.name;
    document.getElementById('age').value = student.age;
    document.getElementById('gender').value = student.gender;
    document.getElementById('email').value = student.email;
    document.getElementById('phone').value = student.phone;
    document.getElementById('mark').value = student.mark;
    document.getElementById('rank').value = student.rank;
    document.getElementById('submitButton').textContent = 'Update Student';
    document.querySelector('[name="action"]').value = 'edit';
}
</script>
</body>
</html>
