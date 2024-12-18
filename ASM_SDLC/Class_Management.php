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

// Thêm lớp học
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_class'])) {
    $class_name = $_POST['class_name'];
    $class_description = $_POST['class_description'];
    $course_id = $_POST['course_id'];

    $sql = "INSERT INTO classes (class_name, class_description, course_id) VALUES (:class_name, :class_description, :course_id)";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            ':class_name' => $class_name,
            ':class_description' => $class_description,
            ':course_id' => $course_id,
        ]);
        echo "Class added successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Chỉnh sửa lớp học
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_class'])) {
    $class_id = $_POST['class_id'];
    $class_name = $_POST['class_name'];
    $class_description = $_POST['class_description'];
    $course_id = $_POST['course_id'];

    $sql = "UPDATE classes SET class_name = :class_name, class_description = :class_description, course_id = :course_id WHERE id = :class_id";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':class_name' => $class_name,
            ':class_description' => $class_description,
            ':course_id' => $course_id,
            ':class_id' => $class_id
        ]);
        echo "Class updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Xóa lớp học
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_class'])) {
    $class_id = $_POST['class_id'];
    $sql = "DELETE FROM classes WHERE id = :class_id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([':class_id' => $class_id]);
        echo "Class deleted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Lấy danh sách khóa học
$stmt = $pdo->query("SELECT id, course_name FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Management</title>

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

<!-- Thêm lớp học mới -->
<h2>Add Class</h2>
<form method="POST">
    <input type="text" name="class_name" placeholder="Class Name" required>
    <textarea name="class_description" placeholder="Class Description" required></textarea>
    <select name="course_id" required>
        <?php foreach ($courses as $course): ?>
            <option value="<?= $course['id'] ?>"><?= $course['course_name'] ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="add_class">Add Class</button>
</form>

<!-- Chỉnh sửa lớp học -->
<h2>Edit Class</h2>
<?php
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM classes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $edit_id]);
    $class = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($class) {
        ?>
        <form method="POST">
            <input type="hidden" name="class_id" value="<?= $class['id'] ?>">
            <input type="text" name="class_name" value="<?= $class['class_name'] ?>" required>
            <textarea name="class_description"><?= $class['class_description'] ?></textarea>
            <select name="course_id" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>" <?= $course['id'] == $class['course_id'] ? 'selected' : '' ?>><?= $course['course_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="update_class">Update Class</button>
        </form>
        <?php
    }
}
?>

<!-- Danh sách lớp học -->
<h2>Class List</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Class Name</th>
        <th>Class Description</th>
        <th>Course Name</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    <?php
    $sql = "SELECT c.id, c.class_name, c.class_description, c.created_at, co.course_name FROM classes c
            JOIN courses co ON c.course_id = co.id";
    $stmt = $pdo->query($sql);
    while ($class = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$class['id']}</td>";
        echo "<td>{$class['class_name']}</td>";
        echo "<td>{$class['class_description']}</td>";
        echo "<td>{$class['course_name']}</td>";
        echo "<td>{$class['created_at']}</td>";
        echo "<td>
                <a href='?edit_id={$class['id']}'>
                <button class='edit-btn'>Edit</button>
                </a> | 
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='class_id' value='{$class['id']}'>
                    <button type='submit' name='delete_class'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
